<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompartilharTableProntuariosPaciente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prontuarios_paciente', function (Blueprint $table) {
            $table->tinyInteger('compartilhado')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prontuarios_paciente', function (Blueprint $table) {
            $table->dropColumn('compartilhado'); 
        });
    }
}
