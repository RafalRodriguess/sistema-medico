<?php

namespace App\Http\Controllers\Instituicao;

use App\ContaReceber;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContasReceber\CriarContaReceberRequest;
use App\Http\Requests\ContasReceber\EditarContaReceberRequest;
use App\Http\Requests\ContasReceber\ReceberParcelaContaReceberRequest;
use App\Instituicao;
use App\ModeloRecibo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\Outros;
use App\Support\ConverteValor;

class ContasReceber extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_contas_receber');

        return view('instituicao.contas_receber.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_contas_receber');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        // $pacientes = $instituicao->instituicaoPessoas()->get();
        $tipos = ContaReceber::tipos();
        $contas = $instituicao->contas()->get();
        $planosConta = $instituicao->planosContas()->where('padrao', 0)->get();
        $formas_pagamento = ContaReceber::formas_pagamento();
        $convenios = $instituicao->convenios()->get();

        return view('instituicao.contas_receber.criar', \compact('tipos', 'contas', 'planosConta', 'pacientes', 'formas_pagamento', 'convenios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarContaReceberRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_contas_receber');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $parcelas = null;
        $tipo_parcelamento = null;
        $num_parcelas = 0;

        $dados = $request->validated();

        $dados['num_parcela'] = 1;
        $dados['valor_parcela'] = str_replace('.','',$dados['valor_parcela']);
        $dados['valor_parcela'] = str_replace(',','.',$dados['valor_parcela']);

        $num_parcelas = $dados['num_parcelas'];
        $tipo_parcelamento = $dados['tipo_parcelamento'];
        unset($dados['num_parcelas']);
        unset($dados['tipo_parcelamento']);

        if($tipo_parcelamento){
            $parcelas = $dados['parcelas'];
            unset($dados['parcelas']);
        }

        $dados['valor_total'] = (float) $dados['valor_parcela'];

        if(!empty($parcelas) && $num_parcelas > 1){
            foreach($parcelas as $item){
                $dados['valor_total'] += str_replace(['.', ','], ['','.'],$item['valor']);
            }
        }

        // dd($dados);

        DB::transaction(function() use($dados, $instituicao, $request, $num_parcelas, $tipo_parcelamento, $parcelas){
            $contaReceber = $instituicao->contasReceber()->create($dados);

            $usuario_logado = $request->user('instituicao');
            $contaReceber->criarLogCadastro($usuario_logado, $instituicao->id);
            $contaReceber->update(['conta_pai' => $contaReceber->id]);

            if($tipo_parcelamento){
                for ($i=1; $i < $num_parcelas; $i++) { 
                    $dados['num_parcela'] = $i + 1;
                    $dados['data_vencimento'] = $parcelas[$i]['data_vencimento'];
                    $dados['valor_parcela'] = $parcelas[$i]['valor'];
                    $dados['valor_parcela'] = str_replace(['.', ','], ['','.'],$dados['valor_parcela']);
                    $dados['conta_pai'] = $contaReceber->id;

                    $instituicao->contasReceber()->create($dados);
                }
            }
            
        });
        
        return redirect()->route('instituicao.contasReceber.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Conta a receber cadastrada com sucesso!'
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
    public function edit(Request $request, ContaReceber $contaReceber)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_contas_receber');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($contaReceber->instituicao_id === $instituicao->id, 403);

        $paciente = $instituicao->instituicaoPessoas()->where('id', $contaReceber->pessoa_id)->first();
        $tipos = ContaReceber::tipos();
        $contas = $instituicao->contas()->get();
        $planosConta = $instituicao->planosContas()->where('padrao', 0)->get();
        $formas_pagamento = ContaReceber::formas_pagamento();
        $convenios = $instituicao->convenios()->get();

        return view('instituicao.contas_receber.editar', \compact('tipos', 'contas', 'planosConta', 'paciente', 'formas_pagamento', 'contaReceber', 'convenios'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditarContaReceberRequest $request, ContaReceber $contaReceber)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_contas_receber');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($contaReceber->instituicao_id === $instituicao->id, 403);

        $dados = $request->validated();

        $dados['valor_parcela'] = str_replace('.','',$dados['valor_parcela']);
        $dados['valor_parcela'] = str_replace(',','.',$dados['valor_parcela']);

        DB::transaction(function() use($dados, $instituicao, $request, $contaReceber){
            $contaReceber->update($dados);

            $usuario_logado = $request->user('instituicao');
            $contaReceber->criarLogEdicao($usuario_logado, $instituicao->id);
            
        });
        
        return redirect()->route('instituicao.contasReceber.edit', [$contaReceber])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Conta a receber editada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ContaReceber $contaReceber)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_contas_receber');
        $instituicaoId = $request->session()->get('instituicao');
        abort_unless($contaReceber->instituicao_id === $instituicaoId, 403);

        DB::transaction(function() use($instituicaoId, $contaReceber, $request){
            $contaReceber->delete();

            $usuario_logado = $request->user('instituicao');
            $contaReceber->criarLogExclusao($usuario_logado, $instituicaoId);
        });

        return redirect()->route('instituicao.contasReceber.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Conta a receber excluida com sucesso'
        ]);
    }

    public function getTipo(Request $request)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $tipo = $request->input('tipo');

        if($tipo == 'cliente'){
            $dados = $instituicao->clientes()->get();
        }else{
            $dados = $instituicao->vendasFinanceiroPesquisa()->get();
        }
        return response()->json($dados);
    }

    public function visualizarParcelas(Request $request, ContaReceber $contaReceber)
    {
        $this->authorize('habilidade_instituicao_sessao', 'receber_contas_receber');
        $instituicao_id = $request->session()->get('instituicao');
        $instituicao = Instituicao::find($instituicao_id);
        abort_unless($contaReceber->instituicao_id === $instituicao_id, 403);

        $conta = $contaReceber;
        $formas_pagamento = ContaReceber::formas_pagamento();
        // $formasRecebimento = $instituicao->formasRecebimento()->get();
        $caixas = $instituicao->contas()->get();
        $planosConta = $instituicao->planosContas()->where('padrao', 0)->get();

        return view('instituicao.contas_receber.receber_parcela', \compact('conta', 'formas_pagamento', 'caixas', 'planosConta'));
    }

    public function receberParcela(ReceberParcelaContaReceberRequest $request, ContaReceber $contaReceber)
    {
        $this->authorize('habilidade_instituicao_sessao', 'receber_contas_receber');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($contaReceber->instituicao_id === $instituicao->id, 403);
        $usuario_logado = $request->user('instituicao');

        $dados = $request->validated();

        $novaParcela = null;
        
        if($contaReceber->status == 0)
        {
            $dados['valor_pago'] = str_replace(['.', ','], ['', '.'],$dados['valor_pago']);

            if($dados['valor_pago'] < $contaReceber->valor_parcela && empty($dados['pagar_menor']) &&  empty($dados['data_vencimento'])){
                return response()->json(
                    [
                        'icon' => 'error',
                        'title' => 'Falha',
                        'text' => 'Quando o valor pago é menor que o valor você deve informar uma data de vencimento para o valor restante ou confirmar a baixa a menor'
                    ]
                );
            }

            $dados['status'] = 1;
            if(empty($dados['data_vencimento'])){
                unset($dados['data_vencimento']);
            }
            
            if($dados['valor_pago'] < $contaReceber->valor_parcela){
                if(empty($dados['pagar_menor'])){
                    $numPar = ($contaReceber->conta_pai) ? ContaReceber::where('conta_pai', $contaReceber->conta_pai)->count() : 2;

                    $novaParcela = [
                        "tipo" => $contaReceber->tipo,
                        "forma_pagamento" => $contaReceber->forma_pagamento,
                        'pessoa_id' => $contaReceber->pessoa_id,
                        'convenio_id' => $contaReceber->convenio_id,
                        "valor_total" =>  $contaReceber->valor_parcela - $dados['valor_pago'],
                        "valor_parcela" =>  $contaReceber->valor_parcela - $dados['valor_pago'],
                        "data_vencimento" => $contaReceber->data_vencimento,
                        "data_compensacao" => $contaReceber->data_compensacao,
                        "num_parcela" => $numPar,
                        "num_parcelas" => $numPar,
                        "tipo_parcelamento" => $contaReceber->tipo_parcelamento,
                        "descricao" => $contaReceber->descricao."  {$numPar}/{$numPar}",
                        "num_documento" => $contaReceber->num_documento,
                        "obs" => $contaReceber->obs,
                        "conta_id" => $contaReceber->conta_id,
                        "plano_conta_id" => $contaReceber->plano_conta_id,
                        'data_vencimento' => $dados['data_vencimento'],
                        'titular' => $contaReceber->titular,
                        'banco' => $contaReceber->banco,
                        'numero_cheque' => $contaReceber->numero_cheque,
                        'conta_pai' => ($contaReceber->conta_pai) ? $contaReceber->conta_pai : $contaReceber->id,
                    ];

                    $dados['descricao'] = $contaReceber->descricao." {$contaReceber->num_parcela}/{$numPar}";
                }
            }
            
        }else{
            unset($dados['valor_pago']);
            unset($dados['data_pago']);
            unset($dados['conta_id']);
            unset($dados['forma_pagamento']);
            unset($dados['data_vencimento']);
        }

        if(array_key_exists('desc_juros_multa', $dados)){
            $dados['desc_juros_multa'] = str_replace(['.', ','], ['', '.'],$dados['desc_juros_multa']);
        }

        $dados['usuario_baixou_id'] = $usuario_logado->id;
          
        DB::transaction(function() use($dados, $contaReceber, $usuario_logado, $instituicao, $novaParcela){
            $contaReceber->update($dados);

            if($novaParcela){
                $conta = $instituicao->contasReceber()->create($novaParcela);
                $conta->criarLogCadastro($usuario_logado, $instituicao->id);
            }

            $contaReceber->criarLogEdicao($usuario_logado, $instituicao->id);
        });

        return true;
    }

    public function estornarParcela(Request $request, ContaReceber $contaReceber)
    {
        $this->authorize('habilidade_instituicao_sessao', 'estornar_contas_receber');
        $instituicao_id = $request->session()->get('instituicao');
        abort_unless($contaReceber->instituicao_id === $instituicao_id, 403);
        $usuario_logado = $request->user('instituicao');

        if($contaReceber->status == 1){   
            
            $dados = [
                'status' => 0,
                'data_pago' => null,
                'valor_pago' => null,
            ];

            DB::transaction(function() use($dados, $contaReceber, $usuario_logado, $instituicao_id){
                $contaReceber->update($dados);
    
                $contaReceber->criarLogEdicao($usuario_logado, $instituicao_id);
            });

            return $contaReceber;
        }else{
            return;
        }

        

        return true;
    }

    public function printRecibo (Request $request, ContaReceber $conta){
        $conta_id = $conta->id;
        
        $conta = ContaReceber::where('id', $conta_id)
            ->with(['paciente','prestador', 'agendamentos'])
        ->first();

        $acompanhante = ($request->input('acompanhante') == 1) ? 1 : 0;

        $agendamento = ($request->input('agendamento') == 1) ? 1 : 0;

        // dd($conta, $agendamento);

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $config = json_decode($instituicao->config);

        if(!empty($conta->agendamento_id)){
            if(!empty($config->modelo_recibo->modelo_atendimento_id)){
                $modelo = ModeloRecibo::find($config->modelo_recibo->modelo_atendimento_id);
                
                $texto = "";
        
                for($i = 0; $i < $modelo->vias; $i++){
                    $texto .= $modelo->texto."<br><hr><br>";
                }

                $map = [
                    'nome_instituicao' => $instituicao->nome,
                    'cnpj_instituicao' => $instituicao->cnpj,
                    'valor_pago' => $conta->valor_pago,
                    'valor_extenso' => Outros::valorPorExtenso($conta->valor_pago),
                    'paciente_nome' => $conta->paciente->nome,
                    'paciente_cpf' => $conta->paciente->cpf,
                    'paciente_id' => $conta->paciente->id,
                    'paciente_data_nascimento' => ($conta->paciente->nascimento) ? date('d/m/Y', strtotime($conta->paciente->nascimento)) : '-',
                    'paciente_idade' => ($conta->paciente->nascimento) ? ConverteValor::calcularIdade($conta->paciente->nascimento) : '-',
                    'paciente_endereco' => "{$conta->paciente->rua} N° {$conta->paciente->numero} {$conta->paciente->cidade} / {$conta->paciente->estado} cep {$conta->paciente->cep}",
                    'data_pago' => $conta->data_pago,
                    'data' => date("d/m/y H:i", strtotime($conta->agendamentos->data)),
                ];

                $texto = replaceVariaveis($map, $texto);

                return view('instituicao.contas_receber.recibo', \compact('texto', 'modelo'));
            }else{
                return view('instituicao.contas_receber.recibo', \compact('conta', 'instituicao', 'acompanhante', 'agendamento'));
            }
        }else{
            if(!empty($config->modelo_recibo->modelo_receber_id)){
                $modelo = ModeloRecibo::find($config->modelo_recibo->modelo_receber_id);
                
                $texto = "";
        
                for($i = 0; $i < $modelo->vias; $i++){
                    $texto .= $modelo->texto."<br><hr><br>";
                }

                $map = [
                    'nome_instituicao' => $instituicao->nome,
                    'cnpj_instituicao' => $instituicao->cnpj,
                    'valor_pago' => $conta->valor_pago,
                    'valor_extenso' => Outros::valorPorExtenso($conta->valor_pago),
                    'paciente_nome' => $conta->paciente->nome,
                    'paciente_cpf' => $conta->paciente->cpf,
                    'paciente_id' => $conta->paciente->id,
                    'paciente_data_nascimento' => ($conta->paciente->nascimento) ? date('d/m/Y', strtotime($conta->paciente->nascimento)) : '-',
                    'paciente_idade' => ($conta->paciente->nascimento) ? ConverteValor::calcularIdade($conta->paciente->nascimento) : '-',
                    'paciente_endereco' => "{$conta->paciente->rua} N° {$conta->paciente->numero} {$conta->paciente->cidade} / {$conta->paciente->estado} cep {$conta->paciente->cep}",
                    'data_pago' => $conta->data_pago,
                    'data' => date("d/m/y H:i", strtotime($conta->agendamentos->data)),
                ];

                $texto = replaceVariaveis($map, $texto);

                return view('instituicao.contas_receber.recibo', \compact('texto', 'modelo'));
            }else{
                return view('instituicao.contas_receber.recibo', \compact('conta', 'instituicao', 'acompanhante', 'agendamento'));
            }
        }

        
    }

    public function getConvenios(Request $request)
    {
        if($request->ajax()){
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            $nome = ($request->input('q')) ? $request->input('q') : '';
            
            $convenios = $instituicao->convenios()->where('nome', 'like', "%{$nome}%")->simplePaginate(100);
            // dd($request->page);
            // $pacientes = $instituicao->instituicaoPessoas()->getPacientes($nome)->simplePaginate(100);
    
            $morePages=true;
            if (empty($convenios->nextPageUrl())){
                $morePages=false;
            }
    
            $results = array(
                "results" => $convenios->items(),
                "pagination" => array(
                    "more" => $morePages,
                )
            );

            // dd($pacientes->per_page());
            return response()->json($results);
        }
    }

    public function geraBoleto(Request $request, ContaReceber $conta_rec){
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        $apiBB = new ApiBB;
        $conta_rec->load('paciente');

        if(empty($conta_rec->apibb_numero)){
            if($instituicao->apibb_possui){
                $boleto = $apiBB->registrarBoleto($request, $conta_rec);
                // dd($boleto, $apiBB->getErros(), $apiBB->getBoleto());
                if(!empty($boleto['numero'])){
                    $dados_boleto = array(
                        'apibb_numero' => $boleto['numero'],
                        'apibb_linha_digitavel' => $boleto['linhaDigitavel'],
                        'apibb_codigo_barra_numerico' => $boleto['codigoBarraNumerico'],
                        'apibb_qrcode_url' => $boleto['qrCode']['url'],
                        'apibb_qrcode_tx_id' => $boleto['qrCode']['txId'],
                        'apibb_qrcode_emv' => $boleto['qrCode']['emv'],
                    );

                    DB::transaction(function() use($dados_boleto, $conta_rec, $instituicao){
                        $conta_rec->update($dados_boleto);
                        $usuario_logado = request()->user('instituicao');
                        $conta_rec->criarLogEdicao($usuario_logado, $instituicao->id);

                        return $conta_rec;
                    });

                    return $apiBB->printBoleto($conta_rec);
                }else{
                    return $boleto['erros'];
                }
                
            }
        }else{
            return $apiBB->printBoleto($conta_rec);
        }

    }
}
