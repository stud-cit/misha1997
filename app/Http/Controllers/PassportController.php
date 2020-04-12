<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class PassportController extends Controller
{
    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register($userData)
    {
//        $this->validate($request, [
//            'name' => 'required|min:3',
//            'email' => 'required|email|unique:users',
//            'password' => 'required|min:6',
//        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
//            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('token')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $cabinet_api = "https://cabinet.sumdu.edu.ua/api/";
        $cabinet_service = "https://cabinet.sumdu.edu.ua/index/service/";
        $cabinet_service_token = "7tvvT6CG";

        $key = !empty($request->key) ? $request->key : "";

        $person = json_decode(file_get_contents($cabinet_api . 'getPerson?key=' . $key . '&token=' . $cabinet_service_token), true);

        if ($person['status'] == 'OK') {
            $personInfo1 = json_decode(file_get_contents($cabinet_api . 'getPersonInfo1?key=' . $key . '&token=' . $cabinet_service_token), true);

        }
        else{
            return response()->json(['error' => 'UnAuthorised'], 401);
        }

        $credentials = [
            'guid' => $person->guid,
            'email' => $person->email,
            'edu_level' => $personInfo1->NAME_LEVEL
        ];

        if (auth()->attempt($credentials) && $credentials['edu_level'] !='student') {
            $token = auth()->user()->createToken('token')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
//            return response()->json(['error' => 'UnAuthorised'], 401);
            $this->register(['person' => $person, 'personInfo1' => $personInfo1]);
        }
    }

    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details()
    {
        return response()->json(['user' => auth()->user()], 200);
    }


}
