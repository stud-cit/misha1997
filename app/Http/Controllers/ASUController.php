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
            "institute" => [], // iнститут, коледж, факультет
            "department" => [] // кафедра
        ];
        $data = json_decode(file_get_contents($this->asu_sumdu_api . $this->asu_key), true);
        foreach ($data['result'] as $key => $value) {
            if($value['KOD_TYPE'] == 7 || $value['KOD_TYPE'] == 8 || $value['KOD_TYPE'] == 9) {
                array_push($result['institute'], $value);
            }
            if($value['KOD_TYPE'] == 2) {
                array_push($result['department'], $value);
            }
        }
        return response()->json($result);
    }
}
