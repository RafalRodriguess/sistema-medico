<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\MotivosPartos\CriarMotivosPartosRequest;
use App\Instituicao;
use App\MotivoParto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MotivosPartos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_motivos_partos');

        return view('instituicao.motivos_partos.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivos_partos');

        return view('instituicao.motivos_partos.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarMotivosPartosRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivos_partos');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $motivoParto = $instituicao->motivoPartos()->create($dados);
            $motivoParto->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.motivosPartos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de parto criado com sucesso!'
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
     * @return \Illuminate\Http\Response,
     */
    public function edit(MotivoParto $motivo_parto)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_motivos_partos');

        return view('instituicao.motivos_partos.editar',\compact("motivo_parto"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarMotivosPartosRequest $request, MotivoParto $motivo_parto)

    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_motivos_partos');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$motivo_parto->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $motivo_parto){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $motivo_parto->update($dados);
            $motivo_parto->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.motivosPartos.edit', [$motivo_parto])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de parto alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, MotivoParto $motivo_parto)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_motivos_partos');
        
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$motivo_parto->instituicao_id, 403);
        DB::transaction(function () use ($motivo_parto, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $motivo_parto->delete();
            $motivo_parto->criarLogExclusao($usuario_logado, $instituicao);
            

            return $motivo_parto;
        });
        
        return redirect()->route('instituicao.motivosPartos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de parto excluído com sucesso!'
        ]);
    }
}
