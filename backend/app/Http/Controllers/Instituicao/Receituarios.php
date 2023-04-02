<?php

namespace App\Http\Controllers\Instituicao;

use App\Agendamentos;
use App\Http\Controllers\Controller;
use App\Http\Requests\Receituario\CriarMedicamentoReceituarioRequest;
use App\Http\Requests\Receituario\CriarReceituarioLivreRequest;
use App\Http\Requests\Receituario\CriarReceituarioMedicamentoRequest;
use App\Instituicao;
use App\InstituicaoMedicamento;
use App\InstituicoesPrestadores;
use App\ModeloReceituario;
use App\Pessoa;
use App\ReceituarioPaciente;
use Collections\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Support\GetModeloImpressao;

class Receituarios extends Controller
{
    public function receituarioPaciente(Request $request, Agendamentos $agendamento, Pessoa $paciente)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_receituario');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        $receituarios = $paciente->receituarios($user->id)->orderBy('created_at', 'DESC')->get();

        $receituario = null;
        $medicamentosReceituario = [];

        $medicamentos = $instituicao->medicamentos()->orderBy('nome', 'ASC')->get();

        $modeloReceituario = [];

        $prestador = $user->prestador()->with(['modeloReceituario' => function($q){
            $q->orderBy('descricao', 'ASC');
        }])->get();
        if(!empty($prestador)){
            foreach ($prestador as $key => $value) {
                foreach ($value->modeloReceituario as $key => $modelo) {
                    $modeloReceituario[] = [
                        'id' => $modelo->id,
                        'descricao' => $modelo->descricao,
                    ];
                }
            }
        }

