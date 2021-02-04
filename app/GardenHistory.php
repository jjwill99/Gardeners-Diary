<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GardenHistory extends Model
{
    protected $fillable = [
        'garden_id', 'grid'
    ];
}
