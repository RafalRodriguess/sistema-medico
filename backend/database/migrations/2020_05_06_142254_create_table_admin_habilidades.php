<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAdminHabilidades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_habilidades', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('nome_unico','255');
            $table->string('nome','255');
            $table->string('descricao','255')->nullable()->default(null);
            $table->boolean('obrigatorio_grupo')->default(false);
            $table->boolean('suporte_perfil')->default(true);
            $table->boolean('sensivel')->default(false);
            $table->foreignId('habilidade_grupo_id')->references('id')->on('admin_habilidades_grupos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_habilidades');
    }
}
