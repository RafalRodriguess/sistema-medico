<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUnidadesLeitoTable extends Migration
{
    private $schema_table = 'unidades_leitos';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->schema_table, function (Blueprint $table) {
            $table->boolean('leito_virtual')->default(false);
            $table->json('caracteristicas')->nullable()->change();
            $table->integer('quantidade')->default(1)->change();
            $table->string('sala')->nullable()->change();
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
            $table->dropColumn('leito_virtual');
            $table->json('caracteristicas')->nullable(false)->change();
            $table->string('sala')->nullable(false)->change();
        });
    }
}
