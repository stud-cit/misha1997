<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publications;
use App\Models\AuthorsPublications;
use App\Models\Countries;
use App\Models\PublicationType;

class PublicationsController extends Controller
{
    function getAll() {
        $data = Publications::with('publicationType', 'scienceType')->get();
        foreach ($data as $key => $value) {
            $value->authors = AuthorsPublications::with('author')->where('publications_id', $value->id)->get();
        }
        return response()->json($data);
    }
    function getId($id) {
        $data = Publications::with('publicationType', 'scienceType')->find($id);
        $data->authors = AuthorsPublications::with('author')->where('publications_id', $id)->get();
        return response()->json($data);
    }

    function post(Request $request) {
        $modelPublications = new Publications();
        $dataPublications = $request->all();
        $dataPublications['publication_type_id'] = $dataPublications['publication_type']['id'];
        $response = $modelPublications->create($dataPublications);

        foreach ($request->authors as $key => $value) {
            $authorsPublications = new AuthorsPublications;
            $authorsPublications->autors_id = $value['id'];
            foreach ($value['alias'] as $k => $v) {
                if($v['select']) {
                    $authorsPublications->autors_aliases_id = $v['id'];
                }
            }
            $authorsPublications->publications_id = $response->id;
            $authorsPublications->save();
        }

        if($request->supervisor) {
            $authorsPublications = new AuthorsPublications;
            $authorsPublications->autors_id = $request->supervisor['id'];
            $authorsPublications->publications_id = $response->id;
            $authorsPublications->supervisor = 1;
            $authorsPublications->save();
        }

        return response('ok', 200);
    }

    function getCountry() {
        $data = Countries::get();
        return response()->json($data);
    }

    function typePublications() {
        $data = PublicationType::get();
        return response()->json($data);
    }

    function Export() {

        $data = [
            "countPublications" => 0,
            "countForeignPublications" => 0,
            "countIndexHirsha" => 0,
            "countArticle" => 0,
            "countTextbooks" => 0,
            "countManuals" => 0,
            "countMonographs" => 0,
            "countScopusWosPublications" => 0,
        ];

        $data["countPublications"] = Publications::count();

        $foreignPublications = Publications::with('author', 'publicationType', 'scienceType')->get();

        // return response()->json($foreignPublications);

        foreach ($foreignPublications as $key => $value) {
            $foreign = 0;
            foreach ($value['author'] as $k => $v) {
                if($v['country'] != "Україна") {
                    $foreign = 1;
                }
                if($v['h_index'] >= 10) {
                    $data["countIndexHirsha"] += 1;
                }
            }
            $data["countForeignPublications"] += $foreign;

            if($value->publicationType['type'] == 'article') {
                $data["countArticle"] += 1;
            }
            if($value->publicationType['type'] == 'book') {
                $data["countTextbooks"] += 1;
            }

            if($value->science_type_id) {
                $data["countScopusWosPublications"] += 1;
            }
        }

        return response()->json($data);
    }

}
