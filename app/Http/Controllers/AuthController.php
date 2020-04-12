<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function getLoginKey(Request $request){
        $cabinet_api = "https://cabinet.sumdu.edu.ua/api/";
        $cabinet_service = "https://cabinet.sumdu.edu.ua/index/service/";
        $cabinet_service_token = "7tvvT6CG";

        $key = !empty($request->key) ? $request->key : "";
        $person = json_decode(file_get_contents($cabinet_api . 'getPerson?key=' . $key . '&token=' . $cabinet_service_token), true);

//        if ($person['status'] == 'OK') {
//            $personInfo1 = json_decode(file_get_contents($cabinet_api . 'getPersonInfo1?key=' . $key . '&token=' . $cabinet_service_token), true);
//            dd( $personInfo1);
//        }
        $this->loginUser();
    }


}
