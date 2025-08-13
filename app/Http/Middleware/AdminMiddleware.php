<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        $user = Auth::user();
        
        // Check if user has the 'Administrator' role (checking both name and slug)
        $isAdmin = $user->roles->contains(function($role) {
            return in_array(strtolower($role->name), ['administrator', 'admin']) || 
                   in_array(strtolower($role->slug), ['administrator', 'admin']);
        });

        if (!$isAdmin) {
            return redirect()->route('posts.index')
                ->with('error', 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
