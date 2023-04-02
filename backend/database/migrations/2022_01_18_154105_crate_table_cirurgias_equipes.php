<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrateTableCirurgiasEquipes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cirurgias_equipes', function (Blueprint $table) {
            $table->foreignId('equipe_id')->references('id')->on("equipes_cirurgicas");
            $table->foreignId('cirurgia_id')->references('id')->on("cirurgias");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('cirurgias_equipes');
    }
}
