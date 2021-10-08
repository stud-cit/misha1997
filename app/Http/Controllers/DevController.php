<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Authors;
use App\Models\Service;
use App\Models\Publications;
use App\Models\AuthorsPublications;

class DevController extends Controller
{
  function set(Request $request) {
    Authors::find(2)->update([
      'faculty_code' => $request->faculty_code,
      'department_code' => $request->department_code,
      'roles_id' => $request->roles_id
    ]);
    $person = $request->session()->get('person');
    $person['faculty_code'] = $request->faculty_code;
    $person['department_code'] = $request->department_code;
    $person['roles_id'] = $request->roles_id;
    $request->session()->put('person', $person);
    Service::where('key', 'access')->update([
      'value' => $request->access
    ]);
  }

  // Публікації у фахових Категорії Б які в Scopus змінити тип на інші статті

  function publicationType() {
    $model = Publications::whereIn('science_type_id', [1, 3])->where('publication_type_id', 1)->update([
      'publication_type_id' => 3
    ]);
    return response()->json($model);
  }

  // Fix duplicate authors

  function supervsior() {
    $duplicate = $this->checkDuplicate();
    $model = Publications::with('authors')->get();
    foreach ($model as $value) {
      $uniqueRes = $this->getUniqueAuthors($value->authors);
      if($uniqueRes) {
        AuthorsPublications::where('publications_id', $value->id)->where('autors_id', $uniqueRes)->delete();
        AuthorsPublications::create([
          'autors_id' => $uniqueRes,
          'publications_id' => $value->id,
          'supervisor' => 0
        ]);
      }
    }
    return response()->json($duplicate);
  }

  function checkDuplicate() {
    $result = [];
    $model = Publications::with('authors')->get();
    foreach ($model as $value) {
      if($this->getUniqueAuthors($value->authors)) {
        array_push($result, $value->id);
      }
    }
    return response()->json($result);
  }

  function getUniqueAuthors($array) {
    $autorsId = [];
    $res = "";
    foreach ($array as $value) {
      if(in_array($value->autors_id, $autorsId)) {
        $res = $value->autors_id;
      }
      array_push($autorsId, $value->autors_id);
    }
    return $res;
  }
}
