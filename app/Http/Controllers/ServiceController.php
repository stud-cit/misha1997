<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

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
        return response('ok', 200);
    }
}
