<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCursusProfileIsCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (($request->user()->school_id === null) or ($request->user()->curriculum_id === null)) {
            return redirect(route('user.update-cursus-form'));
        }

        return $next($request);
    }
}
