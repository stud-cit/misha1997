<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publications;
use App\Models\AuthorsPublications;
use App\Models\Countries;
use App\Models\PublicationType;
use App\Models\Notifications;

class PublicationsController extends ASUController
{
    // всі назви поблікацій
    function getAllNames() {
        $data = Publications::select('title')->get();
        return response()->json($data);
    }

    // всі публікації
    function getAll(Request $request) {
        $data = Publications::with('publicationType', 'scienceType', 'supervisor', 'authors')->get();
        foreach ($data as $key => $publication) {
            foreach ($publication->authors as $key => $value) {
                $value['author'] = $value->author;
            }
        }
        return response()->json($data);
    }

    // публікація по ID
    function getId($id) {
        $divisions = $this->getDivisions();
        $data = Publications::with('publicationType', 'scienceType', 'supervisor')->find($id);
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

        foreach($divisions->original['department']  as $k => $v) {
            if ($data->supervisor['department_code'] == $v['ID_DIV']) {
                $data->supervisor['department'] = $v['NAME_DIV'];
            }
        }
        foreach($divisions->original['institute'] as $k => $v) {
            if ($data->supervisor['faculty_code'] == $v['ID_DIV']) {
                $data->supervisor['faculty'] = $v['NAME_DIV'];
            }
        }

        return response()->json($data);
    }

    // додавання публікації
    function post(Request $request) {
        $modelPublications = new Publications();
        $dataPublications = $request->all();
        $dataPublications['publication_type_id'] = $dataPublications['publication_type']['id'];

        if($dataPublications['supervisor']) {
            $dataPublications['supervisor_id'] = $dataPublications['supervisor']['id'];
        }
        $response = $modelPublications->create($dataPublications);

        foreach ($request->authors as $key => $value) {
            $authorsPublications = new AuthorsPublications;
            $authorsPublications->autors_id = $value['id'];
            $authorsPublications->publications_id = $response->id;
            $authorsPublications->save();
        }

        return response('ok', 200);
    }

    // оновлення публікації
    function updatePublication(Request $request, $id) {
        $data = $request->all();
        if($data['supervisor']) {
            $data['supervisor_id'] = $data['supervisor']['id'];
        } else {
            $data['supervisor_id'] = null;
        }
        Publications::find($id)->update($data);
        return response('ok', 200);
    }

    // видалення публікації
    function deletePublications(Request $request) {
        foreach ($request->publications as $key => $publication) {
            foreach ($publication['authors'] as $k => $author) {
                Notifications::create([
                    "autors_id" => $author['author']['id'],
                    "text" => $request->user['name']." видалив публікацию \"".$publication['title']."\"."
                ]);
            }
            Publications::find($publication['id'])->delete();
        }
        return response('ok', 200);
    }

    // список країн
    function getCountry() {
        $data = Countries::get();
        return response()->json($data);
    }

    // список типів публікацій
    function typePublications() {
        $data = PublicationType::get();
        return response()->json($data);
    }

