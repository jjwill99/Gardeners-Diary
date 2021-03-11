<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    protected $fillable = [
        'plant_name', 'icon', 'garden_id'
    ];
}
