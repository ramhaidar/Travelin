<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle ( Request $request, Closure $next ) : Response
    {
        // Jika user terautentikasi, arahkan ke rute onboarding
        if ( auth ()->check () )
        {
            return redirect ( "/" )->with ( 'success', 'Already logged in' );
        }

        // Jika user tidak terautentikasi, arahkan ke rute login
        return redirect ( "/login" )->with ( "error", "Unauthorized access" );
    }
}
