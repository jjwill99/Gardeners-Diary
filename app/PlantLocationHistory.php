<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlantLocationHistory extends Model
{
    protected $fillable = [
        'row', 'column', 'icon_location', 'plant_id'
    ];
}
