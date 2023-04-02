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

            .formRelatorioAtendimento{
                display: none;
            }
        }
    </style>
@endpush

@section('conteudo')

    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Demonstrativo Financeiro</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Relatorio</a></li>
                <li class="breadcrumb-item active">Demonstrativo Financeiro</li>
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
                    <form action="javascript:void(0)" id="formDemonstrativoFinanceiro">
                        @csrf
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Data de: </label>
                                    <select class="form-control" name="tipo_pesquisa" id="tipo_pesquisa">
                                        <option value="data_vencimento">Vencimento</option>
                                        <option value="data_pago">Pagamento</option>
                                        <option value="data_compensacao">Compensação</option>
                                        <option value="created_at">Criação</option>
                                        <option value="bancaria">Conciliação bancaria</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Data inicial</label>
                                    <input type="date" id="data_inicio" name="data_inicio" class="form-control" value="{{date('Y-m-d')}}">
                                    
                                    
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Data final</label>
                                    <input type="date" id="data_fim" name="data_fim" class="form-control" value="{{date('Y-m-d')}}">
                                    
                                    
                                </div>
                            </div>
                            <div class="col-md-2">
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

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Menor que (R$)</label>
                                    <input type="text" id="menor"  name="menor" class="form-control maskDecimal" placeholder="maior que R$ 000,00">
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Maior que (R$)</label>
                                    <input type="text" id="maior" name="maior" class="form-control maskDecimal" placeholder="menor que R$ 000,00">
                                     
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
                            
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Formas de recebimento</label>
                                    <select name="forma_recebimento" class="form-control selectfild2" style="width: 100%">
                                        <option value="">Todos Formas de recebimento</option>
                                        @foreach ($formasRecebimento as $item)
                                            <option value="{{$item->id}}">{{$item->forma_recebimento}}</option>
                                        @endforeach
                                    </select>
        
        
                                </div>
                            </div> --}}

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Tipo pagamento</label>
                                    <select name="status_id" id="status_id" class="form-control selectfild2" style="width: 100%">
                                        <option value="2">Todas pagas e não pagas</option>
                                        <option value="1">Pagos</option>
                                        <option value="0">Não pagos</option>
                                    </select>      
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Natureza</label>
                                    <select name="natureza" id="natureza" class="form-control selectfild2" style="width: 100%">
                                        <option value="todas">Todas</option>
                                        <option value="conta_pagar">Contas a pagar</option>
                                        <option value="conta_receber">Contas a receber</option>
                                    </select>      
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Tipo relatório</label>
                                    <select name="tipo_relatorio" id="tipo_relatorio" class="form-control selectfild2" style="width: 100%">
                                        <option value="detalhado">Detalhado</option>
                                        <option value="resumido">Resumido</option>
                                        <option value="acamulativo">Exibir saldo acumulativo</option>
                                        <option value="caixa_resumido">Caixa resumido</option>
                                        <option value="plano_contas">Plano de contas</option>
                                        plano_contas
                                    </select>
        
        
                                </div>
                            </div>
                            <div class="col-md-8"></div>
                            <div class="col-md-8"></div>
                            <div class="col-md-2">
                                <div class="form-group imprimir" style="margin-top: 30px !important; float: right; width: 100%; display: none">
                                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="imprimir()">Imprimir</button>
                                </div>
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

                            <h3 class="mt-2"><center>Demonstrativo financeiro <span class='texto_titulo'></span></center></h3>

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
            $("#menor").setMask({ mask: '99,999', type:'reverse'})
            $("#maior").setMask({ mask: '99,999', type:'reverse'})
            
            $(".maskDecimal").each(function () {
                var $element = $(this);
                if (!$(this).attr('wire:model')) {
                    $element.setMask({ mask: '99,999', type:'reverse'})
                    return;
                }

                var $id = $(this).parents('[wire\\:id]').attr('wire:id');
                $element.on('blur', function (e) {
                    $valor = $(e.target).val()
                    window.livewire.find($id).set($(this).attr('wire:model'), $valor);
                });
            });
        })

        $('#formDemonstrativoFinanceiro').on('submit', function(e){
            e.preventDefault()
            var formData = new FormData($(this)[0]);
            
            $.ajax("{{route('instituicao.demonstrativoFinanceiro.tabela')}}", {
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