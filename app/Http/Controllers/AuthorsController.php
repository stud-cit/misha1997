<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Authors;
use App\Models\Users;
use App\Models\Roles;
use App\Models\Notifications;
use Carbon\Carbon;
use Session;

class AuthorsController extends Controller
{
    // authors
    function get(Request $request) {
        $data = [];
        switch ($request->session()->get('person')['roles_id']) {
            case 2:
                $data = Authors::with('role')->where('faculty', $request->session()->get('person')['faculty'])->get();
            break;
            case 3:
                $data = Authors::with('role')->where('department', $request->session()->get('person')['department'])->get();
            break;
            case 4:
                $data = Authors::with('role')->get();
            break;
        }
        return response()->json($data);
    }

    // authors All (admin)
    function getAll(Request $request) {
        $data = Authors::with('role')->get();
        return response()->json($data);
    }

    // cabinet users (add publication page)
    function getPersons($categ, $id) {
        $asu_key = 'eRi1FIAppqFDryG2PFaYw75S1z4q2ZoG';
        $mode = 1;
        $getPersons = file_get_contents('https://asu.sumdu.edu.ua/api/getContingents?key='.$asu_key.'&mode='.$mode.'&'.$categ.'='.$id);
        $getPersons = json_decode($getPersons, true);
        return response()->json($getPersons);
    }

    // profile
    function profile(Request $request) {
        $data = Authors::with('role')->find($request->session()->get('person')['id']);
        return response()->json($data);
    }

    // updateProfile
    function updateProfile(Request $request) {
        $data = $request->all();
        Authors::find($request->session()->get('person')['id'])->update($data);
        $user = Authors::find($request->session()->get('person')['id']);
        return response()->json($user);
    }

    // getId
    function getId($id) {
        $data = Authors::with('publications', 'role', 'notifications')->where("id", $id)->first();
        return response()->json($data);
    }

    // postAuthor (add publication page)
    function postAuthor(Request $request) {
        if(!Authors::where("name", "like", $request->name)->exists()) {
            $model = new Authors();
            $data = $request->all();
            $response = $model->create($data);
            return response()->json([
                "status" => "ok",
                "user" => $response
            ]);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "Автор вже зареєстрований в системі"
            ]);
        }
    }

    // updateAuthor
    function updateAuthor(Request $request, $id) {
        $data = $request->all();
        Authors::find($id)->update($data);
        return response('ok', 200);
    }

    // deleteAuthor
    function deleteAuthor($id) {
        Authors::with('notifications')->find($id)->delete();
        return response('ok', 200);
    }

    // getRoles
    function getRoles() {
        $data = Roles::get();
        return response()->json($data);
    }

    //notifications
    function getNotifications($autors_id) {
        $data = Notifications::where('autors_id', $autors_id)->get();
        foreach ($data as $key => $value) {
            $value['date'] = Carbon::parse($value->created_at)->format('d.m.Y H:i');
        }
        return response()->json($data);
    }
    function postNotifications(Request $request, $autors_id) {
        $model = new Notifications();
        $data = $request->all();
        $data["autors_id"] = $autors_id;
        $model->create($data);
        return response('ok', 200);
    }
    function editNotifications(Request $request, $id, $autors_id) {
        Notifications::find($id)->update([
            "status" => $request->status
        ]);
        return response('ok', 200);
    }
}
