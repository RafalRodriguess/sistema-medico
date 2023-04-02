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

            .formGetAgendamentos{
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
            <h3 class="text-themecolor m-b-0 m-t-0">Atendimentos</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Relatorio</a></li>
                <li class="breadcrumb-item active">Atendimentos</li>
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
                    <form action="javascript:void(0)" class="formGetAgendamentos" id="formGetAgendamentos">
                        @csrf
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Cod agendamento</label>
                                    <input type="text" class="form-control" name="cod_agendamentos" id="cod_agendamentos" placeholder="Ex: 425,451,452,453">
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
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-info waves-effect waves-light m-r-10">Pesquisar</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive print-table mb-2">
                        <form action="{{route('instituicao.agendamentosProcedimento.salvar')}}" method="post">
                            
                            @method('post')
                            @csrf
                            <div class="tabela"></div>
                            <div class="form-group text-right salvar-button" style="display: none">
                               <button type="submit" class="btn btn-success waves-effect waves-light m-r-10 " ><i class="mdi mdi-check"></i> Salvar</button>
                            </div>
                        </form>
                    </div>

                    
                </div>
            </div>
            <!-- Column -->

            <!-- Column -->

        </div>
    </div>

@endsection

@push('scripts')
    <script>
       
        $(document).ready(function() {
            // $(document).on('keypress', 'input.only-number', function(e) {
            //     var $this = $(this);
            //     var key = (window.event)?event.keyCode:e.which;
            //     var dataAcceptDot = $this.data('accept-dot');
            //     var dataAcceptComma = $this.data('accept-comma');
            //     var acceptDot = (typeof dataAcceptDot !== 'undefined' && (dataAcceptDot == true || dataAcceptDot == 1)?true:false);
            //     var acceptComma = (typeof dataAcceptComma !== 'undefined' && (dataAcceptComma == true || dataAcceptComma == 1)?true:false);

            //         if((key > 47 && key < 58)
            //     || (key == 46 && acceptDot)
            //     || (key == 44 && acceptComma)) {
            //         return true;
            //     } else {
            //             return (key == 8 || key == 0)?true:false;
            //         }
            // });
        })

        $("#cod_agendamentos").on('keypress', function(e){
            var key = (window.event)?event.keyCode:e.which;
            if((key > 47 && key < 58)
            || (key == 44)) {
                return true;
            }else{
                return (key == 8 || key == 0)?true:false;
            }
        })

        $('#formGetAgendamentos').on('submit', function(e){
            e.preventDefault()
            var formData = new FormData($(this)[0]);
            $.ajax("{{route('instituicao.agendamentosProcedimento.tabela')}}", {
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
                    $(".salvar-button").css('display', 'block');
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')
                }
            })
        })
    </script>
@endpush
