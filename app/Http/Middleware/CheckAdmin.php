<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdmin {
  public function handle(Request $request, Closure $next) {
    if($request->session()->get('person')['roles_id'] != 4) {
      return redirect('/');
    }
    return $next($request);
  }
}
