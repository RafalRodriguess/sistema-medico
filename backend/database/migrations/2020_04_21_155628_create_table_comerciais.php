<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableComerciais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comerciais', function (Blueprint $table) {
            $table->id();
            $table->string('nome_fantasia','255');
            $table->string('cnpj','20');
            $table->string('razao_social','255');
            $table->string('email','255');
            $table->string('telefone','255');
            $table->string('rua', '255');
            $table->string('numero', '25');
            $table->string('bairro', '255');
            $table->string('cidade', '255');
            $table->string('estado', '255');
            $table->string('cep', '10');
            $table->boolean('exibir')->nullable()->default(true);
            $table->tinyInteger('realiza_entrega')->default('0');
            $table->string('pagamento_cartao', '255')->nullable();
            $table->text('logo')->nullable();
            $table->boolean('retirada_loja')->nullable()->default(false);
            $table->string('complemento', '255')->nullable();
            $table->string('referencia', '255')->nullable();
            $table->foreignId('banco_id')->nullable()->references('id')->on('contas_bancarias');
            $table->string('id_recebedor','100')->nullable();

            $table->tinyInteger('max_parcela')->default(1);
            $table->tinyInteger('free_parcela')->default(1);
            $table->float('valor_parcela', 4, 2)->default(2.00);
            $table->float('taxa_tectotum', 4, 2)->default(3.00);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comerciais');
    }
}
