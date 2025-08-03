<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regime extends Model
{
    protected $fillable = [
        'title',
    ];

    public function counters()
    {
        return $this->hasMany(Counter::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
    public function company_data(){
        return $this->hasMany(CompanyData::class);
    
    }
}
