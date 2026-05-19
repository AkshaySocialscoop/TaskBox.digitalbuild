<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
   protected $fillable = [
    'client_id',
    'platform',
    'ig_business_id',
    'page_id',
    'access_token'
];
}
