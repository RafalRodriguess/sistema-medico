<?php

namespace App\Http\Controllers\Instituicao;

use App\Equipamento;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipamentos\CriarEquipamentoRequest;
use App\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Equipamentos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_equipamentos');
        return view('instituicao.equipamentos.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_equipamentos');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        // $procedimentos = $instituicao->procedimentos()->get();

        return view('instituicao.equipamentos.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarEquipamentoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_equipamentos');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            // dd($dados);

            $equipamento = $instituicao->equipamentos()->create($dados);
            $equipamento->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.equipamentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Equipamento criado com sucesso!'
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
    public function edit(Request $request, Equipamento $equipamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_equipamentos');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        // $procedimentos = $instituicao->procedimentos()->get();

        return view('instituicao.equipamentos.editar',\compact('equipamento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarEquipamentoRequest $request, Equipamento $equipamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_equipamentos');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$equipamento->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $equipamento){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $equipamento->update($dados);
            $equipamento->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.equipamentos.edit', [$equipamento])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Equipamento alterada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Equipamento $equipamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_equipamentos');
        
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$equipamento->instituicao_id, 403);
        DB::transaction(function () use ($equipamento, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $equipamento->delete();
            $equipamento->criarLogExclusao($usuario_logado, $instituicao);
           
            return $equipamento;
        });
        
        return redirect()->route('instituicao.equipamentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Equipamento excluída com sucesso!'
        ]);
    }
}
