<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlantActivity extends Model
{
    protected $fillable = [
        'name', 'description', 'time', 'completed', 'frequency', 'plant_id'
    ];
}
