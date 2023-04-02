<?php

use App\VinculoBrasindiceTipo;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableVinculoBrasindiceTipos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vinculo_brasindice_tipos', function (Blueprint $table) {
            $table->id();
            $table->string('descricao', '50');
            $table->timestamps();
            $table->softDeletes();
        });

        VinculoBrasindiceTipo::create([
            'descricao' => 'Medicamentos'
        ]);

        VinculoBrasindiceTipo::create([
            'descricao' => 'Materiais'
        ]);

        VinculoBrasindiceTipo::create([
            'descricao' => 'Soluções'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vinculo_brasindice_tipos');
    }
}
