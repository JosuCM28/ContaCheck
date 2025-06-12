<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credential extends Model
{
    protected $fillable = [
        'client_id',
        'idse',
        'sipare',
        'siec',
        'useridse',
        'usersipare',
        'auxone',
        'auxtwo',
        'auxthree',
        'iniciofiel',
        'finfiel',
        'iniciosello',
        'finsello',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
