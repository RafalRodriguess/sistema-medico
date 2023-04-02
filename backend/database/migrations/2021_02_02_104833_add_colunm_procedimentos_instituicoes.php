<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmProcedimentosInstituicoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procedimentos_instituicoes', function (Blueprint $table) {
            $table->string('procedimentos_idexterno','30')->nullable()->default(null)->after('procedimentos_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procedimentos_instituicoes', function (Blueprint $table) {
            $table->dropColumn('procedimentos_idexterno');
        });
    }
}
