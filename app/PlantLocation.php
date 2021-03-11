<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlantLocation extends Model
{
    protected $fillable = [
        'row', 'column', 'icon_location', 'plant_id'
    ];
}
