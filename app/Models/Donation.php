<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'fund_category_id',
        'amount',
        'donation_method',
        'transaction_id',
        'notes',
        'is_anonymous',
        'is_regular',
        'frequency',
        'donated_at',
        'verified_at',
        'receipt_sent',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
        'is_regular' => 'boolean',
        'donated_at' => 'datetime',
        'verified_at' => 'datetime',
        'receipt_sent' => 'boolean',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function fundCategory()
    {
        return $this->belongsTo(FundCategory::class);
    }

    public function logActivity($action)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => 'Donation',
            'model_id' => $this->id,
            'description' => "Donation of {$this->amount} to {$this->fundCategory->name}",
        ]);
    }
}
