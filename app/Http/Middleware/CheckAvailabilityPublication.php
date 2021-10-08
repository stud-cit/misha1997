<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\Publications;

class CheckAvailabilityPublication {
    public function handle(Request $request, Closure $next)
    {
      if(Publications::where('id', $request->route('id'))->exists()) {
        return $next($request);
      }
      return redirect('/error');
    }
}
