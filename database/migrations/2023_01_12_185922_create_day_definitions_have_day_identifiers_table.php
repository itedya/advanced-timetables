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
        Schema::create('day_definitions_have_day_identifiers', function (Blueprint $table) {
            $table->foreignId('day_definition_id')
                ->references('id')
                ->on('day_definitions')
                ->onDelete('CASCADE');

            $table->char('day_identifier', 7);
            $table->foreign('day_identifier')
                ->references('identifier')
                ->on('day_identifiers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('day_definitions_have_day_identifiers');
    }
};
