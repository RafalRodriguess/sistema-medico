<div id="modalVisualizar" class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detalhes do orçamento aprovado</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="orcamento_id" id="orcamento_id" value="{{($orcamento) ? $orcamento->id : ''}}">
        
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#itens_modal_aprovado" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Itens Aprovado</span></a> </li>
                    <li class="nav-item"> <a class="nav-link" id="financeiro_modal_aprovado_tab" data-toggle="tab" href="#financeiro_modal_aprovado" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Financeiro</span></a> </li>
                    <li class="nav-item"> <a class="nav-link" id="detalhes_aprovado_tab" data-toggle="tab" href="#detalhes_aprovado" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Detalhes</span></a> </li>
                    @can('habilidade_instituicao_sessao', 'visualizar_laboratorio_odontologico')
                        <li class="nav-item"> <a class="nav-link" id="laboratorio_modal_editar_tab" data-toggle="tab" href="#laboratorio_modal_editar" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Laboratório</span></a> </li>
                    @endcan
                </ul>
                <div class="tab-content tabcontent-border">
                    
                    <div class="tab-pane p-20 active" id="itens_modal_aprovado" role="tabpanel">
                        <table class="table color-table info-table">
                            <thead>
                                <tr>
                                    <th>Dentes</th>
                                    <th>Procedimentos</th>
                                    <th>Regiões</th>
                                    <th>Beneficiário</th>
                                    <th>Dt. Conclusão</th>
                                    <th>Valor</th>
                                    <th>Valor desconto/acréscimo</th>
                                    <th>Desconto</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // $totalItens = count($orcamento->itens);
                                    // $valorDescontoIten = 0;
                                    // if($totalItens > 0){
                                    //     $valorDescontoIten = $orcamento->desconto / $totalItens;
                                    // }
                                    
                                @endphp
                                @foreach ($orcamento->itens as $item)
                                    @php
                                        $valorDescontoIten = $item->desconto;
                                    @endphp
                                    <tr>
                                        <td>{{$item->dente_id}}</td>
                                        <td>{{$item->procedimentos->procedimentoInstituicao->procedimento->descricao}}</td>
                                        <td>
                                            @if ($item->regiao)
                                                {{($item->regiao) ? $item->regiao->descricao : ''}}
                                            @elseif(count($item->regiaoProcedimento) > 0)
                                                @foreach ($item->regiaoProcedimento as $keyR => $regiao)
                                                    @if ($keyR == 0)
                                                        {{$regiao->descricao}}
                                                    @else
                                                            / {{$regiao->descricao}}
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>{{($item->prestador) ? $item->prestador->nome : ''}}</td>
                                        <td>{{($item->data_conclusao) ? $item->data_conclusao->format('d/m/Y') : ''}}</td>
                                        <td>R$ {{number_format($item->valor, 2, ',','.')}}</td>
                                        <td>R$ {{number_format($valorDescontoIten, 2,',','.')}}</td>
                                        <td></td>
                                        <td>R$ {{number_format(($item->valor + $valorDescontoIten), 2,',','.')}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>Total</td>
                                    <td>R$ {{number_format($orcamento->itens->sum('valor'), 2,',','.')}}</td>
                                    <td></td>
                                    <td>R$ -{{number_format($orcamento->desconto, 2,',','.')}}</td>
                                    <td>R$ {{number_format(($orcamento->valor_aprovado - $orcamento->desconto), 2,',','.')}}</td>
                                    
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="tab-pane p-20" id="financeiro_modal_aprovado" role="tabpanel">
                        <table class="table color-table info-table">
                            <thead>
                                <tr>
                                    <th>Descrição</th>
                                    <th>Conta</th>
                                    <th>Vencimento</th>
                                    <th>Pagamento</th>
                                    <th>Valor à pagar</th>
                                    <th>Valor pago</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orcamento->contaReceber as $item)
                                    <tr>
                                        <td>{{$item->descricao}}</td>
                                        <td>{{$item->contaCaixa->descricao}}</td>
                                        <td>{{date('d/m/Y', strtotime($item->data_vencimento))}}</td>
                                        <td>{{($item->status == 1) ? date('d/m/Y', strtotime($item->data_pago)) : ''}}</td>
                                        <td>R$ {{number_format($item->valor_parcela, 2, ',','.')}}</td>
                                        <td>{{($item->status == 1) ? 'R$ '.number_format($item->valor_pago, 2, ',','.') : ''}}</td>
                                        <td>{{($item->status == 1) ? 'Pago' : 'Não pago'}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>Total</td>
                                    <td>R$ {{number_format($orcamento->contaReceber->sum('valor_parcela'), 2,',','.')}}</td>
                                    <td>R$ {{number_format($orcamento->contaReceber->sum('valor_pago'), 2,',','.')}}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="tab-pane p-20" id="detalhes_aprovado" role="tabpanel">
                        <table class="table color-table info-table">
                            <thead>
                                <tr>
                                    <th>Responsavel</th>
                                    <th>Negociador</th>
                                    <th>Criado em</th>
                                    <th>Aprovado em</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$orcamento->responsavel->nome}}</td>
                                    <td>{{$orcamento->negociador->nome}}</td>
                                    <td>{{date('d/m/Y', strtotime($orcamento->created_at))}}</td>
                                    <td>{{date('d/m/Y', strtotime($orcamento->data_aprovacao))}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane p-20" id="laboratorio_modal_editar" role="tabpanel">
                        @include('instituicao/prontuarios/odontologicos/laboratorio_editar')
                    </div>
                    
                </div>
                
            </div>
            <div class="modal-footer">
                {{-- <div class="row"> --}}
                    <div class="form-groupn col-md-6 text-left pb-2">
                        <button type="button" class="btn btn-secondary waves-effect" onclick="liberaImprimirOdontologico({{$orcamento->id}})">Imprimir tudo</button>
                        <button type="button" class="btn btn-secondary waves-effect waves-light" onclick="liberaImprimirOdontologicoTotal({{$orcamento->id}})">Imprimir somente total</button>
                    </div>
                    <div class="form-groupn col-md-6 text-right pb-2">
                        @can('habilidade_instituicao_sessao', 'emitir_boleto')
                            <button class="btn btn-outline-secondary waves-effect waves-light emitir_boleto" data-id="{{$orcamento->id}}" type="button"><span class="btn-label"><i class="mdi mdi-barcode"></i></span>Emitir boletos</button>
                        @endcan
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Fechar</button>
                    </div>
                {{-- </div> --}}
            </div>
        </div>
    </div>
</div>

<script>
    var $table_lab = $("#laboratorio_modal_editar").find('#table_lab');
    $(document).ready(function(){
        $(".valor_lab").setMask()
        setTimeout(() => {
            $table_lab.bootstrapTable().on('check.bs.table', function (e, row) {
                // console.log(row)
                // checkedRows.push({valor: row._valor_data['valor'], id: row._dentes_data['id']});
                // setaValoresCheckRow()
                // calculaTotalCriado()
            })
            $(".lab_state_table_head").css('width', '1%');
            $(".lab_dentes_table_head").css('width', '19%');
            $(".lab_procedimento_table_head").css('width', '20%');
            $(".lab_regiao_table_head").css('width', '20%');
            $(".lab_desconto_table_head").css('width', '20%');
            $(".lab_valor_table_head").css('width', '20%');
            $(".valor_lab").setMask();
        }, 500);
    })
    
    $(".emitir_boleto").on('click', function(){
        ondonto_id = $(this).attr('data-id');

        url = "{{route('instituicao.odontologico.geraBoelto', ['orcamento' => 'orcamento_id'])}}".replace('orcamento_id', ondonto_id);

        window.open(url, '_blank')
    })
</script>