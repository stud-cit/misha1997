<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Authors;
use Illuminate\Http\Request;

class PassportController extends Controller
{

    function checkCabinet() {

        session_start();
        $info = "Наукові публікації";  // Service description
        $icon = public_path() . "/service.png";      // Service icon (48x48)
        $mask = 13;         // Service modes 3,2,0 (1101 bits)

        $cabinet_api = "https://cabinet.sumdu.edu.ua/api/";
        $cabinet_service = "https://cabinet.sumdu.edu.ua/index/service/";
        $cabinet_service_token = env('APP_TOKEN');

        // Получаем параметры GET запроса

        $key = !empty($_REQUEST['key']) ? $_REQUEST['key'] : "";
        $mode = !empty($_REQUEST['mode']) ? $_REQUEST['mode'] : 0;

        // В зависимости от режима (mode) возвращаем или иконку, или описание, или специальный заголовок

        if (!empty($key)) {
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

        // Если ключ не передается, но он сохранен в сессии, берем из сессии. Ключ храним
        // в сессии для запроса на подтверждение, что пользователь все еще авторизован в кабинете.

        if (empty($key) && !empty($_SESSION['key'])) $key = $_SESSION['key'];
        if (!empty($key)) {
            $person = json_decode(file_get_contents($cabinet_api . 'getPerson?key=' . $key . '&token=' . $cabinet_service_token), true);
            if ($person['status'] == 'OK') {
                $_SESSION['key'] = $key;
                $_SESSION['person'] = $person;
            } else {
                unset($_SESSION['key']);
                unset($_SESSION['person']);
            }
        }
    }

    function checkLogin() {
        $this->checkCabinet();
        if (isset($_SESSION['person'])) {
            return response("ok", 200);
        } else {
            return response("error", 200);
        }
    }

    function checkRegister() {
        $this->checkCabinet();
        if (isset($_SESSION['person'])) {
            if (Authors::where("guid", $_SESSION['person']['result']['guid'])->exists()) {
                return response()->json([
                    "status" => "ok",
                    "userId" => $_SESSION['person']['result']['guid']
                ]);
            } else {
                return response("error", 200);
            }
        } else {
            return response("error", 200);
        }
    }

    function register(Request $request) {
        session_start();
        $getPersonInfo1 = json_decode(file_get_contents('https://cabinet.sumdu.edu.ua/api/getPersonInfo1?key='.$_SESSION['key']), true);
        $getPersonInfo2 = json_decode(file_get_contents('https://cabinet.sumdu.edu.ua/api/getPersonInfo2?key='.$_SESSION['key']), true);

        $user = new Authors;
        $user->guid = $_SESSION['person']['result']['guid'];
        $user->job = $getPersonInfo2['result'][0]['NAME_DIV'];
        $user->name = $_SESSION['person']['result']['surname']." ".$_SESSION['person']['result']['name']." ".$_SESSION['person']['result']['patronymic'];
        $user->country = $request->country;
        $user->h_index = $request->h_index;
        $user->scopus_autor_id = $request->scopus_autor_id;
        $user->scopus_researcher_id = $request->scopus_researcher_id;
        $user->orcid = $request->orcid;
        $user->faculty = $getPersonInfo1['result'][0]['NAME_DIV'];
        $user->academic_code = $getPersonInfo1['result'][0]['NAME_GROUP'];
        $user->email = $_SESSION['person']['result']['email'];
        $user->roles_id = 1;
        $user->save();

        return response('ok', 200);
    }

    function index() {
        $this->checkCabinet();
        return view('app');
    }

    function details() {
        return response()->json(['user' => auth()->user()], 200);
    }
}
