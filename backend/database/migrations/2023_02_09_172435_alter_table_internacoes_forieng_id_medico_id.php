<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableInternacoesForiengIdMedicoId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('internacoes', function (Blueprint $table) {
            $table->dropForeign('internacoes_medico_id_foreign');
            $table->foreignId('medico_id')->nullable()->change()->references('id')->on('prestadores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('internacoes', function (Blueprint $table) {
            //
        });
    }
}
