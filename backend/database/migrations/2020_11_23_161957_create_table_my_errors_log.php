<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMyErrorsLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('my_errors_log', function (Blueprint $table) {
            $table->id();

            $table->longText('message');
            $table->longText('context');
            $table->string('level')->index();
            $table->string('level_name');
            $table->string('channel')->index();
            $table->string('record_datetime');
            $table->longText('extra');
            $table->longText('formatted');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('my_errors_log');
    }
}
