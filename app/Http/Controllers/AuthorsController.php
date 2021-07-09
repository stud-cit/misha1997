<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Authors;
use App\Models\Users;
use App\Models\Roles;
use App\Models\Notifications;
use App\Models\Publications;
use App\Models\JobType;
use App\Models\Audit;
use Session;
use Config;

use App\Http\Traits\NotificationTrait;
use App\Http\Traits\AuthorTrait;

class AuthorsController extends ASUController
{
    use NotificationTrait;
    use AuthorTrait;

    protected $cabinet_api = "https://cabinet.sumdu.edu.ua/api/";
    protected $asu_key = "eRi1FIAppqFDryG2PFaYw75S1z4q2ZoG";
    protected $cabinet_service_token;

    function __construct() {
        $this->cabinet_service_token = config('app.token');
    }

    // authors
    function get(Request $request) {
        $divisions = $this->getAllDivision();
        $data = [];
        $model = Authors::with('role', 'jobType')->orderBy('created_at', 'DESC');

        if($request->session()->get('person')['roles_id'] == 3) {
            $model->where('faculty_code', $request->session()->get('person')['faculty_code'])->orWhere('categ_1', 1)->orWhere('job', '!=', 'СумДУ')->where('job', '!=', 'СумДУ (Не працює)');
        }

        if($request->session()->get('person')['roles_id'] == 2) {
            $model->where('department_code', $request->session()->get('person')['department_code'])->orWhere('categ_1', 1)->orWhere('job', '!=', 'СумДУ')->where('job', '!=', 'СумДУ (Не працює)');
        }

        if($request->name != '') {
            $model->where('name', 'like', "%".$request->name."%");
        }

        if($request->role != '') {
            $model->where('roles_id', $request->role);
        }

        if($request->department_code != '') {
            $departments_id = [$request->department_code];
            foreach($divisions as $k2 => $v2) {
                if ($v2['ID_PAR'] == $request->department_code) {
                    array_push($departments_id, $v2['ID_DIV']);
                }
            }
            $model->whereIn('department_code', $departments_id);
        } else {
            if($request->faculty_code != '') {
                $departments_id = [$request->faculty_code];
                foreach($divisions as $k2 => $v2) {
                    if ($v2['ID_PAR'] == $request->faculty_code) {
                        array_push($departments_id, $v2['ID_DIV']);
                    }
                }
                $model->whereIn('faculty_code', $departments_id);
            }
        }

        if($request->h_index == '1') {
            $model->where(function($query) {
                $query->where('h_index', '!=', null)->orWhere('scopus_autor_id', '!=', null);
            });
        }

        if($request->h_index == '0') {
            $model->where('h_index', null)->where('scopus_autor_id', null);
        }

        if($request->h_index == '10') {
            $model->where(function($query) {
                $query->where('h_index', '>=', 10)->orWhere('scopus_autor_id', '>=', 10);
            });
        }

        if($request->scopus_id != '') {
          if($request->scopus_id == '1') {
            $model->whereNotNull('scopus_id');
          }
          if($request->scopus_id == '0') {
            $model->whereNull('scopus_id');
          }
        }

        if(isset($request->categ_users)) {
            $model->where(function($query) use($request) {
                foreach($request->categ_users as $key => $value) {
                  if($value == "Аспіранти") {
                    $query->orWhere('categ_1', 2);
                  }
                  if($value == "Викладачі") {
                    $query->orWhere('categ_2', 2);
                  }
                  if($value == "Докторанти") {
                    $query->orWhere('kod_level', 9);
                  }
                  if($value == "Зовнішні співавтори") {
                    $query->orWhere('job_type_id', '!=', 5)->where('job_type_id', '!=', 6);
                  }
                  if($value == "Іноземці") {
                    $query->where('country', '!=', 'Україна');
                  }
                  if($value == "Менеджери") {
                    $query->orWhere('categ_2', 3);
                  }
                  if($value == "Співробітники") {
                    $query->orWhere('categ_2', 1);
                  }
                  if($value == "Студенти") {
                    $query->orWhere('categ_1', 1)->orWhere('categ_1', 3);
                  }
                  if($value == "СумДУ") {
                      $query->orWhere('job_type_id', 5);
                  }
                  if($value == "СумДУ (не працює)") {
                      $query->orWhere('job_type_id', 6);
                  }
                  if($value == "5 або більше публікацій у періодичних виданнях Scopus та/або WoS") {
                      $query->orWhere('five_publications', '1');
                  }
                }
            });
        }

        $model2 = clone $model;

        $scopusAutorId = $model2->sum('scopus_autor_id');
        $hIndex = $model2->sum('h_index');
        $fivePublications = $model2->where('five_publications', 1)->count();

        $data = $model->paginate($request->size);

        foreach ($data as $key => $value) {
            $value['position'] = $this->getPosition($value);

            $kod_div = $value['department_code'] ? $value['department_code'] : $value['faculty_code'];

            $sectionId = array_search($kod_div, array_column($divisions, 'ID_DIV'));
            $departmentId = array_search($divisions[$sectionId]['ID_PAR'], array_column($divisions, 'ID_DIV'));
            $facultyId = array_search($divisions[$departmentId]['ID_PAR'], array_column($divisions, 'ID_DIV'));
            if(!$departmentId) {
                $departmentId = $sectionId;
                $sectionId = null;
            }
            if(!$facultyId) {
                $facultyId = $departmentId;
                $departmentId = $sectionId;
                $sectionId = null;
            }

            $value['department'] = $departmentId ? $divisions[$departmentId]['NAME_DIV'] : null;
            $value['faculty'] = $facultyId ? $divisions[$facultyId]['NAME_DIV'] : null;
        }

        return response()->json([
            "scopusAutorId" => $scopusAutorId,
            "hIndex" => $hIndex,
            "fivePublications" => $fivePublications,
            "currentPage" => $data->currentPage(),
            "firstItem" => $data->firstItem(),
            "count" => $data->total(),
            "users" => $data
        ]);
    }

