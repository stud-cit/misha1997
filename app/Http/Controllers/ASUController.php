<?php

namespace App\Http\Controllers;

class ASUController extends Controller
{
    protected $asu_key = "eRi1FIAppqFDryG2PFaYw75S1z4q2ZoG";
    protected $asu_sumdu_api = "http://asu.sumdu.edu.ua/api/getDivisions?key=";

    // KOD_TYPE:
    // 7 - iнститут
    // 8 - коледж
    // 9 - факультет
    // 2 - кафедра

  function getAspirantDepartment($guid) {
    $aspirantData = json_decode(file_get_contents('https://asu.sumdu.edu.ua/api/getAspirantInfo?key=' . $this->asu_key . '&guid=' . $guid), true);
    if($aspirantData['status'] == 'OK') {
        return count($aspirantData['result']) > 0 ? $aspirantData['result'][0]['KOD_DIV'] : null;
    } else {
        return null;
    }
  }

    function getAllDivision() {
      $divisions = json_decode(file_get_contents($this->asu_sumdu_api . $this->asu_key), true)['result'];
      return $divisions;
    }

    function getSortDivisions() {
        $result = [];
        $data = json_decode(file_get_contents($this->asu_sumdu_api . $this->asu_key), true);
        foreach ($data['result'] as $key => $value) {
            if(($value['KOD_TYPE'] == 7 || $value['KOD_TYPE'] == 9 || $value['CODE_DIV'] == "35.00") && $value['CODE_DIV'] != "53.05" && $value['CODE_DIV'] != "61.00" && $value['CODE_DIV'] != "15.01.09" && $value['CODE_DIV'] != "53.04" && $value['CODE_DIV'] != "16.02") {
                $value['departments'] = [];
                foreach ($data['result'] as $k => $v) {
                    if($v['KOD_TYPE'] == 2 && $value['ID_DIV'] == $v['ID_PAR'] && $v['CODE_DIV'] != "61.00") {
                        array_push($value['departments'], $v);
                    }
                    if($value['CODE_DIV'] == "60.00") {
                        if($v['ID_PAR'] == "382" || $v['ID_PAR'] == "1428" || $v['ID_DIV'] == "1582" || $v['ID_DIV'] == "382") {
                            array_push($value['departments'], $v);
                        }
                    }
                }
                array_push($result, $value);
            }
        }
        return response()->json($result);
    }

    function getUserDivision($department_code, $divisions) {
      $sectionId = array_search($department_code, array_column($divisions, 'ID_DIV'));
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
      $result = [
          "section" => $sectionId ? $divisions[$sectionId] : null,
          "department" => $departmentId ? $divisions[$departmentId] : null,
          "institute" => $facultyId ? $divisions[$facultyId] : null
      ];

      foreach ($result as $key => $value) {
          if($value && $value['NAME_TYPE'] == "кафедра") {
              $result['department'] = $value;
          }
      }

      return $result;
    }

    function getDivisions() {
      $result = [
          "institute" => [], // iнститут, факультет
          "department" => [] // кафедра
      ];
      $data = json_decode(file_get_contents($this->asu_sumdu_api . $this->asu_key), true);
      foreach ($data['result'] as $key => $value) {
          if($value['KOD_TYPE'] == 7 || $value['KOD_TYPE'] == 9) {
              array_push($result['institute'], $value);
          }
          // if($value['KOD_TYPE'] == 2) {
              array_push($result['department'], $value);
          // }
      }
      return response()->json($result);
    }
}
