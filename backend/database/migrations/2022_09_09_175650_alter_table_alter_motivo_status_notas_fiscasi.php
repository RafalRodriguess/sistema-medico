<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableAlterMotivoStatusNotasFiscasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notas_fiscais', function (Blueprint $table) {
            $table->longText('motivo_status')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notas_fiscais', function (Blueprint $table) {
            $table->string('motivo_status')->nullable();
        });
    }
}
