<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publications;
use App\Models\AuthorsPublications;
use App\Models\Countries;
use App\Models\PublicationType;
use App\Models\Notifications;

class PublicationsController extends Controller
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
        $data = Publications::with('publicationType', 'scienceType', 'supervisor')->find($id);
        $data->authors = AuthorsPublications::with('author')->where('publications_id', $id)->get();
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
        $data = Publications::with('authors.author')
            ->where('country', 'like', "%".$request->country."%") // Країна видання
            ->where('publication_type_id', 'like', "%".$request->publication_type_id."%"); // Вид публікацій

        if($request->year) {
            $data->where('year', $request->year); // Рік видання
        }

        if($request->doi == 1) {
            $data->whereNotNull('doi'); // Публікації з цифровим ідентифікатором DOI
        } elseif($request->doi != "" && $request->doi == 0) {
            $data->whereNull('doi'); // Публікації без цифрового ідентифікатора DOI
        }

        if($request->applicant == "СумДУ") {
            $data->where('applicant', 'СумДУ'); // Охоронні документи СумДУ
        } elseif($request->applicant != "" && $request->applicant != "СумДУ") {
            $data->where('applicant', '!=', 'СумДУ'); // Охоронні документи не СумДУ
        }

        if($request->commercial_applicant == 1) {
            $data->where('commerc_university', 1); // Комерціалізовані охоронні документи (Університетом)
        } elseif($request->commercial_applicant != "" && $request->commercial_applicant == 2) {
            $data->where('commerc_employees', 1); // Комерціалізовані охоронні документи (Штатними співробітниками)
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

        if($request->faculty) { // Інститут / факультет
            $data->whereHas('authors', function($query) use($request) {
                $query->whereHas('author', function($q) use($request) {
                    $q->where('faculty_code', $request->faculty);
                });
            });
        }

        if($request->department) { // Кафедра
            $data->whereHas('authors', function($query) use($request) {
                $query->whereHas('author', function($q) use($request) {
                    $q->where('department_code', $request->department);
                });
            });
        }

        if($request->withForeigners) { // Публікації у співавторстві з іноземними партнерами
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

        if($request->other_organization) { // Публікації за співавторством з представниками інших організацій
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

        $data = $data->get();

        // return response()->json($data->get());

        $rating = [
            "countPublications" => 0,
            "countForeignPublications" => 0,
            "countIndexHirsha" => 0,
            "countArticle" => 0, // кількість статтей
            "countOtherArticle" => 0, // кількість інших статтей
            "countBooks" => 0, // кількість книг
            "countManuals" => 0, // кількість посібників
            "countMonographs" => 0, // Монографія
            "countMonographSection" => 0, // Розділ монографії
            "countBookSection" => 0, // Розділ монографії
            "countThese" => 0, // Тези доповіді
            "countPatent" => 0, // Патент
            "countCertificate" => 0, // Свідоцтво про реєстрації авторських прав на твір/рішення
            "countMethodicalInstructions" => 0, // Методичні вказівки / конспекти лекцій
            "countElectronic" => 0, // Електронні видання
            "countScopusWosPublications" => 0,
        ];

        $rating["countPublications"] = count($data);

        foreach ($data as $key => $value) {
            $foreign = 0;
            foreach ($value['authors'] as $k => $v) {
                if($v['author']['country'] != "Україна") {
                    $foreign = 1;
                }
                if($v['author']['h_index'] >= 10) {
                    $rating["countIndexHirsha"] += 1;
                }
            }
            $rating["countForeignPublications"] += $foreign; // Кількість закордонних авторів

            // статті
            if($value->publication_type_id == 1 || $value->publication_type_id == 2) {
                $rating["countArticle"] += 1;
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


            if($value->science_type_id) {
                $rating["countScopusWosPublications"] += 1;
            }
        }

        return response()->json([
            "rating" => $rating,
            "publications" => $data
        ]);
    }

}
