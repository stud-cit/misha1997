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

        if($request->hasSupervisor == "true") {
            $model->whereHas('authors', function($q) {
                $q->where('supervisor', 1);
            });
        }

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

        $data = $model->get();

        return response()->json($data);
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

        if(count($request->publication_types) > 0) {
            $model->whereIn('publication_type_id', array_column($request->publication_types, 'id')); // Вид публікацій
        }

        if($request->years) {
            $model->whereIn('year', $request->years); // Рік видання
        }

        if($request->not_previous_year == "true") {
            $model->where('not_previous_year', 1); // Публікації які не враховані в рейтингу попереднього року
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
                "receivedReportingNameSSU" => 0,
                // з них за сумісним авторством з представниками бізнесу
                "authorshipBusinessRepresentatives" => 0,
                // отримано за звітний рік штатними співробітниками не на ім'я СумДУ
                "receivedReportingEmployeesNotSSU" => 0,
                // комерціалізовано у звітному році
                "commercializedReportingYear" => [
                    // університетом
                    "university" => 0,
                    // штатним співробітником
                    "employee" => 0
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

            $publicationsScopusWoSProfileSSU = [
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
            $countScopusFiveYear = 0;
            $countSnipScopus = 0;
            $receivedReportingEmployeesNotSSU = 0;
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

            $test = true;

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
                if(($value['publication_type_id'] == 1 || $value['publication_type_id'] == 2 || $value['publication_type_id'] == 3) && ($v['author']['categ_1'] == 1 || $v['author']['categ_1'] == 3)) {
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

                //які опубліковані у виданнях, що індексуються БД Scopus та/або WoS за умови належності до профілю СумДУ
                if($value['science_type_id'] && $v['author']['guid']) {
                    // всього за звітний рік
                    // за БД Scopus або WoS:
                    if($value['science_type_id'] == 1 || $value['science_type_id'] == 2) {
                        $rating["publicationsScopusWoSProfileSSU"]['countReportingYear']['ScopusOrWoS']['rating'] += $this->sumRating($request, $v);
                        $publicationsScopusWoSProfileSSU['countReportingYear']['ScopusOrWoS'] = 1;
                    }

                    // за БД Scopus та WoS:
                    if($value['science_type_id'] == 3) {
                        $rating["publicationsScopusWoSProfileSSU"]['countReportingYear']['ScopusAndWoS']['rating'] += $this->sumRating($request, $v);
                        $publicationsScopusWoSProfileSSU['countReportingYear']['ScopusAndWoS'] = 1;
                    }

                    // у виданні, яке відноситься до квартиля Q4
                    if($value['quartil_scopus'] == 4 || $value['quartil_wos'] == 4) {
                        $rating["publicationsScopusWoSProfileSSU"]['quartile4']['rating'] += $this->sumRating($request, $v);
                        $publicationsScopusWoSProfileSSU['quartile4'] = 1;
                    }

                    // у виданні, яке відноситься до квартиля Q3
                    if($value['quartil_scopus'] == 3 || $value['quartil_wos'] == 3) {
                        $rating["publicationsScopusWoSProfileSSU"]['quartile3']['rating'] += $this->sumRating($request, $v);
                        $publicationsScopusWoSProfileSSU['quartile3'] = 1;
                    }

                    // у виданні, яке відноситься до квартиля Q2
                    if($value['quartil_scopus'] == 2 || $value['quartil_wos'] == 2) {
                        $rating["publicationsScopusWoSProfileSSU"]['quartile2']['rating'] += $this->sumRating($request, $v);
                        $publicationsScopusWoSProfileSSU['quartile2'] = 1;
                    }

                    // у виданні, яке відноситься до квартиля Q1
                    if($value['quartil_scopus'] == 1 || $value['quartil_wos'] == 1) {
                        $rating["publicationsScopusWoSProfileSSU"]['quartile1']['rating'] += $this->sumRating($request, $v);
                        $publicationsScopusWoSProfileSSU['quartile1'] = 1;
                    }
                }

                //статті за БД WoS
                if(($value['publication_type_id'] == 1 || $value['publication_type_id'] == 3) && $value['science_type_id'] == 2) {
                    //у т.ч. статті у виданнях, які входять до SCIE
                    if($value['sub_db_scie'] == 1) {
                        $rating["publicationsScopusWoSProfileSSU"]['articleWoS']['scie']['rating'] += $this->sumRating($request, $v);
                        $articleWoS['scie'] = 1;
                    }
                    //у т.ч. статті у виданнях, які входять до SSCI
                    if($value['sub_db_ssci'] == 1) {
                        $rating["publicationsScopusWoSProfileSSU"]['articleWoS']['ssci']['rating'] += $this->sumRating($request, $v);
                        $articleWoS['ssci'] = 1;
                    }
                }

                //які обліковуються рейтингом Nature Index
                if($value['nature_index'] == 1) {
                    $rating["publicationsScopusWoSProfileSSU"]['accountedNatureIndex']['rating'] += $this->sumRating($request, $v);
                    $accountedNatureIndex = 1;
                }

                //у журналах Nature або Scince
                if($value['nature_science'] == 'Nature' || $value['nature_science'] == 'Science') {
                    $rating["publicationsScopusWoSProfileSSU"]['journalsNatureOrScience']['rating'] += $this->sumRating($request, $v);
                    $journalsNatureOrScience = 1;
                }

                //за співавторством з представниками інших організацій
                if($value['science_type_id'] != null && $v['author']['job'] != "СумДУ" && $v['author']['job'] != "СумДУ (Не працює)" && $v['author']['job'] != "Не працює" && $v['author']['guid'] == null) {
                    if($test) {
                        $rating["publicationsScopusWoSProfileSSU"]['authorsOtherOrganizations']['rating'] += $this->sumRating2($request, $value);
                        $test = false;
                    }
                    $authorsOtherOrganizations = 1;
                }

                // що входять до списків Forbes та Fortune
                if($v['author']['forbes_fortune']) {
                    $rating["publicationsScopusWoSProfileSSU"]['authorsInForbesFortune']['rating'] += $this->sumRating2($request, $value);
                    $authorsInForbesFortune = 1;
                }

                //які увійшли до найбільш цитованих для своєї предметної галузі
                //до 10% за БД Scopus
                if($value['db_scopus_percent']) {
                    $rating["publicationsScopusWoSProfileSSU"]['enteredMostCitedSubjectArea']['scopus']['rating'] += $this->sumRating($request, $v);
                    $enteredMostCitedSubjectArea['scopus'] = 1;
                }

                // До 1% за БД WoS
                if($value['db_wos_percent']) {
                    $rating["publicationsScopusWoSProfileSSU"]['enteredMostCitedSubjectArea']['wos']['rating'] += $this->sumRating($request, $v);
                    $enteredMostCitedSubjectArea['wos'] = 1;
                }

                // - у т.ч. з цифровим ідентифікатором DOI
                if($value['doi'] && $value['science_type_id']) {
                    $rating["publicationsScopusWoSProfileSSU"]['countDOI']['rating'] += $this->sumRating($request, $v);
                    $countDOI = 1;
                }

                // які процитовані у міжнародних патентах
                if($value['cited_international_patents']) {
                    $rating["publicationsScopusWoSProfileSSU"]['citedInternationalPatents']['rating'] += $this->sumRating($request, $v);
                    $citedInternationalPatents = 1;
                }

                // Кількість публікацій у виданнях з показником SNIP більше ніж 1,0 за БД Scopus
                if(($value['science_type_id'] == 1 || $value['science_type_id'] == 3) && ($value['snip'] > 1)) {
                    $countSnipScopus = 1;
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

                // всього за 5 років за БД Scopus
                if(($value['science_type_id'] == 1 || $value['science_type_id'] == 3) && $value['created_at']->toDateTimeString() >= $todayYear - 5) {
                    $rating['publicationsScopusWoSProfileSSU']['countScopusFiveYear']['rating'] += $this->sumRating($request, $v);
                    $countScopusFiveYear = 1;
                }
            }

            //Кількість публікацій всього у тому числі
            if($value['publication_type_id'] != 10 && $value['publication_type_id'] != 11) {
                $rating['publications'] += 1;
            }

            // Кількість охоронних документів щодо об'єктів права інтелектуальної власності
            // отримано за звітний рік на ім'я СумДУ
            if(($value['publication_type_id'] == 10 || $value['publication_type_id'] == 11) && $value['applicant'] == 'СумДУ') {
                $rating['numberSecurityDocuments']['receivedReportingNameSSU'] += 1;
                $authorshipBusinessRepresentatives = 0;
                foreach ($value['authors'] as $k => $v) {
                    if($v['author']['job'] != 'СумДУ' && $v['author']['job'] != null) {
                        $authorshipBusinessRepresentatives = 1;
                    }
                }
                $rating['numberSecurityDocuments']['authorshipBusinessRepresentatives'] += $authorshipBusinessRepresentatives;
            }

            // отримано за звітний рік штатними співробітниками не на ім'я СумДУ
            if($value['publication_type_id'] == 10 && $value['applicant'] != 'СумДУ' && $v['author']['job'] == 'СумДУ') {
                $receivedReportingEmployeesNotSSU = 1;
            }

            // комерціалізовано у звітному році
            if($value['publication_type_id'] == 10 && $value['commerc_university']) {
                $rating['numberSecurityDocuments']['commercializedReportingYear']['university'] += 1;
            }

            // штатним співробітником
            if($value['publication_type_id'] == 10 && $value['commerc_employees']) {
                $rating['numberSecurityDocuments']['commercializedReportingYear']['employee'] += 1;
            }

            $rating["studentPublications"] += $withStudent;
            $rating["foreignPublications"] += $foreignPublications;
            $rating["monographsIndexedScopusOrWoSNotSSU"] += $monographsIndexedScopusOrWoSNotSSU;
            $rating["articleProfessionalPublicationsUkraine"] += $articleProfessionalPublicationsUkraine;
            $rating["publicationsScopusWoSProfileSSU"]['quartile4']['count'] += $publicationsScopusWoSProfileSSU['quartile4'];
            $rating["publicationsScopusWoSProfileSSU"]['quartile3']['count'] += $publicationsScopusWoSProfileSSU['quartile3'];
            $rating["publicationsScopusWoSProfileSSU"]['quartile2']['count'] += $publicationsScopusWoSProfileSSU['quartile2'];
            $rating["publicationsScopusWoSProfileSSU"]['quartile1']['count'] += $publicationsScopusWoSProfileSSU['quartile1'];
            $rating["publicationsScopusWoSProfileSSU"]['countReportingYear']['ScopusOrWoS']['count'] += $publicationsScopusWoSProfileSSU['countReportingYear']['ScopusOrWoS'];
            $rating["publicationsScopusWoSProfileSSU"]['countReportingYear']['ScopusAndWoS']['count'] += $publicationsScopusWoSProfileSSU['countReportingYear']['ScopusAndWoS'];
            $rating["publicationsScopusWoSProfileSSU"]["articleWoS"]['scie']['count'] += $articleWoS['scie'];
            $rating["publicationsScopusWoSProfileSSU"]["articleWoS"]['ssci']['count'] += $articleWoS['ssci'];
            $rating["publicationsScopusWoSProfileSSU"]["accountedNatureIndex"]['count'] += $accountedNatureIndex;
            $rating["publicationsScopusWoSProfileSSU"]["journalsNatureOrScience"]['count'] += $journalsNatureOrScience;
            $rating["publicationsScopusWoSProfileSSU"]["authorsOtherOrganizations"]['count'] += $authorsOtherOrganizations;
            $rating["publicationsScopusWoSProfileSSU"]["authorsInForbesFortune"]['count'] += $authorsInForbesFortune;
            $rating["publicationsScopusWoSProfileSSU"]["enteredMostCitedSubjectArea"]['scopus']['count'] += $enteredMostCitedSubjectArea['scopus'];
            $rating["publicationsScopusWoSProfileSSU"]["enteredMostCitedSubjectArea"]['wos']['count'] += $enteredMostCitedSubjectArea['wos'];
            $rating["publicationsScopusWoSProfileSSU"]["countDOI"]['count'] += $countDOI;
            $rating["publicationsScopusWoSProfileSSU"]["citedInternationalPatents"]['count'] += $citedInternationalPatents;
            $rating["publicationsScopusWoSProfileSSU"]["countScopusFiveYear"]['count'] += $countScopusFiveYear;
            $rating["numberSecurityDocuments"]["receivedReportingEmployeesNotSSU"] += $receivedReportingEmployeesNotSSU;

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
            if($value['five_publications']) {
                $rating['authorsHasfivePublications'] += 1;
            }
            $rating['countHirschIndex'] += max($value['h_index'], $value['scopus_autor_id']);
            $rating['countHirschIndexWithoutCitations'] += max($value['without_self_citations_wos'], $value['without_self_citations_scopus']);
        }

        return response()->json([
            "rating" => $rating,
            "publications" => $data
        ]);
    }
    function sumRating($request, $rating) {
        $result = 0;
        if($request->department_code != '') {
            if($request->faculty_code == $rating['author']['faculty_code']) {
                if($request->department_code == $rating['author']['department_code']) {
                    $result += $rating['rating_department'];
                }
            }
        } elseif ($request->faculty_code != '') {
            if($request->faculty_code == $rating['author']['faculty_code']) {
                $result += $rating['rating_department'];
            }
        } else {
            $result += $rating['rating_department'];
        }
        return $result;
    }

    function sumRating2($request, $value) {
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
                $result += $v['rating_department'];
            }
        }
        return $result;
    }
}
