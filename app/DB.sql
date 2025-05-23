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
    `id`       INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `title`    VARCHAR(255),
    `url`      VARCHAR(255),
    `username` VARCHAR(255),
    `password` VARCHAR(255),
    `added`    DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated`  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE `accesses`
    ADD COLUMN `application_id` INT UNSIGNED REFERENCES `applications` (`id`) ON UPDATE SET NULL ON DELETE SET NULL AFTER `admin_id`;

INSERT INTO `applications` (`title`, `url`, `username`, `password`)
VALUES ('Application 1', 'https://ipayday.stealthxrproject.com', 'test', 'test'),
       ('Application 2', 'https://ipayday.stealthxrproject.com', 'test', 'test');

ALTER TABLE `admins` ADD COLUMN `referral_code` VARCHAR(255) AFTER `avatar`;

