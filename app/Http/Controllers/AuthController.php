<?php

namespace App\Http\Controllers;

use App\Models\Authors;
use Illuminate\Http\Request;
use Session;

class AuthController extends ASUController
{

    protected $cabinet_api = "https://cabinet.sumdu.edu.ua/api/";
    protected $cabinet_service = "https://cabinet.sumdu.edu.ua/index/service/";
    protected $cabinet_service_token = "7B4DIDiV";

    function checkCabinet(Request $request) {
        if(!isset($request->key)) {
            return redirect('/');
        }
        $personCabinet = json_decode(file_get_contents($this->cabinet_api . 'getPersonInfo?key=' . $request->key . '&token=' . $this->cabinet_service_token), true);
        // if($personCabinet['result']['categ1'] == 10) {
        //     return redirect('/');
        // }
        if ($personCabinet['status'] == 'OK') {
            $request->session()->put('key', $request->key);
            $userModel = Authors::where("guid", $personCabinet['result']['guid']);
            if($userModel->exists()) {
                $divisions = $this->getDivisions();
                $person = $userModel->first();
                $person->name = $personCabinet['result']['surname'] . " " . $personCabinet['result']['name'] . " " . $personCabinet['result']['patronymic'];
                $person->job = "СумДУ";
                $person->country = "Україна";
                $person->academic_code = $personCabinet['result']['info1'] ? $personCabinet['result']['info1'][0]['NAME_GROUP'] : "";

                if($personCabinet['result']['categ1'] == 10) {
                    $person->categ_1 = 1;
                } elseif($personCabinet['result']['categ1'] == 12) {
                    $person->categ_1 = 2;
                } else {
                    $person->categ_1 = 0;
                }
                if($personCabinet['result']['categ2'] == 10) {
                    $person->categ_2 = 1;
                } elseif($personCabinet['result']['categ2'] == 12) {
                    $person->categ_2 = 2;
                } else {
                    $person->categ_2 = 0;
                }

                $person->department_code = null;
                $person->faculty_code = null;

                foreach($divisions->original['department'] as $k2 => $v2) {
                    if ($personCabinet['result']['info1'][0]['KOD_DIV'] == $v2['ID_DIV']) {
                        $person->department_code = $v2['ID_DIV'];
                    }
                }

                if($person->department_code) {
                    foreach($divisions->original['department']  as $k => $v) {
                        if ($person->department_code == $v['ID_DIV']) {
                            foreach($divisions->original['institute'] as $k2 => $v2) {
                                if ($v['ID_PAR'] == $v2['ID_DIV']) {
                                    $person->faculty_code = $v2['ID_DIV'];
                                }
                            }
                        }
                    }
                }

                foreach($divisions->original['institute'] as $k2 => $v2) {
                    if ($personCabinet['result']['info1'][0]['KOD_DIV'] == $v2['ID_DIV']) {
                        $person->faculty_code = $v2['ID_DIV'];
                    }
                }

                $userModel->update($person->toArray());

                $request->session()->put('person', $person);
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

    function checkUser(Request $request) {
        $personCabinet = json_decode(file_get_contents($this->cabinet_api . 'getPerson?key=' . $request->session()->get('key') . '&token=' . $this->cabinet_service_token), true);
        if ($personCabinet['status'] == 'OK') {
            $userModel = Authors::where("name", $personCabinet['result']['surname'] . " " . $personCabinet['result']['name'] . " " . $personCabinet['result']['patronymic']);
            if ($userModel->exists()) {
                $divisions = $this->getDivisions();
                $user = $userModel->first();
                foreach($divisions->original['institute'] as $k => $v) {
                    if ($user['department_code'] == $v['ID_DIV']) {
                        $user['department'] = $v['NAME_DIV'];
                    }
                }
                foreach($divisions->original['department']  as $k => $v) {
                    if ($user['department_code'] == $v['ID_DIV']) {
                        $user['department'] = $v['NAME_DIV'];
                        foreach($divisions->original['institute'] as $k2 => $v2) {
                            if ($v['ID_PAR'] == $v2['ID_DIV']) {
                                $user['faculty'] = $v2['NAME_DIV'];
                            }
                        }
                    }
                }
                return response()->json([
                    "status" => "register",
                    "user" => $user
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

    function logout(Request $request) {
        $request->session()->flush();
        return response('ok', 200);
    }

    function register(Request $request) {
        $personCabinet = json_decode(file_get_contents($this->cabinet_api . 'getPersonInfo?key=' . $request->session()->get('key') . '&token=' . $this->cabinet_service_token), true);

        if(!Authors::where("guid", $personCabinet['result']['guid'])->exists()) {
            $divisions = $this->getDivisions();
            $data = $request->all();
            $data['name'] = $personCabinet['result']['surname'] . " " . $personCabinet['result']['name'] . " " . $personCabinet['result']['patronymic'];
            $data['job'] = "СумДУ";
            $data['country'] = "Україна";
            $data['academic_code'] = $personCabinet['result']['info1'] ? $personCabinet['result']['info1'][0]['NAME_GROUP'] : "";

            if($personCabinet['result']['categ1'] == 10) {
                $data['categ_1'] = 1;
            } elseif($personCabinet['result']['categ1'] == 12) {
                $data['categ_1'] = 2;
            } else {
                $data['categ_1'] = 0;
            }
            if($personCabinet['result']['categ2'] == 10) {
                $data['categ_2'] = 1;
            } elseif($personCabinet['result']['categ2'] == 12) {
                $data['categ_2'] = 2;
            } else {
                $data['categ_2'] = 0;
            }

            foreach($divisions->original['department'] as $k2 => $v2) {
                if ($personCabinet['result']['info1'][0]['KOD_DIV'] == $v2['ID_DIV']) {
                    $data['department_code'] = $v2['ID_DIV'];
                }
            }

            if($data['department_code']) {
                foreach($divisions->original['department']  as $k => $v) {
                    if ($data['department_code'] == $v['ID_DIV']) {
                        foreach($divisions->original['institute'] as $k2 => $v2) {
                            if ($v['ID_PAR'] == $v2['ID_DIV']) {
                                $data['faculty_code'] = $v2['ID_DIV'];
                            }
                        }
                    }
                }
            }

            foreach($divisions->original['institute'] as $k2 => $v2) {
                if ($personCabinet['result']['info1'][0]['KOD_DIV'] == $v2['ID_DIV']) {
                    $data['faculty_code'] = $v2['ID_DIV'];
                }
            }

            $userModel = new Authors();
            $newUser = $userModel->create($data);

            foreach($divisions->original['institute'] as $k => $v) {
                if ($newUser['department_code'] == $v['ID_DIV']) {
                    $newUser['department'] = $v['NAME_DIV'];
                }
            }
            foreach($divisions->original['department']  as $k => $v) {
                if ($newUser['department_code'] == $v['ID_DIV']) {
                    $newUser['department'] = $v['NAME_DIV'];
                    foreach($divisions->original['institute'] as $k2 => $v2) {
                        if ($v['ID_PAR'] == $v2['ID_DIV']) {
                            $newUser['faculty'] = $v2['NAME_DIV'];
                        }
                    }
                }
            }

            $request->session()->put('person', $newUser);
            return response()->json('ok', 200);
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
