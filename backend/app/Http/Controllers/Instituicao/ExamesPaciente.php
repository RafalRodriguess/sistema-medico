<?php

namespace App\Http\Controllers\Instituicao;

use App\Agendamentos;
use App\ExamePaciente;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExamePaciente\CriarExamePaciente;
use App\Instituicao;
use App\InstituicoesPrestadores;
use App\ModeloExame;
use App\Pessoa;
use App\Procedimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Support\GetModeloImpressao;

class ExamesPaciente extends Controller
{
    public function examePaciente(Request $request, Agendamentos $agendamento, Pessoa $paciente)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_exame');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        $exames = $paciente->exames($user->id)->orderBy('created_at', 'DESC')->get();

        $exame = null;

        $modeloExame = [];

        $prestador = $user->prestador()->with(['modeloExame' => function($q){
            $q->orderBy('descricao', 'ASC');
        }])->get();
        if(!empty($prestador)){
            foreach ($prestador as $key => $value) {
                foreach ($value->modeloExame as $key => $modelo) {
                    $modeloExame[] = [
                        'id' => $modelo->id,
                        'descricao' => $modelo->descricao,
                    ];
                }
            }
        }

        return view('instituicao.prontuarios.exames.info', \compact('exames', 'exame', 'modeloExame'));
    }
    
    public function examePacienteHistorico(Request $request, Agendamentos $agendamento, Pessoa $paciente)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        $exames = $paciente->exames($user->id)->orderBy('created_at', 'DESC')->get();

        return view('instituicao.prontuarios.exames.historico', \compact('exames'));
    }

    public function exameSalvar(CriarExamePaciente $request, Agendamentos $agendamento, Pessoa $paciente)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');

        $exame = DB::transaction(function() use($instituicao, $user, $agendamento, $paciente, $request){
            $data = $request->validated();
            $dados = [
                'agendamento_id' => $agendamento->id,
                'usuario_id' => $user->id,
                'compartilhado' => (array_key_exists('compartilhado', $data)) ? 1 : 0,
                'exame' => [
                    'tipo' => 'livre',
                    'obs' => $request->obs_exame,
                    'impressao' => GetModeloImpressao::getModeloImpressao($user)
                ]
            ];

            if(array_key_exists('exame_id', $data) && $data['exame_id'] != null){
                $exame = ExamePaciente::find($data['exame_id']);
                $exame->update(['exame' => $dados['exame'], 'compartilhado' => (array_key_exists('compartilhado', $data)) ? 1 : 0]);
                $exame->criarLogEdicao($user, $instituicao->id);
            }else{
                
                $exame = $paciente->exame()->create($dados);
    
                $exame->criarLogCadastro($user, $instituicao->id);
            }


            return $exame;
        });

        // $exame = $paciente->exame($user->id)->orderBy('created_at', 'DESC')->get();

        return response()->json($exame);

    }

    public function pacienteGetExame(Request $request, Agendamentos $agendamento, Pessoa $paciente, ExamePaciente $exame)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($exame->paciente_id === $paciente->id, 403);

        return response()->json($exame);
    }
    
    public function pacienteExcluirExame(Request $request, Agendamentos $agendamento, Pessoa $paciente, ExamePaciente $exame)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($exame->paciente_id === $paciente->id, 403);

        $user = $request->user('instituicao');

        DB::transaction(function() use($instituicao, $user, $agendamento, $paciente, $exame){
            $exame->delete();
            $exame->criarLogExclusao($user,$instituicao->id);
        });

        $exames = $paciente->exames($user->id)->orderBy('created_at', 'DESC')->get();

        $exame = null;
        
        return response()->json(true);
    }

    public function compartilharExame(Request $request, Agendamentos $agendamento, Pessoa $paciente, ExamePaciente $exame)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($exame->paciente_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        
        DB::transaction(function() use($instituicao, $user, $exame){
            $exame->update(['compartilhado' => ($exame->compartilhado == 1) ? 0 : 1]);
            $exame->criarLogEdicao($user,$instituicao->id);
        });

        $exames = $paciente->exames($user->id)->orderBy('created_at', 'DESC')->get();

        return view('instituicao.prontuarios.exames.historico', \compact('exames'));
    }

    public function imprimirExame(Request $request, ExamePaciente $exame)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $agendamento = $exame->agendamento()->first();

        abort_unless($exame->paciente->instituicao_id === $instituicao->id, 403);

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
            $pdf->loadHTML(view('instituicao.prontuarios.exames.imprimir_exame', \compact('exame', 'agendamento', 'modelo', 'exibir_data', 'exibir_titulo_paciente')));
            return $pdf->stream();
            // return view('instituicao.prontuarios.prontuario.imprimir_exame', \compact('prontuario', 'agendamento', 'modelo'));
        }else{
            $user = $request->user('instituicao'); 

            abort_unless($user->id === $exame->usuario_id, 403);
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML(view('instituicao.prontuarios.exames.imprimir_exame', \compact('exame', 'agendamento', 'modelo', 'exibir_data', 'exibir_titulo_paciente')));
            return $pdf->stream();
            // $pdf = PDF::loadView(view('instituicao.prontuarios.prontuario.imprimir_exame', \compact('prontuario', 'agendamento', 'modelo')));
            // return view('instituicao.prontuarios.prontuario.imprimir_exame', \compact('prontuario', 'agendamento', 'modelo'));
        }

        return abort('403');
    }

    public function modeloExame(Request $request, ModeloExame $modelo)
    {
        $user = $request->user('instituicao');
        $prestadorInst = InstituicoesPrestadores::find($modelo->instituicao_prestador_id);

        abort_unless($user->id === $prestadorInst->instituicao_usuario_id, 403);

        return response()->json($modelo);
    }

    public function getSelectProcedimentos(Request $request)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $procedimentos = Procedimento::whereHas('procedimentoInstituicao', function($q) use($instituicao){
            $q->where("instituicoes_id", $instituicao->id);
        })->with(['procedimentoInstituicao'=> function($q) use($instituicao){
            $q->where("instituicoes_id", $instituicao->id);
        }])
        ->when($request->input('descricao'), function($q) use($request){
            $q->where('descricao', 'like', "%{$request->input('descricao')}%");
        })
        ->orderBy('descricao', 'ASC')
        ->paginate(50);
        // dd($procedimentos->toArray());
        // $procedimentoConvenio_id = $procedimentos[0]->instituicaoProcedimentosConvenios[0]->pivot->id;

        // $procConvenio = DB::table('procedimentos_convenios_has_repasse_medico')
        //     ->where('procedimento_instituicao_convenio_id', $procedimentoConvenio_id)
        //     ->where('prestador_id', $request->input('prestador'))
        //     ->first();

        // if(!empty($procConvenio) && !empty((float) $procConvenio->valor_cobrado)){
        //     $procedimentos[0]->instituicaoProcedimentosConvenios[0]->pivot->valor = $procConvenio->valor_cobrado;
        // }
        $procedimentosSelect = [];
        return view("instituicao.prontuarios.exames.procedimentos", compact('procedimentos', 'procedimentosSelect'));
    }
    
    public function getSelectProcedimentosDescricao(Request $request)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $procedimentos = Procedimento::whereHas('procedimentoInstituicao', function($q) use($instituicao){
            $q->where("instituicoes_id", $instituicao->id);
        })->with(['procedimentoInstituicao'=> function($q) use($instituicao){
            $q->where("instituicoes_id", $instituicao->id);
        }])
        ->when($request->input('descricao'), function($q) use($request){
            $q->where('descricao', 'like', "%{$request->input('descricao')}%");
        })
        ->orderBy('descricao', 'ASC')
        ->paginate(50);

        $procedimentosSelect = [];
        $procedimentos_exames = $request->input('procedimentos_exames');
        if(is_array($procedimentos_exames)){
            if(count($procedimentos_exames) > 0){
                for ($i=0; $i < count($procedimentos_exames); $i++) { 
                    $procedimentosSelect[$procedimentos_exames[$i][1]] = $procedimentos_exames[$i][0];
                }
            }
        }
        // dd($procedimentos->toArray());
        // $procedimentoConvenio_id = $procedimentos[0]->instituicaoProcedimentosConvenios[0]->pivot->id;

        // $procConvenio = DB::table('procedimentos_convenios_has_repasse_medico')
        //     ->where('procedimento_instituicao_convenio_id', $procedimentoConvenio_id)
        //     ->where('prestador_id', $request->input('prestador'))
        //     ->first();

        // if(!empty($procConvenio) && !empty((float) $procConvenio->valor_cobrado)){
        //     $procedimentos[0]->instituicaoProcedimentosConvenios[0]->pivot->valor = $procConvenio->valor_cobrado;
        // }

        return view("instituicao.prontuarios.exames.lista_procedimento", compact('procedimentos', 'procedimentosSelect'));
    }
}
