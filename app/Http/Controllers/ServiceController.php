<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Service;
use App\Models\ScienceType;
use App\Models\Countries;
use App\Models\PublicationType;
use App\Models\Roles;
use App\Models\JobType;
use App\Models\PatentType;

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

  function getScienceTypes() {
    $data = ScienceType::get();
    return response()->json($data);
  }

  function getCountry() {
    $data = Countries::select('name')->get()->toArray();
    return response()->json(array_column($data, 'name'));
  }

  function typePublications() {
    $data = PublicationType::get();
    return response()->json($data);
  }

  function getRoles() {
    $data = Roles::get();
    return response()->json($data);
  }

  function jobType() {
    $data = JobType::get();
    return response()->json($data);
  }

  function getPatentTypes() {
    $data = PatentType::get();
    return response()->json($data);
  }
}
