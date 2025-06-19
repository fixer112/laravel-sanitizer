<?php

namespace Fixer112\Sanitizer;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SanitizeInput
{
    public function handle(Request $request, Closure $next)
    {
        $userAgent = strtolower($request->header('User-Agent'));

        $patterns = [
            '/<\s*script\b[^>]*>(.*?)<\s*\/\s*script>/is',
            '/<\s*(iframe|embed|object|svg|link|meta|style)\b[^>]*>/i',
            '/on\w+\s*=\s*["\']?[^"\']*["\']?/i',
            '/javascript:/i',
            '/data\s*:\s*text\/html\s*;?\s*base64,/i',
            '/&#x[0-9a-f]+;/i',
            '/[\s;&|<>`\\\\()]/',
            '/(^|\s)(cmd|powershell|shutdown|del|format|calc|reg|wmic)(\s|$)/i',
            '/\b(select|insert|update|delete|drop|create|alter|truncate|exec|declare)\b/i',
            '/\b(eval|exec|system|passthru|shell_exec|popen|proc_open)\b/i',
        ];

        $input = $request->all();
        $sanitized = [];

        foreach ($input as $key => $value) {
            if (in_array($key, ['password', 'confirm_password'])) {
                $sanitized[$key] = $value;
                continue;
            }

            $flatValues = is_array($value) ? Arr::flatten($value) : [$value];
            $cleanedValues = [];

            foreach ($flatValues as $item) {
                $cleanedItem = $item;
                foreach ($patterns as $pattern) {
                    $cleanedItem = preg_replace($pattern, '', $cleanedItem);
                }
                $cleanedValues[] = $cleanedItem;
            }

            $sanitized[$key] = is_array($value) ? $cleanedValues : $cleanedValues[0];
        }

        $request->merge($sanitized);

        if (
            !$userAgent ||
            str_contains($userAgent, 'bot') ||
            str_contains($userAgent, 'crawler') ||
            str_contains($userAgent, 'spider') ||
            str_contains($userAgent, 'curl') ||
            str_contains($userAgent, 'httpclient') ||
            str_contains($userAgent, 'scrapy')
        ) {
            return response()->json(['message' => 'Bot activity detected'], 422);
        }

        return $next($request);
    }
}
