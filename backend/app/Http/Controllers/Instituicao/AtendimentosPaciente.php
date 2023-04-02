<?php

namespace App\Http\Controllers\Instituicao;

use App\Agendamentos;
use App\AtendimentoPaciente;
use App\Http\Controllers\Controller;
use App\Http\Requests\AtendimentoPaciente\CriarAtendimentoPacienteRequest;
use App\Instituicao;
use App\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AtendimentosPaciente extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Pessoa $pessoa)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_atendimento_paciente');
        $instituicao = $request->session()->get('instituicao');
        abort_unless($instituicao === $pessoa->instituicao_id, 403);
        return view('instituicao.pessoas.atendimentos_paciente.lista', \compact('pessoa'));
    }

    public function lista(Request $request, Pessoa $pessoa)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_atendimento_paciente');
        $instituicao = $request->session()->get('instituicao');
        abort_unless($instituicao === $pessoa->instituicao_id, 403);
        $atendimentoPaciente = $pessoa->atendimentoPaciente()->orderBy('created_at', 'DESC')->limit(15)->get();
        return view('instituicao.pessoas.atendimentos_paciente.lista_agendamento', \compact('pessoa', 'atendimentoPaciente'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Pessoa $pessoa)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_atendimento_paciente');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($instituicao->id === $pessoa->instituicao_id, 403);
        $motivos = $instituicao->motivoAtendimento()->get();

        return view('instituicao.pessoas.atendimentos_paciente.criar', \compact('pessoa', 'motivos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarAtendimentoPacienteRequest $request, Pessoa $pessoa)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_atendimento_paciente');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($instituicao->id === $pessoa->instituicao_id, 403);
        $dados = $request->validated();

        DB::transaction(function() use ($dados, $request, $instituicao, $pessoa){
            $usuario_logado = $request->user('instituicao');
            $dados['usuario_atendeu'] = $usuario_logado->id;
            $dados['paciente_id'] = $pessoa->id;
            $atendimento_paciente = $instituicao->atendimentoPaciente()->create($dados);
            $atendimento_paciente->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.atendimentos_paciente.index', [$pessoa]);
    }
    
    public function storeAgendamento(CriarAtendimentoPacienteRequest $request, Pessoa $pessoa, Agendamentos $agendamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_atendimento_paciente');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($instituicao->id === $pessoa->instituicao_id, 403);

        $dados = $request->validated();

        DB::transaction(function() use ($dados, $request, $instituicao, $pessoa, $agendamento){
            $usuario_logado = $request->user('instituicao');
            $dados['usuario_atendeu'] = $usuario_logado->id;
            $dados['paciente_id'] = $pessoa->id;
            $dados['agendamento_id'] = $agendamento->id;
            $atendimento_paciente = $instituicao->atendimentoPaciente()->create($dados);
            $atendimento_paciente->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.atendimentos_paciente.lista', [$pessoa]);
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
    public function edit(Request $request, Pessoa $pessoa, AtendimentoPaciente $atendimento_paciente)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_atendimento_paciente');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($instituicao->id === $atendimento_paciente->instituicao_id, 403);
        abort_unless($pessoa->id === $atendimento_paciente->paciente_id, 403);

        $motivos = $instituicao->motivoAtendimento()->get();

        return view('instituicao.pessoas.atendimentos_paciente.editar', \compact('atendimento_paciente', 'pessoa', 'motivos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarAtendimentoPacienteRequest $request, Pessoa $pessoa, AtendimentoPaciente $atendimento_paciente)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_atendimento_paciente');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $atendimento_paciente->instituicao_id, 403);
        abort_unless($pessoa->id === $atendimento_paciente->paciente_id, 403);

        $dados = $request->validated();

        $atendimento_paciente = DB::transaction(function() use ($dados, $request, $instituicao, $atendimento_paciente){
            $atendimento_paciente->update($dados);
            $atendimento_paciente->criarLogEdicao($request->user('instituicao'), $instituicao->id);
            return $atendimento_paciente;
        });

        return redirect()->route('instituicao.atendimentos_paciente.index', [$pessoa]);
        // return redirect()->route('instituicao.atendimentos_paciente.edit', [$atendimento_paciente, $pessoa])->with('mensagem', [
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'Atendimento editado com sucesso!'
        // ]);
    }
    
    public function updateAgendamento(CriarAtendimentoPacienteRequest $request, Pessoa $pessoa, AtendimentoPaciente $atendimento_paciente)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_atendimento_paciente');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $atendimento_paciente->instituicao_id, 403);
        abort_unless($pessoa->id === $atendimento_paciente->paciente_id, 403);

        $dados = $request->validated();

        $atendimento_paciente = DB::transaction(function() use ($dados, $request, $instituicao, $atendimento_paciente){
            $atendimento_paciente->update($dados);
            $atendimento_paciente->criarLogEdicao($request->user('instituicao'), $instituicao->id);
            return $atendimento_paciente;
        });

        return redirect()->route('instituicao.atendimentos_paciente.lista', [$pessoa]);
        // return redirect()->route('instituicao.atendimentos_paciente.edit', [$atendimento_paciente, $pessoa])->with('mensagem', [
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'Atendimento editado com sucesso!'
        // ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Pessoa $pessoa, AtendimentoPaciente $atendimento_paciente)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_atendimento_paciente');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $atendimento_paciente->instituicao_id, 403);

        DB::transaction(function() use ($request, $instituicao, $atendimento_paciente){
            $atendimento_paciente->delete();
            $atendimento_paciente->criarLogExclusao($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.atendimentos_paciente.index', [$pessoa])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Atendimento excluído com sucesso!'
        ]);
    }

    public function excluir(Request $request, Pessoa $pessoa, AtendimentoPaciente $atendimento_paciente)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_atendimento_paciente');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $atendimento_paciente->instituicao_id, 403);
        abort_unless($pessoa->id === $atendimento_paciente->paciente_id, 403);

        DB::transaction(function() use ($request, $instituicao, $atendimento_paciente){
            $atendimento_paciente->delete();
            $atendimento_paciente->criarLogExclusao($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.atendimentos_paciente.index', [$pessoa]);
        // return redirect()->route('instituicao.atendimentos_paciente.index', [$pessoa])->with('mensagem', [
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'Atendimento excluído com sucesso!'
        // ]);
    }
    
    public function excluirAgendamento(Request $request, Pessoa $pessoa, AtendimentoPaciente $atendimento_paciente)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_atendimento_paciente');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $atendimento_paciente->instituicao_id, 403);
        abort_unless($pessoa->id === $atendimento_paciente->paciente_id, 403);

        DB::transaction(function() use ($request, $instituicao, $atendimento_paciente){
            $atendimento_paciente->delete();
            $atendimento_paciente->criarLogExclusao($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.atendimentos_paciente.lista', [$pessoa]);
        // return redirect()->route('instituicao.atendimentos_paciente.index', [$pessoa])->with('mensagem', [
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'Atendimento excluído com sucesso!'
        // ]);
    }
}
