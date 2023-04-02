<?php

namespace App\Http\Controllers\Instituicao;

use App\Agendamentos;
use App\Http\Controllers\Controller;
use App\Http\Requests\LaudoPaciente\CriarLaudoPacienteRequest;
use App\Instituicao;
use App\InstituicoesPrestadores;
use App\LaudoPaciente;
use App\ModeloLaudo;
use App\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Support\GetModeloImpressao;
use App\Support\ConverteValor;

class LaudosPaciente extends Controller
{
    public function LaudoPaciente(Request $request, Agendamentos $agendamento, Pessoa $paciente)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_laudo');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        $laudos = $paciente->laudos($user->id)->orderBy('created_at', 'DESC')->get();

        $laudo = null;
        $modeloLaudo = [];

        $prestador = $user->prestador()->with('modeloLaudo')->get();
        if(!empty($prestador)){
            foreach ($prestador as $key => $value) {
                foreach ($value->modeloLaudo as $key => $modelo) {
                    $modeloLaudo[] = [
                        'id' => $modelo->id,
                        'descricao' => $modelo->descricao,
                    ];
                }
            }
        }

        return view('instituicao.prontuarios.laudos.info', \compact('laudos', 'laudo', 'modeloLaudo'));
    }
    
    public function LaudoPacienteHistorico(Request $request, Agendamentos $agendamento, Pessoa $paciente)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        $laudos = $paciente->laudos($user->id)->orderBy('created_at', 'DESC')->get();

        return view('instituicao.prontuarios.laudos.historico', \compact('laudos'));
    }

    public function laudoSalvar(CriarLaudoPacienteRequest $request, Agendamentos $agendamento, Pessoa $paciente)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');

        $laudo = DB::transaction(function() use($instituicao, $user, $agendamento, $paciente, $request){
            $data = $request->validated();

            $map = [
                'nome_instituicao' => $instituicao->nome,
                'cnpj_instituicao' => $instituicao->cnpj,
                'valor_pago' => '',
                'valor_extenso' => '',
                'paciente_nome' => $paciente->nome,
                'paciente_cpf' => $paciente->cpf,
                'paciente_endereco' => "{$paciente->rua} N° {$paciente->numero} {$paciente->cidade} / {$paciente->estado} cep {$paciente->cep}",
                'data_pago' => '',
                'data' => '',
                'fornecedor_nome' => '',
                'fornecedor_cnpj' => '',
                'prestador_cpf' => '',
                'prestador_nome' => '',
                'descricao' => '',
                'paciente_id' => $paciente->id,
                'paciente_data_nascimento' => ($paciente->nascimento) ? date('d/m/Y', strtotime($paciente->nascimento)) : '-',
                'paciente_idade' => ($paciente->nascimento) ? ConverteValor::calcularIdade($paciente->nascimento) : '-',
            ];

            $texto = replaceVariaveis($map, $request->obs_laudo);
            
            $dados = [
                'agendamento_id' => $agendamento->id,
                'usuario_id' => $user->id,
                'laudo' => [
                    'obs' => $texto,
                    'impressao' => GetModeloImpressao::getModeloImpressao($user)
                ]
            ];


            if(array_key_exists('laudo_id', $data) && $data['laudo_id'] != null){
                $laudo = LaudoPaciente::find($data['laudo_id']);
                $laudo->update(['laudo' => $dados['laudo']]);
                $laudo->criarLogEdicao($user, $instituicao->id);
            }else{
                
                $laudo = $paciente->laudo()->create($dados);
    
                $laudo->criarLogCadastro($user, $instituicao->id);
            }


            return $laudo;
        });

        // $laudo = $paciente->laudo($user->id)->orderBy('created_at', 'DESC')->get();

        return response()->json($laudo);

    }

    public function pacienteGetLaudo(Request $request, Agendamentos $agendamento, Pessoa $paciente, LaudoPaciente $laudo)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($laudo->paciente_id === $paciente->id, 403);

        return response()->json($laudo);
    }
    
    public function pacienteExcluirLaudo(Request $request, Agendamentos $agendamento, Pessoa $paciente, LaudoPaciente $laudo)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($laudo->paciente_id === $paciente->id, 403);

        $user = $request->user('instituicao');

        DB::transaction(function() use($instituicao, $user, $agendamento, $paciente, $laudo){
            $laudo->delete();
            $laudo->criarLogExclusao($user,$instituicao->id);
        });

        $laudos = $paciente->laudos($user->id)->orderBy('created_at', 'DESC')->get();

        $laudo = null;
        
        return response()->json(true);
    }

    public function compartilharLaudo(Request $request, Agendamentos $agendamento, Pessoa $paciente, LaudoPaciente $laudo)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($laudo->paciente_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        
        DB::transaction(function() use($instituicao, $user, $laudo){
            $laudo->update(['compartilhado' => ($laudo->compartilhado == 1) ? 0 : 1]);
            $laudo->criarLogEdicao($user,$instituicao->id);
        });

        $laudos = $paciente->laudos($user->id)->orderBy('created_at', 'DESC')->get();

        return view('instituicao.prontuarios.laudos.historico', \compact('laudos'));
    }

    public function imprimirLaudo(Request $request, LaudoPaciente $laudo)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $agendamento = $laudo->agendamento()->first();

        abort_unless($laudo->paciente->instituicao_id === $instituicao->id, 403);

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
            // $pdf = App::make('dompdf.wrapper');
            // $pdf->loadHTML(view('instituicao.prontuarios.laudos.imprimir_laudo', \compact('laudo', 'agendamento', 'modelo', 'exibir_data', 'exibir_titulo_paciente')));
            // return $pdf->stream();
            return view('instituicao.prontuarios.laudos.imprimir_laudo', \compact('laudo', 'agendamento', 'modelo', 'exibir_data', 'exibir_titulo_paciente'));
        }else{
            $user = $request->user('instituicao'); 

            abort_unless($user->id === $laudo->usuario_id, 403);
            // $pdf = App::make('dompdf.wrapper');
            // $pdf->loadHTML(view('instituicao.prontuarios.laudos.imprimir_laudo', \compact('laudo', 'agendamento', 'modelo', 'exibir_data', 'exibir_titulo_paciente')));
            // return $pdf->stream();
            // $pdf = PDF::loadView(view('instituicao.prontuarios.prontuario.imprimir_prontuario', \compact('prontuario', 'agendamento', 'modelo')));
            return view('instituicao.prontuarios.laudos.imprimir_laudo', \compact('laudo', 'agendamento', 'modelo', 'exibir_data', 'exibir_titulo_paciente'));
        }

        return abort('403');
    }

    public function modeloLaudo(Request $request, ModeloLaudo $modelo)
    {
        $user = $request->user('instituicao');
        $prestadorInst = InstituicoesPrestadores::find($modelo->instituicao_prestador_id);

        abort_unless($user->id === $prestadorInst->instituicao_usuario_id, 403);

        return response()->json($modelo);
    }
}
