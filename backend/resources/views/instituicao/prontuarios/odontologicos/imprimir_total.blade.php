
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
        <div class="print-table" 
            @if (!empty($impressao))
            style="font-size: {{$impressao->tamanho_fonte}}px; padding: {{$impressao->margem_cabecalho}}cm {{$impressao->margem_direita}}cm {{$impressao->margem_rodape}}cm {{$impressao->margem_esquerda}}cm"
            @endif>
            <div class="content-wrap">
                <div>
                    @if (!empty($impressao))
                        {!! $impressao->cabecalho !!}
                    @endif
                </div>
                <div>
                    {{-- <hr style="margin-top: 60px;"> --}}
                    <p style="text-align: center"><b><span>ORÇAMENTO</span></b></p>
                    {{-- <p> --}}
                        {{-- <div > --}}
                    <div class="col-md-12" id="DivRodape">
                        <table class="table" style="margin-bottom: 30px;">
                            <tbody>
                                <tr>
                                <td style="text-align: left; border-top: 0px; padding: 0px"><b>Paciente: </b>{{$orcamento->paciente->nome}}</td>
                                <td style="text-align: right; border-top: 0px; padding: 0px"><b>Data orçamento: </b>{{date("d/m/Y", strtotime($orcamento->created_at))}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                        {{-- </div> --}}
                    {{-- </p> --}}
                    {{-- <p> --}}
                        <div  style="position: sticky">
                              
                            <div class="col-md-12" >
                                <table class="table" style="margin: 0px">
                                    <thead>
                                        <tr>
                                            <th>Dente</th>
                                            <th>Procedimento</th>
                                            <th></th>
                                            <th>Regiao</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $valor_total = 0;
                                        @endphp
                                        @foreach ($orcamento->itens as $item) 
                                            @php
                                                if ($orcamento->status == 'criado' || $orcamento->status == 'reprovado'){
                                                    if (array_key_exists($item->id, $itensValores)){
                                                        $valor_total += $itensValores[$item->id]['valor'] + $itensValores[$item->id]['desconto'];
                                                    }else{
                                                        $valor_total += $item->valor;
                                                    }
                                                }else{
                                                    $valor_total += $item->valor + $item->desconto;
                                                }
                                            @endphp
                                            @if ($orcamento->status == 'criado' || $orcamento->status == 'reprovado')
                                                @if (array_key_exists($item->id, $itensValores))
                                                    <tr>
                                                        <td>{{$item->dente_id}}</td>
                                                        <td>{{$item->procedimentosItens->descricao}}</td>
                                                        <td></td>
                                                        <td>
                                                            @if ($item->regiao)
                                                                {{($item->regiao) ? $item->regiao->descricao : ''}}
                                                            @elseif(count($item->regiaoProcedimento) > 0)
                                                                @foreach ($item->regiaoProcedimento as $keyR => $regiao)
                                                                    @if ($keyR == 0)
                                                                        {{$regiao->descricao}}
                                                                    @else
                                                                            / {{$regiao->descricao}}
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @else
                                                <tr>
                                                    <td>{{$item->dente_id}}</td>
                                                    <td>{{$item->procedimentosItens->descricao}}</td>
                                                    <td></td>
                                                    <td>
                                                        @if ($item->regiao)
                                                            {{($item->regiao) ? $item->regiao->descricao : ''}}
                                                        @elseif(count($item->regiaoProcedimento) > 0)
                                                            @foreach ($item->regiaoProcedimento as $keyR => $regiao)
                                                                @if ($keyR == 0)
                                                                    {{$regiao->descricao}}
                                                                @else
                                                                        / {{$regiao->descricao}}
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>Total</td>
                                            <td>
                                                @if ($orcamento->status == 'criado' || $orcamento->status == 'reprovado')
                                                    R$ {{number_format($valor_orcamento, 2, ',','.')}}
                                                @else
                                                    R$ {{number_format($valor_total, 2, ',','.')}}
                                                @endif
                                            </td>
                                        </tr>
                                        @if ($orcamento->desconto > 0)
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td>Desconto</td>
                                                <td>R$ - {{number_format($orcamento->desconto, 2, ',','.')}}</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td>Total geral</td>
                                                <td>R$ {{number_format(($orcamento->valor_aprovado - $orcamento->desconto), 2, ',','.')}}</td>
                                            </tr>
                                        @endif
                                    </tfoot>
                                </table>
                            </div>
                            
                        </div>
                    {{-- </p> --}}
                </div>   
            
            </div>   
            
            <div id="footer">
                @if (!empty($impressao))
                    {!! $impressao->rodape !!}
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
