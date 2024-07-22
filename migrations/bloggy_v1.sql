CREATE TABLE IF NOT EXISTS `User` (
    `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` varchar(50) NOT NULL,
    `first_name` varchar(50) NOT NULL,
    `last_name` varchar(50) NOT NULL,
    `email` varchar(255) NOT NULL,
    `password` varchar(60) NOT NULL,
    `status` tinyint(1) NOT NULL,
    `image_name` text NOT NULL,
    PRIMARY KEY (`user_id`),
    KEY `username` (`username`),
    KEY `email` (`email`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `Blog` (
    `blog_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `summary` varchar(500) NOT NULL,
    `content` text NOT NULL,
    `user_id` int(10) UNSIGNED NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`blog_id`),
    KEY `title` (`title`),
    KEY `user_id` (`user_id`),
    CONSTRAINT `blog_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `Comment` (
    `comment_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `content` text NOT NULL,
    `is_edited` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`comment_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;


CREATE TABLE IF NOT EXISTS `UserBlogComment` (
    `blog_id` int(10) UNSIGNED NOT NULL,
    `user_id` int(10) UNSIGNED NOT NULL,
    `comment_id` int(10) UNSIGNED NOT NULL,
    PRIMARY KEY (`blog_id`, `comment_id`),
    KEY `user_id` (`user_id`),
    KEY `comment_id` (`comment_id`),
    CONSTRAINT `userblogcomment_ibfk_1` FOREIGN KEY (`blog_id`) REFERENCES `Blog` (`blog_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `userblogcomment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `userblogcomment_ibfk_3` FOREIGN KEY (`comment_id`) REFERENCES `Comment` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;