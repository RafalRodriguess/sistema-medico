<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterConveniosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convenios', function ($table) {
            $table->unsignedBigInteger('apresentacoes_convenio_id')->nullable();
            $table->string('email_glossas')->nullable();
            $table->string('cep')->nullable();
            $table->string('cgc')->nullable();
            $table->string('inscricao_municipal')->nullable();
            $table->string('inscricao_estadual')->nullable();

            $table->foreign('apresentacoes_convenio_id')
                    ->references('id')
                    ->on('apresentacoes_convenio')
                    ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('convenios', function ($table) {
            $table->dropForeign(['apresentacoes_convenio_id']);

            $table->dropColumn('apresentacoes_convenio_id');
            $table->dropColumn('email_glossas');
            $table->dropColumn('cep');
            $table->dropColumn('cgc');
            $table->dropColumn('inscricao_municipal');
            $table->dropColumn('inscricao_estadual');
        });
    }
}
