<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usocfdi extends Model
{
    protected $fillable = [
        'title',
        'code',
    ];

    public function receipt()
    {
        return $this->hasMany(Receipt::class);
    }
}
