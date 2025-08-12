<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Counter;
use App\Models\Document;

class Client extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'counter_id',
        'regime_id',
        'status',
        'phone',
        'name',
        'last_name',
        'full_name',
        'email',
        'address',
        'country',
        'localities',
        'street',
        'col',
        'num_ext',
        'rfc',
        'curp',
        'city',
        'state',
        'cp',
        'nss',
        'note',
        'token',
        'birthdate',
    ];

    protected $qualifiedKeyName = 'clients.id';
    /**
     * Relación con el modelo `User`.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el modelo `Counter`.
     *
     * @return BelongsTo
     */
    public function counter(): BelongsTo
    {
        return $this->belongsTo(Counter::class);
    }
    public function credentials()
    {
        return $this->hasOne(Credential::class, 'client_id');

    }

    public function regime()
    {
        return $this->belongsTo(Regime::class);
    }
    public function document()
    {
        return $this->hasMany(Document::class);
    }

}
