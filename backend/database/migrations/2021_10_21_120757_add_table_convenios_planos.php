<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableConveniosPlanos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('convenios_planos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', '155'); 
            $table->foreignId('convenios_id')->references('id')->on('convenios');
            $table->tinyInteger('ativo')->nullable()->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('convenios_planos', function (Blueprint $table) {
            Schema::dropIfExists('convenios_planos');
        });
    }
}
