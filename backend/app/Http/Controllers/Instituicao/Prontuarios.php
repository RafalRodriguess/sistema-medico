<?php

namespace App\Http\Controllers\Instituicao;

use App\Agendamentos;
use App\AuditoriaAgendamento;
use App\Cid;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pessoa\EditPessoaRequest;
use App\Http\Requests\Prontuarios\CriarProntuarioPadraoRequest;
use App\Http\Requests\Prontuarios\SalvarProntuarioPacienteRequest;
use App\Instituicao;
use App\InstituicoesPrestadores;
use App\ModeloProntuario;
use App\Pessoa;
use App\ProntuarioPaciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\ConverteValor;
use App\Support\GetModeloImpressao;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;


class Prontuarios extends Controller
{
    public function prontuario(Request $request, Agendamentos $agendamento)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $paciente = $agendamento->pessoa()->first();
        $atendimento = $agendamento->atendimento()->first();
        if(empty($atendimento)){
            $dadosAtendimento = [
                'pessoa_id' => $paciente->id,
                'data_hora' => date('Y-m-d H:i'),
                'tipo' => 4,
                'status' => 0
            ];
    
            $atendimento = $agendamento->atendimento()->create($dadosAtendimento);
        }
        $agendamentoAtendidos = $paciente->agendamentos()->where('status', '!=', 'ausente')->count();
        $agendamentoAusentes = $paciente->agendamentos()->where('status', 'ausente')->count();
        $idade = null;
        if($paciente->nascimento){
            $idade = ConverteValor::calcularIdade($paciente->nascimento);
        }

        if($agendamento->status == "agendado"){
            
            DB::transaction(function() use($agendamento, $request, $instituicao){
                $agendamento->update(['status' => 'em_atendimento']);
                $agendamento->criarLogEdicao($request->user('instituicao'), $instituicao->id);
                AuditoriaAgendamento::logAgendamento($agendamento->id, 'em_atendimento', $request->user('instituicao')->id, 'atender_consultorio');
            });
        }

