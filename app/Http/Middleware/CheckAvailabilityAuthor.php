<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\Authors;

class CheckAvailabilityAuthor {
  public function handle(Request $request, Closure $next)
  {
    if(Authors::where('id', $request->route('id'))->exists()) {
      return $next($request);
    }
    return redirect('/error');
  }
}
