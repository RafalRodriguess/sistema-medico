<?php

namespace App\Http\Controllers\Instituicao;

use App\Agendamentos;
use App\ConclusaoPaciente;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConclusaoPaciente\CriarConclusaoPacienteRequest;
use App\Instituicao;
use App\InstituicoesPrestadores;
use App\ModeloConclusao;
use App\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\GetModeloImpressao;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class ConclusoesPaciente extends Controller
{
    public function conclusaoPaciente(Request $request, Agendamentos $agendamento, Pessoa $paciente)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_conclusao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        $conclusoes = $paciente->conclusoes($user->id)->orderBy('created_at', 'DESC')->get();

        $conclusao = null;
        $modeloConclusao = [];
        $motivoConclusao = $instituicao->motivosConclusao()->get();

        $prestador = $user->prestador()->with('modeloConclusao')->get();
        if(!empty($prestador)){
            foreach ($prestador as $key => $value) {
                foreach ($value->modeloConclusao as $key => $modelo) {
                    $modeloConclusao[] = [
                        'id' => $modelo->id,
                        'descricao' => $modelo->descricao,
                    ];
                }
            }
        }

        return view('instituicao.prontuarios.conclusoes.info', \compact('conclusoes', 'conclusao', 'modeloConclusao', 'motivoConclusao'));
    }
    
    public function conclusaoPacienteHistorico(Request $request, Agendamentos $agendamento, Pessoa $paciente)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        $conclusoes = $paciente->conclusoes($user->id)->orderBy('created_at', 'DESC')->get();

        return view('instituicao.prontuarios.conclusoes.historico', \compact('conclusoes'));
    }

    public function conclusaoSalvar(CriarConclusaoPacienteRequest $request, Agendamentos $agendamento, Pessoa $paciente)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');

        $conclusao = DB::transaction(function() use($instituicao, $user, $agendamento, $paciente, $request){
            $data = $request->validated();
            
            $dados = [
                'agendamento_id' => $agendamento->id,
                'usuario_id' => $user->id,
                'motivo_conclusao_id' => $request->motivo_conclusao_id,
                'conclusao' => [
                    'obs' => $request->obs_conclusao,
                    'impressao' => GetModeloImpressao::getModeloImpressao($user)
                ]
            ];


            if(array_key_exists('conclusao_id', $data) && $data['conclusao_id'] != null){
                $conclusao = ConclusaoPaciente::find($data['conclusao_id']);
                $conclusao->update(['conclusao' => $dados['conclusao'], 'motivo_conclusao_id' => $dados['motivo_conclusao_id']]);
                $conclusao->criarLogEdicao($user, $instituicao->id);
            }else{
                
                $conclusao = $paciente->conclusao()->create($dados);
    
                $conclusao->criarLogCadastro($user, $instituicao->id);
            }


            return $conclusao;
        });

        // $conclusao = $paciente->conclusao($user->id)->orderBy('created_at', 'DESC')->get();

        return response()->json($conclusao);

    }

    public function pacienteGetConclusao(Request $request, Agendamentos $agendamento, Pessoa $paciente, ConclusaoPaciente $conclusao)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($conclusao->paciente_id === $paciente->id, 403);

        return response()->json($conclusao);
    }
    
    public function pacienteExcluirConclusao(Request $request, Agendamentos $agendamento, Pessoa $paciente, ConclusaoPaciente $conclusao)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($conclusao->paciente_id === $paciente->id, 403);

        $user = $request->user('instituicao');

        DB::transaction(function() use($instituicao, $user, $agendamento, $paciente, $conclusao){
            $conclusao->delete();
            $conclusao->criarLogExclusao($user,$instituicao->id);
        });

        // $conclusoes = $paciente->conclusoes($user->id)->orderBy('created_at', 'DESC')->get();

        $conclusao = null;
        
        return response()->json(true);
    }

    public function compartilharConclusao(Request $request, Agendamentos $agendamento, Pessoa $paciente, ConclusaoPaciente $conclusao)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($conclusao->paciente_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        
        DB::transaction(function() use($instituicao, $user, $conclusao){
            $conclusao->update(['compartilhado' => ($conclusao->compartilhado == 1) ? 0 : 1]);
            $conclusao->criarLogEdicao($user,$instituicao->id);
        });

        $conclusoes = $paciente->conclusoes($user->id)->orderBy('created_at', 'DESC')->get();

        return view('instituicao.prontuarios.conclusoes.historico', \compact('conclusoes'));
    }

    public function imprimirConclusao(Request $request, ConclusaoPaciente $conclusao)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $agendamento = $conclusao->agendamento()->first();

        abort_unless($conclusao->paciente->instituicao_id === $instituicao->id, 403);

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
            $pdf->loadHTML(view('instituicao.prontuarios.conclusoes.imprimir_conclusao', \compact('conclusao', 'agendamento', 'modelo', 'exibir_data', 'exibir_titulo_paciente')));
            return $pdf->stream();
            // return view('instituicao.prontuarios.prontuario.imprimir_prontuario', \compact('prontuario', 'agendamento', 'modelo'));
        }else{
            $user = $request->user('instituicao'); 

            abort_unless($user->id === $conclusao->usuario_id, 403);
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML(view('instituicao.prontuarios.conclusoes.imprimir_conclusao', \compact('conclusao', 'agendamento', 'modelo', 'exibir_data', 'exibir_titulo_paciente')));
            return $pdf->stream();
            // $pdf = PDF::loadView(view('instituicao.prontuarios.prontuario.imprimir_prontuario', \compact('prontuario', 'agendamento', 'modelo')));
            // return view('instituicao.prontuarios.prontuario.imprimir_prontuario', \compact('prontuario', 'agendamento', 'modelo'));
        }

        return abort('403');
    }

    public function modeloConclusao(Request $request, ModeloConclusao $modelo)
    {
        $user = $request->user('instituicao');
        $prestadorInst = InstituicoesPrestadores::find($modelo->instituicao_prestador_id);

        abort_unless($user->id === $prestadorInst->instituicao_usuario_id, 403);

        return response()->json($modelo);
    }
}
