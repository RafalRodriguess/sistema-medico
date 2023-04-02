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
        /* @media print {
            body * {
                visibility: hidden;
            }
            .print-table, .print-table * {
                visibility: visible;
            }

            .print-table {
                position: absolute;
                left: 0;
                top: 0;
            }

            .left-sidebar{
                display: none,
            }

            .topbar{
                display: none;
            }

        } */

        @media print {
           * {
                background: transparent;
                color: #000;
                text-shadow: none; 
                filter: none;
                -ms-filter: none;
            }
            body * {
                visibility: hidden;
            }

            .print-table, .print-table * {
                visibility: visible;
                
            }
            .print-table {
                position: absolute;
                width: 100%;
                top: 0;
                left: 0;
                
                /* display: block; */
            }

            .no_print { 
                display: none !important;
            }

            .quebraPagina{
                page-break-before: always;
            }

            .left-sidebar{
                display: none,
            }

            .topbar{
                display: none;
            }

            .formRelatorioAuditoriaAgendamentos{
                display: none;
            }

            /*  */
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
            <h3 class="text-themecolor m-b-0 m-t-0">Auditoria Agendamentos</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Relatório</a></li>
                <li class="breadcrumb-item active">Auditoria Agendamentos</li>
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
                    <form action="javascript:void(0)" class="formRelatorioAuditoriaAgendamentos" id="formRelatorioAuditoriaAgendamentos">
                        @csrf
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Pesquisar por:</label>
                                    <select class="form-control" style="width: 100%" name="tipo" id="tipo" >
                                        <option value="data_auditoria">Data auditoria</option>
                                        <option value="data_agendamento">Data agendamento</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Data inicial</label>
                                            <input type="date" id="data_inicio" name="data_inicio" class="form-control" value="{{date('Y-m-d')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Data final</label>
                                            <input type="date" id="data_fim" name="data_fim" class="form-control" value="{{date('Y-m-d')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <select class="form-control select2" style="width: 100%" name="status[]" id="status" multiple>
                                        @foreach ($status as $item)
                                            <option value="{{$item}}" selected>
                                                @if ($item == "em_atendimento" || $item == 'finalizado_medico')
                                                    @if ($item == "em_atendimento")
                                                        em atendimento
                                                    @else
                                                        finalizado medico
                                                    @endif    
                                                @else
                                                    {{$item}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <span style="cursor: pointer" onclick="limpa_filtros('status')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Limpar filtros"><i class="fa fa-trash"></i> </span>
                                    <span style="cursor: pointer" onclick="seleciona_filtros('status')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Selecionar todos os filtros"><i class="fa fa-reply-all"></i> </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Usuarios <span><i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Vazio busca todos os usuarios"></i></span></label>
                                    <select class="form-control select2" style="width: 100%" name="usuarios[]" id="usuarios" multiple>
                                        @foreach ($usuarios as $item)
                                            <option value="{{$item->id}}">{{$item->nome}}</option>
                                        @endforeach
                                    </select>
                                    <span style="cursor: pointer" onclick="limpa_filtros('usuarios')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Limpar filtros"><i class="fa fa-trash"></i> </span>
                                    <span style="cursor: pointer" onclick="seleciona_filtros('usuarios')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Selecionar todos os filtros"><i class="fa fa-reply-all"></i> </span>
                                </div>
                            </div>

                            {{-- <div class="col-md-8"></div> --}}
                            {{-- <div class="col-md-8"></div> --}}
                            {{-- <div class="col-md-2">
                                <div class="form-group imprimir" style="margin-top: 30px !important; float: right; width: 100%; display: none">
                                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="imprimir()">Imprimir</button>
                                </div>
                            </div> --}}
                            <div class="col-md-12 acao">
                                <div class="form-groupn text-right">
                                    <button type="submit" class="btn btn-info waves-effect waves-light m-r-10">Pesquisar</button>
                                </div>
                                
                                {{-- <div class="col-md-2 imprimir" style="margin-top: 30px !important; float: right; width: 100%; display: none">
                                    <div class="form-group">
                                        <button type="button" class="btn waves-effect waves-light btn-block btn-success" onclick="imprimir()">Imprimir</button>
                                    </div>
                                </div> --}}

                            </div>
                        </div>
                    </form>

                    <div class="table-responsive print-table mb-2">
                        <div class="cabecalho" style="display: none;">
                            <div class="col-md-12 row align-items-center">
                                <img class="light-logo col-sm-2" src="@if ($instituicao->imagem){{ \Storage::cloud()->url($instituicao->imagem) }} @endif" alt="" style="height: 100px;"/>
                                <h3 class='lead col-sm-8'>{{$instituicao->nome}}</h3>
                                <label class="col-sm-2">{{date("d/m/Y H:i:s")}}</label>
                                <small class="text-muted col-sm-12 text-center"><b>endereço:</b> {{$instituicao->rua}} <b>Nº:</b> {{$instituicao->numero}} {{$instituicao->complemento}} <b>Bairro:</b> {{$instituicao->bairro}} <b>Cidade:</b> {{$instituicao->cidade}} <b>UF:</b> {{$instituicao->estado}}</small>
                            </div>

                            <h3 class="mt-3"><center>Relatório de demonstrativo odontológico <span class='texto_titulo'></span></center></h3>

                            <hr class="hr-line-dashed">
                        </div>

                        <div class="tabela"></div>
                    </div>

                    <div id="modalFinanceiro"></div>
                </div>
            </div>
            <!-- Column -->
            
            <!-- Column -->
            
        </div>
    </div>                    
                     
@endsection

@push('scripts')
    <script>

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
            
            
        })

        $('#formRelatorioAuditoriaAgendamentos').on('submit', function(e){
            e.preventDefault()
            var formData = new FormData($(this)[0]);
            $.ajax("{{route('instituicao.relatorioAuditoriaAgendamentos.tabela')}}", {
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function (result) {
                    $(".tabela").html(result);
                    setTimeout(function(){ 
                        $(".imprimir").css('display', 'block')
                    }, 1000);

                    $(".imprimir").css('display', 'block')
                    ativarClass();
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader') 
                }
            })
        })

        function ativarClass(){
            // $(".table-responsive").find('.accordion').find('#demo-foo-row-toggler').footable()
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
@endpush