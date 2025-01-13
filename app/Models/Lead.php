<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, HasUlids, SoftDeletes;


    protected $fillable = [
        'phone',
        'main_insured_birth_date',
        'has_copay',
        'additional_insured',
        'calculated_price',
        'final_price',
        'payment_frequency',
        'status',
        'is_smoker',
        'payment_method',
        'payment_details',
        'payment_completed',
        'document_signed',
        'signed_at',
    ];

    protected $casts = [
        'main_insured_birth_date' => 'date',
        'has_copay' => 'boolean',
        'additional_insured' => 'array',
        'is_smoker' => 'boolean',
        'payment_details' => 'array',
        'payment_completed' => 'boolean',
        'document_signed' => 'boolean',
        'signed_at' => 'datetime',
        'calculated_price' => 'float',
        'final_price' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function policy(): HasOne
    {
        return $this->hasOne(Policy::class);
    }
}
