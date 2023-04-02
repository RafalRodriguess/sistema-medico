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
                    <input class="colunaTabela" type="checkbox" id="exibeValorCusto"/>
                    <label for="exibeValorCusto"> Valor Custo</label>
                </div>

                <div class="col-md-2">
                    <input class="colunaTabela" type="checkbox" id="exibeTipoRepasse"/>
                    <label for="exibeTipoRepasse"> Tipo repasse</label>
                </div>

                <div class="col-md-2">
                    <input class="colunaTabela" type="checkbox" id="exibeSemDesconto"/>
                    <label for="exibeSemDesconto"> Repasse s desconto</label>
                </div>

                <div class="col-md-2">
                    <input class="colunaTabela" type="checkbox" id="exibeComDesconto"/>
                    <label for="exibeComDesconto"> Repasse c desconto</label>
                </div>
            </div>
        </div>
    </div>
</div>
<small style="float: right">
    <p style="margin: 0px">*** S: R$ 00,00, valor sem desconto</p>
    <p style="margin: 0px">*** D: R$ 00,00, desconto</p>
    <p style="margin: 0px">*** C: R$ 00,00, valor com desconto</p>
</small>
<table id="demo-foo-row-toggler" class="table table-bordered" data-toggle-column="first" style="margin: 0px">
    <thead>
        <tr>
            <th >Paciente</th>
            <th >Prestador</th>
            <th >Procedimento</th>            
            <th >Data Conclusão</th>
            <th >Dente</th>
            <th >Forma de pagamento</th>
            <th >Valor Procedimento</th>
            <th >Valor Laboratório</th>
            <th >Valor Convênio</th>
            <th class="valorCusto" style="display: none">Valor Custo</th>
            <th class="tipoRepasse" style="display: none">Tipo Repasse</th>
            <th class="colunaSemDesconto" style="display: none">Repasse s desconto</th>
            <th class="colunaComDesconto" style="display: none">Repasse c desconto</th>
            <th >Total</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total_procedimentos = 0;
            $total_convenio = 0;
            $repasse_s_desconto = 0;
            $repasse_c_desconto = 0;
            $total_s = 0;
            $total_d = 0;
            $total_c = 0;
            $total_v_custo = 0;
            @endphp
        @foreach($itens as $item)
            @php
                $valor_item = $item->valor + $item->desconto;
                $total_procedimentos += $valor_item;
                $total_convenio += $item->valor_convenio;
                $total_v_custo += $item->valor_custo;
                $valor_repasse_s_desconto = 0;
                $valor_repasse_c_desconto = 0;
            @endphp
            <tr>
                <td>{{$item->odontologico->paciente->nome}}</td>
                <td>{{$item->prestador->nome}}</td>
                <td>{{$item->procedimentosItens->descricao}}</td>
                <td>{{$item->data_conclusao->format('d/m/Y')}}</td>
                <td>{{$item->dente_id}}</td>
                <td>
                    {{-- {{count($item->odontologico->contaReceber)}} --}}
                    @if (count($item->odontologico->contaReceber) > 0)
                        @php
                            $contas = [];
                            foreach ($item->odontologico->contaReceber as $key => $value) {
                                if(array_key_exists($value->forma_pagamento, $contas)){
                                    $contas[$value->forma_pagamento]['qtd']++; 
                                }else{
                                    $contas[$value->forma_pagamento] = [
                                        'forma_pagamento' => App\ContaPagar::forma_pagamento_texto($value->forma_pagamento),
                                        'qtd' => 1,
                                    ];
                                }
                            }
                        @endphp
                        @foreach ($contas as $conta)
                            <p>{{$conta['qtd']}}x {{$conta['forma_pagamento']}}</p>
                        @endforeach
                    @else
                        -
                    @endif
                </td>
                <td>R$ {{number_format($valor_item, 2, ',','.')}}</td>
                <td>R$ {{number_format($item->laboratorio, 2, ',','.')}}</td>
                <td>R$ {{number_format($item->valor_convenio, 2, ',','.')}}</td>
                <td class="valorCusto" style="display: none">R$ {{number_format($item->valor_custo, 2, ',','.')}}</td>
                <td class="tipoRepasse" style="display: none">
                    @if ($item->tipo)
                        {{$item->tipo}}
                    @endif
                </td>
                <td class="colunaSemDesconto" style="display: none">
                    @if ($item->tipo)
                        @if ($item->tipo == 'dinheiro')
                                @php
                                    $valor_repasse_s_desconto = $item->valor_repasse;
                                @endphp
                                R$ {{number_format($item->valor_repasse, 2, ',','.')}}
                            @else
                                @php
                                    $valor_repasse_s_desconto = ((($valor_item-$item->laboratorio)+$item->valor_convenio)*($item->valor_repasse/100));
                                @endphp
                                {{number_format($item->valor_repasse, 2)}} % (R$ {{number_format(((($valor_item-$item->laboratorio)+$item->valor_convenio)*($item->valor_repasse/100)), 2, ',','.')}})
                        @endif
                        
                    @endif
                </td>
                <td class="colunaComDesconto" style="display: none">
                    @if ($item->tipo)
                        @if ($item->tipo == 'dinheiro')
                            @php
                                $valor_repasse_real = 0;
                                if($item->valor_repasse>0){
                                    $porcento_repasse = (($item->valor_repasse * 100)/(($valor_item-$item->laboratorio)+$item->valor_convenio));
                                    $valor_novo_procedimento = ($valor_item-$item->laboratorio)+$item->valor_convenio-($item->desconto_total+$item->valor_custo);
                                    $valor_repasse_real = $valor_novo_procedimento*$porcento_repasse/100;
                                    // $valor_repasse_novo = $item->valor_repasse - $valor_repasse_real;
                                }
                            @endphp
                                R$ {{number_format(($valor_repasse_real), 2, ',','.')}}
                            @php
                                $valor_repasse_c_desconto = $valor_repasse_real;
                            @endphp
                        @else
                            {{number_format($item->valor_repasse, 2)}} % (R$ {{number_format(((($valor_item-$item->laboratorio)+$item->valor_convenio-($item->desconto_total+$item->valor_custo))*($item->valor_repasse/100)), 2, ',','.')}})
                            @php
                                $valor_repasse_c_desconto = ((($valor_item-$item->laboratorio)+$item->valor_convenio-($item->desconto_total+$item->valor_custo))*($item->valor_repasse/100));
                            @endphp
                        @endif
                    @endif
                </td>
                <td>
                    S: R$ {{number_format((($valor_item-$item->laboratorio)+$item->valor_convenio), 2, ',', '.')}}
                    @if ($item->desconto_total > 0 || $item->valor_custo > 0)
                        <p style="margin: 0px">D: R$ {{number_format($item->desconto_total+$item->valor_custo, 2, ',', '.')}}</p>
                        <p style="margin: 0px">C: R$ {{number_format(((($valor_item-$item->laboratorio)+$item->valor_convenio) - ($item->desconto_total+$item->valor_custo)), 2, ',', '.')}}</p>
                    @endif
                    @php
                        $total_s += (($valor_item-$item->laboratorio)+$item->valor_convenio);
                        $total_d += $item->desconto_total+$item->valor_custo;
                        $total_c += ((($valor_item-$item->laboratorio)+$item->valor_convenio) - $item->desconto_total);
                    @endphp
                </td>
            </tr>
            @php
                $repasse_s_desconto += $valor_repasse_s_desconto;
                $repasse_c_desconto += $valor_repasse_c_desconto;
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6" style="text-align: right"><b>Totais</b></td>    
            <td>R$ {{number_format($total_procedimentos, 2, ',', '.')}}</td>
            <td>R$ {{number_format($total_convenio, 2, ',', '.')}}</td>
            <td>R$ {{number_format($total_v_custo, 2, ',', '.')}}</td>
            <td class="valorCusto" style="display: none"></td>
            <td class="tipoRepasse" style="display: none"></td>
            <td class="colunaSemDesconto" style="display: none">R$ {{number_format($repasse_s_desconto, 2, ',', '.')}}</td>
            <td class="colunaComDesconto" style="display: none">R$ {{number_format($repasse_c_desconto, 2, ',', '.')}}</td>
            <td>
                S: R$ {{number_format($total_s, 2, ',', '.')}}
                @if ($total_d > 0)
                    <p style="margin: 0px">D: R$ {{number_format($total_d, 2, ',', '.')}}</p>
                    <p style="margin: 0px">C: R$ {{number_format($total_c, 2, ',', '.')}}</p>
                @endif
            </td>
        </tr>
        
    </tfoot>
</table>

<script>
    $(document).ready(function() {

        $('[data-toggle="tooltip"]').tooltip()

        $("#exibeSemDesconto").on('change', function(){
            
            if($(this).is(':checked')){
                $(".colunaSemDesconto").css("display", "")
            }else{
                $(".colunaSemDesconto").css("display", "none")
            }
        })

        $("#exibeComDesconto").on('change', function(){
            if($(this).is(':checked')){
                $(".colunaComDesconto").css("display", "")
            }else{
                $(".colunaComDesconto").css("display", "none")
            }
        })
        
        $("#exibeTipoRepasse").on('change', function(){
            if($(this).is(':checked')){
                $(".tipoRepasse").css("display", "")
            }else{
                $(".tipoRepasse").css("display", "none")
            }
        })
        
        $("#exibeValorCusto").on('change', function(){
            if($(this).is(':checked')){
                $(".valorCusto").css("display", "")
            }else{
                $(".valorCusto").css("display", "none")
            }
        })
    });    
</script>