    // authors All (admin)
    function getAll(Request $request) {
        $data = Authors::with('role')->orderBy('name', 'ASC')->get();

        $divisions = $this->getAllDivision();

        foreach ($data as $key => $value) {
            $value['position'] = $this->getPosition($value);

            $kod_div = $value['department_code'] ? $value['department_code'] : $value['faculty_code'];

            $sectionId = array_search($kod_div, array_column($divisions, 'ID_DIV'));
            $departmentId = array_search($divisions[$sectionId]['ID_PAR'], array_column($divisions, 'ID_DIV'));
            $facultyId = array_search($divisions[$departmentId]['ID_PAR'], array_column($divisions, 'ID_DIV'));
            if(!$departmentId) {
                $departmentId = $sectionId;
                $sectionId = null;
            }
            if(!$facultyId) {
                $facultyId = $departmentId;
                $departmentId = $sectionId;
                $sectionId = null;
            }

            $value['department'] = $departmentId ? $divisions[$departmentId]['NAME_DIV'] : null;
            $value['faculty'] = $facultyId ? $divisions[$facultyId]['NAME_DIV'] : null;
        }
        return response()->json($data);
    }

    // cabinet users (add publication page)
    function getPersons($categ, $id) {

        $mode = 1;
        $getPersons = file_get_contents('https://asu.sumdu.edu.ua/api/getContingents?key='.$this->asu_key.'&mode='.$mode.'&'.$categ.'='.$id);
        $getPersons = json_decode($getPersons, true);
        $result = [];
        foreach ($getPersons['result'] as $key => $value) {
            array_push($result, [
                "name" => $value['F_FIO'] . " " . $value['I_FIO'] . " " . $value['O_FIO'],
                "kod_div" => $value['KOD_DIV'],
                "name_div" => ($categ == 'categ1' && $id == 4) || $categ == 'categ2' ? $value['NAME_DIV'] : $value['NAME_GROUP'],
                "categ_1" => $value['CATEG_1'],
                "categ_2" => $value['CATEG_2'],
                "job" => ($categ == 'categ1' && $id == 2) ? null : "СумДУ",
                "country" => "Україна",
                "academic_code" => ($categ == 'categ1' && $id == 2) ? $value['NAME_GROUP'] : null,
                "guid" => $value['ID_FIO']
            ]);
        }
        return response()->json($result);
    }

    // profile (Сторінка профілю)
    function profile(Request $request) {
        $data = Authors::with('role', 'jobType')->find($request->session()->get('person')['id']);
        $data->position = $this->getPosition($data);

        $kod_div = $data->department_code ? $data->department_code : $data->faculty_code;

        $division = $this->getUserDivision($kod_div, $this->getAllDivision());
        $data->department = $division['department'] ? $division['department']['NAME_DIV'] : null;
        $data->faculty = $division['institute'] ? $division['institute']['NAME_DIV'] : null;

        return response()->json($data);
    }

    // updateProfile (Оновлення информації профілю)
    function updateProfile(Request $request) {
        $data = $request->all();
        $user = Authors::find($request->session()->get('person')['id']);
        $user->update($data);
        return response()->json($user);
    }

