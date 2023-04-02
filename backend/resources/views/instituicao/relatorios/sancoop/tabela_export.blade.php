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
                height: 100vh;
            }

            .content-wrap {
                min-height: 90%;
                display: flex;
                flex-direction: column;
                align-items: stretch;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 10px;
                /* border: 1px solid; */
            }
        </style>
    </head>

    <body>
        <div class="content-wrap">
            <div class='container col mt-3'>
                <div class="cabecalho">
                    <center>
                        <h6 >{{$instituicao->nome}}</h6>
                        <label class="col-sm-2">{{date("d/m/Y H:i:s")}}</label>
                    </center>

                    <h5 class="mt-2"><center>Relatório de guias sancoop</center></h4>

                    <hr class="hr-line-dashed">
                </div>
                <table class="table table-striped table-bordered table-sm text-center" style="width: 100%">
                    <thead> 
                        <tr>
                            <th>Protocolo</th>
                            <th>Data Envio</th>
                            <th>Data atendimento</th>
                            <th >Paciente</th>
                            <th >Profissional</th>
                            <th >Cod Autorização</th>
                            <th >Nº Guia</th>
                            <th>Tipo guia</th>
                            <th>Convenio</th>
                            <th>Procedimento</th>     
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $qtd_total = 0;
                            $qtd_procedimentos = 0;
                            $qtd_sadt = 0;
                            $qtd_consulta = 0;
                            $qtd_transmitidas = 0;
                            $qtd_abertas = 0;
                            $qtd_pendencias = 0;
                            $qtd_confirmadas = 0;

                        @endphp

                        @foreach ($guias as $item)
                            @foreach($item->guias as $faturamento_guia)
                                @if(!empty($faturamento_guia->agendamento_paciente))
                                    @foreach($faturamento_guia->agendamento_paciente->agendamentoGuias as $guias)
                                        <tr>
                                            <td>{{$item->cod_externo}}</td>
                                            <td>{{$item->created_at->format('d/m/Y')}}</td>
                                            <td>{{$faturamento_guia->agendamento_paciente->data->format('d/m/Y')}}</td>
                                            <td>{{$faturamento_guia->agendamento_paciente->pessoa->nome}}</td>
                                            <td>{{$item->prestador->nome}}</td>
                                            <td>{{$guias->cod_aut_convenio}}</td>
                                            <td>{{$guias->num_guia_convenio}}</td>
                                            <td>{{$guias->tipo_guia}}</td>
                                            <td>{{$faturamento_guia->agendamento_paciente->agendamentoProcedimento[0]->procedimentoInstituicaoConvenioTrashed->convenios->nome}}</td>
                                            <td>
                                                @php
                                                    $qtd_total += 1;
                                                    $procediemntos = [];
                                                    foreach($faturamento_guia->agendamento_paciente->agendamentoProcedimento as $values){
                                                        $qtd_procedimentos += 1;
                                                        $procediemntos[] = $values->procedimentoInstituicaoConvenioTrashed->procedimento->descricao;
                                                    }
                                                    
                                                    if( $item->status == 0){
                                                        $qtd_abertas += 1;
                                                    }else if($item->status == 1){
                                                        $qtd_transmitidas += 1;
                                                    }else if($item->status == 2){
                                                        $qtd_confirmadas += 1;
                                                    }else if($item->status == 3){
                                                        $qtd_pendencias += 1;
                                                    }
                                                    

                                                    if($guias->tipo_guia == 'consulta'){
                                                        $qtd_consulta += 1;
                                                    }else if($guias->tipo_guia == 'sadt'){
                                                        $qtd_sadt += 1;
                                                    }
                                                @endphp

                                                {{implode("; ", array_unique($procediemntos))}}
                                            </td>    
                                            <td>{{App\FaturamentoLote::getStatus($item->status)}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>

                <hr>

                <table class="table table-striped table-bordered table-sm text-center">
                    <tr>
                        <th>Total Guias</th>
                        <th>Abertas</th>
                        <th>Transmitidas</th>
                        <th>Com pendência</th>
                        <th>Conferidas e auditadas</th>
                    </tr>
                    <tr>
                        <td><h3 class="lead text-center">{{$qtd_total}}</h3></td>
                        <td><h3 class="lead text-center">{{$qtd_abertas}}</h3></td>
                        <td><h3 class="lead text-center">{{$qtd_transmitidas}}</h3></td>
                        <td><h3 class="lead text-center">{{$qtd_pendencias}}</h3></td>
                        <td><h3 class="lead text-center">{{$qtd_confirmadas}}</h3></td>
                    </tr>
                </table>
                
                <hr>
                
                <table class="table table-striped table-bordered table-sm text-center">
                    <tr>
                        <th>Sadt</th>
                        <th>Consulta</th>
                    </tr>
                    <tr>
                        <td><h3 class="lead text-center">{{$qtd_sadt}} - {{number_format(($qtd_sadt/$qtd_total) * 100, 2)}}%</h3></td>
                        <td><h3 class="lead text-center">{{$qtd_consulta}} - {{number_format(($qtd_consulta/$qtd_total) * 100, 2)}}%</h3></td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>