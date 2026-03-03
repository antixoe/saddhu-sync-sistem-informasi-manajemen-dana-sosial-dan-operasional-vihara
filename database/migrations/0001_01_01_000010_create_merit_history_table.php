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
        Schema::create('merit_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('activity_type'); // donation, ritual_participation, volunteer, etc
            $table->text('description');
            $table->dateTime('activity_date');
            $table->decimal('amount')->nullable(); // For donations
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('member_id');
            $table->index('activity_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merit_history');
    }
};
