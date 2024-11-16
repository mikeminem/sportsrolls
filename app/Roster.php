<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{
    protected $fillable = [
        'team_id', 
        'user_id', 
        //'player_id', 
        'number', 
        //'positions', 
        'status', 
        'category_id', 
        'is_deleted', 
        'tournament_id',
        'name', 
        'last_name',
        'phone',
        // 'emergency_call',
        // 'emergency_phone',
        'dob',
        'image',
        'access_pin'
    ];

    /*CREATE TABLE `db_underscore`.`rosters` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `team_id` INT UNSIGNED NOT NULL,
  `player_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `number` VARCHAR(45) NULL,
  `positions` VARCHAR(45) NULL,
  `status` ENUM('Active', 'Unactive') NOT NULL,
  `is_deleted` INT NOT NULL DEFAULT 0,
  `tournament_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`));*/

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

