<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeritHistory extends Model
{
    use HasFactory;

    protected $table = 'merit_history';

    protected $fillable = [
        'member_id',
        'activity_type',
        'description',
        'activity_date',
        'amount',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'activity_date' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function getActivityIconAttribute()
    {
        $icons = [
            'donation' => '🙏',
            'ritual_participation' => '🛕',
            'volunteer' => '🤝',
            'special_event' => '✨',
            'class_attendance' => '📚',
        ];
        return $icons[$this->activity_type] ?? '⭐';
    }
}
