<?php
namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldSkip($request, $response)) {
            return $response;
        }

        // Bot skip
        if ($this->isBot($request)) {
            return $response;
        }

        $visitorId = $request->cookie('visitor_id') ?? (string) Str::uuid();
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $path = '/' . ltrim($request->path(), '/');

        $countryName = 'Unknown';
        $countryCode = null;

        try {
            $location = geoip($ip);

            $countryName = $location->country ?? 'Unknown';
            $countryCode = $location->iso_code ?? null;
        } catch (\Throwable $e) {
            // silently fail
        }

        // Per-day unique visitor
        $uniqueKey = 'unique_visit:' . md5($visitorId . '|' . now()->format('Y-m-d'));
        $isUnique = false;

        if (!Cache::has($uniqueKey)) {
            Cache::put($uniqueKey, true, now()->addDay());
            $isUnique = true;
        }

        try {
            Visit::create([
                'visitor_id' => $visitorId,
                'ip' => $ip,
                'country_code' => $countryCode,
                'country_name' => $countryName,
                'user_agent' => $userAgent,
                'path' => $path,
                'is_unique' => $isUnique,
            ]);
        } catch (\Throwable $e) {
            return $response;
        }

        cookie()->queue(cookie(
            'visitor_id',
            $visitorId,
            60 * 24 * 365
        ));

        return $response;
    }

    private function shouldSkip(Request $request, Response $response): bool
    {
        if (App::environment('testing')) {
            return true;
        }

        if ($response->getStatusCode() >= 400) {
            return true;
        }

        if (!$request->isMethod('GET') || $request->expectsJson() || $request->ajax()) {
            return true;
        }

        if ($request->user()) {
            return true;
        }

        return $request->is(
            'dashboard',
            'profile',
            'visitor-dashboard',
            'admin/*',
            '*-section',
            '*-list',
            '*-by-id',
            '*-create',
            '*-update',
            '*-delete',
            'about-save',
            'contact-submit',
            'invoice-*',
            'storage/*',
            'up'
        );
    }

    private function isBot(Request $request): bool
    {
        $agent = strtolower($request->userAgent() ?? '');

        $bots = ['bot', 'crawl', 'spider', 'slurp', 'facebookexternalhit'];

        foreach ($bots as $bot) {
            if (str_contains($agent, $bot)) {
                return true;
            }
        }

        return false;
    }
}
