<div class="card-body">
    <form wire:ignore class="no_print" action="javascript:void(0)" id="FormTitular">
        <div class="row" style="margin-bottom: 20px">
            <div class="col-md-10"></div>
            
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <input type="text" id="search" wire:model.lazy="search" name="search" class="form-control" placeholder="Pesquise por descrição...">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <input type="date" id="data_inicio" wire:model.lazy="data_inicio" name="data_inicio" class="form-control" placeholder="Data vencimento inicio">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">                     
                    <input type="date" alt="date" id="data_fim" wire:model.lazy="data_fim" name="data_fim" class="form-control" placeholder="Data vencimento final">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <span alt="default" class="add fas fa-plus-circle" style="cursor: pointer;">
                        <a class="mytooltip" href="javascript:void(0)">
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Abrir mais filtros"></i>
                        </a>
                    </span>
                    <span alt="default" class="remove fas fa-minus-circle" style="cursor: pointer; display: none">
                        <a class="mytooltip" href="javascript:void(0)">
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Esconder filtros"></i>
                        </a>
                    </span>
                </div>
            </div>

            @can('habilidade_instituicao_sessao', 'cadastrar_contas_receber')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 10px !important; float: right;">
                        <a href="{{ route('instituicao.contasReceber.create') }}">
                        <button type="button" class="btn waves-effect waves-light btn-block btn-info">Nova conta a receber</button>
                        </a>
                    </div>
                </div>
            @endcan
            <div class="col-md-12 filtros" style="display: none">
                <div class="row">                    
                    <div class="col-md-3">
                        <div class="form-group" wire:ignore>
                            <select name="forma_pagamento_id" class="form-control selectfild2" wire:model="formaPagamento" style="width: 100%">
                            <option value="">Todas Formas pagamento</option>
                                @foreach ($formaPagamentos as $formaPagamento)
                                    <option value="{{ $formaPagamento }}">
                                        {{ App\ContaReceber::forma_pagamento_texto($formaPagamento) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" wire:ignore>

                            <select name="status_id" id="status_id" class="form-control selectfild2" wire:model="status" style="width: 100%">
                                <option value="3">Todas pagas e não pagas</option>
                                <option value="1">Pagos</option>
                                <option value="0">Não pagos</option>
                            </select>


                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group" wire:ignore>
                            <select name="conta_id" class="form-control selectfild2" wire:model="conta_id" style="width: 100%">
                                <option value="0">Todos contas</option>
                                @foreach ($contas as $item)
                                    <option value="{{$item->id}}">{{$item->descricao}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-9">
                        <div class="form-group" wire:ignore>
                            <select name="plano_conta_id" class="form-control selectfild2" wire:model="plano_conta_id" style="width: 100%">
                                <option value="0">Todos Planos de Conta</option>
                                @foreach ($planosConta as $item)
                                    <option value="{{$item->id}}">{{$item->codigo}} - {{$item->descricao}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" wire:ignore>
                            <select name="tipo" class="form-control selectfild2" wire:model="tipo" id="tipo" onchange="tipoPesquisa()" style="width: 100%">
                            <option value="">Todos tipo</option>
                                @foreach ($tipos as $tipo)
                                    <option value="{{ $tipo }}">
                                        {{ App\ContaReceber::tipos_texto_all($tipo) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group" wire:ignore>
                            <select name="tipo_id" class="form-control selectfild2" wire:model="tipo_id" id="tipo_id" style="width: 100%" disabled>
                                <option value="0">Selecione um tipo</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <hr class="no_print">

    <div class="col-md-12 my-2 text-right no_print" style="padding-left:0px;padding-top:15px;">
        <button type="button" class="btn btn-outline-secondary" data-toggle="collapse" data-target="#collapseColunas" aria-expanded="false" aria-controls="collapseColunas" style="border: 1px solid #ced4da;">
            <i class="fa fa-fw fa-filter" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Filtar exibição de colunas"></i>
        </button>

        <div class="collapse my-2 text-left no_print" id="collapseColunas">
            <div class="card card-body">
                <h4 class="lead card-title">Escolha quais colunas deseja exibir</h4>
                <hr>
                <div class="row">
                    <div class="col-md-2">
                        <input class="colunaTabela" type="checkbox" id="exibeNParcela" onchange="exibeColuna('exibeNParcela')"/>
                        <label for="exibeNParcela">Nº Parcela</label>
                    </div>

                    <div class="col-md-2">
                        <input class="colunaTabela" type="checkbox" id="exibeDescricao" onchange="exibeColuna('exibeDescricao')"/>
                        <label for="exibeDescricao">Descrição</label>
                    </div>

                    <div class="col-md-2">
                        <input class="colunaTabela" type="checkbox" id="exibeCaixa" onchange="exibeColuna('exibeCaixa')"/>
                        <label for="exibeCaixa">Caixa</label>
                    </div>

                    <div class="col-md-2">
                        <input class="colunaTabela" type="checkbox" id="exibePlanoConta" onchange="exibeColuna('exibePlanoConta')"/>
                        <label for="exibePlanoConta">Plano de conta</label>
                    </div>

                    <div class="col-md-2">
                        <input class="colunaTabela" type="checkbox" id="exibeDataCompensacao" onchange="exibeColuna('exibeDataCompensacao')"/>
                        <label for="exibeDataCompensacao">Data compensação</label>
                    </div>

                    <div class="col-md-2">
                        <input class="colunaTabela" type="checkbox" id="exibeConvenios" onchange="exibeColuna('exibeConvenios')"/>
                        <label for="exibeConvenios">Convênios</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive no_print">
        <table id="demo-foo-row-toggler" class="table table-bordered" data-toggle-column="first" >
            <thead>
                <tr>
                    <th data-breakpoints="all">id</th>
                    {{-- <th data-breakpoints="all">Forma recebimento</th> --}}
                    <th class="exibeNParcela" style="display: none;">N° parcela</th>
                    <th>Paciente</th>
                    <th class="exibeDescricao" style="display: none;">Descrição</th>
                    <th class="exibeCaixa" style="display: none;" data-breakpoints="all">Caixa</th>
                    <th class="exibePlanoConta" style="display: none;" data-breakpoints="all">Plano de conta</th>
                    <th >Data vencimento</th>            
                    <th >Valor parcela</th>
                    <th data-breakpoints="all">Status</th>
                    <th>Data quitação</th>
                    <th class="exibeDataCompensacao" style="display: none;">Data compensação</th>
                    <th>Valor pago</th>
                    <th >Forma pagamento</th>
                    <th class="exibeConvenios" style="display: none;">Convênios</th>
                    <th class="acao">Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contas_receber as $conta_receber)
                    {{-- {{dd($conta_receber)}} --}}

                    <tr
                        @if ($conta_receber->status == 1 && $conta_receber->cancelar_parcela == 0)
                            style="background: #54f75440"
                        @elseif ($conta_receber->cancelar_parcela == 1)
                            style="background: #00000038; color: white"
                        @elseif ($conta_receber->status == 0 && strtotime($conta_receber->data_vencimento) < strtotime(date('Y-m-d')))
                            style="background: #ff6b6b3d"
                        @endif
                        

                        class="id_{{$conta_receber->id}}"
                    >
                        <td>{{$conta_receber->id}}</td>
                        {{-- <td>{{($conta_receber->forma_recebimento_id) ? $conta_receber->formaRecebimento->forma_recebimento : '-'}}</td> --}}
                        <td class="exibeNParcela" style="display: none;">{{$conta_receber->num_parcela}} @if ($conta_receber->cancelar_parcela == 1) (parcela cancelada) @endif </td>
                        
                        <td>
                            @if($conta_receber->tipo == 'paciente')
                                {{($conta_receber->pessoa_id) ? "Paciente: ".$conta_receber->pacienteTrashed->nome : "Paciente Avulso"}}
                            @elseif($conta_receber->tipo == 'convenio')
                                Convenio: {{$conta_receber->convenio->nome}}
                            @endif
                        
                        </td>
                        <td class="exibeDescricao" style="display: none;">{{$conta_receber->descricao}}</td>
                        <td class="exibeCaixa" style="display: none;">{{($conta_receber->contaCaixa) ? $conta_receber->contaCaixa->descricao : '-' }}</td>
                        <td class="exibePlanoConta" style="display: none;">{{($conta_receber->planoConta) ? $conta_receber->planoConta->descricao : '-' }}</td>
                        <td>{{date('d/m/Y', strtotime($conta_receber->data_vencimento))}}</td>
                        <td>R$ {{number_format($conta_receber->valor_parcela, 2, ',','.')}}</td>
                        <td class="status_{{$conta_receber->id}}">{{$conta_receber->status == 0 ? '-' : 'pago'}}</td>
                        <td class="quitacao_{{$conta_receber->id}}">{{($conta_receber->data_pago) ? date('d/m/Y', strtotime($conta_receber->data_pago)) : '-'}}</td>
                        <td class="exibeDataCompensacao" style="display: none;">{{($conta_receber->data_compensacao) ? date('d/m/Y', strtotime($conta_receber->data_compensacao)) : '-' }}</td>
                        <td class="pago_{{$conta_receber->id}}">
                            @if ($conta_receber->valor_pago)
                                R$ {{number_format($conta_receber->valor_pago, 2, ',','.')}}
                            @else
                                -
                            @endif
                        </td>
                        <td>    
                            {{($conta_receber->forma_pagamento) ? App\ContaReceber::forma_pagamento_texto($conta_receber->forma_pagamento) : '-'}}
                        </td>

                        <td class="exibeConvenios" style="display: none;">
                            @php $convenios = [] @endphp
                            @if($conta_receber->agendamentos)
                                @php 
                                    
                                    foreach($conta_receber->agendamentos->conveniosProcedimentos as $item){
                                        $convenios[] = $item->convenios->nome; 
                                    }
                                @endphp
                            @elseif($conta_receber->odontologico)
                                @php
                                    
                                    foreach($conta_receber->odontologico->itens as $item){
                                        $convenios[] = $item->procedimentos->convenios->nome;
                                    }
                                @endphp
                            @endif
                            {{implode(", ", array_unique($convenios))}}
                        </td>
                        
                        <td class="acao">                            
                            @can('habilidade_instituicao_sessao', 'editar_contas_receber')
                                <a href="{{ route('instituicao.contasReceber.edit', [$conta_receber]) }}">
                                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                                <i class="ti-pencil-alt"></i>
                                        </button>
                                </a>
                            @endcan 
                            
                            @can('habilidade_instituicao_sessao', 'receber_contas_receber')
                                @if ($conta_receber->status == 0)
                                    <button type="button" class="btn btn-xs btn-secondary modal_conta_receber" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="receber" data-id="{{$conta_receber->id}}">
                                        <i class="ti-money"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-xs btn-secondary printRecibo" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Recibo" data-id="{{$conta_receber->id}}">
                                        <i class="mdi mdi-receipt"></i>
                                    </button>
                                @endif        
                            @endcan

                            @can('habilidade_instituicao_sessao', 'estornar_contas_receber')
                                @if ($conta_receber->status == 1)
                                    <button type="button" class="btn btn-xs btn-secondary estornar_parcela" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Estornar" data-id="{{$conta_receber->id}}">
                                        <i class="fa fa-ban"></i>
                                    </button>
                                @endif
                            @endcan

                            @can('habilidade_instituicao_sessao', 'emitir_boleto')
                                <button type="button" class="btn btn-xs btn-secondary emitir_boleto" aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Emitir boleto" data-id="{{$conta_receber->id}}">
                                    <i  class="mdi mdi-barcode"></i>
                                </button>
                            @endcan

                            @can('habilidade_instituicao_sessao', 'emitir_nota_fiscal')
                                <button type="button" class="btn btn-xs btn-secondary emitir_nota" aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Emitir nota fiscal" data-id="{{$conta_receber->id}}">
                                    <i  class="fa fa-money-bill-alt"></i>
                                </button>
                            @endcan
                            
                            @can('habilidade_instituicao_sessao', 'excluir_contas_receber')
                                <form action="{{ route('instituicao.contasReceber.destroy', [$conta_receber]) }}" method="post" class="d-inline form-excluir-registro">
                                    @method('delete')
                                    @csrf
                                    <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                            <i class="ti-trash"></i>
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                {{-- <tr>
                    <td colspan="5">
                        {{ $arquivos->links() }}
                    </td>
                </tr>  --}}
            </tfoot>
        </table>
    </div>

    <div class="no_print" style="float: right">
        {{ $contas_receber->links() }}
    </div>

    <div id="modal_receber_visualizar" class="no_print"></div>
    <div id="reciboDiv" class="print-div" style="display: none;"></div>

    <div wire:ignore class="modal inmodal no_print" id="modalEmitirNota" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 1200px;">
            <div class="modal-content" style="background: #f8fafb;"></div>
        </div>
    </div>
</div>

@push('estilos')
    <style>

        @media print {  
            body * {
                visibility: hidden;
            }
            .print-div, .print-div * {
                visibility: visible;
            }
            .print-div {
                position: absolute;
                widows: 100%;
                left: 0;
                top: 0;
            
            }
            .no_print { 
                display: none !important;
            }
        }

        .acao {
            width: 130px;
            padding-left: 2px ;
            padding-right: 2px ;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(".add").on('click', function() {
            $(".filtros").css('display', 'block');
            $(".add").css('display', 'none');
            $(".remove").css('display', 'block');
        })

        $(".remove").on('click', function() {
            $(".filtros").css('display', 'none');
            $(".remove").css('display', 'none');
            $(".add").css('display', 'block');
        })

        function tipoPesquisa(){
            tipo = $('#tipo').val()            
            if(tipo == "paciente"){
                $("#tipo_id").prop("disabled", false);
                $('#tipo_id').html('');

                $("#tipo_id").select2({
                    placeholder: "Pesquise por nome do paciente",
                    allowClear: true,
                    minimumInputLength: 3,

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
            }else if(tipo == "convenio"){
                $("#tipo_id").prop("disabled", false);
                $('#tipo_id').html('');

                $("#tipo_id").select2({
                    placeholder: "Pesquise por nome do convenio",
                    allowClear: true,

                    language: {
                        searching: function () {
                            return 'Buscando convenio (aguarde antes de selecionar)…';
                        },
                    },
                    
                    ajax: {
                        url:"{{route('instituicao.contasReceber.getConvenios')}}",
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
                                    text: item.nome,
                                })),
                                pagination: {
                                    more: data.pagination.more
                                }
                            };
                        },
                        cache: true
                    },
                });
            }else{
                $('#tipo_id').prop('disabled', true)
                $('#tipo_id').html('')
            }
        }

        $('.form_pesquisa').on('click', '.modal_conta_receber', function(e){
            id = $(this).attr('data-id');
            console.log(id);

            var url = "{{ route('instituicao.contasReceber.visualizarParcelas', ['contaReceber' => 'contaReceberId']) }}".replace('contaReceberId', id);
            var data = {
                '_token': '{{csrf_token()}}'
            };
            var modal = 'modalReceberConta';

            // $('#loading').removeClass('loading-off');
            // $('#modal_receber_visualizar').load(url, data, function(resposta, status) {
            //     $('#' + modal).modal();
            //     $('#loading').addClass('loading-off');
            // });

            $.ajax(url, {
                method: "POST",
                data: {
                    "_token": "{{csrf_token()}}",
                },
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function (result) {
                    console.log(result);
                    $("#modal_receber_visualizar").html(result);
                    $('#modalReceberConta').modal();
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')
                }
            });
        })

        $('.form_pesquisa').on('click', '.btn-excluir-registro', function(e) {
            e.preventDefault();

            Swal.fire({   
                title: "Confirmar exclusão?",   
                text: "Ao confirmar você estará excluindo o registro permanente!",   
                icon: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Sim, confirmar!",   
                cancelButtonText: "Não, cancelar!",
            }).then(function (result) {   
                if (result.value) {     
                    $(e.currentTarget).parents('form').submit();
                } 
            });
        });

        $('.form_pesquisa').on('click', '.printRecibo', function(){
            id = $(this).attr('data-id');

            $.ajax("{{ route('instituicao.contasReceber.printRecibo', ['conta' => 'contaId']) }}".replace('contaId', id), {
                method: "GET",
                data: {
                "_token": "{{csrf_token()}}",
                },
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                    $("#reciboDiv").css("display", "block");
                },
                success: function (result) {
                    $("#reciboDiv").html(result);
                    
                    window.print();
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader') 
                    $("#reciboDiv").css("display", "none");
                }
            });
        })

        $('.form_pesquisa').on('click', '.estornar_parcela', function(){
            id = $(this).attr('data-id');

            Swal.fire({
                title: "Atenção!",
                text: 'Deseja estornar parcela? Esta ação irá retornar a parcela para o estatus não pago!',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){
                    $.ajax("{{ route('instituicao.contasReceber.estornarParcela', ['contaReceber' => 'contaId']) }}".replace('contaId', id), {
                        method: "GET",
                        data: {
                        "_token": "{{csrf_token()}}",
                        },
                        beforeSend: () => {
                            $('.loading').css('display', 'block');
                            $('.loading').find('.class-loading').addClass('loader')
                        },
                        success: function (resultado) {
                            $(".id_"+id).css('background', '#fff');
                            $(".status_"+id).text('-');
                            $("quitacao_"+id).text("-");
                            $("pago_"+id).text("-");
                        },
                        complete: () => {
                            $('.loading').css('display', 'none');
                            $('.loading').find('.class-loading').removeClass('loader')
                        }
                    });
                }
            });
        })
        
        $('.form_pesquisa').on('click', '.emitir_nota', function(){
            id = $(this).attr('data-id');

            $.ajax("{{ route('instituicao.notasFiscais.emitirNfe') }}", {
                method: "POST",
                data: {
                    "_token": "{{csrf_token()}}",
                    "conta_receber_id": id,
                },
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function (result) {
                    // if(result.icon == 'error'){
                    //     Object.keys(result.errors).forEach(function(key) {
                    //         $.toast({
                    //             heading: 'Erro',
                    //             text: result.errors[key].mensagem,
                    //             position: 'top-right',
                    //             loaderBg: '#ff6849',
                    //             icon: 'error',
                    //             hideAfter: 9000,
                    //             stack: 10
                    //         });
                    //     });
                    // }else{
                        $('#modalEmitirNota .modal-content').html('');
                        $('#modalEmitirNota .modal-content').html(result);
                        $("#modalEmitirNota").modal('show');
                        
                        // $.toast({
                        //     heading: 'Sucesso',
                        //     text: "Nota emitida com sucesso",
                        //     position: 'top-right',
                        //     loaderBg: '#ff6849',
                        //     icon: 'success',
                        //     hideAfter: 5000,
                        //     stack: 10
                        // });
                    // }
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader') 
                }
            });
        });

        $('.form_pesquisa').on('click', '.emitir_boleto', function(){
            id = $(this).attr('data-id');

            url = "{{ route('instituicao.contasReceber.geraBoleto', ['conta_rec' => 'conta_id']) }}".replace('conta_id', id);
            window.open(url, '_BLANK');
            // $.ajax("{{ route('instituicao.contasReceber.geraBoleto', ['conta_rec' => 'conta_id']) }}".replace('conta_id', id), {
            //     method: "POST",
            //     data: {
            //         "_token": "{{csrf_token()}}"
            //     },
            //     beforeSend: () => {
            //         $('.loading').css('display', 'block');
            //         $('.loading').find('.class-loading').addClass('loader')
            //     },
            //     success: function (result) {
                    // if(result.icon == 'error'){
                    //     Object.keys(result.errors).forEach(function(key) {
                    //         $.toast({
                    //             heading: 'Erro',
                    //             text: result.errors[key].mensagem,
                    //             position: 'top-right',
                    //             loaderBg: '#ff6849',
                    //             icon: 'error',
                    //             hideAfter: 9000,
                    //             stack: 10
                    //         });
                    //     });
                    // }else{
                        // $('#modalEmitirNota .modal-content').html('');
                        // $('#modalEmitirNota .modal-content').html(result);
                        // $("#modalEmitirNota").modal('show');
                        
                        // $.toast({
                        //     heading: 'Sucesso',
                        //     text: "Nota emitida com sucesso",
                        //     position: 'top-right',
                        //     loaderBg: '#ff6849',
                        //     icon: 'success',
                        //     hideAfter: 5000,
                        //     stack: 10
                        // });
                    // }

                    
                // },
                // complete: () => {
                //     $('.loading').css('display', 'none');
                //     $('.loading').find('.class-loading').removeClass('loader') 
                // }
            // });
        });

        function exibeColuna(element){
            console.log(element);
        
            if($("#"+element).is(':checked')){
                $("."+element).css("display", "")
            }else{
                $("."+element).css("display", "none")
            }
        }


        
    </script>
@endpush
