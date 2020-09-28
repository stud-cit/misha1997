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
        $data = Publications::with('authors')
            ->where('country', 'like', "%".$request->country."%") // Країна видання
            ->where('publication_type_id', 'like', "%".$request->publication_type_id."%") // Вид публікацій
            ->get();

        if($request->year) {
            $data = $data->where('year', $request->year); // Рік видання
        }

        if($request->doi == 1) {
            $data = $data->whereNotNull('doi'); // Публікації з цифровим ідентифікатором DOI
        } elseif($request->doi != "" && $request->doi == 0) {
            $data = $data->whereNull('doi'); // Публікації без цифрового ідентифікатора DOI
        }

        if($request->applicant == "СумДУ") {
            $data = $data->where('applicant', 'СумДУ'); // Охоронні документи СумДУ
        } elseif($request->applicant != "" && $request->applicant != "СумДУ") {
            $data = $data->where('applicant', '!=', 'СумДУ'); // Охоронні документи не СумДУ
        }

        if($request->commercial_applicant == 1) {
            $data = $data->where('commerc_university', 1); // Комерціалізовані охоронні документи (Університетом)
        } elseif($request->commercial_applicant != "" && $request->commercial_applicant == 2) {
            $data = $data->where('commerc_employees', 1); // Комерціалізовані охоронні документи (Штатними співробітниками)
        }

        if($request->science_type_id) {
            $data = $data->where('science_type_id', $request->science_type_id); // Індексування БД Scopus\WoS
        }

        if($request->quartil_scopus) {
            $data = $data->where('quartil_scopus', $request->quartil_scopus); // Квартиль журналу SCOPUS
        }

        if($request->quartil_wos) {
            $data = $data->where('quartil_wos', $request->quartil_wos); // Квартиль журналу WOS
        }

        if($request->snip) {
            $data = $data->where('snip', '>', 1); // Публікації опубліковані у виданнях з показником SNIP більше ніж 1,0
        }

        if($request->abroad == 1) {
            $data = $data->where('country', '!=', 'Україна'); // Публікації опубліковані за кордоном
        }

        // return response()->json($data);

        $rating = [
            "countPublications" => 0,
            "countForeignPublications" => 0,
            "countIndexHirsha" => 0,
            "countArticle" => 0,
            "countTextbooks" => 0,
            "countManuals" => 0,
            "countMonographs" => 0,
            "countScopusWosPublications" => 0,
        ];

        $rating["countPublications"] = count($data);

        foreach ($data as $key => $value) {
            $foreign = 0;
            foreach ($value['author'] as $k => $v) {
                if($v['country'] != "Україна") {
                    $foreign = 1;
                }
                if($v['h_index'] >= 10) {
                    $rating["countIndexHirsha"] += 1;
                }
            }
            $rating["countForeignPublications"] += $foreign;

            if($value->publicationType['type'] == 'article') {
                $rating["countArticle"] += 1;
            }
            if($value->publicationType['type'] == 'book') {
                $rating["countTextbooks"] += 1;
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
