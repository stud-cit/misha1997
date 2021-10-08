<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Authors;
use App\Models\Notifications;
use App\Models\Publications;
use App\Models\Audit;
use App\Models\Roles;
use App\Models\AuthorsPublications;

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

  function get(Request $request) {
    $data = [];

    $model = Authors::with('role', 'jobType');

    $model->orderBy($request->sortBy, $request->sortOrder == 1 ? 'asc' : 'desc');

    $divisions = $this->getDivisions();

    if($request->session()->get('person')['roles_id'] == 2) {
      $departments_id = [$request->session()->get('person')['department_code']];
      foreach($divisions->original['department'] as $k2 => $v2) {
        if ($v2['ID_PAR'] == $request->session()->get('person')['department_code']) {
          array_push($departments_id, $v2['ID_DIV']);
        }
      }
      $model->whereIn('department_code', $departments_id);
    } else {
      if($request->session()->get('person')['roles_id'] == 3) {
        $departments_id = [$request->session()->get('person')['faculty_code']];
        foreach($divisions->original['department'] as $k2 => $v2) {
          if ($v2['ID_PAR'] == $request->session()->get('person')['faculty_code']) {
            array_push($departments_id, $v2['ID_DIV']);
          }
        }
        $model->whereIn('faculty_code', $departments_id);
      }
    }

    // ПІБ
    if(isset($request->name)) {
      $model->where('name', 'like', "%".$request->name."%");
    }

    // Роль
    if(isset($request->roles)) {
      $model->whereIn('roles_id', $request->roles);
    }

    // Факультет
    if(isset($request->department_code)) {
      $model->whereIn('department_code', $request->department_code);
    }

    // Кафедра
    if(isset($request->faculty_code)) {
      $model->whereIn('faculty_code', $request->faculty_code);
    }

    // Індекс Гірша
    if(isset($request->indexH)) {
      $model->where(function($queryModel) use($request) {
        foreach ($request->indexH as $value) {
          switch ($value) {
            case 1:
              $queryModel->where(function($query) {
                $query->where('h_index', '>', 0)->orWhere('scopus_autor_id', '>', 0);
              });
              break;
            case 2:
              $queryModel->where(function($query) {
                $query->whereNull('h_index')->whereNull('scopus_autor_id');
              });
              break;
            case 3:
              $queryModel->where(function($query) {
                $query->where('h_index', '>=', 10)->orWhere('scopus_autor_id', '>=', 10);
              });
              break;
          }
        }
      });
    }

    // Наявність скопус ID
    if(isset($request->scopusId)) {
      $model->where(function($queryModel) use($request) {
        foreach ($request->scopusId as $value) {
          switch ($value) {
            case 1:
              $queryModel->orWhere(function($query) {
                $query->whereNotNull('scopus_id');
              });
              break;
            case 0:
              $queryModel->orWhere(function($query) {
                $query->whereNull('scopus_id');
              });
              break;
          }
        }
      });
    }

    // Посада
    if(isset($request->categUsers)) {
      $model->where(function($queryModel) use($request) {
        foreach($request->categUsers as $value) {
          switch ($value) {
            case 1: // Аспіранти
              $queryModel->orWhere(function($query) {
                $query->where('categ_1', 2);
              });
              break;
            case 2: // Викладачі
              $queryModel->orWhere(function($query) {
                $query->where('categ_2', 2);
              });
              break;
            case 3: // Докторанти
              $queryModel->orWhere(function($query) {
                $query->where('kod_level', 9);
              });
              break;
            case 4: // Зовнішні співавтори
              $queryModel->orWhere(function($query) {
                $query->where('job_type_id', '!=', 5)->where('job_type_id', '!=', 6);
              });
              break;
            case 5: // Іноземці
              $queryModel->orWhere(function($query) {
                $query->where('country', '!=', 'Україна');
              });
              break;
            case 6: // Менеджери
              $queryModel->orWhere(function($query) {
                $query->where('categ_2', 3);
              });
              break;
            case 7: // Співробітники
              $queryModel->orWhere(function($query) {
                $query->where('categ_2', 1);
              });
              break;
            case 8: // Студенти
              $queryModel->orWhere(function($query) {
                $query->where('categ_1', 1)->orWhere('categ_1', 3);
              });
              break;
            case 9: // СумДУ
              $queryModel->orWhere(function($query) {
                $query->where('job_type_id', 5);
              });
              break;
            case 10: // СумДУ (не працює)
              $queryModel->orWhere(function($query) {
                $query->where('job_type_id', 6);
              });
              break;
          }
        }
      });
    }

    if($request->five_publications == 'true') {
      $model->where('five_publications', '1')->where(function($queryModel) {
        $queryModel->where('categ_2', 2)->orWhere('categ_2', 3)->orWhere('categ_2', 1);
      });
    }

    $model2 = clone $model;

    $scopusAutorId = $model2->sum('scopus_autor_id');
    $hIndex = $model2->sum('h_index');
    $fivePublications = $model2->where('five_publications', 1)->count();

    $data = $model->paginate($request->size);

    $allDivisions = $this->getAllDivision();

    foreach ($data as $value) {
      $kod_div = $value['department_code'] ? $value['department_code'] : $value['faculty_code'];
      $sectionId = array_search($kod_div, array_column($allDivisions, 'ID_DIV'));
      $departmentId = array_search($allDivisions[$sectionId]['ID_PAR'], array_column($allDivisions, 'ID_DIV'));
      $facultyId = array_search($allDivisions[$departmentId]['ID_PAR'], array_column($allDivisions, 'ID_DIV'));
      if(!$departmentId) {
          $departmentId = $sectionId;
          $sectionId = null;
      }
      if(!$facultyId) {
          $facultyId = $departmentId;
          $departmentId = $sectionId;
          $sectionId = null;
      }
      $value['department'] = $departmentId ? $allDivisions[$departmentId]['NAME_DIV'] : null;
      $value['faculty'] = $facultyId ? $allDivisions[$facultyId]['NAME_DIV'] : null;
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

  function getId(Request $request, $id = null) {
    if(!isset($id)) {
      $id = $request->session()->get('person')->id;
    }
    $data = Authors::with('role', 'user', 'jobType')->find($id);
    
    $kod_div = $data->department_code ? $data->department_code : $data->faculty_code;

    $division = $this->getUserDivision($kod_div, $this->getAllDivision());
    $data->department = $division['department'] ? $division['department']['NAME_DIV'] : null;
    $data->faculty = $division['institute'] ? $division['institute']['NAME_DIV'] : null;

    if(Storage::exists('public/' . $data->guid . '.png')) {
      $data->photo = asset(Storage::url('public/' . $data->guid . '.png'));
    } else {
      $data->photo = '/img/user.png';
    }

    return response()->json($data);
  }

  function getAll() {
    $data = Authors::select(
      'id',
      'academic_code', 
      'categ_1', 
      'categ_2',
      'department_code',
      'faculty_code',
      'job',
      'job_type_id',
      'name',
      'roles_id',
    )->with('role')->orderBy('name', 'ASC')->get();

    $divisions = $this->getAllDivision();

    foreach ($data as $value) {
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

  function getOtherAuthorNames() {
    $data = Authors::select('name', 'job')->where("guid", null)->get();
    return response()->json($data);
  }

    //notifications
    function getNotifications(Request $request) {
      $model = Notifications::where('autors_id', $request->session()->get('person')->id)->orderBy('created_at', 'DESC');
      
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
        $data['job_type_id'] = null;
        if($data['categ_1'] != 1 && $data['categ_1'] != 2) {
          $data['job_type_id'] = 5;
        }

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

  // updateProfile (Оновлення информації профілю)
  function updateProfile(Request $request) {
    $data = $request->all();
    $user = Authors::find($request->session()->get('person')['id']);
    $user->update($data);
    return response()->json($user);
  }

  // deleteAuthor
  function deleteAuthor(Request $request) {
    $model = Authors::whereIn('id', $request->users)->get();
      foreach ($model as $key => $user) {
        Audit::create([
          "text" => "Користувач <a href=\"/user/". $request->session()->get('person')['id'] ."\">" . $request->session()->get('person')['name'] . "</a> видалив автора " . $user['name']
        ]);
      }
      Authors::whereIn('id', $request->users)->delete();
      AuthorsPublications::whereIn('autors_id', $request->users)->delete();
      return response('ok', 200);
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

  // getRoles
  function getRoles() {
    $data = Roles::get();
    return response()->json($data);
  }

  function updateCabinetInfo(Request $request, $user_id) {
    $model = Authors::find($user_id);
    $model->makeVisible(['test_data']);
    if($model['test_data']) {
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
    }
    return response()->json([
      'status' => 'ok'
    ]);
  }
}
