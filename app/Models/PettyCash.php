<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCash extends Model
{
    use HasFactory;

    protected $table = 'petty_cash';

    protected $fillable = [
        'user_id',
        'category',
        'amount',
        'description',
        'transaction_date',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logActivity()
    {
        ActivityLog::create([
            'user_id' => $this->user_id,
            'action' => 'created',
            'model_type' => 'PettyCash',
            'model_id' => $this->id,
            'description' => "Petty cash: {$this->category} - {$this->amount}",
        ]);
    }
}
