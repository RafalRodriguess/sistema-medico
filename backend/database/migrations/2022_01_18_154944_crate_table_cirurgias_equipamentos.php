<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrateTableCirurgiasEquipamentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cirurgias_equipamentos', function (Blueprint $table) {
            $table->foreignId('equipamento_id')->references('id')->on('equipamentos');
            $table->foreignId('cirurgia_id')->references('id')->on('cirurgias');
            $table->integer('quantidade')->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cirurgias_equipamentos');
    }
}
