<?php

namespace App\Http\Controllers\Instituicao;

require('../public/lib/openboleto/openboleto/autoloader.php');

use App\ContaReceber;
use App\Http\Controllers\Controller;
use App\Instituicao;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use OpenBoleto\Banco\BancoDoBrasil as BancoDoBrasil;
use OpenBoleto\Agente as Agente;
use Mpdf\QrCode\QrCode as QrCode;
use Mpdf\QrCode\Output as Output;

class ApiBB extends Controller
{
    protected $url_aut = 'https://oauth.bb.com.br/oauth/token?grant_type=client_credentials&scope=cobrancas.boletos-info cobrancas.boletos-requisicao';
    protected $url_reg = 'https://api.bb.com.br/cobrancas/v2/boletos?gw-dev-app-key={gw-api}';

    protected $dados_test = [
        'cnpj' => '36357991000101',
        'razao_social' => 'GUILHERME EDUARD FERREIRA',
        'codigo_cedente' => '3541637',
        'developer_application_key' => '7091008b07ffbe00136ae181a0050b56b9e1a5bb',
        'indicador_pix' => '6c5162db-87a4-45e2-ba7e-dd9dc140f7f3',
        'client_id' => 'eyJpZCI6IjQ3MjZlZTUtZTQzNi00Mzg2LTk0YSIsImNvZGlnb1B1YmxpY2Fkb3IiOjAsImNvZGlnb1NvZnR3YXJlIjozNTI4Niwic2VxdWVuY2lhbEluc3RhbGFjYW8iOjF9',
        'client_secret' => "Basic ZXlKcFpDSTZJalEzTWpabFpUVXRaVFF6TmkwME16ZzJMVGswWVNJc0ltTnZaR2xuYjFCMVlteHBZMkZrYjNJaU9qQXNJbU52WkdsbmIxTnZablIzWVhKbElqb3pOVEk0Tml3aWMyVnhkV1Z1WTJsaGJFbHVjM1JoYkdGallXOGlPakY5OmV5SnBaQ0k2SW1FNUlpd2lZMjlrYVdkdlVIVmliR2xqWVdSdmNpSTZNQ3dpWTI5a2FXZHZVMjltZEhkaGNtVWlPak0xTWpnMkxDSnpaWEYxWlc1amFXRnNTVzV6ZEdGc1lXTmhieUk2TVN3aWMyVnhkV1Z1WTJsaGJFTnlaV1JsYm1OcFlXd2lPakVzSW1GdFltbGxiblJsSWpvaWNISnZaSFZqWVc4aUxDSnBZWFFpT2pFMk5qWTRPRFUyTWpBeU56Tjk=",
        'url_aut' => 'https://oauth.sandbox.bb.com.br/oauth/token?grant_type=client_credentials&scope=cobrancas.boletos-info cobrancas.boletos-requisicao',
        'url_reg' => 'https://api.sandbox.bb.com.br/cobrancas/v2/boletos?gw-dev-app-key={gw-api}'
    ];

    protected $aut;
    private $boleto;
    private $erros;

    public $instituicao;

    public function __construct()
    {
        $this->aut = [];
        $this->boleto = [];
        $this->errors = [];
    }

