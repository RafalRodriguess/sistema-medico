@extends('instituicao.layout')

@push('estilos')
    <style>
        .table-scroll {
            position:relative;
            max-width:100%;
            margin:auto;
            overflow:hidden;
            /* border:1px solid #000; */
        }
        .table-wrap {
            width:100%;
            overflow:auto;
        }
        .table-scroll table {
            width:100%;
            margin:auto;
            /* border-collapse:separate; */
            /* border-spacing:0; */
        }
        .table-scroll th, .table-scroll td {
            padding:5px 10px;
            /* border:1px solid #000; */
            background:#fff;
            white-space:nowrap;
            vertical-align:top;
        }
        .table-scroll thead, .table-scroll tfoot {
            background:#f9f9f9;
        }
        .fixed-table {
            position:absolute;
            top:0;
            left:0;
            pointer-events:none;
        }
        .fixed-table th, .fixed-table td {
            visibility:hidden
        }
        .fixed-table td, .fixed-table th {
            border-color:transparent
        }
        .fixed-table tbody th {
            visibility:visible;
            color:blue;
        }
        .fixed-table .fixed-side {
            /* border:1px solid #000; */
            background:#eee;
            visibility:visible;
        }
        .fixed-table thead, .fixed-table tfoot {
            background:transparent;
        }
    </style>
@endpush

@section('conteudo')
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Fluxo Caixa</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Relatorio</a></li>
                <li class="breadcrumb-item active">Fluxo Caixa</li>
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
                    <form action="javascript:void(0)" id="formRelatorio">
                        @csrf
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Data inicial</label>
                                    <input type="date" id="data_inicio" name="data_inicio" class="form-control" onchange="getTabela()" value="{{date('Y-m-d')}}">                                    
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Data final</label>
                                    <input type="date" id="data_fim" name="data_fim" class="form-control" onchange="getTabela()" value="{{date('Y-m-d')}}">
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Conta Caixa</label>
                                    <select class="form-control select2" style="width: 100%" name="contas[]" id="contas" multiple required>
                                        @foreach ($contas as $item)
                                            <option value="{{$item->id}}" selected>{{$item->descricao}}</option>
                                        @endforeach
                                    </select>
                                    <span style="cursor: pointer" onclick="limpa_filtros('contas')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Limpar filtros"><i class="fa fa-trash"></i> </span>
                                    <span style="cursor: pointer" onclick="seleciona_filtros('contas')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Selecionar todos os filtros"><i class="fa fa-reply-all"></i> </span>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group" style="margin-top: 30px !important; float: right; width: 100%">
                                    <input type="radio" class="btn-check tipo" name="tipo" id="diario" checked value="diario">
                                    <label class="btn" for="diario">Diario</label>

                                    <input type="radio" class="btn-check tipo" name="tipo" id="mensal" value="mensal">
                                    <label class="btn" for="mensal">Mensal</label>
                                </div>
                            </div>
                        </div>
                    </form>
   
                    <div class="print-table">
                        <div class="tabela"></div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>                    
                     
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            getTabela();
        })

        $("#contas").on('change', function(){
            getTabela();
        })
        
        $("[name=tipo]").on('change', function(){
            getTabela();
        })

        function getTabela(){
            var data_inicio = new Date($("#data_inicio").val());
            var data_fim = new Date($("#data_fim").val());

            var dataDifDias = Math.floor((data_fim.getTime() - data_inicio.getTime())/(24*3600*1000))
            var dataDifMeses = (data_fim.getMonth()+12*data_fim.getFullYear())-(data_inicio.getMonth()+12*data_inicio.getFullYear())

            console.log(dataDifDias, dataDifMeses, $("[name=tipo]").val());

            if(($("#diario").is(":checked") && !(dataDifDias >= 0 && dataDifDias <= 31))){
                $.toast({
                        
                        heading: 'Error',
                        text: 'intervavo não pode ser maior que 30 dias nem menor que 0',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 3000,
                        stack: 10
                    
                    })
            }else if($("#mensal").is(":checked") && !(dataDifMeses >= 0 && dataDifMeses <= 12)){
                $.toast({
                        
                        heading: 'Error',
                        text: 'Intervalo não pode ser maior que 12 meses nem menor que 0',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 3000,
                        stack: 10
                    
                    })
            }else{
                var formData = new FormData($('#formRelatorio')[0]);
                if($("#contas").val() != ""){
                    $.ajax("{{route('instituicao.relatoriosFinanceiros.fluxoCaixatabela')}}", {
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: () => {
                            $('#loading').removeClass('loading-off');
                        },
                        success: function (result) {
                            $(".tabela").html(result);
                            
                            const table = document.querySelector('.main-table');
        
                            let clone = table.cloneNode(true);
                            clone.className += " fixed-table";
        
                            let body = document.getElementById('table-scroll');
        
                            body.appendChild(clone);
                        },
                        complete: () => {
                            $('#loading').addClass('loading-off');
                        }
                    })
                }else{
                    $.toast({
                        
                        heading: 'Error',
                        text: 'Selecione uma conta caixa',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 3000,
                        stack: 10
                    
                    })
                }
            }

            
        }
        

        function limpa_filtros(elemento){
            $("#"+elemento).find("option").attr("selected", false);
            $("#"+elemento).val('').trigger('change');
        }

        function seleciona_filtros(elemento){
            $("#"+elemento).val([]);
            var dados = [];
            $("#"+elemento).find("option").each(function(index, elem){
                $(elem).attr("selected", true);
                dados.push($(elem).val())
            })
            $("#"+elemento).val(dados)
            $("#"+elemento).trigger('change');

        }
    </script>
@endpush