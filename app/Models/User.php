<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, HasUlids, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hasActiveLead(): bool
    {
        return $this->leads()
            ->where('status', '!=', 'rejected')
            ->exists();
    }

    public function hasActivePolicy(): bool
    {
        return $this->policies()
            ->where('status', 'active')
            ->exists();
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function policies(): HasMany
    {
        return $this->hasMany(Policy::class);
    }

    public function getActiveLeadAttribute()
    {
        return $this->leads()
            ->whereNotIn('status', ['completed', 'rejected'])
            ->latest()
            ->first();
    }

    public function getActivePolicyAttribute()
    {
        return $this->policies()
            ->where('status', 'active')
            ->latest()
            ->first();
    }
}
