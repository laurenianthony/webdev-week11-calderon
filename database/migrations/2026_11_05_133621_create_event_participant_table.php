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
    Schema::create('event_participant', function (Blueprint $table) {
        $table->id();
        // Rule: If event or participant is deleted, registrations disappear (onDelete cascade)
        $table->foreignId('event_id')->constrained()->onDelete('cascade');
        $table->foreignId('participant_id')->constrained()->onDelete('cascade');
        $table->enum('status', ['registered', 'waitlisted'])->default('registered');
        $table->timestamps();

        // Rule: The same participant cannot register for the same event twice
        $table->unique(['event_id', 'participant_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_participant');
    }
};
