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

}
