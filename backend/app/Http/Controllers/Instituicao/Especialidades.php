<?php

namespace App\Http\Controllers\Instituicao;

use App\Especialidade;
use App\Http\Controllers\Controller;
use App\Http\Requests\Especialidades\CriarEspecialidadeRequest;
use App\Http\Requests\Especialidades\EditarEspecialidadeRequest;
use App\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Especialidades extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_especialidade');

        return view('instituicao.especialidades/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_especialidade');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
       
        $especializacoes = $instituicao->especializacoes()->get();

        return view('instituicao.especialidades/criar', compact('especializacoes'));
    }

    public function store(CriarEspecialidadeRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_especialidade');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $especialidade = $instituicao->especialidadesInstituicao();

        
        DB::transaction(function () use ($request, $instituicao, $especialidade){

            $especialidade = $especialidade->create($request->validated());

            $usuario_logado = $request->user('instituicao');

            $especialidade->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.especialidades.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Especialidade criado com sucesso!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Especialidade  $especialidade
     * @return \Illuminate\Http\Response
     */
    public function show(Especialidade $especialidade)
    {
        //
    }

    public function edit(Request $request, Especialidade $especialidade)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_especialidade');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($instituicao->id === $especialidade->instituicoes_id, 403);
        $especializacoes = $instituicao->especializacoes()->get();
        return view('instituicao.especialidades/editar', \compact('especialidade', 'especializacoes'));
    }

    public function update(CriarEspecialidadeRequest $request, Especialidade $especialidade)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_especialidade');
        $instituicaoId = $request->session()->get('instituicao');
        abort_unless($instituicaoId === $especialidade->instituicoes_id, 403);

        $dados = $request->validated();

        DB::transaction(function () use ($request, $especialidade, $dados, $instituicaoId){
            $especialidade->update($dados);

            $usuario_logado = $request->user('instituicao');
            $especialidade->criarLogEdicao(
              $usuario_logado,
              $instituicaoId
            );

            return $especialidade;
        });


        //return redirect()->route('administradores.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        return redirect()->route('instituicao.especialidades.edit', [$especialidade])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Especialidade atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Especialidade  $especialidade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Especialidade $especialidade)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_especialidade');

        $instituicaoId = $request->session()->get('instituicao');
        abort_unless($instituicaoId === $especialidade->instituicao_id, 403);

        DB::transaction(function () use ($request, $especialidade, $instituicaoId){
            $especialidade->delete();

            $usuario_logado = $request->user('instituicao');
            $especialidade->criarLogExclusao(
              $usuario_logado,
              $instituicaoId
            );

            return $especialidade;
        });

        return redirect()->route('instituicao.especialidades.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Especialidade excluído com sucesso!'
        ]);
    }

    public function getespecialidades(Request $request){

        $categoria = $request->input('nome');
        $especialidades = Especialidade::where('nome','like','%'.$request->nome.'%')->get();
        return $especialidades->toJson();

    }

    public function buscarEspecialidadeInstituicao(Request $request)
    {

    }
}