    // getId (Користувач по id)
    function getId($id) {
      if(!Authors::where('id', $id)->exists()) {
        return response('Data not fount', 400);
      } else {
        $data = Authors::with('role', 'user', 'jobType')->find($id);
        $data->position = $this->getPosition($data);

        $kod_div = $data->department_code ? $data->department_code : $data->faculty_code;

        $division = $this->getUserDivision($kod_div, $this->getAllDivision());
        $data->department = $division['department'] ? $division['department']['NAME_DIV'] : null;
        $data->faculty = $division['institute'] ? $division['institute']['NAME_DIV'] : null;

        return response()->json($data);
      }
    }

    function getOtherAuthorNames() {
        $data = Authors::select('name', 'job')->where("guid", null)->get();
        return response()->json($data);
    }

    // додання автора не з СумДУ
    function postAuthor(Request $request) {
        if(!Authors::where("name", "like", $request->name)->exists()) {
            $model = new Authors();
            $data = $request->all();
            $data['add_user_id'] = $request->session()->get('person')['id'];
            $response = $model->create($data);
            $author = Authors::with('jobType')->find($response['id']); 

            if($author['job_type_id'] == 6) {
                $kod_div = $author['department_code'] ? $author['department_code'] : $data['faculty_code'];

                $division = $this->getUserDivision($kod_div, $this->getAllDivision());
    
                $author['department'] = $division['department'] ? $division['department']['NAME_DIV'] : null;
                $author['faculty'] = $division['institute'] ? $division['institute']['NAME_DIV'] : null;
            }

            return response()->json([
                "status" => "ok",
                "user" => $author
            ]);
        } else {
            return response()->json(["status" => "error"]);
        }
    }

    // додання автора з СумДУ
    function postAuthorSSU(Request $request) {
        if(!Authors::where("name", "like", $request->name)->exists()) {
            $model = new Authors();
            $data = $request->all();
            $kod_div = $request->kod_div;
            $division = $this->getUserDivision($kod_div, $this->getAllDivision());
            if($request->categ_1 != 1) {
                if($request->categ_1 == 2) {
                    $kod_div = $this->getAspirantDepartment($request->guid);
                }
                $data['department_code'] = $division['department'] ? $division['department']['ID_DIV'] : null;
                $data['faculty_code'] = $division['institute'] ? $division['institute']['ID_DIV'] : null;
            }

            $data['add_user_id'] = $request->session()->get('person')['id'];

            $response = $model->create($data);

            $response['department'] = $division['department'] ? $division['department']['NAME_DIV'] : null;
            $response['faculty'] = $division['institute'] ? $division['institute']['NAME_DIV'] : null;

            return response()->json([
                "status" => "ok",
                "user" => $response
            ]);
        } else {
            return response()->json(["status" => "error"]);
        }
    }

    // updateAuthor
    function updateAuthor(Request $request, $id) {
        $notificationText = "";
        $data = $request->all();
        $model = Authors::with('role')->find($id);

        $roles = [];

        foreach ($this->getRoles()->original as $key => $value) {
            $roles[$value['id']] = $value['name'];
        }

        $notificationText .= $this->notification($data, $model, "roles_id", "роль", $roles);
        $notificationText .= $this->notification($data, $model, "five_publications", "5 або більше публікацій у періодичних виданнях в Scopus та/або WoS");
        $notificationText .= $this->notification($data, $model, "scopus_autor_id", "Індекс Гірша БД Scopus");
        $notificationText .= $this->notification($data, $model, "h_index", "Індекс Гірша БД WoS");
        $notificationText .= $this->notification($data, $model, "without_self_citations_wos", "Індекс Гірша без самоцитувань БД WoS");
        $notificationText .= $this->notification($data, $model, "without_self_citations_scopus", "Індекс Гірша без самоцитувань БД Scopus");
        $notificationText .= $this->notification($data, $model, "orcid", "ORCID");
        $notificationText .= $this->notification($data, $model, "country", "країну");
        $notificationText .= $this->notification($data, $model, "scopus_id", "SCOPUS ID");

        if($notificationText != "") {
            Notifications::create([
                "autors_id" => $id,
                "text" => "Користувач <a href=\"/user/". $request->session()->get('person')['id'] ."\">" . $request->session()->get('person')['name'] . "</a> вніс наступні зміни в Ваш профіль:<br>" . $notificationText
            ]);

            Audit::create([
                "text" => "Користувач <a href=\"/user/". $request->session()->get('person')['id'] ."\">" . $request->session()->get('person')['name'] . "</a> вніс наступні зміни в профіль автора <a href=\"/user/". $model['id'] ."\">" . $model['name'] . "</a>:<br>" . $notificationText
            ]);
        }
        $model->update($data);
        return response('ok', 200);
    }

