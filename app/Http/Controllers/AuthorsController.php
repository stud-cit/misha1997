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

    //cabinet users
    function getPersons($name) {
        $key = 'cVzdHvhDsBX7itCbMsAjYZYhNsZqH7xiiBF2bNM8mZXoR0Aqcp91';
        $getPersons = file_get_contents('https://cabinet.sumdu.edu.ua/api/getPersons?key='.$key.'&search='.$name);
        $getPersons = json_decode($getPersons, true);

        function arrayColumn1($getPersons, $column){
            $values = [];
            foreach ($getPersons as $k => $v) {
                if ($k == $column){
                    $values[] = $v;
                }
                elseif (is_array($v)) {
                    $values = array_merge($values, array_column($v, $column));
                }
            }
            return $values;
        }

        $getPersonInfo1 = file_get_contents('https://cabinet.sumdu.edu.ua/api/getPersonInfo1?key='.$key);
        $getPersonInfo1 = json_decode($getPersonInfo1, true);

        function arrayColumn2($getPersonInfo1, $column){
            $values = [];
            foreach ($getPersonInfo1 as $k => $v) {
                if ($k == $column){
                    $values[] = $v;
                }
                elseif (is_array($v)) {
                    $values = array_merge($values, array_column($v, $column));
                }
            }
            return $values[0];
        }

        $result = [];
        $nameArray = arrayColumn1($getPersons, 'name');
        $countName = count($nameArray);
        $userName = array_chunk($nameArray, $countName, true);

        /*
        $userFacult = arrayColumn2($getPersonInfo1, 'NAME_DIV');
        $userKafedra = arrayColumn2($getPersonInfo1, 'ABBR_DIV');
        $userRole = ' ';
        $userEmail = ' ';
        */

        function insert($i, $userName)
        {
            for ($j = 0; $j <= count($userName); $j++) {
                foreach ($userName as $key_arr => $val_arr) {
                    $users['name'] = $val_arr[$i];
                    return $users;
                }
            }
        }
        for ($i = 0; $i <= $countName - 1; $i++) {
            array_push($result, insert($i, $userName));
        }
        return $result;
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
