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
        Schema::create('groups_have_lessons', function (Blueprint $table) {
            $table->foreignId('group_id')
                ->references('id')
                ->on('groups')
                ->onDelete('CASCADE');

            $table->foreignId('lesson_id')
                ->references('id')
                ->on('lessons')
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
        Schema::dropIfExists('groups_have_lessons');
    }
};