        return view('instituicao.prontuarios.receituarios.index', \compact('receituarios', 'receituario', 'medicamentosReceituario', 'medicamentos', 'modeloReceituario'));
    }

    public function receituarioPacienteHistorico(Request $request, Agendamentos $agendamento, Pessoa $paciente)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        $receituarios = $paciente->receituarios($user->id)->orderBy('created_at', 'DESC')->get();

        return view('instituicao.prontuarios.receituarios.historico', \compact('receituarios'));
    }

    public function receituarioSalvarLivre(CriarReceituarioLivreRequest $request, Agendamentos $agendamento, Pessoa $paciente)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');

        $receituario = DB::transaction(function() use($instituicao, $user, $agendamento, $paciente, $request){
            $data = $request->validated();
            $tipo = ($data['receituario_livre_tipo'] == "1") ? 'especial' : 'simples';

            $dados = [
                'agendamento_id' => $agendamento->id,
                'usuario_id' => $user->id,
                'receituario' => [
                    'receituario' => $request->receituario_livre,
                    'impressao' => GetModeloImpressao::getModeloImpressao($user)
                ],
                'tipo' => $tipo,
                'estrutura' => 'livre',
                // 'compartilhado' => $data['compartilhado'],
            ];
            // dd($data['receituario_livre_tipo']);
            if(array_key_exists('receituario_livre_id', $data) && $data['receituario_livre_id'] != null){
                unset($dados['estrutura']);
                unset($dados['agendamento_id']);
                unset($dados['usuario_id']);
                $receituario = ReceituarioPaciente::find($data['receituario_livre_id']);
                $receituario->update($dados);
                $receituario->criarLogEdicao($user, $instituicao->id);
            }else{
                
                $receituario = $paciente->receituario()->create($dados);
    
                $receituario->criarLogCadastro($user, $instituicao->id);
            }


            return $receituario;
        });

        // $receituarios = $paciente->receituarios($user->id)->orderBy('created_at', 'DESC')->get();

        // $medicamentosReceituario = [];

        // $medicamentos = $instituicao->medicamentos()->get();
        return response()->json($receituario);
        // return view('instituicao.prontuarios.receituarios.index', \compact('receituarios', 'receituario', 'medicamentosReceituario', 'medicamentos'));
    }

    public function cadastrarMedicamento(CriarMedicamentoReceituarioRequest $request)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $medicamento = DB::transaction(function() use($request, $instituicao){
            
            $dados = $request->validated();
            $dados['status'] = 1;
            if(array_key_exists('composicoes', $dados)){
                foreach ($dados['composicoes'] as $key => $value) {
                    $dados['composicao'][] = $value;
                }
            }

            $medicamento = $instituicao->medicamentos()->create($dados);
            $usuario_logado = $request->user('instituicao');

            $medicamento->criarLogCadastro($usuario_logado, $instituicao->id);

            if(array_key_exists('posologia', $dados) || array_key_exists('quantidade', $dados)){
                $data[] = [
                    'posologia' => (array_key_exists('posologia', $dados)) ? $dados['posologia'] : null, 
                    'quantidade' => (array_key_exists('quantidade', $dados)) ? $dados['quantidade'] : null, 
                    'instituicao_usuario_id' => $usuario_logado->id,
                ];
                
                $medicamento->usuario()->attach($data);
            }
            return $medicamento;
        });

        return response()->json($medicamento);
    }

    public function receituarioSalvar(CriarReceituarioMedicamentoRequest $request, Agendamentos $agendamento, Pessoa $paciente)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');

        $receituario = DB::transaction(function() use($instituicao, $user, $agendamento, $paciente, $request){
            $data = $request->validated();
            $tipo = ($data['receituario_medicamento_tipo'] == "1") ? 'especial' : 'simples';

            $dados = [
                'agendamento_id' => $agendamento->id,
                'usuario_id' => $user->id,
                'tipo' => $tipo,
                'estrutura' => 'formulario'
            ];
                        
            foreach ($request->validated()['medicamentos'] as $key => $value) {
                
                $medicamento = InstituicaoMedicamento::find($value['medicamento']);  
                $composicao = null;
                if(array_key_exists('composicoes', $request->validated())) { 
                    if(array_key_exists($key, $request->validated()['composicoes'])) { 
                        foreach ($request->validated()['composicoes'][$key] as $key => $composicoes) {
                            $composicao[] = [
                                "substancia" => $composicoes['substancia'],
                                "concentracao" => $composicoes['concentracao'],
                            ];
                        }
                    }
                }

                $medicamentos[] = [
                    "medicamento" => [
                        'nome' => $medicamento->nome.' ('.$medicamento->forma_farmaceutica.' '.$medicamento->concentracao.')',
                        'medicamento_id' => $medicamento->id,
                        'composicao' => $composicao,
                    ],
                    "quantidade" => $value['quantidade'],
                    "posologia" => $value['posologia'],
                ];
            }
            
            // $medicamentos = collect($request->validated()['medicamentos'])
            // ->filter(function ($medicamentos) {
            //     return !is_null($medicamentos);
            // })
            // ->map(function ($medicamentos) use($request){
            //     $medicamento = InstituicaoMedicamento::find($medicamentos['medicamento']);
            //     return [
            //         "medicamento" => [
            //             'nome' => $medicamento->nome.' '.$medicamento->forma_farmaceutica.' '.$medicamento->concentracao,
            //             'medicamento_id' => $medicamento->id,
            //             'composicao' => $medicamento->composicao,
            //         ],
            //         "quantidade" => $medicamentos['quantidade'],
            //         "posologia" => $medicamentos['posologia'],
            //     ];
            // });

            $dados['receituario'] = [
                'medicamentos' => $medicamentos,
                'impressao' => GetModeloImpressao::getModeloImpressao($user)
            ];

            // $dados['compartilhado'] = $data['compartilhado'];

            if(array_key_exists('receituario_medicamento_id', $data) && $data['receituario_medicamento_id'] != null){
                unset($dados['estrutura']);
                unset($dados['agendamento_id']);
                unset($dados['usuario_id']);

                $receituario = ReceituarioPaciente::find($data['receituario_medicamento_id']);
                $receituario->update($dados);
                $receituario->criarLogEdicao($user, $instituicao->id);
            }else{
                
                $receituario = $paciente->receituario()->create($dados);
    
                $receituario->criarLogCadastro($user, $instituicao->id);
            }


            return $receituario;
        });

        // $receituarios = $paciente->receituarios($user->id)->orderBy('created_at', 'DESC')->get();

        // $medicamentosReceituario = [];

        // $medicamentos = $instituicao->medicamentos()->get();
        return response()->json($receituario);
        // return view('instituicao.prontuarios.receituarios.index', \compact('receituarios', 'receituario', 'medicamentosReceituario', 'medicamentos'));
    }

    public function pacienteExcluirReceituario(Request $request, Agendamentos $agendamento, Pessoa $paciente, ReceituarioPaciente $receituario)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($receituario->paciente_id === $paciente->id, 403);

        $user = $request->user('instituicao');

        DB::transaction(function() use($instituicao, $user, $agendamento, $paciente, $receituario){
            $receituario->delete();
            $receituario->criarLogExclusao($user,$instituicao->id);
        });

        return response()->json(true);
    }

    public function pacienteGetReceituario(Request $request, Agendamentos $agendamento, Pessoa $paciente, ReceituarioPaciente $receituario)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($receituario->paciente_id === $paciente->id, 403);

        return response()->json($receituario);
    }

    public function getComposicaoMedicamento(Request $request, InstituicaoMedicamento $medicamento)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($instituicao->id === $medicamento->instituicao_id, 403);
        $usuario_logado = $request->user('instituicao');

        $medicamento->load('usuario');
        
        return response()->json($medicamento);
    }

    public function compartilharReceituario(Request $request, Agendamentos $agendamento, Pessoa $paciente, ReceituarioPaciente $receituario)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($receituario->paciente_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        
        DB::transaction(function() use($instituicao, $user, $receituario){
            $receituario->update(['compartilhado' => ($receituario->compartilhado == 1) ? 0 : 1]);
            $receituario->criarLogEdicao($user,$instituicao->id);
        });

        $receituarios = $paciente->receituarios($user->id)->orderBy('created_at', 'DESC')->get();

        return view('instituicao.prontuarios.receituarios.historico', \compact('receituarios'));
    }

    public function imprimirReceituario(Request $request, ReceituarioPaciente $receituario)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $agendamento = $receituario->agendamento()->first();

        abort_unless($receituario->paciente->instituicao_id === $instituicao->id, 403);

        $modelo = null;

        $receituario->load(
            'usuario',
            'usuario.prestadorMedico',
            'usuario.prestadorMedico.prestador'
        );

        $dadosEspecial = [
            'crm' => '',
            'rua' => '',
            'numero' => '',
            'bairro' => '',
            'cidade' => '',
            'estado' => '',
            'telefone' => '',
        ];

        if($receituario->usuario->prestadorMedico[0]->crm){
            $dadosEspecial['crm'] = $receituario->usuario->prestadorMedico[0]->crm;
        }
        if($receituario->usuario->prestadorMedico[0]->prestador->rua){
            $dadosEspecial['rua'] = $receituario->usuario->prestadorMedico[0]->prestador->rua;
        }
        if($receituario->usuario->prestadorMedico[0]->prestador->numero){
            $dadosEspecial['numero'] = $receituario->usuario->prestadorMedico[0]->prestador->numero;
        }
        if($receituario->usuario->prestadorMedico[0]->prestador->bairro){
            $dadosEspecial['bairro'] = $receituario->usuario->prestadorMedico[0]->prestador->bairro;
        }
        if($receituario->usuario->prestadorMedico[0]->prestador->cidade){
            $dadosEspecial['cidade'] = $receituario->usuario->prestadorMedico[0]->prestador->cidade;
        }
        if($receituario->usuario->prestadorMedico[0]->prestador->estado){
            $dadosEspecial['estado'] = $receituario->usuario->prestadorMedico[0]->prestador->estado;
        }
        if($receituario->usuario->prestadorMedico[0]->telefone){
            $dadosEspecial['telefone'] = $receituario->usuario->prestadorMedico[0]->telefone;
        }

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
        
        if($receituario->estrutura == "livre"){
            if (Gate::allows('habilidade_instituicao_sessao', 'visualizar_prontuario_compartilhado')) {
                $pdf = App::make('dompdf.wrapper');
                $pdf->loadHTML(view('instituicao.prontuarios.receituarios.imprimir_receituario_livre', \compact('receituario', 'agendamento', 'modelo', 'dadosEspecial', 'exibir_data', 'exibir_titulo_paciente')));
                return $pdf->stream();
                // return view('instituicao.prontuarios.receituarios.imprimir_receituario', \compact('prontuario', 'agendamento', 'modelo'));
            }else{
                $user = $request->user('instituicao'); 
    
                abort_unless($user->id === $receituario->usuario_id, 403);
                $pdf = App::make('dompdf.wrapper');
                $pdf->loadHTML(view('instituicao.prontuarios.receituarios.imprimir_receituario_livre', \compact('receituario', 'agendamento', 'modelo', 'dadosEspecial', 'exibir_data', 'exibir_titulo_paciente')));
                return $pdf->stream();
                // $pdf = PDF::loadView(view('instituicao.prontuarios.receituarios.imprimir_receituario', \compact('receituario', 'agendamento', 'modelo')));
                // return view('instituicao.prontuarios.receituarios.imprimir_receituario', \compact('receituario', 'agendamento', 'modelo'));
            }
        }else{
            $medicamentos = [];
            // [{"posologia": "t", "quantidade": "2", "medicamento": {"nome": "Dipirona (Comprimido 5 mg)", "composicao": null, "medicamento_id": 1}}]
            if(array_key_exists('medicamentos', $receituario->receituario)){
                foreach ($receituario->receituario['medicamentos'] as $key => $value) {
                    $medicamento = InstituicaoMedicamento::find($value['medicamento']['medicamento_id']);
                    if(array_key_exists($medicamento->via_administracao, $medicamentos)){
                        $medicamentos[$medicamento->via_administracao][] = $value;
                    }else{
                        $medicamentos[$medicamento->via_administracao]['via_adm'] = InstituicaoMedicamento::convertViaParaEscrito($medicamento->via_administracao);
                        $medicamentos[$medicamento->via_administracao][] = $value;
                    }
                }
            }else{
                foreach ($receituario->receituario as $key => $value) {
                    $medicamento = InstituicaoMedicamento::find($value['medicamento']['medicamento_id']);
                    if(array_key_exists($medicamento->via_administracao, $medicamentos)){
                        $medicamentos[$medicamento->via_administracao][] = $value;
                    }else{
                        $medicamentos[$medicamento->via_administracao]['via_adm'] = InstituicaoMedicamento::convertViaParaEscrito($medicamento->via_administracao);
                        $medicamentos[$medicamento->via_administracao][] = $value;
                    }
                }
            }
            // dd($medicamentos);
            if (Gate::allows('habilidade_instituicao_sessao', 'visualizar_prontuario_compartilhado')) {
                $pdf = App::make('dompdf.wrapper');
                $pdf->loadHTML(view('instituicao.prontuarios.receituarios.imprimir_receituario_medicamento', \compact('receituario', 'medicamentos', 'agendamento', 'modelo', 'dadosEspecial', 'exibir_data', 'exibir_titulo_paciente')));
                return $pdf->stream();
                // return view('instituicao.prontuarios.receituarios.imprimir_receituario_medicamento', \compact('medicamentos', 'receituario', 'agendamento', 'modelo', 'dadosEspecial'));
            }else{
                $user = $request->user('instituicao'); 
    
                abort_unless($user->id === $receituario->usuario_id, 403);
                $pdf = App::make('dompdf.wrapper');
                $pdf->loadHTML(view('instituicao.prontuarios.receituarios.imprimir_receituario_medicamento', \compact('receituario', 'medicamentos', 'agendamento', 'modelo', 'dadosEspecial', 'exibir_data', 'exibir_titulo_paciente')));
                return $pdf->stream();
                // $pdf = PDF::loadView(view('instituicao.prontuarios.receituarios.imprimir_receituario', \compact('receituario', 'agendamento', 'modelo')));
                // return view('instituicao.prontuarios.receituarios.imprimir_receituario_medicamento', \compact('medicamentos', 'receituario', 'agendamento', 'modelo', 'dadosEspecial'));
            }
        }

        return abort('403');
    }

    public function modeloReceituario(Request $request, ModeloReceituario $modelo)
    {
        $user = $request->user('instituicao');
        $prestadorInst = InstituicoesPrestadores::find($modelo->instituicao_prestador_id);

        abort_unless($user->id === $prestadorInst->instituicao_usuario_id, 403);

        return response()->json($modelo);
    }
}
