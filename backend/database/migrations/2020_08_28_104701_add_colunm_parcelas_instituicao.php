<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmParcelasInstituicao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes', function (Blueprint $table) {
            $table->tinyInteger('max_parcela')->default(1);
            $table->tinyInteger('free_parcela')->default(1);
            $table->float('valor_parcela', 4, 2)->default(2.00);
            $table->float('taxa_tectotum', 4, 2)->default(3.00);
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
        Schema::table('instituicoes', function (Blueprint $table) {
            $table->dropColumn('max_parcela');
            $table->dropColumn('free_parcela');
            $table->dropColumn('valor_parcela', 4, 2);
            $table->dropColumn('taxa_tectotum', 4, 2);
            $table->dropColumn('valor_minimo', 5,2);
        });
    }
}
