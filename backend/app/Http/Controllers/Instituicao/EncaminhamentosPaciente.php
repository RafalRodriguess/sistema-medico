<?php

namespace App\Http\Controllers\Instituicao;

use App\Agendamentos;
use App\EncaminhamentoPaciente;
use App\Http\Controllers\Controller;
use App\Http\Requests\EncaminhamentoPaciente\CriarEncaminhamentoPacienteRequest;
use App\Instituicao;
use App\InstituicoesPrestadores;
use App\ModeloEncaminhamento;
use App\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\GetModeloImpressao;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class EncaminhamentosPaciente extends Controller
{
    public function EncaminhamentoPaciente(Request $request, Agendamentos $agendamento, Pessoa $paciente)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_encaminhamento');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        $encaminhamentos = $paciente->encaminhamentos($user->id)->orderBy('created_at', 'DESC')->get();

        $encaminhamento = null;
        $modeloEncaminhamento = [];

        $prestador = $user->prestador()->with('modeloEncaminhamento')->get();
        if(!empty($prestador)){
            foreach ($prestador as $key => $value) {
                foreach ($value->modeloEncaminhamento as $key => $modelo) {
                    $modeloEncaminhamento[] = [
                        'id' => $modelo->id,
                        'descricao' => $modelo->descricao,
                    ];
                }
            }
        }

        return view('instituicao.prontuarios.encaminhamentos.info', \compact('encaminhamentos', 'encaminhamento', 'modeloEncaminhamento'));
    }
    
    public function EncaminhamentoPacienteHistorico(Request $request, Agendamentos $agendamento, Pessoa $paciente)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        $encaminhamentos = $paciente->encaminhamentos($user->id)->orderBy('created_at', 'DESC')->get();

        return view('instituicao.prontuarios.encaminhamentos.historico', \compact('encaminhamentos'));
    }

    public function encaminhamentoSalvar(CriarEncaminhamentoPacienteRequest $request, Agendamentos $agendamento, Pessoa $paciente)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');

        $encaminhamento = DB::transaction(function() use($instituicao, $user, $agendamento, $paciente, $request){
            $data = $request->validated();
            
            $dados = [
                'agendamento_id' => $agendamento->id,
                'usuario_id' => $user->id,
                'encaminhamento' => [
                    'obs' => $request->obs_encaminhamento,
                    'impressao' => GetModeloImpressao::getModeloImpressao($user)
                ]
            ];


            if(array_key_exists('encaminhamento_id', $data) && $data['encaminhamento_id'] != null){
                $encaminhamento = EncaminhamentoPaciente::find($data['encaminhamento_id']);
                $encaminhamento->update(['encaminhamento' => $dados['encaminhamento']]);
                $encaminhamento->criarLogEdicao($user, $instituicao->id);
            }else{
                
                $encaminhamento = $paciente->encaminhamento()->create($dados);
    
                $encaminhamento->criarLogCadastro($user, $instituicao->id);
            }


            return $encaminhamento;
        });

        // $encaminhamento = $paciente->encaminhamento($user->id)->orderBy('created_at', 'DESC')->get();

        return response()->json($encaminhamento);

    }

    public function pacienteGetEncaminhamento(Request $request, Agendamentos $agendamento, Pessoa $paciente, EncaminhamentoPaciente $encaminhamento)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($encaminhamento->paciente_id === $paciente->id, 403);

        return response()->json($encaminhamento);
    }
    
    public function pacienteExcluirEncaminhamento(Request $request, Agendamentos $agendamento, Pessoa $paciente, EncaminhamentoPaciente $encaminhamento)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($encaminhamento->paciente_id === $paciente->id, 403);

        $user = $request->user('instituicao');

        DB::transaction(function() use($instituicao, $user, $agendamento, $paciente, $encaminhamento){
            $encaminhamento->delete();
            $encaminhamento->criarLogExclusao($user,$instituicao->id);
        });

        $encaminhamentos = $paciente->encaminhamentos($user->id)->orderBy('created_at', 'DESC')->get();

        $encaminhamento = null;
        
        return response()->json(true);
    }

    public function compartilharEncaminhamento(Request $request, Agendamentos $agendamento, Pessoa $paciente, EncaminhamentoPaciente $encaminhamento)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($encaminhamento->paciente_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        
        DB::transaction(function() use($instituicao, $user, $encaminhamento){
            $encaminhamento->update(['compartilhado' => ($encaminhamento->compartilhado == 1) ? 0 : 1]);
            $encaminhamento->criarLogEdicao($user,$instituicao->id);
        });

        $encaminhamentos = $paciente->encaminhamentos($user->id)->orderBy('created_at', 'DESC')->get();

        return view('instituicao.prontuarios.encaminhamentos.historico', \compact('encaminhamentos'));
    }

    public function imprimirEncaminhamento(Request $request, EncaminhamentoPaciente $encaminhamento)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $agendamento = $encaminhamento->agendamento()->first();

        abort_unless($encaminhamento->paciente->instituicao_id === $instituicao->id, 403);

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
            $pdf->loadHTML(view('instituicao.prontuarios.encaminhamentos.imprimir_encaminhamento', \compact('encaminhamento', 'agendamento', 'modelo', 'exibir_data', 'exibir_titulo_paciente')));
            return $pdf->stream();
            // return view('instituicao.prontuarios.prontuario.imprimir_prontuario', \compact('prontuario', 'agendamento', 'modelo'));
        }else{
            $user = $request->user('instituicao'); 

            abort_unless($user->id === $encaminhamento->usuario_id, 403);
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML(view('instituicao.prontuarios.encaminhamentos.imprimir_encaminhamento', \compact('encaminhamento', 'agendamento', 'modelo', 'exibir_data', 'exibir_titulo_paciente')));
            return $pdf->stream();
            // $pdf = PDF::loadView(view('instituicao.prontuarios.prontuario.imprimir_prontuario', \compact('prontuario', 'agendamento', 'modelo')));
            // return view('instituicao.prontuarios.prontuario.imprimir_prontuario', \compact('prontuario', 'agendamento', 'modelo'));
        }

        return abort('403');
    }

    public function modeloEncaminhamento(Request $request, ModeloEncaminhamento $modelo)
    {
        $user = $request->user('instituicao');
        $prestadorInst = InstituicoesPrestadores::find($modelo->instituicao_prestador_id);

        abort_unless($user->id === $prestadorInst->instituicao_usuario_id, 403);

        return response()->json($modelo);
    }
}
