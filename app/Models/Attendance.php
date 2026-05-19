<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
    'user_id',
    'shift_id',
    'date',
    'check_in',
    'check_out',
    'working_hours',
    'overtime_hours',
    'status',
    'lat',
    'lng'
];
public function user()
{
    return $this->belongsTo(User::class);
}
}
