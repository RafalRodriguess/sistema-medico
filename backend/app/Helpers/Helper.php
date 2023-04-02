<?php

const Bancos = [
    "001" => "Banco do Brasil S.A.",
    "070" => "BRB - BANCO DE BRASILIA S.A.",
    "272" => "AGK CORRETORA DE CAMBIO S.A. ",
    "136" => "CONFEDERAÇÃO NACIONAL DAS COOPERATIVAS CENTRAIS UNICRED LTDA. - UNICRED DO BRASI",
    "104" => "CAIXA ECONOMICA FEDERAL",
    "077" => "Banco Inter S.A",
    "741" => "BANCO RIBEIRAO PRETO S.A.",
    "330" => "BANCO BARI DE INVESTIMENTOS E FINANCIAMENTOS S.A.",
    "739" => "Banco Cetelem S.A",
    "743" => "Banco Semear S.A.",
    "100" => "Planner Corretora de Valores S.A.",
    "096" => "Banco B3 S.A.",
    "747" => "Banco Rabobank International Brasil S.A.",
    "362" => "CIELO S.A.",
    "322" => "Cooperativa de Crédito Rural de Abelardo Luz - Sulcredi/Crediluz                ",
    "748" => "BANCO COOPERATIVO SICREDI S.A.                                                  ",
    "350" => "COOPERATIVA DE CRÉDITO RURAL DE PEQUENOS AGRICULTORES E DA REFORMA AGRÁRIA DO CE",
    "752" => "Banco BNP Paribas Brasil S.A.                                                   ",
    "091" => "CENTRAL DE COOPERATIVAS DE ECONOMIA E CRÉDITO MÚTUO DO ESTADO DO RIO GRANDE DO S",
    "399" => "Kirton Bank S.A. - Banco Múltiplo                                               ",
    "108" => "PORTOCRED S.A. - CREDITO, FINANCIAMENTO E INVESTIMENTO                          ",
    "756" => "BANCO COOPERATIVO DO BRASIL S.A. - BANCOOB                                      ",
    "360" => "TRINUS CAPITAL DISTRIBUIDORA DE TÍTULOS E VALORES MOBILIÁRIOS S.A.              ",
    "757" => "BANCO KEB HANA DO BRASIL S.A.                                                   ",
    "102" => "XP INVESTIMENTOS CORRETORA DE CÂMBIO,TÍTULOS E VALORES MOBILIÁRIOS S/A          ",
    "084" => "UNIPRIME NORTE DO PARANÁ - COOPERATIVA DE CRÉDITO LTDA                          ",
    "180" => "CM CAPITAL MARKETS CORRETORA DE CÂMBIO, TÍTULOS E VALORES MOBILIÁRIOS LTDA ",
    "066" => "BANCO MORGAN STANLEY S.A.                                                       ",
    "015" => "UBS Brasil Corretora de Câmbio, Títulos e Valores Mobiliários S.A.              ",
    "143" => "Treviso Corretora de Câmbio S.A.                                                ",
    "062" => "Hipercard Banco Múltiplo S.A.                                                   ",
    "074" => "Banco J. Safra S.A.                                                             ",
    "099" => "UNIPRIME CENTRAL - CENTRAL INTERESTADUAL DE COOPERATIVAS DE CREDITO LTDA.       ",
    "387" => "Banco Toyota do Brasil S.A.                                                     ",
    "326" => "PARATI - CREDITO, FINANCIAMENTO E INVESTIMENTO S.A.                             ",
    "025" => "Banco Alfa S.A.                                                                 ",
    "315" => "PI Distribuidora de Títulos e Valores Mobiliários S.A.                          ",
    "075" => "Banco ABN Amro S.A.                                                             ",
    "040" => "Banco Cargill S.A.                                                              ",
    "307" => "Terra Investimentos Distribuidora de Títulos e Valores Mobiliários Ltda.        ",
    "190" => "SERVICOOP - COOPERATIVA DE CRÉDITO DOS SERVIDORES PÚBLICOS ESTADUAIS DO RIO GRAN",
    "296" => "VISION S.A. CORRETORA DE CAMBIO                                                 ",
    "063" => "Banco Bradescard S.A.                                                           ",
    "191" => "Nova Futura Corretora de Títulos e Valores Mobiliários Ltda.                    ",
    "064" => "GOLDMAN SACHS DO BRASIL BANCO MULTIPLO S.A.                                     ",
    "097" => "Credisis - Central de Cooperativas de Crédito Ltda.                             ",
    "016" => "COOPERATIVA DE CRÉDITO MÚTUO DOS DESPACHANTES DE TRÂNSITO DE SANTA CATARINA E RI",
    "299" => "SOROCRED   CRÉDITO, FINANCIAMENTO E INVESTIMENTO S.A.                           ",
    "012" => "Banco Inbursa S.A.                                                              ",
    "003" => "BANCO DA AMAZONIA S.A.                                                          ",
    "060" => "Confidence Corretora de Câmbio S.A.                                             ",
    "037" => "Banco do Estado do Pará S.A.                                                    ",
    "359" => "ZEMA CRÉDITO, FINANCIAMENTO E INVESTIMENTO S/A                                  ",
    "159" => "Casa do Crédito S.A. Sociedade de Crédito ao Microempreendedor                  ",
    "085" => "Cooperativa Central de Crédito - Ailos                                          ",
    "114" => "Central Cooperativa de Crédito no Estado do Espírito Santo - CECOOP             ",
    "036" => "Banco Bradesco BBI S.A.                                                         ",
    "394" => "Banco Bradesco Financiamentos S.A.                                              ",
    "004" => "Banco do Nordeste do Brasil S.A.                                                ",
    "320" => "China Construction Bank (Brasil) Banco Múltiplo S/A                             ",
    "189" => "HS FINANCEIRA S/A CREDITO, FINANCIAMENTO E INVESTIMENTOS                        ",
    "105" => "Lecca Crédito, Financiamento e Investimento S/A                                 ",
    "076" => "Banco KDB do Brasil S.A.                                                        ",
    "082" => "BANCO TOPÁZIO S.A.                                                              ",
    "286" => "COOPERATIVA DE CRÉDITO RURAL DE OURO   SULCREDI/OURO                            ",
    "093" => "PÓLOCRED   SOCIEDADE DE CRÉDITO AO MICROEMPREENDEDOR E À EMPRESA DE PEQUENO PORT",
    "391" => "COOPERATIVA DE CREDITO RURAL DE IBIAM - SULCREDI/IBIAM                          ",
    "273" => "Cooperativa de Crédito Rural de São Miguel do Oeste - Sulcredi/São Miguel       ",
    "368" => "Banco CSF S.A.                                                                  ",
    "290" => "Pagseguro  S.A.                                                         ",
    "259" => "MONEYCORP BANCO DE CÂMBIO S.A.                                                  ",
    "364" => "GERENCIANET PAGAMENTOS DO BRASIL LTDA                                           ",
    "157" => "ICAP do Brasil Corretora de Títulos e Valores Mobiliários Ltda.                 ",
    "183" => "SOCRED S.A. - SOCIEDADE DE CRÉDITO AO MICROEMPREENDEDOR E À EMPRESA DE PEQUENO P",
    "014" => "STATE STREET BRASIL S.A. ? BANCO COMERCIAL                                      ",
    "130" => "CARUANA S.A. - SOCIEDADE DE CRÉDITO, FINANCIAMENTO E INVESTIMENTO               ",
    "127" => "Codepe Corretora de Valores e Câmbio S.A.                                       ",
    "079" => "Banco Original do Agronegócio S.A.                                              ",
    "340" => "Super Pagamentos e Administração de Meios Eletrônicos S.A.                      ",
    "081" => "BancoSeguro S.A.                                                                ",
    "133" => "CONFEDERAÇÃO NACIONAL DAS COOPERATIVAS CENTRAIS DE CRÉDITO E ECONOMIA FAMILIAR E",
    "323" => "MERCADOPAGO.COM REPRESENTACOES LTDA.                                            ",
    "121" => "Banco Agibank S.A.                                                              ",
    "083" => "Banco da China Brasil S.A.                                                      ",
    "138" => "Get Money Corretora de Câmbio S.A.                                              ",
    "024" => "Banco Bandepe S.A.                                                              ",
    "319" => "OM DISTRIBUIDORA DE TÍTULOS E VALORES MOBILIÁRIOS LTDA                          ",
    "274" => "MONEY PLUS SOCIEDADE DE CRÉDITO AO MICROEMPREENDEDOR E A EMPRESA DE PEQUENO PORT",
    "095" => "Travelex Banco de Câmbio S.A.                                                   ",
    "094" => "Banco Finaxis S.A.                                                              ",
    "276" => "Senff S.A. - Crédito, Financiamento e Investimento                              ",
    "092" => "BRK S.A. Crédito, Financiamento e Investimento                                  ",
    "047" => "Banco do Estado de Sergipe S.A.                                                 ",
    "144" => "BEXS BANCO DE CÂMBIO S/A                                                        ",
    "332" => "Acesso Soluções de Pagamento S.A.                                               ",
    "126" => "BR Partners Banco de Investimento S.A.                                          ",
    "325" => "Órama Distribuidora de Títulos e Valores Mobiliários S.A.                       ",
    "301" => "BPP Instituição de Pagamento S.A.                                               ",
    "173" => "BRL Trust Distribuidora de Títulos e Valores Mobiliários S.A.                   ",
    "331" => "Fram Capital Distribuidora de Títulos e Valores Mobiliários S.A.                ",
    "119" => "Banco Western Union do Brasil S.A.                                              ",
    "309" => "CAMBIONET CORRETORA DE CÂMBIO LTDA.                                             ",
    "254" => "PARANÁ BANCO S.A.                                                               ",
    "268" => "BARI COMPANHIA HIPOTECÁRIA                                                      ",
    "107" => "Banco Bocom BBM S.A.                                                            ",
    "412" => "BANCO CAPITAL S.A.                                                              ",
    "124" => "Banco Woori Bank do Brasil S.A.                                                 ",
    "149" => "Facta Financeira S.A. - Crédito Financiamento e Investimento                    ",
    "197" => "Stone Pagamentos S.A.                                                           ",
    "142" => "Broker Brasil Corretora de Câmbio Ltda.                                         ",
    "389" => "Banco Mercantil do Brasil S.A.                                                  ",
    "184" => "Banco Itaú BBA S.A.                                                             ",
    "634" => "BANCO TRIANGULO S.A.                                                            ",
    "545" => "SENSO CORRETORA DE CAMBIO E VALORES MOBILIARIOS S.A                             ",
    "132" => "ICBC do Brasil Banco Múltiplo S.A.                                              ",
    "298" => "Vip's Corretora de Câmbio Ltda.                                                 ",
    "321" => "CREFAZ SOCIEDADE DE CRÉDITO AO MICROEMPREENDEDOR E A EMPRESA DE PEQUENO PORTE LT",
    "260" => "Nu Pagamentos S.A.                                                              ",
    "129" => "UBS Brasil Banco de Investimento S.A.                                           ",
    "128" => "MS Bank S.A. Banco de Câmbio                                                    ",
    "194" => "PARMETAL DISTRIBUIDORA DE TÍTULOS E VALORES MOBILIÁRIOS LTDA                    ",
    "383" => "BOLETOBANCÁRIO.COM TECNOLOGIA DE PAGAMENTOS LTDA.                               ",
    "310" => "VORTX DISTRIBUIDORA DE TITULOS E VALORES MOBILIARIOS LTDA.                      ",
    "163" => "Commerzbank Brasil S.A. - Banco Múltiplo                                        ",
    "280" => "Avista S.A. Crédito, Financiamento e Investimento                               ",
    "146" => "GUITTA CORRETORA DE CAMBIO LTDA.                                          ",
    "343" => "FFA SOCIEDADE DE CRÉDITO AO MICROEMPREENDEDOR E À EMPRESA DE PEQUENO PORTE LTDA.",
    "279" => "COOPERATIVA DE CREDITO RURAL DE PRIMAVERA DO LESTE                              ",
    "335" => "Banco Digio S.A.                                                                ",
    "349" => "AMAGGI S.A. - CRÉDITO, FINANCIAMENTO E INVESTIMENTO                             ",
    "278" => "Genial Investimentos Corretora de Valores Mobiliários S.A.                      ",
    "271" => "IB Corretora de Câmbio, Títulos e Valores Mobiliários S.A.                      ",
    "021" => "BANESTES S.A. BANCO DO ESTADO DO ESPIRITO SANTO                                 ",
    "246" => "Banco ABC Brasil S.A.                                                           ",
    "292" => "BS2 Distribuidora de Títulos e Valores Mobiliários S.A.                         ",
    "751" => "Scotiabank Brasil S.A. Banco Múltiplo                                           ",
    "352" => "TORO CORRETORA DE TÍTULOS E VALORES MOBILIÁRIOS LTDA                            ",
    "208" => "Banco BTG Pactual S.A.                                                          ",
    "746" => "Banco Modal S.A.                                                                ",
    "241" => "BANCO CLASSICO S.A.                                                             ",
    "336" => "Banco C6 S.A.                                                                   ",
    "612" => "Banco Guanabara S.A.                                                            ",
    "604" => "Banco Industrial do Brasil S.A.                                                 ",
    "505" => "Banco Credit Suisse (Brasil) S.A.                                               ",
    "329" => "QI Sociedade de Crédito Direto S.A.                                             ",
    "196" => "FAIR CORRETORA DE CAMBIO S.A.                                                   ",
    "342" => "Creditas Sociedade de Crédito Direto S.A.                                       ",
    "300" => "Banco de la Nacion Argentina                                                    ",
    "477" => "Citibank N.A.                                                                   ",
    "266" => "BANCO CEDULA S.A.                                                               ",
    "122" => "Banco Bradesco BERJ S.A.                                                        ",
    "376" => "BANCO J.P. MORGAN S.A.                                                          ",
    "348" => "Banco XP S.A.                                                                   ",
    "473" => "Banco Caixa Geral - Brasil S.A.                                                 ",
    "745" => "Banco Citibank S.A.                                                             ",
    "120" => "BANCO RODOBENS S.A.                                                             ",
    "265" => "Banco Fator S.A.                                                                ",
    "007" => "BANCO NACIONAL DE DESENVOLVIMENTO ECONOMICO E SOCIAL                            ",
    "188" => "ATIVA INVESTIMENTOS S.A. CORRETORA DE TÍTULOS, CÂMBIO E VALORES                 ",
    "134" => "BGC LIQUIDEZ DISTRIBUIDORA DE TÍTULOS E VALORES MOBILIÁRIOS LTDA                ",
    "029" => "Banco Itaú Consignado S.A.                                                      ",
    "243" => "Banco Máxima S.A.                                                               ",
    "078" => "Haitong Banco de Investimento do Brasil S.A.                                    ",
    "355" => "ÓTIMO SOCIEDADE DE CRÉDITO DIRETO S.A.                                          ",
    "367" => "VITREO DISTRIBUIDORA DE TÍTULOS E VALORES MOBILIÁRIOS S.A.                      ",
    "373" => "UP.P SOCIEDADE DE EMPRÉSTIMO ENTRE PESSOAS S.A.                                 ",
    "111" => "OLIVEIRA TRUST DISTRIBUIDORA DE TÍTULOS E VALORES MOBILIARIOS S.A.              ",
    "306" => "PORTOPAR DISTRIBUIDORA DE TITULOS E VALORES MOBILIARIOS LTDA.                   ",
    "017" => "BNY Mellon Banco S.A.                                                           ",
    "174" => "PERNAMBUCANAS FINANCIADORA S.A. - CRÉDITO, FINANCIAMENTO E INVESTIMENTO         ",
    "495" => "Banco de La Provincia de Buenos Aires                                           ",
    "125" => "Plural S.A. Banco Múltiplo                                                      ",
    "488" => "JPMorgan Chase Bank, National Association                                       ",
    "065" => "Banco AndBank (Brasil) S.A.                                                     ",
    "492" => "ING Bank N.V.                                                                   ",
    "145" => "LEVYCAM - CORRETORA DE CAMBIO E VALORES LTDA.                                   ",
    "250" => "BCV - BANCO DE CRÉDITO E VAREJO S.A.                                            ",
    "354" => "NECTON INVESTIMENTOS  S.A. CORRETORA DE VALORES MOBILIÁRIOS E COMMODITIES       ",
    "253" => "Bexs Corretora de Câmbio S/A                                                    ",
    "269" => "BANCO HSBC S.A.                                                                 ",
    "213" => "Banco Arbi S.A.                                                                 ",
    "139" => "Intesa Sanpaolo Brasil S.A. - Banco Múltiplo                                    ",
    "018" => "Banco Tricury S.A.                                                              ",
    "422" => "Banco Safra S.A.                                                                ",
    "630" => "Banco Smartbank S.A.                                                            ",
    "224" => "Banco Fibra S.A.                                                                ",
    "600" => "Banco Luso Brasileiro S.A.                                                      ",
    "390" => "BANCO GM S.A.                                                                   ",
    "623" => "Banco Pan S.A.                                                                  ",
    "655" => "Banco Votorantim S.A.                                                           ",
    "479" => "Banco ItauBank S.A.                                                             ",
    "456" => "Banco MUFG Brasil S.A.                                                          ",
    "464" => "Banco Sumitomo Mitsui Brasileiro S.A.                                           ",
    "341" => "ITAÚ UNIBANCO S.A.                                                              ",
    "237" => "Banco Bradesco S.A.                                                             ",
    "381" => "BANCO MERCEDES-BENZ DO BRASIL S.A.                                              ",
    "613" => "Omni Banco S.A.                                                                 ",
    "652" => "Itaú Unibanco Holding S.A.                                                      ",
    "637" => "BANCO SOFISA S.A.                                                               ",
    "653" => "BANCO INDUSVAL S.A.                                                             ",
    "069" => "Banco Crefisa S.A.                                                              ",
    "370" => "Banco Mizuho do Brasil S.A.                                                     ",
    "249" => "Banco Investcred Unibanco S.A.                                                  ",
    "318" => "Banco BMG S.A.                                                                  ",
    "626" => "BANCO FICSA S.A.                                                                ",
    "270" => "Sagitur Corretora de Câmbio Ltda.                                               ",
    "366" => "BANCO SOCIETE GENERALE BRASIL S.A.                                              ",
    "113" => "Magliano S.A. Corretora de Cambio e Valores Mobiliarios                         ",
    "131" => "TULLETT PREBON BRASIL CORRETORA DE VALORES E CÂMBIO LTDA                        ",
    "011" => "CREDIT SUISSE HEDGING-GRIFFO CORRETORA DE VALORES S.A                           ",
    "611" => "Banco Paulista S.A.                                                             ",
    "755" => "Bank of America Merrill Lynch Banco Múltiplo S.A.                               ",
    "089" => "CREDISAN COOPERATIVA DE CRÉDITO                                                 ",
    "643" => "Banco Pine S.A.                                                                 ",
    "140" => "Easynvest - Título Corretora de Valores SA                                      ",
    "707" => "Banco Daycoval S.A.                                                             ",
    "288" => "CAROL DISTRIBUIDORA DE TITULOS E VALORES MOBILIARIOS LTDA.                      ",
    "363" => "SOCOPA SOCIEDADE CORRETORA PAULISTA S.A.                                        ",
    "101" => "RENASCENCA DISTRIBUIDORA DE TÍTULOS E VALORES MOBILIÁRIOS LTDA                  ",
    "487" => "DEUTSCHE BANK S.A. - BANCO ALEMAO                                               ",
    "233" => "Banco Cifra S.A.                                                                ",
    "177" => "Guide Investimentos S.A. Corretora de Valores                                   ",
    "365" => "SOLIDUS S.A. CORRETORA DE CAMBIO E VALORES MOBILIARIOS                          ",
    "633" => "Banco Rendimento S.A.                                                           ",
    "218" => "Banco BS2 S.A.                                                                  ",
    "169" => "BANCO OLÉ CONSIGNADO S.A.                                                       ",
    "293" => "Lastro RDV Distribuidora de Títulos e Valores Mobiliários Ltda.                 ",
    "285" => "Frente Corretora de Câmbio Ltda.                                                ",
    "080" => "B&T CORRETORA DE CAMBIO LTDA.                                                   ",
    "753" => "Novo Banco Continental S.A. - Banco Múltiplo                                    ",
    "222" => "BANCO CRÉDIT AGRICOLE BRASIL S.A.                                               ",
    "281" => "Cooperativa de Crédito Rural Coopavel                                           ",
    "754" => "Banco Sistema S.A.                                                              ",
    "098" => "Credialiança Cooperativa de Crédito Rural                                       ",
    "610" => "Banco VR S.A.                                                                   ",
    "712" => "Banco Ourinvest S.A.                                                            ",
    "010" => "CREDICOAMO CREDITO RURAL COOPERATIVA                                            ",
    "283" => "RB CAPITAL INVESTIMENTOS DISTRIBUIDORA DE TÍTULOS E VALORES MOBILIÁRIOS LIMITADA",
    "033" => "BANCO SANTANDER (BRASIL) S.A.                                                   ",
    "217" => "Banco John Deere S.A.                                                           ",
    "041" => "Banco do Estado do Rio Grande do Sul S.A.                                       ",
    "117" => "ADVANCED CORRETORA DE CÂMBIO LTDA                                               ",
    "654" => "BANCO DIGIMAIS S.A.                                                             ",
    "371" => "WARREN CORRETORA DE VALORES MOBILIÁRIOS E CÂMBIO LTDA.                          ",
    "212" => "Banco Original S.A.                                                             ",
    "289" => "DECYSEO CORRETORA DE CAMBIO LTDA.                                               "


];


