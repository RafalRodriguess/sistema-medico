<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunsTableProdutoMedicamentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('produto_medicamentos', function (Blueprint $table) {
            $table->decimal('quantidade',8,2)->nullable()->default(null);
            $table->string('unidade',100)->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('produto_medicamentos', function (Blueprint $table) {
            $table->dropColumn('quantidade');
            $table->dropColumn('unidade');
        });
    }
}
