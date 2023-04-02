<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmVinculoTussTableProcedimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procedimentos', function (Blueprint $table) {
            $table->foreignId('vinculo_tuss_id')->nullable()->default(null)->references('id')->on('vinculo_tuss');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procedimentos', function (Blueprint $table) {
            //
        });
    }
}
