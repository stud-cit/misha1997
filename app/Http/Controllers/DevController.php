<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Authors;
use App\Models\Users;
use App\Models\Roles;
use App\Models\Notifications;
use App\Models\Publications;
use Session;
use Config;

class DevController extends ASUController
{
    protected $cabinet_api = "https://cabinet.sumdu.edu.ua/api/";
    protected $asu_key = "eRi1FIAppqFDryG2PFaYw75S1z4q2ZoG";
    protected $cabinet_service_token;

    function __construct() {
        $this->cabinet_service_token = config('app.token');
    }

    function checkStudent() {
        $data = Authors::select('name', 'academic_code', 'faculty_code', 'department_code')->orderBy('name', 'ASC')->where('categ_1', 1)->update([
            'faculty_code' => null,
            'department_code' => null
        ]);
        return response('ok', 200);
    }

    function checkSSUNotWork() {
        // $data = Authors::select('name', 'guid', 'faculty_code', 'department_code', 'job')->orderBy('name', 'ASC')->where('job', 'СумДУ')->where('guid', null)->update([
        //     "job" => "СумДУ (Не працює)"
        // ]);
        $data = Authors::select('name', 'guid', 'faculty_code', 'department_code', 'job')->orderBy('name', 'ASC')->where('job', 'like', '%Сумський державний університет%')->get();
        return response()->json($data);
    }

    // authors All (admin)
    function getAll(Request $request) {
        $data = Authors::with('role')->orderBy('name', 'ASC')->get();

        $divisions = $this->getAllDivision()->original;

        foreach ($data as $key => $value) {
            $value['position'] = $this->getPosition($value);

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
}