if (!function_exists('buscarBancos')) {
    function buscarBancos($json = false)
    {
        return Bancos;
    }
}

if (!function_exists('realParaCentavos')) {
    function realParaCentavos($valor = 0)
    {
        return str_replace('.', '', number_format($valor, 2));
    }
}

if (!function_exists('centavosParaReal')) {
    function centavosParaReal($valor = 0)
    {
        return number_format($valor / 100, 2, ',', '.');
    }
}

if (!function_exists('integerParaReal')) {
    function integerParaReal($valor = 0)
    {
        return number_format($valor, 2, ',', '.');
    }
}

if (!function_exists('MotivoRecusaPagarme')) {
    function MotivoRecusaPagarme($transacao)
    {
        $retorno = null;
        if ($transacao->acquirer_response_code == '0') {
            $retorno = [
                'msg' => 'Transação autorizada',
                'orientacao' => ''
            ];
        } else if ($transacao->acquirer_response_code == '1000') {
            $retorno = [
                'msg' => 'Transação não autorizada',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '1001') {
            $retorno = [
                'msg' => 'Cartão vencido',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '1002') {
            $retorno = [
                'msg' => 'Transação não permitida',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '1003') {
            $retorno = [
                'msg' => 'Rejeitado emissor',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '1004') {
            $retorno = [
                'msg' => 'Cartão com restrição',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '1005') {
            $retorno = [
                'msg' => 'Transação não autorizada',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '1006') {
            $retorno = [
                'msg' => 'Tentativas de senha excedidas',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '1007') {
            $retorno = [
                'msg' => 'Rejeitado emissor',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '1008') {
            $retorno = [
                'msg' => 'Rejeitado emissor',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '1009') {
            $retorno = [
                'msg' => 'Transação não autorizada',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '1010') {
            $retorno = [
                'msg' => 'Valor inválido ',
                'orientacao' => 'É possível que o tipo de operação seja inválido. Ex.: cartão de débito onde se aceita apenas crédito'
            ];
        } else if ($transacao->acquirer_response_code == '1011') {
            $retorno = [
                'msg' => 'Cartão inválido',
                'orientacao' => 'O número do cartão digitado está incorreto. Se atente no preenchimento do número do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '1013') {
            $retorno = [
                'msg' => 'Transação não autorizada',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '1014') {
            $retorno = [
                'msg' => 'Tipo de conta inválido',
                'orientacao' => 'O tipo de conta selecionado não existe. '
            ];
        } else if ($transacao->acquirer_response_code == '1015') {
            $retorno = [
                'msg' => 'Função não suportada',
                'orientacao' => 'É possível que o tipo de operação seja inválido. Ex.: cartão de débito onde se aceita apenas crédito'
            ];
        } else if ($transacao->acquirer_response_code == '1016') {
            $retorno = [
                'msg' => 'Saldo insuficiente',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '1017') {
            $retorno = [
                'msg' => 'Senha inválida',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '1019') {
            $retorno = [
                'msg' => 'Transação não permitida',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '1020') {
            $retorno = [
                'msg' => 'Transação não permitida',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '1021') {
            $retorno = [
                'msg' => 'Rejeitado emissor',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '1022') {
            $retorno = [
                'msg' => 'Cartão com restrição',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '1023') {
            $retorno = [
                'msg' => 'Rejeitado emissor',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '1024') {
            $retorno = [
                'msg' => 'Transação não permitida',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '1025') {
            $retorno = [
                'msg' => 'Cartão bloqueado',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '1027') {
            $retorno = [
                'msg' => 'Excedida a quantidade de transações para o cartão. ',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '1042') {
            $retorno = [
                'msg' => 'Tipo de conta inválido',
                'orientacao' => 'O tipo de conta selecionado não existe.'
            ];
        } else if ($transacao->acquirer_response_code == '1045') {
            $retorno = [
                'msg' => 'Código de segurança inválido ',
                'orientacao' => 'O CVV digitado está incorreto. Se atente no preenchimento do CVV.'
            ];
        } else if ($transacao->acquirer_response_code == '1049') {
            $retorno = [
                'msg' => 'Banco/emissor do cartão inválido',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '2000') {
            $retorno = [
                'msg' => 'Cartão com restrição',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '2001') {
            $retorno = [
                'msg' => 'Cartão vencido',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '2002') {
            $retorno = [
                'msg' => 'Transação não permitida',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '2003') {
            $retorno = [
                'msg' => 'Rejeitado emissor ',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '2004') {
            $retorno = [
                'msg' => 'Cartão com restrição ',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '2005') {
            $retorno = [
                'msg' => 'Transação não autorizada',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '2006') {
            $retorno = [
                'msg' => 'Tentativas de senha excedidas',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '2007') {
            $retorno = [
                'msg' => 'Cartão com restrição',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '2008') {
            $retorno = [
                'msg' => 'Cartão com restrição',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '2009') {
            $retorno = [
                'msg' => 'Cartão com restrição',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '5003') {
            $retorno = [
                'msg' => 'Erro interno ',
                'orientacao' => 'Retente a transação'
            ];
        } else if ($transacao->acquirer_response_code == '5006') {
            $retorno = [
                'msg' => 'Erro interno ',
                'orientacao' => 'Retente a transação'
            ];
        } else if ($transacao->acquirer_response_code == '5025') {
            $retorno = [
                'msg' => 'Código de segurança (CVV) do cartão não foi enviado ',
                'orientacao' => 'Certifique-se que foi preenchido o CVV'
            ];
        } else if ($transacao->acquirer_response_code == '5054') {
            $retorno = [
                'msg' => 'Erro interno',
                'orientacao' => 'Retente a transação'
            ];
        } else if ($transacao->acquirer_response_code == '5062') {
            $retorno = [
                'msg' => 'Transação não permitida para este produto ou serviço.',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '5086') {
            $retorno = [
                'msg' => 'Cartão poupança inválido',
                'orientacao' => 'O tipo de conta selecionado não existe'
            ];
        } else if ($transacao->acquirer_response_code == '5088') {
            $retorno = [
                'msg' => 'Transação não autorizada Amex',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '5089') {
            $retorno = [
                'msg' => 'Erro interno',
                'orientacao' => 'Retente a transação'
            ];
        } else if ($transacao->acquirer_response_code == '5092') {
            $retorno = [
                'msg' => 'O valor solicitado para captura não é válido',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '5093') {
            $retorno = [
                'msg' => 'Banco emissor Visa indisponível',
                'orientacao' => 'Transação não autorizada'
            ];
        } else if ($transacao->acquirer_response_code == '5095') {
            $retorno = [
                'msg' => 'Erro interno',
                'orientacao' => 'Retente a transação'
            ];
        } else if ($transacao->acquirer_response_code == '5097') {
            $retorno = [
                'msg' => 'Erro interno',
                'orientacao' => 'Retente a transação'
            ];
        } else if ($transacao->acquirer_response_code == '9102') {
            $retorno = [
                'msg' => 'Transação inválida',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '9103') {
            $retorno = [
                'msg' => 'Cartão cancelado',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '9107') {
            $retorno = [
                'msg' => 'O banco/emissor do cartão ou a conexão parece estar offline',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '9108') {
            $retorno = [
                'msg' => 'Erro no processamento',
                'orientacao' => 'Retente a transação'
            ];
        } else if ($transacao->acquirer_response_code == '9109') {
            $retorno = [
                'msg' => 'Erro no processamento',
                'orientacao' => 'Retente a transação'
            ];
        } else if ($transacao->acquirer_response_code == '9111') {
            $retorno = [
                'msg' => 'Time-out na transação',
                'orientacao' => 'Retente a transação'
            ];
        } else if ($transacao->acquirer_response_code == '9112') {
            $retorno = [
                'msg' => 'Emissor indisponível',
                'orientacao' => 'Entre em contato com o banco emissor do cartão'
            ];
        } else if ($transacao->acquirer_response_code == '9113') {
            $retorno = [
                'msg' => 'Transmissão duplicada',
                'orientacao' => 'É possível já tenha realizado a compra com sucesso e está erroneamente enviando o pagamento uma segunda vez'
            ];
        } else if ($transacao->acquirer_response_code == '9124') {
            $retorno = [
                'msg' => 'Código de segurança inválido',
                'orientacao' => 'O CVV digitado está incorreto. Se atente no preenchimento do CVV'
            ];
        } else if ($transacao->acquirer_response_code == '9999') {
            $retorno = [
                'msg' => 'Erro não especificado.',
                'orientacao' => 'Retente a transação.'
            ];
        }
        return $retorno;
    }
}

if (!function_exists('floatvalue')) {
    /**
     * Método que converte qualquer número decimal
     * com qualquer tipo de separador decimal ou
     * de milhar em um valor float compatível com
     * php e mysql
     * @param mixed $valor Valor a ser convertido
     * @param mixed $default O valor que será retornado caso o
     * valor $valor de entrada seja vazio ou nulo
     * @return float O valor convertido em float
     */
    function floatvalue($valor, $padrao = 0.0)
    {
        $valor = (string)$valor;
        if (empty($valor)) {
            $valor = (string)$padrao;
        }
        $valor = str_replace(",", ".", $valor);
        $valor = preg_replace('/\.(?=.*\.)/', '', $valor);
        return floatval($valor);
    }
}

if (!function_exists('tobrl')) {
    /**
     * Método que converte qualquer número decimal
     * com qualquer tipo de separador decimal ou
     * de milhar em um ponto flutuante no padrão brasileiro
     * @param string $valor Valor em string a ser convertido
     * @param mixed $padrao O valor que será retornado caso o
     * valor $valor de entrada seja vazio ou nulo
     * @param int $casas_decimais Quantidade de casas decimais
     * @return string O valor convertido em real
     */
    function tobrl($valor, $padrao = 0, int $casas_decimais = 2)
    {
        $valor = (string)floatvalue($valor, $padrao);
        $valor = preg_replace('/(\.(?=.*\,))|(\,(?=.*\.))/', '', '' . $valor);
        $valor = explode(',', str_replace(".", ",", $valor));
        $valor[1] = $valor[1] ?? '';
        if (strlen($valor[1]) < $casas_decimais) {
            $valor[1] .= str_repeat('0', $casas_decimais - strlen($valor[1]));
        } else {
            $valor[1] = substr($valor[1], 0, $casas_decimais);
        }
        return implode(',', $valor);
    }

    if(! function_exists('variaveisTexto')){
        function variaveisTexto(){
            return json_encode([
                '{nome_instituicao}',
                '{cnpj_instituicao}',
                '{valor_pago}',
                '{valor_extenso}',
                '{paciente_nome}',
                '{paciente_cpf}',
                '{paciente_endereco}',
                '{data_pago}',
                '{data}',
                '{fornecedor_nome}',
                '{fornecedor_cnpj}',
                '{prestador_cpf}',
                '{prestador_nome}',
                '{descricao}',
                '{paciente_id}',
                '{paciente_data_nascimento}',
                '{paciente_idade}',
            ]);
        }
    }
    
    if(! function_exists('replaceVariaveis')){
        function replaceVariaveis(array $map, $texto){
            $variaveis = [
                '{nome_instituicao}' => (!empty($map['nome_instituicao'])) ? $map['nome_instituicao'] : "",
                '{cnpj_instituicao}' => (!empty($map['cnpj_instituicao'])) ? $map['cnpj_instituicao'] : "",
                '{valor_pago}' => (!empty($map['valor_pago'])) ? $map['valor_pago'] : "valor_pago",
                '{valor_extenso}' => (!empty($map['valor_extenso'])) ? $map['valor_extenso'] : "",
                '{paciente_nome}' => (!empty($map['paciente_nome'])) ? $map['paciente_nome'] : "",
                '{paciente_cpf}' => (!empty($map['paciente_cpf'])) ? $map['paciente_cpf'] : "",
                '{paciente_endereco}' => (!empty($map['paciente_endereco'])) ? $map['paciente_endereco'] : "",
                '{data_pago}' => (!empty($map['data_pago'])) ? $map['data_pago'] : "",
                '{data}' => (!empty($map['data'])) ? $map['data'] : "",
                '{fornecedor_nome}' => (!empty($map['fornecedor_nome'])) ? $map['fornecedor_nome'] : "",
                '{fornecedor_cnpj}' => (!empty($map['fornecedor_cnpj'])) ? $map['fornecedor_cnpj'] : "",
                '{prestador_cpf}' => (!empty($map['prestador_cpf'])) ? $map['prestador_cpf'] : "",
                '{prestador_nome}' => (!empty($map['prestador_nome'])) ? $map['prestador_nome'] : "",
                '{descricao}' => (!empty($map['descricao'])) ? $map['descricao'] : "",
                '{paciente_id}' => (!empty($map['paciente_id'])) ? $map['paciente_id'] : "",
                '{paciente_data_nascimento}' => (!empty($map['paciente_data_nascimento'])) ? $map['paciente_data_nascimento'] : "",
                '{paciente_idade}' => (!empty($map['paciente_idade'])) ? $map['paciente_idade'] : "",
            ];

            return str_replace(array_keys($variaveis), array_values($map), $texto);
        }
    }

    if(! function_exists('clamp')) {
        /**
         * Método limita um valor entre o mínimo especificado e o máximo especificado
         * @param mixed $valor Valor atual que será convertido para float e validado
         * @param float $max Valor máximo
         * @param float $min Valor mínimo
         * @param bool $para_inteiro Converte o valor para inteiro truncando os decimais
         * @param float $padrao Valor utilizado caso a conversão não for possível
         */
        function clamp($valor, float $max, float $min = 0.0, bool $para_inteiro = false, float $padrao = 0.0)
        {
            $valor = floatvalue($valor, $padrao);
            $result = \max($min, \min($max, $valor));
            return $para_inteiro ? (int) $result : $result;
        }
    }
}
