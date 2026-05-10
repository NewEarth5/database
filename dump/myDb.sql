SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
START TRANSACTION;
SET FOREIGN_KEY_CHECKS = 0;
CREATE TABLE `Theme` (
  `name` VARCHAR (255) NOT NULL,
  PRIMARY KEY (`name`)
);

CREATE TABLE `Playlist` (
  `name` VARCHAR (255) NOT NULL,
  PRIMARY KEY (`name`)
);

CREATE TABLE `SuitableFor` (
  `theme`    VARCHAR (255) NOT NULL,
  `playlist` VARCHAR (255) NOT NULL,
  CONSTRAINT `fk_suitablefor_theme`    FOREIGN KEY (`theme`)    REFERENCES `Theme`    (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_suitablefor_playlist` FOREIGN KEY (`playlist`) REFERENCES `Playlist` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`theme`, `playlist`),
  INDEX `idx_suitablefor_playlist` (`playlist`)
);

CREATE TABLE `CD` (
  `cd_number` BIGINT  UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`     VARCHAR (255)    NOT NULL,
  `producer`  VARCHAR (255),
  `year`      YEAR,
  `copies`    INT     UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`cd_number`)
);

CREATE TABLE `Genre` (
  `name` VARCHAR (255) NOT NULL,
  PRIMARY KEY (`name`)
);

CREATE TABLE `Specializes` (
  `subgenre` VARCHAR (255) NOT NULL,
  `genre`    VARCHAR (255) NOT NULL,
  CONSTRAINT `fk_specializes_subgenre` FOREIGN KEY (`subgenre`) REFERENCES `Genre` (`name`) ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_specializes_genre`    FOREIGN KEY (`genre`)    REFERENCES `Genre` (`name`) ON DELETE RESTRICT ON UPDATE CASCADE,
  PRIMARY KEY (`subgenre`, `genre`),
  INDEX `idx_specializes_genre` (`genre`)
);

CREATE TABLE `Song` (
  `cd_number`    BIGINT  UNSIGNED NOT NULL,
  `track_number` INT     UNSIGNED NOT NULL DEFAULT 1,
  `title`        VARCHAR (255)    NOT NULL,
  `artist`       VARCHAR (255),
  `duration`     TIME,
  `genre`        VARCHAR (255),
  CONSTRAINT `fk_song_cd`    FOREIGN KEY (`cd_number`) REFERENCES `CD`    (`cd_number`) ON DELETE CASCADE  ON UPDATE RESTRICT,
  CONSTRAINT `fk_song_genre` FOREIGN KEY (`genre`)     REFERENCES `Genre` (`name`)      ON DELETE SET NULL ON UPDATE CASCADE,
  PRIMARY KEY (`cd_number`, `track_number`),
  INDEX `idx_song_track_number` (`track_number`),
  INDEX `idx_song_genre`        (`genre`)
);

CREATE TABLE `Contains` (
  `playlist`      VARCHAR (255) NOT NULL,
  `track_number`  INT     UNSIGNED NOT NULL DEFAULT 1,
  `cd_number`     BIGINT  UNSIGNED NOT NULL,
  CONSTRAINT `fk_contains_playlist` FOREIGN KEY (`playlist`)                  REFERENCES `Playlist` (`name`)                      ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_contains_song`     FOREIGN KEY (`cd_number`, `track_number`) REFERENCES `Song`     (`cd_number`, `track_number`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`playlist`, `cd_number`, `track_number`),
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

LOAD DATA INFILE '/docker-entrypoint-initdb.d/data/Theme.csv'
INTO TABLE `Theme`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@name)
SET
`name` = NULLIF (@name, '');

LOAD DATA INFILE '/docker-entrypoint-initdb.d/data/Playlist.csv'
INTO TABLE `Playlist`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@name)
SET
`name` = NULLIF (@name, '');

LOAD DATA INFILE '/docker-entrypoint-initdb.d/data/SuitableFor.csv'
INTO TABLE `SuitableFor`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@theme, @playlist)
SET
`theme`    = NULLIF (@theme,    ''),
`playlist` = NULLIF (@playlist, '');

LOAD DATA INFILE '/docker-entrypoint-initdb.d/data/CD.csv'
INTO TABLE `CD`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@cd_number, @title, @producer, @year, @copies)
SET
`cd_number` = NULLIF (@cd_number, ''),
`title`     = NULLIF (@title,     ''),
`producer`  = NULLIF (@producer,  ''),
`year`      = NULLIF (@year,      ''),
`copies`    = NULLIF (@copies,    '');

LOAD DATA INFILE '/docker-entrypoint-initdb.d/data/Genre.csv'
INTO TABLE `Genre`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@name)
SET
`name` = NULLIF (@name, '');

