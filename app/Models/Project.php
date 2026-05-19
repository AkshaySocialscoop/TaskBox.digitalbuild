<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
   protected $fillable = [
        'brand_name',
        'format',
        'link',
        'requirement',
        'comments',
        'status',
        'user_id'
    ];
}
