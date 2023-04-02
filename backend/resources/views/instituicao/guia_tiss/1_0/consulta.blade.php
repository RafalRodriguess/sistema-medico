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
            width: 190mm;
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
    </style>
</head>

<body>
    <div class="container">
        <div class="row" style="height: 12mm; padding-top: 2px;">
            <div class="col" style="width: 40mm;">
                <img src="{{ $logo }}" alt="" height="40px" style="margin-left: 10px;">
            </div>
            <div class="col" style="width: 90mm; text-align: center;">
                <strong style="font-size: 14px;">
                    GUIA DE CONSULTA
                </strong>
            </div>
            <div class="col" style="width: 60mm;  font-size: 10px;">
                2 - Nº Guia no Prestador <strong style="font-size: 16px;">{{ $cod_2 }}</strong>
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
                <span>Dados do Beneficiário</span>
            </div>
        </div>

        <div class="row">
            <div class="col border" style="width: 90mm;">
                <span>4 - Número da Carteira</span>
                <br>
                {{ $cod_4 }}
            </div>
            <div class="col border" style="width: 45mm;">
                <span>5 - Validade da Carteira</span>
                <br>
                {{ $cod_5 }}
            </div>
            <div class="col border" style="width: 40mm;">
                <span>6 - Atendimento a RN</span>
                <br>
                {{ $cod_6 }}
            </div>
        </div>

        <div class="row">
            <div class="col border" style="width: 122mm;">
                <span>7 - Nome</span>
                <br>
                {{ $cod_7 }}
            </div>
            <div class="col border" style="width: 65mm;">
                <span>8 - Cartão Nacional de Saúde</span>
                <br>
                {{ $cod_8 }}
            </div>
        </div>

        <div class="row divider">
            <div class="col">
                <span>Dados do Contrato</span>
            </div>
        </div>

        <div class="row">
            <div class="col border" style="width: 30mm;">
                <span>9 - Código na Operadora</span>
                <br>
                {{ $cod_9 }}
            </div>
            <div class="col border" style="width: 136mm;">
                <span>10 - Nome do Contratado</span>
                <br>
                {{ $cod_10 }}
            </div>
            <div class="col border" style="width: 20mm;">
                <span>11 - Código CNES</span>
                <br>
                {{ $cod_11 }}
            </div>
        </div>

        <div class="row">
            <div class="col border" style="width: 75mm;">
                <span>12 - Nome do Profissional Executante</span>
                <br>
                {{ $cod_12 }}
            </div>
            <div class="col border" style="width: 20mm;">
                <span>13 - Conselho Profissional</span>
                <br>
                {{ $cod_13 }}
            </div>
            <div class="col border" style="width: 51.5mm;">
                <span>14 - Número no Conselho</span>
                <br>
                {{ $cod_14 }}
            </div>
            <div class="col border" style="width: 15mm;">
                <span>15 - UF</span>
                <br>
                {{ $cod_15 }}
            </div>
            <div class="col border" style="width: 22mm;">
                <span>16 - Código CBO</span>
                <br>
                {{ $cod_16 }}
            </div>
        </div>

        <div class="row divider">
            <div class="col">
                <span>Dados do Atendimento / Procedimento Realizado</span>
            </div>
        </div>

        <div class="row">
            <div class="col border" style="width: 60mm;">
                <span>17 - Indicação de Acidente (acidente ou doença relacionada)</span>
                <br>
                {{ $cod_17 }}
            </div>
        </div>

        <div class="row">
            <div class="col border" style="width: 42mm;">
                <span>18 - Data do Atendimento</span>
                <br>
                {{ $cod_18 }}
            </div>
            <div class="col border" style="width: 22mm; margin-right: 17mm">
                <span>19 - Tipo de Consulta</span>
                <br>
                {{ $cod_19 }}
            </div>
            <div class="col border" style="width: 18mm;">
                <span>20 - Tabela</span>
                <br>
                {{ $cod_20 }}
            </div>
            <div class="col border" style="width: 45mm;">
                <span>21 - Código do Procedimento</span>
                <br>
                {{ $cod_21 }}
            </div>
            <div class="col border" style="width: 40mm;">
                <span>22 - Valor do Procedimento</span>
                <br>
                {{ $cod_22 }}
            </div>
        </div>

        <div class="row divider" style="height: 22mm;">
            <div class="col">
                <span>23 - Observação / Justificativa</span>
                <br>
                {{ $cod_23 }}
            </div>
        </div>

        <div class="row" style="height: 10mm">
            <div class="col border" style="width: 93.8mm; height: 10mm">
                <span>24 - Assinatura do Profissional Executante</span>
                <br>
                {{ $cod_24 }}
            </div>
            <div class="col border" style="width: 93.8mm; height: 10mm">
                <span>25 - Assinatura do Beneficiário ou Responsável</span>
                <br>
                {{ $cod_25 }}
            </div>
        </div>
    </div>
</body>

</html>
