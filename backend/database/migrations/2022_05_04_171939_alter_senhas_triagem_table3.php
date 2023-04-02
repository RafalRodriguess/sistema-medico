<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSenhasTriagemTable3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('senhas_triagem', function(Blueprint $table) {
            $table->boolean('encerrado')->default(false);
            $table->dropColumn('chamado');
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
            $table->dropColumn('encerrado');
            $table->boolean('chamado')->default(false);
        });
    }
}
