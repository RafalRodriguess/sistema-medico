<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmTipoTableProcedimentosInstituicoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procedimentos_instituicoes', function (Blueprint $table) {
            $table->enum('tipo', ['avulso', 'ambos', 'unico'])->default('unico');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procedimentos_instituicoes', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }
}
