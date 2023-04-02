<?php

namespace App\Http\Controllers\Instituicao;

use App\Agendamentos As Agendamento;
use App\CodTerritorialIbge;
use App\ContaReceber;
use App\Http\Controllers\Controller;
use App\Http\Requests\NotasFiscais\CreateNotaFiscalRequest;
use App\Instituicao;
use App\NfeEnotas;
use App\NotaFiscal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\App;

use function Deployer\input;

class NotasFiscais extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_nota_fiscal');
        return view('instituicao.nota_fiscal.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_nota_fiscal');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $config_fiscal = $instituicao->configuracaoFiscal()->first();

        return view('instituicao.nota_fiscal.criar', compact('config_fiscal'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateNotaFiscalRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_nota_fiscal');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $config_fiscal = $instituicao->configuracaoFiscal()->first();
        
        $dados = $request->validated();
        
        $dados['valor_total'] = str_replace([".", ","], ['', '.'], $dados['valor_total']);
        $dados['deducoes'] = str_replace([".", ","], ['', '.'], $dados['deducoes']);

        $conta = ContaReceber::whereIn('id', $dados['contas_receber'])
            ->with(['notaFiscal' => function($q){
                $q->whereNotIn('status', ['Cancelada']);
                $q->orWhereNull('status');
            }])
        ->get();

        if($conta[0]->notaFiscal === null){
            $dados_nota = [
                'pessoa_id' => $dados['pessoa_id'],
                'aliquota_iss' => $config_fiscal->aliquota_iss,
                'valor_iis' => $dados['valor_total'] * ($config_fiscal->aliquota_iss/100),
                'iss_retido_fonte' => ($config_fiscal->iss) ? true : false,
                'cnae' => $config_fiscal->cnae,
                'valor_pis' => $dados['valor_total'] * ($config_fiscal->p_pis/100),
                'p_pis' => $config_fiscal->p_pis,
                'valor_cofins' => $dados['valor_total'] * ($config_fiscal->p_cofins/100),
                'p_cofins' => $config_fiscal->p_cofins,
                'valor_inss' => $dados['valor_total'] * ($config_fiscal->p_inss/100),
                'p_inss' => $config_fiscal->p_inss,
                'valor_ir' => $dados['valor_total'] * ($config_fiscal->p_ir/100),
                'p_ir' => $config_fiscal->p_ir,
                'uf_prestacao_servico'=> $instituicao->estado,
                'municipio_prestacao_servico' => $instituicao->cidade,
                'descricao' => $dados['descricao'],
                'cod_servico_municipal' => $config_fiscal->cod_servico_municipal,
                'descricao_servico_municipal' => $config_fiscal->descricao,
                'natureza_operacao' => "Prestação de serviço",
                'deducoes' => 0,
                'descontos' => $dados['deducoes'],
                'valor_total' => $dados['valor_total'],
                'observacoes' => (!empty($dados['observacao'])) ? $dados['observacao'] : null,
                "cliente_nome" => $conta[0]->paciente->nome,
                "cliente_email" => $conta[0]->paciente->email,
                "cliente_cpfCnpj"  => preg_replace("/[^0-9]/", "", ($conta[0]->paciente->personalidade == 1) ? $conta[0]->paciente->cpf : $conta[0]->paciente->cnpj),
                "cliente_inscricaoMunicipal" => $conta[0]->paciente->inscricao_municipal,
                "cliente_inscricaoEstadual" => $conta[0]->paciente->inscricao_estadual,
                "cliente_telefone" =>  preg_replace("/[^0-9]/", "", $conta[0]->paciente->telefone1),
                
                "cliente_pais" => "brasil",
                "cliente_uf" => $conta[0]->paciente->estado,
                "cliente_cidade" => $conta[0]->paciente->cidade,
                "cliente_logradouro" => $conta[0]->paciente->rua,
                "cliente_numero" => $conta[0]->paciente->numero,
                "cliente_complemento" => $conta[0]->paciente->complemento,
                "cliente_bairro" => $conta[0]->paciente->bairro,
                "cliente_cep" => $conta[0]->paciente->cep,
                'contas_receber_id' => json_encode($dados['contas_receber']),
            ];

            if(empty($conta[0]->paciente->estado || $conta[0]->paciente->cidade || $conta[0]->paciente->rua || $conta[0]->paciente->numero || $conta[0]->paciente->complemento || $conta[0]->paciente->bairro || $conta[0]->paciente->cep)){
                return redirect()->route('instituicao.notasFiscais.index')->with('mensagem', [
                    'icon' => 'error',
                    'title' => 'Falha.',
                    'text' => 'Endereço do cliente auxente!'
                ]);
            }

            // dd($dados_nota);

            $nota = DB::transaction(function () use($dados_nota, $request, $instituicao, $conta) {
                $nota = $instituicao->notasFiscais()->create($dados_nota);
                $nota->criarLogCadastro($request->user('instituicao'), $instituicao->id);
                
                foreach($conta as $item){
                    $item->update(['nota_id' => $nota->id]);
                }

                // $conta->update(['nota_id' => $nota->id]);
                
                return $this->emitirNfe($request, $nota);
            });

            // dd($nota);

            if(!empty($nota->data['icon']) && $nota->data['icon'] == "error"){
                return response()->json($nota);
            }else{
                return redirect()->route('instituicao.notasFiscais.index')->with('mensagem', [
                    'icon' => 'success',
                    'title' => 'Sucesso.',
                    'text' => 'Nota fiscal registrada com sucesso!'
                ]);
            }
        }else{
            return redirect()->back()->withInput($dados)->with('mensagem', [
                'icon' => 'error',
                'title' => 'Falha',
                'text' => 'Conta as receber já vindulado a uma nota fiscal'
            ]);
        }
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
    public function edit(Request $request, NotaFiscal $nota)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_nota_fiscal');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $config_fiscal = $instituicao->configuracaoFiscal()->first();
        
        $contas_receber = ContaReceber::whereIn("id", json_decode($nota->contas_receber_id, true))->get();

        abort_unless($instituicao->id === $nota->instituicao_id, 403);

        return view('instituicao.nota_fiscal.editar', compact('nota', 'config_fiscal', 'contas_receber'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateNotaFiscalRequest $request, NotaFiscal $nota)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_nota_fiscal');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $config_fiscal = $instituicao->configuracaoFiscal()->first();
        
        $dados = $request->validated();
        

        $dados['valor_total'] = str_replace([".", ","], ['', '.'], $dados['valor_total']);
        $dados['deducoes'] = str_replace([".", ","], ['', '.'], $dados['deducoes']);

        $conta = ContaReceber::find($dados['contas_receber_id']);

        $dados_nota = [
            'contas_receber_id' => $dados['contas_receber_id'],
            'pessoa_id' => $dados['pessoa_id'],
            'aliquota_iss' => $config_fiscal->aliquota_iss,
            'valor_iis' => $dados['valor_total'] * ($config_fiscal->aliquota_iss/100),
            'iss_retido_fonte' => ($config_fiscal->iss) ? true : false,
            'cnae' => $config_fiscal->cnae,
            'valor_pis' => $dados['valor_total'] * ($config_fiscal->p_pis/100),
            'p_pis' => $config_fiscal->p_pis,
            'valor_cofins' => $dados['valor_total'] * ($config_fiscal->p_cofins/100),
            'p_cofins' => $config_fiscal->p_cofins,
            'valor_inss' => $dados['valor_total'] * ($config_fiscal->p_inss/100),
            'p_inss' => $config_fiscal->p_inss,
            'valor_ir' => $dados['valor_total'] * ($config_fiscal->p_ir/100),
            'p_ir' => $config_fiscal->p_ir,
            'uf_prestacao_servico'=> $instituicao->estado,
            'municipio_prestacao_servico' => $instituicao->cidade,
            'descricao' => $dados['descricao'],
            'cod_servico_municipal' => $config_fiscal->cod_servico_municipal,
            'descricao_servico_municipal' => $config_fiscal->descricao,
            'natureza_operacao' => "Prestação de serviço",
            'deducoes' => 0,
            'descontos' => $dados['deducoes'],
            'valor_total' => $dados['valor_total'],
            'observacoes' => (!empty($dados['observacao'])) ? $dados['observacao'] : null,
            "cliente_nome" => $conta->paciente->nome,
            "cliente_email" => $conta->paciente->email,
            "cliente_cpfCnpj"  => preg_replace("/[^0-9]/", "", ($conta->paciente->personalidade == 1) ? $conta->paciente->cpf : $conta->paciente->cnpj),
            "cliente_inscricaoMunicipal" => $conta->paciente->inscricao_municipal,
            "cliente_inscricaoEstadual" => $conta->paciente->inscricao_estadual,
            "cliente_telefone" =>  preg_replace("/[^0-9]/", "", $conta->paciente->telefone1),
            
            "cliente_pais" => "brasil",
            "cliente_uf" => $conta->paciente->estado,
            "cliente_cidade" => $conta->paciente->cidade,
            "cliente_logradouro" => $conta->paciente->rua,
            "cliente_numero" => $conta->paciente->numero,
            "cliente_complemento" => $conta->paciente->complemento,
            "cliente_bairro" => $conta->paciente->bairro,
            "cliente_cep" => $conta->paciente->cep,
        ];

        $nota = DB::transaction(function () use($dados_nota, $request, $instituicao, $nota) {
            $nota->update($dados_nota);
            $nota->criarLogEdicao($request->user('instituicao'), $instituicao->id);

            return $this->emitirNfe($request, $nota);
        });

        // dd($nota);

        if(!empty($nota->data['icon']) && $nota->data['icon'] == "error"){
            return response()->json($nota);
        }else{
            return redirect()->route('instituicao.notasFiscais.edit', [$nota])->with('mensagem', [
                'icon' => 'success',
                'title' => 'Sucesso.',
                'text' => 'Nota fiscal editada com sucesso!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, NotaFiscal $nota)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_nota_fiscal');
       

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $nota->instituicao_id, 403);

        DB::transaction(function () use ($nota, $request, $instituicao) {
            $nota->update(['status' => "Cancelada"]);
            $nota->delete();
            $nota->criarLogExclusao($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.notasFiscais.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Nota fiscal excluída com sucesso!'
        ]);
    }

    public function pesquisaContaReceber(Request $request){
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        $formaPagamentos = ContaReceber::formas_pagamento();
        $contas = $instituicao->contas()->get();
        $planosConta = $instituicao->planosContas()->get();
        $tipos = ContaReceber::tipos();
        return view("instituicao.nota_fiscal.modal_contas_receber", compact('formaPagamentos', 'contas', 'planosConta', 'tipos'));
    }

    public function pesquisarContaReceber(Request $request){
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        $dados = $request->input();

        // dd($dados);

        $formaPagamento = (!empty($dados['forma_pagamento_id'])) ? $dados['forma_pagamento_id'] : "";
        $tipo = 'paciente';
        $status = $dados['status_id'];
        $search = (!empty($dados['search'])) ? $dados['search'] : '';
        $tipo_id = (!empty($dados['tipo_id'])) ? $dados['tipo_id'] : 0;
        $data_inicio = (!empty($dados['data_inicio'])) ? $dados['data_inicio'] : '';
        $data_fim = (!empty($dados['data_fim'])) ? $dados['data_fim'] : '';
        $conta_id = (!empty($dados['conta_id'])) ? $dados['conta_id'] : 0;
        $plano_conta_id = (!empty($dados['plano_conta_id'])) ? $dados['plano_conta_id'] : 0;

        $query = $instituicao->contasReceber()
            ->searchFinanceiro($formaPagamento, $status, $tipo, $search, $tipo_id, $data_inicio, $data_fim, $conta_id, $plano_conta_id)
            ->with([
                'paciente' => function($q){
                    $q->withTrashed();
                }
            ]);
        
        $contas_receber = $query->orderBy('data_vencimento', "DESC")->get();
        
        return view("instituicao.nota_fiscal.tabela_contas_receber", compact('contas_receber'));
    }

    public function emitirNfe(Request $request, NotaFiscal $nota){
        $instituicao_id = $request->session()->get('instituicao');
        $instituicao = Instituicao::find($instituicao_id);
        // $conta = $instituicao->contasReceber()->find($nota->contas_receber_id);
        abort_unless($nota->instituicao_id === $instituicao_id, 403);
        
        $usuario_logado = $request->user('instituicao');        
        
        if($instituicao->id_enotas === null){
            // id da instituiçaõ 7 no e-notas é 903af21a-f400-4696-8b78-b65148810800
            $instituicao->id_enotas = $this->setEmpresa($instituicao_id);
        }

        // dd($this->setLogo($instituicao->id));
        $link = NfeEnotas::$url."/empresas/{$instituicao->id_enotas}/nfes";

        // $conta_id = (!empty($conta->conta_pai)) ? $conta->conta_pai : $conta->id;
        
        $config_fiscal = $instituicao->configuracaoFiscal()->first();

        if(empty($config_fiscal)){
            return json_encode([
                'icon' => 'error',
                'title' => 'Falha.',
                'text' => "Erros encontrados na requisição!",
                'errors' => array(['mensagem' => 'Configuracoes fiscais não cadastradas'])
            ]);
        }

        $conta = $nota->contaReceber()->get();
        
        // dd($nota->paciente->estado);

        $dados = [
            "idExterno" => (string) $nota->id,
            "ambienteEmissao" => $config_fiscal->ambiente,
            "enviarPorEmail" => true,
            "cliente" => [
                "endereco" => [
                    "pais" => "brasil",
                    "uf" => $nota->paciente->estado,
                    "cidade" => $nota->paciente->cidade,
                    "logradouro" => $nota->paciente->rua,
                    "numero" => $nota->paciente->numero,
                    "complemento" => $nota->paciente->complemento,
                    "bairro" => $nota->paciente->bairro,
                    "cep" => preg_replace("/[^0-9]/", "", $nota->paciente->cep),
                ],
                "tipoPessoa" => ($nota->paciente->personalidade == 1) ? "F" : "J",
                "nome" => $nota->paciente->nome,
                "email" => $nota->paciente->email,
                "cpfCnpj"  => preg_replace("/[^0-9]/", "", ($nota->paciente->personalidade == 1) ? $nota->paciente->cpf : $nota->paciente->cnpj),
                "inscricaoMunicipal" => $nota->paciente->inscricao_municipal,
                "inscricaoEstadual" => $nota->paciente->inscricao_estadual,
                "telefone" => preg_replace("/[^0-9]/", "", $nota->paciente->telefone1),
            ],
            "servico" => [
                "descricao" => $config_fiscal->descricao,
                "issRetidoFonte" => ($config_fiscal->fiscal) ? true : false,
                "valorCofins" => (float) $nota->valor_cofins,
                "valorCsll" => 0,
                "valorInss" => (float) $nota->valor_inss,
                "valorIr" => (float) $nota->valor_ir,
                "valorPis" => (float) $nota->valor_pis
            ],
            'deducoes' => (float) $nota->deducoes,
            'descontos' => (float) $nota->descontos,
            "valorTotal" => (float) $nota->valor_total,
            "observacoes" => $nota->observacoes,
        ];

        // dd(json_encode($dados));
        
        // if($nota->id_nfse_enotas === null){
            
            $conn = Http::withBody(json_encode($dados), 'application/json')->withHeaders(['Authorization' => "Basic ".NfeEnotas::$api_key])->acceptJson()->post($link);

            if($conn->ok()){
                // dd($link, $conn, $conn->body());
                $nota->update(['id_nfse_enotas' => $conn->json()['nfeId']]);
                $getNota = $this->getStatus($request, $nota);
                return json_encode(
                    [
                        'icon' => 'success',
                        'title' => 'Sucesso.',
                        'text' => "Nota fiscal emitida com sucesso!",
                    ]
                );   
            }else{
                return json_encode(
                    [
                        'icon' => 'error',
                        'title' => 'Falha.',
                        'text' => "Erros encontrados na requisição!",
                        'errors' => $conn->json()
                    ]
                );   
            }
        // }
    }

    private function getEmpresa($empresa_id){
        $link = NfeEnotas::$url."/empresas/".$empresa_id;

        $conn = Http::withHeaders(['Authorization' => "Basic ".NfeEnotas::$api_key])->acceptJson()->get($link);

        // dd($link, $conn, $conn->body());
    }

    private function setEmpresa(int $instituicao_id){
        $link = NfeEnotas::$url."/empresas/";

        $instituicao = Instituicao::find($instituicao_id);

        $cod_ibge = CodTerritorialIbge::getCodMunicipio($instituicao->estado, $instituicao->cidade)->first();
        $config_fiscal = $instituicao->configuracaoFiscal()->first();

        if(!$config_fiscal){
            return response()->json([
                'icon' => 'error',
                'title' => 'Falha.',
                'text' => "Erros encontrados na requisição!",
                'errors' => ['Configuracoes fiscais não cadastradas']
            ]);
        }else{
            $dados = [
                'cnpj' => str_replace([".","-","/"], ["","",""], $instituicao->cnpj),
                'inscricaoMunicipal' => $instituicao->inscricao_municipal,
                'inscricaoEstadual' => $instituicao->inscricao_estadual,
                'razaoSocial' => $instituicao->razao_social,
                "nomeFantasia" => $instituicao->nome,
                "optanteSimplesNacional" => ($config_fiscal->regime == 'simples_nacional') ? true : false,
                "email" => $instituicao->email,
                "enviarEmailCliente" => true,
                "telefoneComercial" => preg_replace("/[^0-9]/", "", $instituicao->telefone),
                "incentivadorCultural" => false,
                "endereco" => [
                    "codigoIbgeUf" => $cod_ibge->cod_uf,
                    "codigoIbgeCidade" => $cod_ibge->cod_municipio,
                    "pais" => "Brasil",
                    "uf" => $instituicao->estado,
                    "cidade" => $instituicao->cidade,
                    "logradouro" => $instituicao->rua,
                    "numero" => $instituicao->numero,
                    "complemento" => $instituicao->complemento,
                    "bairro" => $instituicao->bairro,
                    "cep" => preg_replace("/[^0-9]/", "", $instituicao->cep)
                ],
                "regimeEspecialTributacao" => (string) $config_fiscal->regime_especial_tributacao,
                "codigoServicoMunicipal" => $config_fiscal->cod_servico_municipal,
                "itemListaServicoLC116" => $config_fiscal->item_lista_servicos,
                "cnae" => $config_fiscal->cnae,
                "aliquotaIss" => $config_fiscal->aliquota_iss,
                "descricaoServico" => $config_fiscal->descricao,
                "configuracoesNFSeHomologacao" => [
                    "sequencialNFe" => 1,
                    "serieNFe" => "NF",
                    "sequencialLoteNFe" => 1,
                    "usuarioAcessoProvedor" => $config_fiscal->usuario,
                    "senhaAcessoProvedor" => $config_fiscal->senha,
                    "tokenAcessoProvedor" => null
                ],
                "configuracoesNFSeProducao" => [
                    "sequencialNFe" => 1,
                    "serieNFe" => "NF",
                    "sequencialLoteNFe" => 1,
                    "usuarioAcessoProvedor" => $config_fiscal->usuario,
                    "senhaAcessoProvedor" => $config_fiscal->senha,
                    "tokenAcessoProvedor" => null
                ]
            ];

            if(!empty($instituicao->id_enotas)){
                $dados = array('id' => $instituicao->id_enotas)+$dados;
            }

            $conn = Http::withHeaders(['Authorization' => "Basic ".NfeEnotas::$api_key])->acceptJson()->post($link, $dados);

            if($conn->status() == 200){
                $id_empresa = json_decode($conn->body())->empresaId;
                $instituicao->update(['id_enotas' => $id_empresa]);
                if(!empty($instituicao->imagem)){
                    // $this->setLogo($instituicao->id);
                }

                if(!empty($config_fiscal->certificado)){
                    $cert = $this->setCertificado($instituicao->id);
                    if($cert !== true){
                        return $cert;
                    }
                }                
                
                return $id_empresa;
            }else{
                return [
                    'icon' => 'error',
                    'title' => 'Falha.',
                    'text' => "Erros encontrados na requisição!",
                    'errors' => $conn->json()
                ];
            }
        }
    }

    private function setLogo(int $instituicao_id){
        $instituicao = Instituicao::find($instituicao_id);
        $link = NfeEnotas::$url."/empresas/".$instituicao->id_enotas."/logo";
        
        // $photo = fopen(Storage::disk('public')->get($instituicao->imagem), 'r');
        $photo = Storage::disk()->get($instituicao->imagem);

        dd($instituicao, $photo);
        $conn = Http::withHeaders(['Authorization' => "Basic ".NfeEnotas::$api_key])->acceptJson()->withBody(
            base64_encode($photo), 'image/jpeg')->post($link);
        dd($link, $conn, $conn->body());
    }

    private function setCertificado(int $instituicao_id){
        $instituicao = Instituicao::find($instituicao_id);
        $config_fiscal = $instituicao->configuracaoFiscal()->first();
        $link = NfeEnotas::$url."/empresas/".$instituicao->id_enotas."/certificadoDigital";
        
        // $cert = fopen(Storage::disk('public')->get($config_fiscal->certificado), 'r');
        $cert = Storage::disk('public')->get($config_fiscal->certificado);
        
        $conn = Http::withHeaders(['Authorization' => "Basic ".NfeEnotas::$api_key])->acceptJson()
            ->attach('arquivo', $cert, str_replace("certificados//", "", $config_fiscal->certificado)
        )->post($link, ['senha' => $config_fiscal->senha_certificado]);

        if($conn->status() == 200){
            return true;
        }else{
            return [
                'icon' => 'error',
                'title' => 'Falha.',
                'text' => "Erros encontrados na requisição!",
                'errors' => $conn->json()
            ];
        }
    }

    public function cadastrarEmpresa(Request $request){
        $empresa = $this->setEmpresa($request->session()->get("instituicao"));

        if(!empty($empresa['icon']) && $empresa['icon'] == "error"){
            return response()->json($empresa);
        }else{
            return response()->json(
                [
                    'icon' => 'success',
                    'title' => 'Sucesso.',
                    'text' => "Informações enviadas com sucesso para servidor de NFe",
                ]
            );
        }
    }

    public function getStatus(Request $request, NotaFiscal $nota){
        $instituicao = Instituicao::find($nota->instituicao_id);
        $link = (!empty($nota->id_nfse_enotas)) ? NfeEnotas::$url."/empresas/".$instituicao->id_enotas."/nfes/".$nota->id_nfse_enotas : NfeEnotas::$url."/empresas/".$instituicao->id_enotas."/nfes/porIdExterno/".$nota->id;

        // $link = NfeEnotas::$url."/empresas/".$instituicao->id_enotas."/nfes/porIdExterno/". 2;

        $conn = Http::withHeaders(['Authorization' => "Basic ".NfeEnotas::$api_key])->acceptJson()->get($link);

        if($conn->status() == 200){
            // abort_unless($nota->id === $conn->json()['idExterno'], 403);
            abort_unless($nota->id === (int) $conn->json()["idExterno"], 403);
            
            $dados = [
                'status' => $conn->json()['status'],
                'id_nfse_enotas' => $conn->json()['id'],
                'motivo_status' => $conn->json()['motivoStatus'],
                'json_nfe' => $conn->json(),
                'numero_nota' => (!empty($conn->json()['numero'])) ? $conn->json()['numero'] : null,
            ];

            $this->getXml($request, $nota);

            $nota->update($dados);
            return $conn->json();
        }else{
            return [
                'icon' => 'error',
                'title' => 'Falha.',
                'text' => "Erros encontrados na requisição!",
                'errors' => $conn->json()
            ];
        }
    }

    public function cancelarNota(Request $request, NotaFiscal $nota){
        $instituicao = Instituicao::find($nota->instituicao_id);
        $link = (!empty($nota->id_nfse_enotas)) ? NfeEnotas::$url."/empresas/".$instituicao->id_enotas."/nfes/".$nota->id_nfse_enotas : NfeEnotas::$url."/empresas/".$instituicao->id_enotas."/nfes/porIdExterno/".$nota->id;
        
        $conn = Http::withHeaders(['Authorization' => "Basic ".NfeEnotas::$api_key])->acceptJson()->delete($link);

        if($conn->status() == 200){
            $this->getStatus($request, $nota);
            return $conn->json();
        }else{
            return [
                'icon' => 'error',
                'title' => 'Falha.',
                'text' => "Erros encontrados na requisição!",
                'errors' => $conn->json()
            ];
        }
    }

    public function getPDF(Request $request, NotaFiscal $nota){
        $instituicao = Instituicao::find($nota->instituicao_id);
        $link = (!empty($nota->id_nfse_enotas)) ? NfeEnotas::$url."/empresas/{$instituicao->id_enotas}/nfes/{$nota->id_nfse_enotas}/pdf" : NfeEnotas::$url."/empresas/{$instituicao->id_enotas}/nfes/porIdExterno/{$nota->id}/pdf";

        $conn = Http::withHeaders(['Authorization' => "Basic ".NfeEnotas::$api_key])->acceptJson()->get($link);

        if($conn->status() == 200){
            // return response($conn->body());
            $header = [
                "Content-type" => "application/pdf",
               "Content-Disposition" => "inline;filename='{$nota->numero_nota}.pdf'",
            ];
            
            
            return response()->stream(function () use ($conn) {
                echo $conn->body();
            }, 200, $header);

            // $pdf = App::make('dompdf.wrapper');
            // $pdf->loadHtml($conn->body());
            // return $pdf->stream();
        }else{
            // return [
            //     'icon' => 'error',
            //     'title' => 'Falha.',
            //     'text' => "Erros encontrados na requisição!",
            //     'errors' => $conn->json()
            // ];

            $text = "";

            // dd($conn->json());

            foreach($conn->json() as $value){
                $text .="<div class='alert alert-danger' role='alert'>".$value['mensagem']."</div>";
            }

            return $text;
        }
    }

    public function getXml(Request $request, NotaFiscal $nota){
        $instituicao = Instituicao::find($nota->instituicao_id);
        $link = (!empty($nota->id_nfse_enotas)) ? NfeEnotas::$url."/empresas/{$instituicao->id_enotas}/nfes/{$nota->id_nfse_enotas}/xml" : NfeEnotas::$url."/empresas/{$instituicao->id_enotas}/nfes/porIdExterno/{$nota->id}/xml";

        $conn = Http::withHeaders(['Authorization' => "Basic ".NfeEnotas::$api_key])->acceptJson()->get($link);

        if($conn->status() == 200){
            $nota->update(['xml_nfe' => $conn->body()]);
        }else{
            return [
                'icon' => 'error',
                'title' => 'Falha.',
                'text' => "Erros encontrados na requisição!",
                'errors' => $conn->json()
            ];
        }
    }

    public function perfilPrefeitura(Instituicao $instituicao){
        $cod_ibge = CodTerritorialIbge::getCodMunicipio($instituicao->estado, $instituicao->cidade)->first();
        
        $link = NfeEnotas::$url."/estados/cidades/{$cod_ibge->cod_municipio}/provedor";

        $conn = Http::withHeaders(['Authorization' => "Basic ".NfeEnotas::$api_key])->acceptJson()->get($link);

        if($conn->status() == 200){
            return view('instituicao.nota_fiscal.caract_prefeitura', ['caracteristicas' => $conn->json(), 'instituicao' => $instituicao]);

            // return $conn->json();
        }else{
            return [
                'icon' => 'error',
                'title' => 'Falha.',
                'text' => "Erros encontrados na requisição!",
                'errors' => $conn->json()
            ];
        }
    }

    // public function getStatusWebHook(Request $request){
    //     // dd($request->input());
    //     if(!empty($request->input())){
        
    //         $dados_nfse_webhook = json_decode($request->input(), true);

    //         $nota = NotaFiscal::where('id_nfse_enotas', $dados_nfse_webhook['id'])->orwhere('id', $dados_nfse_webhook['nfeIdExterno'])->first();
    //         if(!empty($nota)){
    //             $dados = [
    //                 'status' => $dados_nfse_webhook['status'],
    //                 'id_nfse_enotas' => $dados_nfse_webhook['id'],
    //                 'motivo_status' => $dados_nfse_webhook['motivoStatus'],
    //                 'json_nfe' => $dados_nfse_webhook,
    //                 'numero_nota' => (!empty($dados_nfse_webhook['numero'])) ? $dados_nfse_webhook['numero'] : null,
    //             ];

    //             $retorno = DB::transaction(function () use($nota, $dados){
    //                 $nota->update($dados);

    //                 return true;
    //             });
                
    //             if($retorno === true){
    //                 return response('ok', 200)->json(['ok'], 200);
    //             }else{
    //                 return response()->json([$retorno], 500);
    //             }
    //         }else{
    //             return response()->json(['Nota não localizada pelo id '], 404);
    //         }
    //     }else{
    //         return response()->json(['POST vazio'], 500);
    //     }
    // }

    public function emitirNfModal(Request $request){
        if(!empty($request->input('agendamento_id'))){
            $agendamento = Agendamento::find($request->input('agendamento_id'));
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            $config_fiscal = $instituicao->configuracaoFiscal()->first();

            $paciente = $agendamento->pessoa()->first();
            $contas_receber = $agendamento->contaReceber()->get();

            // dd($agendamento->contaReceber()->get()->toArray());

            // return view('instituicao.nota_fiscal.modal_nota_fiscal', compact('agendamento', 'config_fiscal', 'contas_receber', 'paciente'));
        }else if(!empty($request->input('conta_receber_id'))){
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            $config_fiscal = $instituicao->configuracaoFiscal()->first();
            $contas_receber = $instituicao->contasReceber()->where('id', $request->input('conta_receber_id'))->get();
            // dd($contas_receber);
            $paciente = $contas_receber[0]->paciente()->first();
            
            
        }

        return view('instituicao.nota_fiscal.modal_nota_fiscal', compact('config_fiscal', 'contas_receber', 'paciente'));

        // 'pessoa_id' => ['required', 'exists:pessoas,id'],
        // 'descricao' => ["required"],
        // 'valor_total' => ["required"],
        // 'deducoes' => ["nullable"],
        // 'observacoes' => ["nullable"],
        // 'contas_receber.*' => ['required', 'exists:contas_receber,id']
    }

    public function criarNota(CreateNotaFiscalRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_nota_fiscal');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $config_fiscal = $instituicao->configuracaoFiscal()->first();
        
        $dados = $request->validated();
        
        $dados['valor_total'] = str_replace([".", ","], ['', '.'], $dados['valor_total']);
        $dados['deducoes'] = str_replace([".", ","], ['', '.'], $dados['deducoes']);

        $conta = ContaReceber::whereIn('id', $dados['contas_receber'])
            ->with(['notaFiscal' => function ($q){
                $q->whereNotIn('status', ['Cancelada']);
                $q->orWhereNull('status');
            }])
        ->get();

        if($conta[0]->notaFiscal === null){
            $dados_nota = [
                'pessoa_id' => $dados['pessoa_id'],
                'aliquota_iss' => $config_fiscal->aliquota_iss,
                'valor_iis' => $dados['valor_total'] * ($config_fiscal->aliquota_iss/100),
                'iss_retido_fonte' => ($config_fiscal->iss) ? true : false,
                'cnae' => $config_fiscal->cnae,
                'valor_pis' => $dados['valor_total'] * ($config_fiscal->p_pis/100),
                'p_pis' => $config_fiscal->p_pis,
                'valor_cofins' => $dados['valor_total'] * ($config_fiscal->p_cofins/100),
                'p_cofins' => $config_fiscal->p_cofins,
                'valor_inss' => $dados['valor_total'] * ($config_fiscal->p_inss/100),
                'p_inss' => $config_fiscal->p_inss,
                'valor_ir' => $dados['valor_total'] * ($config_fiscal->p_ir/100),
                'p_ir' => $config_fiscal->p_ir,
                'uf_prestacao_servico'=> $instituicao->estado,
                'municipio_prestacao_servico' => $instituicao->cidade,
                'descricao' => $dados['descricao'],
                'cod_servico_municipal' => $config_fiscal->cod_servico_municipal,
                'descricao_servico_municipal' => $config_fiscal->descricao,
                'natureza_operacao' => "Prestação de serviço",
                'deducoes' => 0,
                'descontos' => $dados['deducoes'],
                'valor_total' => $dados['valor_total'],
                'observacoes' => (!empty($dados['observacao'])) ? $dados['observacao'] : null,
                "cliente_nome" => $conta[0]->paciente->nome,
                "cliente_email" => $conta[0]->paciente->email,
                "cliente_cpfCnpj"  => preg_replace("/[^0-9]/", "", ($conta[0]->paciente->personalidade == 1) ? $conta[0]->paciente->cpf : $conta[0]->paciente->cnpj),
                "cliente_inscricaoMunicipal" => $conta[0]->paciente->inscricao_municipal,
                "cliente_inscricaoEstadual" => $conta[0]->paciente->inscricao_estadual,
                "cliente_telefone" =>  preg_replace("/[^0-9]/", "", $conta[0]->paciente->telefone1),
                
                "cliente_pais" => "brasil",
                "cliente_uf" => $conta[0]->paciente->estado,
                "cliente_cidade" => $conta[0]->paciente->cidade,
                "cliente_logradouro" => $conta[0]->paciente->rua,
                "cliente_numero" => $conta[0]->paciente->numero,
                "cliente_complemento" => $conta[0]->paciente->complemento,
                "cliente_bairro" => $conta[0]->paciente->bairro,
                "cliente_cep" => $conta[0]->paciente->cep,
                'contas_receber_id' => json_encode($dados['contas_receber']),
            ];
            
            if(empty($conta[0]->paciente->estado || $conta[0]->paciente->cidade || $conta[0]->paciente->rua || $conta[0]->paciente->numero || $conta[0]->paciente->complemento || $conta[0]->paciente->bairro || $conta[0]->paciente->cep)){
                return response()->json([
                    'icon' => 'error',
                    'title' => 'Falha.',
                    'text' => 'Endereço do cliente auxente!'
                ]);
            }

            $nota = DB::transaction(function () use($dados_nota, $request, $instituicao, $conta) {
                $nota = $instituicao->notasFiscais()->create($dados_nota);
                $nota->criarLogCadastro($request->user('instituicao'), $instituicao->id);
                
                foreach($conta as $item){
                    $item->update(['nota_id' => $nota->id]);
                }

                // $conta->update(['nota_id' => $nota->id]);
                
                return $this->emitirNfe($request, $nota);
            });

            // dd($nota);

            if(!empty($nota->data['icon']) && $nota->data['icon'] == "error"){
                return response()->json($nota);
            }else{
                return response()->json([
                    'icon' => 'success',
                    'title' => 'Sucesso.',
                    'text' => 'Nota fiscal registrada com sucesso!'
                ]);
            }
        }else{
            return response()->json([
                'icon' => 'error',
                'title' => 'Falha',
                'text' => 'Conta as receber já vindulado a uma nota fiscal'
            ]);
        }
    }

}
