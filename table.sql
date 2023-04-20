
CREATE TABLE `users` (
						 `id` int NOT NULL AUTO_INCREMENT,
						 `username` varchar(100) NOT NULL,
						 `email` varchar(100) NOT NULL,
						 `password` text NOT NULL,
						 `created_at` datetime NOT NULL,
						 `updated_at` datetime NOT NULL,
						 PRIMARY KEY (`id`),
						 UNIQUE KEY `users_UN` (`username`),
						 UNIQUE KEY `users_UN_2` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=1622 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `articles` (
							`id` int NOT NULL AUTO_INCREMENT,
							`title` varchar(100) NOT NULL,
							`content` text NOT NULL,
							`created_by_id` int NOT NULL,
							`created_at` datetime NOT NULL,
							`updated_by_id` int NOT NULL,
							`updated_at` datetime NOT NULL,
							PRIMARY KEY (`id`),
							KEY `articles_FK` (`created_by_id`),
							KEY `articles_FK_1` (`updated_by_id`),
							CONSTRAINT `articles_FK` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`),
							CONSTRAINT `articles_FK_1` FOREIGN KEY (`updated_by_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



CREATE TABLE `tokens` (
						  `id` int unsigned NOT NULL AUTO_INCREMENT,
						  `user_id` int NOT NULL,
						  `refresh_token` text NOT NULL,
						  `access_token` text NOT NULL,
						  PRIMARY KEY (`id`),
						  KEY `user_id` (`user_id`),
						  CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
