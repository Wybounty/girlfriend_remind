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
        Schema::table('pixel_avatars', function (Blueprint $table) {
            $table->json('characteristics')->nullable()->after('pixel_size');
            $table->string('avatar_type')->default('3d')->after('characteristics'); // '3d' ou 'pixel'
            $table->string('rpm_url')->nullable()->after('avatar_type'); // Ready Player Me URL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pixel_avatars', function (Blueprint $table) {
            $table->dropColumn(['characteristics', 'avatar_type', 'rpm_url']);
        });
    }
};
