<?php

namespace App\Http\Controllers;

use App\Models\Authors;
use Illuminate\Http\Request;
use Session;
use Config;
use App\Http\Traits\AuthorTrait;
use App\Http\Traits\NotificationTrait;
use App\Models\Audit;

class AuthController extends ASUController {
    use AuthorTrait;
    use NotificationTrait;
    protected $cabinet_api = "https://cabinet.sumdu.edu.ua/api/";
    protected $cabinet_service = "https://cabinet.sumdu.edu.ua/index/service/";
    protected $cabinet_service_token;

    function __construct() {
        $this->cabinet_service_token = config('app.token');
    }

    function checkUser(Request $request) {
        $personCabinet = json_decode(file_get_contents($this->cabinet_api . 'getPerson?key=' . $request->session()->get('key') . '&token=' . $this->cabinet_service_token), true);
        if ($personCabinet['status'] == 'OK') {
            $userModel = Authors::withCount(['notifications' => function ($query) {
                $query->where('status', '=', 0);
            }])->where("name", $personCabinet['result']['surname'] . " " . $personCabinet['result']['name'] . " " . $personCabinet['result']['patronymic']);
            if ($userModel->exists()) {
                $user = $userModel->first();

                $kod_div = $user['department_code'] ? $user['department_code'] : $user['faculty_code'];

                if($kod_div && $user['categ_1'] == 2) {
                    $kod_div = $this->getAspirantDepartment($user['guid']);
                }

                $division = $this->getUserDivision($kod_div, $this->getAllDivision());
                $user['department'] = $division['department'] ? $division['department']['NAME_DIV'] : null;
                $user['faculty'] = $division['institute'] ? $division['institute']['NAME_DIV'] : null;
                return response()->json([
                    "status" => "register",
                    "user" => $user
                ]);
            } else {
                return response()->json([
                    "status" => "login",
                    "user" => $personCabinet['result']
                ]);
            }
        } else {
            return response()->json([
                "status" => "unauthorized"
            ]);
        }
    }

    function logout(Request $request) {
        $request->session()->flush();
        return response('ok', 200);
    }

    function register(Request $request) {
        $personCabinet = json_decode(file_get_contents($this->cabinet_api . 'getPersonInfo?key=' . $request->session()->get('key') . '&token=' . $this->cabinet_service_token), true);
        if(!Authors::where("guid", $personCabinet['result']['guid'])->exists()) {
            $divisions = $this->getAllDivision();
            $data = $request->all();
            $data = $this->registerUser($personCabinet['result'], $data, $divisions);
            $data['name'] = $personCabinet['result']['surname'] . " " . $personCabinet['result']['name'] . " " . $personCabinet['result']['patronymic'];
            $data['country'] = "Україна";
            $data['guid'] = $personCabinet['result']['guid'];
            $data['token'] = $personCabinet['result']['token'];
            $data['test_data'] = json_encode($personCabinet['result']);

            $userModel = new Authors();
            $newUser = $userModel->create($data);

            $request->session()->put('person', $newUser);
            return response()->json('ok', 200);
        } else {
            return response()->json([
                "message" => "Користувач вже зареєстрований в системі"
            ]);
        }
    }

    function index(Request $request) {
        $this->mode($request);

        if(!isset($request->key)) {
            return view('app', [
                "status" => "unauthorized"
            ]);
        }
        $personCabinet = json_decode(file_get_contents($this->cabinet_api . 'getPersonInfo?key=' . $request->key . '&token=' . $this->cabinet_service_token), true);
        if ($personCabinet['status'] == 'OK') {
            $request->session()->put('key', $request->key);
            $userModel = Authors::where("guid", $personCabinet['result']['guid']);
            if($userModel->exists()) {
                $divisions = $this->getAllDivision();
                $data = $userModel->first();
                $data2 = clone $userModel->first();
                $person = $this->registerUser($personCabinet['result'], $data, $divisions);
                $person['test_data'] = json_encode($personCabinet['result']);

                $userModel->update($person->toArray());
                $request->session()->put('person', $person);

                $notificationText = "";
                $notificationText .= $this->notification($person, $data2, "name_div", "факультет / кафедру");
                if($notificationText != "") {
                    $notificationText = "Оновлено інформацію про автора <a href=\"/user/". $request->session()->get('person')['id'] ."\">" . $request->session()->get('person')['name'] . "</a>:<br>" . $notificationText;
                    Audit::create([
                        "text" => $notificationText
                    ]);
                }

                return view('app', [
                    "status" => "register",
                    "user" => $person
                ]);
            } else {
                return view('app', [
                    "status" => "login"
                ]);
            }
        } else {
            return view('app', [
                "status" => "unauthorized"
            ]);
        }
    }

    function mode($request) {
        $info = 'Інформаційний сервіс «Наукові публікації» дозволить Вам зручно вести облік Ваших наукових та науково-методичних публікацій'; // Service description
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
    }
}
