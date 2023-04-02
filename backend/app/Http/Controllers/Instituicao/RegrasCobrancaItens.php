<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegraCobranca\CriarRegraCobrancaItemRequest;
use App\Http\Requests\RegraCobranca\EditarRegraCobrancaItemRequest;
use App\Instituicao;
use App\RegraCobranca;
use App\RegraCobrancaItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegrasCobrancaItens extends Controller
{
    public function index(Request $request, RegraCobranca $regra)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_regras_cobranca_itens');
        $instituicao = $request->session()->get('instituicao');
        abort_unless($instituicao === $regra->instituicao_id, 403);
        return view('instituicao.regra_cobranca.item.lista', \compact('regra'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, RegraCobranca $regra)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_regras_cobranca_itens');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($instituicao->id === $regra->instituicao_id, 403);
        $bases = RegraCobrancaItem::base();
        $grupos = $instituicao->grupoProcedimentos()->get();
        $faturamentos = $instituicao->faturamentos()->get();
        return view('instituicao.regra_cobranca.item.criar', \compact('regra', 'bases', 'grupos', 'faturamentos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarRegraCobrancaItemRequest $request, RegraCobranca $regra)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_regras_cobranca_itens');
        $instituicao = $request->session()->get('instituicao');
        abort_unless($instituicao === $regra->instituicao_id, 403);
         
        DB::transaction(function() use ($request, $instituicao, $regra){
            $usuario_logado = $request->user('instituicao');
            $dados = collect($request->validated()['itens'])
            ->filter(function ($item) {
                return !is_null($item['grupo_procedimento_id']);
            })
            ->map(function ($item) use($request){
                return [
                    'grupo_procedimento_id' => $item['grupo_procedimento_id'],
                    'faturamento_id' => $item['faturamento_id'],
                    'pago' => $item['pago'],
                    'base' => $item['base'],
                ];
            });
            
            foreach ($dados as $key => $value) {
                $regra->itens()->create($value);
            }

            $regra->criarLog($usuario_logado, 'Adicionando itens a regra de cobrança', $dados, $instituicao);
        });

        return redirect()->route('instituicao.regrasCobrancaItens.index', [$regra])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Regra de cobrança item criada com sucesso!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, RegraCobranca $regra, RegraCobrancaItem $item)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_regras_cobranca_itens');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($instituicao->id === $regra->instituicao_id, 403);
        abort_unless($regra->id === $item->regra_cobranca_id, 403);
        $bases = RegraCobrancaItem::base();
        $grupos = $instituicao->grupoProcedimentos()->get();
        $faturamentos = $instituicao->faturamentos()->get();

        return view('instituicao.regra_cobranca.item.editar', \compact('regra', 'item', 'bases', 'grupos', 'faturamentos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditarRegraCobrancaItemRequest $request, RegraCobranca $regra, RegraCobrancaItem $item)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_regras_cobranca_itens');

        $instituicaoId = $request->session()->get('instituicao');
        abort_unless($instituicaoId === $regra->instituicao_id, 403);
        abort_unless($regra->id === $item->regra_cobranca_id, 403);

        DB::transaction(function() use ($request, $instituicaoId, $item){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $item->update($dados);
            $item->criarLogEdicao($usuario_logado, $instituicaoId);
        });

        return redirect()->route('instituicao.regrasCobrancaItens.edit', [$regra, $item])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Regra de cobrança item alterada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, RegraCobranca $regra, RegraCobrancaItem $item)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_tipos_documentos');
        
        $instituicaoId = $request->session()->get('instituicao');
        abort_unless($instituicaoId === $regra->instituicao_id, 403);
        abort_unless($regra->id === $item->regra_cobranca_id, 403);

        DB::transaction(function () use ($item, $request, $instituicaoId) {
            $usuario_logado = $request->user('instituicao');
            $item->delete();
            $item->criarLogExclusao($usuario_logado, $instituicaoId);
            return $item;
        });
            
        return redirect()->route('instituicao.regrasCobrancaItens.index', [$regra])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Regra de cobrança item excluída com sucesso!'
        ]);
    
    }
}