        return view('instituicao.prontuarios.prontuario', \compact('agendamento', 'paciente', 'atendimento', 'agendamentoAtendidos', 'agendamentoAusentes', 'idade'));
    }

    public function pacienteForm(Request $request, Pessoa $paciente)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);

        return view('instituicao.prontuarios.pacientes.form', [
            'personalidades' => Pessoa::getPersonalidades(),
            'tipos' => Pessoa::getTipos(),
            'referencia_relacoes' => Pessoa::getRelacoesParentescos(),
            'pessoa' => $paciente,
            'sexo' => Pessoa::getSexos(),
            'estado_civil' => Pessoa::getEstadosCivil(),
        ]);
    }

    public function pacienteUpdate(EditPessoaRequest $request, Pessoa $paciente)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);

        $dados = $request->validated();

        $paciente = DB::transaction(function() use ($instituicao, $request, $dados, $paciente) {
            $paciente->update($dados);
            $paciente->criarLogEdicao($request->user('instituicao'), $instituicao->id);
            return $paciente;
        });

        return response()->json(true);
    }

    public function prontuarioPaciente(Request $request, Agendamentos $agendamento, Pessoa $paciente)
    {

        $this->authorize('habilidade_instituicao_sessao', 'visualizar_prontuario');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        $prontuarios = $paciente->prontuarios($user->id)->orderBy('created_at', 'DESC')->get();

        $prontuario = null;

        $modeloProntuario = [];

        $prestador = $user->prestador()->with('modeloProntuario')->get();
        $tipo_prontuario = 'livre';
        if(count($prestador) > 0){
            $tipo_prontuario = $prestador[0]->tipo_prontuario;    
            foreach ($prestador as $key => $value) {
                foreach ($value->modeloProntuario as $key => $modelo) {
                    $modeloProntuario[] = [
                        'id' => $modelo->id,
                        'descricao' => $modelo->descricao,
                        'tipo' => $modelo->prontuario['tipo']
                    ];
                }
            }
        }

        $modelo['prontuario'] = [];
        $cids = Cid::get();

        return view('instituicao.prontuarios.prontuario.info', \compact('prontuarios', 'prontuario', 'modeloProntuario', 'modelo', 'cids', 'tipo_prontuario'));
    }
    
    public function prontuarioPacienteHistorico(Request $request, Agendamentos $agendamento, Pessoa $paciente)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        $prontuarios = $paciente->prontuarios($user->id)->orderBy('created_at', 'DESC')->get();

        return view('instituicao.prontuarios.prontuario.historico', \compact('prontuarios'));
    }

    public function prontuarioSalvar(SalvarProntuarioPacienteRequest $request, Agendamentos $agendamento, Pessoa $paciente)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');

        $prontuario = DB::transaction(function() use($instituicao, $user, $agendamento, $paciente, $request){
            $data = $request->validated();
            $dados = [
                'agendamento_id' => $agendamento->id,
                'usuario_id' => $user->id,
                'compartilhado' => (array_key_exists('compartilhado', $data)) ? 1 : 0,
                'prontuario' => [
                    'tipo' => 'old',
                    'obs' => $request->obs_prontuario,
                    'impressao' => GetModeloImpressao::getModeloImpressao($user)
                ]
            ];
            
            if(array_key_exists('prontuario_id', $data) && $data['prontuario_id'] != null){
                $prontuario = ProntuarioPaciente::find($data['prontuario_id']);
                $prontuario->update(['prontuario' => $dados['prontuario'], 'compartilhado' => (array_key_exists('compartilhado', $data)) ? 1 : 0]);
                $prontuario->criarLogEdicao($user, $instituicao->id);
            }else{
                
                $prontuario = $paciente->prontuario()->create($dados);
    
                $prontuario->criarLogCadastro($user, $instituicao->id);
            }


            return $prontuario;
        });

        $prontuarios = $paciente->prontuarios($user->id)->orderBy('created_at', 'DESC')->get();

        return view('instituicao.prontuarios.prontuario.lista', \compact('prontuarios', 'prontuario'));

    }
    
    public function prontuarioSalvarPadrao(CriarProntuarioPadraoRequest $request, Agendamentos $agendamento, Pessoa $paciente)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');

        $prontuario = DB::transaction(function() use($instituicao, $user, $agendamento, $paciente, $request){
            $data = $request->validated();
            $dados = [
                'agendamento_id' => $agendamento->id,
                'usuario_id' => $user->id,
                'compartilhado' => (array_key_exists('compartilhado', $data)) ? 1 : 0,
                'prontuario' => [
                    'tipo' => 'padrao',
                    'queixa_principal' => $request->queixa_principal,
                    'h_m_a' => $request->h_m_a,
                    'h_p' => $request->h_p,
                    'h_f' => $request->h_f,
                    'hipotese_diagnostica' => $request->hipotese_diagnostica,
                    'conduta' => $request->conduta,
                    'exame_fisico' => $request->exame_fisico,
                    'obs' => $request->obs,
                    'impressao' => GetModeloImpressao::getModeloImpressao($user)
                ]
            ];

            if($request->cid != ""){
                $dados['prontuario']['cid'] = [
                    'id' => $request->cid,
                    'texto' => Cid::find($request->cid)->descricao
                ] ;
            }
            
            if(array_key_exists('prontuario_id', $data) && $data['prontuario_id'] != null){
                $prontuario = ProntuarioPaciente::find($data['prontuario_id']);
                $prontuario->update(['prontuario' => $dados['prontuario'], 'compartilhado' => (array_key_exists('compartilhado', $data)) ? 1 : 0]);
                $prontuario->criarLogEdicao($user, $instituicao->id);
            }else{
                
                $prontuario = $paciente->prontuario()->create($dados);
    
                $prontuario->criarLogCadastro($user, $instituicao->id);
            }


            return $prontuario;
        });

        $prontuarios = $paciente->prontuarios($user->id)->orderBy('created_at', 'DESC')->get();

        return view('instituicao.prontuarios.prontuario.lista', \compact('prontuarios', 'prontuario'));

    }

    public function pacienteGetProntuario(Request $request, Agendamentos $agendamento, Pessoa $paciente, ProntuarioPaciente $prontuario)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($prontuario->paciente_id === $paciente->id, 403);

        if($prontuario->prontuario['tipo'] == 'padrao'){
            $cids = Cid::get();
            $modelo['prontuario'] = $prontuario['prontuario'];
            return view('instituicao.configuracoes.modelo_prontuario.anamnese', \compact('modelo', 'cids'));
        }
        
        return response()->json($prontuario);
    }
    
    public function pacienteExcluirProntuario(Request $request, Agendamentos $agendamento, Pessoa $paciente, ProntuarioPaciente $prontuario)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($prontuario->paciente_id === $paciente->id, 403);

        $user = $request->user('instituicao');

        DB::transaction(function() use($instituicao, $user, $agendamento, $paciente, $prontuario){
            $prontuario->delete();
            $prontuario->criarLogExclusao($user,$instituicao->id);
        });

        $prontuarios = $paciente->prontuarios($user->id)->orderBy('created_at', 'DESC')->get();

        $prontuario = null;
        
        return view('instituicao.prontuarios.prontuario.lista', \compact('prontuarios', 'prontuario'));
    }

    public function compartilharProntuario(Request $request, Agendamentos $agendamento, Pessoa $paciente, ProntuarioPaciente $prontuario)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($prontuario->paciente_id === $paciente->id, 403);

        $user = $request->user('instituicao');

        DB::transaction(function() use($instituicao, $user, $prontuario){
            $prontuario->update(['compartilhado' => ($prontuario->compartilhado == 1) ? 0 : 1]);
            $prontuario->criarLogEdicao($user,$instituicao->id);
        });

        $prontuarios = $paciente->prontuarios($user->id)->orderBy('created_at', 'DESC')->get();

        return view('instituicao.prontuarios.prontuario.historico', \compact('prontuarios'));
    }

    public function imprimirProntuario(Request $request, ProntuarioPaciente $prontuario)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $agendamento = $prontuario->agendamento()->first();

        $user = $request->user('instituicao');

        abort_unless($prontuario->paciente->instituicao_id === $instituicao->id, 403);

        $modelo = null;
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

        $obs = null;
        if(array_key_exists('obs', $prontuario->prontuario)){
            $obs = str_replace("\n", '<br>', $prontuario->prontuario['obs']);
        }

        if (Gate::allows('habilidade_instituicao_sessao', 'visualizar_prontuario_compartilhado')) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML(view('instituicao.prontuarios.prontuario.imprimir_prontuario', \compact('prontuario', 'agendamento', 'modelo', 'obs', 'exibir_data', 'exibir_titulo_paciente')));
            return $pdf->stream();
            // return view('instituicao.prontuarios.prontuario.imprimir_prontuario', \compact('prontuario', 'agendamento', 'modelo'));
        }else{
            $user = $request->user('instituicao'); 

            abort_unless($user->id === $prontuario->usuario_id, 403);
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML(view('instituicao.prontuarios.prontuario.imprimir_prontuario', \compact('prontuario', 'agendamento', 'modelo', 'obs', 'exibir_data', 'exibir_titulo_paciente')));
            return $pdf->stream();
            // $pdf = PDF::loadView(view('instituicao.prontuarios.prontuario.imprimir_prontuario', \compact('prontuario', 'agendamento', 'modelo')));
            // return view('instituicao.prontuarios.prontuario.imprimir_prontuario', \compact('prontuario', 'agendamento', 'modelo'));
        }

        return abort('403');
    }

    public function getModelo(Request $request, ModeloProntuario $modelo)
    {
        $user = $request->user('instituicao');
        $prestadorInst = InstituicoesPrestadores::find($modelo->instituicao_prestador_id);

        abort_unless($user->id === $prestadorInst->instituicao_usuario_id, 403);

        if($modelo->prontuario['tipo'] == "padrao"){
            $cids = Cid::get();
            $modelo['prontuario'] = $modelo->prontuario;
            return view('instituicao.configuracoes.modelo_prontuario.anamnese', \compact('modelo', 'cids'));
        }

        return response()->json($modelo);
    }

}
