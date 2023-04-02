<?php

namespace App\Http\Controllers\API;

use App\Comercial;
use App\EnderecoEntrega;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartaoUsuarioCollection;
use App\Http\Resources\EnderecoUsuarioCollection;
use App\Http\Resources\PedidoDetalhesResource;
use App\Http\Resources\PedidoMensagensCollection;
use App\Http\Resources\PedidosCollection;
use App\Http\Resources\UsuarioEnderecoResource;
use App\Http\Resources\UsuarioResource;
use App\Pedido;
use App\PedidoProduto;
use App\PedidoTimeline;
use App\PedidoProdutoPergunta;
use App\Usuario;
use App\UsuarioCartao;
use App\UsuarioEndereco;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Libraries\PagarMe;
use App\PedidoMensagem;
use App\Produto;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use PagarMe\Client;

use Kreait\Firebase\Database\Transaction;

class UserController extends Controller
{
    public function enderecos(Request $request)
    {
        $usuario = Usuario::find($request->user('sanctum')->id);

        return new UsuarioResource($usuario);
    }

    public function getEndereco(Request $request)
    {
        $endereco = UsuarioEndereco::where('usuario_id', $request->user('sanctum')->id)->where('id', $request->endereco)->first();

        return new UsuarioEnderecoResource($endereco);
    }

