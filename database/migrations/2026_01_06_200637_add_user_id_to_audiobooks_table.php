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
        // Adiciona a coluna user_id como nullable primeiro
        Schema::table('audiobooks', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        });

        // Atribui o primeiro usuário aos audiobooks existentes
        $firstUser = \App\Models\User::first();
        if ($firstUser) {
            \App\Models\Audiobook::whereNull('user_id')->update(['user_id' => $firstUser->id]);
        }

        // Torna a coluna obrigatória
        Schema::table('audiobooks', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audiobooks', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
