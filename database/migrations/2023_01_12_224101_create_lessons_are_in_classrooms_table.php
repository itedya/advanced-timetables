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
        Schema::create('lessons_are_in_classrooms', function (Blueprint $table) {
            $table->foreignId('lesson_id')
                ->references('id')
                ->on('lessons')
                ->onDelete('CASCADE');

            $table->foreignId('classroom_id')
                ->references('id')
                ->on('classrooms')
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
        Schema::dropIfExists('lessons_are_in_classrooms');
    }
};
