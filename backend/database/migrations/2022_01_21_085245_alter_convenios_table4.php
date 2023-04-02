<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterConveniosTable4 extends Migration
{
    private $schema_table = 'convenios';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->schema_table, function (Blueprint $table) {
            $table->unsignedBigInteger('pessoas_id')->comment('Id do fornecedor (Pessoa de tipo = 3)')->nullable();

            $table->foreign('pessoas_id', 'fk_fornecedores_convenios')->references('id')->on('pessoas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->schema_table, function (Blueprint $table) {
            $table->dropForeign('fk_fornecedores_convenios');
            $table->dropColumn('pessoas_id');
        });
    }
}
