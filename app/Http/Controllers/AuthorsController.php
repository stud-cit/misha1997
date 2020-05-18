<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Authors;
use App\Models\Roles;

class AuthorsController extends Controller
{
    function getAll() {
        $data = Authors::get();
        return response()->json($data);
    }
    function getId($id) {
        $data = Authors::with('publications', 'role', 'alias', 'notifications')->find($id);
        return response()->json($data);
    }
    function post(Request $request) {
        $model = new Authors();
        $data = $request->all();
        $response = $model->create($data);
        return response()->json($response);
    }
    function getRoles() {
        $data = Roles::get();
        return response()->json($data);
    }
}
