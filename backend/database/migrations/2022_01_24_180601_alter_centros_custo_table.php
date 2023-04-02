<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCentrosCustoTable extends Migration
{
    private $schema_table = 'centros_de_custos';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->schema_table, function (Blueprint $table) {
            $table->string('gestor')->nullable()->change();
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
            $table->string('gestor')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
        });
    }
}
