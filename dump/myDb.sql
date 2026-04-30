SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
START TRANSACTION;

CREATE TABLE `Theme` (
  `name` VARCHAR (255) NOT NULL,
  PRIMARY KEY (`name`)
);

CREATE TABLE `Playlist` (
  `name` VARCHAR (255) NOT NULL,
  PRIMARY KEY (`name`)
);

CREATE TABLE `SuitableFor` (
  `theme_name`    VARCHAR (255) NOT NULL,
  `playlist_name` VARCHAR (255) NOT NULL,
  CONSTRAINT `fk_suitablefor_theme`    FOREIGN KEY (`theme_name`)    REFERENCES `Theme`    (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_suitablefor_playlist` FOREIGN KEY (`playlist_name`) REFERENCES `Playlist` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`theme_name`, `playlist_name`),
  INDEX `idx_suitablefor_playlist_name` (`playlist_name`)
);

CREATE TABLE `CD` (
  `cd_number` BIGINT  UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`     VARCHAR (255)    NOT NULL,
  `copies`    INT     UNSIGNED NOT NULL DEFAULT 1,
  `producer`  VARCHAR (255),
  `year`      YEAR,
  PRIMARY KEY (`cd_number`)
);

CREATE TABLE `Genre` (
  `name` VARCHAR (255) NOT NULL,
  PRIMARY KEY (`name`)
);

CREATE TABLE `Specializes` (
  `subgenre_name` VARCHAR (255) NOT NULL,
  `genre_name`    VARCHAR (255) NOT NULL,
  CONSTRAINT `fk_specializes_subgenre` FOREIGN KEY (`subgenre_name`) REFERENCES `Genre` (`name`) ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_specializes_genre`    FOREIGN KEY (`genre_name`)    REFERENCES `Genre` (`name`) ON DELETE RESTRICT ON UPDATE CASCADE,
  PRIMARY KEY (`subgenre_name`, `genre_name`),
  INDEX `idx_specializes_genre_name` (`genre_name`)
);

CREATE TABLE `Song` (
  `cd_number`    BIGINT  UNSIGNED NOT NULL,
  `track_number` INT     UNSIGNED NOT NULL DEFAULT 1,
  `title`        VARCHAR (255)    NOT NULL,
  `genre_name`   VARCHAR (255),
  `artist`       VARCHAR (255),
  `duration`     TIME,
  CONSTRAINT `fk_song_cd`    FOREIGN KEY (`cd_number`)  REFERENCES `CD`    (`cd_number`) ON DELETE CASCADE  ON UPDATE RESTRICT,
  CONSTRAINT `fk_song_genre` FOREIGN KEY (`genre_name`) REFERENCES `Genre` (`name`)      ON DELETE SET NULL ON UPDATE CASCADE,
  PRIMARY KEY (`cd_number`, `track_number`),
  INDEX `idx_song_track_number` (`track_number`),
  INDEX `idx_song_genre_name`   (`genre_name`)
);

CREATE TABLE `Contains` (
  `playlist_name` VARCHAR (255) NOT NULL,
  `cd_number`     BIGINT  UNSIGNED NOT NULL,
  `track_number`  INT     UNSIGNED NOT NULL DEFAULT 1,
  CONSTRAINT `fk_contains_playlist` FOREIGN KEY (`playlist_name`)             REFERENCES `Playlist` (`name`)                      ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_contains_song`     FOREIGN KEY (`cd_number`, `track_number`) REFERENCES `Song`     (`cd_number`, `track_number`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`playlist_name`, `cd_number`, `track_number`),
  INDEX `idx_contains_cd_number`    (`cd_number`)
);

CREATE TABLE `Employee` (
  `id`         BIGINT  UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR (255)    NOT NULL,
  `last_name`  VARCHAR (255)    NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `DJ` (
  `id` BIGINT UNSIGNED NOT NULL,
  CONSTRAINT `fk_dj_employee` FOREIGN KEY (`id`) REFERENCES `Employee` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  PRIMARY KEY (`id`)
);

CREATE TABLE `EventPlanner` (
  `id` BIGINT UNSIGNED NOT NULL,
  CONSTRAINT `fk_eventplanner_employee` FOREIGN KEY (`id`) REFERENCES `Employee` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  PRIMARY KEY (`id`)
);

CREATE TABLE `Manager` (
  `id` BIGINT UNSIGNED NOT NULL,
  CONSTRAINT `fk_manager_employee` FOREIGN KEY (`id`) REFERENCES `Employee` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  PRIMARY KEY (`id`)
);

CREATE TABLE `Specialization` (
  `dj`    BIGINT  UNSIGNED NOT NULL,
  `genre` VARCHAR (255)    NOT NULL,
  CONSTRAINT `fk_specialization_dj`    FOREIGN KEY (`dj`)    REFERENCES `DJ`    (`id`)   ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_specialization_genre` FOREIGN KEY (`genre`) REFERENCES `Genre` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`dj`, `genre`),
  INDEX `idx_specialization_genre` (`genre`)
);

CREATE TABLE `Supervision` (
  `supervisor_id` BIGINT UNSIGNED NOT NULL,
  `employee_id`   BIGINT UNSIGNED NOT NULL,
  CONSTRAINT `fk_supervision_manager`  FOREIGN KEY (`supervisor_id`) REFERENCES `Manager`  (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_supervision_employee` FOREIGN KEY (`employee_id`)   REFERENCES `Employee` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  PRIMARY KEY (`employee_id`),
  INDEX `idx_supervision_supervisor_id` (`supervisor_id`)
);

CREATE TABLE `Client` (
  `client_number` BIGINT  UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name`    VARCHAR (255)    NOT NULL,
  `last_name`     VARCHAR (255)    NOT NULL,
  `email_address` VARCHAR (255)             UNIQUE,
  `phone_number`  VARCHAR (20)     NOT NULL,
  PRIMARY KEY (`client_number`)
);

CREATE TABLE `Location` (
  `id`          BIGINT  UNSIGNED NOT NULL AUTO_INCREMENT,
  `street`      VARCHAR (255)    NOT NULL,
  `city`        VARCHAR (255)    NOT NULL,
  `postal_code` VARCHAR (12)     NOT NULL,
  `country`     VARCHAR (255)    NOT NULL,
  `comment`     TEXT,
  PRIMARY KEY (`id`)
);

CREATE TABLE `Event` (
  `id`            BIGINT  UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`          VARCHAR (255)    NOT NULL,
  `date`          DATE             NOT NULL,
  `description`   TEXT,
  `client`        BIGINT  UNSIGNED NOT NULL,
  `manager`       BIGINT  UNSIGNED NOT NULL,
  `event_planner` BIGINT  UNSIGNED,
  `dj`            BIGINT  UNSIGNED,
  `theme`         VARCHAR (255),
  `type`          VARCHAR (255)    NOT NULL,
  `location`      BIGINT  UNSIGNED,
  `rental_fee`    DECIMAL (7, 2),
  `playlist`      VARCHAR (255),
  CONSTRAINT `fk_event_client`       FOREIGN KEY (`client`)        REFERENCES `Client`       (`client_number`) ON DELETE CASCADE  ON UPDATE RESTRICT,
  CONSTRAINT `fk_event_manager`      FOREIGN KEY (`manager`)       REFERENCES `Manager`      (`id`)            ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_event_eventplanner` FOREIGN KEY (`event_planner`) REFERENCES `EventPlanner` (`id`)            ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `fk_event_dj`           FOREIGN KEY (`dj`)            REFERENCES `DJ`           (`id`)            ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `fk_event_theme`        FOREIGN KEY (`theme`)         REFERENCES `Theme`        (`name`)          ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_event_location`     FOREIGN KEY (`location`)      REFERENCES `Location`     (`id`)            ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `fk_event_playlist`     FOREIGN KEY (`playlist`)      REFERENCES `Playlist`     (`name`)          ON DELETE SET NULL ON UPDATE CASCADE,
  PRIMARY KEY (`id`),
  INDEX `idx_event_client`       (`client`),
  INDEX `idx_event_manager`      (`manager`),
  INDEX `idx_event_eventplanner` (`event_planner`),
  INDEX `idx_event_dj`           (`dj`),
  INDEX `idx_event_theme`        (`theme`),
  INDEX `idx_event_location`     (`location`),
  INDEX `idx_event_playlist`     (`playlist`)
);

CREATE TABLE `Request` (
  `event_id` BIGINT  UNSIGNED NOT NULL,
  `name`     VARCHAR (255)    NOT NULL,
  `provider` VARCHAR (255),
  `price`    DECIMAL (7, 2),
  CONSTRAINT `fk_request_event`        FOREIGN KEY (`event_id`) REFERENCES `Event`        (`id`) ON DELETE CASCADE  ON UPDATE RESTRICT,
  PRIMARY KEY (`event_id`, `name`)
);

COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
