<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Notifications;

class ServiceController extends Controller
{
    function access() {
        $data = Service::where('key', 'access')->first();
        return response()->json($data);
    }
    function changeAccess(Request $request) {
        Service::where('key', 'access')->update([
            'value' => $request->mode
        ]);
        // if($request->mode == 'close') {
        //     Notifications::create([
        //         "text" => "Сервіс переведено в режим обмеженого доступу."
        //     ]);
        // }
        // if($request->mode == 'open') {
        //     Notifications::create([
        //         "text" => "Сервіс переведено в режим повного доступу."
        //     ]);
        // }
        return response('ok', 200);
    }
}
