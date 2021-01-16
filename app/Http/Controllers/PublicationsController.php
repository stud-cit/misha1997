<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Publications;
use App\Models\AuthorsPublications;
use App\Models\Authors;
use App\Models\Countries;
use App\Models\PublicationType;
use App\Models\Notifications;

class PublicationsController extends ASUController
{
    // всі назви поблікацій
    function getAllNames() {
        $data = Publications::select('title', 'publication_type_id')->with('publicationType')->get();
        return response()->json($data);
    }

    function checkPublication($id) {
        $model = Publications::with('authors.author')->whereHas('authors.author', function($q) use ($request) {
            $q->whereIn('id', $request->session()->get('person')['id']);
        })->where('id', $id)->exists();
        if($model) {
            return response()->json([
                "status" => "ok"
            ]);
        } else {
            return response()->json([
                "status" => "error"
            ]);
        }
    }

    // всі публікації
    function getAll(Request $request, $author_id = null) {
        $divisions = $this->getDivisions();
        $model = Publications::with('publicationType', 'scienceType', 'authors.author')->orderBy('created_at', 'DESC');

        if(!$author_id) {
            if($request->session()->get('person')['roles_id'] == 2) {
                $departments_id = [$request->session()->get('person')['department_code']];
                foreach($divisions->original['department'] as $k2 => $v2) {
                    if ($v2['ID_PAR'] == $request->session()->get('person')['department_code']) {
                        array_push($departments_id, $v2['ID_DIV']);
                    }
                }
                $model->whereHas('authors.author', function($q) use ($departments_id) {
                    $q->whereIn('department_code', $departments_id);
                });
            } else {
                if($request->session()->get('person')['roles_id'] == 3) {
                    $departments_id = [$request->session()->get('person')['faculty_code']];
                    foreach($divisions->original['department'] as $k2 => $v2) {
                        if ($v2['ID_PAR'] == $request->session()->get('person')['faculty_code']) {
                            array_push($departments_id, $v2['ID_DIV']);
                        }
                    }
                    $model->whereHas('authors.author', function($q) use ($departments_id) {
                        $q->whereIn('faculty_code', $departments_id);
                    });
                }
            }
        }

        if($author_id) {
            $model->whereHas('authors', function($query) use ($request, $author_id) {
                $query->whereHas('author', function($query) use ($request, $author_id) {
                    $query->where('id', $author_id);
                });
                if($request->withSupervisor == "false") {
                    $query->where('supervisor', '!=', 1);
                }
            });
        }

        // Під керівництвом
        if($request->hasSupervisor == "true") {
            $model->whereHas('authors', function($q) {
                $q->where('supervisor', 1);
            });
        }

        // Назва публікації
        if($request->title) {
            $model->where('title', 'like', "%".$request->title."%");
        }

        // ПІБ автора
        if($request->authors_f) {
            $model->whereHas('authors.author', function($q) use ($request) {
                $q->where('name', 'like', "%".$request->authors_f."%");
            });
        }

        // БД Scopus/WoS
        if($request->science_type_id) {
            $model->where('science_type_id', $request->science_type_id);
        }

        // Рік видання
        if($request->years) {
            $model->whereIn('year', $request->years);
        }

        // Країна видання
        if($request->country) {
            $model->where('country', 'like', "%".$request->country."%");
        }

        // Вид публікації
        if($request->publication_type_id) {
            $model->where('publication_type_id', $request->publication_type_id);
        }

        // Публікації які не враховані в рейтингу попереднього року
        if($request->notPreviousYear == "true") {
            $model->where('not_previous_year', 1);
        }

        // Публікації які не враховані в рейтингу цього року
        if($request->notThisYear == "true") {
            $model->where('not_this_year', 1);
        }

        if($request->department_code != '') {
            $departments_id = [$request->department_code];
            foreach($divisions->original['department'] as $k2 => $v2) {
                if ($v2['ID_PAR'] == $request->department_code) {
                    array_push($departments_id, $v2['ID_DIV']);
                }
            }
            $model->whereHas('authors.author', function($q) use ($departments_id) {
                $q->whereIn('department_code', $departments_id);
            });
        } else {
            if($request->faculty_code != '') {
                $departments_id = [$request->faculty_code];
                foreach($divisions->original['department'] as $k2 => $v2) {
                    if ($v2['ID_PAR'] == $request->faculty_code) {
                        array_push($departments_id, $v2['ID_DIV']);
                    }
                }
                $model->whereHas('authors.author', function($q) use ($departments_id) {
                    $q->whereIn('faculty_code', $departments_id);
                });
            }
        }

        $publications = $model->paginate($request->size);

        return response()->json([
            "currentPage" => $publications->currentPage(),
            "firstItem" => $publications->firstItem(),
            "count" => $publications->total(),
            "publications" => $publications
        ]);
    }

    // публікація по ID
    function getId($id) {
        $divisions = $this->getDivisions();
        $data = Publications::with('publicationType', 'scienceType', 'authors.author', 'publicationAdd', 'publicationEdit')->find($id);
        foreach ($data->authors as $key => $value) {
            foreach($divisions->original['department']  as $k => $v) {
                if ($value['author']['department_code'] == $v['ID_DIV']) {
                    $value['author']['department'] = $v['NAME_DIV'];
                }
            }
            foreach($divisions->original['institute'] as $k => $v) {
                if ($value['author']['faculty_code'] == $v['ID_DIV']) {
                    $value['author']['faculty'] = $v['NAME_DIV'];
                }
            }
        }
        return response()->json($data);
    }

