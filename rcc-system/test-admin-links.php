<?php

// Admin Links Test Script
// This script tests all admin panel links to identify broken ones

$baseUrl = 'http://localhost:8001';
$adminUrl = $baseUrl . '/admin';

// Admin panel routes to test (based on actual Laravel route:list output)
$routes = [
    // Dashboard
    ['url' => '/admin', 'name' => 'Dashboard'],
    
    // Custom Pages
    ['url' => '/admin/pastoreio-history', 'name' => 'Pastoreio History'],
    ['url' => '/admin/presenca-rapida', 'name' => 'Presença Rápida'],
    ['url' => '/admin/duplicates-tool', 'name' => 'Duplicates Tool'],
    
    // Resources
    ['url' => '/admin/users', 'name' => 'Users'],
    ['url' => '/admin/groups', 'name' => 'Groups'],
    ['url' => '/admin/events', 'name' => 'Events'],
    ['url' => '/admin/ministerios', 'name' => 'Ministerios'],
    ['url' => '/admin/payment-logs', 'name' => 'Payment Logs'],
    ['url' => '/admin/visitas', 'name' => 'Visitas'],
    ['url' => '/admin/settings', 'name' => 'Settings'],
    ['url' => '/admin/logs', 'name' => 'Logs'],
];

echo "Testing Admin Panel Links\n";
echo "========================\n\n";

$brokenLinks = [];
$workingLinks = [];

foreach ($routes as $route) {
    $fullUrl = $baseUrl . $route['url'];
    echo "Testing: {$route['name']} ({$fullUrl})... ";
    
    $ch = curl_init($fullUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($httpCode >= 200 && $httpCode < 400) {
        echo "✅ WORKING (HTTP {$httpCode})\n";
        $workingLinks[] = $route;
    } else {
        echo "❌ BROKEN (HTTP {$httpCode})\n";
        if ($error) {
            echo "   Error: {$error}\n";
        }
        $brokenLinks[] = array_merge($route, ['http_code' => $httpCode, 'error' => $error]);
    }
}

echo "\n";
echo "Test Results\n";
echo "============\n";
echo "Total links tested: " . count($routes) . "\n";
echo "Working links: " . count($workingLinks) . "\n";
echo "Broken links: " . count($brokenLinks) . "\n";

if (!empty($brokenLinks)) {
    echo "\nBroken Links Details:\n";
    echo "====================\n";
    foreach ($brokenLinks as $link) {
        echo "- {$link['name']}: {$baseUrl}{$link['url']} (HTTP {$link['http_code']})\n";
        if ($link['error']) {
            echo "  Error: {$link['error']}\n";
        }
    }
}

if (!empty($workingLinks)) {
    echo "\nWorking Links Details:\n";
    echo "=====================\n";
    foreach ($workingLinks as $link) {
        echo "- {$link['name']}: {$baseUrl}{$link['url']}\n";
    }
}

echo "\n";
echo "Recommendations:\n";
echo "================\n";

if (!empty($brokenLinks)) {
    echo "1. Check Laravel routes configuration (php artisan route:list)\n";
    echo "2. Verify Filament resource registrations\n";
    echo "3. Check middleware and permission requirements\n";
    echo "4. Ensure database migrations are run\n";
    echo "5. Check server error logs for detailed error messages\n";
} else {
    echo "✅ All admin panel links are working correctly!\n";
}

echo "\nTo get more detailed information, run:\n";
echo "- php artisan route:list | grep admin\n";
echo "- tail -f storage/logs/laravel.log\n";
echo "- php artisan route:list --name=admin\n";