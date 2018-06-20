<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poder extends Model
{
    protected $table = "poderes";

    protected $guarded = ['id'];

    protected $hidden = ['created_at', 'updated_at'];
}