    public function registrarBoleto(Request $request, ContaReceber $conta_rec)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        if($this->getAut()){
            if($this->envioBoleto($conta_rec)){
                return $this->getBoleto();
            }else{
                return $this->getErros();
            }
        }else{
            return $this->getErros();
        }
    }

    public function getErros(){
        return $this->errors;
    }

    public function getBoleto(){
        return $this->boleto;
    }

    private function envioBoleto(ContaReceber $conta_rec){
        
        $url = str_replace("{gw-api}", $this->instituicao->apibb_gw_dev_app_key, $this->url_reg);
        $url = str_replace("{gw-api}", $this->dados_test['developer_application_key'], $this->url_reg);

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            // 'Authorization' => $this->instituicao->apibb_client_secret,
            'Authorization' => $this->aut['token_type']." ".$this->aut['access_token']
        ];
        
        if($conta_rec->agendamento_id){
            $numeroInscricao = (empty($conta_rec->agendamentos->boleto_acompanhante)) ? str_replace(["-", ".", "/"], ["", "", ""], $conta_rec->paciente->cpf) : str_replace(["-", ".", "/"], ["", "", ""], $conta_rec->agendamentos->cpf_acompanhante);
            $nome = (empty($conta_rec->agendamentos->boleto_acompanhante)) ? $this->tirarAcentos($conta_rec->paciente->nome) : $this->tirarAcentos($conta_rec->agendamentos->acompanhante_nome);
        }else{
            $numeroInscricao = (!$conta_rec->paciente->gerar_via_acompanhante) ? str_replace(["-", ".", "/"], ["", "", ""], $conta_rec->paciente->cpf) : str_replace(["-", ".", "/"], ["", "", ""], $conta_rec->paciente->referencia_documento);
            $nome = (!$conta_rec->paciente->gerar_via_acompanhante) ? $this->tirarAcentos($conta_rec->paciente->nome) : $this->tirarAcentos($conta_rec->paciente->referencia_nome);
        }
        
        $dados =  [
            "numeroConvenio" => $this->instituicao->apibb_codigo_cedente,
            // "numeroConvenio" => $this->dados_test['codigo_cedente'],
            "dataVencimento" => date("d.m.Y", strtotime($conta_rec->data_vencimento)),
            "valorOriginal" => $conta_rec->valor_parcela,
            "numeroCarteira" => "17",
            "numeroVariacaoCarteira" => "19",
            "codigoModalidade" => "1",
            "dataEmissao" => date('d.m.Y'),
            // "dataEmissao" => '26.07.2022',
            "valorAbatimento" => "",
            "quantidadeDiasProtesto" => "0",
            "quantidadeDiasNegativacao" => "",
            "orgaoNegativador" => "",
            "indicadorAceiteTituloVencido" => "N",
            "numeroDiasLimiteRecebimento" => !empty($this->instituicao->dias_pagamento) ? $this->instituicao->dias_pagamento : 0,
            "codigoAceite" => "N",
            "codigoTipoTitulo" => "2",
            "descricaoTipoTitulo" => "DM",
            "indicadorPermissaoRecebimentoParcial" => "N",
            "numeroTituloBeneficiario" => str_pad($conta_rec->id, 10, "0", STR_PAD_LEFT),
            "campoUtilizacaoBeneficiario" => "",
            "numeroTituloCliente" => "000".$this->instituicao->apibb_codigo_cedente.str_pad($conta_rec->id, 10, "0", STR_PAD_LEFT),
            "mensagemBloquetoOcorrencia" => "",
            "desconto" => [
                "tipo" => "0",
                "dataExpiracao" => "",
                "porcentagem" => "",
                "valor" => ""
            ],
            "segundoDesconto" => [
                "dataExpiracao" => "",
                "porcentagem" => "",
                "valor" => ""
            ],
            "terceiroDesconto" => [
                "dataExpiracao" => "",
                "porcentagem" => "",
                "valor" => ""
            ],
            "jurosMora" => [
                "tipo" => !empty((float) $this->instituicao->p_juros) ? 2 : 0,
                "porcentagem" => !empty((float) $this->instituicao->p_juros) ? str_replace(",", ".", (float) $this->instituicao->p_juros) : "",
                "valor" => ""
            ],
            "multa" => [
                "tipo" => !empty((float) $this->instituicao->p_multa) ? 2 : 0,
                "data" => !empty((float) $this->instituicao->p_multa) ? date("d.m.Y", strtotime("+1 day", strtotime($conta_rec->data_vencimento))) : "",
                "porcentagem" => !empty((float) $this->instituicao->p_multa) ? str_replace(",", ".", (float) $this->instituicao->p_multa) : "",
                "valor" => ""
            ],
            "pagador" => [
                "tipoInscricao" => "1",
                "numeroInscricao" => $numeroInscricao,
                "nome" => $nome,
                "endereco" => $this->tirarAcentos($conta_rec->paciente->rua),
                "cep" => str_replace(["-"], [""], $conta_rec->paciente->cep),
                "cidade" => $this->tirarAcentos($conta_rec->paciente->cidade),
                "bairro" => $this->tirarAcentos($conta_rec->paciente->bairro),
                "uf" => $conta_rec->paciente->estado,
                "telefone" => str_replace(["-", "(", ")", " "], ["","","",""], $conta_rec->paciente->telefone1),
            ],
            "beneficiarioFinal" => [
                "tipoInscricao" => "",
                "numeroInscricao" => "",
                "nome" => ""
            ],
            "indicadorPix" => "S"
        ];

        $conn = Http::withHeaders($headers)
            ->acceptJson()
            ->withBody(json_encode($dados), 'application/json')
        ->post($url);

        if($conn->ok()){
            $this->boleto = $conn->json();
            return true;
        }else{
            $this->errors = $conn->json();
            return false;
        }

        // dd($url, $conn->body(), $conn->json());
    }

    private function getAut(){
        // $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $url = $this->url_aut;

        if($this->instituicao->apibb_possui){
            $headers = [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => $this->instituicao->apibb_client_secret,
                // 'Authorization' => $this->dados_test['client_secret'],
            ];

            $conn = Http::withHeaders($headers)
                ->acceptJson()
            ->post($url);
            
            if($conn->ok()){
                $this->aut = $conn->json();
                return true;
            }else{
                $this->errors = array("erros" => $conn->json());
                return false;
            }

        }else{
            $this->errors = ['erros' => 'erro instituicao não posssui integração'];
            return false;
        }
    }

    public function printBoleto(ContaReceber $conta_rec){
        if(empty($this->instituicao)){
            $this->instituicao = Instituicao::find($conta_rec->instituicao_id);
        }

        $cedente = new Agente(
            $this->instituicao->razao_social,
            $this->instituicao->cnpj,
            // $this->dados_test['razao_social'],
            // $this->dados_test['cnpj'],
            $this->instituicao->rua.", ".$this->instituicao->numero." ".$this->instituicao->complemento." ".$this->instituicao->bairro,
            $this->instituicao->cep,
            $this->instituicao->cidade,
            $this->instituicao->estado
        );

        if($conta_rec->odontologico_id){
            $numeroInscricao = (!$conta_rec->paciente->gerar_via_acompanhante) ? $conta_rec->paciente->cpf : $conta_rec->paciente->referencia_documento;
            $nome = (!$conta_rec->paciente->gerar_via_acompanhante) ? $conta_rec->paciente->nome : $conta_rec->paciente->referencia_nome;
        }else{
            $numeroInscricao = $conta_rec->paciente->nome;
            $nome = $conta_rec->paciente->cpf;
        }

        $sacado = new Agente(
            $nome,
            $numeroInscricao,
            $conta_rec->paciente->rua.", ".$conta_rec->paciente->numero." ".$conta_rec->paciente->complemento." ".$conta_rec->paciente->bairro,
            $conta_rec->paciente->cep,
            $conta_rec->paciente->cidade,
            $conta_rec->paciente->estado
        );

        $dadosboleto["data_vencimento"] = date("Y-m-d", strtotime($conta_rec->data_vencimento)); // REGRA: Formato DD/MM/AAAA
        $dadosboleto["valor_boleto"] = $conta_rec->valor_parcela;   // Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
        $dadosboleto["sequencial"] = $conta_rec->id; // Número sequencial do documento
        $dadosboleto["sacado"] = $sacado;
        $dadosboleto["cedente"] = $cedente;

        $dadosboleto["instrucoes"] = [];

        //IMG PIX
        if(!empty($conta_rec->apibb_qrcode_emv)){
            $obQrCode = new QrCode($conta_rec->apibb_qrcode_emv);
            $imagePix = (new Output\Png)->output($obQrCode,400);
        }else{
            $imagePix = null;
        }

        // dd($dadosboleto["data_vencimento"], $conta_rec->data_vencimento);

        $gerar_boleto = new BancoDoBrasil(array(
            // Parâmetros obrigatórios
            'dataVencimento' => new DateTime($dadosboleto["data_vencimento"]),
            'valor' => $dadosboleto["valor_boleto"],
            'sequencial' => $dadosboleto["sequencial"], // Para gerar o nosso número
            'sacado' => $dadosboleto["sacado"] = $sacado,
            'cedente' => $dadosboleto["cedente"],
            'agencia' => $this->instituicao->agencia, // Até 4 dígitos
            'carteira' => '17',
            'conta' => $this->instituicao->conta, // Até 8 dígitos
            'convenio' => $this->instituicao->apibb_codigo_cedente, // 4, 6 ou 7 dígitos
            // 'convenio' => $this->dados_test['codigo_cedente'], // 4, 6 ou 7 dígitos
            'contaDv' => $this->instituicao->digito_conta,
            'agenciaDv' => $this->instituicao->digito_agencia,    
            'numeroDocumento' => $dadosboleto["sequencial"],
            'especieDoc' => 'RC',    
            'instrucoes' => $dadosboleto['instrucoes'],
            'imgPIx' => $imagePix
        ));
    
        // $pdf = App::make('dompdf.wrapper');
        // $pdf->loadHTML($gerar_boleto->getOutput());
        // return $pdf->stream();

        return $gerar_boleto->getOutput();
    } 

    private function tirarAcentos($string){
        return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/", "/(ç)/", "/(Ç)/"),explode(" ","a A e E i I o O u U n N c C"),$string);
    }

    function inverteData($date = null, $sep = null) {
        $data = substr($date, 0, 10);
        $restante = substr($date, 10);
        $data = str_replace("-", '/', $data);
        $array = explode("/", $data);
        return $array[2] . $sep . $array[1] . $sep . $array[0] . $restante;
    }
}
