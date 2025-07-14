<?php

namespace Fixer112\Sanitizer\Middleware;

use Closure;
use Illuminate\Http\Request;

class Sanitizer
{
    public function handle(Request $request, Closure $next)
    {
        $userAgent = strtolower($request->header('User-Agent', ''));
        $clientIp = $request->ip();

        if (app()->runningInConsole() || app()->runningUnitTests()) {
            return $next($request);
        }

        if ($userAgent === '' && $this->isInternalRequestPath($request->path())) {
            return $next($request);
        }

        // Load from config
        $allowedAgents = config('sanitizer.allowed_agents', []);
        $blockedAgents = config('sanitizer.blocked_agents', []);
        $allowLocalhost = config('sanitizer.allow_localhost', true);

        foreach ($allowedAgents as $agent) {
            if (str_contains($userAgent, $agent)) {
                return $next($request);
            }
        }

        if ($allowLocalhost && in_array($clientIp, ['127.0.0.1', '::1'])) {
            return $next($request);
        }

        foreach ($blockedAgents as $bot) {
            if (str_contains($userAgent, $bot)) {
                abort(422, 'Bot activity detected');
            }
        }

        return null;

    }

    protected function isInternalRequestPath(string $path): bool
    {
        $patterns = config('sanitizer.internal_paths', []);

        foreach ($patterns as $pattern) {
            // Convert 'vendor/*' to regex ^vendor/.*$
            $regex = '/^'.str_replace(['*', '/'], ['.*', '\/'], $pattern).'$/i';
            if (preg_match($regex, $path)) {
                return true;
            }
        }

        return false;
    }
}
