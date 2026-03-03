<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('fund_category_id')->constrained()->onDelete('restrict');
            $table->decimal('amount', 12, 2);
            $table->string('donation_method'); // qris, bank_transfer, cash, etc
            $table->string('transaction_id')->unique()->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_regular')->default(false); // For recurring donations
            $table->string('frequency')->nullable(); // monthly, weekly, etc (for recurring)
            $table->timestamp('donated_at');
            $table->timestamp('verified_at')->nullable();
            $table->boolean('receipt_sent')->default(false);
            $table->timestamps();
            
            $table->index('donated_at');
            $table->index('fund_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
