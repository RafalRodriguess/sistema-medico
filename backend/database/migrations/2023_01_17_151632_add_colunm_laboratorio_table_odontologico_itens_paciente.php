<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmLaboratorioTableOdontologicoItensPaciente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('odontologico_itens_paciente', function (Blueprint $table) {
            $table->decimal('laboratorio', 15,2)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('odontologico_itens_paciente', function (Blueprint $table) {
            $table->dropColumn('laboratorio');
        });
    }
}
