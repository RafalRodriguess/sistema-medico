<?php

namespace App\Http\Controllers\Instituicao;

use App\Agendamentos;
use App\AtestadoPaciente;
use App\Http\Controllers\Controller;
use App\Http\Requests\AtestadoPaciente\CriarAtestadoPacienteRequest;
use App\Instituicao;
use App\InstituicoesPrestadores;
use App\ModeloAtestado;
use App\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Support\GetModeloImpressao;

class AtestadosPaciente extends Controller
{
    public function atestadoPaciente(Request $request, Agendamentos $agendamento, Pessoa $paciente)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_atestado');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        $atestados = $paciente->atestados($user->id)->orderBy('created_at', 'DESC')->get();

        $atestado = null;
        $modeloAtestado = [];

        $prestador = $user->prestador()->with(['modeloAtestado' => function($q){
            $q->orderBy('descricao', 'ASC');
        }])->get();
        if(!empty($prestador)){
            foreach ($prestador as $key => $value) {
                foreach ($value->modeloAtestado as $key => $modelo) {
                    $modeloAtestado[] = [
                        'id' => $modelo->id,
                        'descricao' => $modelo->descricao,
                    ];
                }
            }
        }

        return view('instituicao.prontuarios.atestados.info', \compact('atestados', 'atestado', 'modeloAtestado'));
    }
    
    public function atestadoPacienteHistorico(Request $request, Agendamentos $agendamento, Pessoa $paciente)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        $atestados = $paciente->atestados($user->id)->orderBy('created_at', 'DESC')->get();

        return view('instituicao.prontuarios.atestados.historico', \compact('atestados'));
    }

    public function atestadoSalvar(CriarAtestadoPacienteRequest $request, Agendamentos $agendamento, Pessoa $paciente)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');

        $atestado = DB::transaction(function() use($instituicao, $user, $agendamento, $paciente, $request){
            $data = $request->validated();
            
            $dados = [
                'agendamento_id' => $agendamento->id,
                'usuario_id' => $user->id,
                'compartilhado' => (array_key_exists('compartilhado', $data)) ? 1 : 0,
                'atestado' => [
                    'obs' => $request->obs_atestado,
                    'impressao' => GetModeloImpressao::getModeloImpressao($user)
                ]
            ];


            if(array_key_exists('atestado_id', $data) && $data['atestado_id'] != null){
                $atestado = AtestadoPaciente::find($data['atestado_id']);
                $atestado->update(['atestado' => $dados['atestado'], 'compartilhado' => (array_key_exists('compartilhado', $data)) ? 1 : 0]);
                $atestado->criarLogEdicao($user, $instituicao->id);
            }else{
                
                $atestado = $paciente->atestado()->create($dados);
    
                $atestado->criarLogCadastro($user, $instituicao->id);
            }


            return $atestado;
        });

        // $atestado = $paciente->atestado($user->id)->orderBy('created_at', 'DESC')->get();

        return response()->json($atestado);

    }

    public function pacienteGetAtestado(Request $request, Agendamentos $agendamento, Pessoa $paciente, AtestadoPaciente $atestado)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($atestado->paciente_id === $paciente->id, 403);

        return response()->json($atestado);
    }
    
    public function pacienteExcluirAtestado(Request $request, Agendamentos $agendamento, Pessoa $paciente, AtestadoPaciente $atestado)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($atestado->paciente_id === $paciente->id, 403);

        $user = $request->user('instituicao');

        DB::transaction(function() use($instituicao, $user, $agendamento, $paciente, $atestado){
            $atestado->delete();
            $atestado->criarLogExclusao($user,$instituicao->id);
        });

        $atestados = $paciente->atestados($user->id)->orderBy('created_at', 'DESC')->get();

        $atestado = null;
        
        return response()->json(true);
    }

    public function compartilharAtestado(Request $request, Agendamentos $agendamento, Pessoa $paciente, AtestadoPaciente $atestado)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($atestado->paciente_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        
        DB::transaction(function() use($instituicao, $user, $atestado){
            $atestado->update(['compartilhado' => ($atestado->compartilhado == 1) ? 0 : 1]);
            $atestado->criarLogEdicao($user,$instituicao->id);
        });

        $atestados = $paciente->atestados($user->id)->orderBy('created_at', 'DESC')->get();

        return view('instituicao.prontuarios.atestados.historico', \compact('atestados'));
    }

    public function imprimirAtestado(Request $request, AtestadoPaciente $atestado)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $agendamento = $atestado->agendamento()->first();

        abort_unless($atestado->paciente->instituicao_id === $instituicao->id, 403);

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
            $pdf->loadHTML(view('instituicao.prontuarios.atestados.imprimir_atestado', \compact('atestado', 'agendamento', 'modelo', 'exibir_data', 'exibir_titulo_paciente')));
            return $pdf->stream();
            // return view('instituicao.prontuarios.prontuario.imprimir_prontuario', \compact('prontuario', 'agendamento', 'modelo'));
        }else{
            $user = $request->user('instituicao'); 

            abort_unless($user->id === $atestado->usuario_id, 403);
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML(view('instituicao.prontuarios.atestados.imprimir_atestado', \compact('atestado', 'agendamento', 'modelo', 'exibir_data', 'exibir_titulo_paciente')));
            return $pdf->stream();
            // $pdf = PDF::loadView(view('instituicao.prontuarios.prontuario.imprimir_prontuario', \compact('prontuario', 'agendamento', 'modelo')));
            // return view('instituicao.prontuarios.prontuario.imprimir_prontuario', \compact('prontuario', 'agendamento', 'modelo'));
        }

        return abort('403');
    }

    public function modeloAtestado(Request $request, ModeloAtestado $modelo)
    {
        $user = $request->user('instituicao');
        $prestadorInst = InstituicoesPrestadores::find($modelo->instituicao_prestador_id);

        abort_unless($user->id === $prestadorInst->instituicao_usuario_id, 403);

        return response()->json($modelo);
    }
}
