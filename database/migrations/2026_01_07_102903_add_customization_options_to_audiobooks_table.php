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
            $table->string('voice_name')->default('pt-BR-Standard-A')->after('timecodes');
            $table->string('video_background_type')->default('gradient')->after('voice_name'); // gradient, solid, image
            $table->string('video_background_color')->default('#1e3a8a')->after('video_background_type');
            $table->string('video_background_image')->nullable()->after('video_background_color');
            $table->string('subtitle_style')->default('default')->after('video_background_image'); // default, bold, outline, box
            $table->integer('subtitle_font_size')->default(24)->after('subtitle_style');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audiobooks', function (Blueprint $table) {
            $table->dropColumn([
                'voice_name',
                'video_background_type',
                'video_background_color',
                'video_background_image',
                'subtitle_style',
                'subtitle_font_size'
            ]);
        });
    }
};