    // deleteAuthor
    function deleteAuthor(Request $request) {
        foreach ($request->users as $key => $user) {
            Authors::find($user['id'])->delete();
            Audit::create([
                "text" => "Користувач <a href=\"/user/". $request->session()->get('person')['id'] ."\">" . $request->session()->get('person')['name'] . "</a> видалив автора <a href=\"/user/". $user['id'] ."\">" . $user['name'] . "</a>."
            ]);
        }
        return response('ok', 200);
    }

    // getRoles
    function getRoles() {
        $data = Roles::get();
        return response()->json($data);
    }

    //notifications
    function getNotifications(Request $request, $autors_id) {
        $model = Notifications::where('autors_id', $autors_id)->orWhere('autors_id', null)->orderBy('created_at', 'DESC');
        
        if(isset($request->search)) {
            $model = $model->where('text', 'like', '%' . $request->search . '%');
        }

        $data = $model->paginate($request->size);
        return response()->json([
            "currentPage" => $data->currentPage(),
            "firstItem" => $data->firstItem(),
            "count" => $data->total(),
            "data" => $data
        ]);
    }
    function postNotifications(Request $request, $publication_id) {
        $model = Publications::with('authors')->find($publication_id);
        $notificationText = "Користувач <a href=\"/user/". $request->session()->get('person')['id'] ."\">" . $request->session()->get('person')['name'] . "</a> залишив коментар до публікації <a href=\"/publications/". $publication_id ."\">" . $model['title'] . "</a>: ";
        $notificationText .= $request->comment;
        foreach ($model['authors'] as $key => $value) {
            Notifications::create([
                "autors_id" => $value['autors_id'],
                "text" => $notificationText
            ]);
        }
        return response('ok', 200);
    }
    function editNotifications(Request $request, $id, $autors_id) {
        Notifications::find($id)->update([
            "status" => $request->status
        ]);
        $count = Notifications::where('autors_id', $autors_id)->where('status', 0)->count();
        return response()->json([
            'count' => $count
        ]);
    }

    function deleteNotifications($id, $autors_id) {
        Notifications::find($id)->delete();
        $count = Notifications::where('autors_id', $autors_id)->where('status', 0)->count();
        return response()->json([
            'count' => $count
        ]);
    }

    function deleteAllNotifications($autors_id) {
        Notifications::where('autors_id', $autors_id)->delete();
        return response('ok', 200);
    }

    function jobType() {
        $data = JobType::where('id', '!=', 5)->get();
        return response()->json($data);
    }

    function updateCabinetInfo(Request $request, $user_id) {
        $key = $request->session()->get('key');
        $model = Authors::find($user_id);
        $model->makeVisible(['test_data']);
        $getPersons = json_decode(file_get_contents($this->cabinet_api . 'getPersons?key=' . $key . '&token=' . $this->cabinet_service_token . '&search=' . urlencode($model['name'])), true);
        if(isset($model['test_data'])) {
            $newData = [
                "job" => null,
                "job_type_id" => null,
                "faculty_code" => null,
                "department_code" => null,
                "academic_code" => null,
                "kod_level" => null,
                "categ_1" => null,
                "categ_2" => null
            ];
            $person = $this->updateAuthUser($model, $newData, $this->getAllDivision());
            $model->update($person);
            return response()->json([
                'status' => 'ok'
            ]);
        } else {
            $getPersons = json_decode(file_get_contents($this->cabinet_api . 'getPersons?key=' . $key . '&token=' . $this->cabinet_service_token . '&search=' . urlencode($model['name'])), true);
            if($getPersons['status'] == 'OK' && count($getPersons['result']) > 0) {
                $mode = 1;
                $getPersons = array_shift($getPersons['result']);
                $getContingents = json_decode(file_get_contents('https://asu.sumdu.edu.ua/api/getContingents?key=' . $this->asu_key . '&mode=' . $mode . '&categ1=' . $getPersons['categ1'] . '&categ2=' . $getPersons['categ2']), true);
                if($getContingents['status'] == 'OK') {
                    $person = $this->UpdateNotAuthUser($getContingents, $this->getAllDivision());
                    $model->update([
                        "name" => $person['F_FIO'] . ' ' . $person['I_FIO'] . ' ' . $person['O_FIO'],
                        "faculty_code" => $person['CATEG_1'] != 1 ? $person['FACULTY_CODE'] : null,
                        "department_code" => $person['CATEG_1'] != 1 ? $person['DEPARTMENT_CODE'] : null,
                        "academic_code" => $person['NAME_GROUP'],
                        "categ_1" => $person['CATEG_1'],
                        "categ_2" => $person['CATEG_2'],
                        "kod_level" => $person['KOD_LEVEL'],
                    ]);
                    return response()->json([
                        'status' => 'ok'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'error'
                ]);
            }
        }
    }
}
