<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Garden extends Model
{
    protected $fillable = [
        'user_id', 'name', 'width', 'length', 'grid', 'picture'
    ];
}
