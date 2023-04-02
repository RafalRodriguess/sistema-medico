<!DOCTYPE html>
<html>

<head>
    <title>Guia TISS | Consulta</title>
    <style>
        @page {
            size: A4 landscape;
        }

        * {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 8px;

        }

        span,
        td {
            font-size: 6px;
        }

        .container {
            width: 252mm;
            border: 0.75px solid black;
            padding: 4px;
        }

        .row {
            margin-top: 3px;
            margin-bottom: 3px;
            height: 7mm;
        }

        .col {
            float: left;
            height: auto;
            padding-left: 2px;
        }

        .divider {
            height: 2.9mm;
            background-color: rgb(204, 204, 204);
        }

        .border {
            float: left;
            margin: 0.5px 1px;
            border: 0.5px solid black;
            height: 6.3mm;
        }

        .table {
            border: 0.5px solid black;
        }

        .footer {
            page-break-after: always;
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
                <div class="col" style="width: 120mm; text-align: center;">
                    <strong style="font-size: 14px;">
                        ANEXO DE OUTRAS DESPESAS
                    </strong>
                    <br>
                    (para Guia de SP/SADT e Resumo de Internação)
                </div>
                <div class="col" style="width: 60mm;  font-size: 10px;">
                    2 - Nº Guia no Prestador <strong style="font-size: 16px;">{{ $cod_2 }}</strong>
                </div>
                <div class="col" style="width: 20mm; font-size: 12px;">
                    Folha {{ $key + 1 }}/{{ $paginas }}
                </div>
            </div>

            <div class="row">
                <div class="col border" style="width: 30mm;">
                    <span>1 - Registro ANS</span>
                    <br>
                    {{ $cod_1 }}
                </div>
                <div class="col border" style="width: 90mm">
                    <span>3 - Número da Guia Atribúido pela Operadora</span>
                    <br>
                    {{ $cod_3 }}
                </div>
                <div class="col" style="width: 165mm"></div>
            </div>

            <div class="row divider">
                <div class="col">
                    <span>Dados do Contratado Executante</span>
                </div>
            </div>

            <div class="row">
                <div class="col border" style="width: 30mm;">
                    <span>3 - Código na Operadora</span>
                    <br>
                    {{ $cod_3 }}
                </div>
                <div class="col border" style="width: 188.5mm;">
                    <span>4 - Nome do Contratado</span>
                    <br>
                    {{ $cod_4 }}
                </div>
                <div class="col border" style="width: 30mm;">
                    <span>5 - Código CNES</span>
                    <br>
                    {{ $cod_5 }}
                </div>
            </div>

            <div class="row divider">
                <div class="col">
                    <span>Despesas Realizadas</span>
                </div>
            </div>

            <div class="row table" style="height: 120mm;">
                <table>
                    <thead>
                        <tr>
                            <td>6 - CD</td>
                            <td>7 - Data</td>
                            <td>8 - Hora Inicial</td>
                            <td>9 - Hora Final</td>
                            <td>10 - Tabela</td>
                            <td>11 - Código do Item</td>
                            <td>12 - Qte.</td>
                            <td>13 - Unidade de Medida</td>
                            <td>14 - Fator Red./Acres.</td>
                            <td>15 - Valor Unitário R$</td>
                            <td>16 - Valor Total R$</td>
                            <td>17 - Registro ANVISA do Material</td>
                            <td>18 - Referência do material no fabricante</td>
                            <td>19 - Nº Autorização de Funcionamento</td>
                            <td>20 - Descrição</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($guia['despesas'] as $despesa)
                            <tr>
                                <td>{{ $despesa['cod_6'] }}</td>
                                <td>{{ $despesa['cod_7'] }}</td>
                                <td>{{ $despesa['cod_8'] }}</td>
                                <td>{{ $despesa['cod_9'] }}</td>
                                <td>{{ $despesa['cod_10'] }}</td>
                                <td>{{ $despesa['cod_11'] }}</td>
                                <td>{{ $despesa['cod_12'] }}</td>
                                <td>{{ $despesa['cod_13'] }}</td>
                                <td>{{ $despesa['cod_14'] }}</td>
                                <td>{{ $despesa['cod_15'] }}</td>
                                <td>{{ $despesa['cod_16'] }}</td>
                                <td>{{ $despesa['cod_17'] }}</td>
                                <td>{{ $despesa['cod_18'] }}</td>
                                <td>{{ $despesa['cod_19'] }}</td>
                                <td>{{ $despesa['cod_20'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col border" style="width: 34.7mm;">
                    <span>21 - Total de Gases Medicinais (R$)</span>
                    <br>
                    {{ ($key + 1) != $paginas ? '************************ ' : $cod_21 }}
                </div>                
                <div class="col border" style="width: 34.7mm;">
                    <span>22 - Total de Medicamentos (R$)</span>
                    <br>
                    {{ ($key + 1) != $paginas ? '************************ ' : $cod_22 }}
                </div>                
                <div class="col border" style="width: 34.7mm;">
                    <span>23 - Total de Materiais (R$)</span>
                    <br>
                    {{ ($key + 1) != $paginas ? '************************ ' : $cod_23 }}
                </div>                
                <div class="col border" style="width: 34.7mm;">
                    <span>24 - Total de OPME (R$)</span>
                    <br>
                    {{ ($key + 1) != $paginas ? '************************ ' : $cod_24 }}
                </div>                
                <div class="col border" style="width: 34.7mm;">
                    <span>25 - Total de Taxas e Aluguéis (R$)</span>
                    <br>
                    {{ ($key + 1) != $paginas ? '************************ ' : $cod_25 }}
                </div>                
                <div class="col border" style="width: 34.7mm;">
                    <span>26 - Total de Diárias (R$)</span>
                    <br>
                    {{ ($key + 1) != $paginas ? '************************ ' : $cod_26 }}
                </div>                
                <div class="col border" style="width: 34.7mm;">
                    <span>27 - Total Geral (R$)</span>
                    <br>
                    {{ ($key + 1) != $paginas ? '************************ ' : $cod_27 }}
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
    @endforeach
</body>

</html>
