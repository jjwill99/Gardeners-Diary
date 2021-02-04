<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomTile extends Model
{
    protected $fillable = [
        'user_id', 'tile_name', 'icon'
    ];
}
