<?php

namespace App\Http\Controllers\Instituicao;

use App\Alta;
use App\Carteirinha;
use App\Cid;
use App\Http\Controllers\Controller;
use App\Http\Requests\Internacao\CriarInternacaoRequest;
use App\Http\Requests\Internacao\RealizaAltaRequest;
use App\Http\Requests\Internacao\TrocaLeiroRequest;
use App\Http\Requests\Internacao\TrocaMedicoRequest;
use App\Instituicao;
use App\InstituicoesPrestadores;
use App\Internacao;
use App\PreInternacao;
use App\UnidadeInternacao;
use App\AgendamentoAtendimento;
use App\Http\Requests\Internacao\TransferirInstituicaoRequest;
use App\Pessoa;
use App\Prestador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Agendamentos as Agendamento;
use App\Avaliacao;
use App\Support\ConverteValor;

class Internacoes extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_internacao');
        return view('instituicao.internacoes.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_internacao');
        $instituicaoId = $request->session()->get('instituicao');
        $instituicao = Instituicao::find($instituicaoId);
        
        $medicos = Prestador::whereHas('prestadoresInstituicoes', function($q) use ($instituicao){
            $q->where('ativo', 1);
            $q->where('instituicoes_id',$instituicao->id);
        })->get();
        
        $convenios = $instituicao->convenios()->get();

        $origens = $instituicao->origens()->get();
       
        $cids = Cid::get();
        $acomodacoes = $instituicao->acomodacoes()->get();
        $unidades = $instituicao->unidadesInternacoes()->get();

        $especialidades = $instituicao->especialidadesInstituicao()->get();
        
        return view('instituicao.internacoes.criar', \compact('medicos', 'origens', 'acomodacoes', 'unidades', 'cids', 'convenios', 'especialidades'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarInternacaoRequest $request)
    {
        
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_internacao');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));

        $dados = $request->validated();
        $usuario_logado = $request->user('instituicao');

       
        $procedimentos = collect($request->validated()['itens'])
        ->filter(function ($itens) {
            return !is_null($itens['convenio']);
        })
        ->map(function ($itens){
            return [
                'convenio_id' => $itens['convenio'],
                'proc_conv_id' => $itens['procedimento'],
                'quantidade_procedimento' => !empty($itens['quantidade_procedimento']) ? $itens['quantidade_procedimento'] : 1,
                'valor' => !empty($itens['valor']) ? $valor = str_replace(['.', ','],['','.'], $itens['valor']) : 0,
            ];
        });

        // $atendimento_id = AgendamentoAtendimento::where('pessoa_id', $dados['paciente_id'])->where('status', 1)->first();
         
        // if(empty($atendimento_id)){
        //     $atendimento = array(
        //         'pessoa_id' => $dados['paciente_id'],
        //         'data_hora' => date("Y-m-d H:i:s"),
        //         'tipo' => 3,
        //         'status' => 1
        //     );
            
        //     $atend = AgendamentoAtendimento::create($atendimento);
        //     $atend->criarLogCadastro($usuario_logado, $instituicao->id);
        //     $dados['atendimento_id'] = $atend->id;
        // }else{
        //     $dados['atendimento_id'] = $atendimento_id->id;
        // }

        $dados['pre_internacao'] = 0;
        $dados['possui_responsavel'] = (!empty($dados['possui_responsavel'])) ? $dados['possui_responsavel'] : 0;
        $dados['previsao'] = null;
        $preInternacao = null;

        if($dados["internacao_id"]){
            $preInternacao = [
                "id" => $dados["internacao_id"],
                "status" => 1,
            ];
        }

        DB::transaction(function() use ($request, $instituicao, $dados, $preInternacao, $usuario_logado, $procedimentos){
            
            if(!empty($preInternacao)){
                
                $preInsternacao = PreInternacao::find($preInternacao["id"]);
                abort_unless($preInsternacao->instituicao_id === $instituicao->id, 403);
                $preInsternacao->update(['status' => $preInternacao['status']]);
                $preInsternacao->criarLogEdicao($usuario_logado, $instituicao->id);
                
            }

            if(!empty($dados['leito_id']) OR !empty($dados['unidade_id']) OR !empty($dados['acomodacao_id'])){
                $dados_leito = [
                    'acomodacao_id' => !empty($dados['acomodacao_id']) ? $dados['acomodacao_id'] : null,
                    'unidade_id' => !empty($dados['unidade_id']) ? $dados['unidade_id'] : null,
                    'leito_id' => !empty($dados['leito_id']) ? $dados['leito_id'] : null
                ];
            }

            if($dados['medico_id']){
                $dados_medico = ['medico_id' => $dados['medico_id']];
            }

            unset($dados['medico_id'],$dados['acomodacao_id'],$dados['unidade_id'],$dados['leito_id']);
            
            $internacoes = $instituicao->internacoes()->create($dados);
            $internacoes->procedimentos()->attach($procedimentos);
            $internacoes->criarLogCadastro($usuario_logado, $instituicao->id);
            if(!empty($dados_leito)){
                $leito = $internacoes->internacaoLeitos()->create($dados_leito);
                $leito->criarLogCadastro($usuario_logado, $instituicao->id);
            }
            if(!empty($dados_medico)){
                $medico = $internacoes->internacaoMedicos()->create($dados_medico);
                $medico->criarLogCadastro($usuario_logado, $instituicao->id);
            }
            
            
        });

        // return redirect()->route('instituicao.internacoes.index')->with('mensagem', [
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'Internação criada com sucesso!'
        // ]);

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Internação criada com sucesso!'
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
    public function edit(Request $request, Internacao $internacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_internacao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        $medicos = Prestador::whereHas('prestadoresInstituicoes', function($q) use ($instituicao){
            $q->where('ativo', 1);
            $q->where('instituicoes_id',$instituicao->id);
        })->get();

        $origens = $instituicao->origens()->get();

        $convenios = $instituicao->convenios()->get();

        $procedimentos = $internacao->procedimentos()->with(['procedimentoInstituicao.procedimento', 'convenios'])->get();

        $cids = Cid::get();
        $acomodacoes = $instituicao->acomodacoes()->get();
        $unidades = $instituicao->unidadesInternacoes()->get();

        $leitos = $internacao->internacaoLeitos()->orderBy('created_at',  'DESC')->get();

        $internacao_medicos = $internacao->internacaoMedicos()->orderBy('created_at',  'DESC')->get();

        $especialidades = $instituicao->especialidadesInstituicao()->get();
        
        return view('instituicao.internacoes.editar', \compact('leitos', 'internacao_medicos', 'medicos', 'origens', 'acomodacoes', 'unidades', 'cids', 'internacao', 'convenios', 'procedimentos', 'especialidades'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarInternacaoRequest $request, Internacao $internacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_internacao');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$internacao->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $internacao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            unset($dados['internacao_id']);

            $procedimentos = collect($request->validated()['itens'])
            ->filter(function ($itens) {
                return !is_null($itens['convenio']);
            })
            ->map(function ($itens){
                return [
                    'convenio_id' => $itens['convenio'],
                    'proc_conv_id' => $itens['procedimento'],
                    'quantidade_procedimento' => !empty($itens['quantidade_procedimento']) ? $itens['quantidade_procedimento'] : 1,
                    'valor' => !empty($itens['valor']) ? $valor = str_replace(['.', ','],['','.'], $itens['valor']) : 0,
                ];
            });

            $internacao->update($dados);
            $internacao->procedimentos()->detach();
            $internacao->procedimentos()->attach($procedimentos);
            $internacao->criarLogEdicao($usuario_logado, $instituicao);
        });

        // return redirect()->route('instituicao.internacoes.edit', [$internacao])->with('mensagem', [
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'Internação alterada com sucesso!'
        // ]);

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Internação alterada com sucesso!'
        ]); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Internacao $internacao)
    {
        $dados = $request->input();
        
        $this->authorize('habilidade_instituicao_sessao', 'excluir_internacao');
        
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$internacao->instituicao_id, 403);
        if(isset($dados['exclui_pre_internacao'])){
            if($dados['exclui_pre_internacao'] == 1){
                DB::transaction(function () use ($internacao, $request, $instituicao, $dados) {
                    // $dados = $dados['dados'];
                    
                    $usuario_logado = $request->user('instituicao');
                    
                    $pre_internacao = PreInternacao::find($dados['id']);

                    $pre_internacao->delete();
                    
                    $pre_internacao->criarLogExclusao($usuario_logado, $instituicao);
                    
                    $internacao->delete();
                    $internacao->procedimentos()->detach();
                    $internacao->criarLogExclusao($usuario_logado, $instituicao);

                    return $internacao;
                });
            }else{
                DB::transaction(function () use ($internacao, $request, $instituicao, $dados) {
                    // $dados = $dados['dados'];
                    
                    $usuario_logado = $request->user('instituicao');
                    $pre_internacao = PreInternacao::find($dados["id"]);
                    
                    $pre_internacao->update(['status' => 1]);
                    $pre_internacao->criarLogEdicao($usuario_logado, $instituicao);
                    
                    $internacao->delete();
                    $internacao->procedimentos()->detach();
                    $internacao->criarLogExclusao($usuario_logado, $instituicao);
                    
                    return $internacao;
                });
            }
        }else{
            DB::transaction(function () use ($internacao, $request, $instituicao) {
                $usuario_logado = $request->user('instituicao');

                $internacao->delete();
                $internacao->procedimentos()->detach();
                $internacao->criarLogExclusao($usuario_logado, $instituicao);
                
                return $internacao;
            });
        }

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Internação excluída com sucesso!'
        ]);      
        
    }

    public function getPaciente(Request $request){
        $pacientes = [];
       
        $instituicaoId = $request->session()->get('instituicao');
        $instituicao = Instituicao::find($instituicaoId);

        
        if(!empty($request->input('paciente_id'))){
            $pacientes = $instituicao->instituicaoPessoas()
                ->where('tipo', '2')
                ->where('id', $request->input('paciente_id'))
                ->with(['preInternacoes' => function($q){
                    $q->with(['medico', 'Especialidade', 'procedimentos' => function($q){
                        $q->with(['procedimentoInstituicao.procedimento', 'convenios']);
                    }]);
                }])
                ->first();

            $internacao = $instituicao->internacoes()
                ->where([
                    'paciente_id' => $request->input('paciente_id'),
                    'alta_internacao' => 0,
                    'alta_hospitalar' => 0,
                ])
                ->get();

            if($internacao->count() > 0){
                return response()->json([
                    'icon' => 'error',
                    'title' => 'Falha.',
                    'text' => 'Paciente possui internação ativa sem alta!'
                ]);
            }
                

            
            return response()->json($pacientes);
        }else{
            if(!empty($request->input('cpf'))){
                $cpf = $request->input('cpf');
                
                $pacientes = $instituicao->instituicaoPessoas()
                    ->where('tipo', '2')
                    ->where('cpf', $cpf)
                    ->get();
            }

            if(!empty($request->input('nome'))){
                $nome = "%".$request->input('nome')."%";

                $pacientes = $instituicao->instituicaoPessoas()
                    ->where('tipo', '2')
                    ->where('nome', 'like', $nome)
                    ->get();
            }

            return view('instituicao.internacoes.tabela_paciente', \compact('pacientes'));
        }
    }

    public function pesquisaPaciente()
    {
        return view('instituicao.internacoes.modal_paciente');
    }

    public function verPaciente(Request $request)
    {
        $instituicaoId = $request->session()->get('instituicao');
        $instituicao = Instituicao::find($instituicaoId);

        $paciente = [];
        
        if(!empty($request->input('paciente_id'))){
            $paciente = $instituicao->instituicaoPessoas()
                ->where('tipo', '2')
                ->where('id', $request->input('paciente_id'))
                ->first();
        }
        
        return view('instituicao.internacoes.modal_ver_paciente', compact('paciente'));
    }

    public function getCarteirinha(Request $request)
    {
        $carteirinhas = [];

        if(!empty($request->input('paciente_id'))){
            $carteirinhas = Carteirinha::
                where('pessoa_id', $request->input('paciente_id'))
                ->with('convenio')
                ->with('plano')
                ->get();
        }

        return view('instituicao.internacoes.modal_mostra_carteirinha', compact('carteirinhas'));
    }

    public function getPreInternacoes(Request $request)
    {
        $preInternacoes = $request->input('dados');
        return view('instituicao.internacoes.modal_select_pre_internacao', compact('preInternacoes'));
        
    }

    public function getEspecialidades(Request $request)
    {        
        $prestador = InstituicoesPrestadores::find($request->input('medico_id'));
        
        $Especialidades = $prestador->especialidade()->get();

        // $Especialidades = [];

        return response()->json($Especialidades);
    }

    public function getleitos(Request $request)
    {        
        $unidade = UnidadeInternacao::find($request->input('unidade_id'));

        return response()->json($unidade->leitos()->get());
    }

    public function verAlta(Request $request)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
                
        $internacao = null;
        $motivoAlta = null;
        $procedimentos = null;
        $especialidades = null;
        
        if(!empty($request->input('id'))){
            $internacao = Internacao::
                where('id', $request->input('id'))
                ->with('paciente')
                ->first();
            
            $internacao->data_internacao = str_replace(" ", "T", date("Y-m-d H:i:s", strtotime($internacao->created_at)));

            $alta = Alta::where('internacao_id', $request->input('id'));
            
            $motivoAlta = $instituicao->motivosAltas()->get();
            $procedimentos = $instituicao->procedimentos()->get();
            $especialidades = $instituicao->especialidades()->get();
        }

        
        return view('instituicao.internacoes.modal_realiza_alta', compact('internacao', 'motivoAlta', 'procedimentos', 'especialidades', 'alta'));
    }

    public function realizarAlta(RealizaAltaRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'realizar_alta_internacao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        $dados = $request->validated();
        
        $internacao = Internacao::find($dados['id']);

        abort_unless($instituicao->id===$internacao->instituicao_id, 403);

        DB::transaction(function() use ($request, $instituicao, $internacao, $dados){
            unset($dados['id']);
            $usuario_logado = $request->user('instituicao');
            $alta = $internacao->alta()->create($dados);
            
            $alta->criarLogCadastro($usuario_logado, $instituicao->id);
            $internacao->update(['alta_internacao' => 1]);
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Alta médica realizada com sucesso!'
        ]);  
    }

    public function verLeito(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'troca_leito_internacao');
        $instituicaoId = $request->session()->get('instituicao');
        $instituicao = Instituicao::find($instituicaoId);
        
        $internacao = Internacao::find($request->input('id'));

        $internacoesLeitos = $internacao->internacaoLeitos()->orderBy('created_at',  'DESC')->get();
        $leito_atual = (!empty($internacoesLeitos[0])) ? $internacoesLeitos[0] : null;

        $acomodacoes = $instituicao->acomodacoes()->get();
        $unidades = $instituicao->unidadesInternacoes()->get();

        $leitos = !empty($leito_atual->unidade_id) ? UnidadeInternacao::find($leito_atual->unidade_id)->leitos()->get() : null;

        return view('instituicao.internacoes.modal_troca_leito', compact('internacao', 'acomodacoes', 'unidades', 'leitos', 'leito_atual', 'internacoesLeitos'));
        
    }

    public function trocaLeito(TrocaLeiroRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'troca_leito_internacao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        $dados = $request->validated();
        $internacao = Internacao::find($dados['internacao_id']);
        
        abort_unless($instituicao->id===$internacao->instituicao_id, 403);

        // dd($dados);
        
        DB::transaction(function() use ($request, $instituicao, $internacao, $dados){
            $usuario_logado = $request->user('instituicao');

            // $internacao->update($dados);
            $leito = $internacao->internacaoLeitos()->create($dados);
            $leito->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Transferencia de leito realizada com sucesso!'
        ]);  
    }

    public function cancelarAlta(Request $request){
        $this->authorize('habilidade_instituicao_sessao', 'cancelar_alta_internacao');
        
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $internacao_id = $request->input('id');
        $internacao = Internacao::where('id', $internacao_id)->with('alta')->first();

        $dados = array();

        foreach($internacao->alta as $item){
            if(empty($item->data_cancel_alta)){
                $dados = [
                    'status' => 0,
                    'data_cancel_alta' => date("Y-m-d H:i:s"),
                    'id' => $item->id
                ];

                break;
            }
        }

        DB::transaction(function() use ($request, $instituicao, $internacao, $dados){
            $usuario_logado = $request->user('instituicao');
            $internacao->update(['alta_internacao' => 0]);
            $alta = Alta::find($dados['id']);
            $alta->update($dados);
            $alta->criarLogEdicao($usuario_logado, $instituicao->id);

        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Cancelamento de alta medica realizada com sucesso!'
        ]);  
    }
            

    public function verMedico(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'troca_medico_internacao');
        $instituicaoId = $request->session()->get('instituicao');
        $instituicao = Instituicao::find($instituicaoId);
        
        $internacao = Internacao::find($request->input('id'));
        $internacoesMedicoss = $internacao->internacaoMedicos()->orderBy('created_at',  'DESC')->get();
        $medico_atual = (!empty($internacoesMedicoss[0])) ? $internacoesMedicoss[0] : null;


        $medicos = Prestador::whereHas('prestadoresInstituicoes', function($q) use ($instituicao){
            $q->where('ativo', 1);
            $q->where('instituicoes_id',$instituicao->id);
        })->get();
        
        return view('instituicao.internacoes.modal_troca_medico', compact('internacao', 'medicos', 'medico_atual', 'internacoesMedicoss'));
        
    }

    public function trocaMedico(TrocaMedicoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'troca_medico_internacao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        $dados = $request->validated();
        $internacao = Internacao::find($dados['internacao_id']);
        
        abort_unless($instituicao->id===$internacao->instituicao_id, 403);
        
        DB::transaction(function() use ($request, $instituicao, $internacao, $dados){
            $usuario_logado = $request->user('instituicao');

            $medico = $internacao->internacaoMedicos()->create($dados);
            $medico->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Transferencia de médico realizada com sucesso!'
        ]);  
    }

    public function verInstituicao(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'transferir_instituicao_internacao');
        $instituicaoId = $request->session()->get('instituicao');
        $instituicao = Instituicao::find($instituicaoId);
        
        $internacao = Internacao::find($request->input('id'));

        $instituicoes_trasferencia = $instituicao->instituicoesTransferencia()->get();
        
        return view('instituicao.internacoes.modal_transferir_instituicao', compact('internacao', 'instituicoes_trasferencia'));
    }

    public function transferirInstituicao(TransferirInstituicaoRequest $request, Internacao $internacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'transferir_instituicao_internacao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        $dados = $request->validated();
        
        abort_unless($instituicao->id===$internacao->instituicao_id, 403);
        
        DB::transaction(function() use ($request, $instituicao, $internacao, $dados){
            $usuario_logado = $request->user('instituicao');
            
            $internacao->update($dados);
            $internacao->criarLogEdicao($usuario_logado, $instituicao->id);
            $alta = $internacao->alta()->create(['data_alta' => $dados['data_transferencia'], 'status' => 1]);
            $alta->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Transferencia de médico realizada com sucesso!'
        ]);  
    }

    public function abrirProntuario(Request $request, Internacao $internacao)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $paciente = $internacao->paciente;
        $usuario_logado = $request->user('instituicao');

        $dadosAgendamento = [
            'pessoa_id' => $paciente->id,
            'data' => date('Y-m-d H:i'),
            'internacao_id' => $internacao->id, 
            'profissional_id' => $usuario_logado->prestador()->first()->id,
            'tipo' => 'internacao'
        ];        

        $agendamento = Agendamento::create($dadosAgendamento);

        $agendamento->criarLog($usuario_logado, 'Atendimento de internação', $dadosAgendamento, $instituicao->id);
        
        $atendimento = DB::transaction( function() use($usuario_logado, $paciente, $instituicao, $agendamento){
    
            $dadosAtendimento = [
                'pessoa_id' => $paciente->id,
                'data_hora' => date('Y-m-d H:i'),
                'tipo' => 3,
                'status' => 1
            ];

            $atendimento = $agendamento->atendimento()->create($dadosAtendimento);
            $atendimento->criarLogCadastro($usuario_logado, $instituicao->id);
            return $atendimento;
        });

        $agendamentoAtendidos = $paciente->agendamentos()->where('status', '!=', 'ausente')->count();
        $agendamentoAusentes = $paciente->agendamentos()->where('status', 'ausente')->count();
        
        $idade = null;
        if($paciente->nascimento){
            $idade = ConverteValor::calcularIdade($paciente->nascimento);
        }

        return view('instituicao.prontuarios.prontuario', \compact('internacao', 'agendamento', 'paciente', 'atendimento', 'agendamentoAtendidos', 'agendamentoAusentes', 'idade'));
    }
    
    public function abrirProntuarioResumo(Request $request, Internacao $internacao)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $paciente = $internacao->paciente;

        $agendamento = null;
        $atendimento = (object) array('created_at' => date('Y-m-d H:i'));

        $agendamentoAtendidos = $paciente->agendamentos()->where('status', '!=', 'ausente')->count();
        $agendamentoAusentes = $paciente->agendamentos()->where('status', 'ausente')->count();
        
        $idade = null;
        if($paciente->nascimento){
            $idade = ConverteValor::calcularIdade($paciente->nascimento);
        }

        return view('instituicao.prontuarios.prontuario', \compact('internacao', 'agendamento', 'paciente', 'atendimento', 'agendamentoAtendidos', 'agendamentoAusentes', 'idade'));
    }

    public function atenderAvaliacao(Request $request, Avaliacao $avaliacao){
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $usuario_logado = $request->user('instituicao');
        DB::transaction( function() use($avaliacao, $usuario_logado, $instituicao){
            $avaliacao->update(['atendido' => 1]);
            $avaliacao->criarLogEdicao($usuario_logado, $instituicao->id);

        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Avaliação atendida com sucesso!'
        ]); 
        
    }
    

}
