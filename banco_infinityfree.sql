-- Estrutura do banco do site Órbita dos Números.
-- Selecione o banco criado no painel da InfinityFree antes de importar este arquivo.

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(190) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `avatar` VARCHAR(40) NOT NULL DEFAULT 'astronauta',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `attempts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `game_key` VARCHAR(40) NOT NULL,
  `score` INT NOT NULL,
  `correct` INT NOT NULL,
  `total` INT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `attempts_user_idx` (`user_id`),
  CONSTRAINT `attempts_user_fk`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `badges` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `badge_key` VARCHAR(40) NOT NULL,
  `unlocked_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_badge_unique` (`user_id`, `badge_key`),
  CONSTRAINT `badges_user_fk`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
