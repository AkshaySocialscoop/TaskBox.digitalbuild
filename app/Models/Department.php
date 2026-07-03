<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToCompany;

class Department extends Model
{
    use BelongsToCompany;
    
    protected $fillable = [
        'name', 
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

}
