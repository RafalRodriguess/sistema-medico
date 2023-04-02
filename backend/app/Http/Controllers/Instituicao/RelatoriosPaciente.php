<?php

namespace App\Http\Controllers\Instituicao;

use App\Agendamentos;
use App\Http\Controllers\Controller;
use App\Http\Requests\RelatorioPaciente\CriarRelatorioPacienteRequest;
use App\Instituicao;
use App\InstituicoesPrestadores;
use App\ModeloRelatorio;
use App\Pessoa;
use App\RelatorioPaciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Support\GetModeloImpressao;

class RelatoriosPaciente extends Controller
{
    public function relatorioPaciente(Request $request, Agendamentos $agendamento, Pessoa $paciente)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        $relatorios = $paciente->relatorios($user->id)->orderBy('created_at', 'DESC')->get();

        $relatorio = null;

        $modeloRelatorio = [];

        $prestador = $user->prestador()->with(['modeloRelatorio' => function($q){
            $q->orderBy('descricao', 'ASC');
        }])->get();
        if(!empty($prestador)){
            foreach ($prestador as $key => $value) {
                foreach ($value->modeloRelatorio as $key => $modelo) {
                    $modeloRelatorio[] = [
                        'id' => $modelo->id,
                        'descricao' => $modelo->descricao,
                    ];
                }
            }
        }

        return view('instituicao.prontuarios.relatorios.info', \compact('relatorios', 'relatorio', 'modeloRelatorio'));
    }
    
    public function relatorioPacienteHistorico(Request $request, Agendamentos $agendamento, Pessoa $paciente)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        $relatorios = $paciente->relatorios($user->id)->orderBy('created_at', 'DESC')->get();

        return view('instituicao.prontuarios.relatorios.historico', \compact('relatorios'));
    }

    public function relatorioSalvar(CriarRelatorioPacienteRequest $request, Agendamentos $agendamento, Pessoa $paciente)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');

        $relatorio = DB::transaction(function() use($instituicao, $user, $agendamento, $paciente, $request){
            $data = $request->validated();
            $dados = [
                'agendamento_id' => $agendamento->id,
                'usuario_id' => $user->id,
                'compartilhado' => (array_key_exists('compartilhado', $data)) ? 1 : 0,
                'relatorio' => [
                    'obs' => $request->obs_relatorio,
                    'impressao' => GetModeloImpressao::getModeloImpressao($user)
                ]
            ];

            if(array_key_exists('relatorio_id', $data) && $data['relatorio_id'] != null){
                $relatorio = RelatorioPaciente::find($data['relatorio_id']);
                $relatorio->update(['relatorio' => $dados['relatorio'], 'compartilhado' => (array_key_exists('compartilhado', $data)) ? 1 : 0]);
                $relatorio->criarLogEdicao($user, $instituicao->id);
            }else{
                
                $relatorio = $paciente->relatorio()->create($dados);
    
                $relatorio->criarLogCadastro($user, $instituicao->id);
            }


            return $relatorio;
        });

        // $relatorio = $paciente->relatorio($user->id)->orderBy('created_at', 'DESC')->get();

        return response()->json($relatorio);

    }

    public function pacienteGetRelatorio(Request $request, Agendamentos $agendamento, Pessoa $paciente, RelatorioPaciente $relatorio)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($relatorio->paciente_id === $paciente->id, 403);

        return response()->json($relatorio);
    }
    
    public function pacienteExcluirRelatorio(Request $request, Agendamentos $agendamento, Pessoa $paciente, RelatorioPaciente $relatorio)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($relatorio->paciente_id === $paciente->id, 403);

        $user = $request->user('instituicao');

        DB::transaction(function() use($instituicao, $user, $agendamento, $paciente, $relatorio){
            $relatorio->delete();
            $relatorio->criarLogExclusao($user,$instituicao->id);
        });

        $relatorios = $paciente->relatorios($user->id)->orderBy('created_at', 'DESC')->get();

        $relatorio = null;
        
        return response()->json(true);
    }

    public function compartilharRelatorio(Request $request, Agendamentos $agendamento, Pessoa $paciente, RelatorioPaciente $relatorio)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($relatorio->paciente_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        
        DB::transaction(function() use($instituicao, $user, $relatorio){
            $relatorio->update(['compartilhado' => ($relatorio->compartilhado == 1) ? 0 : 1]);
            $relatorio->criarLogEdicao($user,$instituicao->id);
        });

        $relatorios = $paciente->relatorios($user->id)->orderBy('created_at', 'DESC')->get();

        return view('instituicao.prontuarios.relatorios.historico', \compact('relatorios'));
    }

    public function imprimirRelatorio(Request $request, RelatorioPaciente $relatorio)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $agendamento = $relatorio->agendamento()->first();

        abort_unless($relatorio->paciente->instituicao_id === $instituicao->id, 403);

        $modelo = null;

        $user = $request->user('instituicao');

        $exibir_data = true;
        $exibir_titulo_paciente = true;

        if($agendamento->instituicao_agenda_id != null){
            $modelo = $agendamento->instituicoesAgenda->prestadores->modeloImpressao()->first();
            $exibir_data = $agendamento->instituicoesAgenda->prestadores->exibir_data;
            $exibir_titulo_paciente = $agendamento->instituicoesAgenda->prestadores->exibir_titulo_paciente;
            
        }else if($prestador = $user->prestadorMedico()->first()){
            $modelo = $prestador->modeloImpressao()->first();
            $exibir_data = $prestador->exibir_data;
            $exibir_titulo_paciente = $prestador->exibir_titulo_paciente;
        }

        if (Gate::allows('habilidade_instituicao_sessao', 'visualizar_prontuario_compartilhado')) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML(view('instituicao.prontuarios.relatorios.imprimir_relatorio', \compact('relatorio', 'agendamento', 'modelo', 'exibir_data', 'exibir_titulo_paciente')));
            return $pdf->stream();
            // return view('instituicao.prontuarios.prontuario.imprimir_relatorio', \compact('prontuario', 'agendamento', 'modelo'));
        }else{
            $user = $request->user('instituicao'); 

            abort_unless($user->id === $relatorio->usuario_id, 403);
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML(view('instituicao.prontuarios.relatorios.imprimir_relatorio', \compact('relatorio', 'agendamento', 'modelo', 'exibir_data', 'exibir_titulo_paciente')));
            return $pdf->stream();
            // $pdf = PDF::loadView(view('instituicao.prontuarios.prontuario.imprimir_relatorio', \compact('prontuario', 'agendamento', 'modelo')));
            // return view('instituicao.prontuarios.prontuario.imprimir_relatorio', \compact('prontuario', 'agendamento', 'modelo'));
        }

        return abort('403');
    }

    public function modeloRelatorio(Request $request, ModeloRelatorio $modelo)
    {
        $user = $request->user('instituicao');
        $prestadorInst = InstituicoesPrestadores::find($modelo->instituicao_prestador_id);

        abort_unless($user->id === $prestadorInst->instituicao_usuario_id, 403);

        return response()->json($modelo);
    }
}
