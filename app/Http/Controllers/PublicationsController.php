<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publications;
use App\Models\Articles;
use App\Models\Patents;
use App\Models\Certificates;
use App\Models\MethodicalInstructions;
use App\Models\Textbooks;
use App\Models\ElectronicPublications;
use App\Models\Abstracts;
use App\Models\Manuals;
use App\Models\Monographs;
use App\Models\AuthorsPublications;
use App\Models\Countries;

class PublicationsController extends Controller
{
    function getAll() {
        $data = AuthorsPublications::with("autors", "publications")->get();
        return response()->json($data);
    }
    function getId($id) {
        $data = Publications::with('type', 'article')->find($id);
        return response()->json($data);
    }
    function post(Request $request, $type) {
        $types = [
            'articles' => new Articles(),
            'patents' => new Patents(),
            'certificates' => new Certificates(),
            'methodical_instructions' => new MethodicalInstructions(),
            'textbooks' => new Textbooks(),
            'electronic_publications' => new ElectronicPublications(),
            'abstracts' => new Abstracts(),
            'manuals' => new Manuals(),
            'monographs' => new Monographs()
        ];

        $modelPublications = new Publications();
        $dataPublications = $request->all();
        $dataPublications['type'] = $dataPublications['type']['title'];
        $response = $modelPublications->create($dataPublications);

        $dataType = $request->publication;
        $dataType['publications_id'] = $response->id;
        $types[$type]->create($dataType);

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

        $foreignPublications = Publications::with(
            "author",
            "article",
            "certificates",
            "abstracts",
            "electronicPublications",
            "manuals",
            "methodicalInstructions",
            "monographs",
            "patents",
            "textbooks"
        )->get();

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

            if($value->article) {
                $data["countArticle"] += 1;
            }
            if(isset($value->textbooks)) {
                $data["countTextbooks"] += 1;
            }
            if(isset($value->manuals)) {
                $data["countManuals"] += 1;
            }
            if(isset($value->monographs)) {
                $data["countMonographs"] += 1;
            }
            if(isset($value->article)) {
                $data["countArticle"] += 1;
            }

            if($value->science_type_id) {
                $data["countScopusWosPublications"] += 1;
            }
        }

        return response()->json($data);
    }

}
