<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Authors;
use App\Models\Users;
use App\Models\Roles;
use App\Models\Notifications;
use Carbon\Carbon;
use Session;

class AuthorsController extends ASUController
{
    // authors
    function get(Request $request) {
        $divisions = $this->getDivisions();
        $data = [];
        switch ($request->session()->get('person')['roles_id']) {
            case 2:
                $data = Authors::with('role')->where('faculty_code', $request->session()->get('person')['faculty_code'])->get();
            break;
            case 3:
                $data = Authors::with('role')->where('department_code', $request->session()->get('person')['department_code'])->get();
            break;
            case 4:
                $data = Authors::with('role')->where('roles_id', '!=', 4)->get();
            break;
        }
        // $data = Authors::with('role')->get();
        foreach ($data as $key => $value) {
            foreach($divisions->original['department']  as $k => $v) {
                if ($value['department_code'] == $v['ID_DIV']) {
                    $value['department'] = $v['NAME_DIV'];
                }
            }
            foreach($divisions->original['institute'] as $k => $v) {
                if ($value['faculty_code'] == $v['ID_DIV']) {
                    $value['faculty'] = $v['NAME_DIV'];
                }
            }
        }
        return response()->json($data);
    }

    // authors All (admin)
    function getAll(Request $request) {
        $divisions = $this->getDivisions();
        $data = Authors::with('role')->get();
        foreach ($data as $key => $value) {
            foreach($divisions->original['department']  as $k => $v) {
                if ($value['department_code'] == $v['ID_DIV']) {
                    $value['department'] = $v['NAME_DIV'];
                }
            }
            foreach($divisions->original['institute'] as $k => $v) {
                if ($value['faculty_code'] == $v['ID_DIV']) {
                    $value['faculty'] = $v['NAME_DIV'];
                }
            }
        }
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
<<<<<<< HEAD
        if(!Authors::where("name", "like", $request->name)->orWhere("guid", $request->guid)->exists()) {
=======
        if(!Authors::where("name", "like", $request->name)->exists()) {
            $divisions = $this->getDivisions();
>>>>>>> master
            $model = new Authors();
            $data = $request->all();
            $response = $model->create($data);

            foreach($divisions->original['department']  as $k => $v) {
                if ($response['department_code'] == $v['ID_DIV']) {
                    $response['department'] = $v['NAME_DIV'];
                }
            }
            foreach($divisions->original['institute'] as $k => $v) {
                if ($response['faculty_code'] == $v['ID_DIV']) {
                    $response['faculty'] = $v['NAME_DIV'];
                }
            }

            return response()->json([
                "status" => "ok",
                "user" => $response
            ]);
        } else {
            return response()->json([
                "status" => "error"
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
