<?php

namespace App\Http\Controllers\Instituicao;

use App\CartaoCredito;
use App\ContaPagar;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContasPagar\CriarContasPagarRequest;
use App\Http\Requests\ContasPagar\EditarContasPagarRequest;
use App\Http\Requests\ContasPagar\PagarParcelaContasRequest;
use App\Instituicao;
use App\ModeloRecibo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\Outros;

class ContasPagar extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_contas_pagar');
        return view('instituicao.contas_pagar.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_contas_pagar');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        // $pessoas = $instituicao->instituicaoPessoas()->get();
        // $prestadores = $instituicao->prestadores()->with('prestador')->get();
        $tipos = ContaPagar::tipos();
        $contas = $instituicao->contas()->get();
        $planosConta = $instituicao->planosContas()->where('padrao', 1)->get();
        $metodos_pagamento = ContaPagar::formas_pagamento();
        $centroCustos = $instituicao->centrosCustos()->get();
        $cartoes = $instituicao->cartoesCredito()->get();
        // $fornecedores = $instituicao->fornecedores()->get();


        // dd($prestadores->toArray());

        return view('instituicao.contas_pagar.criar', \compact('tipos', 'contas', 'planosConta', 'pessoas', 'prestadores', 'metodos_pagamento', 'centroCustos', 'cartoes', 'fornecedores'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarContasPagarRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_contas_pagar');
        $instituicao = instituicao::find($request->session()->get('instituicao'));
        // dd($request->validated());
        $parcelas = null;
        $tipo_parcelamento = null;
        $num_parcelas = 0;
        $tipo_divisao = null;

        $dados = $request->validated();
        $dados['nf_imposto'] = $request->boolean('nf_imposto');

        if($dados['tipo'] == 'fornecedor'){
            $dados['pessoa_id'] = $dados['fornecedor_id'];
        }

        $dados['num_parcela'] = 1;
        $dados['total'] = str_replace(['.', ','],['', '.'],$dados['total']);
        $dados['valor_pago'] = str_replace(['.', ','],['', '.'],$dados['valor_pago']);
        $dados['desc_juros_multa'] = str_replace(['.', ','],['', '.'],$dados['desc_juros_multa']);

        $dados['status'] = (!empty($dados['status'])) ? $dados['status'] : 0 ;

        $dados['data_pago'] = (!empty($dados['status'])) ? $dados['data_pago'] : null ;

        $num_parcelas = $dados['num_parcelas'];
        $tipo_parcelamento = $dados['tipo_parcelamento'];
        $tipo_divisao = $dados['tipo_divisao'];
        unset($dados['num_parcelas']);
        unset($dados['tipo_divisao']);
        unset($dados['tipo_parcelamento']);

        if($tipo_parcelamento){
            $parcelas = $dados['parcelas'];
            unset($dados['parcelas']);
        }

        unset($dados['cc'], $dados['fornecedor_id']);

        $cc = collect($request->validated()['cc'])
        ->filter(function ($cc) {
            return !is_null($cc['centro_custo_id']);
        })
        ->map(function ($cc){
            $valor = str_replace('.','',$cc['valor']);
            $valor = str_replace(',','.',$valor);
            return [
                'centro_custo_id' => $cc['centro_custo_id'],
                'valor' => $valor,
            ];
        });

        $total_cc = 0;

        foreach ($cc as $key => $value) {
            $total_cc += $value['valor'];
        }

        if($total_cc != $dados['total'] && $total_cc > 0){
            return redirect()->route('instituicao.contasPagar.index')->with('mensagem', [
                'icon' => 'error',
                'title' => 'Erro',
                'text' => 'Valor total centro de custo diferente do valor total!'
            ]);
        }
        
        DB::transaction(function() use($dados, $instituicao, $request, $num_parcelas, $tipo_parcelamento, $parcelas, $tipo_divisao, $cc){
            $usuario_logado = $request->user('instituicao');
            if(!empty($dados['status'])){
                $dados['usuario_baixou_id'] = $usuario_logado->id;
            }

            if(!empty($dados['duplicar'])){
                $duplicar = true;
                unset($dados['duplicar']);
            }else{
                $duplicar = false;
            }

            if($tipo_parcelamento){
                $idPai = 0;

                if($tipo_divisao == 'dividir'){

                    $valor_parcela = $dados['total']/$num_parcelas;

                    $valor_parcela = number_format($valor_parcela, 2, '.', '');

                    for ($i=0; $i < $num_parcelas; $i++) {
                        // $valor_parcela_utilizar = 0;
                        // if($i == 0){
                        //     $total_parcelas = $valor_parcela*$num_parcelas;

                        //     if($total_parcelas == $dados['total']){
                        //         $valor_parcela_utilizar = $valor_parcela;
                        //     }else if($total_parcelas > $dados['total']){
                        //         $valor_parcela_utilizar = $total_parcelas - $dados['total'];
                        //         $valor_parcela_utilizar = number_format($valor_parcela_utilizar, 2, '.', '');

                        //         $valor_parcela_utilizar = $valor_parcela - $valor_parcela_utilizar;
                        //     }else{
                        //         $valor_parcela_utilizar = $dados['total'] - $total_parcelas;
                        //         $valor_parcela_utilizar = number_format($valor_parcela_utilizar, 2, '.', '');
                        //         $valor_parcela_utilizar = $valor_parcela + $valor_parcela_utilizar;
                        //     }

                        // }else{
                        //     $valor_parcela_utilizar = $valor_parcela;
                        // }

                        $dados['num_parcela'] = $i+1;
                        $dados['data_vencimento'] = $parcelas[$i]['data_vencimento'];
                        $dados['valor_parcela'] = $parcelas[$i]['valor'];
                        $dados['valor_parcela'] = str_replace('.','',$dados['valor_parcela']);
                        $dados['valor_parcela'] = str_replace(',','.',$dados['valor_parcela']);

                        $contaPagar = $instituicao->contasPagar()->create($dados);
                        $contaPagar->criarLogCadastro($usuario_logado, $instituicao->id);

                        if($idPai == 0){
                            $idPai = $contaPagar->id;
                            $contaPagar->update(['conta_pai' => $idPai]);
                        }else{
                            $contaPagar->update(['conta_pai' => $idPai]);
                        }

                        $contaPagar->centroCusto()->attach($cc);

                        if($duplicar){
                            $dados['descricao'] = $dados['descricao']." (registro duplicado)";
                            $contaPagar = $instituicao->contasPagar()->create($dados);
                            $contaPagar->criarLogCadastro($usuario_logado, $instituicao->id);

                            if($idPai == 0){
                                $idPai = $contaPagar->id;
                                $contaPagar->update(['conta_pai' => $idPai]);
                            }else{
                                $contaPagar->update(['conta_pai' => $idPai]);
                            }

                            $contaPagar->centroCusto()->attach($cc);
                        }
                    }
                }else{
                    for ($i=0; $i < $num_parcelas; $i++) {
                        $dados['num_parcela'] = $i+1;
                        $dados['data_vencimento'] = $parcelas[$i]['data_vencimento'];
                        $dados['valor_parcela'] = $parcelas[$i]['valor'];
                        $dados['valor_parcela'] = str_replace('.','',$dados['valor_parcela']);
                        $dados['valor_parcela'] = str_replace(',','.',$dados['valor_parcela']);

                        $contaPagar = $instituicao->contasPagar()->create($dados);
                        $contaPagar->criarLogCadastro($usuario_logado, $instituicao->id);

                        if($idPai == 0){
                            $idPai = $contaPagar->id;
                            $contaPagar->update(['conta_pai' => $idPai]);
                        }else{
                            $contaPagar->update(['conta_pai' => $idPai]);
                        }

                        $contaPagar->centroCusto()->attach($cc);

                        if($duplicar){
                            $dados['descricao'] = $dados['descricao']." (registro duplicado)";
                            
                            $contaPagar = $instituicao->contasPagar()->create($dados);
                            $contaPagar->criarLogCadastro($usuario_logado, $instituicao->id);

                            if($idPai == 0){
                                $idPai = $contaPagar->id;
                                $contaPagar->update(['conta_pai' => $idPai]);
                            }else{
                                $contaPagar->update(['conta_pai' => $idPai]);
                            }

                            $contaPagar->centroCusto()->attach($cc);
                        }
                    }
                }
            }else{
                $dados['valor_parcela'] = $dados['total'];

                $contaPagar = $instituicao->contasPagar()->create($dados);

                $contaPagar->criarLogCadastro($usuario_logado, $instituicao->id);

                $contaPagar->centroCusto()->attach($cc);

                if($duplicar){
                    $dados['descricao'] = $dados['descricao']." (registro duplicado)";

                    $contaPagar = $instituicao->contasPagar()->create($dados);

                    $contaPagar->criarLogCadastro($usuario_logado, $instituicao->id);

                    $contaPagar->centroCusto()->attach($cc);
                }
            }

        });

        return redirect()->route('instituicao.contasPagar.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Conta a pagar cadastrada com sucesso!'
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
    public function edit(Request $request, ContaPagar $contaPagar)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_contas_pagar');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($contaPagar->instituicao_id === $instituicao->id, 403);

        $pessoa = $instituicao->instituicaoPessoas()->where('id', $contaPagar->pessoa_id)->first();
        $prestador = $contaPagar->prestador()->first();
        $tipos = ContaPagar::tipos();
        $contas = $instituicao->contas()->get();
        $planosConta = $instituicao->planosContas()->where('padrao', 1)->get();
        $metodos_pagamento = ContaPagar::formas_pagamento();
        $contasPagar = null;
        $centroCustos = $instituicao->centrosCustos()->get();
        $cartoes = $instituicao->cartoesCredito()->get();
        $fornecedor = $instituicao->fornecedores()->where('id', $contaPagar->pessoa_id)->first();

        if($contaPagar->conta_pai){
            $contasPagar = ContaPagar::getAllParcelas($contaPagar->conta_pai)->get();
        }



        return view('instituicao.contas_pagar.editar', \compact('tipos', 'contas', 'planosConta', 'pessoa', 'prestador', 'metodos_pagamento', 'contaPagar', 'contasPagar', 'centroCustos', 'cartoes', 'fornecedor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditarContasPagarRequest $request, ContaPagar $contaPagar)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_contas_pagar');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($contaPagar->instituicao_id === $instituicao->id, 403);

        $dados = $request->validated();
        $dados['nf_imposto'] = $request->boolean('nf_imposto');


            $dados['valor_parcela'] = str_replace('.','',$dados['valor_parcela']);
            $dados['valor_parcela'] = str_replace(',','.',$dados['valor_parcela']);
            $dados['total'] = str_replace('.','',$dados['total']);
            $dados['total'] = str_replace(',','.',$dados['total']);


        unset($dados['cc']);
        $total_cc = 0;

        $cc = collect($request->validated()['cc'])
        ->filter(function ($cc){
            return !is_null($cc['centro_custo_id']);
        })
        ->map(function ($cc){
            $valor = str_replace('.','',$cc['valor']);
            $valor = str_replace(',','.',$valor);
            return [
                'centro_custo_id' => $cc['centro_custo_id'],
                'valor' => $valor,
            ];
        });

        foreach ($cc as $key => $value) {
            $total_cc += $value['valor'];
        }

        if($total_cc != $dados['total'] && $total_cc > 0){
            return redirect()->route('instituicao.contasPagar.edit', [$contaPagar])->with('mensagem', [
                'icon' => 'error',
                'title' => 'Erro',
                'text' => 'Valor total centro de custo diferente do valor total!'
            ]);
        }

        DB::transaction(function() use($dados, $instituicao, $request, $contaPagar, $cc){
            $contaPagar->update($dados);

            $contaPagar->centroCusto()->detach();
            $contaPagar->centroCusto()->attach($cc);

            $usuario_logado = $request->user('instituicao');
            $contaPagar->criarLogEdicao($usuario_logado, $instituicao->id);

        });

        return redirect()->route('instituicao.contasPagar.edit', [$contaPagar])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Conta a pagar editada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ContaPagar $contaPagar)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_contas_pagar');
        $instituicao = $request->session()->get('instituicao');
        abort_unless($contaPagar->instituicao_id === $instituicao, 403);

        DB::transaction(function() use($instituicao, $contaPagar, $request){
            if (empty($contaPagar->conta_pai)) {
                $contaPagar->delete();

                $usuario_logado = $request->user('instituicao');
                $contaPagar->criarLogExclusao($usuario_logado, $instituicao);
            }else{
                $contaPagar->delete();

                $usuario_logado = $request->user('instituicao');

                if($request->input('excluir_'.$contaPagar->id) == 1){
                    ContaPagar::where('conta_pai', $contaPagar->conta_pai)->delete();

                    $contaPagar->criarLog($usuario_logado, 'Exclusão', 'exclusão da conta e de todas as contas pai', $instituicao);
                }else{
                    $contaPagar->criarLogExclusao($usuario_logado, $instituicao);
                }

            }
        });

        return redirect()->route('instituicao.contasPagar.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Conta a pagar excluida com sucesso'
        ]);
    }

    public function getTipo(Request $request)
    {
        $instituicao = Instituicao::find($request->session()->get('insitituicao'));
        $tipo = $request->input('tipo');

        if($tipo == 'fornecedor'){
            $dados = $instituicao->prestadores()->get();
        }else{
            $dados = $instituicao->pessoas()->get();
        }
        return response()->json($dados);
    }

    public function getCartao(Request $request)
    {
        $instituicao = Instituicao::find($request->session()->get('insitituicao'));
        $cartao_id = $request->input('cartao_id');

        //echo $cartao_id;

        $dados = CartaoCredito::where('id', $cartao_id)->first();

        if($dados['vencimento'] < date('d')){
            $dados['data_vencimento'] = date("Y-m-").str_pad($dados['vencimento'], 2, 0, STR_PAD_LEFT);
        }else{
            if(date("m") == 12){
                $dados['data_vencimento'] = (date("Y") + 1) . "-01-" . str_pad($dados['vencimento'], 2, 0, STR_PAD_LEFT);
            }else{
                $dados['data_vencimento'] = date("Y-") . str_pad((date("m") + 1), 2, 0, STR_PAD_LEFT) . "-". str_pad($dados['vencimento'], 2, 0, STR_PAD_RIGHT);
            }
        }

       return response()->json($dados);
    }

    public function pagarParcela(Request $request, ContaPagar $conta)
    {
        $this->authorize('habilidade_instituicao_sessao', 'pagar_contas_pagar');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($conta->instituicao_id === $instituicao->id, 403);

        $metodo_pagamento = ContaPagar::formas_pagamento();
        $contas = $instituicao->contas()->get();
        $planos_conta = $instituicao->planosContas()->where('padrao', 1)->get();
        $centro_custos = $instituicao->centrosCustos()->orderBy("codigo", "ASC")->get();

        return view('instituicao.contas_pagar.pagar_parcela', \compact('conta', 'contas', 'metodo_pagamento', 'planos_conta', 'centro_custos'));
    }

    public function contaPagar(PagarParcelaContasRequest $request, ContaPagar $conta)
    {
        $this->authorize('habilidade_instituicao_sessao', 'pagar_contas_pagar');
        $instituicaoId = $request->session()->get('instituicao');
        abort_unless($conta->instituicao_id === $instituicaoId, 403);

        $usuario_logado = $request->user('instituicao');

        $dados = $request->validated();
        $novaParcela = null;

        if($conta->status == 0)
        {

            $dados['valor_pago'] = str_replace('.','',$dados['valor_pago']);
            $dados['valor_pago'] = str_replace(',','.',$dados['valor_pago']);

            $dados['status'] = 1;
            if(empty($dados['data_vencimento'])){
                unset($dados['data_vencimento']);
            }

            if($dados['valor_pago'] < $conta->valor_parcela){
                if(empty($dados['pagar_menor'])){
                    $numPar = ($conta->conta_pai) ? ContaPagar::where('conta_pai', $conta->conta_pai)->count() : 2;

                    $novaParcela = [
                        'pessoa_id' => ($conta->pessoa_id) ? $conta->pessoa_id : null,
                        'num_parcela' => $numPar,
                        'data_vencimento' => $dados['data_vencimento'],
                        'valor_parcela' => $conta->valor_parcela - $dados['valor_pago'],
                        'status' => 0,
                        'forma_pagamento' => $conta->forma_pagamento,
                        'descricao' => $conta->descricao."  {$numPar}/{$numPar}",
                        'conta_id' => ($conta->conta_id) ? $conta->conta_id : null,
                        'plano_conta_id' => ($conta->plano_conta_id) ? $conta->plano_conta_id : null,
                        'prestador_id' => ($conta->prestador_id) ? $conta->prestador_id : null,
                        'tipo' => $conta->tipo,
                        'conta_pai' => ($conta->conta_pai) ? $conta->conta_pai : $conta->id,
                        'total' => $conta->total,
                        'data_emissao_nf' => $conta->data_emissao_nf,
                        'nf_imposto' => $conta->nf_imposto,
                        'cartao_credito_id' => $conta->cartao_credito_id,
                        'data_compra_cartao' => $conta->data_compra_credito,
                        'instituicao_id' => $conta->instituicao_id,
                    ];

                    $dados['descricao'] = $conta->descricao." {$conta->num_parcela}/{$numPar}";
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
            $dados['desc_juros_multa'] = str_replace('.','',$dados['desc_juros_multa']);
            $dados['desc_juros_multa'] = str_replace(',','.',$dados['desc_juros_multa']);
        }

       
       $dados['usuario_baixou_id'] = $usuario_logado->id;
       

       DB::transaction(function() use($dados, $conta, $usuario_logado, $instituicaoId, $novaParcela){

            $conta->update($dados);

            if($novaParcela){
                $conta->create($novaParcela);
                $conta->criarLogCadastro($usuario_logado, $instituicaoId);
            }

            $conta->criarLogEdicao($usuario_logado, $instituicaoId);
        });

        return true;
    }

    public function printRecibo (Request $request, ContaPagar $conta){
        $conta_id = $conta->id;

        $conta = ContaPagar::where('id', $conta_id)
            ->with(['paciente','prestador','fornecedor'])
        ->first();
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $config = json_decode($instituicao->config);

        if(!empty($config->modelo_recibo->modelo_pagar_id)){
                $modelo = ModeloRecibo::find($config->modelo_recibo->modelo_pagar_id);
                
                $texto = "";
        
                for($i = 0; $i < $modelo->vias; $i++){
                    $texto .= $modelo->texto."<br><hr><br>";
                }

                $map = [
                    'nome_instituicao' => $instituicao->nome,
                    'cnpj_instituicao' => $instituicao->cnpj,
                    'valor_pago' => $conta->valor_pago,
                    'valor_extenso' => Outros::valorPorExtenso($conta->valor_pago),
                    'fornecedor_nome' => (!empty($conta->fornecedor->nome)) ? $conta->fornecedor->nome  : "",
                    'fornecedor_cnpj' => (!empty($conta->fornecedor->cnpj)) ? $conta->fornecedor->cnpj : "",
                    'prestador_cpf' => (!empty($conta->pprestador->cpf)) ? $conta->pprestador->cpf : "",
                    'prestador_nome' => (!empty($conta->prestador->nome)) ? $conta->prestador->nome : "",
                    'data_pago' => $conta->data_pago,
                ];

                $texto = replaceVariaveis($map, $texto);

                return view('instituicao.contas_pagar.recibo', \compact('texto', 'modelo'));
            }else{
                return view('instituicao.contas_pagar.recibo', \compact('conta', 'instituicao'));
            }

        
    }

    public function getPacientes(Request $request)
    {
        if($request->ajax()){
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            $nome = ($request->input('q')) ? $request->input('q') : '';
            
            // dd($request->page);
            $pacientes = $instituicao->instituicaoPessoas()->getPacientes($nome)->simplePaginate(100);
    
            $morePages=true;
            if (empty($pacientes->nextPageUrl())){
                $morePages=false;
            }
    
            $results = array(
                "results" => $pacientes->items(),
                "pagination" => array(
                    "more" => $morePages,
                )
            );
            // dd($pacientes->per_page());
            return response()->json($results);
        }
    }

    public function getFornecedores(Request $request)
    {
        if($request->ajax()){
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            $nome = ($request->input('q')) ? $request->input('q') : '';
            
            $fornecedores = $instituicao->instituicaoPessoas()->fornecedores()
                ->where(function($q) use($nome){
                    $q->where('nome', 'like', "%{$nome}%");
                    $q->orWhere('nome_fantasia', 'like', "%{$nome}%");
                    $q->orWhere('razao_social', 'like', "%{$nome}%");
                })
                ->where('instituicao_id', $instituicao->id)
            ->simplePaginate(100);
            
            // dd($request->page);
            // $pacientes = $instituicao->instituicaoPessoas()->getPacientes($nome)->simplePaginate(100);
    
            $morePages=true;
            if (empty($fornecedores->nextPageUrl())){
                $morePages=false;
            }
    
            $results = array(
                "results" => $fornecedores->items(),
                "pagination" => array(
                    "more" => $morePages,
                )
            );

            // dd($pacientes->per_page());
            return response()->json($results);
        }
    }

    public function getPrestadores(Request $request)
    {
        if($request->ajax()){
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            $nome = ($request->input('q')) ? $request->input('q') : '';
            
            $prestadores = $instituicao->prestadores()
                ->whereHas('prestador', function ($query) use ($nome) {
                    $query->where('nome', 'like', "%{$nome}%");
                })
                ->with('prestador')
                ->simplePaginate(100);
               
            $morePages=true;
            if (empty($prestadores->nextPageUrl())){
                $morePages=false;
            }
    
            $results = array(
                "results" => $prestadores->items(),
                "pagination" => array(
                    "more" => $morePages,
                )
            );
            
            return response()->json($results);
        }
    }

    public function estornarParcela(Request $request, ContaPagar $contaPagar)
    {
        $this->authorize('habilidade_instituicao_sessao', 'estornar_contas_pagar');
        $instituicao_id = $request->session()->get('instituicao');
        abort_unless($contaPagar->instituicao_id === $instituicao_id, 403);
        $usuario_logado = $request->user('instituicao');

        if($contaPagar->status == 1){   
            
            $dados = [
                'status' => 0,
                'data_pago' => null,
                'valor_pago' => null,
            ];

            DB::transaction(function() use($dados, $contaPagar, $usuario_logado, $instituicao_id){
                $contaPagar->update($dados);
    
                $contaPagar->criarLogEdicao($usuario_logado, $instituicao_id);
            });

            return $contaPagar;
        }else{
            return;
        }

        

        return true;
    }
}
