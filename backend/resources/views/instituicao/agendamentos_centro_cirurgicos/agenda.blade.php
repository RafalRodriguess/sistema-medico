<div class="agenda_eventos">
    <div>
        <b>Intervalo</b>: {{\Carbon\Carbon::parse($hora_inicio)->format('H:i')}} - {{\Carbon\Carbon::parse($hora_fim)->format('H:i')}}
        {{-- <p><b>Total horas disponivel dia</b>: {{$total_disponivel_dia}}hrs</p> --}}
    </div>
    @if (count($agendamentos) > 0)
        @foreach ($agendamentos as $item)
            <div class="agendamento clickable
                @if ($item->tipo == 'cirurgia')
                    
                    @php
                        $tempo = explode(':', $item->salaCirurgica->tempo_minimo_preparo);
                    @endphp
                    @if ($item->status == "pendente")
                        status-2
                    @elseif($item->status == "confirmado")
                        status-3
                    @elseif($item->status == "em_atendimento")
                        status-5
                    @elseif($item->status == "finalizado")
                        status-7
                    @endif
                    {{-- @if (date('Y-m-d H:i') < date('Y-m-d H:i', strtotime($item->hora_inicio.' +'.$tempo[0].' hours +'.$tempo[1].' minutes')) && date('Y-m-d H:i') >= date('Y-m-d H:i', strtotime($item->hora_inicio)))
                        status-2
                    @else
                        @if (date('Y-m-d H:i') >= date('Y-m-d H:i', strtotime($item->hora_inicio.' +'.$tempo[0].' hours +'.$tempo[1].' minutes')))
                            status-1
                        @else
                            status-0
                        @endif
                    @endif --}}
                @else
                    status-5
                @endif " data-id="{{$item->id}}" >
                <div class="agendamento_col agendamento-icone">
                    @if ($item->tipo == 'cirurgia')
                        <span style="font-weight:bold;"> Aviso de cirurgia: {{$item->id}} </span>
                    @endif
                </div>
                <div class="agendamento_col agendamento_texto">
                    @if ($item->tipo == 'cirurgia')
                        <span style="font-weight:bold;"> {{$item->cirurgiao->nome}} - {{$item->salaCirurgica->descricao}} - {{$item->cirurgia->descricao}} </span>
                    @else
                        <span style="font-weight:bold;"> Fechado / Interditado </span>
                    @endif
                </div>
                <div class="agendamento_col agendamento-procedimentos">
                    <span style="font-weight:bold;"> Hora: {{\Carbon\Carbon::parse($item->hora_inicio)->format('H:i')}} - {{\Carbon\Carbon::parse($item->hora_final)->format('H:i')}} @if (date('Y-m-d', strtotime($item->hora_inicio)) < date('Y-m-d', strtotime($data_selecionada)))
                        (inicio dia anterior)
                    @endif</span>
                </div>
                <div class="agendamento_col agendamento_actions">
                    <div class="btn-group">
                        @if ($item->tipo == 'cirurgia')
                            <button type="button" @if($item->status == "finalizado") style="display: none" @endif class="btn mudar_status"  data-toggle="tooltip" data-id="{{$item->id}}" title="
                                @if ($item->status == "pendente")
                                    Confirmar cirurgia
                                @elseif($item->status == "confirmado")
                                    Iniciar atendimento
                                @elseif($item->status == "em_atendimento")
                                    Finalizar cirurgia
                                @endif
                                ">
                                <span class="mdi mdi-check"></span>
                            </button>
                        @endif
                        <button type="button" class="btn excluir_agendamento"  data-toggle="tooltip" data-id="{{$item->id}}" @if ($item->tipo == 'cirurgia') title="Excluir agendamento" @else title="Desinterditar" @endif>
                            <span class="mdi mdi-close-box-outline"></span>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

<script>
    $("body").on('click', '.excluir_agendamento', function() {
        var id = $(this).attr('data-id');
        title = "Excluir!";
        text = 'Deseja excluir o agendamento ?';
        icon = "warning";

        if($(this).attr('data-tipo') != "cirurgia"){
            title = "Desinterditar!";
            text = 'Deseja desinterditar a sala ?';
            icon = "warning";
        }

        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $.ajax("{{ route('instituicao.agendamentoCentroCirurgico.excluirAgendamento', ['agendamento' => 'agendamentoId']) }}".replace('agendamentoId', id), {
                    method: "POST",
                    data: {'_token': '{{csrf_token()}}'},
                    success: function (response) {

                        $.toast({
                            heading: response.title,
                            text: response.text,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: response.icon,
                            hideAfter: 3000,
                            stack: 10
                        });

                        if(response.icon=='success'){

                            getCentroCirurgico();
                        }

                    },
                    error: function (response) {
                        if(response.responseJSON.errors){
                            Object.keys(response.responseJSON.errors).forEach(function(key) {
                                $.toast({
                                    heading: 'Erro',
                                    text: response.responseJSON.errors[key][0],
                                    position: 'top-right',
                                    loaderBg: '#ff6849',
                                    icon: 'error',
                                    hideAfter: 9000,
                                    stack: 10
                                });

                            });
                        }
                    }
                })
            }
        })
    })
    
    $("body").on('click', '.mudar_status', function(e) {
        e.stopPropagation();
        var id = $(this).attr('data-id');
        Swal.fire({
            title: "Status!",
            text: 'Deseja mudar o status da cirurgia ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $.ajax("{{ route('instituicao.agendamentoCentroCirurgico.mudarStatusAgendamento', ['agendamento' => 'agendamentoId']) }}".replace('agendamentoId', id), {
                    method: "POST",
                    data: {'_token': '{{csrf_token()}}'},
                    success: function (response) {

                        $.toast({
                            heading: response.title,
                            text: response.text,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: response.icon,
                            hideAfter: 3000,
                            stack: 10
                        });

                        if(response.icon=='success'){

                            getCentroCirurgico();
                        }

                    },
                    error: function (response) {
                        if(response.responseJSON.errors){
                            Object.keys(response.responseJSON.errors).forEach(function(key) {
                                $.toast({
                                    heading: 'Erro',
                                    text: response.responseJSON.errors[key][0],
                                    position: 'top-right',
                                    loaderBg: '#ff6849',
                                    icon: 'error',
                                    hideAfter: 9000,
                                    stack: 10
                                });

                            });
                        }
                    }
                })
            }
        })
    })

    $('body').on('click', '.agendamento.clickable',function(e){
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        $("#modal_visualizar").html("");
        var id = $(this).attr('data-id');

        var url = "{{ route('instituicao.agendamentoCentroCirurgico.editarAgenda', ['agendamento' => 'agendaId']) }}".replace('agendaId', id);
        var data = {
            '_token': '{{csrf_token()}}'
        };
        var modal = 'modalEditarAgenda';
        
        $('#loading').removeClass('loading-off');
        $('#modal_visualizar').load(url, data, function(resposta, status) {
            $('#' + modal).modal();
            $('#loading').addClass('loading-off');
        });
    })
</script>