<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'ritual_id',
        'checked_in_at',
        'checked_out_at',
        'notes',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function ritual()
    {
        return $this->belongsTo(Ritual::class);
    }

    public function getDurationInMinutesAttribute()
    {
        if ($this->checked_in_at && $this->checked_out_at) {
            return $this->checked_in_at->diffInMinutes($this->checked_out_at);
        }
        return null;
    }
}
