<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HotfixChaveChatTbale extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::table('chat_contatos', function(Blueprint $table) {
                $table->dropForeign('fk_contato_ultima_mensagem');
                $table->dropIndex('fk_contato_ultima_mensagem');
            });
        } catch(\Exception $e) {}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
