<?php

namespace App\Http\Controllers\Instituicao;

use App\AgendamentoListaEspera;
use App\Http\Controllers\Controller;
use App\Http\Requests\AgendamentosListaEspera\CriarAgendamentoListaEsperaRequest;
use App\Instituicao;
use App\InstituicoesPrestadores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgendamentosListaEspera extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_agendamentos_lista_espera');
        return view('instituicao.agendamentos_lista_espera.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_agendamentos_lista_espera');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $prestadores = $instituicao->medicos()->get();
        $convenios = $instituicao->convenios()->get();
        $especialidades = $instituicao->especialidadesInstituicao()->get();
        return view('instituicao.agendamentos_lista_espera.criar', \compact('prestadores','convenios', 'especialidades'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarAgendamentoListaEsperaRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_agendamentos_lista_espera');
        $dados = $request->validated();
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        DB::transaction(function() use($dados, $request, $instituicao){
            $usuario_logado = $request->user('instituicao');

            $agendamento = $instituicao->agendamentosListaEspera()->create($dados);
            $agendamento->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.agendamentosListaEspera.index')->with('mensagem', [
            'icon' => 'success',
            'text' => 'Paciente cadastrado com sucesso na lista de espera!',
            'title' => 'Lista de espera'
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
    public function edit(Request $request, AgendamentoListaEspera $agendamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_agendamentos_lista_espera');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($instituicao->id === $agendamento->instituicao_id, 403);

        $prestadores = $instituicao->medicos()->get();
        $convenios = $instituicao->convenios()->get();
        $especialidades = $instituicao->especialidadesInstituicao()->get();
        
        return view('instituicao.agendamentos_lista_espera.editar', \compact('prestadores','convenios', 'especialidades', 'agendamento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarAgendamentoListaEsperaRequest $request, AgendamentoListaEspera $agendamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_agendamentos_lista_espera');
        $instituicao = $request->session()->get('instituicao');
        abort_unless($instituicao === $agendamento->instituicao_id, 403);
        $dados = $request->validated();

        DB::transaction(function() use($dados, $request, $instituicao, $agendamento){
            $usuario_logado = $request->user('instituicao');

            $agendamento->update($dados);
            $agendamento->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.agendamentosListaEspera.edit', [$agendamento])->with('mensagem', [
            'icon' => 'success',
            'text' => 'Paciente editado com sucesso na lista de espera!',
            'title' => 'Lista de espera'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, AgendamentoListaEspera $agendamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_agendamentos_lista_espera');
        $instituicao = $request->session()->get('instituicao');
        abort_unless($instituicao === $agendamento->instituicao_id, 403);

        DB::transaction(function() use($request, $instituicao, $agendamento){
            $usuario_logado = $request->user('instituicao');

            $agendamento->delete();
            $agendamento->criarLogExclusao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.agendamentosListaEspera.index')->with('mensagem', [
            'icon' => 'success',
            'text' => 'Paciente excluido com sucesso na lista de espera!',
            'title' => 'Lista de espera'
        ]);
    }

    public function listaEsperaAgenda(Request $request, InstituicoesPrestadores $prestadorInst)
    {
        $prestador = $prestadorInst->prestador()->first();
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($instituicao->id === $prestadorInst->instituicoes_id, 403);
        $listaEspera = $instituicao->agendamentosListaEspera()->getListaEspera($prestador->id, $prestadorInst->especialidade_id)->paginate(30);
        return view('instituicao.agendamentos_lista_espera.listaModalAgendamento', \compact('listaEspera'));
    }
}
