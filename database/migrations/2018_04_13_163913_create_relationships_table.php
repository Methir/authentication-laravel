<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persona_pericia', function (Blueprint $table) {
            $table->unsignedInteger('persona_id');
            $table->unsignedInteger('pericia_id');
            $table->unsignedInteger('graduacao');
            $table->timestamps();

            $table->foreign('persona_id')->references('id')->on('personas');
            $table->foreign('pericia_id')->references('id')->on('pericias');
        });

        Schema::create('persona_feito', function (Blueprint $table) {
            $table->unsignedInteger('persona_id');
            $table->unsignedInteger('feito_id');
            $table->unsignedInteger('graduacao');
            $table->timestamps();

            $table->foreign('persona_id')->references('id')->on('personas');
            $table->foreign('feito_id')->references('id')->on('feitos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persona_pericia');
        Schema::dropIfExists('persona_feito');
    }
}