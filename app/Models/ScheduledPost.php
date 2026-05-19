<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledPost extends Model
{ 
    protected $guarded = [];
    public function account()
    {
        return $this->belongsTo(SocialAccount::class, 'account_id');
    }
    
    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }
}
