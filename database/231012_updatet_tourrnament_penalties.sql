ALTER TABLE `db_underscore`.`tournaments` 
ADD COLUMN `winner_points_penalties` INT NULL DEFAULT -1 AFTER `draw_points`,
ADD COLUMN `loser_points_penalties` INT NULL DEFAULT -1 AFTER `winner_points_penalties`,
CHANGE COLUMN `draw_points` `draw_points` INT NOT NULL DEFAULT -1 ;