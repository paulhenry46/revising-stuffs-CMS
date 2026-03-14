<?php

namespace App\Http\Middleware;

use App\Models\Curriculum;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class HandleSubdomain
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $appHost = parse_url(config('app.url'), PHP_URL_HOST);

        $subdomain = null;
        if ($appHost && str_ends_with($host, '.' . $appHost)) {
            $subdomain = substr($host, 0, strlen($host) - strlen('.' . $appHost));
        }

        if ($subdomain) {
            $curriculum = Curriculum::where('subdomain', $subdomain)->first();

            if ($curriculum) {
                if (!$curriculum->subdomain_enabled) {
                    $mainUrl = rtrim(config('app.url'), '/') . $request->getRequestUri();
                    return redirect($mainUrl);
                }

                View::share('subdomainCurriculum', $curriculum);
                app()->instance('subdomainCurriculum', $curriculum);
            }
        }

        return $next($request);
    }
}
