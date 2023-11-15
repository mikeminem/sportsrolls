CREATE TABLE `db_underscore`.`actions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `sport_id` INT NOT NULL,
  `value` VARCHAR(10) NULL,
  `scoring` ENUM('Si', 'No') NOT NULL DEFAULT 'No',
  `label` VARCHAR(10) NULL,
  PRIMARY KEY (`id`));


  ALTER TABLE `db_underscore`.`actions` 
ADD COLUMN `is_deleted` INT NOT NULL DEFAULT 0 AFTER `label`,
CHANGE COLUMN `label` `label` VARCHAR(10) COLLATE 'utf8mb3_spanish_ci' NOT NULL ;

ALTER TABLE `db_underscore`.`actions` 
CHANGE COLUMN `value` `value` VARCHAR(10) NULL DEFAULT 0 ;