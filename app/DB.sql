CREATE TABLE `accesses`
(
    `id`         BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `admin_id`   INT UNSIGNED,
    `controller` VARCHAR(255),
    `method`     VARCHAR(255),
    `available`  BOOLEAN  DEFAULT 1,
    `added`      DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated`    DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON UPDATE SET NULL ON DELETE SET NULL
);

CREATE TABLE `applications`
(
    `id`      INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `title`   VARCHAR(255),
    `url`     VARCHAR(255),
    `token`   VARCHAR(255),
    `added`   DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE `accesses`
    ADD COLUMN `application_id` INT UNSIGNED REFERENCES `applications` (`id`) ON UPDATE SET NULL ON DELETE SET NULL AFTER `admin_id`;

INSERT INTO `applications` (`title`, `url`, `token`)
VALUES ('Application 1', 'https://admin-panel.vytovtov.pro/test', 'test_token'),
       ('Application 2', 'https://admin-panel.vytovtov.pro/test', 'test_token');