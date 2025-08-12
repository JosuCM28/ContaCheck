<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class CompanyData extends Model
{
    use HasFactory;

    protected $table = 'company_data';

    protected $fillable = [
        'regime_id',
        'name',
        'last_name',
        'full_name',
        'curp',
        'cp',
        'rfc',
        'nombre_comercial',
        'phone',
        'phone2',
        'email',
        'street',
        'num_ext',
        'col',
        'state',
        'localities',
        'referer',
        'city',
    ];

    public function regime()
    {
        return $this->belongsTo(Regime::class);
    }
}
