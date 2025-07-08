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
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('duration')->nullable(); // in seconds
            $table->string('file_path');
            $table->string('waveform_path')->nullable();
            $table->string('cover_image')->nullable();
            $table->integer('bpm')->nullable(); // beats per minute
            $table->string('key')->nullable(); // musical key
            $table->json('genres')->nullable();
            $table->json('moods')->nullable();
            $table->json('instruments')->nullable();
            $table->decimal('price', 8, 2);
            $table->foreignId('artist_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracks');
    }
};