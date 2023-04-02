<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CamposPagamentoSaidasEstoqueCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saida_estoque', function(Blueprint $table) {
            $table->unsignedTinyInteger('tipo_destino')->nullable()->comment('Para quem será criada a conta caso exista uma');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saida_estoque', function(Blueprint $table) {
            $table->dropColumn('tipo_destino');
        });
    }
}
