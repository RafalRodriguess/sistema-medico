<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Triagens\CreateTriagem;
use App\{
    ChamadaTotem,
    Especialidade,
    Instituicao,
    FilaTotem,
    Pessoa,
    Prestador,
    SenhaTriagem,
    Totem
};
use App\Http\Requests\SenhasTriagem\CreateSenhaTriagem;

class Triagens extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * 
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_triagens');
        return view('instituicao.triagens/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * 
     */
    public function retirar(Request $request, Totem $totem)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_triagens');
        $filas = $totem->filasTotem()->get();
        return view('instituicao.triagens/retirar', \compact('totem', 'filas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     */
    public function retirarSenha(CreateSenhaTriagem $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_triagens');

        $senha = null;
        DB::transaction(function () use ($request, &$senha) {
            $dados = $request->validated();
            $fila = FilaTotem::findOrFail($dados['filas_totem_id']);
            $usuario = $request->user('instituicao');
            // Impedir edição entre instituições
            $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
            abort_unless($fila->instituicao()->first()->id == $instituicao->id, 403);
            $senha = $fila->senhasTriagem()->create($dados);
            $senha->criarLogCadastro($usuario);
        });

        return view('instituicao.triagens/senha', \compact('senha'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * 
     */
    public function edit(Request $request, SenhaTriagem $triagem)
    {
        $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
        abort_unless($triagem->instituicao()->first()->id == $instituicao->id && !ChamadaTotem::passouPor($triagem, 'triagem') && $this->authorize('habilidade_instituicao_sessao', 'editar_triagens'), 403);
        $classificacoes = $instituicao->classificacoesTriagem()->get();
        ChamadaTotem::chamarSenha($triagem, 'triagem', 'Sala de triagem');
        $paciente_escolhido = Pessoa::find(old('pessoa_id', $triagem->pessoa_id));
        $prestador_escolhido = Prestador::find(old('prestador_id', $triagem->pessoa_id));
        $especialidades_escolhidas = !empty(old('especialidades')) ? Especialidade::whereIn('id', old('especialidades'))->get() : $triagem->especialidades()->get();

        return view('instituicao.triagens/editar', \compact(
            'triagem',
            'classificacoes',
            'pacientes',
            'prestador_escolhido',
            'paciente_escolhido',
            'especialidades_escolhidas'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * 
     */
    public function update(CreateTriagem $request, SenhaTriagem $triagem)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_triagens');

        DB::transaction(function () use ($request, $triagem) {
            $usuario = $request->user('instituicao');
            $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
            // Impedir edição entre instituições
            abort_unless($instituicao->id == $triagem->totem->instituicoes_id && !ChamadaTotem::passouPor($triagem, 'triagem'), '403');
            $dados = collect($request->validated());

            $triagem->update($dados->except('especialidades')->toArray());
            $triagem->overwrite($triagem->especialidadesTriagem(),array_map(function($e) {
                return [
                    'especialidades_id' => $e
                ];
            }, $dados->get('especialidades', [])));

            $triagem->criarLogEdicao($usuario);
            $triagem->concluirEtapa();
        });
        return redirect()->route('instituicao.triagens.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Triagem cadastrada com sucesso!'
        ]);
    }

    public function show(Request $request, SenhaTriagem $triagem) {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_triagens');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($instituicao->id == $triagem->totem->instituicoes_id && ChamadaTotem::passouPor($triagem, 'triagem'), '403');
        return view('instituicao.triagens/visualizar', \compact('triagem'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * 
     */
    public function destroy(Request $request, SenhaTriagem $triagem)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_triagens');

        DB::transaction(function () use ($triagem, $request) {
            $usuario = $request->user('instituicao');
            $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
            // Impedir edição entre instituições
            abort_unless($instituicao->id == $triagem->totem->instituicoes_id, '403');
            $triagem->clearTriagem();
            $triagem->criarLogExclusao($usuario);
        });

        return redirect()->route('instituicao.triagens.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Triagem excluída com sucesso!'
        ]);
    }

    #region MÉTODOS NÃO IMPLEMENTADOS
    /**
     * Show the form for creating a new resource.
     *
     * 
     */
    public function create(Request $request)
    {
        abort('404');
    }
    #endregion


    /**
     * Chama senha para a recepção
     */
    public function chamarPaciente(Request $request)
    {
        // $this->authorize('habilidade_instituicao_sessao', 'chamar_pacientes_triagem');
        try {
            $usuario = $request->user('instituicao');
            $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
            $paciente = SenhaTriagem::findOrFail($request->get('id'));
            $totem = $paciente->totem()->first();

            abort_if(empty($totem) || $totem->instituicoes_id != $instituicao->id, 403);

            ChamadaTotem::chamarSenha($paciente, 'guiche', $request->get('guiche'));
            $paciente->criarLogEdicao($usuario);

            return response()->json([
                'icon' => 'success',
                'title' => 'Sucesso',
                'text' => 'Paciente chamado!'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Erro',
                'text' => 'Houve um erro ao tentar chamar o paciente!'
            ]);
        }
    }
}
