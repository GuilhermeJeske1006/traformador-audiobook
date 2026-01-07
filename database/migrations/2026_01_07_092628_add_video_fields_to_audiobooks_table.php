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
        Schema::table('audiobooks', function (Blueprint $table) {
            $table->string('video_path')->nullable()->after('audio_path');
            $table->enum('video_status', ['pending', 'processing', 'completed', 'failed'])->nullable()->after('video_path');
            $table->integer('video_progress')->default(0)->after('video_status');
            $table->text('video_error_message')->nullable()->after('video_progress');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audiobooks', function (Blueprint $table) {
            $table->dropColumn(['video_path', 'video_status', 'video_progress', 'video_error_message']);
        });
    }
};
