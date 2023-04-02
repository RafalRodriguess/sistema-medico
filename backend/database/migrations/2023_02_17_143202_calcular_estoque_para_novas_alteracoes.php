<?php

use App\EstoqueEntradaProdutos;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CalcularEstoqueParaNovasAlteracoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $saidas = DB::table('estoque_baixa_produtos')
            ->selectRaw('
                lote,
                SUM(quantidade) as quantidade
            ')
            ->groupBy('lote');

        $entradas = DB::table('estoque_entradas_produtos')
            ->selectRaw('
                id,
                lote,
                SUM(quantidade) as quantidade
            ')
            ->groupBy([
                'lote',
                'id'
            ]);

        DB::table(DB::raw("({$entradas->toSql()}) as entrada"))
        ->leftJoin(DB::raw("({$saidas->toSql()}) as saida"), 'saida.lote', 'entrada.lote')
        ->selectRaw('
            entrada.id,
            entrada.lote,
            GREATEST((entrada.quantidade - IFNULL(saida.quantidade, 0)), 0) AS quantidade
        ')
        ->get()
        ->map(function($item) {
            EstoqueEntradaProdutos::where('id', $item->id)->update([
                'quantidade_estoque' => $item->quantidade
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
