<?php
declare(strict_types=1);

function db(): PDO
{
    static $pdo;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dataDir = __DIR__ . DIRECTORY_SEPARATOR . 'data';
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0777, true);
    }

    $pdo = new PDO('sqlite:' . $dataDir . DIRECTORY_SEPARATOR . 'orbitanumeros.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys = ON');
    $pdo->exec(<<<'SQL'
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    avatar TEXT NOT NULL DEFAULT 'astronauta',
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS attempts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    game_key TEXT NOT NULL,
    score INTEGER NOT NULL,
    correct INTEGER NOT NULL,
    total INTEGER NOT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS badges (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    badge_key TEXT NOT NULL,
    unlocked_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_id, badge_key),
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
);
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
