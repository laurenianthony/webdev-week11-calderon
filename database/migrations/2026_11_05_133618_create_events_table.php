<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description'); // Required for full-text search (Step 6)
            $table->jsonb('details');    // Required for JSONB filtering (Step 5)
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->dateTime('start_time'); // Event date and time
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};