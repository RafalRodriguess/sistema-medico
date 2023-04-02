<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTableAgendamentosIncFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->foreignId('internacao_id')->nullable()->references('id')->on('internacoes');
            $table->foreignId('profissional_id')->nullable()->references('id')->on('prestadores');

            Schema::table('agendamentos', function (Blueprint $table) {
                DB::statement("ALTER TABLE agendamentos MODIFY COLUMN tipo ENUM('agendamento', 'pre_agendamento', 'internacao')");
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            //
        });
    }
}
