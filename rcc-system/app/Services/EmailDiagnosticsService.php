<?php

namespace App\Services;

class EmailDiagnosticsService
{
    public function probe(string $host, int $port, string $encryption = 'tls', float $timeout = 8.0): array
    {
        $start = microtime(true);
        $result = [ 'host'=>$host, 'port'=>$port, 'encryption'=>$encryption, 'timeout'=>$timeout, 'latency_ms'=>0, 'code'=>0, 'error'=>'', 'banner'=>'' ];

        try {
            $target = ($encryption === 'ssl') ? ('ssl://'.$host) : $host;
            $fp = @fsockopen($target, $port, $errno, $errstr, $timeout);
            if ($fp) {
                stream_set_timeout($fp, (int)ceil($timeout));
                $banner = fgets($fp);
                $result['banner'] = $banner ? substr($banner, 0, 200) : '';
                if ($banner && preg_match('/^\d{3}/', $banner, $m)) {
                    $result['code'] = (int)$m[0];
                }
                fclose($fp);
            } else {
                $result['error'] = $errstr ?: 'connect failed';
            }
        } catch (\Throwable $e) {
            $result['error'] = $e->getMessage();
        }

        $result['latency_ms'] = (int) round((microtime(true) - $start) * 1000);
        return $result;
    }

    public function validateAddress(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function checkSizeLimit(string $message, int $maxBytes = 1024 * 1024): bool
    {
        return strlen($message) <= $maxBytes;
    }
}

