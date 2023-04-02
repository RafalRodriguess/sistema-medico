<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmMinimoParcelaComercial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comerciais', function (Blueprint $table) {
            $table->decimal('valor_minimo', 5,2)->after('valor_parcela')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comerciais', function (Blueprint $table) {
            $table->dropColumn('valor_minimo');
        });
    }
}
