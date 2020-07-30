<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Authors;
use App\Models\AutorsAliases;
use App\Models\Users;
use App\Models\Roles;
use App\Models\Notifications;

class AuthorsController extends Controller
{
    // authors
    function getAll() {
        $data = Authors::with('role', 'alias')->get();
        return response()->json($data);
    }

    //cabinet users
    function getPersons($categ, $id) {
        $asu_key = 'eRi1FIAppqFDryG2PFaYw75S1z4q2ZoG';
        $mode = 1;
        $getPersons = file_get_contents('https://asu.sumdu.edu.ua/api/getContingents?key='.$asu_key.'&mode='.$mode.'&'.$categ.'='.$id);
        $getPersons = json_decode($getPersons, true);
        return response()->json($getPersons);
    }

    function getId($id) {
        $data = Authors::with('publications', 'role', 'alias', 'notifications')->where("guid", $id)->first();
        return response()->json($data);
    }

    function getLoginUser() {
        session_start();
        $data = [];

        $getPersonInfo1 = json_decode(file_get_contents('https://cabinet.sumdu.edu.ua/api/getPersonInfo1?key='.$_SESSION['key']), true);

        $data['guid'] = $_SESSION['person']['result']['guid'];
        $data['name'] = $_SESSION['person']['result']['surname']." ".$_SESSION['person']['result']['name']." ".$_SESSION['person']['result']['patronymic'];
        $data['email'] = $_SESSION['person']['result']['email'];
        $data['faculty'] = $getPersonInfo1['result'][0]['NAME_DIV'];

        return response()->json($data);
    }

    function postAuthor(Request $request) {
        $model = new Authors();
        $data = $request->all();
        $response = $model->create($data);
        if($request->alias != "") {
            $alias = new AutorsAliases;
            $alias->surname_initials = $request->alias;
            $alias->autors_id = $response->id;
            $alias->save();
        }
        $response->alias = [];
        return response()->json($response);
    }

    function updateAuthor(Request $request, $id) {
        $edit_author = Authors::find($id);
        $edit_alias = AutorsAliases::where("autors_id", $id)->first();
        $edit_notifications = Notifications::where("autors_id", $id)->first();

        $edit_author->id = $request->id;
        $edit_author->name = $request->name;
        $edit_author->guid = $request->guid;
        $edit_author->job = $request->job;
        $edit_author->country = $request->country;
        $edit_author->h_index = $request->hIndex;
        $edit_author->scopus_autor_id = $request->scopusAutorId;
        $edit_author->scopus_researcher_id = $request->scopusResearcherId;
        $edit_author->orcid = $request->orcid;
        $edit_author->department = $request->department;
        $edit_author->faculty = $request->faculty;
        $edit_author->academic_code = $request->academicCode;
        $edit_author->email = $request->email;
        $edit_author->roles_id = $request->rolesId;

        if($request->id == $edit_alias->autors_id) {
            //$alias = AutorsAliases::find($id);
            $edit_alias->surname_initials = $request->alias;
            $edit_alias->save();
        }

        if($request->id == $edit_notifications->autors_id) {
            //$notifications = Notifications::find($id);
            $edit_notifications->text = $request->text;
            $edit_notifications->save();
        }
        $edit_author->save();
    }

    function deleteAuthor($id) {
        $model = Authors::with('alias', 'notifications')->find($id);
        $model->delete();
        return response('ok', 200);
    }

    function getRoles() {
        $data = Roles::get();
        return response()->json($data);
    }

    //notifications
    function getNotifications($autors_id) {
        $data = Notifications::where('autors_id', $autors_id)->get();
        return response()->json($data);
    }
    function postNotifications(Request $request, $autors_id) {
        $model = new Notifications();
        $data = $request->all();
        $data["autors_id"] = $autors_id;
        $model->create($data);
        return response('ok', 200);
    }
    function postAlias(Request $request) {
        $model = new AutorsAliases();
        $data = $request->all();
        $response = $model->create($data);
        return response()->json($response);
    }
}
