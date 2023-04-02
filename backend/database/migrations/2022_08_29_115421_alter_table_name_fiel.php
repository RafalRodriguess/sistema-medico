<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableNameFiel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notas_fiscais', function (Blueprint $table) {
            $table->renameColumn('valor_confis', 'valor_cofins');
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
            $table->renameColumn('valor_cofins', 'valor_confis');
        });
    }
}
