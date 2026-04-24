<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('elearning_forfaits', function (Blueprint $table) {
            $table->json('promo_codes')->nullable()->after('stripe_price_id');
            // Structure: [{"code": "PROMO123", "max_uses": 10, "used_count": 0, "is_active": true}]
        });
    }

    public function down()
    {
        Schema::table('elearning_forfaits', function (Blueprint $table) {
            $table->dropColumn('promo_codes');
        });
    }
};
