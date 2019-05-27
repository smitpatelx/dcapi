<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "attendance";

    protected $fillable = [
        'first_name', 'last_name', 'student_number', 'ipaddress', 'secret',
    ];
}
