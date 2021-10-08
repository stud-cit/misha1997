<?php

namespace App\Http\Controllers;

use App\Models\Authors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\AuthorTrait;
use App\Http\Traits\NotificationTrait;
use App\Models\Audit;
use App\Models\Service;

class AuthController extends ASUController {
    use AuthorTrait;
    use NotificationTrait;
    protected $cabinet_api = "https://cabinet.sumdu.edu.ua/api/";
    protected $cabinet_service = "https://cabinet.sumdu.edu.ua/index/service/";
    protected $cabinet_service_token;

    function __construct() {
      $this->cabinet_service_token = config('app.token');
    }

    function logout(Request $request) {
        $request->session()->forget('key');
        $request->session()->forget('person');
        return response('ok', 200);
    }

    function register(Request $request) {
        $personCabinet = json_decode(file_get_contents($this->cabinet_api . 'getPersonInfo?key=' . $request->session()->get('key') . '&token=' . $this->cabinet_service_token), true);
  
        if(!Authors::where("guid", $personCabinet['result']['guid'])->exists()) {
            $divisions = $this->getAllDivision();
            $userModel = new Authors();
            $person = $this->registerUser($personCabinet['result'], $userModel, $divisions);
            $userModel->create([
              'name' => $personCabinet['result']['surname'] . " " . $personCabinet['result']['name'] . " " . $personCabinet['result']['patronymic'],
              'country' => 'Україна',
              'guid' => $personCabinet['result']['guid'],
              'token' => $personCabinet['result']['token'],
              'date_bth' => $personCabinet['result']['birthday'],
              'job' => $person['job'],
              'job_type_id' => $person['job_type_id'],
              'faculty_code' => $person['faculty_code'],
              'department_code' => $person['department_code'],
              'name_div' => $person['name_div'],
              'academic_code' => $person['academic_code'],
              'categ_1' => $person['categ_1'],
              'categ_2' => $person['categ_2'],
              'kod_level' => $person['kod_level'],
              'scopus_autor_id' => $request->scopus_autor_id,
              'h_index' => $request->h_index,
              'without_self_citations_scopus' => $request->without_self_citations_scopus,
              'without_self_citations_wos' => $request->without_self_citations_wos,
              'scopus_researcher_id' => $request->scopus_researcher_id,
              'orcid' => $request->orcid,
              'scopus_id' => $request->scopus_id,
              'test_data' => json_encode($personCabinet['result'])
            ]);
            $image = file_get_contents('https://cabinet.sumdu.edu.ua/api/getPersonPhoto?key=' . $request->session()->get('key') . '&token=' . $this->cabinet_service_token);
            Storage::disk('local')->put('public/' . $personCabinet['result']['guid'] . '.png', $image, 'public');  
            return response()->json('ok', 200);
        } else {
            return response()->json([
                "message" => "Користувач вже зареєстрований в системі"
            ]);
        }
    }

    function index(Request $request) {
      $this->mode($request);

      $key = "";

      if($request->key) {
        $key = $request->key;
        $request->session()->put('key', $request->key);
      }

      if($key == '' && $request->session()->get('key')) {
        $key = $request->session()->get('key');
      }

      // $personCabinet = json_decode(file_get_contents($this->cabinet_api . 'getPersonInfo?key=' . $key . '&token=' . $this->cabinet_service_token), true);

      $personCabinet = json_decode(Storage::get('getPerson.json'), true);

      if ($personCabinet['status'] == 'OK') {
        $userModel = Authors::where("guid", $personCabinet['result']['guid']);
        if($userModel->exists()) {
          $divisions = $this->getAllDivision();
          $data = $userModel->first();
          $person = $this->registerUser($personCabinet['result'], $data, $divisions);
          if(!$request->session()->get('person')) {
            $newData = clone $userModel->first();
            $person['test_data'] = json_encode($personCabinet['result']);

            if($key) {
              $image = file_get_contents('https://cabinet.sumdu.edu.ua/api/getPersonPhoto?key=' . $key . '&token=' . $this->cabinet_service_token);
              Storage::disk('local')->put('public/' . $personCabinet['result']['guid'] . '.png', $image, 'public');  
            }

            $userModel->update([
              'name' => $person['name'],
              'date_bth' => $person['date_bth'],
              'job' => $person['job'],
              'job_type_id' => $person['job_type_id'],
              'faculty_code' => $person['faculty_code'],
              'department_code' => $person['department_code'],
              'name_div' => $person['name_div'],
              'academic_code' => $person['academic_code'],
              'categ_1' => $person['categ_1'],
              'categ_2' => $person['categ_2'],
              'kod_level' => $person['kod_level'],
              'test_data' => $person['test_data']
            ]);
            $notificationText = "";
            if($person['name_div'] != '') {
              $notificationText .= $this->notification($person, $newData, "name_div", "факультет / кафедру");
            }
            if($notificationText != "") {
              $notificationText = "Оновлено інформацію про автора <a href=\"/user/". $request->session()->get('person')['id'] ."\">" . $request->session()->get('person')['name'] . "</a>:<br>" . $notificationText;
              Audit::create([
                "text" => $notificationText
              ]);
            }
            $request->session()->put('person', $person);
          }
          $access = Service::select('value')->where('key', 'access')->first();
          return view('app', [
            "status" => "register",
            "user" => $person,
            "access" => $access->value
          ]);
        } else {
          $request->session()->forget('person');
          return view('app', [
            "status" => "login",
            "user" => "{
              name: \"". $personCabinet['result']['surname'] . ' ' . $personCabinet['result']['name'] . ' ' . $personCabinet['result']['patronymic'] ."\"
            }",
            "access" => ""
          ]);
        }
      } else {
        $request->session()->forget('key');
        $request->session()->forget('person');
        return view('app', [
          "status" => "unauthorized",
          "user" => "{}",
          "access" => ""
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
