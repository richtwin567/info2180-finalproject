CREATE DATABASE IF NOT EXISTS `issuetracker` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `issuetracker`;
DROP TABLE IF EXISTS `issues`;
CREATE TABLE `issues`(
    `id` int NOT NULL,
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
ALTER TABLE `issues`
MODIFY `id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `issues`
ADD PRIMARY KEY (`id`),
    ADD KEY `created_by_fk` (`created_by`),
    ADD KEY `assigned_to_fk` (`assigned_to`);
ALTER TABLE `issues`
ADD CONSTRAINT `assigned_to_fk` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE
SET NULL ON UPDATE CASCADE,
    ADD CONSTRAINT `created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` int NOT NULL,
    `firstname` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
    `lastname` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
    `password` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
    `email` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
    `date_joined` datetime NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
ALTER TABLE `users`
MODIFY `id` int NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT;
CREATE USER 'issuetrackeradmin' @'localhost' IDENTIFIED BY 'issueTRACKER11';
GRANT ALL PRIVILEGES ON issuetracker.* TO 'issuetrackeradmin' @'localhost';