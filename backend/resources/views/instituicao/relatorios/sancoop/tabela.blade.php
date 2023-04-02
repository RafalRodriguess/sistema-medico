<table class="table table-striped">
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
    <tfoot>
    </tfoot>
</table>

<div class="col-sm-12 ">
    <div class="row justify-content-md-center">
        <div class="card card-body text-center mx-2 col-sm-2">
            <label class="card-title">Total Guias</label>
            <h3 class="lead">{{$qtd_total}}</h3>
        </div>
        <div class="card card-body text-center mx-2 col-sm-2">
            <label class="card-title">Abertas</label>
            <h3 class="lead">{{$qtd_abertas}}</h3>
        </div>
        <div class="card card-body text-center mx-2 col-sm-2">
            <label class="card-title">Transmitidas</label>
            <h3 class="lead">{{$qtd_transmitidas}}</h3>
        </div>
        <div class="card card-body text-center mx-2 col-sm-2">
            <label class="card-title">Com pendência</label>
            <h3 class="lead">{{$qtd_pendencias}}</h3>
        </div>
        <div class="card card-body text-center mx-2 col-sm-2">
            <label class="card-title">Conferidas e auditadas</label>
            <h3 class="lead">{{$qtd_confirmadas}}</h3>
        </div>
    </div>
</div>

<div class="col-sm-12 ">
    <div class="row justify-content-md-center">
        <div class="card card-body text-center mx-2 col-sm-2">
            <label class="card-title">Sadt</label>
            <h3 class="lead">{{$qtd_sadt}} - {{number_format(($qtd_sadt/$qtd_total) * 100, 2)}}%</h3>
        </div>
        <div class="card card-body text-center mx-2 col-sm-2">
            <label class="card-title">Consulta</label>
            <h3 class="lead">{{$qtd_consulta}} - {{number_format(($qtd_consulta/$qtd_total) * 100, 2)}}%</h3>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>