<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmVistoMensagensPedidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mensagens_pedidos', function (Blueprint $table) {
            $table->boolean('visto')->nullable()->default(false)->after('mensagem');
            $table->timestamp('data_visto')->nullable()->after('mensagem');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mensagens_pedidos', function (Blueprint $table) {
            $table->dropColumn('visto');
            $table->dropColumn('data_visto');
        });
    }
}
