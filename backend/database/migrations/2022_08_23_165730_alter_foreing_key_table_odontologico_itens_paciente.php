<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterForeingKeyTableOdontologicoItensPaciente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
    
        Schema::table('odontologico_itens_paciente', function (Blueprint $table) {
            $table->unsignedBigInteger('procedimento_instituicao_convenio_id')->nullable()->change();
        });
            
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('odontologico_itens_paciente', function (Blueprint $table) {
            //
        });
    }
}
