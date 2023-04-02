<?php

namespace App\Http\Controllers\Instituicao;

use App\Agendamentos;
use App\Http\Controllers\Controller;
use App\Http\Requests\RefracaoPaciente\CriarRefracaoPacienteRequest;
use App\Instituicao;
use App\Pessoa;
use App\RefracaoPaciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\GetModeloImpressao;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class RefracoesPaciente extends Controller
{
    public function refracaoPaciente(Request $request, Agendamentos $agendamento, Pessoa $paciente)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_refracao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        $refracoes = $paciente->refracoes($user->id)->orderBy('created_at', 'DESC')->get();

        $refracao = null;

        return view('instituicao.prontuarios.refracoes.info', \compact('refracoes', 'refracao'));
    }
    
    public function refracaoPacienteHistorico(Request $request, Agendamentos $agendamento, Pessoa $paciente)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        $refracoes = $paciente->refracoes($user->id)->orderBy('created_at', 'DESC')->get();

        return view('instituicao.prontuarios.refracoes.historico', \compact('refracoes'));
    }

    public function refracaoSalvar(CriarRefracaoPacienteRequest $request, Agendamentos $agendamento, Pessoa $paciente)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');

        $refracao = DB::transaction(function() use($instituicao, $user, $agendamento, $paciente, $request){
            $data = $request->validated();
            $dados = [
                'agendamento_id' => $agendamento->id,
                'usuario_id' => $user->id,
                'refracao' => [
                    'refracao_atual' => $data['refracao_atual'],
                    'acuidade_visual' => $data['acuidade_visual'],
                    'refracao_estatica' => $data['refracao_estatica'],
                    'refracao_dinamica' => $data['refracao_dinamica'],
                    'prescricao_oculos' => $data['prescricao_oculos'],
                    'impressao' => GetModeloImpressao::getModeloImpressao($user)
                ]
            ];
            
            if(array_key_exists('refracao_id', $data) && $data['refracao_id'] != null){
                $refracao = RefracaoPaciente::find($data['refracao_id']);
                $refracao->update(['refracao' => $dados['refracao']]);
                $refracao->criarLogEdicao($user, $instituicao->id);
            }else{
                
                $refracao = $paciente->refracao()->create($dados);
    
                $refracao->criarLogCadastro($user, $instituicao->id);
            }


            return $refracao;
        });

        // $refracao = $paciente->refracao($user->id)->orderBy('created_at', 'DESC')->get();

        return response()->json($refracao);

    }

    public function pacienteGetRefracao(Request $request, Agendamentos $agendamento, Pessoa $paciente, RefracaoPaciente $refracao)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($refracao->paciente_id === $paciente->id, 403);
        
        return view('instituicao.prontuarios.refracoes.form', \compact('refracao'));
        // return response()->json($refracao);
    }
    
    public function pacienteExcluirRefracao(Request $request, Agendamentos $agendamento, Pessoa $paciente, RefracaoPaciente $refracao)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($refracao->paciente_id === $paciente->id, 403);

        $user = $request->user('instituicao');

        DB::transaction(function() use($instituicao, $user, $agendamento, $paciente, $refracao){
            $refracao->delete();
            $refracao->criarLogExclusao($user,$instituicao->id);
        });
        
        return response()->json(true);
    }

    // public function compartilharRefracao(Request $request, Agendamentos $agendamento, Pessoa $paciente, RefracaoPaciente $refracao)
    // {

    //     $instituicao = Instituicao::find($request->session()->get('instituicao'));

    //     abort_unless($instituicao->id === $paciente->instituicao_id, 403);
    //     abort_unless($agendamento->pessoa_id === $paciente->id, 403);
    //     abort_unless($refracao->paciente_id === $paciente->id, 403);

    //     $user = $request->user('instituicao');
        
    //     DB::transaction(function() use($instituicao, $user, $refracao){
    //         $refracao->update(['compartilhado' => ($refracao->compartilhado == 1) ? 0 : 1]);
    //         $refracao->criarLogEdicao($user,$instituicao->id);
    //     });

    //     $refracoes = $paciente->refracoes($user->id)->orderBy('created_at', 'DESC')->get();

    //     return view('instituicao.prontuarios.refracoes.historico', \compact('refracoes'));
    // }

    public function imprimirRefracao(Request $request, RefracaoPaciente $refracao)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $agendamento = $refracao->agendamento()->first();

        abort_unless($refracao->paciente->instituicao_id === $instituicao->id, 403);

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
            $pdf->loadHTML(view('instituicao.prontuarios.refracoes.imprimir_refracao', \compact('refracao', 'agendamento', 'modelo', 'exibir_data', 'exibir_titulo_paciente')));
            return $pdf->stream();
            // return view('instituicao.prontuarios.prontuario.imprimir_refracao', \compact('prontuario', 'agendamento', 'modelo'));
        }else{
            $user = $request->user('instituicao'); 

            abort_unless($user->id === $refracao->usuario_id, 403);
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML(view('instituicao.prontuarios.refracoes.imprimir_refracao', \compact('refracao', 'agendamento', 'modelo', 'exibir_data', 'exibir_titulo_paciente')));
            return $pdf->stream();
            // $pdf = PDF::loadView(view('instituicao.prontuarios.prontuario.imprimir_refracao', \compact('prontuario', 'agendamento', 'modelo')));
            // return view('instituicao.prontuarios.prontuario.imprimir_refracao', \compact('prontuario', 'agendamento', 'modelo'));
        }

        return abort('403');
    }
}
