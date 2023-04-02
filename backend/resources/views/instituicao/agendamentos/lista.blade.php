@extends('instituicao.layout')


@push('scripts')
    <!-- jQuery peity -->
    <script src="{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.jquery.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw-init.js') }}"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="{{ asset('material/assets/plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>
@endpush

@push('estilos')
    <link href="{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.css') }}" rel="stylesheet">
    <link href="{{ asset('material/assets/plugins/calendar/dist/fullcalendar.css') }}" rel="stylesheet">
@endpush

@section('conteudo')

                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                        <div class="col-md-5 col-8 align-self-center">
                            <h3 class="text-themecolor m-b-0 m-t-0">Agendamentos</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Agendamentos</a></li>
                            </ol>
                        </div>

                    </div>
                    <!-- ============================================================== -->
                    <!-- End Bread crumb and right sidebar toggle -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Start Page Content -->
                    <!-- ============================================================== -->
                    <div class="row">
                        <div class="col-12 list-pesquisa">
                            
                            <!-- Column -->
                            <div class="card">
                                <ul class="nav nav-tabs customtab" role="tablist">
                                    <li class="nav-item"> <a class="nav-link show active" data-toggle="tab" href="#tab1" role="tab" ><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Diária</span></a> </li>
                                    <li class="nav-item" id="semanal-click"> <a class="nav-link show " data-toggle="tab" href="#tab3" role="tab" ><span class="hidden-sm-up"><i class="ti-list"></i></span> <span class="hidden-xs-down">Semanal</span></a> </li>
                                    <li class="nav-item" id="reg-click"> <a class="nav-link show " data-toggle="tab" href="#tab2" role="tab" ><span class="hidden-sm-up"><i class="ti-list"></i></span> <span class="hidden-xs-down">Registros</span></a> </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane show active" id="tab1" role="tabpanel">
                                        @php $startAgenda = microtime(true); @endphp
                                        @livewire('instituicao.agendamentos-novo-pesquisa')
                                        @php $endAgenda = microtime(true); @endphp
                                    </div>
                                    <div class="tab-pane p-20 show" id="tab2" role="tabpanel">
                                        @php $startReg = microtime(true); @endphp
                                        <div id="registro-pesquisa"></div>
                                        @php $endReg = microtime(true); @endphp                                        
                                    </div>
                                    <div class="tab-pane p-20 show" id="tab3" role="tabpanel">
                                        @php $startSemanal = microtime(true); @endphp
                                        <div id="agenda-semanal"></div>
                                        @php $endSemanal = microtime(true); @endphp                                        
                                    </div>
                                </div>
                            </div>

                            {{-- @php
                                dump($endAgenda - $startAgenda);
                                dump($endReg - $startReg);
                            @endphp --}}

                            <div id="toobar-pesquisa"></div>
                            {{-- @livewire('instituicao.agendamentos-toolbar-pesquisa') --}}
                            <!-- Column -->

                            <!-- Column -->

                        </div>
                    </div>

                    <div class="modal inmodal no_print" id="modalInserirAgenda" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 1200px;">
                            {{-- <div class="modal-content" style="background: #f8fafb;padding: 20px 30px 30px 30px"> --}}
                            <div class="modal-content" style="background: #f8fafb;"></div>
                        </div>
                    </div>

                    <div class="modal inmodal no_print" id="modalDescricao" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 1200px;">
                            {{-- OCULTO POR KENNEDY <div class="modal-content" style="background: #f8fafb;padding: 20px 30px 30px 30px"> --}}
                            <div class="modal-content" style="background: #f8fafb;"></div>
                        </div>
                    </div>
                
                    <div class="modal inmodal no_print" id="modalPacotesProcedimentos" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                            {{-- <div class="modal-content" style="background: #f8fafb;padding: 20px 30px 30px 30px"> --}}
                            <div class="modal-content" style="background: #f8fafb;"></div>
                        </div>
                    </div>
                
                    <div class="modal inmodal no_print" id="modalEmitirNota" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 1200px;">
                            {{-- OCULTO POR KENNEDY <div class="modal-content" style="background: #f8fafb;padding: 20px 30px 30px 30px"> --}}
                            <div class="modal-content" style="background: #f8fafb;"></div>
                        </div>
                    </div>

                    <div wire:ignore class="modal inmodal no_print" id="modalChangePrestadorSemanal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                
                                <div class="modal-body" style="text-align: center;">
                                    <h2>Selecione um prestador</h2>
            
                                    <select id="prestador_semanal_change" style="width: 100%; margin-bottom: 10px;" class="form-control select2">
                                        <option value=""></option>
                                        @foreach ($profissionaisHome as $item)
                                            <optgroup label="{{ $item->descricao }}">
                                                @foreach ($item->prestadoresInstituicao as $prestadoresInstituicao)
                                                    {{-- @if ($prestadoresInstituicao->ativo == 1) --}}
                                                        <option value="{{ $prestadoresInstituicao->id }}">{{ $prestadoresInstituicao->prestador->nome }}</option>
                                                    {{-- @endif --}}
                                                @endforeach
                                            </optgroup>
            
                                        @endforeach
                                    </select>
                                    <div class="form-group text-right pb-2" style="margin-top: 10px;">
                                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10" data-dismiss="modal"><i class="mdi mdi-arrow-left-bold"></i> Cancelar</button>
                                        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 alterar_prestador_semanal"><i class="mdi mdi-check"></i> Alterar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div wire:ignore id="modalTermoFolhaSala" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    Escolha um modelo
                                </div>
                                <div class="modal-body">
                                    <select name="modelo_termo_folha_sala" id="modelo_termo_folha_sala" class="select2" style="width: 100%">
                                        <option value="">Selecione</option>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal" >Fechar</button>
                                    <a class="imprimirTermoFolhaSala" href="#" target="_blank">
                                        <button type="button" class="btn btn-success waves-effect imprimirButtonTermoFolha">Imprimir</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->


