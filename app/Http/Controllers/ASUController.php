<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ASUController extends Controller
{
    protected $asu_key = "eRi1FIAppqFDryG2PFaYw75S1z4q2ZoG";
    protected $asu_sumdu_api = "http://asu.sumdu.edu.ua/api/getDivisions?key=";

    // KOD_TYPE:
    // 7 - iнститут
    // 8 - коледж
    // 9 - факультет
    // 2 - кафедра

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
            if($value['KOD_TYPE'] == 2) {
                array_push($result['department'], $value);
            }
        }
        return response()->json($result);
    }

    function getSortDivisions() {
        $result = [];
        $data = json_decode(file_get_contents($this->asu_sumdu_api . $this->asu_key), true);
        foreach ($data['result'] as $key => $value) {
            if(($value['KOD_TYPE'] == 7 || $value['KOD_TYPE'] == 9 || $value['CODE_DIV'] == "35.00") && $value['CODE_DIV'] != "53.05" && $value['CODE_DIV'] != "61.00") {
                $value['departments'] = [];
                foreach ($data['result'] as $k => $v) {
                    if($v['KOD_TYPE'] == 2 && $value['ID_DIV'] == $v['ID_PAR'] && $v['CODE_DIV'] != "61.00") {
                        array_push($value['departments'], $v);
                    }
                    if($value['CODE_DIV'] == "60.00") {
                        if($v['ID_PAR'] == "382" || $v['ID_PAR'] == "1428" || $v['ID_DIV'] == "1582") {
                            array_push($value['departments'], $v);
                        }
                    }
                }
                array_push($result, $value);
            }
        }
        return response()->json($result);
    }
}
