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
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('program_exercises');
        Schema::dropIfExists('sets');

        Schema::table('training_programs', function (Blueprint $table) {
            $table->dropColumn('training_time');

            $table->integer('training_time')->default(0);
            $table->integer('exercises_qty')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('program_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained('training_programs')->cascadeOnDelete();
            $table->foreignId('exercise_id')->constrained('exercises')->cascadeOnDelete();
            $table->integer('order');
            $table->timestamps();
        });

        Schema::create('sets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_exercise_id')->constrained('program_exercises')->cascadeOnDelete();
            $table->integer('weight_value');
            $table->time('rest_time');
            $table->timestamps();
        });

        Schema::table('training_programs', function (Blueprint $table) {
            $table->dropColumn('training_time');
            $table->dropColumn('exercises_qty');

            $table->time('training_time');
        });
    }
};
