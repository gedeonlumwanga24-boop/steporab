<?php

require __DIR__ . '/../../vendor/autoload.php';

$app = require __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

putenv('APP_ENV=testing');
$_ENV['APP_ENV'] = 'testing';
config(['app.env' => 'testing']);

$tests = [
    ['GET', '/api/v1/cart'],
    ['DELETE', '/api/v1/cart'],
    ['GET', '/'],
    ['GET', '/api/v1/search?q=test'],
];

foreach ($tests as [$method, $uri]) {
    $request = Illuminate\Http\Request::create($uri, $method);
    $request->headers->set('Accept', 'application/json');
    $response = $app->handle($request);
    echo "{$method} {$uri} => {$response->getStatusCode()}\n";
    if ($response->getStatusCode() >= 400) {
        echo substr($response->getContent(), 0, 200) . "\n";
    }
}
