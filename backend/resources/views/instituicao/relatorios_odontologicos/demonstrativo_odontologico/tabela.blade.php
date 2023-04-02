@php
    $contaPagarTotal = 0;
@endphp
@can('habilidade_instituicao_sessao', 'visualizar_contas_pagar_relatorio_demonstrativo_odontologico')
    @if (count($contasPagar) > 0)
        <table id="demo-foo-row-toggler" class="table table-bordered" data-toggle-column="first" style="margin: 0px 0px 35px 0px;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Descrição</th>
                    <th>Conta</th>
                    <th>Plano de Conta</th>
                    <th>Data Vencimento</th>
                    <th>Data Pagamento</th>
                    <th>Valor Pago</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contasPagar as $item)
                    @php
                        $contaPagarTotal += $item->valor_pago;
                    @endphp
                    <tr style="background: #ff00002e">
                        <td>{{$item->id}}</td>
                        <td>{{$item->descricao}}</td>
                        <td>{{($item->contaCaixa) ? $item->contaCaixa->descricao : "-"}}</td>
                        <td>{{($item->planoConta) ? $item->planoConta->descricao : "-"}}</td>
                        <td>{{date('d/m/Y', strtotime($item->data_vencimento))}}</td>
                        <td>{{date('d/m/Y', strtotime($item->data_pago))}}</td>
                        <td>{{number_format($item->valor_pago,2,',','.')}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endcan

<table id="demo-foo-row-toggler" class="table table-bordered" data-toggle-column="first" style="margin: 0px">
    <thead>
        <tr>
            <th >Avaliador</th>
            <th >Negociador</th>
            <th >Paciente</th>            
            <th >Convênios</th>
            <th >Forma de Pagamento</th>
            <th >Nº de Parcelas</th>
            <th >Recebido </th>
            <th >A Receber </th>
            <th >Total </th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
            $total_receber = 0;
            $formas_pagamento = [];
            $convenios = [];
        @endphp
        @foreach($orcamentos as $item)
            @php
                $convenios = [];
            @endphp
            @if (count($item->parcelas) > 1)    
                <tr>
                    <td rowspan="{{count($item->parcelas)}}">@if ($item->avaliador) {{$item->avaliador->nome}} @endif</td>
                    <td rowspan="{{count($item->parcelas)}}">{{$item->negociador->nome}}</td>
                    <td rowspan="{{count($item->parcelas)}}">{{$item->paciente->nome}}</td>
                    <td rowspan="{{count($item->parcelas)}}">
                        @foreach ($item->itens as $key => $iten)
                            @if (!empty($iten->procedimentos))   
                                @if (!in_array($iten->procedimentos->conveniosTrashed->nome, $convenios))
                                    @if ($key == 0)
                                        {{$iten->procedimentos->conveniosTrashed->nome}}
                                    @else
                                        <br> {{$iten->procedimentos->conveniosTrashed->nome}}
                                    @endif
                                @endif
                                @php
                                    $convenios[] = $iten->procedimentos->conveniosTrashed->nome;
                                @endphp
                            @endif
                            
                        @endforeach
                    </td>
                    <td>{{App\ContaPagar::forma_pagamento_texto($item->parcelas[0]->forma_pagamento)}}</td>
                    <td>{{$item->parcelas[0]->qtd_parcelas}} x</td>
                    <td>R$ {{number_format($item->parcelas[0]->valor_parcela, 2,',','.')}} 
                        @php
                            $total += $item->parcelas[0]->valor_parcela;
                            if (!array_key_exists($item->parcelas[0]->forma_pagamento, $formas_pagamento)) {
                                $formas_pagamento[$item->parcelas[0]->forma_pagamento] = [
                                    'descricao' => App\ContaPagar::forma_pagamento_texto($item->parcelas[0]->forma_pagamento),
                                    'valor' => $item->parcelas[0]->valor_parcela,
                                ];  
                            }else{
                                $formas_pagamento[$item->parcelas[0]->forma_pagamento]['valor'] += $item->parcelas[0]->valor_parcela;
                            }
                        @endphp
                    </td>
                </tr>
                @for ($i = 1; $i < count($item->parcelas); $i++)
                    <td>{{App\ContaPagar::forma_pagamento_texto($item->parcelas[$i]->forma_pagamento)}}</td>
                    <td>{{$item->parcelas[$i]->qtd_parcelas}} x</td>
                    <td>R$ {{number_format($item->parcelas[$i]->valor_parcela, 2,',','.')}} 
                        @php
                            $total += $item->parcelas[$i]->valor_parcela;
                            if (!array_key_exists($item->parcelas[$i]->forma_pagamento, $formas_pagamento)) {
                                $formas_pagamento[$item->parcelas[$i]->forma_pagamento] = [
                                    'descricao' => App\ContaPagar::forma_pagamento_texto($item->parcelas[$i]->forma_pagamento),
                                    'valor' => $item->parcelas[$i]->valor_parcela,
                                ];  
                            }else{
                                $formas_pagamento[$item->parcelas[$i]->forma_pagamento]['valor'] += $item->parcelas[$i]->valor_parcela;
                            }
                        @endphp
                    </td>
                @endfor
            @else
            <tr>
                <td>@if ($item->avaliador) {{$item->avaliador->nome}} @endif</td>
                <td>{{$item->negociador->nome}}</td>
                <td>{{$item->paciente->nome}}</td>
                <td>
                    @foreach ($item->itens as $key => $iten)
                        @if (!empty($iten->procedimentos))   
                            @if (!in_array($iten->procedimentos->conveniosTrashed->nome, $convenios))
                                @if ($key == 0)
                                    {{$iten->procedimentos->conveniosTrashed->nome}}
                                @else
                                    <br> {{$iten->procedimentos->conveniosTrashed->nome}}
                                @endif
                            @endif
                            @php
                                $convenios[] = $iten->procedimentos->conveniosTrashed->nome;
                            @endphp
                        @endif
                    @endforeach
                </td>
                <td>{{App\ContaPagar::forma_pagamento_texto($item->total[0]->forma_pagamento)}}</td>
                <td>{{$item->total[0]->qtd_parcelas}} x</td>
                @if (count($item->parcelas) > 0)    
                    <td>R$ {{number_format($item->parcelas[0]->valor_parcela, 2,',','.')}} 
                        @php
                            $total += $item->parcelas[0]->valor_parcela;
                            if (!array_key_exists($item->parcelas[0]->forma_pagamento, $formas_pagamento)) {
                                $formas_pagamento[$item->parcelas[0]->forma_pagamento] = [
                                    'descricao' => App\ContaPagar::forma_pagamento_texto($item->parcelas[0]->forma_pagamento),
                                    'valor' => $item->parcelas[0]->valor_parcela,
                                ];  
                            }else{
                                if (array_key_exists('valor', $formas_pagamento[$item->parcelas[0]->forma_pagamento])) {
                                    $formas_pagamento[$item->parcelas[0]->forma_pagamento]['valor'] += $item->parcelas[0]->valor_parcela;
                                }else{
                                    $formas_pagamento[$item->parcelas[0]->forma_pagamento]['valor'] = $item->parcelas[0]->valor_parcela;
                                }
                                
                            }
                            $total_pagas = $item->parcelas[0]->valor_parcela;
                        @endphp
                    </td>
                @else
                    <td>R$ 0,00</td>
                    @php
                        $total_pagas = 0;
                    @endphp
                @endif
                @if (count($item->parcelasReceber) > 0)    
                    <td>R$ {{number_format($item->parcelasReceber[0]->valor_parcela, 2,',','.')}} 
                        @php
                            $total_receber += $item->parcelasReceber[0]->valor_parcela;
                            if (!array_key_exists($item->parcelasReceber[0]->forma_pagamento, $formas_pagamento)) {
                                $formas_pagamento[$item->parcelasReceber[0]->forma_pagamento] = [
                                    'descricao' => App\ContaPagar::forma_pagamento_texto($item->parcelasReceber[0]->forma_pagamento),
                                    'valor_receber' => $item->parcelasReceber[0]->valor_parcela,
                                ];  
                            }else{
                                if (array_key_exists('valor_receber', $formas_pagamento[$item->parcelasReceber[0]->forma_pagamento])) {
                                    $formas_pagamento[$item->parcelasReceber[0]->forma_pagamento]['valor_receber'] += $item->parcelasReceber[0]->valor_parcela;
                                }else{
                                    $formas_pagamento[$item->parcelasReceber[0]->forma_pagamento]['valor_receber'] = $item->parcelasReceber[0]->valor_parcela;
                                }
                            }
                        @endphp
                    </td>
                    <td>
                        R$ {{number_format(($item->parcelasReceber[0]->valor_parcela + $total_pagas), 2,',','.')}} 
                    </td>
                @else
                    <td>R$ 0,00 </td>
                    <td>
                        R$ {{number_format($total_pagas, 2,',','.')}} 
                    </td>
                @endif
            </tr>
            @endif
        @endforeach
    </tbody>
    <tfoot>
        @foreach ($formas_pagamento as $value)    
            <tr>
                <td colspan="6" style="text-align: right"><b>{{$value['descricao']}}</b></td>
                <td><b>R$ 
                    @if (array_key_exists('valor', $value))
                        {{number_format($value['valor'], 2, ',', '.')}}
                        @php
                            $valor = $value['valor'];
                        @endphp
                    @else
                        0,00
                        @php
                            $valor = 0;
                        @endphp
                    @endif
                    </b></td>
                <td><b>R$ {{(array_key_exists('valor_receber', $value)) ? number_format($value['valor_receber'], 2, ',', '.') : "0,00"}}</b></td>
                @if (array_key_exists('valor_receber', $value))
                    <td>R$ {{number_format($valor + $value['valor_receber'], 2, ',', '.')}}</td>
                @else
                    <td>R$ {{number_format($valor, 2, ',', '.')}}</td>
                @endif
            </tr>
        @endforeach
        <tr>
            <td colspan="6" style="text-align: right"><b>Total</b></td>
            <td><b>R$ {{number_format($total, 2, ',', '.')}}</b></td>
            <td><b>R$ {{number_format($total_receber, 2, ',', '.')}}</b></td>
            <td><b>R$ {{number_format($total_receber+$total, 2, ',', '.')}}</b></td>
        </tr>
        @can('habilidade_instituicao_sessao', 'visualizar_contas_pagar_relatorio_demonstrativo_odontologico')
            @if (count($contasPagar) > 0)
                <tr>
                    <td colspan="6" style="text-align: right"><b>Total Pago</b></td>
                    <td></td>
                    <td></td>
                    <td><b>R$ - {{number_format($contaPagarTotal, 2, ',', '.')}}</b></td>
                </tr>
            @endif
        @endcan
        <tr>
            <td colspan="6" style="text-align: right"><b>Total Geral</b></td>
            <td></td>
            <td></td>
            <td><b>R$ {{number_format((($total+$total_receber) - $contaPagarTotal), 2, ',', '.')}}</b></td>
        </tr>
        
    </tfoot>
</table>