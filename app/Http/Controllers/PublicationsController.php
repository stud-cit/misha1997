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

    // всі публікації
    function getAll(Request $request) {
        $divisions = $this->getDivisions();
        $data = [];
        $model = Publications::with('publicationType', 'scienceType', 'authors.author', 'publicationAdd', 'publicationEdit')->whereHas('authors.author', function($q) use ($request) {
            if($request->session()->get('person')['roles_id'] == 2) {
                $q->where('department_code', $request->session()->get('person')['department_code']);
            }
            if($request->session()->get('person')['roles_id'] == 3) {
                $q->where('faculty_code', $request->session()->get('person')['faculty_code']);
            }
        })->orderBy('created_at', 'DESC');

        if($request->title) {
            $model->where('title', 'like', "%".$request->title."%");
        }

        if($request->authors_f) {
            $model->whereHas('authors.author', function($q) use ($request) {
                $q->where('name', 'like', "%".$request->authors_f."%");
            });
        }

        if($request->science_type_id) {
            $model->where('science_type_id', $request->science_type_id);
        }

        if($request->year) {
            $model->where('year', $request->year);
        }

        if($request->country) {
            $model->where('country', 'like', "%".$request->country."%");
        }

        if($request->publication_type_id) {
            $model->where('publication_type_id', $request->publication_type_id);
        }

        if($request->department_code != '') {
            $departments_id = [$request->department_code];
            foreach($divisions->original['department'] as $k2 => $v2) {
                if ($v2['ID_PAR'] == $request->department_code) {
                    array_push($departments_id, $v2['ID_DIV']);
                }
            }
            $model->whereHas('authors.author', function($q) use ($departments_id) {
                $q->whereIn('department_code', $departments_id)->where('categ_1', "!=", 1);;
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

        $data = $model->get();

        foreach ($data as $key => $publication) {
            $publication['date'] = Carbon::parse($publication['created_at'])->format('d.m.Y');
        }
        return response()->json($data);
    }

    // мої публікації
    function getMyPublications(Request $request) {
        $data = [];
        $model = Publications::with('publicationType', 'scienceType', 'authors.author')->whereHas('authors.author', function($q) use ($request) {
            $q->where('autors_id', $request->session()->get('person')['id']);
        })->orderBy('created_at', 'DESC');

        if($request->title) {
            $model->where('title', 'like', "%".$request->title."%");
        }

        if($request->authors_f) {
            $model->whereHas('authors.author', function($q) use ($request) {
                $q->where('name', 'like', "%".$request->authors_f."%");
            });
        }

        if($request->science_type_id) {
            $model->where('science_type_id', $request->science_type_id);
        }

        if($request->year) {
            $model->where('year', $request->year);
        }

        if($request->country) {
            $model->where('country', 'like', "%".$request->country."%");
        }

        if($request->publication_type_id) {
            $model->where('publication_type_id', $request->publication_type_id);
        }

        if($request->faculty_code) {
            $model->whereHas('authors.author', function($q) use ($request) {
                $q->where('faculty_code', $request->faculty_code);
            });
        }

        if($request->department_code) {
            $model->whereHas('authors.author', function($q) use ($request) {
                $q->where('department_code', $request->department_code);
            });
        }

        $data = $model->get();

        foreach ($data as $key => $publication) {
            $publication['date'] = Carbon::parse($publication['created_at'])->format('m.d.Y');
        }
        return response()->json($data);
    }

    // публікація по ID
    function getId($id) {
        $divisions = $this->getDivisions();
        $data = Publications::with('publicationType', 'scienceType', 'authors.author', 'publicationAdd', 'publicationEdit')->find($id);
        $data->authors = AuthorsPublications::with('author')->where('publications_id', $id)->get();
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

        return response('ok', 200);
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
        $model = Publications::with('authors.author', 'publicationType', 'scienceType')->whereHas('authors.author', function($q) use ($request) {
            if($request->session()->get('person')['roles_id'] == 2) {
                $q->where('department_code', $request->session()->get('person')['department_code']);
            }
            if($request->session()->get('person')['roles_id'] == 3) {
                $q->where('faculty_code', $request->session()->get('person')['faculty_code']);
            }
            })
            ->where('country', 'like', "%".$request->country."%") // Країна видання
            ->orderBy('created_at', 'DESC');

        $todayYear = Carbon::today()->year;
        $countScopusFiveYear = Publications::where('science_type_id', 1)->orWhere('science_type_id', 3)->whereYear('created_at', '>=', $todayYear - 5)->count();
        $authorsHasfivePublications = Authors::where('five_publications', 1)->count();

        if(count($request->publication_types) > 0) {
            $model->whereIn('publication_type_id', array_column($request->publication_types, 'id')); // Вид публікацій
        }

        if($request->year) {
            $model->where('year', $request->year); // Рік видання
        }

        if($request->year_db) {
            $model->where('created_at', 'like', "%".$request->year_db."%"); // Рік занесення до бази даних
        }

        if($request->doi != "") {
            if($request->doi == 1) {
                $model->whereNotNull('doi'); // Публікації з цифровим ідентифікатором DOI
            }
            if($request->doi == 0) {
                $model->whereNull('doi'); // Публікації без цифрового ідентифікатора DOI
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

        if(count($request->science_types) > 0) {
            $model->whereIn('science_type_id', array_column($request->science_types, 'id')); // Індексування БД Scopus/WoS
        }

        if($request->quartil_scopus || $request->quartil_wos && ($request->quartil_scopus != $request->quartil_wos)) {
            if($request->quartil_scopus) {
                $model->where('quartil_scopus', $request->quartil_scopus); // Квартиль журналу SCOPUS
            }
            if($request->quartil_wos) {
                $model->where('quartil_wos', $request->quartil_wos); // Квартиль журналу WOS
            }
        }

        if($request->snip) {
            $model->where('snip', '>', 1); // Публікації опубліковані у виданнях з показником SNIP більше ніж 1,0
        }

        if($request->abroad == 1) {
            $model->where('country', '!=', 'Україна'); // Публікації опубліковані за кордоном
        }

        if($request->withStudents) { // Публікації за авторством та співавторством студентів
            $model->whereHas('authors.author', function($q) {
                $q->where('categ_1', 1);
            });
        }

        if($request->department != '') {
            $departments_id = [$request->department];
            foreach($divisions->original['department'] as $k2 => $v2) {
                if ($v2['ID_PAR'] == $request->department) {
                    array_push($departments_id, $v2['ID_DIV']);
                }
            }
            $model->whereHas('authors.author', function($q) use ($departments_id) {
                $q->whereIn('department_code', $departments_id)->where('categ_1', '!=', 1);
            });
        } else {
            if($request->faculty != '') {
                $departments_id = [$request->faculty];
                foreach($divisions->original['department'] as $k2 => $v2) {
                    if ($v2['ID_PAR'] == $request->faculty) {
                        array_push($departments_id, $v2['ID_DIV']);
                    }
                }
                $model->whereHas('authors.author', function($q) use ($departments_id) {
                    $q->whereIn('faculty_code', $departments_id)->where('categ_1', '!=', 1);
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
                    $q->where('country', '!=', "Україна")->where('h_index', '>', 10)->where('scopus_autor_id', '>', 10);
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

        foreach ($data as $key => $publication) {
            $publication['date'] = Carbon::parse($publication['created_at'])->format('m.d.Y');
        }

        $rating = [
            "countPublications" => 0, // Всього
            "countStudentPublications" => 0, // Кількість статей за авторством та співавторством студентів
            "countForeignPublications" => [
                "count" => 0, // Всього
                "haveIndexScopusWoS" => 0 // Мають індекс Гірша за БД Scopus або WoS не нижче 10
            ], // Кількість публікацій статей та монографій (розділів) у співавторстві з іноземними партнерами, які мають індекс Гірша за БД Scopus або WoS не нижче 10
            "monographsIndexedScopusOrWoSNotSSU" => 0, // монографії проіндексовані  БД Scopus або WoS за належності до профілю СумДУ
            "articleProfessionalPublicationsUkraine" => 0, // статей у фахових за статусом виданнях
            "publicationsScopusOrAndWoSNotSSU" => [
                "countReportingYear" => [
                    "ScopusAndWoS" => 0, // за БД Scopus та WoS:
                    "ScopusOrWoS" => 0 // за БД Scopus або WoS:
                ], // всього за звітний рік
                "quartile1" => 0, // у виданні, яке відноситься до квартиля Q1
                "quartile2" => 0, // у виданні, яке відноситься до квартиля Q2
                "quartile3" => 0, // у виданні, яке відноситься до квартиля Q3
                "quartile4" => 0, // у виданні, яке відноситься до квартиля Q3
            ], // які опубліковані у виданнях, що індексуються БД Scopus та/або WoS за умови належності до профілю СумДУ
            "articleWoS" => [
                "scie" => 0, // статті у виданнях, які входять до SCIE
                "ssci" => 0 // статті у виданнях, які входять до SSCI
            ],
            "accountedNatureIndex" => 0, // які обліковуються рейтингом Nature Index
            "journalsNatureOrScience" => 0, // у журналах Nature Scince
            "authorsOtherOrganizations" => 0, // за співавторством з представниками інших організацій
            "authorsInForbesFortune" => 0, // що входять до списків Forbes та Fortune
            "enteredMostCitedSubjectArea" => [
                "scopus" => 0, // до 10% за БД Scopus
                "wos" => 0 // До 1% за БД WoS
            ], // які увійшли до найбільш цитованих для своєї предметної галузі
            "countDOI" => 0, // - у т.ч. з цифровим ідентифікатором DOI
            "citedInternationalPatents" => 0, // які процитовані у міжнародних патентах
            "countScopusFiveYear" => $countScopusFiveYear, // всього за 5 років за БД Scopus
            "countSnipScopus" => 0, // Кількість публікацій у виданнях з показником SNIP більше ніж 1,0 за БД Scopus
            "countHirschIndex" => 0, //Загальне значення індексів Гірша (за БД Scopus  або БД WoS ) штатних працівників та докторантів (динаміка змін)
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
            "authorsHasfivePublications" => $authorsHasfivePublications, // Чисельність штатних працівників, які мають не менше 5-ти публікацій у виданнях, що  індексуються БД Scopus та/або WoS (динаміка змін)
            "receivedReportingNameSSU" => 0,
            "authorshipBusinessRepresentatives" => 0,
            "receivedReportingEmployeesNotSSU" => 0,
            "commercializedReportingYear" => [
                "university" => 0,
                "employee" => 0
            ]
        ];

        $authors = [];

        if($request->quartil_scopus && $request->quartil_wos && ($request->quartil_scopus == $request->quartil_wos)) {
            $data = array_filter($data, function($value) use ($request) {
                return $request->quartil_scopus == min($value['quartil_scopus'], $value['quartil_wos']);
            });
        }

        foreach ($data as $key => $value) {
            if($value['publication_type_id'] != 10 && $value['publication_type_id'] != 11) {
                $rating['countPublications'] += 1;
            }
            if(($value['publication_type_id'] == 10 || $value['publication_type_id'] == 11) && $value['applicant'] == 'СумДУ') {
                $rating['receivedReportingNameSSU'] += 1;
                $authorshipBusinessRepresentatives = 0;
                foreach ($value['authors'] as $k => $v) {
                    if($v['author']['job'] != 'СумДУ' && $v['author']['job'] != null) {
                        $authorshipBusinessRepresentatives = 1;
                    }
                }
                $rating['authorshipBusinessRepresentatives'] += $authorshipBusinessRepresentatives;
            }

            if($value['publication_type_id'] == 10 && $value['applicant'] != 'СумДУ') {
                $receivedReportingEmployeesNotSSU = 0;
                foreach ($value['authors'] as $k => $v) {
                    if($v['author']['job'] == 'СумДУ') {
                        $receivedReportingEmployeesNotSSU = 1;
                    }
                }
                $rating['receivedReportingEmployeesNotSSU'] += $receivedReportingEmployeesNotSSU;
            }

            if($value['publication_type_id'] == 10 && $value['commerc_university']) {
                $rating['commercializedReportingYear']['university'] += 1;
            }

            if($value['publication_type_id'] == 10 && $value['commerc_employees']) {
                $rating['commercializedReportingYear']['employee'] += 1;
            }

            $withStudent = 0;
            $countForeignPublications = [
                "count" => 0,
                "haveIndexScopusWoS" => 0
            ];
            $monographsIndexedScopusOrWoSNotSSU = 0;
            $articleProfessionalPublicationsUkraine = 0;
            $publicationsScopusOrAndWoSNotSSU = [
                "countReportingYear" => [
                    "ScopusAndWoS" => 0,
                    "ScopusOrWoS" => 0
                ],
                "quartile1" => 0,
                "quartile2" => 0,
                "quartile3" => 0,
                "quartile4" => 0,
            ];
            $articleWoS = [
                "scie" => 0,
                "ssci" => 0
            ];
            $accountedNatureIndex = 0;
            $journalsNatureOrScience = 0;
            $authorsOtherOrganizations = 0;
            $authorsInForbesFortune = 0;
            $enteredMostCitedSubjectArea = [
                "scopus" => 0,
                "wos" => 0
            ];
            $countDOI = 0;
            $citedInternationalPatents = 0;
            $countSnipScopus = 0;
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

                
                if(($value['publication_type_id'] == 1 || $value['publication_type_id'] == 2 || $value['publication_type_id'] == 3) && $v['author']['categ_1'] == 1) {
                    $withStudent = 1;
                }

                if(($value['publication_type_id'] == 1 || $value['publication_type_id'] == 2 || $value['publication_type_id'] == 3 || $value['publication_type_id'] == 6 || $value['publication_type_id'] == 7) && $v['author']['country'] != "Україна") {
                    $countForeignPublications['count'] = 1;
                    if($v['author']['h_index'] >= 10 || $v['author']['scopus_autor_id'] >= 10) {
                        $countForeignPublications['haveIndexScopusWoS'] = 1;
                    }
                }
                if($value['publication_type_id'] == 6 && ($value['science_type_id'] == 1 || $value['science_type_id'] == 2) && $v['author']['guid']) {
                    $monographsIndexedScopusOrWoSNotSSU = 1;
                }
                if($value['publication_type_id'] == 1) {
                    $articleProfessionalPublicationsUkraine = 1;
                }

                if($value['science_type_id']) {
                    if($value['science_type_id'] == 1 || $value['science_type_id'] == 2) {
                        $publicationsScopusOrAndWoSNotSSU['countReportingYear']['ScopusOrWoS'] = 1;
                    }
                    if($value['science_type_id'] == 3) {
                        $publicationsScopusOrAndWoSNotSSU['countReportingYear']['ScopusAndWoS'] = 1;
                    }
                    if($value['quartil_scopus'] == 4 || $value['quartil_wos'] == 4) {
                        $publicationsScopusOrAndWoSNotSSU['quartile4'] = 1;
                    }
                    if($value['quartil_scopus'] == 3 || $value['quartil_wos'] == 3) {
                        $publicationsScopusOrAndWoSNotSSU['quartile3'] = 1;
                    }
                    if($value['quartil_scopus'] == 2 || $value['quartil_wos'] == 2) {
                        $publicationsScopusOrAndWoSNotSSU['quartile2'] = 1;
                    }
                    if($value['quartil_scopus'] == 1 || $value['quartil_wos'] == 1) {
                        $publicationsScopusOrAndWoSNotSSU['quartile1'] = 1;
                    }
                }

                if(($value['publication_type_id'] == 1 || $value['publication_type_id'] == 3) && $value['science_type_id'] == 2) {
                    if($value['sub_db_scie'] == 1) {
                        $articleWoS['scie'] = 1;
                    }
                    if($value['sub_db_ssci'] == 1) {
                        $articleWoS['ssci'] = 1;
                    }
                }

                if($value['nature_index']) {
                    $accountedNatureIndex = 1;
                }

                if($value['nature_science'] == 'Nature' || $value['nature_science'] == 'Science') {
                    $journalsNatureOrScience = 1;
                }

                if($value['publication_type_id'] != null && $v['author']['job'] != "СумДУ" && $v['author']['job'] != "Не працює" && $v['author']['job'] != null) {
                    $authorsOtherOrganizations = 1;
                }

                if($v['author']['forbes_fortune']) {
                    $authorsInForbesFortune = 1;
                }

                if($value['db_scopus_percent']) {
                    $enteredMostCitedSubjectArea['scopus'] = 1;
                }

                if($value['db_wos_percent']) {
                    $enteredMostCitedSubjectArea['wos'] = 1;
                }

                // - у т.ч. з цифровим ідентифікатором DOI
                if($value['doi'] && $value['science_type_id']) {
                    $countDOI = 1;
                }

                if($value['cited_international_patents']) {
                    $rating["citedInternationalPatents"] = 1;
                }

                if($value['science_type_id'] == 1 && ($value['snip'] > 1)) {
                    $rating["countSnipScopus"] = 1;
                }

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
                    if($v['author']['categ_1'] == 1) {
                        $these['publishedWithStudents'] = 1;
                    }
                }

                if($value['publication_type_id'] == 1 || $value['publication_type_id'] == 2 || $value['publication_type_id'] == 3 || $value['publication_type_id'] == 7) {
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
            }

            

            $rating["countStudentPublications"] += $withStudent; // Кількість статей за авторством та співавторством студентів

            $rating["countForeignPublications"]['count'] += $countForeignPublications['count'];
            $rating["countForeignPublications"]['haveIndexScopusWoS'] += $countForeignPublications['haveIndexScopusWoS'];
            $rating["monographsIndexedScopusOrWoSNotSSU"] += $monographsIndexedScopusOrWoSNotSSU;
            $rating["articleProfessionalPublicationsUkraine"] += $articleProfessionalPublicationsUkraine;

            $rating["publicationsScopusOrAndWoSNotSSU"]['quartile4'] += $publicationsScopusOrAndWoSNotSSU['quartile4'];
            $rating["publicationsScopusOrAndWoSNotSSU"]['quartile3'] += $publicationsScopusOrAndWoSNotSSU['quartile3'];
            $rating["publicationsScopusOrAndWoSNotSSU"]['quartile2'] += $publicationsScopusOrAndWoSNotSSU['quartile2'];
            $rating["publicationsScopusOrAndWoSNotSSU"]['quartile1'] += $publicationsScopusOrAndWoSNotSSU['quartile1'];

            $rating["publicationsScopusOrAndWoSNotSSU"]['countReportingYear']['ScopusOrWoS'] += $publicationsScopusOrAndWoSNotSSU['countReportingYear']['ScopusOrWoS'];
            $rating["publicationsScopusOrAndWoSNotSSU"]['countReportingYear']['ScopusAndWoS'] += $publicationsScopusOrAndWoSNotSSU['countReportingYear']['ScopusAndWoS'];

            $rating["articleWoS"]['scie'] += $articleWoS['scie'];
            $rating["articleWoS"]['ssci'] += $articleWoS['ssci'];

            $rating["accountedNatureIndex"] += $accountedNatureIndex;
            $rating["journalsNatureOrScience"] += $journalsNatureOrScience;
            $rating["authorsOtherOrganizations"] += $authorsOtherOrganizations;
            $rating["authorsInForbesFortune"] += $authorsInForbesFortune;

            $rating["enteredMostCitedSubjectArea"]['scopus'] += $enteredMostCitedSubjectArea['scopus'];
            $rating["enteredMostCitedSubjectArea"]['wos'] += $enteredMostCitedSubjectArea['wos'];

            $rating["countDOI"] += $countDOI;
            $rating["citedInternationalPatents"] += $citedInternationalPatents;
            $rating["countSnipScopus"] += $countSnipScopus;

            $rating["these"]['count'] += $these['count'];
            $rating["these"]['publishedAbroad'] += $these['publishedAbroad'];
            $rating["these"]['publishedWithStudents'] += $these['publishedWithStudents'];
            $rating["these"]['publishedWithForeignPartners'] += $these['publishedWithForeignPartners'];

            $rating["articles"]['count'] += $articles['count'];
            $rating["articles"]['publishedAbroad'] += $articles['publishedAbroad'];
            $rating["articles"]['publishedWithForeignPartners'] += $articles['publishedWithForeignPartners'];
        }

        foreach ($authors as $key => $value) {
            $rating['countHirschIndex'] += max($value['h_index'], $value['scopus_autor_id']);
        }



        return response()->json([
            "rating" => $rating,
            "publications" => $data
        ]);
    }
}