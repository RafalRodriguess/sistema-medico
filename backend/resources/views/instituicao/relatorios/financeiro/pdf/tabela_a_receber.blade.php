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

            .content-wrap {
                min-height: 85%;
                display: flex;
                flex-direction: column;
                align-items: stretch;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                border: 1px solid;
            }
        </style>
    </head>

    <body>
        <div class="print-table" style="font-size:10px">
            <div class="content-wrap">
                <div class="cabecalho">
                    <center>
                        <h6 >{{$instituicao->nome}}</h6>
                        <label class="col-sm-2">{{date("d/m/Y H:i:s")}}</label>
                    </center>

                    <h5 class="mt-2"><center>Relatório de contas a receber</center></h4>

                    <hr class="hr-line-dashed">
                </div>
                <table class="table table-bordered table-sm" style="width: 100%">
                    <thead>
                        <tr class="text-center">
                            <th class="col_id">#Id</th>
                            <th class="col_favorecido">Favorecido</th>
                            <th class="col_descricao">Descrição</th>
                            <th class="col_plano_conta">Plano de conta</th>            
                            <th class="col_caixa">Conta Caixa</th>
                            <th class="vencimento">Dt vencimento</th>            
                            <th class="vl_parcela">Vl parcela</th>
                            <th class="dt_compensacao">Dt compensação</th>
                            <th class="forma_pg">Forma pagamento</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dados as $item)
                            <tr>
                                <td class="col_id">{{$item->id}}</td>
                                <td class="col_favorecido">
                                    @if ($item->prestador)
                                        Prestador: {{$item->prestador->nome}};
                                    @elseif ($item->paciente)
                                        Paciente: {{$item->paciente->nome}};
                                    @elseif($item->fornecedor)
                                        Fornecedor: {{(!empty($item->fornecedor->nome_fantasia)) ? $item->fornecedor->nome_fantasia : $item->fornecedor->nome}}
                                    @endif
                                </td>
                                <td class="col_descricao">{{$item->descricao}}</td>
                                <td class="col_plano_conta">{{$item->planoConta ? $item->planoConta->codigo." ".$item->planoConta->descricao : '-'}}</td>
                                <td class="col_caixa">{{(!empty($item->contaCaixa)) ? $item->contaCaixa->descricao : "-"}}</td>                
                                <td class="vencimento">{{\Carbon\Carbon::createFromFormat('Y-m-d',$item->data_vencimento)->format('d/m/Y')}}</td>
                                <td class="vl_parcela">{{number_format($item->valor_parcela, 2, ',','.')}}</td>
                                <td class="dt_compensacao">{{$item->data_compensacao ? \Carbon\Carbon::createFromFormat('Y-m-d',$item->data_compensacao)->format('d/m/Y') : '-'}}</td>
                                <td class="forma_pg">{{($item->forma_pagamento) ? App\ContaPagar::forma_pagamento_texto($item->forma_pagamento) : '-'}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                        <th colspan="6">Total</th>
                        <th>{{number_format($dados->sum('valor_parcela'), 2, ',', '.')}}</th> 
                            <th colspan="2"></th>
                        </tr> 
                    </tfoot>
                </table>
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