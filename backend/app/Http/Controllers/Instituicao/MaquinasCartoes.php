<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\MaquinasCartao\AlterMaquinasCartaoRequest;
use App\Http\Requests\MaquinasCartao\CreateMaquinasCartaoRequest;
use App\Instituicao;
use App\MaquinaCartao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaquinasCartoes extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_maquina_cartao');
        return view('instituicao.maquinas_catao/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_maquina_cartao');
        return view('instituicao.maquinas_catao/criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateMaquinasCartaoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_maquina_cartao');
        $dados = $request->validated();

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        DB::transaction(function() use($request, $instituicao, $dados){
            $usuario_logado = $request->user('instituicao');
            $dados['taxa_credito'] = (!empty($dados['taxa_credito'])) ? json_encode($dados['taxa_credito']) : null;
            $maquina_cartao = $instituicao->maquinasCartao()->create($dados);
            $maquina_cartao->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.maquinasCartoes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Maquina de cartão cadastrada com sucesso!'
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
    public function edit(Request $request, MaquinaCartao $maquina)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_maquina_cartao');

        $instituicao = $request->session()->get("instituicao");

       $maquina->taxa_credito = (!empty($maquina->taxa_credito)) ? json_decode($maquina->taxa_credito) : null;

        abort_unless($instituicao===$maquina->instituicao_id, 403);

        return view('instituicao.maquinas_catao/editar', compact('maquina'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AlterMaquinasCartaoRequest $request, MaquinaCartao $maquina)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_maquina_cartao');
        $dados = $request->validated();

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        DB::transaction(function() use($request, $instituicao, $dados, $maquina){
            $usuario_logado = $request->user('instituicao');
            $dados['taxa_credito'] = (!empty($dados['taxa_credito'])) ? json_encode($dados['taxa_credito']) : null;
            $maquina->update($dados);
            $maquina->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.maquinasCartoes.edit', [$maquina])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Maquina de cartão editada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(request $request, MaquinaCartao $maquina)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_maquina_cartao');
        $instituicao = $request->session()->get("instituicao");

        abort_unless($instituicao===$maquina->instituicao_id, 403);

        DB::transaction(function () use ($request, $maquina, $instituicao){

            $maquina->delete();

            $usuario_logado = $request->user('instituicao');
            $maquina->criarLogExclusao($usuario_logado, $instituicao);

            return $maquina;
        });
        
    
        return redirect()->route('instituicao.maquinasCartoes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => "Maquina de cartão excluida com sucesso!"
        ]);
    }
}
