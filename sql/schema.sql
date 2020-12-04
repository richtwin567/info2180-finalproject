CREATE DATABASE IF NOT EXISTS `issuetracker` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `issuetracker`;
DROP TABLE IF EXISTS `issues`;
CREATE TABLE `issues`(
    `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `title` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
    `description` text COLLATE utf8mb4_general_ci NOT NULL,
    `type` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
    `priority` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
    `status` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
    `assigned_to` int DEFAULT NULL,
    `created_by` int NOT NULL,
    `created` datetime NOT NULL,
    `updated` datetime NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `firstname` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
    `lastname` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
    `password` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
    `email` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
    `date_joined` datetime NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE USER 'issuetrackeradmin' @'localhost' IDENTIFIED BY 'issueTRACKER11';
GRANT ALL PRIVILEGES ON issuetracker.* TO 'issuetrackeradmin' @'localhost';