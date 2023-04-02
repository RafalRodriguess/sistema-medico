<?php

namespace App\Http\Controllers\Instituicao;

use App\AgendaAusente;
use App\Agendamentos;
use App\Http\Controllers\Controller;
use App\Http\Requests\AgendaAusente\CreateAgendaAusenteRequest;
use App\Instituicao;
use App\Prestador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function GuzzleHttp\Psr7\str;

class AgendasAusente extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index(Prestador $prestador)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_agenda_ausente');
        return view('instituicao.agendas_ausente.lista', ['prestador' => $prestador]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Prestador $prestador)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_agenda_ausente');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        return view('instituicao.agendas_ausente.criar', compact('prestador'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAgendaAusenteRequest $request, Prestador $prestador)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_agenda_ausente');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        $dados = $request->validated();

        if(empty($dados['dia_todo'])){
            $dados['dia_todo'] = 0;
        }else{
            $dados['hora_inicio'] = '00:00';
            $dados['hora_fim'] = '23:59';
        }

        if(strtotime($dados['hora_fim']) < strtotime($dados['hora_inicio'])){
            return redirect()->route('instituicao.prestadores.agendaAusente.create', ['prestador' => $prestador] )->with('mensagem', [
                'icon' => 'error',
                'title' => 'Erro.',
                'text' => 'A hora fim deve ser maior que a hora de inicio!'
            ]);
        }

        if(!empty($dados['repetir'])){
            if(!empty($dados['repetir_data']) && strtotime($dados['repetir_data']) < strtotime($dados['data'])){
                return redirect()->back()->withInput()->with('mensagem', [
                    'icon' => 'error',
                    'title' => 'Erro.',
                    'text' => 'A data limite para repetir deve ser maior que a data de inicio!'
                ]);
            }
        }

        $id_prest = $instituicao->prestadores()->where('prestadores_id', $prestador->id)->first()->id;
        $data_ini = date("Y-m-d H:i:s", strtotime($dados['data']." ".$dados['hora_inicio']));
        $data_fim = date("Y-m-d H:i:s", strtotime($dados['data']." ".$dados['hora_fim']));

        $agendamento = Agendamentos::where('status', '<>', 'excluir')->where('status', '<>', 'cancelado')->whereNotNull('instituicoes_agenda_id')
            ->whereHas('instituicoesAgenda', function($q) use ($dados, $id_prest){
                $q->whereHas('prestadores',function($q) use ($dados, $id_prest){
                    $q->where('instituicoes_prestadores_id', $id_prest);
                });
            })
            // ->whereDate("data", '<=', $data_ini)
            // ->whereDate("data", '>=', $data_fim)
            ->whereBetween('data', [$data_ini, $data_fim])
        ->get();

        if(count($agendamento) > 0){
            return redirect()->route('instituicao.prestadores.agendaAusente.create', ['prestador' => $prestador] )->with('mensagem', [
                'icon' => 'error',
                'title' => 'Erro.',
                'text' => 'Existem agendamentos marcados para este profissional no período escolhido, gentileza cancelar ou remarcar antes de prosseguir!'
            ]);
        }else{
        
            DB::transaction(function() use ($request, $instituicao, $dados){
                $data = $dados['data'];
                $data_fim = (!empty($dados['repetir_data'])) ? $dados['repetir_data'] : $dados['data'];
                $usuario_logado = $request->user('instituicao');
                
                while(strtotime($data) <= strtotime($data_fim)){
                    $dados['data'] = $data;

                    $agendaAusente =$instituicao->agendasAusente()->create($dados);
                    $agendaAusente->criarLogCadastro($usuario_logado, $instituicao->id);

                    $data = date("Y-m-d", strtotime($data.' + 1 days'));

                }
            
                
                // $agendaAusente =$instituicao->agendasAusente()->create($dados);
                // $agendaAusente->criarLogCadastro($usuario_logado, $instituicao->id);
            });
        }

        return redirect()->route('instituicao.prestadores.agendaAusente.index', ['prestador' => $prestador] )->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Agenda ausente criado com sucesso!'
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
    public function edit(Request $request, Prestador $prestador, AgendaAusente $agendaAusente)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_agenda_ausente');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($instituicao->id === $agendaAusente->instituicao_id, 403);
        return view('instituicao.agendas_ausente.editar', compact('agendaAusente', 'prestador'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateAgendaAusenteRequest $request, Prestador $prestador, AgendaAusente $agendaAusente)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_agenda_ausente');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($instituicao->id === $agendaAusente->instituicao_id, 403);
        
        $dados = $request->validated();

        if(empty($dados['dia_todo'])){
            $dados['dia_todo'] = 0;
        }else{
            $dados['hora_inicio'] = '00:00:00';
            $dados['hora_fim'] = '23:59:59';
        }

        if(strtotime($dados['hora_fim']) < strtotime($dados['hora_inicio'])){
            return redirect()->route('instituicao.prestadores.agendaAusente.create', ['prestador' => $prestador] )->with('mensagem', [
                'icon' => 'error',
                'title' => 'Erro.',
                'text' => 'A hora fim deve ser maior que a hora de inicio!'
            ]);
        }

        $id_prest = $instituicao->prestadores()->where('prestadores_id', $prestador->id)->first()->id;
        $data_ini = date("Y-m-d H:i:s", strtotime($dados['data']." ".$dados['hora_inicio']));
        $data_fim = date("Y-m-d H:i:s", strtotime($dados['data']." ".$dados['hora_fim']));

        $agendamento = Agendamentos::where('status', '<>', 'excluir')->whereNotNull('instituicoes_agenda_id')
            ->whereHas('instituicoesAgenda', function($q) use ($dados, $id_prest){
                $q->whereHas('prestadores',function($q) use ($dados, $id_prest){
                    $q->where('instituicoes_prestadores_id', $id_prest);
                });
            })
            // ->whereDate("data", '<=', $data_ini)
            // ->whereDate("data", '>=', $data_fim)
            ->whereBetween('data', [$data_ini, $data_fim])
        ->get();

        if(count($agendamento) > 0){
            return redirect()->route('instituicao.prestadores.agendaAusente.edit', ['prestador' => $prestador, 'agenda_ausente' => $agendaAusente] )->with('mensagem', [
                'icon' => 'error',
                'title' => 'Erro.',
                'text' => 'Existem agendamentos marcados para este profissional no período escolhido, gentileza cancelar ou remarcar antes de prosseguir!'
            ]);

        }else{
        
            DB::transaction(function() use ($dados, $instituicao, $agendaAusente, $request){
                $usuario_logado = $request->user('instituicao');            
                
                $agendaAusente->update($dados);
                $agendaAusente->criarLogEdicao($usuario_logado, $instituicao->id);

            });
        }

        return redirect()->route('instituicao.prestadores.agendaAusente.edit', [$prestador, $agendaAusente])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Agenda ausente editada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Prestador $prestador, AgendaAusente $agendaAusente)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_agenda_ausente');
        $instituicao_id = $request->session()->get('instituicao');
        abort_unless($instituicao_id === $agendaAusente->instituicao_id, 403);

        DB::transaction(function () use ($agendaAusente, $request, $instituicao_id) {
            $usuario = $request->user('instituicao');
            $agendaAusente->delete();
            $agendaAusente->criarLogExclusao($usuario, $instituicao_id);
        });

        return redirect()->route('instituicao.prestadores.agendaAusente.index', [$prestador])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Agenda ausente excluída com sucesso!'
        ]);
    }
}