    // додавання публікації
    function post(Request $request) {
        $modelPublications = new Publications();
        $dataPublications = $request->all();
        $dataPublications['publication_type_id'] = $dataPublications['publication_type']['id'];
        $dataPublications['add_user_id'] = $request->session()->get('person')['id'];
        $response = $modelPublications->create($dataPublications);

        foreach ($request->authors as $key => $value) {
            $authorsPublications = new AuthorsPublications;
            $authorsPublications->autors_id = $value['id'];
            $authorsPublications->publications_id = $response->id;
            $authorsPublications->save();
            if($value['id'] != $request->session()->get('person')['id']) {
                Notifications::create([
                    "autors_id" => $value['id'],
                    "text" => "Користувач <a href=\"/user/" . $request->session()->get('person')['id'] . "\">" . $request->session()->get('person')['name'] . "</a> додав публікацію <a href=\"/publications/".$response['id']."\">\"".$response['title']."\"</a> і відзначив Вас співавтором публікації."
                ]);
            }
        }
        if($dataPublications['supervisor']) {
            $authorsPublications = new AuthorsPublications;
            $authorsPublications->autors_id = $dataPublications['supervisor']['id'];
            $authorsPublications->publications_id = $response->id;
            $authorsPublications->supervisor = true;
            $authorsPublications->save();
            if($dataPublications['supervisor']['id'] != $request->session()->get('person')['id']) {
                Notifications::create([
                    "autors_id" => $dataPublications['supervisor']['id'],
                    "text" => "Користувач <a href=\"/user/" . $request->session()->get('person')['id'] . "\">" . $request->session()->get('person')['name'] . "</a> додав публікацію <a href=\"/publications/".$response['id']."\">\"".$response['title']."\"</a> і відзначив Вас співавтором публікації."
                ]);
            }
        }
        return response('ok', 200);
    }

    // оновлення публікації
    function updatePublication(Request $request, $id) {
        $data = $request->all();
        $model = Publications::with('authors', 'publicationType')->find($id);
        $data['edit_user_id'] = $request->session()->get('person')['id'];
        $notificationText = "";

        // check authors old or new
        $oldAuthors = [];
        foreach ($model->authors as $key => $value) {
            if($value['supervisor'] == 0) {
                array_push($oldAuthors, $value['autors_id']);
            }
        }

        foreach ($data['authors'] as $key => $value) {
            if(AuthorsPublications::where('autors_id', $value['id'])->where('publications_id', $id)->exists()) {
                unset($oldAuthors[array_search($value['id'], $oldAuthors)]);
            } else {
                $authorsPublications = new AuthorsPublications;
                $authorsPublications->autors_id = $value['id'];
                $authorsPublications->publications_id = $id;
                $authorsPublications->save();
                $notificationText .= "додано автора: <a href=\"/user/". $value['id'] ."\">" . $value['name'] . "</a>;<br>";
            }
        }

        foreach ($oldAuthors as $key => $value) {
            AuthorsPublications::where('autors_id', $value)->where('publications_id', $id)->delete();
            $notificationText .= "видалено автора: " . $this->test($value) . ";<br>";
        }
        // end check

        // check supervisor
        if($data['supervisor']) {
            if(AuthorsPublications::where('publications_id', $id)->where('supervisor', 1)->exists()) {

                if(!AuthorsPublications::where('autors_id', $data['supervisor']['id'])->where('publications_id', $id)->where('supervisor', 1)->exists()) {
                    AuthorsPublications::where('publications_id', $id)->where('supervisor', 1)->update([
                        "autors_id" => $data['supervisor']['id']
                    ]);

                    $notificationText .= "змінено керівника: <a href=\"/user/". $data['old_supervisor']['id'] ."\">" . $data['old_supervisor']['name'] . "</a> на <a href=\"/user/". $data['supervisor']['id'] ."\">" . $data['supervisor']['name'] . "</a>;<br>";
                }
            } else {
                $authorsPublications = new AuthorsPublications;
                $authorsPublications->autors_id = $data['supervisor']['id'];
                $authorsPublications->publications_id = $id;
                $authorsPublications->supervisor = 1;
                $authorsPublications->save();
                $notificationText .= "додано керівника: <a href=\"/user/". $data['supervisor']['id'] ."\">" . $data['supervisor']['name'] . "</a>;<br>";
            }
        } else {
            if($data['old_supervisor']) {
                AuthorsPublications::where('publications_id', $id)->where('supervisor', 1)->delete();
                $notificationText .= "видалено керівника: <a href=\"/user/". $data['old_supervisor']['id'] ."\">" . $data['old_supervisor']['name'] . "</a>;<br>";
            }
        }
        // end check



        // перевірка полів
        // Назва
        $notificationText .= $this->notification($data, $model, "title", "назву");

        // БД Scopus\WoS
        $science_type = [
            "1" => "Scopus",
            "2" => "WoS",
            "3" => "Scopus та WoS"
        ];
        $notificationText .= $this->notification($data, $model, "science_type_id", "базу даних", $science_type);

        // тип публікації
        if($data['publication_type']['id'] != $model->publication_type_id) {
            $data['publication_type_id'] = $data['publication_type']['id'];
            $notificationText .= "змінено тип публікації: " . $model->publicationType['title'] . " на " . $data['publication_type']['title'] . ";<br>";
        }

        $notificationText .= $this->notification($data, $model, "snip", "SNIP");
        $notificationText .= $this->notification($data, $model, "quartil_scopus", "квартиль Scopus");
        $notificationText .= $this->notification($data, $model, "quartil_wos", "квартиль WoS");
        $notificationText .= $this->notification($data, $model, "impact_factor", "Impact Factor");
        $notificationText .= $this->notification($data, $model, "year", "рік видання");
        $notificationText .= $this->notification($data, $model, "number", "номер (том)");
        $notificationText .= $this->notification($data, $model, "pages", "сторінки");
        $notificationText .= $this->notification($data, $model, "country", "країна");
        $notificationText .= $this->notification($data, $model, "number_volumes", "кількість томів");
        $notificationText .= $this->notification($data, $model, "by_editing", "за редакцією");
        $notificationText .= $this->notification($data, $model, "city", "місто");
        $notificationText .= $this->notification($data, $model, "editor_name", "назву редакції");
        $notificationText .= $this->notification($data, $model, "number_certificate", "номер");
        $notificationText .= $this->notification($data, $model, "applicant", "заявника");
        $notificationText .= $this->notification($data, $model, "date_application", "дата подачі");
        $notificationText .= $this->notification($data, $model, "date_publication", "дата публікації");
        $commerc_university = [
            "1" => "Так",
            "0" => "Ні"
        ];
        $notificationText .= $this->notification($data, $model, "commerc_university", "комерціалізовано університетом", $commerc_university);
        $commerc_employees = [
            "1" => "Так",
            "0" => "Ні"
        ];
        $notificationText .= $this->notification($data, $model, "commerc_employees", "Комерціалізовано штатними співробітниками університету", $commerc_employees);
        $access_mode = [
            "1" => "Відкритий",
            "0" => "Закрити"
        ];
        $notificationText .= $this->notification($data, $model, "access_mode", "Режим доступу", $access_mode);
        $notificationText .= $this->notification($data, $model, "application_number", "№ заявки");
        $notificationText .= $this->notification($data, $model, "newsletter_number", "№ бюлетеня");
        $notificationText .= $this->notification($data, $model, "name_conference", "Назва конференції");
        $notificationText .= $this->notification($data, $model, "url", "Посилання");
        $notificationText .= $this->notification($data, $model, "name_magazine", "Назва журналу");
        $notificationText .= $this->notification($data, $model, "doi", "DOI");
        $nature_index = [
            "1" => "Так",
            "2" => "Ні"
        ];
        $notificationText .= $this->notification($data, $model, "nature_index", "Natire Index", $nature_index);
        $notificationText .= $this->notification($data, $model, "nature_science", "журнал");

        // Підбаза WoS
        $sub_db_scie = [
            "1" => "Так",
            "0" => "Ні",
        ];

        $sub_db_ssci = [
            "1" => "Так",
            "0" => "Ні",
        ];
        $notificationText .= $this->notification($data, $model, "sub_db_scie", "Підбаза WoS - SCIE", $sub_db_scie);
        $notificationText .= $this->notification($data, $model, "sub_db_ssci", "підбазу WoS - SSCI", $sub_db_ssci);

        if($notificationText != "") {
            $notificationText = "Користувач <a href=\"/user/". $request->session()->get('person')['id'] ."\">" . $request->session()->get('person')['name'] . "</a> вніс наступні зміни в публікацію <a href=\"/publications/". $id ."\">" . $model->title . "</a>:<br>" . $notificationText;
            foreach ($data['authors'] as $k => $author) {
                if($author['id'] != $request->session()->get('person')['id']) {
                    Notifications::create([
                        "autors_id" => $author['id'],
                        "text" => $notificationText
                    ]);
                }
            }
        }

        $model->update($data);

//        return response('ok', 200);
    }

