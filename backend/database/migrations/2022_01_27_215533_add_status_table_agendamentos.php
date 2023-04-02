<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddStatusTableAgendamentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `agendamentos` CHANGE `status` `status` ENUM('agendado', 'confirmado', 'cancelado', 'pendente', 'finalizado', 'excluir') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");
        Schema::table('agendamentos', function (Blueprint $table) {
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
