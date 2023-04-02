<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnsTableMedicamentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medicamentos', function (Blueprint $table) {
            $table->dropColumn('via_administracao');
            $table->dropColumn('quantidade');
            $table->dropColumn('unidade');
            $table->string('codigo_externo',50)->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medicamentos', function (Blueprint $table) {
            $table->dropColumn('codigo_externo');
            $table->decimal('quantidade', 8,2);
            $table->string('unidade',255);
            $table->string('via_administracao', 255);
        });
    }
}
