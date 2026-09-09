<?php
declare(strict_types=1);

function db(): PDO
{
    static $pdo;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = getenv('ORBITA_DB_HOST') ?: '127.0.0.1';
    $port = getenv('ORBITA_DB_PORT') ?: '3306';
    $name = getenv('ORBITA_DB_NAME') ?: 'orbita_numeros';
    $user = getenv('ORBITA_DB_USER') ?: 'root';
    $password = getenv('ORBITA_DB_PASSWORD') ?: '';

    $pdo = new PDO(
        "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4",
        $user,
        $password,
        [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"]
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec(<<<'SQL'
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    avatar VARCHAR(40) NOT NULL DEFAULT 'astronauta',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS attempts (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    game_key VARCHAR(40) NOT NULL,
    score INT NOT NULL,
    correct INT NOT NULL,
    total INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT attempts_user_fk FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX attempts_user_idx (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS badges (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    badge_key VARCHAR(40) NOT NULL,
    unlocked_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY user_badge_unique (user_id, badge_key),
    CONSTRAINT badges_user_fk FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    return $pdo;
}

function currentUser(): ?array
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }
    $stmt = db()->prepare('SELECT id, name, email, avatar, created_at FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch() ?: null;
}

function userStats(int $userId): array
{
    $pdo = db();
    $summary = $pdo->prepare('SELECT COALESCE(SUM(score), 0) AS points, COALESCE(SUM(correct), 0) AS correct, COALESCE(SUM(total), 0) AS total, COUNT(*) AS rounds FROM attempts WHERE user_id = ?');
    $summary->execute([$userId]);
    $stats = $summary->fetch();
    $badges = $pdo->prepare('SELECT badge_key, unlocked_at FROM badges WHERE user_id = ? ORDER BY unlocked_at DESC');
    $badges->execute([$userId]);
    $history = $pdo->prepare('SELECT game_key, score, correct, total, created_at FROM attempts WHERE user_id = ? ORDER BY id DESC LIMIT 8');
    $history->execute([$userId]);
    return [
        'points' => (int)$stats['points'],
        'correct' => (int)$stats['correct'],
        'total' => (int)$stats['total'],
        'rounds' => (int)$stats['rounds'],
        'accuracy' => (int)$stats['total'] > 0 ? round(((int)$stats['correct'] / (int)$stats['total']) * 100) : 0,
        'badges' => $badges->fetchAll(),
        'history' => $history->fetchAll(),
    ];
}
