<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CentroCirurgico;
use App\SalaCirurgica;

use App\Http\Requests\SalaCirurgica\{
    CreateSalaCirurgica,
    EditSalaCirurgica
};
use App\Instituicao;
use Illuminate\Support\Facades\DB;

class SalasCirurgicas extends Controller
{
    /**
     * Lista todas as salas cirúrgicas de um determinado centro cirúrgico
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, CentroCirurgico $centro_cirurgico)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_salas_cirurgicas');

        return view('instituicao.centros.salas-cirurgicas.lista', [
            'centro_cirurgico' => $centro_cirurgico,
        ]);
    }

    /**
     * Mostra o formulário para cadastro e registro de salas cirúrgicas 
     * para um determinado centro dirúrgico
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, CentroCirurgico $centro_cirurgico)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_salas_cirurgicas');

        return view('instituicao.centros.salas-cirurgicas.criar', [
            'centro_cirurgico' => $centro_cirurgico,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSalaCirurgica $request, CentroCirurgico $centro_cirurgico)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_salas_cirurgicas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $centro_cirurgico->instituicao_id, 403);

        $usuario_logado = $request->user('instituicao');

        $dados = $request->validated();

        DB::transaction(function() use ($dados, $usuario_logado, $centro_cirurgico) {

            foreach($dados['salas_cirurgicas'] as $sala_cirurgica_dados){
                $sala_cirurgica = $centro_cirurgico->salasCirurgicas()->create($sala_cirurgica_dados);
                $sala_cirurgica->criarLogCadastro($usuario_logado);
            }

        });

        return redirect()->route('instituicao.centros.cirurgicos.salas.index', [$centro_cirurgico])
        ->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Salas Cirúrgicas registradas com sucesso!'
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
    public function edit(Request $request, 
        CentroCirurgico $centro_cirurgico, SalaCirurgica $sala_cirurgica)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_salas_cirurgicas');

        return view('instituicao.centros.salas-cirurgicas.editar', [
            'centro_cirurgico' => $centro_cirurgico,
            'sala_cirurgica' => $sala_cirurgica,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditSalaCirurgica $request, 
        CentroCirurgico $centro_cirurgico, SalaCirurgica $sala_cirurgica)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_salas_cirurgicas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $centro_cirurgico->instituicao_id || 
            $centro_cirurgico->id === $sala_cirurgica->centro_cirurgico_id, 403);

        $usuario_logado = $request->user('instituicao');

        $dados = $request->validated();

        $sala_cirurgica = DB::transaction(function() use ($dados, $usuario_logado, $instituicao, $sala_cirurgica) {
            $sala_cirurgica->update($dados);
            $sala_cirurgica->criarLogEdicao($usuario_logado, $instituicao->id);
            return $sala_cirurgica;
        });

        return redirect()->route('instituicao.centros.cirurgicos.salas.edit', [$centro_cirurgico, $sala_cirurgica])
        ->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Sala Cirúrgica editada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, 
        CentroCirurgico $centro_cirurgico, SalaCirurgica $sala_cirurgica)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_salas_cirurgicas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $centro_cirurgico->instituicao_id || 
            $centro_cirurgico->id === $sala_cirurgica->centro_cirurgico_id, 403);

        $usuario_logado = $request->user('instituicao');

        DB::transaction(function() use ($usuario_logado, $instituicao, $sala_cirurgica) {
            $sala_cirurgica->delete();
            $sala_cirurgica->criarLogExclusao($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.centros.cirurgicos.salas.index', [$centro_cirurgico])
        ->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Sala Cirúrgica excluída com sucesso!'
        ]);
    }
}
