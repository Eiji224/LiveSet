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
        Schema::table('sets', function (Blueprint $table) {
            $table->dropForeign(['exercise_id']);
            $table->dropColumn('exercise_id');

            $table->foreignId('program_exercise_id')->constrained('program_exercises')->cascadeOnDelete();
        });

        Schema::table('program_exercises', function (Blueprint $table) {
            $table->foreignId('exercise_id')->constrained('exercises')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sets', function (Blueprint $table) {
            $table->dropForeign(['program_exercise_id']);
            $table->dropColumn('program_exercise_id');

            $table->foreignId('exercise_id')->constrained('exercises')->cascadeOnDelete();
        });

        Schema::table('program_exercises', function (Blueprint $table) {
            $table->dropForeign(['exercise_id']);
            $table->dropColumn('exercise_id');
        });
    }
};
