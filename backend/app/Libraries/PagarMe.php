<?php

namespace App\Libraries;

use App\Agendamentos;
use Illuminate\Support\Facades\Auth;
use PagarMe\Client;
use App\Pedido;
use App\Comercial;
use App\EnderecoEntrega;
use App\Instituicao;
use App\UsuarioCartao;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PagarMe
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(config('services.pagarme.api_key'),
            ['headers' => ['X-PagarMe-Version' => '2019-09-01']]);
    }


    //RECEBEDORES
    public function cadastrarRecebedor($id_banco){
        return $this->client->recipients()->create([
            'transfer_enabled' => false,
            'bank_account_id' => $id_banco
            ]);
    }

    public function atualizarBancoRecebedor($recebedor_id,$banco_id){
        return $this->client->recipients()->update([
            'id'=>$recebedor_id,
            'bank_account_id'=>$banco_id
        ]);
    }

    public function recebedores($id = null){
        if(is_null($id)){
            return $this->client->recipients()->getList();
        }

        return $this->client->recipients()->get([
            'id' => $id
        ]);
    }

    public function saldoRecebedor($id){
        return $this->client->recipients()->getBalance([
            'recipient_id' => $id,
        ]);
    }

    public function criarCartao($dados){
        return $this->client->cards()->create([
            'holder_name' => $dados->holder_name,
            'number' => $dados->number,
            'expiration_date' =>  $dados->expiration_date,
            'cvv' => $dados->cvv
        ]);
    }
    public function cadastrarContaBancaria($banco){
        return $this->client->bankAccounts()->create($banco);
    }

    public function cadastrarCliente($customer){
        return $this->client->customers()->create($customer);
    }


    public function saque($valor, $recebedorId){
        return $this->client->transfers()->create([
            'amount' => realParaCentavos($valor),
            'recipient_id' => $recebedorId
        ]);
    }

    public function obterSaldo($recebedorId){
        return $this->client->recipients()->getBalance([
            'recipient_id' => $recebedorId
        ]);
    }

    public function criarTransacaoComercial(Pedido $pedido,EnderecoEntrega $endereco_entrega, array $cartao )
    {

        $transacao = [
            "amount" => realParaCentavos($pedido['valor_total']),
            'installments' => $pedido['parcelas'],
            "payment_method" => "credit_card",
            'card_cvv' => $cartao['cvv'],
            // 'reference_key' => (string) $pedido['id'],
            // "postback_url" => route('notificacao-pagarme'),
            'capture' => true,
        ];

        if(config('app.env') == 'production'){
                $transacao['postback_url'] = route('api.notificacao-pagarme');
        }

        if(array_key_exists('id_pagarme', $cartao)){
            $transacao['card_id'] = $cartao['id_pagarme'];
        }else{
            $transacao['card_number'] = $cartao['numero_cartao'];
            $transacao['card_holder_name'] = $cartao['nome_cartao'];
            $transacao['card_expiration_date'] = preg_replace('/\D/', '', $cartao['data_validade']);
        }

        $transacao['split_rules'] = [];

        $taxa_pagarme=0;
        if($pedido['parcelas'] >= 7){
            $taxa_pagarme = 4.59;
        }else if($pedido['parcelas'] >= 2){
            $taxa_pagarme = 4.19;
        }else if($pedido['parcelas'] == 1){
            $taxa_pagarme = 3.79;
        }
        $valor_tectotum = ( $pedido->comercial->taxa_tectotum / 100 ) * ( ( $pedido['valor_total'] * ( 1 - ( $taxa_pagarme / 100 ) ) ) - 1.2 ) + 1.2 ;
        array_push($transacao['split_rules'], [
            'recipient_id' => $pedido->comercial->id_recebedor,
            'liable' => true, //Se o recebedor é responsável ou não pelo chargeback
            'charge_processing_fee' => true, //Se o recebedor será cobrado das taxas da criação da transação
            'charge_remainder_fee' => true, //Se o recebedor deverá pagar os eventuais restos das taxas, calculadas em porcentagem.
            'amount' => realParaCentavos($pedido['valor_total'] - $valor_tectotum),
        ]);



        array_push($transacao['split_rules'], [
            'recipient_id' => config('services.pagarme.conta_default'),
            'liable' => false, //Se o recebedor é responsável ou não pelo chargeback
            'charge_processing_fee' => false, //Se o recebedor será cobrado das taxas da criação da transação
            'charge_remainder_fee' => false, //Se o recebedor deverá pagar os eventuais restos das taxas, calculadas em porcentagem.
            'amount' => realParaCentavos($valor_tectotum),
        ]);

        $customer = [
            "id" => (string) $pedido->usuario->customer_id
        ];

        $dados_cobranca = [
            "name" => $cartao['nome_cartao'],
            'address' => [
                'street' => $cartao['rua'],
                'street_number' => $cartao['numero'],
                'neighborhood' => $cartao['bairro'],
                'zipcode' => preg_replace('/\D/', '', $cartao['cep']),
                'state' => $cartao['estado'],
                'city' => $cartao['cidade'],
                'country' => 'br'
            ]
        ];

        $dados_envio = [
            "name" => $pedido->usuario->nome,
            "fee" => realParaCentavos($pedido['valor_entrega']), //Taxa de envio cobrada do comprador. Por exemplo, se a taxa de envio é de dez reais e três centavos (R$10,03), o valor deve ser fornecido como ‘1003'
            "delivery_date" => Carbon::now()->toDateString(), //data do envio
            "expedited" => false, //Entrega expressa
            'address' => [
                'street' => $endereco_entrega['rua'],
                'street_number' => $endereco_entrega['numero'],
                'neighborhood' => $endereco_entrega['bairro'],
                'zipcode' => preg_replace('/\D/', '', $endereco_entrega['cep']),
                'state' => $endereco_entrega['estado'],
                'city' => $endereco_entrega['cidade'],
                'country' => 'br'
            ]
        ];

        $items = [];

        foreach ($pedido->produtos as $produtos) {
             array_push($items, [
                "id" => (string) $produtos['id'],
                "title" => $produtos['nome'],
                "unit_price" => realParaCentavos($produtos['valor']),
                "quantity" => $produtos['quantidade'],
                "tangible" => true
             ]);
        }


        $transacao["billing"] = $dados_cobranca;
        $transacao["shipping"] = $dados_envio;
        $transacao["customer"] = $customer;
        $transacao["items"] = $items;
        try {
            $transacao_pagarme = $this->client->transactions()->create($transacao);
        } catch (\Throwable $th) {
            $pedido->update(['status_pedido'=>'cancelado']);
            event(new \App\Events\PedidoTimeline($pedido,"Pedido falhou",null,json_encode($pedido->getChanges()) ));

            Log::channel('my_errors')->error('Pedido '.$pedido->id.' falhou no pagarme', ["resumo"=> '['.$th->getCode().'] "'.$th->getMessage().'" on line '.$th->getTrace()[0]['line'].' of file '.$th->getTrace()[0]['file'], 'transacao'=> $transacao_pagarme , 'exception'=>$th  ]);
            return (object)['error' => 'Erro ao criar transação'];
        }

        if(!array_key_exists('id_pagarme', $cartao)){
            $cartao = UsuarioCartao::create([
                'id_pagarme' => $transacao_pagarme->card->id,
                'ultimos_digitos' => $transacao_pagarme->card->last_digits,
                'bandeira'=> $transacao_pagarme->card->brand,
                'usuario_id'=> $pedido->usuario->id,
                'nome'=> $transacao_pagarme->card->holder_name,

                'rua' => $cartao['rua'],
                'numero' => $cartao['numero'],
                'bairro' => $cartao['bairro'],
                'cep' => preg_replace('/\D/', '', $cartao['cep']),
                'estado' => $cartao['estado'],
                'cidade' => $cartao['cidade'],
            ]);

            $pedido->update(['cartoes_id'=>$cartao->id]);

        }

        $status ='';
        $descricao ='';
        $corpo ='';
        switch ($transacao_pagarme->status) {
            case 'processing':
                $status = 'processando';
                $descricao ='Transação está em processo de autorização';
                $corpo ='Pagamento em processo de autorização';
                break;
            case 'authorized':
                $status = 'autorizado';
                $descricao ='Transação foi autorizada';
                $corpo ='Pagamento autorizado';
                break;
            case 'paid':
                $status = 'pago';
                $descricao ='Transação paga';
                $corpo ='Pagamento realizado';
                break;
            case 'refunded':
                $status = 'estornado';
                $descricao = 'Transação estornada completamente';
                $corpo = 'Pagamento estornado';
                break;
            case 'waiting_payment':
                $status = 'aguardando pagamento';
                $descricao = 'Transação aguardando pagamento';
                $corpo = 'Aguardando pagamento';
                break;
            case 'pending_refund':
                $status = 'estorno pendente';
                $descricao = 'Transação do tipo Boleto e que está aguardando confirmação do estorno solicitado';
                $corpo = 'Estorno solicitado';
                break;
            case 'refused':
                $status = 'recusado';
                $descricao = 'Transação recusada, não autorizada';
                $corpo = 'Pagamento recusado, não autorizado';
                $pedido->status_pedido = 'cancelado';
                $motivo_recusa = MotivoRecusaPagarme($transacao_pagarme);
                Log::channel('my_errors')->error('Pedido '.$pedido->id.' recusado no pagarme',['motivo_recusa' => $motivo_recusa, 'transacao'=> $transacao_pagarme]);

                break;
            case 'chargedback':
                $status = 'chargedback';
                $descricao = 'Transação sofreu chargeback';
                $corpo = 'Pagamento sofreu chargeback';
                break;
            case 'analyzing':
                $descricao = 'Transação encaminhada para a análise manual feita por um especialista em prevenção a fraude';
                $corpo = 'Pagamento encaminhado para a análise manual feita por um especialista em prevenção a fraude';
                break;
            case 'pending_review':
                $status = 'revisao pendente';
                $descricao = 'Transação pendente de revisão manual por parte do lojista';
                $corpo = 'Pagamento pendente de revisão manual por parte do lojista';
                break;
        }

        $pedido->status_pagamento = $status;
        $pedido->codigo_transacao = $transacao_pagarme->id;
        $pedido->update();
        event(new \App\Events\PedidoTimeline($pedido,$descricao,null,json_encode($pedido->getChanges()) ));
        event(new \App\Events\NotificacaoFCM(
            'Pedido #'.$pedido->id,
            $corpo,
            'tabs/tab1/pedidosUsuario/'.$pedido->id,
            $pedido->usuarios_id
        ));

        return $transacao_pagarme;

    }

    public function estornarTransacaoComercial(Pedido $pedido){
        return $this->client->transactions()->refund([
            'id' => $pedido->codigo_transacao,
        ]);
    }

    public function estornarTransacaoInstituicao(Agendamentos $agendamento){
        try {
            $estorno = $this->client->transactions()->refund([
                'id' => $agendamento->codigo_transacao,
            ]);
            return $estorno;
        } catch (\Throwable $th) {
            Log::channel('my_errors')->error('Estorno de consulta '.$agendamento->id.' falhou no pagarme', ["resumo"=> '['.$th->getCode().'] "'.$th->getMessage().'" on line '.$th->getTrace()[0]['line'].' of file '.$th->getTrace()[0]['file'], "estorno" => $estorno]);
        }

    }

    public function estornarParcialmenteTransacaoInstituicao(Agendamentos $agendamento, $valor){
        try {
            $estorno = $this->client->transactions()->refund([
                'id' => $agendamento->codigo_transacao,
                'amount' => realParaCentavos($valor)
            ]);
            return $estorno;
        } catch (\Throwable $th) {
            Log::channel('my_errors')->error('Estorno parcial '.$agendamento->id.' falhou no pagarme', ["resumo"=> '['.$th->getCode().'] "'.$th->getMessage().'" on line '.$th->getTrace()[0]['line'].' of file '.$th->getTrace()[0]['file'], "estorno" => $estorno]);
        }

    }


    public function receberTransacao($transacaoId){
        return $this->client->transactions()->get([
            'id' => $transacaoId
        ]);
    }

    public function atualizarStatusTransacao($postBack)
    {
         $status = '';
         $descricao ='';
         $corpo ='';
         switch ($postBack['transaction']['status']) {
            case 'processing':
                $status = 'processando';
                $descricao ='Transação está em processo de autorização';
                $corpo ='Pagamento em processo de autorização';
                break;
            case 'authorized':
                $status = 'autorizado';
                $descricao ='Transação foi autorizada';
                $corpo ='Pagamento autorizado';
                break;
            case 'paid':
                $status = 'pago';
                $descricao ='Transação paga';
                $corpo ='Pagamento realizado';
                break;
            case 'refunded':
                $status = 'estornado';
                $descricao = 'Transação estornada completamente';
                $corpo = 'Pagamento estornado';
                break;
            case 'waiting_payment':
                $status = 'aguardando pagamento';
                $descricao = 'Transação aguardando pagamento';
                $corpo = 'Aguardando pagamento';
                break;
            case 'pending_refund':
                $status = 'estorno pendente';
                $descricao = 'Transação do tipo Boleto e que está aguardando confirmação do estorno solicitado';
                $corpo = 'Estorno solicitado';
                break;
            case 'refused':
                $status = 'recusado';
                $descricao = 'Transação recusada, não autorizada';
                $corpo = 'Pagamento recusado, não autorizado';
                $pedido->status_pedido = 'cancelado';
                break;
            case 'chargedback':
                $status = 'chargedback';
                $descricao = 'Transação sofreu chargeback';
                $corpo = 'Pagamento sofreu chargeback';
                break;
            case 'analyzing':
                $descricao = 'Transação encaminhada para a análise manual feita por um especialista em prevenção a fraude';
                $corpo = 'Pagamento encaminhado para a análise manual feita por um especialista em prevenção a fraude';
                break;
            case 'pending_review':
                $status = 'revisao pendente';
                $descricao = 'Transação pendente de revisão manual por parte do lojista';
                $corpo = 'Pagamento pendente de revisão manual por parte do lojista';
                break;
        }

        $pedido = Pedido::where('codigo_transacao', $postBack->id)->first();
        if (!$pedido) {
            return;
        }
        $pedido->update(['status' => $status]);
        event(new \App\Events\PedidoTimeline($pedido,$descricao,null,json_encode($pedido->getChanges()) ));
        event(new \App\Events\NotificacaoFCM(
            'Pedido #'.$pedido->id,
            $corpo,
            'tabs/tab1/pedidosUsuario/'.$pedido->id,
            $pedido->usuarios_id
        ));
        return $pedido;



    }

    public function criarTransacaoAgendaConsulta(Agendamentos $agendamentos, Instituicao $instituicao, array $cartao )
    {

        $transacao = [
            "amount" => realParaCentavos($agendamentos['valor_total']),
            'installments' => $agendamentos['parcelas'],
            "payment_method" => "credit_card",
            'card_cvv' => $cartao['cvv'],
            // 'reference_key' => (string) $pedido['id'],
            // "postback_url" => route('notificacao-pagarme'),
            'capture' => true,
        ];

        if(config('app.env') == 'production'){
                $transacao['postback_url'] = route('api.notificacao-pagarme');
        }

        if(array_key_exists('id_pagarme', $cartao)){
            $transacao['card_id'] = $cartao['id_pagarme'];
        }else{
            $transacao['card_number'] = $cartao['numero_cartao'];
            $transacao['card_holder_name'] = $cartao['nome_cartao'];
            $transacao['card_expiration_date'] = preg_replace('/\D/', '', $cartao['data_validade']);
        }

        $transacao['split_rules'] = [];

        $taxa_pagarme=0;
        if($agendamentos['parcelas'] >= 7){
            $taxa_pagarme = 4.59;
        }else if($agendamentos['parcelas'] >= 2){
            $taxa_pagarme = 4.19;
        }else if($agendamentos['parcelas'] == 1){
            $taxa_pagarme = 3.79;
        }
        $valor_tectotum = ( $instituicao->taxa_tectotum / 100 ) * ( ( $agendamentos['valor_total'] * ( 1 - ( $taxa_pagarme / 100 ) ) ) - 1.2 ) + 1.2 ;
        array_push($transacao['split_rules'], [
            'recipient_id' => $instituicao->id_recebedor,
            'liable' => true, //Se o recebedor é responsável ou não pelo chargeback
            'charge_processing_fee' => true, //Se o recebedor será cobrado das taxas da criação da transação
            'charge_remainder_fee' => true, //Se o recebedor deverá pagar os eventuais restos das taxas, calculadas em porcentagem.
            'amount' => realParaCentavos($agendamentos['valor_total'] - $valor_tectotum),
        ]);



        array_push($transacao['split_rules'], [
            'recipient_id' => config('services.pagarme.conta_default'),
            'liable' => false, //Se o recebedor é responsável ou não pelo chargeback
            'charge_processing_fee' => false, //Se o recebedor será cobrado das taxas da criação da transação
            'charge_remainder_fee' => false, //Se o recebedor deverá pagar os eventuais restos das taxas, calculadas em porcentagem.
            'amount' => realParaCentavos($valor_tectotum),
        ]);

        $customer = [
            "id" => (string) $agendamentos->usuario->customer_id
        ];

        $dados_cobranca = [
            "name" => $cartao['nome_cartao'],
            'address' => [
                'street' => $cartao['rua'],
                'street_number' => $cartao['numero'],
                'neighborhood' => $cartao['bairro'],
                'zipcode' => preg_replace('/\D/', '', $cartao['cep']),
                'state' => $cartao['estado'],
                'city' => $cartao['cidade'],
                'country' => 'br'
            ]
        ];

        $items = [];

        array_push($items, [
        "id" => (string) $agendamentos['id'],
        "title" => $agendamentos['tipo'],
        "unit_price" => realParaCentavos($agendamentos['valor_total']),
        "quantity" => 1,
        "tangible" => false
        ]);



        $transacao["billing"] = $dados_cobranca;
        // $transacao["shipping"] = $dados_envio;
        $transacao["customer"] = $customer;
        $transacao["items"] = $items;
        try {
            $transacao_pagarme = $this->client->transactions()->create($transacao);
        } catch (\Throwable $th) {
            $agendamentos->update(['status'=>'cancelado']);
            Log::channel('my_errors')->error('Agendamento '.$agendamentos->id.' falhou no pagarme', ["resumo"=> '['.$th->getCode().'] "'.$th->getMessage().'" on line '.$th->getTrace()[0]['line'].' of file '.$th->getTrace()[0]['file']]);
            return (object)['error' => 'Erro ao criar transação'];
        }

        if(!array_key_exists('id_pagarme', $cartao)){
            $cartao = UsuarioCartao::create([
                'id_pagarme' => $transacao_pagarme->card->id,
                'ultimos_digitos' => $transacao_pagarme->card->last_digits,
                'bandeira'=> $transacao_pagarme->card->brand,
                'usuario_id'=> $agendamentos->usuario->id,
                'nome'=> $transacao_pagarme->card->holder_name,

                'rua' => $cartao['rua'],
                'numero' => $cartao['numero'],
                'bairro' => $cartao['bairro'],
                'cep' => preg_replace('/\D/', '', $cartao['cep']),
                'estado' => $cartao['estado'],
                'cidade' => $cartao['cidade'],
            ]);

            $agendamentos->update(['cartao_id'=>$cartao->id]);

        }

        $status ='';
        $descricao ='';
        $corpo ='';
        switch ($transacao_pagarme->status) {
            case 'processing':
                $status = 'processando';
                $descricao ='Transação está em processo de autorização';
                $corpo ='Pagamento em processo de autorização';
                break;
            case 'authorized':
                $status = 'autorizado';
                $descricao ='Transação foi autorizada';
                $corpo ='Pagamento autorizado';
                break;
            case 'paid':
                $status = 'pago';
                $descricao ='Transação paga';
                $corpo ='Pagamento realizado';
                break;
            case 'refunded':
                $status = 'estornado';
                $descricao = 'Transação estornada completamente';
                $corpo = 'Pagamento estornado';
                break;
            case 'waiting_payment':
                $status = 'aguardando pagamento';
                $descricao = 'Transação aguardando pagamento';
                $corpo = 'Aguardando pagamento';
                break;
            case 'pending_refund':
                $status = 'estorno pendente';
                $descricao = 'Transação do tipo Boleto e que está aguardando confirmação do estorno solicitado';
                $corpo = 'Estorno solicitado';
                break;
            case 'refused':
                $status = 'recusado';
                $descricao = 'Transação recusada, não autorizada';
                $corpo = 'Pagamento recusado, não autorizado';
                $agendamentos->status = 'cancelado';
                $motivo_recusa = MotivoRecusaPagarme($transacao_pagarme);
                Log::channel('my_errors')->error('Agendamento '.$agendamentos->id.' recusado no pagarme',['motivo_recusa' => $motivo_recusa, 'transacao'=> $transacao_pagarme]);
                break;
            case 'chargedback':
                $status = 'chargedback';
                $descricao = 'Transação sofreu chargeback';
                $corpo = 'Pagamento sofreu chargeback';
                break;
            case 'analyzing':
                $descricao = 'Transação encaminhada para a análise manual feita por um especialista em prevenção a fraude';
                $corpo = 'Pagamento encaminhado para a análise manual feita por um especialista em prevenção a fraude';
                break;
            case 'pending_review':
                $status = 'revisao pendente';
                $descricao = 'Transação pendente de revisão manual por parte do lojista';
                $corpo = 'Pagamento pendente de revisão manual por parte do lojista';
                break;
        }

        $agendamentos->status_pagamento = $status;
        $agendamentos->codigo_transacao = $transacao_pagarme->id;
        $agendamentos->update();

        event(new \App\Events\NotificacaoFCM(
            'Agendamento Consulta #'.$agendamentos->id,
            $corpo,
            'tabs/tab1/agendamentos/consulta/'.$agendamentos->id,
            $agendamentos->usuario_id
        ));

        return $transacao_pagarme;

    }



    public function criarTransacaoAgendaExame(Agendamentos $agendamentos, Instituicao $instituicao, array $cartao )
    {

        $transacao = [
            "amount" => realParaCentavos($agendamentos['valor_total']),
            'installments' => $agendamentos['parcelas'],
            "payment_method" => "credit_card",
            'card_cvv' => $cartao['cvv'],
            // 'reference_key' => (string) $pedido['id'],
            // "postback_url" => route('notificacao-pagarme'),
            'capture' => true,
        ];

        if(config('app.env') == 'production'){
                $transacao['postback_url'] = route('api.notificacao-pagarme');
        }

        if(array_key_exists('id_pagarme', $cartao)){
            $transacao['card_id'] = $cartao['id_pagarme'];
        }else{
            $transacao['card_number'] = $cartao['numero_cartao'];
            $transacao['card_holder_name'] = $cartao['nome_cartao'];
            $transacao['card_expiration_date'] = preg_replace('/\D/', '', $cartao['data_validade']);
        }

        $transacao['split_rules'] = [];

        $taxa_pagarme=0;
        if($agendamentos['parcelas'] >= 7){
            $taxa_pagarme = 4.59;
        }else if($agendamentos['parcelas'] >= 2){
            $taxa_pagarme = 4.19;
        }else if($agendamentos['parcelas'] == 1){
            $taxa_pagarme = 3.79;
        }
        $valor_tectotum = ( $instituicao->taxa_tectotum / 100 ) * ( ( $agendamentos['valor_total'] * ( 1 - ( $taxa_pagarme / 100 ) ) ) - 1.2 ) + 1.2 ;
        array_push($transacao['split_rules'], [
            'recipient_id' => $instituicao->id_recebedor,
            'liable' => true, //Se o recebedor é responsável ou não pelo chargeback
            'charge_processing_fee' => true, //Se o recebedor será cobrado das taxas da criação da transação
            'charge_remainder_fee' => true, //Se o recebedor deverá pagar os eventuais restos das taxas, calculadas em porcentagem.
            'amount' => realParaCentavos($agendamentos['valor_total'] - $valor_tectotum),
        ]);



        array_push($transacao['split_rules'], [
            'recipient_id' => config('services.pagarme.conta_default'),
            'liable' => false, //Se o recebedor é responsável ou não pelo chargeback
            'charge_processing_fee' => false, //Se o recebedor será cobrado das taxas da criação da transação
            'charge_remainder_fee' => false, //Se o recebedor deverá pagar os eventuais restos das taxas, calculadas em porcentagem.
            'amount' => realParaCentavos($valor_tectotum),
        ]);

        $customer = [
            "id" => (string) $agendamentos->usuario->customer_id
        ];

        $dados_cobranca = [
            "name" => $cartao['nome_cartao'],
            'address' => [
                'street' => $cartao['rua'],
                'street_number' => $cartao['numero'],
                'neighborhood' => $cartao['bairro'],
                'zipcode' => preg_replace('/\D/', '', $cartao['cep']),
                'state' => $cartao['estado'],
                'city' => $cartao['cidade'],
                'country' => 'br'
            ]
        ];

        $items = [];

        array_push($items, [
        "id" => (string) $agendamentos['id'],
        "title" => $agendamentos['tipo'],
        "unit_price" => realParaCentavos($agendamentos['valor_total']),
        "quantity" => 1,
        "tangible" => false
        ]);



        $transacao["billing"] = $dados_cobranca;
        // $transacao["shipping"] = $dados_envio;
        $transacao["customer"] = $customer;
        $transacao["items"] = $items;
        try {
            $transacao_pagarme = $this->client->transactions()->create($transacao);
        } catch (\Throwable $th) {
            $agendamentos->update(['status'=>'cancelado']);
            Log::channel('my_errors')->error('Agendamento '.$agendamentos->id.' falhou no pagarme', ["resumo"=> '['.$th->getCode().'] "'.$th->getMessage().'" on line '.$th->getTrace()[0]['line'].' of file '.$th->getTrace()[0]['file']]);
            return (object)['error' => 'Erro ao criar transação'];
        }

        if(!array_key_exists('id_pagarme', $cartao)){
            $cartao = UsuarioCartao::create([
                'id_pagarme' => $transacao_pagarme->card->id,
                'ultimos_digitos' => $transacao_pagarme->card->last_digits,
                'bandeira'=> $transacao_pagarme->card->brand,
                'usuario_id'=> $agendamentos->usuario->id,
                'nome'=> $transacao_pagarme->card->holder_name,

                'rua' => $cartao['rua'],
                'numero' => $cartao['numero'],
                'bairro' => $cartao['bairro'],
                'cep' => preg_replace('/\D/', '', $cartao['cep']),
                'estado' => $cartao['estado'],
                'cidade' => $cartao['cidade'],
            ]);

            $agendamentos->update(['cartao_id'=>$cartao->id]);

        }

        $status ='';
        $descricao ='';
        $corpo ='';
        switch ($transacao_pagarme->status) {
            case 'processing':
                $status = 'processando';
                $descricao ='Transação está em processo de autorização';
                $corpo ='Pagamento em processo de autorização';
                break;
            case 'authorized':
                $status = 'autorizado';
                $descricao ='Transação foi autorizada';
                $corpo ='Pagamento autorizado';
                break;
            case 'paid':
                $status = 'pago';
                $descricao ='Transação paga';
                $corpo ='Pagamento realizado';
                break;
            case 'refunded':
                $status = 'estornado';
                $descricao = 'Transação estornada completamente';
                $corpo = 'Pagamento estornado';
                break;
            case 'waiting_payment':
                $status = 'aguardando pagamento';
                $descricao = 'Transação aguardando pagamento';
                $corpo = 'Aguardando pagamento';
                break;
            case 'pending_refund':
                $status = 'estorno pendente';
                $descricao = 'Transação do tipo Boleto e que está aguardando confirmação do estorno solicitado';
                $corpo = 'Estorno solicitado';
                break;
            case 'refused':
                $status = 'recusado';
                $descricao = 'Transação recusada, não autorizada';
                $corpo = 'Pagamento recusado, não autorizado';
                $agendamentos->status = 'cancelado';
                $motivo_recusa = MotivoRecusaPagarme($transacao_pagarme);
                Log::channel('my_errors')->error('Agendamento '.$agendamentos->id.' recusado no pagarme',['motivo_recusa' => $motivo_recusa, 'transacao'=> $transacao_pagarme]);
                break;
            case 'chargedback':
                $status = 'chargedback';
                $descricao = 'Transação sofreu chargeback';
                $corpo = 'Pagamento sofreu chargeback';
                break;
            case 'analyzing':
                $descricao = 'Transação encaminhada para a análise manual feita por um especialista em prevenção a fraude';
                $corpo = 'Pagamento encaminhado para a análise manual feita por um especialista em prevenção a fraude';
                break;
            case 'pending_review':
                $status = 'revisao pendente';
                $descricao = 'Transação pendente de revisão manual por parte do lojista';
                $corpo = 'Pagamento pendente de revisão manual por parte do lojista';
                break;
        }

        $agendamentos->status_pagamento = $status;
        $agendamentos->codigo_transacao = $transacao_pagarme->id;
        $agendamentos->update();

        event(new \App\Events\NotificacaoFCM(
            'Agendamento Exame #'.$agendamentos->id,
            $corpo,
            'tabs/tab1/agendamentos/exame/'.$agendamentos->id,
            $agendamentos->usuario_id
        ));

        return $transacao_pagarme;

    }


    public function criarTransacaoAgendaBateriaExame(array $agendamentos, Instituicao $instituicao, array $cartao )
    {

        $transacao = [
            "amount" => realParaCentavos($agendamentos[0]['valor_total']),
            'installments' => $agendamentos[0]['parcelas'],
            "payment_method" => "credit_card",
            'card_cvv' => $cartao['cvv'],
            // 'reference_key' => (string) $pedido['id'],
            // "postback_url" => route('notificacao-pagarme'),
            'capture' => true,
        ];

        if(config('app.env') == 'production'){
                $transacao['postback_url'] = route('api.notificacao-pagarme');
        }

        if(array_key_exists('id_pagarme', $cartao)){
            $transacao['card_id'] = $cartao['id_pagarme'];
        }else{
            $transacao['card_number'] = $cartao['numero_cartao'];
            $transacao['card_holder_name'] = $cartao['nome_cartao'];
            $transacao['card_expiration_date'] = preg_replace('/\D/', '', $cartao['data_validade']);
        }

        $transacao['split_rules'] = [];

        $taxa_pagarme=0;
        if($agendamentos[0]['parcelas'] >= 7){
            $taxa_pagarme = 4.59;
        }else if($agendamentos[0]['parcelas'] >= 2){
            $taxa_pagarme = 4.19;
        }else if($agendamentos[0]['parcelas'] == 1){
            $taxa_pagarme = 3.79;
        }
        $valor_tectotum = ( $instituicao->taxa_tectotum / 100 ) * ( ( $agendamentos[0]['valor_total'] * ( 1 - ( $taxa_pagarme / 100 ) ) ) - 1.2 ) + 1.2 ;
        array_push($transacao['split_rules'], [
            'recipient_id' => $instituicao->id_recebedor,
            'liable' => true, //Se o recebedor é responsável ou não pelo chargeback
            'charge_processing_fee' => true, //Se o recebedor será cobrado das taxas da criação da transação
            'charge_remainder_fee' => true, //Se o recebedor deverá pagar os eventuais restos das taxas, calculadas em porcentagem.
            'amount' => realParaCentavos($agendamentos[0]['valor_total'] - $valor_tectotum),
        ]);



        array_push($transacao['split_rules'], [
            'recipient_id' => config('services.pagarme.conta_default'),
            'liable' => false, //Se o recebedor é responsável ou não pelo chargeback
            'charge_processing_fee' => false, //Se o recebedor será cobrado das taxas da criação da transação
            'charge_remainder_fee' => false, //Se o recebedor deverá pagar os eventuais restos das taxas, calculadas em porcentagem.
            'amount' => realParaCentavos($valor_tectotum),
        ]);

        $customer = [
            "id" => (string) $agendamentos[0]->usuario->customer_id
        ];

        $dados_cobranca = [
            "name" => $cartao['nome_cartao'],
            'address' => [
                'street' => $cartao['rua'],
                'street_number' => $cartao['numero'],
                'neighborhood' => $cartao['bairro'],
                'zipcode' => preg_replace('/\D/', '', $cartao['cep']),
                'state' => $cartao['estado'],
                'city' => $cartao['cidade'],
                'country' => 'br'
            ]
        ];

        $items = [];
        foreach ($agendamentos as $agendamento) {
            foreach ($agendamento->agendamentoProcedimento as $agendamentoProcedimento) {

                array_push($items, [
                "id" => (string) $agendamentoProcedimento['id'],
                "title" => $agendamentoProcedimento->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento['descricao'],
                "unit_price" => realParaCentavos($agendamentoProcedimento['valor_atual']),
                "quantity" => 1,
                "tangible" => false
                ]);
            }
        }



        $transacao["billing"] = $dados_cobranca;
        // $transacao["shipping"] = $dados_envio;
        $transacao["customer"] = $customer;
        $transacao["items"] = $items;
        try {
            $transacao_pagarme = $this->client->transactions()->create($transacao);
        } catch (\Throwable $th) {

            foreach ($agendamentos as $agendamento) {
                $agendamento->update(['status' =>'cancelado']);
                Log::channel('my_errors')->error('Agendamento '.$agendamento->id.' falhou no pagarme', ["resumo"=> '['.$th->getCode().'] "'.$th->getMessage().'" on line '.$th->getTrace()[0]['line'].' of file '.$th->getTrace()[0]['file']]);

            }
            return (object)['error' => 'Erro ao criar transação'];
        }

        if(!array_key_exists('id_pagarme', $cartao)){
            $cartao = UsuarioCartao::create([
                'id_pagarme' => $transacao_pagarme->card->id,
                'ultimos_digitos' => $transacao_pagarme->card->last_digits,
                'bandeira'=> $transacao_pagarme->card->brand,
                'usuario_id'=> $agendamentos[0]->usuario->id,
                'nome'=> $transacao_pagarme->card->holder_name,

                'rua' => $cartao['rua'],
                'numero' => $cartao['numero'],
                'bairro' => $cartao['bairro'],
                'cep' => preg_replace('/\D/', '', $cartao['cep']),
                'estado' => $cartao['estado'],
                'cidade' => $cartao['cidade'],
            ]);

            foreach ($agendamentos as $agendamento) {
                $agendamento->update(['cartao_id'=>$cartao->id]);
            }

        }

        $status ='';
        $descricao ='';
        $corpo ='';
        switch ($transacao_pagarme->status) {
            case 'processing':
                $status = 'processando';
                $descricao ='Transação está em processo de autorização';
                $corpo ='Pagamento em processo de autorização';
                break;
            case 'authorized':
                $status = 'autorizado';
                $descricao ='Transação foi autorizada';
                $corpo ='Pagamento autorizado';
                break;
            case 'paid':
                $status = 'pago';
                $descricao ='Transação paga';
                $corpo ='Pagamento realizado';
                break;
            case 'refunded':
                $status = 'estornado';
                $descricao = 'Transação estornada completamente';
                $corpo = 'Pagamento estornado';
                break;
            case 'waiting_payment':
                $status = 'aguardando pagamento';
                $descricao = 'Transação aguardando pagamento';
                $corpo = 'Aguardando pagamento';
                break;
            case 'pending_refund':
                $status = 'estorno pendente';
                $descricao = 'Transação do tipo Boleto e que está aguardando confirmação do estorno solicitado';
                $corpo = 'Estorno solicitado';
                break;
            case 'refused':
                $status = 'recusado';
                $descricao = 'Transação recusada, não autorizada';
                $corpo = 'Pagamento recusado, não autorizado';
                foreach ($agendamentos as $agendamento) {
                    $agendamento->update(['status' =>'cancelado']);
                    $motivo_recusa = MotivoRecusaPagarme($transacao_pagarme);
                    Log::channel('my_errors')->error('Agendamento '.$agendamento->id.' cancelado no pagarme', ["resumo"=> "Transação recusada, não autorizada",'motivo_recusa' => ($motivo_recusa), "transacao" => $transacao_pagarme]);
                }
                break;
            case 'chargedback':
                $status = 'chargedback';
                $descricao = 'Transação sofreu chargeback';
                $corpo = 'Pagamento sofreu chargeback';
                break;
            case 'analyzing':
                $descricao = 'Transação encaminhada para a análise manual feita por um especialista em prevenção a fraude';
                $corpo = 'Pagamento encaminhado para a análise manual feita por um especialista em prevenção a fraude';
                break;
            case 'pending_review':
                $status = 'revisao pendente';
                $descricao = 'Transação pendente de revisão manual por parte do lojista';
                $corpo = 'Pagamento pendente de revisão manual por parte do lojista';
                break;
        }

        foreach ($agendamentos as $agendamento) {
            $agendamento->status_pagamento = $status;
            $agendamento->codigo_transacao = $transacao_pagarme->id;
            $agendamento->update();
        }


        event(new \App\Events\NotificacaoFCM(
            'Agendamento Exame #'.$agendamentos[0]->id,
            $corpo,
            'tabs/tab1/agendamentos/exame/'.$agendamentos[0]->id,
            $agendamentos[0]->usuario_id
        ));

        return $transacao_pagarme;

    }
}