LOAD DATA INFILE '/docker-entrypoint-initdb.d/data/Specializes.csv'
INTO TABLE `Specializes`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@subgenre, @genre)
SET
`subgenre` = NULLIF (@subgenre, ''),
`genre`    = NULLIF (@genre   , '');

LOAD DATA INFILE '/docker-entrypoint-initdb.d/data/Song.csv'
INTO TABLE `Song`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@cd_number, @track_number, @title, @artist, @duration, @genre)
SET
`cd_number`    = NULLIF (@cd_number,    ''),
`track_number` = NULLIF (@track_number, ''),
`title`        = NULLIF (@title,        ''),
`artist`       = NULLIF (@artist,       ''),
`duration`     = NULLIF (@duration,     ''),
`genre`        = NULLIF (@genre,        '');

LOAD DATA INFILE '/docker-entrypoint-initdb.d/data/Contains.csv'
INTO TABLE `Contains`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@playlist, @track_number, @cd_number)
SET
`playlist`     = NULLIF (@playlist,     ''),
`track_number` = NULLIF (@track_number, ''),
`cd_number`    = NULLIF (@cd_number,    '');

LOAD DATA INFILE '/docker-entrypoint-initdb.d/data/Employee.csv'
INTO TABLE `Employee`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@id, @first_name, @last_name)
SET
`id`         = NULLIF (@id,         ''),
`first_name` = NULLIF (@first_name, ''),
`last_name`  = NULLIF (@last_name,  '');

LOAD DATA INFILE '/docker-entrypoint-initdb.d/data/DJ.csv'
INTO TABLE `DJ`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@id)
SET
`id` = NULLIF (@id, '');

LOAD DATA INFILE '/docker-entrypoint-initdb.d/data/EventPlanner.csv'
INTO TABLE `EventPlanner`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@id)
SET
`id` = NULLIF (@id, '');

LOAD DATA INFILE '/docker-entrypoint-initdb.d/data/Manager.csv'
INTO TABLE `Manager`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@id)
SET
`id` = NULLIF (@id, '');

LOAD DATA INFILE '/docker-entrypoint-initdb.d/data/Specialization.csv'
INTO TABLE `Specialization`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@dj, @genre)
SET
`dj`    = NULLIF (@dj,    ''),
`genre` = NULLIF (@genre, '');

LOAD DATA INFILE '/docker-entrypoint-initdb.d/data/Supervision.csv'
INTO TABLE `Supervision`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@supervisor_id, @employee_id)
SET
`supervisor_id` = NULLIF (@supervisor_id, ''),
`employee_id`   = NULLIF (@employee_id,   '');

LOAD DATA INFILE '/docker-entrypoint-initdb.d/data/Client.csv'
INTO TABLE `Client`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@client_number, @first_name, @last_name, @email_address, @phone_number)
SET
`client_number` = NULLIF (@client_number, ''),
`first_name`    = NULLIF (@first_name,    ''),
`last_name`     = NULLIF (@last_name,     ''),
`email_address` = NULLIF (@email_address, ''),
`phone_number`  = NULLIF (@phone_number,  '');

LOAD DATA INFILE '/docker-entrypoint-initdb.d/data/Location.csv'
INTO TABLE `Location`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\r\n'
IGNORE 1 ROWS
(@id, @street, @city, @postal_code, @country, @comment)
SET
`id`          = NULLIF (@id,          ''),
`street`      = NULLIF (@street,      ''),
`city`        = NULLIF (@city,        ''),
`postal_code` = NULLIF (@postal_code, ''),
`country`     = NULLIF (@country,     ''),
`comment`     = NULLIF(@comment,     '');

LOAD DATA INFILE '/docker-entrypoint-initdb.d/data/Event.csv'
INTO TABLE `Event`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@id, @name, @date, @description, @client, @manager, @event_planner, @dj, @theme, @type, @location, @rental_fee, @playlist)
SET
`id`            = NULLIF (@id,            ''),
`name`          = NULLIF (@name,          ''),
`date`          = NULLIF (@date,          ''),
`description`   = NULLIF (@description,   ''),
`client`        = NULLIF (@client,        ''),
`manager`       = NULLIF (@manager,       ''),
`event_planner` = NULLIF (@event_planner, ''),
`dj`            = NULLIF (@dj,            ''),
`theme`         = NULLIF (@theme,         ''),
`type`          = NULLIF (@type,          ''),
`location`      = NULLIF (@location,      ''),
`rental_fee`    = NULLIF (@rental_fee,    ''),
`playlist`      = NULLIF (@playlist,      '');

LOAD DATA INFILE '/docker-entrypoint-initdb.d/data/Request.csv'
INTO TABLE `Request`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@event_id, @name, @provider, @price)
SET
`event_id` = NULLIF (@event_id, ''),
`name`     = NULLIF (@name,     ''),
`provider` = NULLIF (@provider, ''),
`price`    = NULLIF (@price,    '');

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