    function test($id) {
        $result = Authors::select('name')->find($id);
        return $result['name'];
    }

    function notification($data, $model, $key, $text, $arr = null) {
        if($arr) {
            if($data[$key] && !$model->$key) {
                return "додано ".$text.": " . $arr[$data[$key]] . ";<br>";
            }
            if(($data[$key] != $model->$key) && $data[$key] && $model->$key) {
                return "змінено ".$text.": " . $arr[$model->$key] . " на " . $arr[$data[$key]] . ";<br>";
            }
            if(!$data[$key] && $model->$key) {
                return "видалено ".$text.";<br>";
            }
        } else {
            if($data[$key] && !$model->$key) {
                return "додано ".$text.": " . $data[$key] . ";<br>";
            }
            if(($data[$key] != $model->$key) && $data[$key] && $model->$key) {
                return "змінено ".$text.": " . $model->$key . " на " . $data[$key] . ";<br>";
            }
            if(!$data[$key] && $model->$key) {
                return "видалено ".$text.";<br>";
            }
        }
    }

    // видалення публікацій
    function deletePublications(Request $request) {
        foreach ($request->publications as $key => $publication) {
            foreach ($publication['authors'] as $k => $author) {
                if($author['author']['id'] != $request->session()->get('person')['id']) {
                    Notifications::create([
                        "autors_id" => $author['author']['id'],
                        "text" => "Користувач <a href=\"/user/" . $request->user['id'] . "\">" . $request->user['name'] . "</a> видалив Вашу публікацію \"".$publication['title']."\"."
                    ]);
                }
            }
            Publications::find($publication['id'])->delete();
        }
        return response('ok', 200);
    }

    // видалення публікації
    function deletePublication(Request $request, $id) {
        foreach ($request->publication['authors'] as $k => $author) {
            if($author['id'] != $request->session()->get('person')['id']) {
                Notifications::create([
                    "autors_id" => $author['id'],
                    "text" => "Користувач <a href=\"/user/" . $request->user['id'] . "\">" . $request->user['name'] . "</a> видалив Вашу публікацію \"".$request->publication['title']."\"."
                ]);
            }
        }
        Publications::find($id)->delete();
        return response('ok', 200);
    }

    // список країн
    function getCountry() {
        $data = Countries::select('name')->get()->toArray();
        return response()->json(array_column($data, 'name'));
    }

    // список типів публікацій
    function typePublications() {
        $data = PublicationType::get();
        return response()->json($data);
    }

