<?php
// database/migrations/2024_01_01_000000_create_formation_sessions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('formation_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Nom de la session (peut être différent de la formation)
            $table->enum('type', ['presentiel', 'e_learning', 'mixte'])->default('presentiel');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location')->nullable(); // Lieu de la formation
            $table->integer('max_places')->default(0);
            $table->integer('available_places')->default(0);
            $table->decimal('price', 10, 2)->nullable(); // Prix spécifique à la session (si différent)
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable(); // Description spécifique à la session
            $table->json('metadata')->nullable(); // Métadonnées supplémentaires
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('formation_sessions');
    }
};
