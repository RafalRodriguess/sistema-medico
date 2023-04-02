<?php

namespace App\Http\Controllers\Instituicao;

use App\CentroCirurgico;
use App\CentroCusto;
use App\HorarioFuncionamento;
use App\Http\Controllers\Controller;
use App\Instituicao;
use Illuminate\Http\Request;
use App\Http\Requests\CentroCirurgico\{
    CreateCentroCirurgico,
    EditCentroCirurgico
};
use Illuminate\Support\Facades\DB;

class CentrosCirurgicos extends Controller
{
    /**
     * Mostra uma lista de Centros Cirúrgicos 
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_centros_cirurgicos');

        return view('instituicao.centros.cirurgicos.lista');
    }

    /**
     * Mostra o formulário de Cadastro de um novo Centro Cirúrgico
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_centros_cirurgicos');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        return view('instituicao.centros.cirurgicos.criar', [
            'centros_custos' => $instituicao->centrosCustos()->get(),
        ]);
    }

    /**
     * Cadastra um Centro Cirúrgico, seu Horário de Funcionamento e suas Salas Cirúrgicas
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCentroCirurgico $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_centros_cirurgicos');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $dados = $request->validated();

        $usuario_logado = $request->user('instituicao');

        DB::transaction(function() use ($dados, $instituicao, $usuario_logado) {

            $centro_cirurgico = $instituicao->centrosCirurgicos()->create($dados);
            $centro_cirurgico->criarLogCadastro($usuario_logado, $instituicao->id);

            $horario_funcionamento = $centro_cirurgico->horarioFuncionamento()->create($dados);
            $horario_funcionamento->criarLogCadastro($usuario_logado, $instituicao->id);

            if(isset($dados['salas_cirurgicas'])){
                foreach($dados['salas_cirurgicas'] as $sala_cirurgica_dados){
                    $sala_cirurgica = $centro_cirurgico->salasCirurgicas()->create($sala_cirurgica_dados);
                    $sala_cirurgica->criarLogCadastro($usuario_logado, $instituicao->id);
                }
            }

        });

        return redirect()->route('instituicao.centros.cirurgicos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Centro Cirúrgico registrado com sucesso!'
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
    public function edit(Request $request, CentroCirurgico $centro_cirurgico)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_centros_cirurgicos');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        return view('instituicao.centros.cirurgicos.editar', [
            'centros_custos' => $instituicao->centrosCustos()->get(),
            'centro_cirurgico' => $centro_cirurgico
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditCentroCirurgico $request, CentroCirurgico $centro_cirurgico)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_centros_cirurgicos');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $centro_cirurgico->instituicao_id, 403);

        $usuario_logado = $request->user('instituicao');

        $dados = $request->validated();

        $centro_cirurgico = DB::transaction(function() use (
            $dados, $centro_cirurgico, $instituicao, $usuario_logado) {

            $centro_cirurgico->update($dados);
            
            unset($dados['descricao']);
            unset($dados['cc_id']);

            $centro_cirurgico->horarioFuncionamento()->update($dados);

            $centro_cirurgico->criarLogEdicao($usuario_logado, $instituicao->id);

            return $centro_cirurgico;
        });

        return redirect()->route('instituicao.centros.cirurgicos.edit', [$centro_cirurgico])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Centro Cirúrgico editado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, CentroCirurgico $centro_cirurgico)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_centros_cirurgicos');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $centro_cirurgico->instituicao_id, 403);

        $usuario_logado = $request->user('instituicao');

        DB::transaction(function() use ($centro_cirurgico, $instituicao, $usuario_logado) {
            $centro_cirurgico->horarioFuncionamento()->delete();
            $centro_cirurgico->delete();
            $centro_cirurgico->criarLogExclusao($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.centros.cirurgicos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Centro Cirúrgico excluído com sucesso!'
        ]);
    }
}
