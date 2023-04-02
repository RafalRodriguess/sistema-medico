<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InsertIntroTableRegiaoProcedimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $dados[] = [
            'descricao' => 'Oclusal',
            'tipo_limpeza' => 0,
        ];
        $dados[] = [
            'descricao' => 'mesial',
            'tipo_limpeza' => 0,
        ];
        $dados[] = [
            'descricao' => 'distal',
            'tipo_limpeza' => 0,
        ];
        $dados[] = [
            'descricao' => 'lingual',
            'tipo_limpeza' => 0,
        ];
        $dados[] = [
            'descricao' => 'palatina',
            'tipo_limpeza' => 0,
        ];
        $dados[] = [
            'descricao' => 'vestibular',
            'tipo_limpeza' => 0,
        ];
        $dados[] = [
            'descricao' => 'hase',
            'tipo_limpeza' => 1,
        ];
        $dados[] = [
            'descricao' => 'haid',
            'tipo_limpeza' => 1,
        ];
        $dados[] = [
            'descricao' => 'haie',
            'tipo_limpeza' => 1,
        ];
        $dados[] = [
            'descricao' => 'arcada superior',
            'tipo_limpeza' => 1,
        ];
        $dados[] = [
            'descricao' => 'arcada inferior',
            'tipo_limpeza' => 1,
        ];
        $dados[] = [
            'descricao' => 'asai',
            'tipo_limpeza' => 1,
        ];
        
        DB::table('regiao_procedimentos')->insert($dados);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
