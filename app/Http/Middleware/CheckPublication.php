<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\Publications;

class CheckPublication {
  public function handle(Request $request, Closure $next) {
    if($request->session()->get('person')['roles_id'] == 1) {
      $model = Publications::with('authors.author')->where('status_id', 1)->whereHas('authors.author', function($q) use ($request) {
        $q->where('id', $request->session()->get('person')['id']);
      })->where('id', $request->id)->exists();
      if($model) {
        return $next($request);
      }
      return redirect('/');
    } else {
      return $next($request);
    }
  }
}
