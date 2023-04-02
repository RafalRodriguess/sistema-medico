@extends('instituicao.layout')

@push('estilos')
    <style>
        
    </style>
@endpush

@section('conteudo')

    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Contas pagas</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Relatorio</a></li>
                <li class="breadcrumb-item active">Contas pagas</li>
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
                                    <label class="form-label">Data de: </label>
                                    <select class="form-control" name="tipo_pesquisa" id="tipo_pesquisa">
                                        <option value="data_vencimento">Vencimento</option>
                                        <option value="data_pago">Pagamento</option>
                                        <option value="data_compensacao">Compensação</option>
                                        <option value="created_at">Emissão</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Data inicial</label>
                                    <input type="date" id="data_inicio" name="data_inicio" class="form-control" value="{{date('Y-m-d', strtotime('-1 month'))}}">
                                    
                                    
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Data final</label>
                                    <input type="date" id="data_fim" name="data_fim" class="form-control" value="{{date('Y-m-d')}}">
                                    
                                    
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Conta caixa</label>
                                    <select class="form-control select2" style="width: 100%" name="conta_id" id="conta_id">
                                        <option value="">Todas</option>
                                        @foreach ($contas as $item)
                                            <option value="{{$item->id}}">{{$item->descricao}}</option>
                                        @endforeach
                                    </select>
                                    
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Formas de pagamento</label>
                                    <select name="formaPagamento" class="form-control selectfild2" style="width: 100%">
                                    <option value="">Todas Formas pagamento</option>
                                        @foreach ($formaPagamentos as $formaPagamento)
                                            <option value="{{ $formaPagamento }}">
                                                {{ App\ContaPagar::forma_pagamento_texto($formaPagamento) }}
                                            </option>
                                        @endforeach
                                    </select>
        
        
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Planos de conta</label>
                                    <select name="plano_conta_caixa_id" class="form-control selectfild2" style="width: 100%">
                                        <option value="">Todos Planos de Conta</option>
                                        @foreach ($planosConta as $item)
                                            <option value="{{$item->id}}">{{$item->codigo}} - {{$item->descricao}}</option>
                                        @endforeach
                                    </select>
        
        
                                </div>
                            </div>
                            
                            <!-- <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <select name="status" id="status" class="form-control selectfild2" style="width: 100%">
                                        <option value="">Todas</option>
                                        <option value="a_vencer">A vencer</option>
                                        <option value="vencidas">Vencidas</option>
                                    </select>
                                </div>
                            </div> -->
                            <div class="col-md-8"></div>
                            <div class="col-md-8"></div>
                            <div class="col-md-2">
                                <div class="form-group imprimir" style="margin-top: 30px !important; float: right; width: 100%; display: none">
                                    <button type="button" class="btn btn-success btn-circle" onclick="imprimir()" data-toggle="tooltip" title="" data-original-title="Imprimir">
                                        <i class="mdi mdi-printer"></i>
                                    </button>

                                    <button type="button" class="btn btn-info btn-circle" onclick="excel()" data-toggle="tooltip" title="" data-original-title="Excel">
                                        <i class="mdi mdi-file-excel"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group" style="margin-top: 30px !important; float: right; width: 100%">
                                    <button type="submit" class="btn waves-effect waves-light btn-block btn-info">Pesquisar</button>
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
        function imprimir(){
            var formData = $('#formRelatorio').serialize();

            url = "{{route('instituicao.relatoriosFinanceiros.exportRelatorios', ['relatorio' => 'pagas', 'tipo' => 'pdf'])}}"+"/?"+formData;

            // console.log(url);

            window.open(url, '_blank')
        }

        function excel(){
            var formData = $('#formRelatorio').serialize();

            url = "{{route('instituicao.relatoriosFinanceiros.exportRelatorios', ['relatorio' => 'pagas', 'tipo' => 'xlsx'])}}"+"/?"+formData;

            // console.log(url);

            window.open(url, '_blank')
        }       

        $(document).ready(function() {

        })

        $('#formRelatorio').on('submit', function(e){
            e.preventDefault()
            var formData = new FormData($(this)[0]);
            
            $.ajax("{{route('instituicao.relatoriosFinanceiros.pagasTabela')}}", {
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
                }
            })
        })

        function ativarClass(){
            $(".tabela").find('#demo-foo-row-toggler').footable()
        }
    </script>
@endpush