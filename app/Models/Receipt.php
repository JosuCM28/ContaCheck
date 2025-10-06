<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Counter;
use App\Models\Client;
use App\Models\Category;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'counter_id',
        'client_id',
        'category_id',
        'identificator',
        'pay_method',
        'mount',
        'payment_date',
        'status',
        'concept',
        'usocfdi_id',
        'regime_id',
        'is_timbred',
    ];

    protected $casts = [
        'payment_date' => 'datetime',  // <- clave para el filtro
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_timbred' => 'boolean',
        'mount' => 'decimal:2', // si tu columna es DECIMAL(… ,2)
    ];


    public function counter(): BelongsTo
    {
        return $this->belongsTo(Counter::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function usocfdi(): BelongsTo
    {
        return $this->belongsTo(Usocfdi::class);
    }
    public function regime(): BelongsTo
    {
        return $this->belongsTo(Regime::class);
    }
}