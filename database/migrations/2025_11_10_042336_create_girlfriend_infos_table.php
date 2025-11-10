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
        Schema::create('girlfriend_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('girlfriend_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->text('reponses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('girlfriend_infos');
    }
};
