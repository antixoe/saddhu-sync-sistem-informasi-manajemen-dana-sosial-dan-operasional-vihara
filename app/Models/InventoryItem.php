<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'quantity',
        'unit',
        'purchase_price',
        'reorder_level',
        'last_updated_at',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'purchase_price' => 'decimal:2',
        'reorder_level' => 'integer',
        'last_updated_at' => 'datetime',
    ];

    public function isLowStock()
    {
        return $this->reorder_level && $this->quantity <= $this->reorder_level;
    }

    public function getTotalValueAttribute()
    {
        return ($this->purchase_price ?? 0) * $this->quantity;
    }
}
