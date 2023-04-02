<?php

namespace App\Http\Controllers\Admin;

use App\Especialidade;
use App\Especializacao;
use App\EspecializacaoEspecialidade;
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
        $this->authorize('habilidade_admin', 'visualizar_especialidade');

        return view('admin.especialidades/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_admin', 'cadastrar_especialidade');
        $especializacoes = Especializacao::all();
        return view('admin.especialidades/criar', \compact('especializacoes'));
    }

    public function store(CriarEspecialidadeRequest $request)
    {
        $this->authorize('habilidade_admin', 'cadastrar_especialidade');
        DB::transaction(function () use ($request){
            $data = collect($request->validated());
            $especialidade = Especialidade::create($data->except('especializacoes')->toArray());
            $especialidade->criarLogCadastro($request->user('admin'));
            $especialidade->overwrite($especialidade->especializacoesEspecialidade(), $data->get('especializacoes', []));
        });

        return redirect()->route('especialidades.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Especialidade criado com sucesso!'
        ]);
    }

    public function edit(Request $request, Especialidade $especialidade)
    {
        $this->authorize('habilidade_admin', 'editar_especialidade');
        $especializacoes = Especializacao::all();
        return view('admin.especialidades/editar', \compact('especialidade', 'especializacoes'));
    }

    public function update(EditarEspecialidadeRequest $request, Especialidade $especialidade)
    {
        $this->authorize('habilidade_admin', 'editar_especialidade');

        $dados = collect($request->validated());

        DB::transaction(function () use ($request, $especialidade, $dados){
            $especialidade->update($dados->except('especializacoes')->toArray());
            $especialidade->overwrite($especialidade->especializacoesEspecialidade(), $dados->get('especializacoes', []));

            $usuario_logado = $request->user('admin');

            $especialidade->criarLogEdicao(
              $usuario_logado
            );

            return $especialidade;
        });


        return redirect()->route('especialidades.index', [$especialidade])->with('mensagem', [
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
        $this->authorize('habilidade_admin', 'excluir_especialidade');

        if($especialidade->countPrestadoresInstituicoes() > 0){
            return redirect()->route('especialidades.index')->with('mensagem', [
                'icon' => 'error',
                'title' => 'Falha.',
                'text' => 'Especialidade possui prestador vinculado!'
            ]);
        }

        DB::transaction(function () use ($request, $especialidade){
            $especialidade->especializacoesEspecialidade()->delete();
            $especialidade->delete();
            $especialidade->criarLogExclusao($request->user('admin'));
        });

        return redirect()->route('especialidades.index')->with('mensagem', [
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
}