@endsection

@push('scripts')
    <script>
        
        $("#reg-click").on('click', function(){
            if($('#reg-click').hasClass('carregado')){
                return;
            }else{
                callRenderRegistro('')
            }            
        })

        function callChangePesquisaRegistro(){
            var pesquisa = $("#registro-pesquisa").find("#pesquisa").val()
            callRenderRegistro(pesquisa);
        }

        function callRenderRegistro(pesquisa){
            $("#registro-pesquisa").html('');
            $('#reg-click').addClass('carregado')           
            $.ajax("{{route('instituicao.agendamentos.getRegistroPesquisa')}}", {
                method: "GET",
                data: {
                    "_token": "{{csrf_token()}}",
                    'pesquisa': pesquisa
                },
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function (result) {   
                    // console.log(result);                 
                    $("#registro-pesquisa").html(result);
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')

                }

            });
        }


        /////INICIAR AGENDA SEMANAL
        $("#semanal-click").on('click', function(){
            if($('#semanal-click').hasClass('carregado')){
                return;
            }else{
                var data = $(".tab-content").find("#data").val()
                // console.log(data)
                var prestador = $(".tab-content").find("#prestador option:selected").val()
                // console.log(prestador)
                if(prestador == ""){
                    Swal.fire({
                        title: "Prestador!",
                        text: 'Selecione um prestador',
                        icon: "warning",
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ok!",
                    })
                }else{
                    callRenderInicioSemanal(data, prestador)
                }
            }            
        })

        function callRenderInicioSemanal(data, prestador){
            $("#agenda-semanal").html('');
            $('#semanal-click').addClass('carregado')           
            $.ajax("{{route('instituicao.agendamentos.getAgendaSemanal')}}", {
                method: "POST",
                data: {
                    "_token": "{{csrf_token()}}",
                    'prestador': prestador,
                    'data': data,
                },
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function (result) {   
                    // console.log(result);                 
                    $("#agenda-semanal").html(result);
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')

                }

            });
        }

        function getDataAtendimentosInicio(){
            if($('#prestador option:selected').val()){
                $.ajax("{{ route('instituicao.agendamentos.getDiasAtendimentoPrestador') }}", {
                    method: "POST",
                    data: {
                        'prestador_id': $('#prestador option:selected').val(),
                        '_token': '{{csrf_token()}}'
                    },
                    success: function (response) {
                        // console.log(response);
                        SelectedDates = {};
                        for (let index = 0; index < response.length; index++) {
                            const element = response[index];
                            SelectedDates[new Date(element)] = new Date(element);
                        }
                        // var SelectedDates = {};
                        // SelectedDates[new Date('2022-08-18 00:00:00')] = new Date('2022-08-18 00:00:00');
                        // SelectedDates[new Date('2022-09-17 00:00:00')] = new Date('2022-09-17 00:00:00');
                    }
                })
            }
        }

        $(".alterar_prestador_semanal").on('click', function(){
            var $prestador = $("#prestador_semanal_change option:selected").val()
            $("#modalChangePrestadorSemanal").modal('hide')                 
            if($prestador == ""){
                Swal.fire({
                    title: "Error!",
                    text: 'Selecione um prestador',
                    icon: "error",
                })
            }else{
                $("#prestador").val($prestador).change();          
                callRenderPageSemanal($prestador)    
                callRenderInicioSemanal($(".tab-content").find("#data").val(),$prestador)
                getDataAtendimentosInicio()
            }
        })

        function geraModalModeloTermo(tipo){
            $.ajax("{{route('instituicao.modelosTermoFolhaSala.getModelos')}}", {
                method: "GET",
                data: {
                    tipo: tipo,
                    "_token": "{{csrf_token()}}"
                },
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function (result) {            
                    $("#modalDescricao").modal('hide');
                    $("#modelo_termo_folha_sala").find('option').filter(':not([value=""])').remove();    
                    $.each(result, function (key, value) {
                            // $('<option').val(value.id).text(value.Nome).appendTo(options);
                        $("#modelo_termo_folha_sala").append('<option value='+value.id+'>'+value.nome+'</option>')
                        //options += '<option value="' + key + '">' + value + '</option>';
                    });
                    $("#modalTermoFolhaSala").modal('show');
                },
                complete: () => {
                    
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')
                }
            })
        }

        $("#modelo_termo_folha_sala").on('change', function(e){
            var modelo_id = $("#modelo_termo_folha_sala").val();
            console.log(modelo_id)
            var url = "{{route('instituicao.modelosTermoFolhaSala.imprmirModelo', ['modelo' => 'modelo_id'])}}".replace('modelo_id', modelo_id);
            $(".imprimirTermoFolhaSala").attr('href', url);
        })

        $(".imprimirButtonTermoFolha").on('click', function(){
            $("#modalTermoFolhaSala").modal('hide');
        })

    </script>

@endpush
