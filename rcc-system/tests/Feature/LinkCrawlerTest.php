<?php

namespace Tests\Feature;

use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class LinkCrawlerTest extends TestCase
{
    /**
     * Crawl internal links and report broken ones.
     */
    public function test_internal_links_are_not_broken(): void
    {
        $base = rtrim(getenv('TEST_BASE_URL') ?: 'http://127.0.0.1', '/');
        $host = parse_url($base, PHP_URL_HOST);
        $queue = [$base . '/'];
        $visited = [];
        $broken = [];
        $maxPages = 200;

        while ($queue && count($visited) < $maxPages) {
            $url = array_shift($queue);
            if (isset($visited[$url])) {
                continue;
            }
            $visited[$url] = true;

            try {
                [$status, $html] = $this->fetch($url);
                if ($status >= 400) {
                    $broken[] = ['url' => $url, 'status' => $status];
                    continue;
                }
                foreach ($this->extractLinks($html) as $link) {
                    $next = $this->normalizeUrl($link, $url, $base);
                    if (! $next) {
                        continue;
                    }
                    $nextHost = parse_url($next, PHP_URL_HOST);
                    $nextPath = parse_url($next, PHP_URL_PATH) ?: '';
                    // Skip known POST-only or protected endpoints
                    $ignorePaths = ['/pastoreio/attendance', '/pastoreio/draw', '/checkout'];
                    if (in_array($nextPath, $ignorePaths, true)) {
                        continue;
                    }
                    if ($nextHost === $host) {
                        if (! isset($visited[$next])) {
                            $queue[] = $next;
                        }
                    } else {
                        // External link: check status but don't crawl deeper
                        try {
                            [$s, $_] = $this->fetch($next);
                            if ($s >= 400) {
                                $broken[] = ['url' => $next, 'status' => $s, 'external' => true];
                            }
                        } catch (\Throwable $e) {
                            $broken[] = ['url' => $next, 'status' => -1, 'error' => $e->getMessage(), 'external' => true];
                        }
                    }
                }
            } catch (\Throwable $e) {
                $broken[] = ['url' => $url, 'status' => -1, 'error' => $e->getMessage()];
            }
        }

        // Save report
        $report = [
            'base' => $base,
            'visited_count' => count($visited),
            'broken_count' => count($broken),
            'broken' => $broken,
        ];
        $logDir = __DIR__ . '/../../storage/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0777, true);
        }
        file_put_contents(__DIR__ . '/../../storage/logs/link-report.json', json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        // Assert no internal broken links
        $internalBroken = array_filter($broken, fn ($b) => empty($b['external']));
        $internalBroken = array_filter($internalBroken, fn ($b) => !str_contains($b['url'] ?? '', '/build/assets/app-'));
        $this->assertCount(0, $internalBroken, 'Internal broken links found: ' . json_encode($internalBroken));
    }

    private function extractLinks(string $html): array
    {
        $links = [];
        if (! $html) return $links;
        if (preg_match_all('/href\s*=\s*"([^"]+)"/i', $html, $m)) {
            $links = array_merge($links, $m[1]);
        }
        if (preg_match_all('/href\s*=\s*\'([^\']+)\'/i', $html, $m2)) {
            $links = array_merge($links, $m2[1]);
        }
        return array_values(array_unique($links));
    }

    private function normalizeUrl(string $href, string $currentUrl, string $base): ?string
    {
        if (Str::startsWith($href, ['mailto:', 'tel:', 'javascript:', '#'])) {
            return null;
        }
        if (Str::startsWith($href, ['http://', 'https://'])) {
            return $href;
        }
        // Resolve relative
        $prefix = rtrim($base, '/');
        if (Str::startsWith($href, '/')) {
            return $prefix . $href;
        }
        $curr = rtrim($currentUrl, '/');
        return $curr . '/' . $href;
    }

    private function fetch(string $url): array
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return [$status ?: 0, $body ?: ''];
    }
}
