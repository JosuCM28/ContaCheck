<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Receipt;

class Category extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relación con el modelo `Receipt`.
     * Una categoría puede tener muchos recibos.
     *
     * @return HasMany
     */
    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }
}