@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar Conta a Pagar #{$contaPagar->id}",
        'breadcrumb' => [
            'Contas a Pagar' => route('instituicao.contasPagar.index'),
            'Editar',
        ],
    ])
    @endcomponent

    <div class="card">
        <div class="card-body">
            <form action="{{ route('instituicao.contasPagar.update', [$contaPagar]) }}" method="post">
                @method('put')
                @csrf

                <div class="row">
                    <div class="form-group col-md-4 @if($errors->has('tipo')) has-danger @endif">
                        <label class="form-control-label">Tipo: *</span>
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Deve ser escolhido o fornecedor do serviço ou venda, caso não seja cadastrado, cadastrar o mesmo antes de inserir a conta"></i>
                        </label>
                        <input name="tipo" style="display: none" id="tipo" value="{{$contaPagar->tipo}}">
                        <select name="tipo_texto" class="form-control @if($errors->has('tipo')) form-control-danger @endif" style="width: 100%" id="tipo_texto" disabled>
                            @foreach ($tipos as $item)
                                <option value="{{$item}}" @if (old('tipo', $contaPagar->tipo) == $item)
                                    selected="selected"
                                @endif>{{App\ContaPagar::tipos_texto_all($item)}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('tipo'))
                            <div class="form-control-feedback">{{ $errors->first('tipo') }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-8 pessoas @if($errors->has('pessoa_id')) has-danger @endif" style="display: none">
                        <label class="form-control-label">Paciente: *</span></label>
                        <input name="pessoa_id" style="display: none" id="pessoa_id" value="{{$contaPagar->pessoa_id}}">
                        <input name="pessoa_nome" disabled class="form-control @if($errors->has('pessoa_id')) form-control-danger @endif" style="width: 100%" id="pessoa_nome" value="{{!empty($pessoa) ? $pessoa->nome : ""}}">
                        @if($errors->has('pessoa_id'))
                            <div class="form-control-feedback">{{ $errors->first('pessoa_id') }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-8 prestadores @if($errors->has('prestador_id')) has-danger @endif" style="display: none">
                        <label class="form-control-label">Prestadores: *</span></label>
                        <input name="prestador_id" style="display: none" id="prestador_id" value="{{$contaPagar->prestador_id}}">
                        <input name="prestador_nome" disabled class="form-control @if($errors->has('pessoa_id')) form-control-danger @endif" style="width: 100%" id="prestador_nome" value="{{(!empty($prestador)) ? $prestador->nome : ""}}">

                        @if($errors->has('prestador_id'))
                            <div class="form-control-feedback">{{ $errors->first('prestador_id') }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-8 fornecedores @if($errors->has('pessoa_id')) has-danger @endif" style="display: none">
                        <label class="form-control-label">Fornecedores: *</span></label>
                        <input name="pessoa_id" style="display: none" id="pessoa_id" value="{{$contaPagar->pessoa_id}}">
                        <input name="pessoa_nome" disabled class="form-control @if($errors->has('pessoa_id')) form-control-danger @endif" style="width: 100%" id="pessoa_nome" value="{{(!empty($fornecedor)) ? $fornecedor->nome_fantasia ? $fornecedor->nome_fantasia : $fornecedor->nome : ""}}">
                        @if($errors->has('pessoa_id'))
                            <div class="form-control-feedback">{{ $errors->first('pessoa_id') }}</div>
                        @endif
                        @if($errors->has('pessoa_id'))
                            <div class="form-control-feedback">{{ $errors->first('pessoa_id') }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-12">
                        <label class="form-control-label">Descrição *:
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Caso queira detalhar melhor o seu pagamento"></i>
                        </label>
                        <input type="text" name="descricao" class="form-control" value="{{old('descricao', $contaPagar->descricao)}}">
                    </div>

                    <div class="form-group col-md-2">
                        <label class="form-control-label">Nº documento:
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Caso tenha número do boleto, número da nota, etc..."></i>
                        </label>
                        <input type="text" name="numero_doc" class="form-control" value="{{old('numero_doc', $contaPagar->numero_doc)}}">
                    </div>

                    <div class="form-group col-md-2 @if($errors->has('data_vencimento')) has-danger @endif">
                        <label class="form-control-label">Data vencimento: *
                            {{-- <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Data em que vence a compra ou despesa"></i> --}}
                        </label>
                        <input type="date" name="data_vencimento" id="data_vencimento" class="form-control @if ($errors->has('data_vencimento')) form-control-danger @endif" value="{{old('data_vencimento', $contaPagar->data_vencimento)}}">
                        @if ($errors->has('data_vencimento'))
                            <div class="form-control-feedback">{{$errors->first('data_vencimento')}}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-2 @if($errors->has('total')) has-danger @endif">
                        <label class="form-control-label">Valor Total: *
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Data em que vence a compra ou despesa"></i>
                        </label>
                        <input type="text" alt="decimal" name="total" id="total" class="form-control @if ($errors->has('total')) form-control-danger @endif" value="{{old('total', $contaPagar->total)}}" onchange="totalCC(this)">
                        @if ($errors->has('total'))
                            <div class="form-control-feedback">{{$errors->first('total')}}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-2 @if($errors->has('valor_parcela')) has-danger @endif">
                        <label class="form-control-label">Valor Parcela: *
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Data em que vence a compra ou despesa"></i>
                        </label>
                        <input type="text" alt="decimal" name="valor_parcela" id="valor_parcela" class="form-control @if ($errors->has('valor_parcela')) form-control-danger @endif" value="{{old('valor_parcela', $contaPagar->valor_parcela)}}">
                        @if ($errors->has('valor_parcela'))
                            <div class="form-control-feedback">{{$errors->first('valor_parcela')}}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-2 @if($errors->has('data_emissao_nf')) has-danger @endif">
                        <label class="form-control-label">Data emissão NF:
                            {{-- <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Data em que vence a compra ou despesa"></i> --}}
                        </label>
                        <input type="date" name="data_emissao_nf" id="data_emissao_nf" class="form-control @if ($errors->has('data_emissao_nf')) form-control-danger @endif" value="{{old('data_emissao_nf', $contaPagar->data_emissao_nf)}}">
                        @if ($errors->has('data_emissao_nf'))
                            <div class="form-control-feedback">{{$errors->first('data_emissao_nf')}}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-2" style="margin-top: 34px;">
                        <input type="checkbox" id="nf_imposto" value="1" name="nf_imposto" @if (old('nf_imposto', $contaPagar->nf_imposto) == 1)
                            checked=""
                        @endif class="filled-in" />
                        <label for="nf_imposto">NF com imposto<label>
                    </div>

                    <div class="form-group col-md-3 @if($errors->has('conta_id')) has-danger @endif">
                        <label class="form-control-label">Conta caixa:</span>
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Esta é a conta onde será debitada o dinheiro, se for pagamento na empresa por dinheiro 'Conta Caixa' e na conta no banco utilizando cheque ou pagamento online 'Conta Banco XX..."></i>
                        </label>
                        <select name="conta_id" class="form-control selectfild2 @if($errors->has('conta_id')) form-control-danger @endif" style="width: 100%">
                            <option value="">Selecione um caixa</option>
                            @foreach ($contas as $item)
                                <option value="{{$item->id}}" @if (old('conta_id', $contaPagar->conta_id) == $item->id)
                                    selected="selected"
                                @endif>{{$item->descricao}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('conta_id'))
                            <div class="form-control-feedback">{{ $errors->first('conta_id') }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-4 @if($errors->has('plano_conta_id')) has-danger @endif">
                        <label class="form-control-label">Plano de conta:</span>
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Este é o filtro por tipo de pagamento, que deve ser escolhido o plano exato que se associa ao pagamento"></i>
                        </label>
                        <select name="plano_conta_id" class="form-control selectfild2 @if($errors->has('plano_conta_id')) form-control-danger @endif" style="width: 100%">
                            <option value="">Selecione um plano de conta</option>
                            @foreach ($planosConta as $item)
                                <option value="{{$item->id}}" @if (old('plano_conta_id', $contaPagar->plano_conta_id) == $item->id)
                                    selected="selected"
                                @endif>{{$item->codigo}} - {{$item->descricao}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('plano_conta_id'))
                            <div class="form-control-feedback">{{ $errors->first('plano_conta_id') }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-4 @if($errors->has('forma_pagamento')) has-danger @endif">
                        <label class="form-control-label">Metodo de pagamento:</label>
                        <select class="form-control select_parcela selectfild2 @if($errors->has('forma_pagamento')) form-control-danger @endif" name="forma_pagamento" id="forma_pagamento" style="width: 100%">
                            <option value="">Selecione uma metodo de pagamento</option>
                            @foreach ($metodos_pagamento as $metodo)
                                <option value="{{$metodo}}" @if (old('forma_pagamento', $contaPagar->forma_pagamento) == $metodo)
                                    selected="selected"
                                @endif>{{ App\ContaPagar::forma_pagamento_texto($metodo)}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('forma_pagamento'))
                            <div class="form-control-feedback">{{$errors->first('forma_pagamento')}}</div>
                        @endif
                    </div>

                    <div class="col-md-12">
                        <div class="centro_custo row">

                            @include('instituicao.contas_pagar.centro_custo_editar')

                            <div class="form-group col-md-12 add-class" >
                                <span alt="default" class="add-cc fas fa-plus-circle">
                                    <a class="mytooltip" href="javascript:void(0)">
                                        <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar centro de custo"></i>
                                    </a>
                                </span>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">Total centro de custo</label>
                                <input class="form-control" alt="decimal" type="text" readonly id="centro_custo_total">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="cheque_data col-md-9" style="display: none">
                                <div class="row">
                                    <div class="form-group col-md-4 @if($errors->has('data_compensacao')) has-danger @endif">
                                        <label class="form-control-label">Data compensação: *
                                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Data em que a compra ou despesa ocorreu"></i>
                                        </label>
                                        <input type="date" name="data_compensacao" id="data_compensacao" class="form-control @if ($errors->has('data_compensacao')) form-control-danger @endif" value="{{old('data_compensacao', $contaPagar->data_compensacao)}}" disabled>
                                        @if ($errors->has('data_compensacao'))
                                            <div class="form-control-feedback">{{$errors->first('data_compensacao')}}</div>
                                        @endif
                                    </div>

                                    <div class="form-group col-md-4 @if($errors->has('titular')) has-danger @endif">
                                        <label class="form-control-label">Titular</label>
                                        <input class="form-control" type="text" name="titular" id="titular" value="{{old('titular', $contaPagar->titular)}}">
                                        @if($errors->has('titular'))
                                            <div class="form-control-feedback">{{ $errors->first('titular') }}</div>
                                        @endif
                                    </div>

                                    <div class="col-md-4 form-group @if($errors->has('numero_cheque')) has-danger @endif">
                                        <label for="form-control-label">Número do cheque</label>
                                        <input class="form-control" type="text" name="numero_cheque" id="numero_cheque" value="{{old('numero_cheque', $contaPagar->numero_cheque)}}">
                                        @if ($errors->has('numero_cheque'))
                                            <div class="form-control-feedback">{{ $errors->first('numero_cheque')}}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 banco_opcao_pix form-group @if($errors->has('chave_pix')) has-danger @endif" style="display: none">
                                <label for="form-control-label">Chave pix</label>
                                <input class="form-control" type="text" name="chave_pix" id="chave_pix" value="{{old('chave_pix', $contaPagar->chave_pix)}}">
                                @if ($errors->has('chave_pix'))
                                    <div class="form-control-feedback">{{ $errors->first('chave_pix')}}</div>
                                @endif
                            </div>

                            <div class="col-md-8 cartao_credito form-group @if($errors->has('cartao_credito_id')) has-danger @endif" style="display: none">

                                <label for="form-control-label">Cartão de crédito</label>
                                <select class="form-control select_parcela selectfild2 @if($errors->has('cartao_credito_id')) form-control-danger @endif" name="cartao_credito_id" id="cartao_credito_id" style="width: 100%">
                                    <option value="">Selecione um cartão de credito</option>
                                    @foreach ($cartoes as $item)
                                        <option value="{{$item->id}}"@if (old('cartao_credito_id', $contaPagar->cartao_credito_id) == $item->id)
                                            selected="selected"
                                        @endif>{{$item->descricao}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('cartao_credito_id'))
                                    <div class="form-control-feedback">{{ $errors->first('cartao_credito_id')}}</div>
                                @endif
                            </div>

                            <div class="col-md-4 cartao_credito form-group @if($errors->has('data_compra_cartao')) has-danger @endif" style="display: none">
                                <label for="form-control-label">Data da compra</label>
                                <input type="date" name="data_compra_cartao" id="data_compra_cartao" class="form-control @if ($errors->has('data_compra_cartao')) form-control-danger @endif" value="{{old('data_compra_cartao', date('Y-m-d'), $contaPagar->data_compra_cartao)}}">
                                @if ($errors->has('data_compra_cartao'))
                                    <div class="form-control-feedback">{{ $errors->first('data_compra_cartao')}}</div>
                                @endif
                            </div>

                            <div class="transferencia_bancaria col-md-6" style="display: none">
                                <div class="row">
                                    <div class="form-group col-md-6 @if($errors->has('conta')) has-danger @endif">
                                        <label class="form-control-label">Conta</label>
                                        <input class="form-control" type="text" name="conta" id="conta" value="{{old('conta', $contaPagar->conta)}}">
                                        @if($errors->has('conta'))
                                            <div class="form-control-feedback">{{ $errors->first('conta') }}</div>
                                        @endif
                                    </div>

                                    <div class="col-md-6 form-group @if($errors->has('agencia')) has-danger @endif">
                                        <label for="form-control-label">Agencia</label>
                                        <input class="form-control" type="text" name="agencia" id="agencia" value="{{old('agencia', $contaPagar->agencia)}}">
                                        @if ($errors->has('agencia'))
                                            <div class="form-control-feedback">{{ $errors->first('agencia')}}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 banco_opcao form-group @if($errors->has('banco')) has-danger @endif" style="display: none">
                                <label for="form-control-label">Banco</label>
                                <input class="form-control" type="text" name="banco" id="banco" value="{{old('banco', $contaPagar->banco)}}">
                                @if ($errors->has('banco'))
                                    <div class="form-control-feedback">{{ $errors->first('banco')}}</div>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="form-group col-md-12 @if($errors->has('obs')) has-danger @endif">
                        <label class="form-control-label">Observação: </label>
                        <textarea name="obs" id="obs" class="form-control" cols="5" rows="4">{{old('obs', $contaPagar->obs)}}</textarea>
                    </div>

                    @if ($contasPagar)
                        <div class="form-group form_pesquisa col-md-12">
                            <label class="form-control-label">Outras parcelas: </label>
                            <div class="table-responsive">
                                <table id="demo-foo-row-toggler" class="table table-bordered" data-toggle-column="first" >
                                    <thead>
                                        <tr>
                                            {{-- <th data-breakpoints="xs" hidden></th> --}}
                                            <th data-breakpoints="all">id</th>
                                            <th >Numero parcela</th>
                                            <th >Cotação</th>
                                            <th >Descrição</th>
                                            <th data-breakpoints="all">Caixa</th>
                                            <th data-breakpoints="all">Plano de conta</th>
                                            <th >Data vencimento</th>
                                            <th >Valor parcela</th>
                                            <th data-breakpoints="all">Status</th>
                                            <th >
                                                Data quitação
                                            </th>
                                            <th >Data compensação</th>
                                            <th >
                                                Valor pago
                                            </th>
                                            <th >Forma pagamento</th>
                                            <th data-breakpoints="all">Processada</th>
                                            <th >Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($contasPagar as $conta_pagar)
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

                                                @if ($conta_pagar->id == $contaPagar->id)
                                                    style="background: #a3a1a185"
                                                @endif

                                                class="id_{{$conta_pagar->id}}"
                                            >
                                                {{-- <td hidden></td> --}}
                                                <td>{{$conta_pagar->id}}</td>
                                                <td>
                                                    @if ($conta_pagar->cotacao == 1)
                                                        -
                                                    @else
                                                        {{$conta_pagar->num_parcela}}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($conta_pagar->cotacao == 1)
                                                        {{App\ContaPagar::status_cotacao_para_texto($conta_pagar->status_cotacao)}}
                                                    @else
                                                        Não
                                                    @endif
                                                </td>
                                                <td>{{$conta_pagar->descricao}}</td>
                                                <td class="conta_caixa">{{($conta_pagar->caixa) ? $conta_pagar->caixa->nome : '-' }}</td>
                                                <td>{{($conta_pagar->planoContaCaixa) ? $conta_pagar->planoContaCaixa->descricao : '-' }}</td>
                                                <td>
                                                    @if ($conta_pagar->cotacao == 1)
                                                        -
                                                    @else
                                                        {{date('d/m/Y', strtotime($conta_pagar->data_vencimento))}}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($conta_pagar->cotacao == 1)
                                                        -
                                                    @else
                                                        R$ {{number_format($conta_pagar->valor_parcela, 2, ',','.')}}
                                                    @endif
                                                </td>
                                                <td class="status">{{$conta_pagar->status == 0 ? '-' : 'pago'}}</td>
                                                <td class="quitacao">
                                                    @if ($conta_pagar->data_pago)
                                                        {{date('d/m/Y', strtotime($conta_pagar->data_pago))}}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="data_compensacao">
                                                    @if ($conta_pagar->data_compensacao)
                                                        {{date('d/m/Y', strtotime($conta_pagar->data_compensacao))}}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="pago">
                                                    @if ($conta_pagar->valor_pago)
                                                        R$ {{number_format($conta_pagar->valor_pago, 2, ',','.')}}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="forma_pagamento">
                                                    @if ($conta_pagar->cotacao == 1)
                                                        -
                                                    @else
                                                        {{($conta_pagar->forma_pagamento != null) ?App\ContaPagar::forma_pagamento_texto($conta_pagar->forma_pagamento) : '-' }}
                                                    @endif
                                                </td>
                                                <td class="processada">{{$conta_pagar->processada == 0 ? 'não' : 'sim'}}</td>
                                                <td>
                                                    @if ($conta_pagar->id != $contaPagar->id)
                                                        @can('habilidade_instituicao_sessao', 'editar_contas_pagar_financeiro')
                                                            <a href="{{ route('instituicao.contasPagar.edit', [$conta_pagar]) }}">
                                                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                                                            <i class="ti-pencil-alt"></i>
                                                                    </button>
                                                            </a>
                                                        @endcan


                                                        @can('habilidade_instituicao_sessao', 'pagar_contas_pagar_financeiro')
                                                            {{-- @if ($conta_pagar->status == 0) --}}
                                                                <button type="button" class="btn btn-xs btn-secondary modal_conta_pagar" aria-haspopup="true" aria-expanded="false"
                                                                data-toggle="tooltip" data-placement="top" data-original-title="pagar" data-id="{{$conta_pagar->id}}">
                                                                    <i class="ti-money"></i>
                                                                </button>
                                                            {{-- @endif         --}}
                                                        @endcan
                                                    @endif
                                                {{-- </a> --}}
                                                {{-- @endcan --}}
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
                        </div>
                    @endif

                </div>

                <div id="modal_pagar_visualizar"></div>

                <div class="form-group text-right">
                    <a href="{{ route('instituicao.contasPagar.index') }}">
                    <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10 salvar_form"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var quantidade_cc = 0;

        $(document).ready(function(){
            tipoValue();
            formaPagamentoCheque();
            quantidadeCC();
        })

        $("#cotacao").on('change', function(){
            cotacaoChange()
        })

        function formaPagamentoCheque(){
            if($("#forma_pagamento").val() == 'cheque'){
                $(".cheque_data").css('display', 'block');
                $(".banco_opcao").css('display', 'block');
                $("#data_compensacao").prop('disabled', false);
                $(".transferencia_bancaria").css('display', 'none');
                $(".banco_opcao_pix").css('display', 'none');
                $(".cartao_credito").css('display', 'none');

            }else if($("#forma_pagamento").val() == 'transferencia_bancaria'){
                $(".transferencia_bancaria").css('display', 'block');
                $(".banco_opcao").css('display', 'block');
                $(".cheque_data").css('display', 'none');
                $(".banco_opcao_pix").css('display', 'none');
                $("#data_compensacao").prop('disabled', true);
                $(".cartao_credito").css('display', 'none');

            }else if($("#forma_pagamento").val() == 'pix'){
                $(".transferencia_bancaria").css('display', 'block');
                $(".banco_opcao").css('display', 'block');
                $(".banco_opcao_pix").css('display', 'block');
                $(".cheque_data").css('display', 'none');
                $("#data_compensacao").prop('disabled', true);
                $(".cartao_credito").css('display', 'none');

            }else if($("#forma_pagamento").val() == 'cartao_credito'){
                $(".transferencia_bancaria").css('display', 'none');
                $(".banco_opcao").css('display', 'none');
                $(".banco_opcao_pix").css('display', 'none');
                $(".cheque_data").css('display', 'none');
                $("#data_compensacao").prop('disabled', true);
                $(".cartao_credito").css('display', 'block');
            }else{
                $(".cheque_data").css('display', 'none');
                $(".banco_opcao").css('display', 'none');
                $(".banco_opcao_pix").css('display', 'none');
                $("#data_compensacao").prop('disabled', true);
                $(".transferencia_bancaria").css('display', 'none');
                $(".cartao_credito").css('display', 'none');
            }
        }

        $("#forma_pagamento").on('change', function() {
            formaPagamentoCheque()
        })

        function tipoValue(){
            if($("#tipo").val() == 'paciente'){
                $(".pessoas").css('display', 'block');
                $(".fornecedores").css('display', 'none');
                $(".prestadores").css('display', 'none');
                $("#prestador_id").val('').change();
                $("#fornecedor_id").val('').change();
            }else if($("#tipo").val() == 'prestador'){
                $(".pessoas").css('display', 'none');
                $("#pessoa_id").val('').change();
                $("#fornecedor_id").val('').change();
                $(".prestadores").css('display', 'block');
                $(".fornecedores").css('display', 'none');
            }else if($("#tipo").val() == 'fornecedor'){
                $(".fornecedores").css('display', 'block');
                $("#pessoa_id").val('').change();
                $("#prestador_id").val('').change();
                $(".prestadores").css('display', 'none');
                $(".pessoas").css('display', 'none');
            }else if($("#tipo".val() == "movimentacao")){
                $(".fornecedores").css('display', 'none');
                $("#pessoa_id").val('').change();
                $("#prestador_id").val('').change();
                $(".prestadores").css('display', 'none');
                $(".pessoas").css('display', 'none');
            }
        }

        $("#tipo").on('change', function(){
            tipoValue();
        })

        $('.form_pesquisa').on('click', '.modal_conta_pagar', function(){
            id = $(this).data('id');

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

        $('#modal_pagar_visualizar').on('click', '.salvar_pagamento_parcela', function(e){
            e.preventDefault()

            parcela_id = $('#parcela_id_pagar').val()
            valor_recebido = $('#valor_pagar_parcela').val()
            data_pago = $('#data_pagamento_parcela').val()
            data_compensacao = $('#parcela_compensacao_pagar').val()
            obs = $('#parcela_obs_pagar').val()
            conta_caixa = $('#conta_id').val()
            conta_caixa_text = $('#conta_id option:selected').text()
            forma_pagamento_text = $('#forma_pagamento option:selected').text()
            forma_pagamento = $('#forma_pagamento').val()

            $('#modalPagarConta').modal('hide')

            $.ajax({
                type: "POST",
                data: {
                    valor_recebido: valor_recebido,
                    data_pago: data_pago,
                    data_compensacao: data_compensacao,
                    obs: obs,
                    forma_pagamento: forma_pagamento,
                    conta_id: conta_caixa,
                    status: 1,
                    "_token": "{{ csrf_token() }}"
                },
                url: "{{ route('instituicao.contasPagar.contaPagar', ['conta' => 'contaPagarId']) }}".replace('contaPagarId', parcela_id),

                beforeSend: () => {
                },
                success: (result) => {
                    $.toast({
                        heading: 'Sucesso',
                        text: 'Parcela paga com sucesso',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3000,
                        stack: 10
                    });
                    data_pago = data_pago.split('-');
                    $(".id_"+parcela_id).css('background','#54f75440');
                    $(".id_"+parcela_id).find('.status').html('pago');
                    $(".id_"+parcela_id).find('.quitacao').html(data_pago[2]+'/'+data_pago[1]+'/'+data_pago[0]);
                    $(".id_"+parcela_id).find('.pago').html('R$ '+valor_recebido);
                    $(".id_"+parcela_id).find('.conta_caixa').html(conta_caixa_text);
                    $(".id_"+parcela_id).find('.forma_pagamento').html(forma_pagamento_text);
                    if(data_compensacao){
                        data_compensacao = data_compensacao.split('-');
                        $(".id_"+parcela_id).find('.data_compensacao').html(data_compensacao[2]+'/'+data_compensacao[1]+'/'+data_compensacao[0]);
                    }
                },
                complete: () => {
                }
            })
        })

        function retornaFormatoValor(valor){
            var novo = valor;
            novo = novo.replace('.','')
            novo = novo.replace(',','.')
            return novo;
        }
    </script>
@endpush
