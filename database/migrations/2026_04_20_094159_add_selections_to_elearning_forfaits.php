<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSelectionsToElearningForfaits extends Migration
{
    public function up()
    {
        Schema::table('elearning_forfaits', function (Blueprint $table) {
            // Colonnes pour stocker les sélections (JSON)
            $table->json('selected_cours_ids')->nullable()->after('features');
            $table->json('selected_qcms_ids')->nullable()->after('selected_cours_ids');
            $table->json('selected_examens_ids')->nullable()->after('selected_qcms_ids');

            // Flag pour "tout inclure" (optionnel, pour garder l'ancien comportement)
            $table->boolean('include_all_cours')->default(false)->after('is_active');
            $table->boolean('include_all_qcms')->default(false)->after('include_all_cours');
            $table->boolean('include_all_examens')->default(false)->after('include_all_qcms');
        });
    }

    public function down()
    {
        Schema::table('elearning_forfaits', function (Blueprint $table) {
            $table->dropColumn([
                'selected_cours_ids',
                'selected_qcms_ids',
                'selected_examens_ids',
                'include_all_cours',
                'include_all_qcms',
                'include_all_examens'
            ]);
        });
    }
}
