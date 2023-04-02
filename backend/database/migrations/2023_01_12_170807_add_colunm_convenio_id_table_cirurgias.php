<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmConvenioIdTableCirurgias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cirurgias', function (Blueprint $table) {
            $table->foreignId('convenio_id')->nullable()->default(null)->references('id')->on('convenios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cirurgias', function (Blueprint $table) {
            
        });
    }
}
