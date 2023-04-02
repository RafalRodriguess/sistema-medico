<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFieldsEntradasEstoqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estoque_entradas', function(Blueprint $table) {
            $table->string('numero_documento')->nullable()->change();
            $table->string('serie')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estoque_entradas', function(Blueprint $table) {
            $table->string('numero_documento')->nullable(false)->change();
            $table->string('serie')->nullable(false)->change();
        });
    }
}
