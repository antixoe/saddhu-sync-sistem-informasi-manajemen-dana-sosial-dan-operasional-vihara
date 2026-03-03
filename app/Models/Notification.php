<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'type',
        'title',
        'message',
        'delivery_method',
        'is_sent',
        'sent_at',
        'error_message',
    ];

    protected $casts = [
        'is_sent' => 'boolean',
        'sent_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public static function sendDonationReceipt(Donation $donation)
    {
        if ($donation->is_anonymous) {
            return;
        }

        $member = $donation->member;
        if (!$member) {
            return;
        }

        $notification = self::create([
            'member_id' => $member->id,
            'type' => 'donation_receipt',
            'title' => 'Donation Confirmation',
            'message' => "Thank you for your donation of Rp" . number_format((float) $donation->amount) . " to {$donation->fundCategory->name}",
            'delivery_method' => 'both',
        ]);

        // Queue sending via email and WhatsApp
        // dispatch(new SendDonationReceipt($notification));
    }

    public static function sendReminder(Member $member, $type, $title, $message)
    {
        return self::create([
            'member_id' => $member->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'delivery_method' => 'both',
        ]);
    }
}
