<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elearning_acces', function (Blueprint $table) {
            $table->float('average_qcm_score')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('elearning_acces', function (Blueprint $table) {
            $table->float('average_qcm_score')->nullable(false)->change();
        });
    }
};
