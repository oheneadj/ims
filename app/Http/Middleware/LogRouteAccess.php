<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class LogRouteAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        $response = $next($request);
        
        $endTime = microtime(true);
        $duration = round(($endTime - $startTime) * 1000, 2); // in ms

        // Only log web routes (exclude API, debugbar, assets, livewire messages if deemed too noisy)
        // For deep logging we log everything except assets and filament internal routes if any
        if ($this->shouldLog($request)) {
            $user = Auth::user();
            
            activity('route_access')
                ->causedBy($user)
                ->withProperties([
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'route_name' => $request->route() ? $request->route()->getName() : null,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'payload' => $request->except(['password', 'password_confirmation', '_token']),
                    'status_code' => $response->getStatusCode(),
                    'duration_ms' => $duration,
                ])
                ->log($this->getLogDescription($request));
        }

        return $response;
    }

    protected function shouldLog(Request $request): bool
    {
        // Don't log debugbar, livewire internal updates (unless critical), or assets
        if ($request->is('_debugbar*')) return false;
        
        // Optional: Filter out pure Livewire polling if it spams logs
        // if ($request->routeIs('livewire.update')) return false; 

        return true;
    }

    protected function getLogDescription(Request $request): string
    {
        $name = $request->route() ? $request->route()->getName() : 'Unknown Route';
        return "Visited {$request->path()} ({$name})";
    }
}
