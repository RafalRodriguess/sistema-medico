<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPessoasTable extends Migration
{
    private $schema_table = 'pessoas';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->schema_table, function (Blueprint $table) {
            $table->string('complemento')->nullable();
            $table->string('nome')->nullable()->change();
            $table->string('cpf')->nullable()->change();
            $table->string('telefone2')->nullable()->change();
            $table->string('email')->nullable()->change();
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
            $table->dropColumn('complemento');
            $table->string('nome')->nullable(false)->change();
            $table->string('cpf')->nullable(false)->change();
            $table->string('telefone2')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
        });
    }
}
