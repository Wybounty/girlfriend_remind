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
        Schema::create('pixel_avatars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('girlfriend_id')->constrained()->onDelete('cascade');
            $table->string('original_photo')->nullable(); // Photo originale
            $table->string('segmented_photo')->nullable(); // Photo avec fond retiré
            $table->string('pixel_art_photo')->nullable(); // Version pixel art
            $table->json('animation_frames')->nullable(); // Frames d'animation
            $table->integer('pixel_size')->nullable(); // Taille des pixels
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pixel_avatars');
    }
};
