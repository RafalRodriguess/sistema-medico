<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColunsNullableTableMedicamentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicao_medicamentos', function (Blueprint $table) {
            $table->string('forma_farmaceutica')->nullable()->default()->change();
            $table->string('concentracao')->nullable()->default()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instituicao_medicamentos', function (Blueprint $table) {
            //
        });
    }
}
