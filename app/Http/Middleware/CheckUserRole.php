<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        
        // Log pentru debugging
        Log::info('CheckUserRole middleware', [
            'user_role' => $user ? $user->role : 'no user',
            'required_roles' => $roles,
            'path' => $request->path()
        ]);
        
        if (!$user || !in_array($user->role, $roles)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
            
            // Redirect based on user role
            if ($user) {
                $redirectRoute = match($user->role) {
                    'admin' => 'admin.welcome',
                    'teacher' => 'teacher.welcome',
                    'student' => 'student.welcome',
                    default => '/'
                };
                
                return redirect()->route($redirectRoute)
                    ->with('error', 'Nu aveți permisiunea de a accesa această pagină.');
            }
            
            return redirect()->route('login');
        }

        return $next($request);
    }
}
