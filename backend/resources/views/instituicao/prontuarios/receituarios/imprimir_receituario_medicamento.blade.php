
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- Favicon icon -->
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('material/assets/images/logo-h.png') }}">
        <title>Asa saude - admin</title>
        <!-- Bootstrap Core CSS -->
        {{-- <link rel="stylesheet" href="{{ asset('material/assets/plugins/jqueryui/jquery-ui.theme.min.css') }}"> --}}
        <link rel="stylesheet" href="{{ asset('material/assets/plugins/jqueryui/jquery-ui.css') }}">
        <link href="{{ asset('material/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

        <style>
            body{
                /* height: 100vh; */
            }
            .print-table {
                /* min-height: 100vh; */
                /* height: 100%; */
            }

            .content-wrap {
                min-height: 85%;
                display: flex;
                flex-direction: column;
                align-items: stretch;
            }

            #footer {
                flex: 0;
            }

            .print-table:last-child {
                page-break-after: avoid;
            }
            .ponto-preencher{
                background-image: url('{{asset("material/assets/images/ponto.png")}}');
                background-repeat:repeat-x;
                /* background-image: url(https://mdn.mozillademos.org/files/8971/firefox_logo.png); */
                background-size: 13px;
            }

            .total{
                width: 100%;
                display: flex;
            }
            .metade{
                width: 50%;
                margin: 2px;
            }
            .table-especial {
                width: 100%;
                border-collapse: collapse;
            }
            .table-especial, .td-especial {
                border: 1px solid;
            }

            #DivRodape{
                /* position: absolute; */
                top: auto;
                /* height: 35px; */
                /* line-height: 35px; */
                text-align: center;
                /* width: 100%; */
                /* background-color:#004085; */
            }
            #DivLateral{
                position:relative;
                border-width:2px;
                width:50%;
                /* height: 100px; */
                /* background-color: peachpuff; */
                float: left;
            }
            #DivA{
                position: relative;
                border-width:2px;
                width: 50%;
                /* height: 100px; */
                left:0px;
                /* background-color: red; */
                float: left;
            }
            .group:before,
            .group:after {
                content: "";
                display: table;
            } 
            .group:after {
                clear: both;
            }
            .group {
                zoom: 1; /* For IE 6/7 (trigger hasLayout) */
            }

        </style>
    </head>

    <body>
        <div class="print-table" @if (array_key_exists('impressao', $receituario->receituario))
            @if ($receituario->receituario['impressao'] != null)
            style="font-size: {{$receituario->receituario['impressao']['tamanho_fonte']}}px; padding: {{$receituario->receituario['impressao']['margem_cabecalho']}}cm {{$receituario->receituario['impressao']['margem_direita']}}cm {{$receituario->receituario['impressao']['margem_rodape']}}cm {{$receituario->receituario['impressao']['margem_esquerda']}}cm"
            @endif
            @endif>
            <div class="content-wrap">
                <div>
                    @if (array_key_exists('impressao', $receituario->receituario))
                        @if ($receituario->receituario['impressao'] != null)
                            {!! $receituario->receituario['impressao']['cabecalho'] !!}
                        @endif
                    @endif
                </div>
                <div>
                    {{-- <hr style="margin-top: 60px;"> --}}
                    @if ($exibir_titulo_paciente)
                        <p style="text-align: center"><b><span>RECEITUÁRIO</span></b></p>
                    @endif
                    @if ($receituario->tipo == "especial")
                        {{-- <p style="position: absolute"> --}}
                            <div class="group" >
                                <div class="metade" id="DivA">
                                    <table class="table-especial">
                                        <tbody>
                                            <tr style="text-align: center">
                                                <td class="td-especial"><b>IDENTIFICAÇÃO DO EMINENTE</b></td>
                                            </tr>
                                            <tr>
                                                <td class="td-especial" style="padding: 8px;">
                                                    <p style="margin: 1px"><b>Nome: </b>{{$receituario->usuario->prestadorMedico[0]->prestador->nome}}</p>
                                                    <p style="margin: 1px"><b>CRM: </b>{{$dadosEspecial['crm']}}</p>
                                                    <p style="margin: 1px"><b>Endereço: </b>{{$dadosEspecial['rua']}}, {{$dadosEspecial['numero']}}, {{$dadosEspecial['bairro']}}</p>
                                                    <p style="margin: 1px"><b>Telefone: </b>{{$dadosEspecial['telefone']}}</p>
                                                    <p style="margin: 1px"><b>Cidade/UF: </b>{{$dadosEspecial['cidade']}}/{{$dadosEspecial['estado']}}</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="metade" id="DivLateral">
                                    <p style="text-align: right;margin: 1px">1° VIA FARMÁCIA</p>
                                    <p style="text-align: right;margin: 1px">1° VIA PACIENTE</p>
                                    <p></p>
                                    <p></p>
                                    <span style="bottom: 0; width: 100%">
                                        <p style="text-align: center; width: 100%"><b>____________________________________</b></p>
                                        <p style="text-align: center; width: 100%">Assinatura/Carimbo</p>
                                    </span>
                                </div>
                            </div>
                        {{-- </p> --}}
                    @endif
                    {{-- <p> --}}
                        {{-- <div > --}}
                            @if ($exibir_titulo_paciente)
                                <div class="col-md-12" id="DivRodape">
                                    <table class="table" style="margin-bottom: 0px;">
                                        <tbody>
                                            <tr>
                                            <td style="text-align: left; border-top: 0px; padding: 0px"><b>Paciente: </b>{{$agendamento->pessoa->nome}}</td>
                                            <td style="text-align: right; border-top: 0px; padding: 0px">@if ($exibir_data)<b>Data: </b>{{date("d/m/Y", strtotime($agendamento->data))}} @endif</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        {{-- </div> --}}
                    {{-- </p> --}}
                    {{-- <p> --}}
                        <div  style="position: sticky">
                            @foreach ($medicamentos as $item)
                                <div class="col-md-12" style="margin-bottom: 5px; margin-top: 15px">
                                    <span><b>Uso: </b>{{$item['via_adm']}}</span>
                                </div>    
                                @php
                                    $j = 0;
                                    unset($item['via_adm']);
                                @endphp
                                @for ($j = 0; $j < count($item); $j++)
                                    <div class="col-md-12" >
                                        <table class="table" style="margin: 0px">
                                            <tbody>
                                                <tr>
                                                    <td style="text-align: left; border-top: 0px; padding: 0px;flex-grow: 0">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div style="display: flex; width: 100%;">
                                                                    <div class="texto" style="flex-grow: 0">{{$j+1}}) {{$item[$j]['medicamento']['nome']}}</div>
                                                                    <div  style="flex-grow: 1">
                                                                        <table class="table" style="margin: 0px; padding: 0px">
                                                                            <tbody>
                                                                                <tr><td style="padding: 0px"></td></tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                   
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="text-align: right; border-top: 0px; padding: 0px;flex-grow: 0">{{$item[$j]['quantidade']}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    @if ($item[$j]['medicamento']['composicao'] != null)
                                        @foreach ($item[$j]['medicamento']['composicao'] as $composicao)
                                            <div class="col-md-12" >
                                                <table class="table" style="margin: 0px">
                                                    <tbody>
                                                        <tr>
                                                            <td style="text-align: left; border-top: 0px; padding: 0px;flex-grow: 0; padding-left: 30px; width: 80%;">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div style="display: flex; width: 100%;">
                                                                            <div class="texto" style="flex-grow: 0">{{$composicao['substancia']}}</div>
                                                                            <div  style="flex-grow: 1">
                                                                                <table class="table" style="margin: 0px; padding: 0px">
                                                                                    <tbody>
                                                                                        <tr><td style="padding: 0px"></td></tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td style="text-align: right; border-top: 0px; padding: 0px;flex-grow: 0; padding-right: 20px;">{{$composicao['concentracao']}}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endforeach                                                       
                                    @endif
                                    <div class="col-md-12" style="margin-bottom: 5px;">
                                        <span style="text-align: left; border-top: 0px; padding-left: 0px;" colspan="3">{{$item[$j]['posologia']}}</span>
                                    </div>
                                @endfor
                            @endforeach
                        </div>
                    {{-- </p> --}}
                    @if ($receituario->tipo == "especial")
                        <p>
                            <div class="group">
                                <div class="metade" id="DivA">
                                    <table class="table-especial">
                                        <tbody>
                                            <tr style="text-align: center">
                                                <td class="td-especial"><b>IDENTIFICAÇÃO DO COMPRADOR</b></td>
                                            </tr>
                                            <tr>
                                                <td class="td-especial" style="padding: 8px;">
                                                    <p style="margin: 1px"><b>Nome: </b></p>
                                                    <p style="margin: 1px"><b>CRM: </b></p>
                                                    <p style="margin: 1px"><b>Endereço: </b></p>
                                                    <p style="margin: 1px"><b>Telefone: </b></p>
                                                    <p style="margin: 1px"><b>Cidade/UF: </b></p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="metade" id="DivLateral">
                                    <table class="table-especial">
                                        <tbody>
                                            <tr style="text-align: center">
                                                <td class="td-especial"><b>IDENTIFICAÇÃO DO FORNECEDOR</b></td>
                                            </tr>
                                            <tr>
                                                <td class="td-especial" style="padding: 8px;">
                                                    <p style="margin: 1px; text-align: center">______/_______/________</p>
                                                    <p style="margin: 1px; text-align: center; margin-bottom: 20px"><b>Data</b></p>
                                                    <p style="margin: 1px; text-align: center">_______________________________</p>
                                                    <p style="margin: 1px; text-align: center">Assinatura fornecedor</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </p>
                    @endif
                </div>   
            
            </div>   
            
            <div id="footer">
                @if (array_key_exists('impressao', $receituario->receituario))
                    @if ($receituario->receituario['impressao'] != null)
                        {!! $receituario->receituario['impressao']['rodape'] !!}
                    @endif
                @endif
            </div>
        </div>


        <script src="{{ asset('material/assets/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('material/assets/plugins/jqueryui/jquery-ui.min.js') }}"></script>
        
        <script src="{{ asset('material/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

        <script>
            $(document).ready(function(){
                setTimeout(() => {
                    window.print();
                }, 0);
            })
        </script>
    </body>
</html>
