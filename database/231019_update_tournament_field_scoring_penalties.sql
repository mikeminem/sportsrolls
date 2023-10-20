ALTER TABLE `db_underscore`.`tournaments` 
ADD COLUMN `enable_penalties_scoring` ENUM('Si', 'No') NOT NULL DEFAULT 'No' AFTER `loser_points_penalties`,
CHANGE COLUMN `winner_points_penalties` `winner_points_penalties` INT NOT NULL DEFAULT '-1' ,
CHANGE COLUMN `loser_points_penalties` `loser_points_penalties` INT NOT NULL DEFAULT '-1' ;
