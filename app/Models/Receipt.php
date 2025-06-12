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
}