    // рейтингові показники
    function export(Request $request) {
        $divisions = $this->getDivisions();
        $model = Publications::with('authors.author', 'publicationType', 'scienceType')->orderBy('created_at', 'DESC');

        // Країна видання
        $model->where('country', 'like', "%".$request->country."%");

        $department_code = $request->department_code;
        $faculty_code = $request->faculty_code;

        if($request->session()->get('person')['roles_id'] == 2) {
            $faculty_code = $request->session()->get('person')['faculty_code'];
            $department_code = $request->session()->get('person')['department_code'];
        }

        if($request->session()->get('person')['roles_id'] == 3) {
            $department_code = $request->session()->get('person')['faculty_code'];
        }

        $todayYear = Carbon::today()->year;

        // Вид публікацій
        if(count($request->publication_types) > 0) {
            $model->whereIn('publication_type_id', array_column($request->publication_types, 'id'));
        }

        // Публікації які не враховані в рейтингу попереднього року
        if($request->not_previous_year == "true") {
            $years = $request->years;
            unset($years[array_search($request->reporting_year, $years)]);
            $model->where(function($query) use ($request, $years) {
                $query->where(function($q) use ($request, $years) {
                    $q->where('not_previous_year', 1)->whereIn('year', $years);
                })->orWhere(function($q) use ($request) {
                    $q->where('not_previous_year', 0)->where('year', $request->reporting_year);
                });
            });
        } else {
            // Рік видання
            if($request->years) {
                $model->whereIn('year', $request->years);
            }
        }

        // Публікації які не враховані в рейтингу цього року
        if($request->not_this_year == "true") {
            $years = $request->years;
            unset($years[array_search($request->reporting_year, $years)]);
            $model->where(function($query) use ($request, $years) {
                $query->where(function($q) use ($request, $years) {
                    $q->where('not_this_year', 1)->whereIn('year', $years);
                })->orWhere(function($q) use ($request) {
                    $q->where('not_this_year', 0)->where('year', $request->reporting_year);
                });
            });
        } else {
            // Рік видання
            if($request->years) {
                $model->whereIn('year', $request->years);
            }
        }

        // Рік занесення до бази даних
        if($request->year_db) {
            $model->where(function($query) use ($request) {
                foreach ($request->year_db as $keyYear => $year) {
                    $query->orWhereYear('created_at', $year);
                }
            });
        }

        if($request->doi != "") {
            // Публікації з цифровим ідентифікатором DOI
            if($request->doi == 1) {
                $model->whereNotNull('doi');
            }
            // Публікації без цифрового ідентифікатора DOI
            if($request->doi == 0) {
                $model->whereNull('doi');
            }
        }

        if($request->applicant != "") {
            if($request->applicant == "СумДУ") {
                $model->where('applicant', 'СумДУ'); // Охоронні документи СумДУ
            }
            if($request->applicant != "СумДУ") {
                $model->where('applicant', '!=', 'СумДУ'); // Охоронні документи не СумДУ
            }
        }

        if($request->commercial_applicant != "") {
            if($request->commercial_applicant == 1) {
                $model->where('commerc_university', 1); // Комерціалізовані охоронні документи (Університетом)
            }
            if($request->commercial_applicant == 2) {
                $model->where('commerc_employees', 1); // Комерціалізовані охоронні документи (Штатними співробітниками)
            }
        }

        // Індексування БД Scopus/WoS
        if(count($request->science_types) > 0) {
            $model->whereIn('science_type_id', array_column($request->science_types, 'id'));
        }

        if(($request->quartil_scopus != "" || $request->quartil_wos != "") && ($request->quartil_scopus != $request->quartil_wos)) {
            if($request->quartil_scopus) {
                $model->where('quartil_scopus', $request->quartil_scopus); // Квартиль журналу SCOPUS
            }
            if($request->quartil_wos) {
                $model->where('quartil_wos', $request->quartil_wos); // Квартиль журналу WOS
            }
        }

        if($request->snip) {
            $model->where('snip', '>=', 1); // Публікації опубліковані у виданнях з показником SNIP більше ніж 1,0
        }

        if($request->abroad == 1) {
            $model->where('country', '!=', 'Україна'); // Публікації опубліковані за кордоном
        }

        if($request->withStudents) { // Публікації за авторством та співавторством студентів
            $model->whereHas('authors.author', function($q) {
                $q->where('categ_1', 1);
            });
        }

        if($department_code != '') {
            $model->whereHas('authors.author', function($q) use ($department_code) {
                $q->where('department_code', $department_code);
            });
        } else {
            if($faculty_code != '') {
                $model->whereHas('authors.author', function($q) use ($faculty_code) {
                    $q->where('faculty_code', $faculty_code);
                });
            }
        }

        if($request->withForeigners != "") { // Публікації у співавторстві з іноземними партнерами
            if($request->withForeigners == 1) {
                $model->whereHas('authors.author', function($q) {
                    $q->where('country', '!=', "Україна");
                });
            }
            if($request->withForeigners == 0) {
                $query2 = clone $model;
                $query2->whereHas('authors.author', function($q) {
                    $q->where('country', '!=', "Україна");
                });
                $ids = $query2->pluck('id')->toArray();
                $model->whereNotIn('id', $ids);
            }
            if($request->withForeigners == 10) {
                $model->whereHas('authors.author', function($q) {
                    $q->where('country', '!=', "Україна")->where(function($q2) {
                        $q2->where('h_index', '>=', 10)->orWhere('scopus_autor_id', '>=', 10);
                    });
                });
            }
        }

        if($request->other_organization) { // Публікації за співавторством з представниками інших організацій
            $model->whereHas('authors.author', function($q) {
                $q->where('job', '!=', 'СумДУ')->where('job', '!=', 'Не працює');
            });
        }

        if($request->scie != "") { // Статті у виданнях, які входять до підбази WOS - SCIE
            if($request->scie == 1) {
                $model->where('sub_db_scie', 1);
            }
            if($request->scie == 0) {
                $model->where('sub_db_scie', 0);
            }
        }

        if($request->ssci != "") { // Статті у виданнях, які входять до підбази WOS - SSCI
            if($request->ssci == 1) {
                $model->where('sub_db_ssci', 1);
            }
            if($request->ssci == 0) {
                $model->where('sub_db_ssci',  0);
            }
        }

        $data = $model->get();

        if($request->quartil_scopus && $request->quartil_wos && ($request->quartil_scopus == $request->quartil_wos)) {
            foreach ($data as $key => $value) {
                if($request->quartil_scopus != min($value['quartil_scopus'], $value['quartil_wos'])) {
                    unset($data[$key]);
                }
            }
        }

        $rating = [
            //Кількість статей за авторством та співавторством студентів
            "studentPublications" => 0,
            //Кількість статей та монографій (розділів) у співавторстві з іноземними партнерами, які мають індекс Гірша за БД Scopus або WoS не нижче 10
            "foreignPublications" => 0,
            //Кількість публікацій всього у тому числі
            "publications" => 0,
            //монографії проіндексовані БД Scopus або WoS за належності до профілю СумДУ
            "monographsIndexedScopusOrWoSNotSSU" => 0,
            //статей у фахових за статусом виданнях
            "articleProfessionalPublicationsUkraine" => 0,
            //які опубліковані у виданнях, що індексуються БД Scopus та/або WoS за умови належності до профілю СумДУ
            "publicationsScopusWoSProfileSSU" => [
                // всього за звітний рік
                "countReportingYear" => [
                    // за БД Scopus та WoS:
                    "ScopusAndWoS" => [
                        "rating" => 0,
                        "count" => 0
                    ],
                    // за БД Scopus або WoS:
                    "ScopusOrWoS" => [
                        "rating" => 0,
                        "count" => 0
                    ]
                ],
                 // у виданні, яке відноситься до квартиля Q1
                "quartile1" => [
                    "rating" => 0,
                    "count" => 0
                ],
                // у виданні, яке відноситься до квартиля Q2
                "quartile2" => [
                    "rating" => 0,
                    "count" => 0
                ],
                // у виданні, яке відноситься до квартиля Q3
                "quartile3" => [
                    "rating" => 0,
                    "count" => 0
                ],
                // у виданні, яке відноситься до квартиля Q4
                "quartile4" => [
                    "rating" => 0,
                    "count" => 0
                ],
                // за БД WoS
                "articleWoS" => [
                    // статті у виданнях, які входять до SCIE
                    "scie" => [
                        "rating" => 0,
                        "count" => 0
                    ],
                    // статті у виданнях, які входять до SSCI
                    "ssci" => [
                        "rating" => 0,
                        "count" => 0
                    ]
                ],
                // які обліковуються рейтингом Nature Index
                "accountedNatureIndex" => [
                    "rating" => 0,
                    "count" => 0
                ],
                // у журналах Nature або Scince
                "journalsNatureOrScience" => [
                    "rating" => 0,
                    "count" => 0
                ],
                // за співавторством з представниками інших організацій
                "authorsOtherOrganizations" => [
                    "rating" => 0,
                    "count" => 0
                ],
                // що входять до списків Forbes та Fortune
                "authorsInForbesFortune" => [
                    "rating" => 0,
                    "count" => 0
                ],
                // які увійшли до найбільш цитованих для своєї предметної галузі
                "enteredMostCitedSubjectArea" => [
                    // до 10% за БД Scopus
                    "scopus" => [
                        "rating" => 0,
                        "count" => 0
                    ],
                    // До 1% за БД WoS
                    "wos" => [
                        "rating" => 0,
                        "count" => 0
                    ]
                ],
                // з цифровим ідентифікатором DOI
                "countDOI" => [
                    "rating" => 0,
                    "count" => 0
                ],
                // які процитовані у міжнародних патентах
                "citedInternationalPatents" => [
                    "rating" => 0,
                    "count" => 0
                ],
                // всього за 5 років за БД Scopus
                "countScopusFiveYear" => [
                    "rating" => 0,
                    "count" => 0
                ],
            ],
            // Кількість охоронних документів щодо об'єктів права інтелектуальної власності
            "numberSecurityDocuments" => [
                // отримано за звітний рік на ім'я СумДУ
                "receivedReportingNameSSU" => [
                    "rating" => 0,
                    "count" => 0
                ],
                // з них за сумісним авторством з представниками бізнесу
                "authorshipBusinessRepresentatives" => [
                    "rating" => 0,
                    "count" => 0
                ],
                // отримано за звітний рік штатними співробітниками не на ім'я СумДУ
                "receivedReportingEmployeesNotSSU" => [
                    "rating" => 0,
                    "count" => 0
                ],
                // комерціалізовано у звітному році
                "commercializedReportingYear" => [
                    // університетом
                    "university" => [
                        "rating" => 0,
                        "count" => 0
                    ],
                    // штатним співробітником
                    "employee" => [
                        "rating" => 0,
                        "count" => 0
                    ]
                ]
            ],
            // Кількість публікацій у виданнях з показником SNIP більше ніж 1,0 за БД Scopus
            "countSnipScopus" => 0,
            //Загальне значення індексів Гірша (за БД Scopus  або БД WoS ) штатних працівників та докторантів (динаміка змін)
            "countHirschIndex" => 0,
            "countHirschIndexWithoutCitations" => 0,
            "these" => [
                "count" => 0, // Кількість тез всього
                "publishedAbroad" => 0, // Тез опублікованих за кордоном
                "publishedWithStudents" => 0, // Тез опублікованих зі студентами
                "publishedWithForeignPartners" => 0 // Тез опублікованих з іноземними партнерами
            ],
            "articles" => [
                "count" => 0, // Кількість статей (всього)
                "publishedAbroad" => 0, // статей опублікованих за кордоном
                "publishedWithForeignPartners" => 0 // статей з іноземними партнерами
            ],
            "authorsHasfivePublications" => 0, // Чисельність штатних працівників, які мають не менше 5-ти публікацій у виданнях, що  індексуються БД Scopus та/або WoS (динаміка змін)
        ];

        $authors = [];

        foreach ($data as $key => $value) {
            $withStudent = 0;
            $foreignPublications = 0;
            $monographsIndexedScopusOrWoSNotSSU = 0;
            $articleProfessionalPublicationsUkraine = 0;

            $authorsOtherOrganizations = false;
            $receivedReportingEmployeesNotSSU = false;
            $authorsInForbesFortune = 0;
            $these = [
                "count" => 0,
                "publishedAbroad" => 0,
                "publishedWithStudents" => 0,
                "publishedWithForeignPartners" => 0
            ];
            $articles = [
                "count" => 0,
                "publishedAbroad" => 0,
                "publishedWithForeignPartners" => 0
            ];

            $testFacultys = [];
            $testFaculty = [];
            $testDepartment = [];
            $testDepartments = [];

            for($i = 0; $i < count($value['authors']); $i++) {

                array_push($testFacultys, $value['authors'][$i]['author']['faculty_code']);
                if($value['authors'][$i]['author']['faculty_code'] && $value['authors'][$i]['author']['categ_1'] != 1) {
                    array_push($testFaculty, $value['authors'][$i]['author']['faculty_code']);
                }

                array_push($testDepartments, $value['authors'][$i]['author']['department_code']);
                if($value['authors'][$i]['author']['department_code'] && $value['authors'][$i]['author']['categ_1'] != 1) {
                    array_push($testDepartment, $value['authors'][$i]['author']['department_code']);
                }
            }

            foreach ($value['authors'] as $k => $v) {

                if($v['author']['faculty_code'] && $v['author']['categ_1'] != 1) {
                    $res = array_filter($testFaculty, function($value) use ($v) {
                        return $value == $v['author']['faculty_code'];
                    });
                    $result = 1 / count($testFaculty) * count($res);
                    $value['authors'][array_search($v['author']['faculty_code'], $testFacultys)]['rating_faculty'] = round($result, 2);
                }

                if($v['author']['department_code'] && $v['author']['categ_1'] != 1) {
                    $res = array_filter($testDepartment, function($value) use ($v) {
                        return $value == $v['author']['department_code'];
                    });
                    $result = 1 / count($testDepartment) * count($res);
                    $v['test_department'] = $res;
                    $value['authors'][array_search($v['author']['department_code'], $testDepartments)]['rating_department'] = round($result, 2);
                }

                if(!in_array($v['author'], $authors) && $v['author']['categ_1'] != 2) {
                    array_push($authors, $v['author']);
                }
                // Кафедра - автора
                foreach($divisions->original['department'] as $keyDepartment => $department) {
                    if ($v['author']['department_code'] == $department['ID_DIV']) {
                        $v['author']['department'] = $department['ABBR_DIV'];
                    }
                }

                // Інcтитут / факультет - автора
                foreach($divisions->original['institute'] as $keyInstitute => $institute) {
                    if ($v['author']['faculty_code'] == $institute['ID_DIV']) {

                        $v['author']['faculty'] = $institute['ABBR_DIV'];

                    }
                }

                //Кількість статей за авторством та співавторством студентів
                if(($value['publication_type_id'] == 1 || $value['publication_type_id'] == 2 || $value['publication_type_id'] == 3 || $value['publication_type_id'] == 8) && ($v['author']['categ_1'] == 1 || $v['author']['categ_1'] == 3)) {
                    $withStudent = 1;
                }

                //Кількість статей та монографій (розділів) у співавторстві з іноземними партнерами, які мають індекс Гірша за БД Scopus або WoS не нижче 10
                if(($value['publication_type_id'] == 1 || $value['publication_type_id'] == 2 || $value['publication_type_id'] == 3 || $value['publication_type_id'] == 6 || $value['publication_type_id'] == 7) && $v['author']['country'] != "Україна" && ($v['author']['h_index'] >= 10 || $v['author']['scopus_autor_id'] >= 10)) {
                    $foreignPublications = 1;
                }

                //монографії проіндексовані БД Scopus або WoS за належності до профілю СумДУ
                if($value['publication_type_id'] == 6 && ($value['science_type_id'] == 1 || $value['science_type_id'] == 2) && $v['author']['guid']) {
                    $monographsIndexedScopusOrWoSNotSSU = 1;
                }

                //статей у фахових за статусом виданнях
                if($value['publication_type_id'] == 1) {
                    $articleProfessionalPublicationsUkraine = 1;
                }

                //за співавторством з представниками інших організацій
                if($value['science_type_id'] != null && $v['author']['job'] != "СумДУ" && $v['author']['job'] != "СумДУ (Не працює)" && $v['author']['job'] != "Не працює" && $v['author']['guid'] == null) {
                    $authorsOtherOrganizations = true;
                }

                // що входять до списків Forbes та Fortune
                if($v['author']['forbes_fortune']) {
                    $rating["publicationsScopusWoSProfileSSU"]['authorsInForbesFortune']['rating'] += $this->sumRating($request, $value);
                    $authorsInForbesFortune = 1;
                }

                // Кількість тез всього
                if($value['publication_type_id'] == 9) {
                    $these['count'] = 1;
                    // Тез опублікованих за кордоном
                    if($value['country'] != "Україна") {
                        $these['publishedAbroad'] = 1;
                    }
                    // Тез опублікованих з іноземними партнерами
                    if($v['author']['country'] != "Україна") {
                        $these['publishedWithForeignPartners'] = 1;
                    }
                    // Тез опублікованих зі студентами
                    if($v['author']['categ_1'] == 1 || $v['author']['categ_1'] == 3) {
                        $these['publishedWithStudents'] = 1;
                    }
                }

                // Кількість статей (всього), з них:
                if($value['publication_type_id'] == 1 || $value['publication_type_id'] == 2 || $value['publication_type_id'] == 3 || $value['publication_type_id'] == 7 || $value['publication_type_id'] == 8) {
                    $articles['count'] = 1;
                    // Статті опубліковані за кордоном
                    if($value['country'] != "Україна") {
                        $articles['publishedAbroad'] = 1;
                    }
                    // Статті опубліковані з іноземними партнерами
                    if($v['author']['country'] != "Україна") {
                        $articles['publishedWithForeignPartners'] = 1;
                    }
                }

                // отримано за звітний рік штатними співробітниками не на ім'я СумДУ
                if($value['publication_type_id'] == 10 && $value['applicant'] != 'СумДУ' && $v['author']['job'] == 'СумДУ') {
                    $receivedReportingEmployeesNotSSU = true;
                }
            }

            //які опубліковані у виданнях, що індексуються БД Scopus та/або WoS за умови належності до профілю СумДУ
            if($value['science_type_id']) {
                // всього за звітний рік
                // за БД Scopus або WoS:
                if($value['science_type_id'] == 1 || $value['science_type_id'] == 2) {
                    $rating["publicationsScopusWoSProfileSSU"]['countReportingYear']['ScopusOrWoS']['rating'] += $this->sumRating($request, $value);
                    $rating["publicationsScopusWoSProfileSSU"]['countReportingYear']['ScopusOrWoS']['count'] += 1;
                }

                // за БД Scopus та WoS:
                if($value['science_type_id'] == 3) {
                    $rating["publicationsScopusWoSProfileSSU"]['countReportingYear']['ScopusAndWoS']['rating'] += $this->sumRating($request, $value);
                    $rating["publicationsScopusWoSProfileSSU"]['countReportingYear']['ScopusAndWoS']['count'] += 1;
                }
            }

            // у виданні, яке відноситься до квартиля Q4
            if($value['quartil_scopus'] == 4 || $value['quartil_wos'] == 4) {
                $rating["publicationsScopusWoSProfileSSU"]['quartile4']['rating'] += $this->sumRating($request, $value);
                $rating["publicationsScopusWoSProfileSSU"]['quartile4']['count'] += 1;
            }

            // у виданні, яке відноситься до квартиля Q3
            if($value['quartil_scopus'] == 3 || $value['quartil_wos'] == 3) {
                $rating["publicationsScopusWoSProfileSSU"]['quartile3']['rating'] += $this->sumRating($request, $value);
                $rating["publicationsScopusWoSProfileSSU"]['quartile3']['count'] += 1;
            }

            // у виданні, яке відноситься до квартиля Q2
            if($value['quartil_scopus'] == 2 || $value['quartil_wos'] == 2) {
                $rating["publicationsScopusWoSProfileSSU"]['quartile2']['rating'] += $this->sumRating($request, $value);
                $rating["publicationsScopusWoSProfileSSU"]['quartile2']['count'] += 1;
            }

            // у виданні, яке відноситься до квартиля Q1
            if($value['quartil_scopus'] == 1 || $value['quartil_wos'] == 1) {
                $rating["publicationsScopusWoSProfileSSU"]['quartile1']['rating'] += $this->sumRating($request, $value);
                $rating["publicationsScopusWoSProfileSSU"]['quartile1']['count'] += 1;
            }

            //статті за БД WoS
            if(($value['publication_type_id'] == 1 || $value['publication_type_id'] == 3) && $value['science_type_id'] == 2) {
                //у т.ч. статті у виданнях, які входять до SCIE
                if($value['sub_db_scie'] == 1) {
                    $rating["publicationsScopusWoSProfileSSU"]['articleWoS']['scie']['rating'] += $this->sumRating($request, $value);
                    $rating["publicationsScopusWoSProfileSSU"]["articleWoS"]['scie']['count'] += 1;
                }
                //у т.ч. статті у виданнях, які входять до SSCI
                if($value['sub_db_ssci'] == 1) {
                    $rating["publicationsScopusWoSProfileSSU"]['articleWoS']['ssci']['rating'] += $this->sumRating($request, $value);
                    $rating["publicationsScopusWoSProfileSSU"]["articleWoS"]['ssci']['count'] += 1;
                }
            }

            //які обліковуються рейтингом Nature Index
            if($value['nature_index'] == 1) {
                $rating["publicationsScopusWoSProfileSSU"]['accountedNatureIndex']['rating'] += $this->sumRating($request, $value);
                $rating["publicationsScopusWoSProfileSSU"]["accountedNatureIndex"]['count'] += 1;
            }

            //у журналах Nature або Scince
            if($value['nature_science'] == 'Nature' || $value['nature_science'] == 'Science') {
                $rating["publicationsScopusWoSProfileSSU"]['journalsNatureOrScience']['rating'] += $this->sumRating($request, $value);
                $rating["publicationsScopusWoSProfileSSU"]["journalsNatureOrScience"]['count'] += 1;
            }

            //за співавторством з представниками інших організацій (підрахунок)
            if($authorsOtherOrganizations) {
                $rating["publicationsScopusWoSProfileSSU"]["authorsOtherOrganizations"]['count'] += 1;
                $rating["publicationsScopusWoSProfileSSU"]['authorsOtherOrganizations']['rating'] += $this->sumRating($request, $value);
            }

            //які увійшли до найбільш цитованих для своєї предметної галузі
            //до 10% за БД Scopus
            if($value['db_scopus_percent']) {
                $rating["publicationsScopusWoSProfileSSU"]['enteredMostCitedSubjectArea']['scopus']['rating'] += $this->sumRating($request, $value);
                $rating["publicationsScopusWoSProfileSSU"]["enteredMostCitedSubjectArea"]['scopus']['count'] += 1;
            }

            // До 1% за БД WoS
            if($value['db_wos_percent']) {
                $rating["publicationsScopusWoSProfileSSU"]['enteredMostCitedSubjectArea']['wos']['rating'] += $this->sumRating($request, $value);
                $rating["publicationsScopusWoSProfileSSU"]["enteredMostCitedSubjectArea"]['wos']['count'] += 1;
            }

            // - у т.ч. з цифровим ідентифікатором DOI
            if($value['doi'] && $value['science_type_id']) {
                $rating["publicationsScopusWoSProfileSSU"]['countDOI']['rating'] += $this->sumRating($request, $value);
                $rating["publicationsScopusWoSProfileSSU"]['countDOI']['count'] += 1;
            }

            // які процитовані у міжнародних патентах
            if($value['cited_international_patents']) {
                $rating["publicationsScopusWoSProfileSSU"]['citedInternationalPatents']['rating'] += $this->sumRating($request, $value);
                $rating["publicationsScopusWoSProfileSSU"]['citedInternationalPatents']['count'] += 1;
            }

            // Кількість публікацій у виданнях з показником SNIP більше ніж 1,0 за БД Scopus
            if(($value['science_type_id'] == 1 || $value['science_type_id'] == 3) && ($value['snip'] > 1)) {
                $rating["countSnipScopus"] += 1;
            }

            //Кількість публікацій всього у тому числі
            if($value['publication_type_id'] != 10 && $value['publication_type_id'] != 11) {
                $rating['publications'] += 1;
            }

            // Кількість охоронних документів щодо об'єктів права інтелектуальної власності
            // отримано за звітний рік на ім'я СумДУ
            if(($value['publication_type_id'] == 10 || $value['publication_type_id'] == 11) && $value['applicant'] == 'СумДУ') {
                $rating['numberSecurityDocuments']['receivedReportingNameSSU']['count'] += 1;
                $rating['numberSecurityDocuments']['receivedReportingNameSSU']['rating'] += $this->sumRating($request, $value);
                $authorshipBusinessRepresentatives = false;
                foreach ($value['authors'] as $k => $v) {
                    // з них за сумісним авторством з представниками бізнесу
                    if($v['author']['job'] != 'СумДУ' && $v['author']['job'] != null) {
                        $authorshipBusinessRepresentatives = true;
                    }
                }
                if($authorshipBusinessRepresentatives) {
                    $rating['numberSecurityDocuments']['authorshipBusinessRepresentatives']['count'] += 1;
                    $rating['numberSecurityDocuments']['authorshipBusinessRepresentatives']['rating'] += $this->sumRating($request, $value);
                }
            }

            // отримано за звітний рік штатними співробітниками не на ім'я СумДУ
            if($receivedReportingEmployeesNotSSU) {
                $rating['numberSecurityDocuments']['receivedReportingEmployeesNotSSU']['count'] += 1;
                $rating['numberSecurityDocuments']['receivedReportingEmployeesNotSSU']['rating'] += $this->sumRating($request, $value);
            }

            // комерціалізовано у звітному році
            if($value['publication_type_id'] == 10 && $value['commerc_university']) {
                $rating['numberSecurityDocuments']['commercializedReportingYear']['university']['count'] += 1;
                $rating['numberSecurityDocuments']['commercializedReportingYear']['university']['rating'] += $this->sumRating($request, $value);
            }

            // штатним співробітником
            if($value['publication_type_id'] == 10 && $value['commerc_employees']) {
                $rating['numberSecurityDocuments']['commercializedReportingYear']['employee']['count'] += 1;
                $rating['numberSecurityDocuments']['commercializedReportingYear']['employee']['rating'] += $this->sumRating($request, $value);
            }

            // всього за 5 років за БД Scopus
            if(($value['science_type_id'] == 1 || $value['science_type_id'] == 3) && $value['created_at']->toDateTimeString() >= $todayYear - 5) {
                $rating['publicationsScopusWoSProfileSSU']['countScopusFiveYear']['rating'] += $this->sumRating($request, $value);
                $rating["publicationsScopusWoSProfileSSU"]["countScopusFiveYear"]['count'] += 1;
            }

            $rating["studentPublications"] += $withStudent;
            $rating["foreignPublications"] += $foreignPublications;
            $rating["monographsIndexedScopusOrWoSNotSSU"] += $monographsIndexedScopusOrWoSNotSSU;
            $rating["articleProfessionalPublicationsUkraine"] += $articleProfessionalPublicationsUkraine;
            $rating["publicationsScopusWoSProfileSSU"]["authorsInForbesFortune"]['count'] += $authorsInForbesFortune;

            $rating["these"]['count'] += $these['count'];
            $rating["these"]['publishedAbroad'] += $these['publishedAbroad'];
            $rating["these"]['publishedWithStudents'] += $these['publishedWithStudents'];
            $rating["these"]['publishedWithForeignPartners'] += $these['publishedWithForeignPartners'];

            $rating["articles"]['count'] += $articles['count'];
            $rating["articles"]['publishedAbroad'] += $articles['publishedAbroad'];
            $rating["articles"]['publishedWithForeignPartners'] += $articles['publishedWithForeignPartners'];
        }

        foreach ($authors as $key => $value) {
            if($request->department_code != '') {
                if($request->faculty_code == $value['faculty_code']) {
                    if($request->department_code == $value['department_code']) {
                        if($value['five_publications']) {
                            $rating['authorsHasfivePublications'] += 1;
                        }
                        $rating['countHirschIndex'] += max($value['h_index'], $value['scopus_autor_id']);
                        $rating['countHirschIndexWithoutCitations'] += max($value['without_self_citations_wos'], $value['without_self_citations_scopus']);
                    }
                }
            } elseif ($request->faculty_code != '') {
                if($request->faculty_code == $value['faculty_code']) {
                    if($value['five_publications']) {
                        $rating['authorsHasfivePublications'] += 1;
                    }
                    $rating['countHirschIndex'] += max($value['h_index'], $value['scopus_autor_id']);
                    $rating['countHirschIndexWithoutCitations'] += max($value['without_self_citations_wos'], $value['without_self_citations_scopus']);
                }
            } else {
                if($value['five_publications']) {
                    $rating['authorsHasfivePublications'] += 1;
                }
                $rating['countHirschIndex'] += max($value['h_index'], $value['scopus_autor_id']);
                $rating['countHirschIndexWithoutCitations'] += max($value['without_self_citations_wos'], $value['without_self_citations_scopus']);
            }
        }

        return response()->json([
            "rating" => $rating,
            "publications" => $data
        ]);
    }

    function sumRating($request, $value) {
        $result = 0;
        foreach ($value['authors'] as $k => $v) {
            if($request->department_code != '') {
                if($request->faculty_code == $v['author']['faculty_code']) {
                    if($request->department_code == $v['author']['department_code']) {
                        $result += $v['rating_department'];
                    }
                }
            } elseif ($request->faculty_code != '') {
                if($request->faculty_code == $v['author']['faculty_code']) {
                    $result += $v['rating_department'];
                }
            } else {
                $result += $v['rating_faculty'];
            }
        }
        return $result;
    }
}
