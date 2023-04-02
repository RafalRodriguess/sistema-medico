<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProdutos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('imagem','255')->nullable();
            $table->string('nome','255');
            $table->text('descricao_completa');
            $table->string('breve_descricao', '255');
            $table->decimal('preco', 8,2);
            $table->unsignedBigInteger('comercial_id');
            $table->string('nome_farmaceutico', '255')->nullable()->default(null);
            $table->string('tarja','25')->nullable();
            $table->string('tipo_produto','25');
            $table->boolean('generico')->nullable();
            $table->boolean('exibir')->nullable()->default(true);
            $table->boolean('estoque_ilimitado')->nullable()->default(false);
            $table->boolean('promocao')->default(false);
            $table->decimal('preco_promocao', 8,2)->nullable()->default(null);
            $table->date('promocao_inicio')->nullable()->default(null);
            $table->date('promocao_final')->nullable()->default(null);
            $table->integer('quantidade')->nullable()->default(0);
            $table->boolean('permitir_comprar_muitos')->nullable()->default(true);

            $table->unsignedBigInteger('marca_id')->nullable();
            $table->foreign('marca_id')->references('id')->on('marcas');

            $table->unsignedBigInteger('categoria_id');
            $table->foreign('categoria_id')->references('id')->on('categorias');

            $table->unsignedBigInteger('sub_categoria_id')->nullable();
            $table->foreign('sub_categoria_id')->references('id')->on('sub_categorias');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('comercial_id')->references('id')->on('comerciais');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtos');
    }
}
