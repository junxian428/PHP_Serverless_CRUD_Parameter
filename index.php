<?php declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

return function ($event) {
    $method = $event['requestContext']['http']['method'];
    $path = $event['rawPath'];

    switch ($method) {
        case 'GET':
            return handleGET($event, $path);
        case 'POST':
            return handlePOST($event, $path);
        case 'PUT':
            return handlePUT($event, $path);
        case 'DELETE':
            return handleDELETE($event, $path);
        default:
            return json_encode(['message' => 'Unsupported method']);
    }
};

function handleGET($event, $path) {
    $name = $event['queryStringParameters']['name'] ?? null;

    if ($path === '/hello' && $name !== null) {
        return json_encode(['message' => 'GET Request: Hello ' . $name]);
    }

    return json_encode(['message' => 'Invalid path or missing name parameter']);
}


function handlePOST($event, $path) {
    if ($path === '/hello') {
        $postData = json_decode($event['body'], true);
        return json_encode(['message' => 'POST Request: Hello ' . ($postData['name'] ?? 'world')]);
    }

    return json_encode(['message' => 'Invalid path']);
}

function handlePUT($event, $path) {
    if ($path === '/hello') {
        $putData = json_decode($event['body'], true);
        return json_encode(['message' => 'PUT Request: Hello ' . ($putData['name'] ?? 'world')]);
    }

    return json_encode(['message' => 'Invalid path']);
}

function handleDELETE($event, $path) {
    $name = $event['queryStringParameters']['name'] ?? null;

    if ($path === '/hello' && $name !== null) {
        return json_encode(['message' => 'Delete Request: Hello ' . $name]);
    }

    return json_encode(['message' => 'Invalid path or missing name parameter']);
}


function getParameterFromPath($path, $paramName) {
    $pathParts = explode('/', $path);
    foreach ($pathParts as $i => $part) {
        if ($part === $paramName && isset($pathParts[$i+1])) {
            return $pathParts[$i+1];
        }
    }
    return null;
}
