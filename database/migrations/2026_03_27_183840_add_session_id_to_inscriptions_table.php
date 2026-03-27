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
        Schema::table('inscriptions', function (Blueprint $table) {
            // Ajoute la colonne session_id après formation_id
            $table->foreignId('session_id')
                  ->nullable()                    // Peut être null pour les inscriptions sans session
                  ->after('formation_id')          // Place la colonne après formation_id
                  ->constrained('formation_sessions')  // Crée une clé étrangère vers formation_sessions
                  ->onDelete('set null');          // Si une session est supprimée, session_id devient null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inscriptions', function (Blueprint $table) {
            // Supprime d'abord la clé étrangère
            $table->dropForeign(['session_id']);
            // Puis supprime la colonne
            $table->dropColumn('session_id');
        });
    }
};
