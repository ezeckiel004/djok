<?php
// database/migrations/xxxx_xx_xx_xxxxxx_update_demande_formation_internationales_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('demande_formation_internationales', function (Blueprint $table) {
            // Rendre certains champs nullable pour compatibilité
            $table->string('nationalite')->nullable()->change();
            $table->string('whatsapp')->nullable()->change();
            $table->string('duree')->nullable()->change();

            // Ajouter les nouveaux champs (rendus nullable)
            $table->string('nom_entreprise')->nullable()->after('nom_complet');
            $table->string('destination_souhaitee')->nullable()->after('telephone');
            $table->integer('nombre_participants')->nullable()->after('destination_souhaitee');
            $table->json('type_evenement')->nullable()->after('nombre_participants');
            $table->text('objectifs_projet')->nullable()->after('message');
        });

        // Remplir les nouveaux champs avec les données existantes si possible
        DB::table('demande_formation_internationales')->update([
            'nom_entreprise' => DB::raw('nom_complet'), // Par défaut, copie du nom
            'objectifs_projet' => DB::raw('message'),
        ]);
    }

    public function down()
    {
        Schema::table('demande_formation_internationales', function (Blueprint $table) {
            // Supprimer les nouveaux champs
            $table->dropColumn([
                'nom_entreprise',
                'destination_souhaitee',
                'nombre_participants',
                'type_evenement',
                'objectifs_projet'
            ]);
        });
    }
};
