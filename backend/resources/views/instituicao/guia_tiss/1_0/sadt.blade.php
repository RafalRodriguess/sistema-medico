<!DOCTYPE html>
<html>

<head>
    <title>Guia TISS | SADT</title>
    <style>
        @page {
            size: A4 landscape;
            margin-top: 5mm;
            margin-bottom: 15mm;
        }

        * {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 8px;
            
        }
        
        span, td {
            font-size: 6px;
        }

        .container {
            width: 250mm;
            border: 0.75px solid black;
        }

        .row {
            height: 7mm;
        }

        .col {
            float: left;
            height: auto;
            padding-left: 2px;
        }

        .divider {
            height: 2.9mm;
            width: 248.60mm;
            margin-left: 0.25mm;
            padding-left: 1mm;
            background-color: rgb(204, 204, 204);
        }

        .border {
            float: left;
            margin: 0.5px 1px;
            border: 0.5px solid black;
            height: 6.3mm;
        }

        .table {
            margin: 0.5px 1px;
            width: 249.25mm;
            border: 0.5px solid black;
        }
    </style>
</head>

<body>
    @foreach ($guias as $key => $guia)
        <div class="container">
            <div class="row" style="height: 12mm; padding-top: 2px;">
                <div class="col" style="width: 40mm;">
                    <img src="{{ $logo }}" alt="" height="40px" style="margin-left: 10px;">
                </div>
                <div class="col" style="width: 130mm; text-align: center;">
                    <strong style="font-size: 14px;">
                        GUIA DE SERVIÇO PROFISSIONAL / SERVIÇO AUXILIAR DE DIAGNÓSTICO E TERAPIA - SP/SADT
                    </strong>
                </div>
                <div class="col" style="width: 60mm;  font-size: 10px;">
                    2 - Nº Guia no Prestador <strong style="font-size: 16px;">{{ $cod_2 }}</strong>
                </div>
                <div class="col" style="width: 20mm; font-size: 12px;">
                    Folha {{ $key + 1 }}/{{ $paginas }}
                </div>
            </div>

            <div class="row">
                <div class="col border" style="width: 25mm;">
                    <span>1 - Registro ANS</span>
                    <br>
                    {{ $cod_1 }}
                </div>
                <div class="col border" style="width: 60mm">
                    <span>3 - Número da Guia Principal</span>
                    <br>
                    {{ $cod_3 }}
                </div>
                <div class="col" style="width: 165mm"></div>
            </div>

            <div class="row">
                <div class="col border" style="width: 25mm;">
                    <span>4 - Data de Autorização</span>
                    <br>
                    {{ $cod_4 }}
                </div>
                <div class="col border" style="width: 65mm;">
                    <span>5 - Senha</span>
                    <br>
                    {{ $cod_5 }}
                </div>
                <div class="col border" style="width: 25mm;">
                    <span>6 - Data de Validade da Senha</span>
                    <br>
                    {{ $cod_6 }}
                </div>
                <div class="col border" style="width: 85mm;">
                    <span>7 - Número da Guia Atribuído pela Operadora</span>
                    <br>
                    {{ $cod_7 }}
                </div>
            </div>

            <div class="row divider">
                <div class="col">
                    <span>Dados do Beneficiário</span>
                </div>
            </div>

            <div class="row">
                <div class="col border" style="width: 42mm;">
                    <span>8 - Número da Carteira</span>
                    <br>
                    {{ $cod_8 }}
                </div>
                <div class="col border" style="width: 26mm;">
                    <span>9 - Validade da Carteira</span>
                    <br>
                    {{ $cod_9 }}
                </div>
                <div class="col border" style="width: 91mm;">
                    <span>10 - Nome</span>
                    <br>
                    {{ $cod_10 }}
                </div>
                <div class="col border" style="width: 60.35mm;">
                    <span>11 - Número do Cartão Nacional de Saúde</span>
                    <br>
                    {{ $cod_11 }}
                </div>
                <div class="col border" style="width: 24mm;">
                    <span>12 - Atendimento a RN</span>
                    <br>
                    {{ $cod_12 }}
                </div>
            </div>

            <div class="row divider">
                <div class="col">
                    <span>Dados do Solicitante</span>
                </div>
            </div>

            <div class="row">
                <div class="col border" style="width: 47.35mm;">
                    <span>13 - Código da Operadora</span>
                    <br>
                    {{ $cod_13 }}
                </div>
                <div class="col border" style="width: 200mm;">
                    <span>14 - Nome do Contratado</span>
                    <br>
                    {{ $cod_14 }}
                </div>
            </div>

            <div class="row">
                <div class="col border" style="width: 69.25mm;">
                    <span>15 - Nome do Profissional Solicitante</span>
                    <br>
                    {{ $cod_15 }}
                </div>
                <div class="col border" style="width: 22mm;">
                    <span>16 - Conselho Profissional</span>
                    {{ $cod_16 }}
                    <br>
                </div>
                <div class="col border" style="width: 32mm;">
                    <span>17 - Número no Conselho</span>
                    <br>
                    {{ $cod_17 }}
                </div>
                <div class="col border" style="width: 10mm;">
                    <span>18 - UF</span>
                    <br>
                    {{ $cod_18 }}
                </div>
                <div class="col border" style="width: 24mm;">
                    <span>19 - Código CBO</span>
                    <br>
                    {{ $cod_19 }}
                </div>
                <div class="col border" style="width: 84.80mm;">
                    <span>20 - Assinatura do Profissional Solicitante</span>
                    <br>
                    {{ $cod_20 }}
                </div>
            </div>

            <div class="row divider">
                <div class="col">
                    <span>Dados da solicitação / Procedimentos e Exames Solicitados</span>
                </div>
            </div>

            <div class="row">
                <div class="col border" style="width: 25mm;">
                    <span>21 - Caráter do Atendimento</span>
                    <br>
                    {{ $cod_21 }}
                </div>
                <div class="col border" style="width: 40mm;">
                    <span>22 - Data da Solicitação</span>
                    <br>
                    {{ $cod_22 }}
                </div>
                <div class="col border" style="width: 181.05mm;">
                    <span>23 - Indicação Clínica</span>
                    <br>
                    {{ $cod_23 }}
                </div>
            </div>

            <div class="row table" style="height: 16mm;">
                <div class="col">
                    <table>
                        <thead>
                            <tr>
                                <td style="width: 15mm;">24 - Tabela</td>
                                <td style="width: 50mm;">25 - Código do Procedimento ou Item Assistencial</td>
                                <td style="width: 130mm;">26 - Descrição</td>
                                <td>27 - Qt.Solic.</td>
                                <td style="width: 15mm;">28 - Qt.Autoriz.</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($guia['solicitados'] as $procedimento)
                                <tr>
                                    <td>{{ $procedimento['cod_24'] }}</td>
                                    <td>{{ $procedimento['cod_25'] }}</td>
                                    <td>{{ $procedimento['cod_26'] }}</td>
                                    <td>{{ $procedimento['cod_27'] }}</td>
                                    <td>{{ $procedimento['cod_28'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row divider">
                <div class="col">
                    <span>Dados do Contrato Executante</span>
                </div>
            </div>

            <div class="row">
                <div class="col border" style="width: 30mm;">
                    <span>29 - Código na Operadora</span>
                    <br>
                    {{ $cod_29 }}
                </div>
                <div class="col border" style="width: 196.05mm;">
                    <span>30 - Nome do Contratado</span>
                    <br>
                    {{ $cod_30 }}
                </div>
                <div class="col border" style="width: 20mm;">
                    <span>31 - Código CNES</span>
                    <br>
                    {{ $cod_31 }}
                </div>
            </div>

            <div class="row divider">
                <div class="col">
                    <span>Dados do Atendimento</span>
                </div>
            </div>

            <div class="row">
                <div class="col border" style="width: 20mm;">
                    <span>32 - Tipo de Atendimento</span>
                    <br>
                    {{ $cod_32 }}
                </div>
                <div class="col border" style="width: 52mm;">
                    <span>33 - Indicação de Acidente (acidente ou doença relacionada)</span>
                    <br>
                    {{ $cod_33 }}
                </div>
                <div class="col border" style="width: 20mm;">
                    <span>34 - Tipo de Consulta</span>
                    <br>
                    {{ $cod_34 }}
                </div>
                <div class="col border" style="width: 40mm;">
                    <span>35 - Motivo de Encerramento do Atendimento</span>
                    {{ $cod_35 }}
                </div>
            </div>

            <div class="row divider">
                <div class="col">
                    <span>Dados da Execução / Procedimentos e Exames Realizados</span>
                </div>
            </div>

            <div class="row table" style="height: 18.5mm;">
                <table>
                    <thead>
                        <tr>
                            <td style="width: 18mm;">36 - Data</td>
                            <td style="width: 15mm;">37 - Hr. Inicial</td>
                            <td style="width: 15mm;">38 - Hr. Final</td>
                            <td>39 - Tabela</td>
                            <td>40 - Procedimento</td>
                            <td style="width: 70mm;">41 - Descrição</td>
                            <td>42 - Qtde.</td>
                            <td>43 - Via</td>
                            <td>44 - Téc.</td>
                            <td style="width: 20mm;">45 - Rator R/Acr</td>
                            <td style="width: 20mm;">46 - Valor Unitário</td>
                            <td style="width: 20mm;">47 - Valor Total</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($guia['realizados'] as $procedimento)
                            <tr>
                                <td>{{ $procedimento['cod_36'] }}</td>
                                <td>{{ $procedimento['cod_37'] }}</td>
                                <td>{{ $procedimento['cod_38'] }}</td>
                                <td>{{ $procedimento['cod_39'] }}</td>
                                <td>{{ $procedimento['cod_40'] }}</td>
                                <td>{{ $procedimento['cod_41'] }}</td>
                                <td>{{ $procedimento['cod_42'] }}</td>
                                <td>{{ $procedimento['cod_43'] }}</td>
                                <td>{{ $procedimento['cod_44'] }}</td>
                                <td>{{ $procedimento['cod_45'] }}</td>
                                <td>{{ $procedimento['cod_46'] }}</td>
                                <td>{{ $procedimento['cod_47'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row divider">
                <div class="col">
                    <span>Identificação do(s) Profissional(is) Executante(s)</span>
                </div>
            </div>

            <div class="row table" style="height: 16mm;">
                <table>
                    <thead>
                        <tr>
                            <td style="width: 15mm;">48 - Seq.Ref.</td>
                            <td style="width: 15mm;">49 - Gr.Part</td>
                            <td style="width: 30mm;">50 - Cód na operadora/CPF</td>
                            <td style="width: 90mm;">51 - nome do Profissional</td>
                            <td style="width: 25mm;">52 - Conselho Prof</td>
                            <td style="width: 30mm;">53 - Número no Conselho</td>
                            <td style="width: 10mm;">54 - UF</td>
                            <td>55 - Código CBO</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($guia['executantes'] as $procedimento)
                            <tr>
                                <td>{{ $procedimento['cod_48'] }}</td>
                                <td>{{ $procedimento['cod_49'] }}</td>
                                <td>{{ $procedimento['cod_50'] }}</td>
                                <td>{{ $procedimento['cod_51'] }}</td>
                                <td>{{ $procedimento['cod_52'] }}</td>
                                <td>{{ $procedimento['cod_53'] }}</td>
                                <td>{{ $procedimento['cod_54'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row table" style="height: 8.8mm;">
                <span>56 - Data de Realização de Procedimentos em Série 57 - Assinatura do Beneficiário ou Responsável</span>
                <table>
                    <tbody>
                        <tr>
                            <td style="width: 50mm;">1 - ____/____/________ _____________________</td>
                            <td style="width: 50mm;">3 - ____/____/________ _____________________</td>
                            <td style="width: 50mm;">5 - ____/____/________ _____________________</td>
                            <td style="width: 50mm;">7 - ____/____/________ _____________________</td>
                            <td style="width: 50mm;">9 - ____/____/________ _____________________</td>
                        </tr>
                        <tr>
                            <td style="width: 50mm;">2 - ____/____/________ _____________________</td>
                            <td style="width: 50mm;">4 - ____/____/________ _____________________</td>
                            <td style="width: 50mm;">6 - ____/____/________ _____________________</td>
                            <td style="width: 50mm;">8 - ____/____/________ _____________________</td>
                            <td style="width: 50mm;">10 - ____/____/________ _____________________</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row divider" style="height: 8mm;">
                <div class="col">
                    <span>58 - Observação / Justificativa</span>
                    <br>
                    {{ $cod_58 }}
                </div>
            </div>

            <div class="row">
                <div class="col border" style="width: 34.39mm;">
                    <span>59 - Total de Procedimentos (R$)</span>
                    <br>
                    {{ ($key + 1) != $paginas ? '************************ ' : $cod_59 }}
                </div>
                <div class="col border" style="width: 34.39mm;">
                    <span>60 - Total de Taxas e Aluguéis (R$)</span>
                    <br>
                    {{ ($key + 1) != $paginas ? '************************ ' : $cod_60 }}
                </div>
                <div class="col border" style="width: 34.39mm;">
                    <span>61 - Total de Materiais (R$)</span>
                    <br>
                    {{ ($key + 1) != $paginas ? '************************ ' : $cod_61 }}
                </div>
                <div class="col border" style="width: 34.39mm;">
                    <span>62 - Total de OPME (R$)</span>
                    <br>
                    {{ ($key + 1) != $paginas ? '************************ ' : $cod_62 }}
                </div>
                <div class="col border" style="width: 34.39mm;">
                    <span>63 - Total de Medicamentos (R$)</span>
                    <br>
                    {{ ($key + 1) != $paginas ? '************************ ' : $cod_63 }}
                </div>
                <div class="col border" style="width: 34.39mm;">
                    <span>64 - Total de Gases Medicinais (R$)</span>
                    <br>
                    {{ ($key + 1) != $paginas ? '************************ ' : $cod_64 }}
                </div>
                <div class="col border" style="width: 34.39mm;">
                    <span>65 - Total Geral (R$)</span>
                    <br>
                    {{ ($key + 1) != $paginas ? '************************ ' : $cod_65 }}
                </div>
            </div>

            <div class="row" style="height:12mm;">
                <div class="col border" style="height:10mm; width: 79.52mm; margin-right: 4mm;">
                    <span>66 - Assinatura do Responsável pela Autorização</span>
                </div>
                <div class="col border" style="height:10mm; width: 79.52mm; margin-right: 4mm;">
                    <span>67 - Assinatura do Beneficiário ou Responsável</span>
                </div>
                <div class="col border" style="height:10mm; width: 79.52mm;">
                    <span>68 - Assinatura do Contratado</span>
                </div>
            </div>

            <div class="row" style="height:12mm;">
                <div class="col" style="width: 40mm;">
                    Impresso por: {{ $impresso }}
                </div>
                <div class="col" style="width: 40mm;">
                    Data/Hora: {{ $data_hora }}
                </div>
                <div class="col" style="width: 40mm;">
                    Conta/Lote: {{ $conta_lote }}
                </div>
                <div class="col" style="width: 40mm;">
                    Atendimento: {{ $atendimento }}
                </div>
                <div class="col" style="width: 40mm;">
                    Convênio: {{ $convenio }}
                </div>
                <div class="col" style="width: 40mm;">
                    <strong style="font-size: 18px">*{{ $controle }}*</strong> 
                </div>
            </div>

        </div>
        <br>
    @endforeach
</body>

</html>
