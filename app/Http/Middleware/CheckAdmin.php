<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->userType === 'admin') {
            return $next($request);
        }

        // return redirect('/')->with('error', 'You do not have admin access.');
        return back()->with('error', 'Anda tidak mempunyai akses admin.');
    }
}
