<?php
declare(strict_types=1);

session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/database.php';

function input(): array
{
    $raw = file_get_contents('php://input');
    return $raw ? (json_decode($raw, true) ?: []) : $_POST;
}
function respond(array $data, int $status = 200): never
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
function requireUser(): array
{
    $user = currentUser();
    if (!$user) respond(['error' => 'Faça login para continuar.'], 401);
    return $user;
}

try {
    $action = $_GET['action'] ?? 'me';
    $data = input();
    $pdo = db();

    if ($action === 'register') {
        $name = trim((string)($data['name'] ?? ''));
        $email = strtolower(trim((string)($data['email'] ?? '')));
        $password = (string)($data['password'] ?? '');
        if (strlen($name) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
            respond(['error' => 'Informe nome, e-mail válido e senha com pelo menos 6 caracteres.'], 422);
        }
        $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
        $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
        $_SESSION['user_id'] = (int)$pdo->lastInsertId();
        respond(['user' => currentUser(), 'stats' => userStats((int)$_SESSION['user_id'])], 201);
    }

    if ($action === 'login') {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([strtolower(trim((string)($data['email'] ?? '')))]);
        $user = $stmt->fetch();
        if (!$user || !password_verify((string)($data['password'] ?? ''), $user['password_hash'])) {
            respond(['error' => 'E-mail ou senha não conferem.'], 422);
        }
        $_SESSION['user_id'] = (int)$user['id'];
        respond(['user' => currentUser(), 'stats' => userStats((int)$user['id'])]);
    }

    if ($action === 'logout') {
        session_destroy();
        respond(['ok' => true]);
    }

    if ($action === 'me') {
        $user = currentUser();
        respond($user ? ['user' => $user, 'stats' => userStats((int)$user['id'])] : ['user' => null]);
    }

    if ($action === 'submit') {
        $user = requireUser();
        $gameKey = preg_replace('/[^a-z0-9-]/', '', strtolower((string)($data['game'] ?? '')));
        $score = max(0, min(1000, (int)($data['score'] ?? 0)));
        $correct = max(0, (int)($data['correct'] ?? 0));
        $total = max(1, (int)($data['total'] ?? 1));
        $pdo->prepare('INSERT INTO attempts (user_id, game_key, score, correct, total) VALUES (?, ?, ?, ?, ?)')->execute([$user['id'], $gameKey, $score, $correct, $total]);
        $newBadges = [];
        $definitions = [['first-flight', 'Primeiro voo', 'Complete seu primeiro desafio'], ['perfect', 'Mira perfeita', 'Acerte todas as questões de uma rodada'], ['explorer', 'Explorador orbital', 'Jogue os quatro desafios']];
        foreach ($definitions as [$key, $label, $description]) {
            $eligible = ($key === 'first-flight' && userStats((int)$user['id'])['rounds'] === 1) || ($key === 'perfect' && $correct === $total) || ($key === 'explorer' && count(array_unique(array_column($pdo->query('SELECT game_key FROM attempts WHERE user_id = ' . (int)$user['id'])->fetchAll(), 'game_key'))) >= 4);
            if ($eligible) {
                $check = $pdo->prepare('INSERT IGNORE INTO badges (user_id, badge_key) VALUES (?, ?)');
                $check->execute([$user['id'], $key]);
                if ($check->rowCount() > 0) $newBadges[] = ['key' => $key, 'label' => $label, 'description' => $description];
            }
        }
        respond(['stats' => userStats((int)$user['id']), 'newBadges' => $newBadges]);
    }

    if ($action === 'ranking') {
        $ranking = $pdo->query('SELECT u.name, u.avatar, COALESCE(SUM(a.score), 0) AS points FROM users u LEFT JOIN attempts a ON a.user_id = u.id GROUP BY u.id ORDER BY points DESC, u.name ASC LIMIT 10')->fetchAll();
        respond(['ranking' => $ranking]);
    }

    respond(['error' => 'Ação desconhecida.'], 404);
} catch (Throwable $error) {
    respond(['error' => 'Não foi possível concluir a operação agora.', 'detail' => $error->getMessage()], 500);
}
