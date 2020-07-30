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
        $data = Publications::with('publicationType', 'scienceType', 'supervisor')->get();

        foreach ($data as $key => $value) {
            $value->authors = AuthorsPublications::with('author')->where('publications_id', $value->id)->get();
        }
        return response()->json($data);
    }
    function getId($id) {
        $data = Publications::with('publicationType', 'scienceType', 'supervisor')->find($id);
        $data->authors = AuthorsPublications::with('author')->where('publications_id', $id)->get();
        return response()->json($data);
    }

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
            foreach ($value['alias'] as $k => $v) {
                if($v['select']) {
                    $authorsPublications->autors_aliases_id = $v['id'];
                }
            }
            $authorsPublications->publications_id = $response->id;
            $authorsPublications->save();
        }

        return response('ok', 200);
    }

    function editPublication(Request $request, $id)
    {
        $edit_publication = Publications::find($id);
        $edit_publication->title = $request->title;
        $edit_publication->science_type_id = $request->scienceTypeId;
        $edit_publication->publication_type_id = $request->publicationTypeId;
        $edit_publication->snip = $request->snip;
        $edit_publication->impact_factor = $request->impactFactor;
        $edit_publication->quartil_scopus = $request->quartilScopus;
        $edit_publication->quartil_wos = $request->quartilWos;
        $edit_publication->sub_db_index = $request->subDbIndex;
        $edit_publication->year = $request->year;
        $edit_publication->number = $request->number;
        $edit_publication->pages = $request->pages;
        $edit_publication->initials = $request->initials;
        $edit_publication->country = $request->country;
        $edit_publication->name_magazine = $request->nameMagazine;
        $edit_publication->number_volumes = $request->numberVolumes;
        $edit_publication->by_editing = $request->byEditing;
        $edit_publication->city = $request->city;
        $edit_publication->editor_name = $request->editorName;
        $edit_publication->languages = $request->Languages;
        $edit_publication->number_certificate = $request->numberCertificate;
        $edit_publication->applicant = $request->applicant;
        $edit_publication->date_application = $request->dateApplication;
        $edit_publication->date_publication = $request->datePublication;
        $edit_publication->commerc_university = $request->commercUniversity;
        $edit_publication->commerc_employees = $request->commercEmployees;
        $edit_publication->access_mode = $request->accessMode;
        $edit_publication->mpk = $request->mpk;
        $edit_publication->application_number = $request->applicationNumber;
        $edit_publication->newsletter_number = $request->newsletterNumber;
        $edit_publication->name_conference = $request->nameConference;
        $edit_publication->publisher = $request->publisher;
        $edit_publication->doi = $request->doi;
        $edit_publication->supervisor_id = $request->supervisorId;
        $edit_publication->save();
    }

    function deletePublication(Request $request, $id) {
        $model = Publications::with('publicationType', 'scienceType', 'supervisor')->find($id);
        $model->delete();
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

        return error;
    }

}