    public function finalizarCarrinho(Request $request)
    {
        $dadosPedido = $request->dadosPedido;
        $dadosFinalizar = $request->dadosFinalizar;
        $validacao = [];
        $subTotal = 0;
        $total = 0;
        $pedido_produtos = [];
        $pedido_produtos_perguntas = [];
        $endereco_entregas = [];
        $cartao_pagarme = [];
        $retirarQuantidadeProdutos = [];

        $comercial = Comercial::where('id', $dadosFinalizar['comercialId'])->first();
        ///VERIFICAÇÃO PRODUTO PEDIDO
        foreach($dadosPedido as $pedido){
            $produto = $comercial->produtos()->where('id', $pedido['produto']['id'])->first();
            if(!empty($produto)){

                ////CRIANDO ARRAY DE PEDIDO PRODUTOS

                $pedido_produto = [
                    'imagem' => $produto->imagem,
                    'produto_id' => $produto->id,
                    'valor' => $produto->promocao ? $produto->preco_promocao : $produto->preco,
                    'nome' => $produto->nome,
                    'nome_farmaceutico' => $produto->nome_farmaceutico,
                    'breve_descricao' => $produto->breve_descricao,
                    'quantidade' => $pedido['pedido']['quantidade'],
                    'perguntas' => []
                ];
                /////////////////////////////////////

                if ($produto->exibir == 1) {
                    if ($produto->promocao) {
                        $total = $total + ($produto->preco_promocao * $pedido['pedido']['quantidade']);
                    }else{
                        $total = $total + ($produto->preco * $pedido['pedido']['quantidade']);
                    }
                    $produto->load('produto_perguntas');
                    if($produto->produto_perguntas){
                        foreach($produto->produto_perguntas as $pergunta){

                            $subTotal = 0;
                            $verificaResposta = true;

                            ///TIPO TEXTO
                            if ($pergunta->tipo == 'Texto') {
                                if($pergunta->obrigatorio){
                                    $verificaResposta = false;
                                }
                                foreach($pedido['pedido']['respostas'] as $resposta){
                                    if ($pergunta->obrigatorio) {
                                        if ($pergunta->id == $resposta['id']) {
                                            $verificaResposta = true;
                                            $pedido_produto['perguntas'][] = $this->criaPedidoPerguntaAlternativaTexto($pergunta, $resposta);
                                        break;
                                        }
                                    }else{
                                        if ($pergunta->id == $resposta['id']) {
                                            $pedido_produto['perguntas'][] = $this->criaPedidoPerguntaAlternativaTexto($pergunta, $resposta);
                                        break;
                                        }
                                    }
                                }
                            }

                            ///TIPO ESCOLHA SIMPLES
                            if ($pergunta->tipo == "Escolha Simples") {
                                if($pergunta->obrigatorio){
                                    $verificaResposta = false;
                                }
                                foreach($pedido['pedido']['respostas'] as $resposta){

                                    if ($pergunta->id == $resposta['id']) {
                                        $pergunta->load('produto_pergunta_alternativas');
                                        foreach($pergunta->produto_pergunta_alternativas as $alternativa){
                                            if ($pergunta->obrigatorio) {
                                                if($alternativa->id == $resposta['opcao']){
                                                    $verificaResposta = true;
                                                    $subTotal = $alternativa->preco;
                                                    $pedido_produto['perguntas'][] = $this->criaPedidoPerguntaAlternativaSimples($pergunta, $alternativa);
                                                break;
                                                }
                                            }else{
                                                if($alternativa->id == $resposta['opcao']){
                                                    $subTotal = $alternativa->preco;
                                                    $pedido_produto['perguntas'][] = $this->criaPedidoPerguntaAlternativaSimples($pergunta, $alternativa);
                                                break;
                                                }
                                            }
                                        }
                                    break;
                                    }else if($pergunta->obrigatorio){
                                        $verificaResposta = false;
                                    }
                                }

                            }

                            ///TIPO ESCOLHA MULTIPLA
                            if ($pergunta->tipo == "Escolha Multipla") {
                                $verificaResposta = false;
                                $selecionadas = 0;
                                if(!$pedido['pedido']['respostas'] && $pergunta->obrigatorio){
                                    $verificaResposta = false;
                                }

                                foreach($pedido['pedido']['respostas'] as $resposta){
                                    if ($pergunta->id == $resposta['id']) {
                                        $pergunta->load('produto_pergunta_alternativas');
                                        foreach($pergunta->produto_pergunta_alternativas as $alternativa){
                                            foreach($resposta['opcoes'] as $opcoes){
                                                if($opcoes){
                                                    if($opcoes == $alternativa->id){
                                                        $subTotal += $alternativa->preco;
                                                        $selecionadas++;
                                                        $pedido_produto['perguntas'][] = $this->criaPedidoPerguntaAlternativaMultipla($pergunta, $alternativa);
                                                    break;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                if ($pergunta->obrigatorio) {
                                    if ($selecionadas >= $pergunta->quantidade_minima && $selecionadas <= $pergunta->quantidade_maxima) {
                                        $verificaResposta = true;
                                    }
                                }else{
                                    $verificaResposta = true;
                                    if ($selecionadas > $pergunta->quantidade_maxima) {
                                        $validacao[] = [
                                            "id" => $produto->id,
                                            "id_pergunta" => $pergunta->id,
                                            "erro" => "Quantidade excedida",
                                            'texto' => "Quantidade excedida"
                                        ];
                                    }
                                }

                            }

                            ///TIPO CONTADOR
                            if ($pergunta->tipo == "Contador") {
                                $verificaResposta = false;
                                $selecionadas = 0;
                                if(!$pedido['pedido']['respostas'] && $pergunta->obrigatorio){
                                    $verificaResposta = false;
                                }
                                foreach($pedido['pedido']['respostas'] as $resposta){
                                    if ($pergunta->id == $resposta['id']) {
                                        $pergunta->load('produto_pergunta_alternativas');
                                        foreach($pergunta->produto_pergunta_alternativas as $alternativa){
                                            foreach($resposta['itens'] as $item){
                                                if($item){
                                                    if($item['id'] == $alternativa->id){
                                                        if ($item['quantidade'] <= $alternativa->quantidade_maxima_itens) {
                                                            $subTotal += $alternativa->preco * $item['quantidade'];
                                                            $selecionadas++;
                                                            $pedido_produto['perguntas'][] = $this->criaPedidoPerguntaAlternativaContador($pergunta, $alternativa, $item['quantidade']);
                                                        break;
                                                        }else{
                                                            $validacao[] = [
                                                                "id" => $produto->id,
                                                                "id_pergunta" => $pergunta->id,
                                                                "id_alternativa" => $alternativa->id,
                                                                "erro" => "Quantidade excedida",
                                                                "texto" => "Quantidade excedida"
                                                            ];
                                                        break;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

                                if ($pergunta->obrigatorio) {
                                    if ($selecionadas >= $pergunta->quantidade_minima && $selecionadas <= $pergunta->quantidade_maxima) {
                                        $verificaResposta = true;
                                    }
                                }else{
                                    $verificaResposta = true;
                                    if ($selecionadas > $pergunta->quantidade_maxima) {
                                        $validacao[] = [
                                            "id" => $produto->id,
                                            "id_pergunta" => $pergunta->id,
                                            "erro" => "Quantidade excedida",
                                            "texto" => "Quantidade excedida",
                                        ];
                                    }
                                }

                            }



                            if (!$verificaResposta) {
                                $validacao[] = [
                                    "id" => $produto->id,
                                    "id_pergunta" => $pergunta->id,
                                    "erro" => "Obrigatorio",
                                    "texto" => "Obrigatorio",
                                ];
                            }

                            $total = $subTotal + $total;
                        }
                    }

                    if (!$produto->estoque_ilimitado) {
                        if ($produto->permitir_comprar_muitos) {
                            if($pedido['pedido']['quantidade'] > $produto->quantidade){
                                $validacao[] = [
                                    "id" => $produto->id,
                                    "erro" => "Quantidade limite",
                                    "texto" => "Quantidade limite",
                                ];
                            }
                        }else{
                            if($pedido['pedido']['quantidade'] > 1){
                                $validacao[] = [
                                    "id" => $produto->id,
                                    "erro" => "Quantidade limite",
                                    "texto" => "Quantidade limite",
                                ];
                            }else{
                                if ($produto->quantidade <= 0) {
                                    $validacao[] = [
                                        "id" => $produto->id,
                                        "erro" => "Quantidade estoque",
                                        "texto" => "Quantidade estoque",
                                    ];
                                }
                            }
                        }

                        $retirarQuantidadeProdutos[] = [
                            'id' => $produto->id,
                            'quantidade' => $pedido['pedido']['quantidade'],
                        ];

                    }else{
                        if(!$produto->permitir_comprar_muitos){
                            if($pedido['pedido']['quantidade'] > 1){
                                $validacao[] = [
                                    "id" => $produto->id,
                                    "erro" => "Quantidade limite",
                                    "texto" => "Quantidade limite",
                                ];
                            }
                        }
                    }


                }else{
                    $validacao[] = [
                        "id" => $produto->id,
                        "erro" => "Indisponivel",
                        "texto" => "Indisponivel",
                    ];

                }
                $pedido_produtos[] = $pedido_produto;
            }else{
                $validacao[] = [
                    "erro" => "Erro produto",
                    "texto" => "Erro produto",
                ];
            }
        }

        ///VALIDAÇÃO ERRO PRODUTO
        if ($validacao) {
            return $validacao;
        }

        ///VERIFICAÇÃO FINALIZAR CARRINHO
        $prazo_tipo = null;
        $prazo_tipo_minimo = null;
        $prazo_tipo_maximo = null;
        $prazo_maximo = null;
        $prazo_minimo = null;
        $endereco_entregas = null;
        $endereco_retirada = null;
        ///VERIFICAÇÃO ENTREGA/RETIRADA
        if($dadosFinalizar['tipoEntrega'] == 'entrega'){
            ///ENTREGA
            $endereco = UsuarioEndereco::where('usuario_id',$request->user('sanctum')->id)->where('id', $dadosFinalizar['tipoEntregaRadio'])->first();
            if(!empty($endereco)){
                $fretes = $comercial->fretes()->where('tipo_frete', 'entrega')->first();
                if($fretes->tipo_filtro == "cidade_bairro"){
                    $entrega = $fretes->fretesEntrega()->where('cidade','like',"{$endereco->cidade}")->where('bairro','like',"{$endereco->bairro}")->first();
                }else{
                    $entrega = $fretes->fretesEntrega()->where('cidade','like',"{$endereco->cidade}")->first();
                }
                if(empty($entrega)){
                    $validacao[] = [
                        'erro' => 'endereco',
                        "texto" => "A empresa não atende neste endereço!"
                    ];
                }else{
                    $prazo_tipo = $entrega->tipo_prazo;
                    $prazo_minimo = $entrega->prazo_minimo;
                    $prazo_maximo = $entrega->prazo_maximo;
                    $total += $entrega->valor;
                    $valorEntrega = $entrega->valor;
                    if($entrega->valor_minimo > $total){
                        $valor = $entrega->valor_minimo;
                        $validacao[] = [
                            'erro' => 'endereco',
                            "texto" => "O valor minimo para a compra é: ${valor}"
                        ];
                    }
                }
            }else{
                $validacao[] = [
                    'erro' => 'endereco_usuario',
                    "texto" => "Endereço não existe!"
                ];
            }

            ////TABELA ENDEREÇO ENTREGA
            $endereco_entregas = [
                'nome' => $request->user('sanctum')->nome,
                'cpf' => $request->user('sanctum')->cpf,
                'rua' => $endereco->rua,
                'numero' => $endereco->numero,
                'bairro' => $endereco->bairro,
                'cidade' => $endereco->cidade,
                'estado' => $endereco->estado,
                'cep' => $endereco->cep,
                'complemento' => $endereco->complemento,
                'referencia' => $endereco->referencia,
            ];
            ///////////////////////////
        }else{
            ///RETIRADA
            $fretes = $comercial->fretes()->where('tipo_frete', 'retirada')->first();
            $retirada = $fretes->fretesRetirada()->where('id', $dadosFinalizar['tipoRetiradaRadio'])->first();

            if(empty($retirada)){
                $validacao[] = [
                    'erro' => 'endereco',
                    "texto" => "A empresa não aceita retirada neste endereço!"
                ];
            }else{
                $prazo_tipo_minimo = $retirada->tipo_prazo_minimo;
                $prazo_tipo_maximo = $retirada->tipo_prazo_maximo;
                $prazo_maximo = $retirada->prazo_maximo;
                $prazo_minimo = $retirada->prazo_minimo;
                $valorEntrega = null;
                $endereco_retirada = [
                    "nome" => $retirada->nome,
                    "rua" => $retirada->rua,
                    "numero" => $retirada->numero,
                    "bairro" => $retirada->bairro,
                    "cidade" => $retirada->cidade,
                    "estado" => $retirada->estado,
                    "cep" => $retirada->cep,
                    "frete_id_retirada" => $retirada->id,
                ];
            }
        }

        if($dadosFinalizar['tipoPagamentoForm'] == 'cartao_entrega'){
            if(!$comercial->cartao_entrega){
                $validacao[] = [
                    'erro' => 'tipoPagamento',
                    'texto' => 'Estabelecimento não aceita essa forma de pagamento'
                ];
                return $validacao;
            }
        }
        
        if($dadosFinalizar['tipoPagamentoForm'] == 'dinheiro'){
            if(!$comercial->dinheiro){
                $validacao[] = [
                    'erro' => 'tipoPagamento',
                    'texto' => 'Estabelecimento não aceita essa forma de pagamento'
                ];
                return $validacao;
            }
        }
        
        if($dadosFinalizar['tipoPagamentoForm'] == 'cartao_credito'){
            if(!$comercial->cartao_credito){
                $validacao[] = [
                    'erro' => 'tipoPagamento',
                    'texto' => 'Estabelecimento não aceita essa forma de pagamento'
                ];
                return $validacao;
            }
        }

        if($dadosFinalizar['tipoPagamentoForm'] == 'cartao_credito'){
            $enderecoCobranca = UsuarioEndereco::where('usuario_id',$request->user('sanctum')->id)->where('id', $dadosFinalizar['endereco_cobranca'])->first();
    
            if(!empty($enderecoCobranca)){
                $endereco_cartao = [
                    'rua' => $enderecoCobranca->rua,
                    'numero' => $enderecoCobranca->numero,
                    'bairro' => $enderecoCobranca->bairro,
                    'cidade' => $enderecoCobranca->cidade,
                    'estado' => $enderecoCobranca->estado,
                    'complemento' => $enderecoCobranca->complemento,
                    'referencia' => $enderecoCobranca->referencia,
                    'cep' => $enderecoCobranca->cep,
                ];
                ///VALIDAÇÃO CARTÃO USUARIO
                if($dadosFinalizar['cartao']){
                    ///USAR CARTÃO EXISTENTE
                    $cartao = UsuarioCartao::where('usuario_id', $request->user('sanctum')->id)->where('id', $dadosFinalizar['cartao'])->first();
    
                    if(empty($cartao)){
                        $validacao[] = [
                            'erro' => 'cartao',
                            "texto" => "Cartão não existe!"
                        ];
                    }
                    $cartaoUltimo = $cartao->ultimos_digitos;
    
                    $dados_cartao = [
                        'cvv' => $dadosFinalizar['cvv'],
                        'id_pagarme' => $cartao->id_pagarme,
                        'nome_cartao' => $cartao->nome,
                        'id' => $cartao->id
                    ];
                    $cartao_pagarme = array_merge($endereco_cartao, $dados_cartao);
                }else{
    
                    if($dadosFinalizar['numero_cartao'] && $dadosFinalizar['nome_cartao'] && $dadosFinalizar['data_validade']){
                        $dados_cartao = [
                            'numero_cartao' => $dadosFinalizar['numero_cartao'],
                            'nome_cartao' => $dadosFinalizar['nome_cartao'],
                            'data_validade' => $dadosFinalizar['data_validade'],
                            'cvv' => $dadosFinalizar['cvv']
                        ];
                        $cartao_pagarme = array_merge($endereco_cartao, $dados_cartao);
    
                    }else{
                        $validacao[] = [
                            'erro' => 'cartao',
                            "texto" => "Escolha um cartão existente!"
                        ];
                    }
                    $cartaoUltimo = null;
                }
            }else{
                $validacao[] = [
                    'erro' => 'enderecoCobranca',
                    "texto" => "Endereço de cobrança não existe!"
                ];
            }
    
            ///VALIDAÇÃO PARCELAS
            if($dadosFinalizar['parcelas'] <= $comercial->max_parcela && $dadosFinalizar['parcelas'] > 0 ){
                if($dadosFinalizar['parcelas'] > $comercial->free_parcela){
    
                    $parcelasTaxa = $dadosFinalizar['parcelas'] - $comercial->free_parcela;
                    $valorTaxa = $parcelasTaxa * $comercial->valor_parcela;
    
                    $valorNovo = ($total * ( 1 + ($valorTaxa / 100 ) ) );
                    $total = number_format($valorNovo, 2);
                    $valorParcelas = $total / $dadosFinalizar['parcelas'];
                    $valorParcelas = number_format($valorParcelas, 2);
    
                    if($valorParcelas < $comercial->valor_minimo && $dadosFinalizar['parcelas'] != 1){
                        $validacao[] = [
                            'erro' => 'parcelas',
                            'texto' => 'Numero de parcelas inválido!',
                        ];
                    }
    
                }else{
                    $valorParcelas = $total / $dadosFinalizar['parcelas'];
    
                    if($valorParcelas < $comercial->valor_minimo && $dadosFinalizar['parcelas'] != 1){
                        $validacao[] = [
                            'erro' => 'parcelas',
                            'texto' => 'Numero de parcelas inválido!',
                        ];
                    }
                }
    
            }else{
                $validacao[] = [
                    'erro' => 'parcelas',
                    "texto" => "Numero de parcelas inválido!"
                ];
            }
    
            ///VALIDAÇÃO CAMPOS QUE NÃO PODEM SER NULOS
            if(!$dadosFinalizar['cvv']){
                $validacao[] = [
                    'erro' => 'cvv',
                    "texto" => "CVV não preenchido"
                ];
            }
        }

        ///VALIDAÇÃO ERRO VERIFICAR CARRINHO
        if($validacao){
            return $validacao;
        }

        $transacao = DB::transaction(function () use ($endereco_entregas, $comercial, $request, $total,$dadosFinalizar,$valorEntrega, $pedido_produtos, $cartao_pagarme, $prazo_tipo, $prazo_tipo_minimo, $prazo_tipo_maximo, $prazo_maximo, $prazo_minimo, $endereco_retirada, $retirarQuantidadeProdutos ){

            if($endereco_entregas){
                $endereco_entrega = EnderecoEntrega::create($endereco_entregas);
            }else{
                $endereco_entrega = EnderecoEntrega::create($endereco_retirada);
            }

            if ($retirarQuantidadeProdutos) {
                foreach ($retirarQuantidadeProdutos as $key => $value) {
                    $produto = Produto::where('id', $value['id'])->first();
                    $quantidade = $produto->quantidade - $value['quantidade'];
                    $dados = [
                        'quantidade' => $quantidade,
                    ];
                    $produto->update($dados);
                }
            }

            $pedidos = [
                'comercial_id' => $comercial->id,
                'endereco_entregas_id' => $endereco_entrega->id,
                'usuarios_id' => $request->user('sanctum')->id,
                'valor_total' => $total,
                'forma_entrega' => $dadosFinalizar['tipoEntrega'],
                'valor_entrega' => $valorEntrega,
                'status_pedido' => 'pendente',
                'parcelas' => $dadosFinalizar['parcelas'],
                'valor_parcela' => $comercial->valor_parcela,
                'free_parcela' => $comercial->free_parcela,
                'observacao' => $dadosFinalizar['observacao'],
                'cartoes_id' => $dadosFinalizar['cartao'],
                'prazo_tipo' => $prazo_tipo,
                'prazo_tipo_minimo' => $prazo_tipo_minimo,
                'prazo_tipo_maximo' => $prazo_tipo_maximo,
                'prazo_minimo' => $prazo_minimo,
                'prazo_maximo' => $prazo_maximo,
                'forma_pagamento' => $dadosFinalizar['tipoPagamentoForm'],
                'troco_dinheiro' => $dadosFinalizar['trocoDinheiro']
                // 'data_entrega' => '2020-08-14 17:14:02'
            ];


            $pedido = Pedido::create($pedidos);
            $descricao = 'Pedido iniciado!';
            event(new \App\Events\PedidoTimeline($pedido,$descricao,$request->user('sanctum'),json_encode($pedido->getAttributes()) ));

            foreach ($pedido_produtos as $pedido_produto) {
                $pedido_produto['pedido_id'] = $pedido->id;
                $PedidoProduto = PedidoProduto::create($pedido_produto);
                foreach ($pedido_produto['perguntas'] as $pergunta) {
                    PedidoProdutoPergunta::create([
                        'pedido_produtos_id' => $PedidoProduto->id,
                        'pergunta_id' => $pergunta['pergunta_id'],
                        'alternativa_id' => $pergunta['alternativa_id'],
                        'texto_pergunta' => $pergunta['texto_pergunta'],
                        'texto_resposta' => $pergunta['texto_resposta'],
                        'valor' => $pergunta['valor'],
                        'tipo_pergunta' => $pergunta['tipo_pergunta'],
                        'quantidade' => $pergunta['quantidade'],
                    ]);
                }
            }

            if($dadosFinalizar['tipoPagamentoForm'] == 'cartao_credito'){
                $pagarMe = new PagarMe();
                return $pagarMe->criarTransacaoComercial($pedido, $endereco_entrega, $cartao_pagarme );
            }

            return (object) array(
                'status' => 'Sucesso',
                'pedido_id' => $pedido->id,
            );

        });

        if(property_exists($transacao,'error')){
            $validacao[] = [
                'erro' => 'transacao',
                'texto' => $transacao->error
            ];

            if ($retirarQuantidadeProdutos) {
                foreach ($retirarQuantidadeProdutos as $key => $value) {
                    $produto = Produto::where('id', $value['id'])->first();
                    $quantidade = $produto->quantidade + $value['quantidade'];
                    $dados = [
                        'quantidade' => $quantidade,
                    ];
                    $produto->update($dados);
                }
            }

            return $validacao;
        }

        if($transacao->status == 'refused'){
            $resultado = MotivoRecusaPagarme($transacao);
            $validacao[] =
            [
                'texto' => $resultado['msg'],
                'status' => $transacao->status,
                'descricao' => $resultado['orientacao']
            ];
        }else{

            $validacao[] =
            [
                'texto' => 'Pedido enviado',
                'status' => $transacao->status
            ];
        }

        // $validacao[] =
        // [
        //     'texto' => 'Pedido enviado',
        //     'status' => $transacao->status
        // ];

        return $validacao;
    }

    private function criaPedidoPerguntaAlternativaTexto($pergunta, $resposta)
    {
        $pedido_alternativa = [
            'pergunta_id' => $pergunta->id,
            'alternativa_id' => null,
            'texto_pergunta' => $pergunta->titulo,
            'texto_resposta' => $resposta['texto'],
            'valor' => null,
            'quantidade' => null,
            'tipo_pergunta' => $pergunta->tipo,
        ];
        return $pedido_alternativa;
    }

    private function criaPedidoPerguntaAlternativaSimples($pergunta, $alternativa)
    {
        $pedido_alternativa = [
            'pergunta_id' => $pergunta->id,
            'alternativa_id' => $alternativa->id,
            'texto_pergunta' => $pergunta->titulo,
            'texto_resposta' => $alternativa->alternativa,
            'valor' => $alternativa->preco,
            'quantidade' => null,
            'tipo_pergunta' => $pergunta->tipo,
        ];
        return $pedido_alternativa;
    }

    private function criaPedidoPerguntaAlternativaMultipla($pergunta, $alternativa)
    {
        $pedido_alternativa = [
            'pergunta_id' => $pergunta->id,
            'alternativa_id' => $alternativa->id,
            'texto_pergunta' => $pergunta->titulo,
            'texto_resposta' => $alternativa->alternativa,
            'valor' => $alternativa->preco,
            'quantidade' => null,
            'tipo_pergunta' => $pergunta->tipo,
        ];
        return $pedido_alternativa;
    }

    private function criaPedidoPerguntaAlternativaContador($pergunta, $alternativa, $quantidade)
    {
        $pedido_alternativa = [
            'pergunta_id' => $pergunta->id,
            'alternativa_id' => $alternativa->id,
            'texto_pergunta' => $pergunta->titulo,
            'texto_resposta' => $alternativa->alternativa,
            'valor' => ($alternativa->preco * $quantidade),
            'quantidade' => $quantidade,
            'tipo_pergunta' => $pergunta->tipo,
        ];
        return $pedido_alternativa;
    }

    public function salvarEndereco(Request $request)
    {
        $dadosEndereco = $request->dadosEndereco;
        $validacao = [];

        if($dadosEndereco['rua'] && $dadosEndereco['numero'] && $dadosEndereco['bairro'] && $dadosEndereco['cidade'] && $dadosEndereco['estado'] && $dadosEndereco['cep']){

            $usuario = Usuario::where('id', $request->user('sanctum')->id)->first();
            DB::transaction(function () use ($usuario, $dadosEndereco, $request){
                $endereco = $usuario->usuarioEnderecos()->create($dadosEndereco);

                $usuario_logado = $request->user('sanctum');
                $endereco->criarLogCadastro($usuario_logado);
            });

            $validacao[] = [
                'Endereço salvo'
            ];

        }else{
            $validacao[] = [
                'Erro'
            ];
        }

        return $validacao;
    }

    public function editandoEndereco(Request $request)
    {
        $dadosEndereco = $request->dadosEndereco;
        $validacao = [];

        if($dadosEndereco['rua'] && $dadosEndereco['numero'] && $dadosEndereco['bairro'] && $dadosEndereco['cidade'] && $dadosEndereco['estado'] && $dadosEndereco['cep']){

            $endereco = UsuarioEndereco::where('usuario_id', $request->user('sanctum')->id)->where('id', $dadosEndereco['id'])->first();

            $dados = [
                'rua' => $dadosEndereco['rua'],
                'numero' => $dadosEndereco['numero'],
                'bairro' => $dadosEndereco['bairro'],
                'cidade' => $dadosEndereco['cidade'],
                'estado' => $dadosEndereco['estado'],
                'cep' => $dadosEndereco['cep'],
                'complemento' => $dadosEndereco['complemento'],
                'referencia' => $dadosEndereco['referencia'],
            ];

            DB::transaction(function () use ($endereco, $dados, $request){

                $endereco->update($dados);

                $usuario_logado = $request->user('sanctum');
                $endereco->criarLogEdicao($usuario_logado);
            });

            $validacao[] = [
                'Endereço editado'
            ];

        }else{
            $validacao[] = [
                'Erro'
            ];
        }

        return $validacao;
    }

    public function pedidosUsuario(Request $request)
    {
        $pedidos = Pedido::where('usuarios_id', $request->user('sanctum')->id)->orderBy('id', 'desc')->paginate(30);

        return new PedidosCollection($pedidos);
    }

    public function pedidoDetalhesUsuario(Request $request, Pedido $pedido)
    {
        if($request->user('sanctum')->id == $pedido->usuarios_id){
            return new PedidoDetalhesResource($pedido);
        }

        return [
            'data' => null,
        ];
    }

    public function pedidoMensagensUsuario(Request $request, Pedido $pedido)
    {
        if($request->user('sanctum')->id == $pedido->usuarios_id){
            $mensagem = $pedido->mensagem()->orderBy('id', 'asc')->get();

            DB::transaction(function() use($request, $pedido){
                $dados = [
                    'visto' => 1,
                    'data_visto' => date('Y-m-d H:i:s'),
                ];
                PedidoMensagem::where('pedido_id', $pedido->id)->where('remetente', 'comercial')->where('visto', 0)->update($dados);
            });

            return new PedidoMensagensCollection($mensagem);
        }

        return [
            'data' => null,
        ];
    }

    public function enviarMensagemPedido(Request $request, Pedido $pedido)
    {
        $transacao = DB::transaction(function() use($pedido, $request){
            $dados = [
                'mensagem' => $request->mensagem['mensagem'],
                'remetente' => 'cliente',
            ];
            return $pedido->mensagem()->create($dados);
        });

        if(!$transacao){
            $validacao[] = [
                'msg' => 'erro'
            ];
            return $validacao;
        }

        $validacao[] = [
            'msg' => 'sucesso'
        ];

        return $validacao;

    }

    public function listaEndereco(Request $request)
    {
        $endereco = UsuarioEndereco::where('usuario_id', $request->user('sanctum')->id)->paginate(30);

        return new EnderecoUsuarioCollection($endereco);
    }

    public function listaCartao(Request $request)
    {
        $cartao = UsuarioCartao::where('usuario_id', $request->user('sanctum')->id)->paginate(30);

        return new CartaoUsuarioCollection($cartao);
    }

    public function excluirCartao(Request $request, UsuarioCartao $cartao)
    {
        $transacao = DB::transaction(function() use($cartao, $request){

            return $cartao->delete();

        });

        if(!$transacao){
            $validacao[] = [
                'msg' => 'erro'
            ];
            return $validacao;
        }

        $validacao[] = [
            'msg' => 'sucesso'
        ];

        return $validacao;
    }

    public function excluirEndereco(Request $request, UsuarioEndereco $endereco)
    {
        $transacao = DB::transaction(function() use($endereco, $request){

            return $endereco->delete();

        });

        if(!$transacao){
            $validacao[] = [
                'msg' => 'erro'
            ];
            return $validacao;
        }

        $validacao[] = [
            'msg' => 'sucesso'
        ];

        return $validacao;
    }

    public function RegisterTokenFcm(Request $request)
    {
        $dados = [
            'fcm_token' => $request->token,
        ];

        $user = $request->user('sanctum');
        $token = $user->currentAccessToken();

        DB::transaction(function() use($request, $dados, $token, $user) {
            $user->tokens()->where('name', $token->name)->update($dados);
        });
    }

    public function statusEntregue(Request $request)
    {
        DB::Transaction(function() use($request) {
            $dados = [
                'entrega_cliente' => date('Y-m-d H:i:s'),
                'status_pedido' => 'entregue'
            ];
            $pedido = Pedido::where('id', $request->pedidoId)->where('entrega_cliente', null)->where('usuarios_id', $request->user('sanctum')->id)->first();
            $pedido->update($dados);
            event(new \App\Events\PedidoTimeline($pedido,"Pedido entregue",$pedido->usuario,json_encode($pedido->getChanges()) ));
        });

        return $validacao = ['data' => 'sucesso'];
    }

    public function postbackPagarme(Request $request){
        $client = new Client(config('services.pagarme.api_key'));
        $signature = $request->server('HTTP_X_HUB_SIGNATURE');
        $bodyRequest = http_build_query($request->toArray(), '', '&', PHP_QUERY_RFC3986);
        if ($client->postbacks()->validate($bodyRequest, $signature)) {
            if( $request->event == 'transaction_status_changed'){

                $pagarme = new Pagarme();
                $pagarme->atualizarStatusTransacao($request->all());
            }

        }
    }
}
