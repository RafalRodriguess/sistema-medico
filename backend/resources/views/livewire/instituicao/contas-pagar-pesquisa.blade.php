<div class="card-body">
    <form wire:ignore action="javascript:void(0)" id="FormTitular" class="no_print">
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
                     
                    <input type="text" alt="date" id="data_inicio" wire:model.lazy="data_inicio" name="data_inicio" class="form-control" placeholder="Data vencimento inicio">
                    
                     
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                     
                      <input type="text" alt="date" id="data_fim" wire:model.lazy="data_fim" name="data_fim" class="form-control" placeholder="Data vencimento final">
                    
                     
                </div>
            </div>
            
            <div class="col-md-1">
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

            @can('habilidade_instituicao_sessao', 'cadastrar_contas_pagar')
                <div class="col-md-2">
                    <div class="form-group col-md" style="margin-bottom: 10px !important; float: right;">
                        <a href="{{ route('instituicao.contasPagar.create') }}">
                        <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                        </a>
                    </div>
                </div>
            @endcan

            <div class="col-md-12 filtros" style="display: none">
                <div class="row">     
                        
                    <div class="col-md-3">
                        <div class="form-group" wire:ignore>
                             
                            <input type="text" id="valor_total_nf"  name="valor_total_nf" wire:model="valor_total_nf" class="form-control maskDecimal" placeholder="valor total da NF R$ 000,00">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" wire:ignore>
                             
                            <input type="text" id="nota_fiscal"  name="nota_fiscal" wire:model="nota_fiscal" class="form-control" placeholder="Nº da NF">
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group" wire:ignore>
                             
                            <input type="text" id="menor_f"  name="menor_f" wire:model="menor" class="form-control maskDecimal" placeholder="maior que R$ 000,00">
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group" wire:ignore>
                             
                            <input type="text" id="maior_f" wire:model="maior" name="maior_f" class="form-control maskDecimal" placeholder="menor que R$ 000,00">
                             
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group" wire:ignore>
                            <select name="status_id" id="status_id" class="form-control selectfild2" wire:model="status" style="width: 100%">
                                <option value="3">Todas pagas e não pagas</option>
                                <option value="1">Pagos</option>
                                <option value="0">Não pagos</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group" wire:ignore>

                            <select name="formaPagamento" class="form-control selectfild2" wire:model="formaPagamento" id="formaPagamento" style="width: 100%">
                            <option value="" selected>Todos tipo</option>
                                @foreach ($formaPagamentos as $forma)
                                    <option value="{{ $forma }}">
                                        {{ App\ContaPagar::forma_pagamento_texto($forma) }}
                                    </option>
                                @endforeach
                            </select>


                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" wire:ignore>

                            <select name="conta_id" class="form-control selectfild2" wire:model="conta_id" style="width: 100%">
                                <option value="0">Todos caixas</option>
                                @foreach ($contas as $item)
                                    <option value="{{$item->id}}">{{$item->descricao}}</option>
                                @endforeach
                            </select>


                        </div>
                    </div>
                    
                    <div class="col-md-4">
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
                                        {{ App\ContaPagar::tipos_texto_all($tipo) }}
                                    </option>
                                @endforeach
                            </select>


                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group" wire:ignore>

                            <select name="tipo_id" class="form-control selectfild2" wire:model="tipo_id" id="tipo_id" style="width: 100%" disabled></select>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <hr>

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
                        <input class="colunaTabela" type="checkbox" id="exibeDescJurosMulta" onchange="exibeColuna('exibeDescJurosMulta')"/>
                        <label for="exibeDescJurosMulta">Desc/Juros/Multa</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive no_print">
        <table id="demo-foo-row-toggler" class="table table-bordered" data-toggle-column="first" >
            <thead>
                <tr>
                    {{-- <th data-breakpoints="xs" hidden></th> --}}
                    <th data-breakpoints="all">id</th>
                    <th >Fornecedor/Paciente</th>
                    <th class="exibeDescricao" style="display: none;">Descrição</th>
                    <th class="exibeNParcela" style="display: none;">Nº Parc.</th>
                    {{-- <th >Cotação</th> --}}
                    <th class="exibeDescJurosMulta" style="display: none;" data-breakpoints="all">Desc/Juros/Multa</th>
                    <th class="exibeCaixa" style="display: none;" data-breakpoints="all">Caixa</th>
                    <th class="exibePlanoConta" style="display: none;" data-breakpoints="all">Plano de conta</th>
                    <th >Data Vct.</th>            
                    <th >Valor Parc</th>
                    <th data-breakpoints="all">Status</th>
                    <th >Data quitação</th>
                    <th >Valor pago</th>
                    <th class="exibeDataCompensacao" style="display: none;" data-breakpoints="all">Data compensação</th>
                    <th >Forma pagamento</th>
                    <th class="acao">Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contas_pagar as $conta_pagar)
                    <tr 
                        @if ($conta_pagar->status == 1)
                            style="background: #54f75440"
                        @endif
                        @if ($conta_pagar->status == 0 && strtotime($conta_pagar->data_vencimento) < strtotime(date('Y-m-d')) && $conta_pagar->cotacao == 0)
                            style="background: #ff6b6b3d"
                        @endif
                        @if ($conta_pagar->cotacao == 1)
                            style="background: #e8f52452"
                        @endif

                        class="id_{{$conta_pagar->id}}"
                    >
                        {{-- {{dd($conta_pagar->conta_id)}} --}}
                        <td>{{$conta_pagar->id}}</td>
                        
                        <td>
                            @if ($conta_pagar->tipo == 'paciente')
                                Paciente: {{(!empty($conta_pagar->paciente)) ? $conta_pagar->paciente->nome : ""}}
                            @endif
                            
                            @if ($conta_pagar->tipo == 'prestador')
                                Prestador: {{$conta_pagar->prestador->nome}}
                            @endif 
                            
                            @if ($conta_pagar->tipo == 'fornecedor')                            
                                Fornecedor: {{(!empty($conta_pagar->fornecedor['nome_fantasia'])) ? $conta_pagar->fornecedor['nome_fantasia'] : $conta_pagar->fornecedor['nome']}}
                            @endif  
                        </td>
                        <td class="exibeDescricao" style="display: none;">{{$conta_pagar->descricao}}</td>
                        <td class="exibeNParcela" style="display: none;">{{$conta_pagar->num_parcela}}</td>
                       
                        <td class="exibeDescJurosMulta" style="display: none;" class="desc_juros_multa_tabela">R$ {{number_format($conta_pagar->desc_juros_multa, 2, ',','.')}}</td>
                        <td class="exibeCaixa conta_caixa" style="display: none;">{{($conta_pagar->contaCaixa) ? $conta_pagar->contaCaixa->descricao : '-' }}</td>
                        <td class="exibePlanoConta plano_conta_caixa" style="display: none;" class="plano_conta_caixa">{{($conta_pagar->planoConta) ? $conta_pagar->planoConta->descricao : '-' }}</td>
                        
                        <td>
                            {{date('d/m/Y', strtotime($conta_pagar->data_vencimento))}}
                        </td>
                        <td>
                            R$ {{number_format($conta_pagar->valor_parcela, 2, ',','.')}}
                        </td>
                        <td id="status_{{$conta_pagar->id}}" class="status">{{$conta_pagar->status == 0 ? '-' : 'pago'}}</td>
                        <td id="quitacao_{{$conta_pagar->id}}" class="quitacao">{{$conta_pagar->data_pago ? date('d/m/Y', strtotime($conta_pagar->data_pago)) : '-'}}</td>
                        <td id="pago_{{$conta_pagar->id}}" class="pago">
                            @if ($conta_pagar->valor_pago)
                                R$ {{number_format($conta_pagar->valor_pago, 2, ',','.')}}
                            @else
                                -
                            @endif
                        </td>
                        <td class="exibeDataCompensacao data_compensacao" style="display: none;">
                            @if ($conta_pagar->data_compensacao)
                                {{date('d/m/Y', strtotime($conta_pagar->data_compensacao))}}                    
                            @else
                                -
                            @endif
                        </td>
                        
                        <td class="forma_pagamento">    
                            @if ($conta_pagar->cotacao == 1)
                                -
                            @else
                                {{($conta_pagar->forma_pagamento) ?App\ContaPagar::forma_pagamento_texto($conta_pagar->forma_pagamento) : '-' }}
                            @endif
                        </td>
                        <td class="acao">
                            @can('habilidade_instituicao_sessao', 'editar_contas_pagar')
                                <a href="{{ route('instituicao.contasPagar.edit', [$conta_pagar]) }}">
                                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                                <i class="ti-pencil-alt"></i>
                                        </button>
                                </a>
                            @endcan

                            @can('habilidade_instituicao_sessao', 'estornar_contas_pagar')
                                @if ($conta_pagar->status == 1)
                                    <button type="button" class="btn btn-xs btn-secondary estornar_parcela" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Estornar" data-id="{{$conta_pagar->id}}">
                                        <i class="fa fa-ban"></i>
                                    </button>
                                @endif
                            @endcan 

                            @if ($conta_pagar->status == 0)
                                @can('habilidade_instituicao_sessao', 'pagar_contas_pagar')
                                    {{-- @if ($conta_pagar->status == 0) --}}
                                        <button type="button" class="btn btn-xs btn-secondary modal_conta_pagar" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="pagar" data-id="{{$conta_pagar->id}}">
                                            <i class="ti-money"></i>
                                        </button>
                                    {{-- @endif         --}}
                                @endcan
                            @else
                                <button type="button" class="btn btn-xs btn-secondary printRecibo" aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Recibo" data-id="{{$conta_pagar->id}}">
                                    <i class="mdi mdi-receipt"></i>
                                </button>
                            @endif

                            @if (empty($conta_pagar->conta_pai))    
                                @can('habilidade_instituicao_sessao', 'excluir_contas_pagar')
                                    <form action="{{ route('instituicao.contasPagar.destroy', [$conta_pagar]) }}" method="post" class="d-inline form-excluir-registro">
                                        @method('delete')
                                        @csrf
                                        <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                                <i class="ti-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            @else
                                @can('habilidade_instituicao_sessao', 'excluir_contas_pagar')
                                    <form action="{{ route('instituicao.contasPagar.destroy', [$conta_pagar]) }}" method="post" class="d-inline form-excluir-registro-todos">
                                        @method('delete')
                                        @csrf
                                        <input type="hidden" class="excluir_todos" name="excluir_{{$conta_pagar->id}}" value="">
                                        <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro-todos"  aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                                <i class="ti-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            @endif
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
    <div style="float: right">
        {{ $contas_pagar->links() }}
    </div>

    <div id="modal_pagar_visualizar" class=" no_print"></div>
    <div id="reciboDiv" class="print-div" style="display: none;"></div>
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
                position: absolute;;
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
        $(document).ready(function() {
            $("#menor_f").setMask({ mask: '99,999.999.999', type:'reverse'})
            $("#maior_f").setMask({ mask: '99,999.999.999', type:'reverse'})
            $("#valor_total_nf").setMask({ mask: '99,999.999.999', type:'reverse'})
            
            $(".maskDecimal").each(function () {
                var $element = $(this);
                if (!$(this).attr('wire:model')) {
                    $element.setMask({ mask: '99,999.999.999', type:'reverse'})
                    return;
                }

                var $id = $(this).parents('[wire\\:id]').attr('wire:id');
                $element.on('blur', function (e) {
                    $valor = $(e.target).val()
                    window.livewire.find($id).set($(this).attr('wire:model'), $valor);
                });
            });

        })

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

            }else if(tipo == "prestador"){
                $("#tipo_id").prop("disabled", false);
                $('#tipo_id').html('');
                $("#tipo_id").select2({
                    placeholder: "Pesquise por nome do prestador",
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
                        url:"{{route('instituicao.contasPagar.getPrestadores')}}",
                        dataType: 'json',
                        delay: 100,

                        data: function (params) {
                            return {
                                q: params.term, // search term
                                page: params.page || 1
                            };
                        },

                        processResults: function (data, params) {
                            params.page = params.page || 1;
                            console.log(data)
                            return {
                                results: _.map(data.results, item => ({
                                    id: Number.parseInt(item.prestador.id),
                                    text: `${item.prestador.nome}`,
                                })),
                                pagination: {
                                    more: data.pagination.more
                                }
                            };
                        },
                        cache: true
                    },
                });  
            }else if(tipo == "fornecedor"){
                $("#tipo_id").prop("disabled", false);
                $('#tipo_id').html('');
                $("#tipo_id").select2({
                    placeholder: "Pesquise por nome do fornecedor",
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
                        url:"{{route('instituicao.contasPagar.getFornecedores')}}",
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

            }else{
                $('#tipo_id').prop('disabled', true)
                $('#tipo_id').html('')
                options = '<option value="0"> Selecione um tipo </option>'
                $('#tipo_id').html(options);
            }
            
            // if(tipo == ''){
            //     $('#tipo_id').prop('disabled', true)
            //     $('#tipo_id').html('')
            //     options = '<option value="0"> Selecione um tipo </option>'
            //     $('#tipo_id').html(options);
            // }else{
                // $.ajax({
                //     url: "{{route('instituicao.contasPagar.getTipo')}}",
                //     type: 'post',
                //     data: {
                //             "_token": "{{ csrf_token() }}",
                //             tipo: tipo
                //         },
                //     beforeSend: () => {
                //         $('#tipo_id').html('')
                //         $('#tipo_id').prop('disabled', true)
                //     },
                //     success: function(retorno) {
                //         if(tipo == 'fornecedor'){
                //             options = '<option value="0"> Todos Fornecedores </option>'

                //             retorno.forEach(element => {
                //                 options += '<option value="'+element.id+'"> '+element.nome+' </option>'
                //             });


                //         }else{
                //             options = '<option value="0"> Todos Vendas </option>'

                //             retorno.forEach(element => {
                //                 options += '<option value="'+element.id+'"> '+element.empreendimento.nome+' '+element.empreendimento_unidade.nome+' '+element.empreendimento_sub_unidade.lote+' </option>'
                //             });
                //         }

                //         $('#tipo_id').html(options);
                //     },
                //     complete: () => {
                //         $("#tipo_id").val(0).change()
                //         $('#tipo_id').prop('disabled', false)
                //     }
                // })
            // }
        }

        $('.form_pesquisa').on('click', '.modal_conta_pagar', function(){
            id = $(this).attr('data-id');
            
            var url = "{{ route('instituicao.contasPagar.pagarParcela', ['conta' => 'contaPagarId']) }}".replace('contaPagarId', id);
            
            var data = {
                '_token': '{{csrf_token()}}'
            };
            var modal = 'modalPagarConta';

            $('#loading').removeClass('loading-off');

            $('#modal_pagar_visualizar').load(url, data, function(resposta, status) {
                $('#' + modal).modal();
                $('#loading').addClass('loading-off');
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
                    $.ajax("{{ route('instituicao.contasPagar.estornarParcela', ['contaPagar' => 'contaId']) }}".replace('contaId', id), {
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
                            $("#status_"+id).text('-');
                            $("#quitacao_"+id).text("-");
                            $("#pago_"+id).text("-");
                        },
                        complete: () => {
                            $('.loading').css('display', 'none');
                            $('.loading').find('.class-loading').removeClass('loader')
                        }
                    });
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

        $(".form_pesquisa").on('click', '.btn-excluir-registro-todos', function(e){
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
                    Swal.fire({   
                        title: "Excluir todos lancamentos?",   
                        text: "Ao confirmar você estará excluindo todos os lançamentos referente a conta!",   
                        icon: "warning",   
                        showCancelButton: true,   
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonText: "Sim, confirmar!",   
                        cancelButtonText: "Não, cancelar!",
                    }).then(function (result) {   
                        if (result.value) {     

                            $(e.currentTarget).parents('form').find('.excluir_todos').val('1');
                            $(e.currentTarget).parents('form').submit();

                        } else{

                            $(e.currentTarget).parents('form').find('.excluir_todos').val('0');
                            $(e.currentTarget).parents('form').submit();
                        }
                    });  
                } 
            });
        })

        $('.form_pesquisa').on('click', '.printRecibo', function(){
            id = $(this).attr('data-id');

            $.ajax("{{ route('instituicao.contasPagar.printRecibo', ['conta' => 'contaPagarId']) }}".replace('contaPagarId', id), {
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