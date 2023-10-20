ALTER TABLE `db_underscore`.`games` 
ADD COLUMN `score_penalties_team1` INT NULL DEFAULT 0 AFTER `stadium_id`,
ADD COLUMN `score_penalties_team2` INT NULL DEFAULT 0 AFTER `score_penalties_team1`;