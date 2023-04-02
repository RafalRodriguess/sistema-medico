<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEspecialidadesTable extends Migration
{
    private $schema_table = 'especialidades';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->schema_table, function (Blueprint $table) {
            $table->dropColumn('nome');

            $table->string('descricao', 512)->nullable(false)->change();
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

            $table->string('nome', 255);
            $table->string('descricao', 512)->nullable()->change();
        });
    }
}
