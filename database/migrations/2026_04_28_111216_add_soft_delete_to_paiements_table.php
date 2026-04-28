<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeleteToPaiementsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            // Ajout du soft delete
            $table->softDeletes();

            // Ajout des colonnes pour le tracking des suppressions
            $table->unsignedBigInteger('deleted_by')->nullable()->after('deleted_at');
            $table->text('deleted_reason')->nullable()->after('deleted_by');

            // Ajout d'index pour les performances
            $table->index('deleted_at');
            $table->index('deleted_by');

            // Ajout d'une contrainte de clé étrangère pour deleted_by
            $table->foreign('deleted_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            // Supprimer la clé étrangère
            $table->dropForeign(['deleted_by']);

            // Supprimer les index
            $table->dropIndex(['deleted_at']);
            $table->dropIndex(['deleted_by']);

            // Supprimer les colonnes
            $table->dropColumn(['deleted_at', 'deleted_by', 'deleted_reason']);
        });
    }
}
