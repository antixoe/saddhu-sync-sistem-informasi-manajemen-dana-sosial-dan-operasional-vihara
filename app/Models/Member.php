<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'member_id',
        'phone',
        'birth_date',
        'address',
        'city',
        'province',
        'postal_code',
        'notes',
        'qr_code_token',
        'is_active',
        'join_date',
        'latitude',
        'longitude',
        'profile_image',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'join_date' => 'date',
        'is_active' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function merits()
    {
        return $this->hasMany(MeritHistory::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function getFullNameAttribute()
    {
        return $this->user->name ?? 'Unknown';
    }

    public function getTotalDonationsAttribute()
    {
        return $this->donations()->where('is_anonymous', false)->sum('amount');
    }

    public function getLastDonationAttribute()
    {
        return $this->donations()->latest('donated_at')->first();
    }
}
