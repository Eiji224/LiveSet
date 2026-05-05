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
        Schema::table('trainings_sets', function (Blueprint $table) {
           $table->dropForeign(['set_id']);
           $table->dropColumn(['set_id', 'sets_quantity']);
        });

        Schema::rename('trainings_sets', 'program_exercises');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_exercises', function (Blueprint $table) {
            $table->integer('sets_quantity');
            $table->foreignId('set_id')->constrained('sets')->cascadeOnDelete();
        });

        Schema::rename('program_exercises', 'trainings_sets');
    }
};
