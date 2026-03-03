<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ritual extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'start_time',
        'end_time',
        'location',
        'capacity',
        'is_recurring',
        'recurrence_pattern',
        'recurrence_end',
        'requires_registration',
        'special_notes',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'recurrence_end' => 'datetime',
        'is_recurring' => 'boolean',
        'requires_registration' => 'boolean',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function getAttendanceCountAttribute()
    {
        return $this->attendances()->count();
    }

    public function isUpcoming()
    {
        return $this->start_time > now();
    }

    public function isPast()
    {
        return $this->start_time < now();
    }
}
