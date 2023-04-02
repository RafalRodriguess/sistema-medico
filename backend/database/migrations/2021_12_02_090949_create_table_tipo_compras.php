<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateTableTipoCompras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_compras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instituicao_id')->references('id')->on("instituicoes");
            $table->string("descricao");
            $table->boolean("urgente");  
            $table->timestamps();            
            $table->softDeletes();
        });
    }

   
    public function down()
    {
        Schema::dropIfExists('tipo_compras');
    }
}
