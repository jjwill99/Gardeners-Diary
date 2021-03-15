<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GardenHistory extends Model
{
    protected $fillable = [
        'name', 'width', 'length', 'grid', 'date', 'garden_id'
    ];
}
