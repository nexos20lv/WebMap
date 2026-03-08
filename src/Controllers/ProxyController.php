<?php

namespace Azuriom\Plugin\WebMap\Controllers;

use Azuriom\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProxyController extends Controller
{
    /**
     * Proxy all requests to the WebMap/LiveAtlas server.
     */
    public function proxy(Request $request, string $path = '')
    {
        $baseUrl = rtrim(setting('webmap.url', 'http://play.nexaria.fr:8123'), '/');
        $targetUrl = $baseUrl . '/' . ltrim($path, '/');
        $proxyBase = url('/webmap/proxy');

        // Forward query string
        if ($qs = $request->getQueryString()) {
            $targetUrl .= '?' . $qs;
        }

        try {
            $pendingRequest = Http::timeout(15)
                ->withHeaders([
                    'X-Forwarded-For' => $request->ip(),
                    'X-Forwarded-Host' => $request->getHost(),
                    'Accept' => $request->header('Accept', '*/*'),
                    'Accept-Encoding' => 'identity', // disable gzip so we can rewrite text
                ]);

            $method = strtolower($request->method());
            $response = in_array($method, ['post', 'put', 'patch'])
                ? $pendingRequest->$method($targetUrl, $request->all())
                : $pendingRequest->get($targetUrl);

            $contentType = $response->header('Content-Type', 'application/octet-stream');
            $status = $response->status();
            $body = $response->body();

            // Only rewrite text-based responses
            $isText = str_contains($contentType, 'text/')
                || str_contains($contentType, 'javascript')
                || str_contains($contentType, 'json');

            if ($isText) {
                // Rewrite any absolute path starting with "/" to go through the proxy.
                // This covers src="/...", href="/...", url(/...), fetch('/...'), etc.

                // 1. HTML attributes: src="/path" href="/path" action="/path"
                $body = preg_replace_callback(
                    '/(src|href|action|content|data-src)=(["\'])\/(?!\/)/i',
                    fn($m) => $m[1] . '=' . $m[2] . $proxyBase . '/',
                    $body
                );

                // 2. JS strings: "/path"  '/path'
                $body = preg_replace_callback(
                    '/(["\'])(\/(tiles|standalone|assets|images|js|css|fonts|web)[^"\']*)\1/',
                    fn($m) => $m[1] . $proxyBase . $m[2] . $m[1],
                    $body
                );

                // 3. CSS url(/path)
                $body = preg_replace_callback(
                    '/url\((["\']?)\/(?!\/)/',
                    fn($m) => 'url(' . $m[1] . $proxyBase . '/',
                    $body
                );

                // 4. Inject base tag in HTML head for any remaining relative URLs
                if (str_contains($contentType, 'text/html')) {
                    $baseTag = '<base href="' . $proxyBase . '/">';
                    $body = preg_replace('/<head([^>]*)>/i', '<head$1>' . $baseTag, $body);
                }
            }

            return response($body, $status)
                ->header('Content-Type', $contentType)
                ->header('Cache-Control', 'public, max-age=60')
                ->header('X-Frame-Options', '')
                ->header('Content-Security-Policy', '');

        } catch (\Exception $e) {
            return response(
                '<div style="font-family:sans-serif;padding:40px;text-align:center;">'
                . '<h2>🗺️ Dynamic map unavailable</h2>'
                . '<p>Unable to connect to: <code>' . e($baseUrl) . '</code></p>'
                . '<p style="color:#888">' . e($e->getMessage()) . '</p></div>',
                503,
                ['Content-Type' => 'text/html']
            );
        }
    }
}
