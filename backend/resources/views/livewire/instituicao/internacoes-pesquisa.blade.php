<div class="card-body">
    <form action="javascript:void(0)" id="formPesquisa">
        <div class="row pb-2"> 
            <div class="form-group" wire:ignore>
                <input type="text" name ="data" id="data" class="form-control" wire:ignore style="display: none;"> 
            </div>
            <div class="col-md">
                @can('habilidade_instituicao_sessao', 'cadastrar_internacao')
                        <a href="{{ route('instituicao.internacoes.create') }}">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-info btn-circle"><i class="mdi mdi-plus"></i></button>
                        </a>
                
                @endcan
                
                <button type="button" id="resetDate" class="btn waves-effect waves-light btn-block btn-secondary btn-circle" data-toggle="tooltip" title="" data-original-title="Resetar filtro de data"><i class="mdi mdi-calendar-remove"></i></button>

            </div>
        </div>

        <div class="row col-md-12">
            <div class="col-md-6">
                <div class="form-group" wire:ignore>
                    <label class="form-control-label p-0 m-0">Paciente</label>
                    <select name="paciente_id" id="paciente_id" class="form-control"></select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group" wire:ignore>
                    <label class="form-control-label p-0 m-0">Médico</label>
                    <select name="medico_id" id="medico_id" class="form-control selectfild2" wire:model="medico_id">
                        <option value="0">Todos Medicos</option>
                        @foreach ($medicos as $medico)
                            <option value="{{ $medico->id }}">{{ $medico->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group" wire:ignore>
                    <label class="form-control-label p-0 m-0">Especialidade</label>
                    <select name="especialidade_id" id="especialidade_id" class="form-control selectfild2" wire:model="especialidade_id">
                        <option value="">Todas especialidades</option>
                        @foreach ($especialidades as $item)
                            <option value="{{$item->id}}">{{$item->descricao}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group" wire:ignore>
                    <label class="form-control-label p-0 m-0">Convênio:</span></label>
                    <select name="convenio_id" id="convenio_id" class="form-control selectfild2" wire:model="convenio_id">
                        <option value="">Selecione um convênio</option>
                        @foreach ($convenios as $item)
                            <option value="{{$item->id}}">{{$item->nome}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group" wire:ignore>
                    <label class="form-control-label p-0 m-0">Tipo</label>
                    <select class="form-control selectfild2" name="tipo_internacao" id="tipo_internacao" wire:model="tipo_internacao">
                        <option value="" >Nenhum</option>
                        <option value="1" >Clínico</option>
                        <option value="2" >Cirúrgico</option>
                        <option value="3" >Materno-Infantil</option>
                        <option value="4" >Neonatalogia</option>
                        <option value="5" >Obstetrícia</option>
                        <option value="6" >Pediatria</option>
                        <option value="7" >Psiquiatria</option>
                        <option value="8" >Outros</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group" wire:ignore>                    
                    <label class="form-control-label p-0 m-0">Acomodação:</span></label>
                    <select name="acomodacao_id" id="acomodacao_id" class="form-control selectfild2" wire:model="acomodacao_id">
                        <option value="">Selecione uma acomodação</option>
                        @foreach ($acomodacoes as $item)
                            <option value="{{$item->id}}">{{$item->descricao}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group" wire:ignore>                    
                    <label class="form-control-label p-0 m-0">Unidade:</span></label>
                    <select name="unidade_id" id="unidade_id" class="form-control selectfild2" wire:model="unidade_id">
                        <option value="">Selecione uma unidade</option>
                        @foreach ($unidades as $item)
                            <option value="{{$item->id}}">{{$item->nome}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group" wire:ignore>                    
                    <label class="form-control-label p-0 m-0">Leito:</span></label>
                    <select name="leito_id" id="leito_id" class="form-control selectfild2" wire:model="leito_id">
                        <option value="">Selecione um leito</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group" wire:ignore>                    
                    <div class="checkbox checkbox-primary" style="margin-top: 30px; margin-left: 15px;">
                        <input type="checkbox" id="check_previsao_alta" name="check_previsao_alta" value="1" class="filled-in" wire:model="previsao_alta"/>
                        <label for="check_previsao_alta">Buscar por previsão de alta</label>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    <hr>

    <div class="row">

        <div class="table-responsive col-md-9">
            <table class="tablesaw table-bordered table-hover table" >
                <thead>
                    <tr>
                        <th scope="col" >ID</th>
                        <th scope="col" >Paciente</th>
                        <th scope="col" >Médico</th>
                        <th scope="col" >Possui Responsável</th>
                        <th scope="col" >Alta médica</th>
                        <th scope="col" >Alta hospitalar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($internacoes as $internacao)
                    @php($alta = array_search(null, array_column($internacao->alta->toArray(), 'data_cancel_alta')))

                        <tr>
                            <td class="title"><a href="javascript:void(0)">{{ $internacao->id }}</a></td>
                            <td>{{ $internacao->paciente->nome }}</td>
                            
                            <td>{{ $internacao->medico ? $internacao->medico->nome : "" }}</td>
                            <td>{{ ($internacao->possui_responsavel == 1) ? 'Sim' : 'Não' }}</td>
                            
                            <td>{{ ( $internacao->alta_internacao == 1) ? 'Sim' : 'Não' }}</td>
                            <td>{{ ( $internacao->alta_hospitalar == 1) ? 'Sim' : 'Não' }}</td>
                        
                            <td>                                
                                @can('habilidade_instituicao_sessao', 'editar_internacao')
                                    <a href="{{ route('instituicao.internacoes.edit', [$internacao]) }}">
                                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                                <i class="ti-pencil-alt"></i>
                                        </button>
                                    </a>
                                @endcan
                                
                                @can('habilidade_instituicao_sessao', 'excluir_internacao')
                                    <button type="button" data-id="{{$internacao->internacao_id}}" data-internacao="{{$internacao->id}}" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Excluir"><i class="ti-trash"></i>
                                        </button>
                                @endcan

                                @if($internacao->alta_internacao == 1)
                                    @can('habilidade_instituicao_sessao', 'cancelar_alta_internacao')
                                        <button type="button" value="{{$internacao->id}}" class="btn btn-xs btn-secondary" onClick="cancelarAlta(this.value)" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Cancelar Alta"><span class="mdi mdi-account-remove"></span></button>
                                    @endcan
                                @else
                                    @can('habilidade_instituicao_sessao', 'realizar_alta_internacao')
                                        <button type="button" value="{{$internacao->id}}" class="btn btn-xs btn-secondary" onClick="verAlta(this.value)" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Realizar Alta"><span class="mdi mdi-account-check"></span></button>
                                    @endcan
                                @endif

                                @can('habilidade_instituicao_sessao', 'troca_leito_internacao')
                                    <button type="button" value="{{$internacao->id}}" class="btn btn-xs btn-secondary" onClick="verLeito(this.value)" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Transferir leito"><i class="fas fa-bed"></i></button>
                                @endcan

                                @can('habilidade_instituicao_sessao', 'troca_medico_internacao')
                                    <button type="button" value="{{$internacao->id}}" class="btn btn-xs btn-secondary" onClick="verMedico(this.value)" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Transferir Médico"><i class="fas fa-user-md"></i></button>
                                @endcan

                                @can('habilidade_instituicao_sessao', 'transferir_instituicao_internacao')
                                    <button type="button" value="{{$internacao->id}}" class="btn btn-xs btn-secondary" onClick="verInstituicao(this.value)" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Transferir instituição"><i class="mdi mdi-hospital-building"></i></button>
                                @endcan

                                @if ($profissional)
                                    @can('habilidade_instituicao_sessao', 'abrir_prontuario')
                                        <a class="btn btn-xs btn-secondary" href="{{ route('instituicao.internacoes.abrirProntuario', [$internacao]) }}" target="_blank" data-toggle="tooltip" title="" data-original-title="Atender consultório" >
                                            <span class="mdi mdi-clipboard-text"></span>
                                        </a>
                                    @endcan
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="float: right">
                {{ $internacoes->links() }}
            </div>
        </div>

        <div class="col-md" wire:ignore>
            <div class="calendar" id="calendar"></div>
        </div>

        <div id="alta"></div>
        <input type="hidden" id="events" value="{{$events}}">
    </div>
</div>

@push('scripts');

    <script src="{{ asset('material/assets/plugins/moment/moment.js') }}"></script>

    <script>
        ///Calendar        
        let calendar = new Calendar({
            id: '#calendar',
            headerColor: '#052453',
            headerBackgroundColor: '#052453',
            customWeekdayValues: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
            dateChanged: (currentDate, DateEvents) => {                
                var date = new Date(currentDate).toISOString().split('T')[0];                
                $("#data").val(date).change();
                window.livewire.emit('data', date);
                
            },
            eventsData: $.map(JSON.parse($('#events').val()), item => (
                {
                    start: item.data+'T00:00:00',
                    end: item.data+'T23:59:59',
                }
            )),
            
        })

        $(document).ready(function(){
            $("#paciente_id").select2({
                placeholder: "Pesquise por nome do paciente",
                allowClear: true,
                // minimumInputLength: 3,

                language: {
                    searching: function () {
                        return 'Buscando paciente (aguarde antes de selecionar)…';
                    },

                    inputTooShort: function (input) {
                        return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar";
                    },
                },

                ajax: {
                    url:"{{route('instituicao.contasPagar.getPacientes')}}",
                    dataType: 'json',
                    type: 'get',
                    delay: 100,

                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page || 1
                        };
                    },

                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: _.map(data.results, item => ({
                                id: Number.parseInt(item.id),
                                text: `${item.nome} ${(item.cpf) ? '- ('+item.cpf+')': ''}`,
                            })),
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                },
            });


            $('#paciente_id').on('change', function(){
                console.log($(this).val());
                window.livewire.emit('pacienteId', $(this).val());
            })
        });

        // setInterval(function() {
        //     myEvents = $.map(JSON.parse($('#events').val()), item => (
        //         {
        //             start: item.data+'T00:00:00',
        //             end: item.data+'T23:59:59',
        //         }
        //     ));
        //     calendar.setEventsData(myEvents);
        // }, 1000);

        $("#resetDate").on('click', function(){
            window.livewire.emit('data', "");
        })
        
        $('body').on('click','.btn-excluir-registro',function(e){
            var dados = $(this).attr('data-id');
            var id = $(this).attr('data-internacao');
            // console.log(dados['internacao_id'])
            // console.log(dados)
            Swal.fire({
                    title: "Excluir!",
                    text: 'Tem certeza que deseja excluir esta internação?',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: "Não!",
                    confirmButtonText: "Sim, confirmar!",
                }).then(function(result) {
                    if(dados){
                        e.stopPropagation();
                        Swal.fire({
                            title: "Excluir!",
                            text: 'Esta internação foi gerada apartir de uma pré internação, deseja excluir a pre internação original também?',
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            cancelButtonText: "Não, reativar pré internação!",
                            confirmButtonText: "Sim, confirmar!",
                        }).then(function(result) {
                            if(result.value){
                                enviarDados(id, dados, 1)
                            }else{
                                enviarDados(id, dados, 0)
                            }
                        })
                    }else{
                        enviarDados(id, dados, null)
                    }
                })
        })

        function enviarDados(id, dados, existe){
            $.ajax({
                    url: "{{ route('instituicao.internacoes.destroy', ['internacao' => 'internacao_id']) }}".replace('internacao_id', id),
                    method: "POST",
                    data: {
                        id: dados,
                        '_token': '{{csrf_token()}}',
                        '_method': 'delete',
                        'exclui_pre_internacao': existe,
                        // 'dados': dados
                    },
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

                            @this.call('render');
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

        function verAlta(id){
            
            var url = "{{ route('instituicao.internacoes.verAlta') }}";
            var data = {
                '_token': '{{csrf_token()}}',
                'id': id
            };
            var modal = 'modalRealizaAlta';
            
            $('#loading').removeClass('loading-off');
            $('#alta').load(url, data, function(resposta, status) {
                $('#' + modal).modal();
                $('#loading').addClass('loading-off');
            });

        }

        // $('#medico_id').on('change', function(){
        //     getEspecialidade();
        // })

        function cancelarAlta($id){
            
            Swal.fire({
                title: "Cancelar alta!",
                text: 'Deseja cancelar a alta desta internação?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){
                    $.ajax("{{ route('instituicao.internacoes.cancelarAlta') }}", {
                        method: "POST",
                        data: {id: $id, '_token': '{{csrf_token()}}'},
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

                                window.livewire.emit('refresh');
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
        }
        
        function verLeito(id){            
            var url = "{{ route('instituicao.internacoes.verLeito') }}";
            var data = {
                '_token': '{{csrf_token()}}',
                'id': id
            };
            var modal = 'modalTocaLeito';
            
            $('#loading').removeClass('loading-off');
            $('#alta').load(url, data, function(resposta, status) {
                $('#' + modal).modal();
                $('#loading').addClass('loading-off');
            });

        }

        function verMedico(id){
            
            var url = "{{ route('instituicao.internacoes.verMedico') }}";
            var data = {
                '_token': '{{csrf_token()}}',
                'id': id
            };
            var modal = 'modalTocaMedico';
            
            $('#loading').removeClass('loading-off');
            $('#alta').load(url, data, function(resposta, status) {
                $('#' + modal).modal();
                $('#loading').addClass('loading-off');
            });

        }

        function verInstituicao(id){
            
            var url = "{{ route('instituicao.internacoes.verInstituicao') }}";
            var data = {
                '_token': '{{csrf_token()}}',
                'id': id
            };
            var modal = 'modaltransferirInstituicao';
            
            $('#loading').removeClass('loading-off');
            $('#alta').load(url, data, function(resposta, status) {
                $('#' + modal).modal();
                $('#loading').addClass('loading-off');
            });

        }

        function getEspecialidade(){
            if($('#medico_id').val() == ''){
                $('#especialidade_id').find('option').filter(':not([value=""])').remove();
            }else{
                $('#especialidade_id').find('option').filter(':not([value=""])').remove();
                id = $('#medico_id').val();

                $.ajax({
                    url: "{{route('instituicao.internacoes.getEspecialidades')}}",
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        medico_id: id
                    },
                    success: function(retorno){
                        $('#especialidade_id').find('option').filter(':not([value=""])').remove();

                        console.log()

                        for (i = 0; i < retorno.length; i++) {
                            var selected = '';
                            // if(especialidadeId == retorno[i]['id']){
                            //     selected = "selected";
                            // }
                           $('#especialidade_id').append("<option {{ (old('especialidade_id') == "+ retorno[i]['id'] +") ? 'selected' : '' }} value = "+ retorno[i]['id'] +" "+selected+">" + retorno[i]['descricao'] + "</option>");
                        }
                    }
                })
            }

        }

        $("#unidade_id").on("change", function(){
            getLeitos()
        })

        function getLeitos(){
            $('#leito_id').find('option').filter(':not([value=""])').remove();
            id = $('#unidade_id').val();
            $.ajax({
                url: "{{route('instituicao.internacoes.getLeitos')}}",
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    unidade_id: id
                },
                beforeSend: () => {
                    
                },
                success: function(retorno){
                    for (i = 0; i < retorno.length; i++) {
                        var selected = '';
                        $('#leito_id').append("<option value = "+ retorno[i]['id'] +">" + retorno[i]['descricao'] + "</option>");
                    }
                    
                }
            })
        }
    </script>
@endpush