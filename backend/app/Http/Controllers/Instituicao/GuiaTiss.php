<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuiaTiss extends Controller
{
    public function sadt()
    {
        $dompdf = new Dompdf();

        // versao do doc a ser gerado
        $versao = '1_0';

        // Obtenha os dados da guia TISS a partir do banco de dados ou outras fontes
        $data = $this->getFakeDataGuiaSadt();

        // Renderize o HTML com os dados da guia TISS
        $html = view("instituicao.guia_tiss.{$versao}.sadt", $data)->render();
        // return $html;

        // Carregue o HTML no DomPDF
        $dompdf->loadHtml($html);

        // (Opcional) Defina o tamanho e orientação da página
        $dompdf->setPaper('A4', 'landscape');

        // Renderize o PDF
        $dompdf->render();

        // Envie o PDF para o navegador
        return $dompdf->stream('guia-tiss-sadt.pdf', [
            'Attachment' => false
        ]);
    }

    public function consulta()
    {
        $dompdf = new Dompdf();

        // versao do doc a ser gerado
        $versao = '1_0';

        // Obtenha os dados da guia TISS a partir do banco de dados ou outras fontes
        $data = $this->getFakeDataGuiaConsulta();

        // Renderize o HTML com os dados da guia TISS
        $html = view("instituicao.guia_tiss.{$versao}.consulta", $data)->render();
        // return $html;

        // Carregue o HTML no DomPDF
        $dompdf->loadHtml($html);

        // (Opcional) Defina o tamanho e orientação da página
        $dompdf->setPaper('A4', 'landscape');

        // Renderize o PDF
        $dompdf->render();

        // Envie o PDF para o navegador
        return $dompdf->stream('guia-tiss-consulta.pdf', [
            'Attachment' => false
        ]);
    }

    public function outras_despesas()
    {
        $dompdf = new Dompdf();

        // versao do doc a ser gerado
        $versao = '1_0';

        // Obtenha os dados da guia TISS a partir do banco de dados ou outras fontes
        $data = $this->getFakeDataGuiaOutrasDespesas();

        // Renderize o HTML com os dados da guia TISS
        $html = view("instituicao.guia_tiss.{$versao}.outras_despesas", $data)->render();
        // return $html;

        // Carregue o HTML no DomPDF
        $dompdf->loadHtml($html);

        // (Opcional) Defina o tamanho e orientação da página
        $dompdf->setPaper('A4', 'landscape');

        // Renderize o PDF
        $dompdf->render();

        // Envie o PDF para o navegador
        return $dompdf->stream('guia-tiss-outras_despesas.pdf', [
            'Attachment' => false
        ]);
    }

    private function convertToImageUrlInBase64($url)
    {
        $type = pathinfo($url, PATHINFO_EXTENSION);
        $data = file_get_contents($url);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        return $base64;
    }

    private function getFakeDataGuiaSadt()
    {
        $logo = $this->convertToImageUrlInBase64('https://www.cemigsaude.org.br/assets/img/site/logo_cemig_rodape.png');

        $guias = [
            [
                'solicitados' => [
                    [
                        'cod_24' => '',
                        'cod_25' => '',
                        'cod_26' => '',
                        'cod_27' => '',
                        'cod_28' => '',
                    ],
                ],
                'realizados' => [
                    [
                        'cod_36' => '2022-09-10',
                        'cod_37' => '21:25',
                        'cod_38' => '21:25',
                        'cod_39' => '22',
                        'cod_40' => '40301400',
                        'cod_41' => 'CALCIO - PESQUISA E/OU DOSAGEM',
                        'cod_42' => '001',
                        'cod_43' => '',
                        'cod_44' => '',
                        'cod_45' => '1.0',
                        'cod_46' => '5.02',
                        'cod_47' => '5.02',
                    ],
                    [
                        'cod_36' => '2022-09-10',
                        'cod_37' => '21:25',
                        'cod_38' => '21:25',
                        'cod_39' => '22',
                        'cod_40' => '40301630',
                        'cod_41' => 'CREATININA - PESQUISA E/OU DOSAGEM',
                        'cod_42' => '001',
                        'cod_43' => '',
                        'cod_44' => '',
                        'cod_45' => '1.0',
                        'cod_46' => '5.02',
                        'cod_47' => '5.02',
                    ],
                    [
                        'cod_36' => '2022-09-10',
                        'cod_37' => '21:25',
                        'cod_38' => '21:25',
                        'cod_39' => '22',
                        'cod_40' => '40302040',
                        'cod_41' => 'GLICOSE - PESQUISA E/OU DOSAGEM',
                        'cod_42' => '001',
                        'cod_43' => '',
                        'cod_44' => '',
                        'cod_45' => '1.0',
                        'cod_46' => '5.02',
                        'cod_47' => '5.02',
                    ],
                    [
                        'cod_36' => '2022-09-10',
                        'cod_37' => '21:25',
                        'cod_38' => '21:25',
                        'cod_39' => '22',
                        'cod_40' => '40302318',
                        'cod_41' => 'POTASSIO - PESQUISA E/OU DOSAGEM',
                        'cod_42' => '001',
                        'cod_43' => '',
                        'cod_44' => '',
                        'cod_45' => '1.0',
                        'cod_46' => '5.02',
                        'cod_47' => '5.02',
                    ],
                    [
                        'cod_36' => '2022-09-10',
                        'cod_37' => '21:25',
                        'cod_38' => '21:25',
                        'cod_39' => '22',
                        'cod_40' => '40302423',
                        'cod_41' => 'SODIO - PESQUISA E/OU DOSAGEM',
                        'cod_42' => '001',
                        'cod_43' => '',
                        'cod_44' => '',
                        'cod_45' => '1.0',
                        'cod_46' => '5.02',
                        'cod_47' => '5.02',
                    ],
                ],
                'executantes' => [
                    [
                        'cod_48' => '',
                        'cod_49' => '',
                        'cod_50' => '',
                        'cod_51' => '',
                        'cod_52' => '',
                        'cod_53' => '',
                        'cod_54' => '',
                    ],
                ]
            ],
            [
                'solicitados' => [
                    [
                        'cod_24' => '',
                        'cod_25' => '',
                        'cod_26' => '',
                        'cod_27' => '',
                        'cod_28' => '',
                    ],
                ],
                'realizados' => [
                    [
                        'cod_36' => '2022-09-10',
                        'cod_37' => '21:25',
                        'cod_38' => '21:25',
                        'cod_39' => '22',
                        'cod_40' => '40302580',
                        'cod_41' => 'UREIA - PESQUISA E/OU DOSAGEM',
                        'cod_42' => '001',
                        'cod_43' => '',
                        'cod_44' => '',
                        'cod_45' => '1.0',
                        'cod_46' => '5.02',
                        'cod_47' => '5.02',
                    ],
                    [
                        'cod_36' => '2022-09-10',
                        'cod_37' => '21:25',
                        'cod_38' => '21:25',
                        'cod_39' => '22',
                        'cod_40' => '40304361',
                        'cod_41' => 'HEMOGRAMA COM CONTAGEM DE PLAQUETAS OU FRACOES (ER',
                        'cod_42' => '001',
                        'cod_43' => '',
                        'cod_44' => '',
                        'cod_45' => '1.0',
                        'cod_46' => '11.07',
                        'cod_47' => '11.07',
                    ],
                    [
                        'cod_36' => '2022-09-10',
                        'cod_37' => '21:25',
                        'cod_38' => '21:25',
                        'cod_39' => '22',
                        'cod_40' => '40308391',
                        'cod_41' => 'PROTEINA C REATIVA, QUANTITATIVA - PESQUISA E/OU D',
                        'cod_42' => '001',
                        'cod_43' => '',
                        'cod_44' => '',
                        'cod_45' => '1.0',
                        'cod_46' => '27.70',
                        'cod_47' => '27.70',
                    ],
                    [
                        'cod_36' => '2022-09-10',
                        'cod_37' => '21:25',
                        'cod_38' => '21:25',
                        'cod_39' => '22',
                        'cod_40' => '40805026',
                        'cod_41' => 'RX - TORAX - 2 INCIDENCIAS',
                        'cod_42' => '001',
                        'cod_43' => '',
                        'cod_44' => '',
                        'cod_45' => '1.0',
                        'cod_46' => '42.21',
                        'cod_47' => '42.21',
                    ],
                ],
                'executantes' => [
                    [
                        'cod_48' => '',
                        'cod_49' => '',
                        'cod_50' => '',
                        'cod_51' => '',
                        'cod_52' => '',
                        'cod_53' => '',
                        'cod_54' => '',
                    ],
                ]
            ],
        ];

        $faturamento = [
            'logo' => $logo,                    // Logo do convenio
            'cod_1' => '417505',                // ans
            'cod_2' => '56057',                 // guia no prestador
            'cod_3' => '',                      // guia_principal
            'cod_4' => '2022-09-10',            // data_autorizacao
            'cod_5' => '23881742202239939199',  // senha
            'cod_6' => '',                      // senha_validade
            'cod_7' => '000120220900027002',    // numero_guia_operadora
            'cod_8' => '010313800002',          // carteirinha_numero
            'cod_9' => '31/12/2022',            // carteirinha_validade
            'cod_10' => 'ISMAEL ALVES DE SOUZA', // nome_paciente
            'cod_11' => '',                     // cns
            'cod_12' => 'N',                    // atendimento_rn
            'cod_13' => '22669931000110',       // cod_operadora
            'cod_14' => 'IRMANDADE NSA SRA MERCES DE MONTES CLAROS', // nome_contratado
            'cod_15' => 'RAFAEL MEIRA COUTINHO', // nome_solicitante
            'cod_16' => '06',                   // conselho_profissional
            'cod_17' => '56641',                // numero do conselho
            'cod_18' => '31',                   // uf
            'cod_19' => '225120',               // codigo_cbo
            'cod_20' => '',                     // assinatura profissional
            'cod_21' => '2',                    // carater_atendimento
            'cod_22' => '22/09/2022',           // data da solicitacao
            'cod_23' => 'RELATA MELHORA DA FEBRE COBRO EXAMES : RX TORAX: SEM ALTERACOES RADIOLOGICAS REV LABORATORIAL', // indicacao_clinica
            'cod_29' => '22669931000110',       // cod_operadora
            'cod_30' => 'IRMANDADE NSA SRA MERCES DE MONTES CLAROS', // nome_contratado
            'cod_31' => '2149990',              // codigo CNES
            'cod_32' => '11',                   // tipo atendimento
            'cod_33' => '9',                    // indicacao de acidente
            'cod_34' => '',                     // tipo de consulta
            'cod_35' => '',                     // motivo de encerramento do atendimento
            'cod_58' => '',                     // Observacao
            'cod_59' => '111.10',               // total de procedimentos
            'cod_60' => '42.43',                // total de taxas
            'cod_61' => '195.91',               // total de materiais
            'cod_62' => '0.00',                 // total de OPME
            'cod_63' => '11.89',                // total de medicamentos
            'cod_64' => '0.00',                 // total de gases
            'cod_65' => '361.33',               // total geral
            'impresso' => 'DBAMV',              // impresso por 
            'data_hora' => '30/09/2022 12:15:35',
            'conta_lote' => '2857672',
            'atendimento' => '7103864',
            'convenio' => 'CEMIG SAÚDE',
            'controle' => '2857672',
            'guias' => $guias,
        ];

        $faturamento['paginas'] = count($guias);

        return $faturamento;
    }

    private function getFakeDataGuiaConsulta()
    {
        $logo = $this->convertToImageUrlInBase64('https://www.cemigsaude.org.br/assets/img/site/logo_cemig_rodape.png');

        $faturamento = [
            'logo' => $logo,                    // Logo do convenio
            'cod_1' => '417505',                // ANS
            'cod_2' => '56057',                 // guia no prestador
            'cod_3' => '000120220900027002',    // Número da Guia Atribuido pela Operadora
            'cod_4' => '010313800002',          // Número da Carteira
            'cod_5' => '31/12/2022',            // Validade da Carteira
            'cod_6' => 'N',                     // Atendimento a RN  
            'cod_7' => 'ISMAEL ALVES DE SOUZA', // Nome
            'cod_8' => '',                      // Cartao Nacional de Saúde
            'cod_9' => '22669931000110', // Código na Operadora
            'cod_10' => 'IRMANDADE NSA SRA MERCES DE MONTES CLAROS', //  Nome do Contratado
            'cod_11' => '',                     // Codigo CNES
            'cod_12' => '',                     // Nome do Profissional Executante
            'cod_13' => '',                     // Conselho Profissional
            'cod_14' => '',                     // Numero no Coselho
            'cod_15' => '31',                   // UF
            'cod_16' => '',                     // Codigo CBO
            'cod_17' => '',                     // Inicacao de acidente
            'cod_18' => '31/11/2022',           // Data do Atendimento
            'cod_19' => '',                     // Tipo de consulta
            'cod_20' => '',                     // Tabela
            'cod_21' => '',                     // Codigo do procedimento
            'cod_22' => '',                     // Valor do procedimento
            'cod_23' => '',                     // Observacoes / justificativa
            'cod_24' => '',                     // assinatura do profissional
            'cod_25' => '',                     // assinatura do beneficiario
        ];

        return $faturamento;
    }

    private function getFakeDataGuiaOutrasDespesas()
    {
        $logo = $this->convertToImageUrlInBase64('https://www.cemigsaude.org.br/assets/img/site/logo_cemig_rodape.png');

        $guias = [
            [
                'despesas' => [
                    [
                        'cod_6' => '02',
                        'cod_7' => '10/09/2022',
                        'cod_8' => '21:25:53',
                        'cod_9' => '21:25:53',
                        'cod_10' => '20',
                        'cod_11' => '90303164',
                        'cod_12' => '1',
                        'cod_13' => '001',
                        'cod_14' => '1.00',
                        'cod_15' => '0.72',
                        'cod_16' => '0.72',
                        'cod_17' => '',
                        'cod_18' => '',
                        'cod_19' => '',
                        'cod_20' => 'AGUA PARA INJECAO - SOL INJ CX 200 AMP PE X 10 ML',
                    ],
                    [
                        'cod_6' => '02',
                        'cod_7' => '10/09/2022',
                        'cod_8' => '21:25:53',
                        'cod_9' => '21:25:53',
                        'cod_10' => '20',
                        'cod_11' => '90137485',
                        'cod_12' => '1',
                        'cod_13' => '001',
                        'cod_14' => '1.00',
                        'cod_15' => '3.51',
                        'cod_16' => '3.51',
                        'cod_17' => '',
                        'cod_18' => '',
                        'cod_19' => '',
                        'cod_20' => 'DIPIRONA SODICA - 500 MG/ML SOL INJ CT 120 AMP VD AMB X 2 ML(EMB HOSP)',
                    ],
                    [
                        'cod_6' => '02',
                        'cod_7' => '10/09/2022',
                        'cod_8' => '21:25:53',
                        'cod_9' => '21:25:53',
                        'cod_10' => '20',
                        'cod_11' => '90123093',
                        'cod_12' => '1',
                        'cod_13' => '001',
                        'cod_14' => '1.00',
                        'cod_15' => '7.66',
                        'cod_16' => '7.66',
                        'cod_17' => '',
                        'cod_18' => '',
                        'cod_19' => '',
                        'cod_20' => 'SOLUCAO DE CLORETO DE SODIO B.BRAUN - 9 MG/ML SOL INJ IV CX 20 FA PLAS INC SIST FECH X 500 ML',
                    ],
                    [
                        'cod_6' => '02',
                        'cod_7' => '10/09/2022',
                        'cod_8' => '21:25:53',
                        'cod_9' => '21:25:53',
                        'cod_10' => '20',
                        'cod_11' => '90303164',
                        'cod_12' => '1',
                        'cod_13' => '001',
                        'cod_14' => '1.00',
                        'cod_15' => '0.72',
                        'cod_16' => '0.72',
                        'cod_17' => '',
                        'cod_18' => '',
                        'cod_19' => '',
                        'cod_20' => 'AGUA PARA INJECAO - SOL INJ CX 200 AMP PE X 10 ML',
                    ],
                    [
                        'cod_6' => '02',
                        'cod_7' => '10/09/2022',
                        'cod_8' => '21:25:53',
                        'cod_9' => '21:25:53',
                        'cod_10' => '20',
                        'cod_11' => '90137485',
                        'cod_12' => '1',
                        'cod_13' => '001',
                        'cod_14' => '1.00',
                        'cod_15' => '3.51',
                        'cod_16' => '3.51',
                        'cod_17' => '',
                        'cod_18' => '',
                        'cod_19' => '',
                        'cod_20' => 'DIPIRONA SODICA - 500 MG/ML SOL INJ CT 120 AMP VD AMB X 2 ML(EMB HOSP)',
                    ],
                    [
                        'cod_6' => '02',
                        'cod_7' => '10/09/2022',
                        'cod_8' => '21:25:53',
                        'cod_9' => '21:25:53',
                        'cod_10' => '20',
                        'cod_11' => '90123093',
                        'cod_12' => '1',
                        'cod_13' => '001',
                        'cod_14' => '1.00',
                        'cod_15' => '7.66',
                        'cod_16' => '7.66',
                        'cod_17' => '',
                        'cod_18' => '',
                        'cod_19' => '',
                        'cod_20' => 'SOLUCAO DE CLORETO DE SODIO B.BRAUN - 9 MG/ML SOL INJ IV CX 20 FA PLAS INC SIST FECH X 500 ML',
                    ],
                    [
                        'cod_6' => '02',
                        'cod_7' => '10/09/2022',
                        'cod_8' => '21:25:53',
                        'cod_9' => '21:25:53',
                        'cod_10' => '20',
                        'cod_11' => '90303164',
                        'cod_12' => '1',
                        'cod_13' => '001',
                        'cod_14' => '1.00',
                        'cod_15' => '0.72',
                        'cod_16' => '0.72',
                        'cod_17' => '',
                        'cod_18' => '',
                        'cod_19' => '',
                        'cod_20' => 'AGUA PARA INJECAO - SOL INJ CX 200 AMP PE X 10 ML',
                    ],
                    [
                        'cod_6' => '02',
                        'cod_7' => '10/09/2022',
                        'cod_8' => '21:25:53',
                        'cod_9' => '21:25:53',
                        'cod_10' => '20',
                        'cod_11' => '90137485',
                        'cod_12' => '1',
                        'cod_13' => '001',
                        'cod_14' => '1.00',
                        'cod_15' => '3.51',
                        'cod_16' => '3.51',
                        'cod_17' => '',
                        'cod_18' => '',
                        'cod_19' => '',
                        'cod_20' => 'DIPIRONA SODICA - 500 MG/ML SOL INJ CT 120 AMP VD AMB X 2 ML(EMB HOSP)',
                    ],
                    [
                        'cod_6' => '02',
                        'cod_7' => '10/09/2022',
                        'cod_8' => '21:25:53',
                        'cod_9' => '21:25:53',
                        'cod_10' => '20',
                        'cod_11' => '90123093',
                        'cod_12' => '1',
                        'cod_13' => '001',
                        'cod_14' => '1.00',
                        'cod_15' => '7.66',
                        'cod_16' => '7.66',
                        'cod_17' => '',
                        'cod_18' => '',
                        'cod_19' => '',
                        'cod_20' => 'SOLUCAO DE CLORETO DE SODIO B.BRAUN - 9 MG/ML SOL INJ IV CX 20 FA PLAS INC SIST FECH X 500 ML',
                    ],
                ] 
            ],
        ];

        $faturamento = [
            'logo' => $logo,                    // Logo do convenio
            'cod_1' => '417505',                // ans
            'cod_2' => '56057',                 // guia no prestador
            'cod_3' => '22669931000110',       // cod_operadora
            'cod_4' => 'IRMANDADE NSA SRA MERCES DE MONTES CLAROS', // nome_contratado
            'cod_5' => '',                      // CNS
            'cod_21' => '0.00', // total de gases
            'cod_22' => '11.89', // total de medicamentos
            'cod_23' => '195.91', // total de materiais
            'cod_24' => '0.00', // total de OPME
            'cod_25' => '42.43', // total de taxas
            'cod_26' => '0.00', // total de diarias
            'cod_27' => '250.23', // Total geral
            'impresso' => 'DBAMV',              // impresso por 
            'data_hora' => '30/09/2022 12:15:35',
            'conta_lote' => '2857672',
            'atendimento' => '7103864',
            'convenio' => 'CEMIG SAÚDE',
            'controle' => '2857672',
            'guias' => $guias,
        ];

        $faturamento['paginas'] = count($guias);

        return $faturamento;
    }
}
