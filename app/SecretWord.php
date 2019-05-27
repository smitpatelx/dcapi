<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SecretWord extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "secretwords";

    protected $fillable = [
        'secret',
    ];
}
