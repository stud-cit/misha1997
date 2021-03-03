<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Audit;

class AuditController extends Controller
{
    function get(Request $request) {
        $model = new Audit;
        if(isset($request->search)) {
            $model = $model->where('text', 'like', "%".$request->search."%");
        }
        $model = $model->orderBy('created_at', 'DESC');
        $data = $model->paginate($request->size);
        return response()->json([
            "currentPage" => $data->currentPage(),
            "firstItem" => $data->firstItem(),
            "count" => $data->total(),
            "data" => $data
        ]);
    }
}
