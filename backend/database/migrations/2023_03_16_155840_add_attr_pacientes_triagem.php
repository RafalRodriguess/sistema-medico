<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttrPacientesTriagem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('senhas_triagem', function(Blueprint $table) {
            $table->string('paciente_nome')->nullable();
            $table->string('paciente_mae')->nullable();
            $table->string('paciente_cpf')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('senhas_triagem', function(Blueprint $table) {
            $table->dropColumn('paciente_nome');
            $table->dropColumn('paciente_mae');
            $table->dropColumn('paciente_cpf');
        });
    }
}
