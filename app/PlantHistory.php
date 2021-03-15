<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlantHistory extends Model
{
    protected $fillable = [
        'plant_name', 'icon', 'history_id'
    ];
}