    // рейтингові показники
    function export(Request $request) {
        $divisions = $this->getDivisions();
        $data = Publications::with('authors.author', 'publicationType', 'scienceType', 'supervisor')
            ->where('country', 'like', "%".$request->country."%") // Країна видання
            ->where('publication_type_id', 'like', "%".$request->publication_type_id."%"); // Вид публікацій

        if($request->year) {
            $data->where('year', $request->year); // Рік видання
        }

        if($request->doi != "") {
            if($request->doi == 1) {
                $data->whereNotNull('doi'); // Публікації з цифровим ідентифікатором DOI
            }
            if($request->doi == 0) {
                $data->whereNull('doi'); // Публікації без цифрового ідентифікатора DOI
            }
        }

        if($request->applicant != "") {
            if($request->applicant == "СумДУ") {
                $data->where('applicant', 'СумДУ'); // Охоронні документи СумДУ
            }
            if($request->applicant != "СумДУ") {
                $data->where('applicant', '!=', 'СумДУ'); // Охоронні документи не СумДУ
            }
        }

        if($request->commercial_applicant != "") {
            if($request->commercial_applicant == 1) {
                $data->where('commerc_university', 1); // Комерціалізовані охоронні документи (Університетом)
            }
            if($request->commercial_applicant == 2) {
                $data->where('commerc_employees', 1); // Комерціалізовані охоронні документи (Штатними співробітниками)
            }
        }

        if($request->science_type_id) {
            $data->where('science_type_id', $request->science_type_id); // Індексування БД Scopus\WoS
        }

        if($request->quartil_scopus) {
            $data->where('quartil_scopus', $request->quartil_scopus); // Квартиль журналу SCOPUS
        }

        if($request->quartil_wos) {
            $data->where('quartil_wos', $request->quartil_wos); // Квартиль журналу WOS
        }

        if($request->snip) {
            $data->where('snip', '>', 1); // Публікації опубліковані у виданнях з показником SNIP більше ніж 1,0
        }

        if($request->abroad == 1) {
            $data->where('country', '!=', 'Україна'); // Публікації опубліковані за кордоном
        }

        if($request->withStudents == 1) { // Публікації за авторством та співавторством студентів
            $data->whereHas('authors', function($query) {
                $query->whereHas('author', function($q) {
                    $q->where('categ_1', 1);
                });
            });
        }

        if($request->faculty != "") { // Інститут / факультет
            $data->whereHas('authors', function($query) use($request) {
                $query->whereHas('author', function($q) use($request) {
                    $q->where('faculty_code', $request->faculty);
                });
            });
        }

        if($request->department != "") { // Кафедра
            $data->whereHas('authors', function($query) use($request) {
                $query->whereHas('author', function($q) use($request) {
                    $q->where('department_code', $request->department);
                });
            });
        }

        if($request->withForeigners != "") { // Публікації у співавторстві з іноземними партнерами
            if($request->withForeigners == 1) {
                $data->whereHas('authors', function($query) {
                    $query->whereHas('author', function($q) {
                        $q->where('country', "Україна");
                    });
                });
            }
            if($request->withForeigners == 2) {
                $data->whereHas('authors', function($query) {
                    $query->whereHas('author', function($q) {
                        $q->where('country', '!=', "Україна");
                    });
                });
            }
            if($request->withForeigners == 10) {
                $data->whereHas('authors', function($query) {
                    $query->whereHas('author', function($q) {
                        $q->where('h_index', '>', 10);
                    });
                });
            }
        }

        if($request->other_organization != "") { // Публікації за співавторством з представниками інших організацій
            if($request->other_organization == 1) {
                $data->whereHas('authors', function($query) {
                    $query->whereHas('author', function($q) {
                        $q->where('job', '!=', 'СумДУ');
                    });
                });
            }
            if($request->other_organization == 0) { // Публікації за співавторством з представниками інших організацій
                $data->whereHas('authors', function($query) {
                    $query->whereHas('author', function($q) {
                        $q->where('job', 'СумДУ');
                    });
                });
            }
        }

        if($request->scie != "") { // Статті у виданнях, які входять до підбази WOS - SCIE
            if($request->scie == 1) {
                $data->where('sub_db_index', 1);
            }
            if($request->scie == 0) {
                $data->where('sub_db_index', '!=', 1);
            }
        }

        if($request->ssci != "") { // Статті у виданнях, які входять до підбази WOS - SSCI
            if($request->ssci == 1) {
                $data->where('sub_db_index', 2);
            }
            if($request->ssci == 0) {
                $data->where('sub_db_index', '!=', 2);
            }
        }

        $data = $data->get();

        // return response()->json($data);

        $rating = [
            "countPublications" => 0, // Всього
            "countStudentPublications" => 0, // Кількість статей за авторством та співавторством студентів
            "countForeignPublications" => 0, // Кількість публікацій у співавторстві з іноземними партнерами
            "countIndexHirsha" => 0, // Мають індекс Гірша за БД Scopus або WoS не нижче 10
            "countArticle" => 0, // кількість статтей
            "countOtherArticle" => 0, // кількість інших статтей
            "countBooks" => 0, // кількість книг
            "countManuals" => 0, // кількість посібників
            "countMonographs" => 0, // Монографія
            "countMonographSection" => 0, // Розділ монографії
            "countBookSection" => 0, // Розділ монографії
            "countThese" => 0, // Кількість тез всього
            "countPatent" => 0, // Патент
            "countCertificate" => 0, // Свідоцтво про реєстрації авторських прав на твір/рішення
            "countMethodicalInstructions" => 0, // Методичні вказівки / конспекти лекцій
            "countElectronic" => 0, // Електронні видання
            "countScopusAndWoS" => 0, // за БД Scopus та WoS
            "countScopusOrWoS" => 0, // за БД Scopus або WoS
            "countSnipScopus" => 0, // у тому числі у виданнях з показником SNIP більше ніж 1,0 за БД Scopus
            "countSCIEWoS" => 0, // за БД WoS - у т.ч. статті у виданнях, які входять до SCIE
            "countSSCIWoS" => 0, // за БД WoS - у т.ч. статті у виданнях, які входять до SSCI
            "countImpactFactorWoS" => 0, // за БД WoS - у т.ч. у виданнях з імпакт-фактором більше ніж 0‚5
            "countOtherOrganization" => 0, // у т.ч. за співавторством з представниками інших організацій
            "countDOI" => 0, // - у т.ч. з цифровим ідентифікатором DOI
            "countFiveYearScopus" => 0, // - всього за 5 років за БД Scopus
            "countTheseAbroad" => 0, // Тез опублікованих за кордоном
            "countTheseWithStudent" => 0, // Тез опублікованих зі студентами
            "countTheseWithForeign" => 0, // Тез опублікованих з іноземними партнерами
            "countArticleAbroad" => 0, // - статей опублікованих за кордоном
            "countArticleWithForeign" => 0, // - статей з іноземними партнерами
            "countEmployees" => 0, // Чисельність штатних науково та науково-педагогічних працівників, які мають не менше 5-ти публікацій у виданнях, що індексуються БД Scopus та/або WoS.
        ];

        foreach ($data as $key => $value) {
            // Кафедра - керівника
            foreach($divisions->original['department'] as $keyDepartment => $department) {
                if ($value['supervisor']['department_code'] == $department['ID_DIV']) {
                    $value['supervisor']['department'] = $department['NAME_DIV'];
                }
            }
            // Інcтитут / факультет - керівника
            foreach($divisions->original['institute'] as $keyInstitute => $institute) {
                if ($value['supervisor']['faculty_code'] == $institute['ID_DIV']) {
                    $value['supervisor']['faculty'] = $institute['NAME_DIV'];
                }
            }

            $withStudent = 0;
            $theseWithStudent = 0;
            $withForeign = 0;
            $theseWithForeign = 0;
            $articleWithForeign = 0;
            $hasIndexHirsha = 0;
            $hasOtherOrganization = 0;
            $hasEmployees = 0;
            
            foreach ($value['authors'] as $k => $v) {
                // Кафедра - автора
                foreach($divisions->original['department'] as $keyDepartment => $department) {
                    if ($v['author']['department_code'] == $department['ID_DIV']) {
                        $v['author']['department'] = $department['NAME_DIV'];
                    }
                }

                // Інcтитут / факультет - автора
                foreach($divisions->original['institute'] as $keyInstitute => $institute) {
                    if ($v['author']['faculty_code'] == $institute['ID_DIV']) {
                        $v['author']['faculty'] = $institute['NAME_DIV'];
                    }
                }

                // Кількість статей за авторством та співавторством студентів
                if($v['author']['categ_1'] == 1) {
                    $withStudent = 1;
                }

                // Тез опублікованих зі студентами
                if($value->publication_type_id == 9 && $v['author']['categ_1'] == 1) {
                    $theseWithStudent = 1;
                }

                // Кількість публікацій у співавторстві з іноземними партнерами
                if($v['author']['country'] != "Україна") {
                    $withForeign = 1;
                }

                // Тез опублікованих з іноземними партнерами
                if($value->publication_type_id == 9 && $v['author']['country'] != "Україна") {
                    $theseWithForeign = 1;
                }

                // - статей з іноземними партнерами
                if(($value->publication_type_id == 1 || $value->publication_type_id == 2) && $v['author']['country'] != "Україна") {
                    $articleWithForeign = 1;
                }

                // Мають індекс Гірша за БД Scopus або WoS не нижче 10
                if($v['author']['h_index'] >= 10) {
                    $hasIndexHirsha = 1;
                }

                // у т.ч. за співавторством з представниками інших організацій
                if($v['author']['job'] != "СумДУ") {
                    $hasOtherOrganization = 1;
                }

                // Чисельність штатних науково та науково-педагогічних працівників, які мають не менше 5-ти публікацій у виданнях, що індексуються БД Scopus та/або WoS.
                if($value->index_scopus_wos && ($v['author']['categ_2'] == 1 || $v['author']['categ_2'] == 2) && (AuthorsPublications::where('autors_id', $v['author']['id'])->count() >= 5)) {
                    $hasEmployees = 1;
                }
            }

            $rating["countPublications"] = count($data); // Всього
            $rating["countStudentPublications"] += $withStudent; // Кількість сдудентів
            $rating["countForeignPublications"] += $withForeign; // Кількість закордонних авторів
            $rating["countIndexHirsha"] += $hasIndexHirsha; // Кількість авторів з індексом Гірша не нижче 10
            $rating["countOtherOrganization"] += $hasOtherOrganization; // за співавторством з представниками інших організацій
            $rating["countTheseWithStudent"] += $theseWithStudent; // Тез опублікованих зі студентами
            $rating["countTheseWithForeign"] += $theseWithForeign; // Тез опублікованих з іноземними партнерами
            $rating["countArticleWithForeign"] += $articleWithForeign; // статей з іноземними партнерами
            $rating["countEmployees"] += $hasEmployees; // Чисельність штатних науково та науково-педагогічних працівників

            // статті
            if($value->publication_type_id == 1 || $value->publication_type_id == 2) {
                $rating["countArticle"] += 1;
                // - статей опублікованих за кордоном
                if($value->country != "Україна") {
                    $rating["countArticleAbroad"] += 1;
                }
            }
            // інші статті
            if($value->publication_type_id == 3) {
                $rating["countOtherArticle"] += 1;
            }
            // книги
            if($value->publication_type_id == 4) {
                $rating["countBooks"] += 1;
            }
            // Посібник
            if($value->publication_type_id == 5) {
                $rating["countManuals"] += 1;
            }
            // Монографія
            if($value->publication_type_id == 6) {
                $rating["countMonographs"] += 1;
            }
            // Розділ монографії
            if($value->publication_type_id == 7) {
                $rating["countMonographSection"] += 1;
            }
            // Розділ книги
            if($value->publication_type_id == 8) {
                $rating["countBookSection"] += 1;
            }
            // Тези доповіді
            if($value->publication_type_id == 9) {
                $rating["countThese"] += 1;
                // Тез опублікованих за кордоном
                if($value->country != "Україна") {
                    $rating["countTheseAbroad"] += 1;
                }
            }

            // Патент
            if($value->publication_type_id == 10) {
                $rating["countPatent"] += 1;
            }
            // Свідоцтво про реєстрації авторських прав на твір/рішення
            if($value->publication_type_id == 11) {
                $rating["countCertificate"] += 1;
            }
            // Методичні вказівки / конспекти лекцій
            if($value->publication_type_id == 12) {
                $rating["countMethodicalInstructions"] += 1;
            }
            // Електронні видання
            if($value->publication_type_id == 13) {
                $rating["countElectronic"] += 1;
            }

            // за БД Scopus та WoS
            if($value->science_type_id == 3) {
                $rating["countScopusAndWoS"] += 1;
            }

            // за БД Scopus або WoS
            if($value->science_type_id == 1 || $value->science_type_id == 2) {
                $rating["countScopusOrWoS"] += 1;
            }

            // у тому числі у виданнях з показником SNIP більше ніж 1,0 за БД Scopus
            if($value->science_type_id == 1 && ($value->snip > 1)) {
                $rating["countSnipScopus"] += 1;
            }

            // за БД WoS - у т.ч. статті у виданнях, які входять до SCIE
            if($value->science_type_id == 2 && ($value->sub_db_index == 1)) {
                $rating["countSCIEWoS"] += 1;
            }

            // за БД WoS - у т.ч. статті у виданнях, які входять до SSCI
            if($value->science_type_id == 2 && ($value->sub_db_index == 2)) {
                $rating["countSSCIWoS"] += 1;
            }

            // за БД WoS - у т.ч. у виданнях з імпакт-фактором більше ніж 0‚5
            if($value->science_type_id == 2 && ($value->impact_factor > 0.5)) {
                $rating["countImpactFactorWoS"] += 1;
            }

            // - у т.ч. з цифровим ідентифікатором DOI
            if($value->doi) {
                $rating["countDOI"] += 1;
            }

            // - всього за 5 років за БД Scopus
            if($value->science_type_id == 1 && (date('Y') - $value->year) <= 5) {
                $rating["countFiveYearScopus"] += 1;
            }
        }

        return response()->json([
            "rating" => $rating,
            "publications" => $data
        ]);
    }

}
