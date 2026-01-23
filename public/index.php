<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Model\User;
use Dotenv\Dotenv;
use App\Repository\JsonUserRepository;
use App\Repository\MysqlUserRepository;
use App\Service\UserService;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$dbSource = $_ENV['DB_SOURCE'] ?? 'json';

if ($dbSource === 'mysql') {
    $repository = new MysqlUserRepository();
} else {
    $repository = new JsonUserRepository(__DIR__ . '/../db/users.json');
}

$userService = new UserService($repository);


header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($method === 'GET' && $uri === '/users') {
    $users = $userService->getAllUsers();

    $result = [];
    foreach ($users as $user) {
        $result[] = $user->toArray();
    }

    echo json_encode($result, JSON_THROW_ON_ERROR);
    return;
}

if ($method === 'POST' && $uri === '/users') {
    $rawBody = file_get_contents('php://input');
    $data = json_decode($rawBody, true, 512, JSON_THROW_ON_ERROR);

    if (!is_array($data)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON'], JSON_THROW_ON_ERROR);
        return;
    }

    if (
        empty($data['name']) ||
        empty($data['familiya']) ||
        empty($data['email'])
    ) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing fields'], JSON_THROW_ON_ERROR);
        return;
    }

    $id = $userService->getNextId();

    $user = new User(
        $id,
        $data['name'],
        $data['familiya'],
        $data['email']
    );

    $userService->addUser($user);

    http_response_code(201);
    echo json_encode($user->toArray(), JSON_THROW_ON_ERROR);
    return;
}

if ($method === 'DELETE' && preg_match('#^/users/(\d+)$#', $uri, $matches)) {
    $id = (int)$matches[1];

    $userService->deleteUserById($id);

    echo json_encode([
        'status' => 'deleted',
        'id' => $id,
    ], JSON_THROW_ON_ERROR);
    return;
}

http_response_code(404);
echo json_encode([
    'error' => 'Not Found',
], JSON_THROW_ON_ERROR);
