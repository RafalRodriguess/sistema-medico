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
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .print-table, .print-table * {
                visibility: visible;
                -webkit-print-color-adjust: exact;
            }
            .print-table {
                position: fixed;
                left: 0;
                top: 0;
            }
        }

        .select2-selection {
            height: 50px !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            height: 50px!important;
        }

        .select2-selection__choice {
            font-size: 14px;
            margin: 2px !important;
            color: black;
        }
    </style>
@endpush

@section('conteudo')

    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Conclusão</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Relatório</a></li>
                <li class="breadcrumb-item active">Conclusão</li>
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
        <div class="col-12">
            <!-- Column -->
            <div class="card">
                <div class="card-body">
                    <form action="javascript:void(0)" id="formRelatorioConclusao">
                        @csrf
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Data inicial</label>
                                    <input type="date" id="data_inicio" name="data_inicio" class="form-control" value="{{date('Y-m-d')}}">
                                    @if($errors->has('data_inicio'))
                                        <small class="form-control-feedback">{{ $errors->first('data_inicio') }}</small>
                                    @endif
                                    
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Data final</label>
                                    <input type="date" id="data_fim" name="data_fim" class="form-control" value="{{date('Y-m-d')}}">
                                    @if($errors->has('data_fim'))
                                        <small class="form-control-feedback">{{ $errors->first('data_fim') }}</small>
                                    @endif
                                    
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Paciente</label>
                                    <select class="form-control select2agenda" style="width: 100%" name="paciente_id" id="paciente_id">
                                        <option value="">Selecione</option>
                                    </select>
                                    <span style="cursor: pointer" onclick="limpa_filtros('paciente_id')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Limpar filtros"><i class="fa fa-trash"></i> </span>
                                    <span style="cursor: pointer" onclick="seleciona_filtros('paciente_id')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Selecionar todos os filtros"><i class="fa fa-reply-all"></i> </span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Profissionais</label>
                                    <select class="form-control select2" style="width: 100%" name="usuario_id[]" id="usuario_id" multiple>
                                        @foreach ($profissionais as $item)
                                            @if ($item->prestadoresInstituicoes[0]->instituicao_usuario_id)
                                                <option value="{{$item->prestadoresInstituicoes[0]->instituicao_usuario_id}}" selected>{{$item->nome}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span style="cursor: pointer" onclick="limpa_filtros('usuario_id')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Limpar filtros"><i class="fa fa-trash"></i> </span>
                                    <span style="cursor: pointer" onclick="seleciona_filtros('usuario_id')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Selecionar todos os filtros"><i class="fa fa-reply-all"></i> </span>
                                </div>
                                @if($errors->has('usuario_id'))
                                    <small class="form-control-feedback">{{ $errors->first('usuario_id') }}</small>
                                @endif
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Motivos</label>
                                    <select class="form-control select2" style="width: 100%" name="motivo_conclusao_id[]" id="motivo_conclusao_id" multiple>
                                        @foreach ($motivos as $item)
                                            <option value="{{$item->id}}" selected>{{$item->descricao}}</option>
                                        @endforeach
                                    </select>
                                    <span style="cursor: pointer" onclick="limpa_filtros('motivo_conclusao_id')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Limpar filtros"><i class="fa fa-trash"></i> </span>
                                    <span style="cursor: pointer" onclick="seleciona_filtros('motivo_conclusao_id')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Selecionar todos os filtros"><i class="fa fa-reply-all"></i> </span>
                                </div>
                                @if($errors->has('motivo_conclusao_id'))
                                    <small class="form-control-feedback">{{ $errors->first('motivo_conclusao_id') }}</small>
                                @endif
                            </div>
                            
                            <div class="col-md-8"></div>
                            <div class="col-md-8"></div>
                            <div class="col-md-2">
                                {{-- <div class="form-group imprimir" style="margin-top: 30px !important; float: right; width: 100%; display: none">
                                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="imprimir()">Imprimir</button>
                                </div> --}}
                            </div>
                            <div class="col-md-2">
                                <div class="form-group" style="margin-top: 30px !important; float: right; width: 100%">
                                    <button type="submit" class="btn waves-effect waves-light btn-block btn-info">Pesquisar</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive print-table">
                        <div class="cabecalho" style="display: none;">
                            <div class="col-md-12 row align-items-center">
                                <img class="light-logo col-sm-2" src="@if ($instituicao->imagem){{ \Storage::cloud()->url($instituicao->imagem) }} @endif" alt="" style="height: 100px;"/>
                                <h3 class='lead col-sm-8'>{{$instituicao->nome}}</h3>
                                <label class="col-sm-2">{{date("d/m/Y H:i:s")}}</label>
                                <small class="text-muted col-sm-12 text-center"><b>endereço:</b> {{$instituicao->rua}} <b>Nº:</b> {{$instituicao->numero}} {{$instituicao->complemento}} <b>Bairro:</b> {{$instituicao->bairro}} <b>Cidade:</b> {{$instituicao->cidade}} <b>UF:</b> {{$instituicao->estado}}</small>
                            </div>

                            <h3 class="mt-2"><center>Relatório Conclusão <span class='texto_titulo'></span></center></h3>

                            <hr class="hr-line-dashed">
                        </div>

                        <div class="tabela"></div>
                    </div>
                </div>
            </div>
            <!-- Column -->
            
            <!-- Column -->
            
        </div>
    </div>      
    
    <div class="visualizar_conclusao"></div>
                     
@endsection

@push('scripts')
    <script>

        $(".tabela").on('click', '.visualizar-conclusao', function(){
            var paciente_id = $(this).attr('data-paciente');
            var agendamento_id = $(this).attr('data-agendamento');
            var conclusao_id = $(this).attr('data-conclusao');

            $.ajax({
                url: "{{route('agendamentos.relatorioConclusao.paciente.conclusao', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id', 'conclusao' => 'conclusao_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id).replace('conclusao_id', conclusao_id),
                type: 'POST',
                data: {'_token': '{{ csrf_token() }}'},
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function(result) {
                    $(".visualizar_conclusao").html('');
                    $(".visualizar_conclusao").html(result);
                    $(".visualizar_conclusao").find('#modalConclusaoResumo').modal('show')
                    $('.loading').css('display', 'none');
                },
                complete: () => {
                    $('.loading').find('.class-loading').removeClass('loader') 
                }

            });
        });

        function imprimir(){
            $(".imprimir").attr('disabled', true);
            $(".texto_titulo").text($("#tipo_relatorio").val());
            $(".cabecalho").css("display", "block");
            window.print();
            $(".cabecalho").css("display", "none");
            setTimeout(function(){ 
                $(".imprimir").attr('disabled', false)
            }, 1000);
            
        }

        $(document).ready(function() {
            $(".select2agenda").select2({
                placeholder: "Pesquise por nome ou cpf",
                allowClear: true,
                minimumInputLength: 3,
                tags: true,
                language: {
                searching: function () {
                    return 'Buscando paciente (aguarde antes de selecionar)…';
                },
                
                inputTooShort: function (input) {
                    return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar"; 
                },
                },    
                
                ajax: {
                    url:"{{route('instituicao.agendamentos.getPacientes')}}",
                    dataType: 'json',
                    delay: 100,

                    data: function (params) {
                    return {
                        q: params.term || '', // search term
                        page: params.page || 1
                    };
                    },
                    processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: _.map(data.results, item => ({
                            id: item.id,
                            text: `${item.nome} ${(item.cpf) ? '- ('+item.cpf+')': ''}`,
                        })),
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                    },
                    cache: true
                },

            })
        })

        $('#formRelatorioConclusao').on('submit', function(e){
            e.preventDefault()
            var formData = new FormData($(this)[0]);
            
            $.ajax("{{route('instituicao.relatorioConclusao.tabela')}}", {
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: () => {
                    $('#loading').removeClass('loading-off');
                },
                success: function (result) {
                    $('#loading').addClass('loading-off');
                    $(".tabela").html(result);
                    $(".imprimir").css('display', 'block')
                    // ativarClass();
                },
                complete: () => {
                    $('#loading').addClass('loading-off');
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
        })

        function ativarClass(){
            $(".tabela").find('#demo-foo-row-toggler').footable()
        }

        function limpa_filtros(elemento){
            $("#"+elemento).find("option").attr("selected", false);
            $("#"+elemento).val('').trigger('change');
        }

        function seleciona_filtros(elemento){
            if(elemento == "procedimentos"){
                $("#"+elemento).val([]);
                var dados = [];
                dados.push("todos")
                $("#"+elemento).val(dados)
                $("#"+elemento).trigger('change');
            }else{
                $("#"+elemento).val([]);
                var dados = [];
                $("#"+elemento).find("option").each(function(index, elem){
                    $(elem).attr("selected", true);
                    dados.push($(elem).val())
                })
                $("#"+elemento).val(dados)
                $("#"+elemento).trigger('change');
            }
        }

    </script>

    <script language=javascript type="text/javascript">
        function newPopup(url){
            window.open(url, 'prontuário', 'width=1024, height=860')
        }
    </script>
@endpush