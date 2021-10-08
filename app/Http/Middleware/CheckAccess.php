<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\Service;

class CheckAccess
{
    public function handle(Request $request, Closure $next) {
      $access = Service::select('value')->where('key', 'access')->first()->value;
      if($access == 'close' && $request->session()->get('person')['roles_id'] != 4) {
        return redirect('/');
      }
      return $next($request);
    }
}
