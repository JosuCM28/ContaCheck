<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Counter extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'regime_id',
        'document_id',
        'name',
        'last_name',
        'full_name',
        'phone',
        'address',
        'rfc',
        'curp',
        'city',
        'state',
        'cp',
        'birthdate',
        'nss'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventorie::class);
    }
    public function credentials()
    {
        return $this->hasMany(Credential::class);
    }

    public function regime()
    {
        return $this->belongsTo(Regime::class);
    }
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    protected static function booted()
    {
        static::deleting(function ($counter) {
            if ($counter->user) {
                $counter->user->delete();
            }
        });
    }

}
