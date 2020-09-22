<?php

namespace App\Http\Controllers;

use App\Models\Authors;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    protected $cabinet_api = "https://cabinet.sumdu.edu.ua/api/";
    protected $cabinet_service = "https://cabinet.sumdu.edu.ua/index/service/";
    protected $cabinet_service_token = "TNWcmzpZ";

    // служебная информация
    function mode() {
        $info = "Наукові публікації"; // Service description
        $icon = public_path() . "/service.png"; // Service icon (48x48)
        $mask = 13; // Service modes 3,2,0 (1101 bits)

        $mode = !empty($_REQUEST['mode']) ? $_REQUEST['mode'] : 0;

        // В зависимости от режима (mode) возвращаем или иконку, или описание, или специальный заголовок
        switch($mode) {
            case 0:
                break;
            case 2:
                header('Content-Type: image/png');
                readfile($icon);
                exit;
            case 3:
                echo $info;
                exit;
            case 100;
                header('X-Cabinet-Support: ' . $mask);
            default:
                exit;
        }
    }

    function checkUser(Request $request) {
        $person = json_decode(file_get_contents($this->cabinet_api . 'getPerson?key=' . $request->header('Authorization') . '&token=' . $this->cabinet_service_token), true);
        if ($person['status'] == 'OK') {
            if (Authors::where("guid", $person['result']['guid'])->exists()) {
                return response()->json([
                    "status" => "register",
                    "user" => $person['result']
                ]);
            } else {
                return response()->json([
                    "status" => "login"
                ]);
            }
        } else {
            return response()->json([
                "status" => "unauthorized"
            ]);
        }
    }

    function register(Request $request) {
        $getPersonInfo1 = json_decode(file_get_contents($this->cabinet_api . 'getPersonInfo1?key='.$request->header('Authorization')), true);
        $getPersonInfo2 = json_decode(file_get_contents($this->cabinet_api . 'getPersonInfo2?key='.$request->header('Authorization')), true);

        $person = json_decode(file_get_contents($this->cabinet_api . 'getPerson?key=' . $request->header('Authorization') . '&token=' . $this->cabinet_service_token), true);
        if(!Authors::where("guid", $person['result']['guid'])->exists()) {
            $user = new Authors;
            $user->guid = $person['result']['guid'];
            $user->job = $getPersonInfo2['result'][0]['NAME_DIV'];
            $user->name = $person['result']['surname']." ".$person['result']['name']." ".$person['result']['patronymic'];
            $user->country = $request->country;
            $user->h_index = $request->h_index;
            $user->scopus_autor_id = $request->scopus_autor_id;
            $user->scopus_researcher_id = $request->scopus_researcher_id;
            $user->orcid = $request->orcid;
            $user->faculty = $getPersonInfo1['result'][0]['NAME_DIV'];
            $user->academic_code = $getPersonInfo1['result'][0]['NAME_GROUP'];
            $user->email = $person['result']['email'];
            $user->roles_id = 1;
            $user->save();
            return response()->json($person['result']);
        } else {
            return response()->json([
                "message" => "Користувач вже зареєстрований в системі"
            ]);
        }
    }

    function index() {
        $this->mode();
        return view('app');
    }
}
