<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Authors;
use App\Models\Roles;
use App\Models\Notifications;

class AuthorsController extends Controller
{

    // authors
    function getAll() {
        $data = Authors::with('role')->get();
        return response()->json($data);
    }
    function getId($id) {
        $data = Authors::with('publications', 'role', 'alias', 'notifications')->find($id);
        return response()->json($data);
    }
    function post(Request $request) {
        $model = new Authors();
        $data = $request->all();
        $response = $model->create($data);
        return response()->json($response);
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
}
