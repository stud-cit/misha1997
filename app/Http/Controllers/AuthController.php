<?php

namespace App\Http\Controllers;

use App\Models\Authors;
use Illuminate\Http\Request;
use Session;

class AuthController extends Controller
{

    protected $cabinet_api = "https://cabinet.sumdu.edu.ua/api/";
    protected $cabinet_service = "https://cabinet.sumdu.edu.ua/index/service/";
    protected $cabinet_service_token = "7B4DIDiV";

    function checkUser(Request $request) {
        $person = json_decode(file_get_contents($this->cabinet_api . 'getPerson?key=' . $request->header('Authorization') . '&token=' . $this->cabinet_service_token), true);
        // if($person['result']['categ1'] == 10) {
        //     return response()->json([
        //         "message" => "Сервіс дступний лише для співробітників та аспірантів."
        //     ]);
        // }
        if ($person['status'] == 'OK') {
            if (Authors::where("guid", $person['result']['guid'])->exists()) {
                $person = Authors::where("guid", $person['result']['guid'])->first();
                $request->session()->put('person', $person);
                return response()->json([
                    "status" => "register",
                    "user" => $person
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
        $cabinetInfo = json_decode(file_get_contents($this->cabinet_api . 'getPersonInfo?key='.$request->header('Authorization')), true);
        if(!Authors::where("guid", $cabinetInfo['result']['guid'])->exists()) {
            $categ1 = null;
            $categ2 = null;
            if($cabinetInfo['result']['categ1'] == 10) {
                $categ1 = 1;
            } elseif($cabinetInfo['result']['categ1'] == 12) {
                $categ1 = 2;
            } else {
                $categ1 = 0;
            }
            if($cabinetInfo['result']['categ2'] == 10) {
                $categ2 = 1;
            } elseif($cabinetInfo['result']['categ2'] == 12) {
                $categ2 = 2;
            } else {
                $categ2 = 0;
            }
            $result = [
                "guid" => $cabinetInfo['result']['guid'],
                "name" => $cabinetInfo['result']['surname']." ".$cabinetInfo['result']['name']." ".$cabinetInfo['result']['patronymic'],
                "email" => $cabinetInfo['result']['email'],
                "job" => "СумДУ",
                "faculty_code" => $cabinetInfo['result']['info1'][0]['KOD_DIV'] ? $cabinetInfo['result']['info1'][0]['KOD_DIV'] : "",
                "department_code" => $cabinetInfo['result']['info1'][0]['NAME_DIV'] ? $cabinetInfo['result']['info1'][0]['NAME_DIV'] : "",
                "country" => $request->country,
                "h_index" => $request->h_index,
                "scopus_autor_id" => $request->scopus_autor_id,
                "scopus_researcher_id" => $request->scopus_researcher_id,
                "orcid" => $request->orcid,
                "categ_1" => $categ1,
                "categ_2" => $categ2
            ];

            $model = new Authors();
            $response = $model->create($result);

            return response()->json($response);
        } else {
            return response()->json([
                "message" => "Користувач вже зареєстрований в системі"
            ]);
        }
    }

    function index(Request $request) {
        $info = "Наукові публікації"; // Service description
        $icon = public_path() . "/service.png"; // Service icon (48x48)
        $mask = 13; // Service modes 3,2,0 (1101 bits)

        $mode = !empty($request->mode) ? $request->mode : 0;

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
        return view('app');
    }
}
