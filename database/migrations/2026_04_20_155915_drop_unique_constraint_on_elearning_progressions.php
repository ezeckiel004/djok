<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('elearning_progressions', function (Blueprint $table) {
            // Supprimer la contrainte unique sur acces_id + cours_id
            $table->dropUnique('elearning_progressions_acces_id_cours_id_unique');

            // Ajouter une contrainte unique sur acces_id + qcm_id (si nécessaire)
            $table->unique(['acces_id', 'qcm_id'], 'elearning_progressions_acces_id_qcm_id_unique');
        });
    }

    public function down()
    {
        Schema::table('elearning_progressions', function (Blueprint $table) {
            $table->dropUnique('elearning_progressions_acces_id_qcm_id_unique');
            $table->unique(['acces_id', 'cours_id'], 'elearning_progressions_acces_id_cours_id_unique');
        });
    }
};
