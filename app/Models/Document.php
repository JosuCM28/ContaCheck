<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client; 
use App\Models\Counter; 

class Document extends Model
{
    protected $fillable = ['title', 'file_path', 'client_id'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}