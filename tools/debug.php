<?php
require __DIR__ . '/../vendor/autoload.php';
try {
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "App booted\n";
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    echo "Bootstrapped kernel\n";
    $config = $app['config']->all();
    echo "APP NAME: " . ($config['app']['name'] ?? 'n/a') . "\n";
    echo "Providers count: " . count($config['app']['providers'] ?? []) . "\n";
    echo "Providers: \n";
    foreach (($config['app']['providers'] ?? []) as $p) {
        echo " - $p\n";
    }
    $routes = $app['router']->getRoutes();
    echo "Routes count: " . count($routes->getRoutes()) . "\n";
    foreach ($routes->getRoutes() as $r) {
        echo $r->uri() . " -> " . implode(',', (array)$r->getActionName()) . "\n";
    }
} catch (Throwable $e) {
    echo "EXCEPTION: " . get_class($e) . " - " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}

