<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elearning_progressions', function (Blueprint $table) {
            $table->json('qcm_answers')->nullable()->after('qcm_completed_at');
            $table->json('qcm_details')->nullable()->after('qcm_answers');
        });
    }

    public function down(): void
    {
        Schema::table('elearning_progressions', function (Blueprint $table) {
            $table->dropColumn(['qcm_answers', 'qcm_details']);
        });
    }
};
