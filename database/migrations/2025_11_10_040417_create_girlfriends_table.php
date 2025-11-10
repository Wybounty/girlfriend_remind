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
        Schema::create('girlfriends', function (Blueprint $table) {
            $table->id();
            $table->string('prenom');
            $table->string('nom');
            $table->string('surnom');
            $table->date('date_anniversaire');
            $table->string('signe_astro');
            $table->text('allergie');
            $table->date('date_rencontre');
            $table->string('nom_doudou');
            $table->string('plat_prefere');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('girlfriends');
    }
};
