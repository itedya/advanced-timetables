<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();

            $table->foreignId('day_id')
                ->references('id')
                ->on('day_definitions')
                ->onDelete('CASCADE');

            $table->foreignId('teacher_id')
                ->references('id')
                ->on('teachers')
                ->onDelete('CASCADE');

            $table->foreignId('subject_id')
                ->references('id')
                ->on('subjects')
                ->onDelete('CASCADE');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lessons');
    }
};
