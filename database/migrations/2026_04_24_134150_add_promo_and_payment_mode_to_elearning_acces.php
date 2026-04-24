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
        Schema::table('elearning_acces', function (Blueprint $table) {
            // Ajouter les colonnes sans "after" (plus simple)
            $table->string('payment_mode')->nullable();
            $table->string('promo_code_used')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elearning_acces', function (Blueprint $table) {
            $table->dropColumn(['payment_mode', 'promo_code_used']);
        });
    }
};
