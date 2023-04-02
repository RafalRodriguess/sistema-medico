    <small style="float: right">
        <p style="margin: 0px">*** S: R$ 00,00, valor sem desconto</p>
        <p style="margin: 0px">*** D: R$ 00,00, desconto</p>
        <p style="margin: 0px">*** C: R$ 00,00, valor com desconto</p>
    </small>
    <table id="demo-foo-row-toggler" class="table table-bordered" data-toggle-column="first" style="margin: 0px">
        <thead>
            <tr>
                <th colspan="2">Profissional/agenda</th>
                <th colspan="2">Total de repasse</th>
            </tr>
        </thead>
        <tbody>
            <div class="accordion" >
                
                @foreach($agendamentos as $item)
                    <tr data-toggle="collapse" data-target="#collapse{{$item->id}}" aria-expanded="true" aria-controls="collapse{{$item->id}}" style="background: #80808036; ">
                        <td colspan="2">
                            {{$item->pessoa->nome}}
                        </td>
                        <td>{{$item->id}}</td>
                        <td colspan="2">{{date('d/m/Y H:i', strtotime($item->data))}}</td>
                        <td colspan="2">{{$item->instituicoesAgenda->prestadores->prestador->nome}}</td>
                        <td>{{$item->status}}</td>
                        <td>R$ {{number_format($item->desconto, 2, ',','.')}}</td>
                        
                    </tr>			
                    <div data-toggle-column="first" id="collapse{{$item->id}}" class="collapse show" aria-labelledby="heading{{$item->id}}" style="font-size: 14px">
                        <tr data-toggle-column="first" id="collapse{{$item->id}}" class="collapse show" aria-labelledby="heading{{$item->id}}" style="font-size: 14px">
                            <th >Convênio</th>
                            <th >Tipo pgto</th>            
                            <th >Serviço</th>
                            <th >Situação faturamento</th>
                            <th >Valor procedimento</th>
                            <th >Valor convênio</th>
                            <th >Tipo repasse</th>
                            <th >Repasse s desconto</th>
                            <th >Repasse c desconto</th>
                        </tr>
                        @php
                            $sub_total = 0;
                            $sub_total_desconto = 0;
                            $procedimentos_convenios = 0;
                            $procedimentos = 0;
                            $total_procedimento = $item->agendamentoProcedimento->sum('valor_atual') + $item->agendamentoProcedimento->sum('valor_convenio');
                            $desconto_porcentagem = 0;
                            $desconto_por_procedimentos = 0;
                            if($item->desconto > 0){
                                $desconto_porcentagem = (($item->desconto * 100)/$total_procedimento);
                                $desconto_por_procedimentos = $desconto_porcentagem/$item->agendamentoProcedimento->count();
                            }
                        @endphp
                        @foreach ($item->agendamentoProcedimento as $procedimento)
                            <tr data-toggle-column="first" id="collapse{{$item->id}}" class="collapse show" aria-labelledby="heading{{$item->id}}" style="font-size: 14px">
                                <td>{{$procedimento->procedimentoInstituicaoConvenioTrashed->convenios->nome}}</td>
                                <td>@if (count($item->contaReceber) > 0) {{App\ContaPagar::forma_pagamento_texto($item->contaReceber[0]->forma_pagamento)}} @endif</td>
                                <td>{{$procedimento->procedimentoInstituicaoConvenioTrashed->procedimentoInstituicao->procedimento->descricao}}</td>
                                <td>
                                    @if (count($item->contaReceber) > 0)
                                        {{App\ContaPagar::forma_pagamento_texto($item->contaReceber[0]->forma_pagamento)}}
                                        {{-- @if ($item->contaReceber[0]->forma_pagamento == 'cartao_credito')
                                            {{ucwords($item->status_pagamento)}}
                                        @elseif($item->contaReceber[0]->forma_pagamento == 'cartao_entrega')Cartão no dia
                                        @elseif($item->contaReceber[0]->forma_pagamento == 'dinheiro')Dinheiro no dia
                                        @endif --}}
                                    @endif
                                </td>
                                <td>R$ {{number_format($procedimento->valor_atual, 2, ',', '.')}}</td>
                                <td>R$ {{number_format($procedimento->valor_convenio, 2, ',', '.')}}</td>
                                @if ($procedimento->tipo != null)
                                    <td>{{ucwords($procedimento->tipo)}}</td>
                                @else
                                    <td>-</td>
                                @endif
                                @if ($procedimento->tipo != null)
                                    <td>@if($procedimento->tipo == 'porcentagem') {{$procedimento->valor_repasse}} % (R$ {{(number_format(($procedimento->valor_atual+$procedimento->valor_convenio)*$procedimento->valor_repasse/100, 2, ',', '.'))}}) @else R$ {{number_format($procedimento->valor_repasse, 2, ',', '.')}} @endif</td>
                                    @php
                                        if($procedimento->tipo == 'porcentagem') {
                                            $sub_total += (($procedimento->valor_atual+$procedimento->valor_convenio)*$procedimento->valor_repasse/100);
                                        } else {
                                            $sub_total += $procedimento->valor_repasse;
                                        }
                                        
                                    @endphp
                                @else
                                    <td>-</td>
                                @endif
                                @if ($procedimento->tipo != null && $item->desconto > 0)
                                    @php
                                        if($procedimento->tipo == 'porcentagem'){
                                            $valor_procedimento_retirar = (($procedimento->valor_atual+$procedimento->valor_convenio) * $desconto_por_procedimentos)/100;
                                            $valor_procedimento_novo = ($procedimento->valor_atual+$procedimento->valor_convenio) - $valor_procedimento_retirar;
                                            $valor_repasse_novo = (($valor_procedimento_novo)*$procedimento->valor_repasse/100);
                                        }else{
                                            $valor_repasse_novo = 0;
                                            if($procedimento->valor_repasse>0){
                                                $porcento_repasse = (($procedimento->valor_atual+$procedimento->valor_convenio)/($procedimento->valor_repasse * 100));
                                                $valor_procedimento_retirar = (($procedimento->valor_atual+$procedimento->valor_convenio) * $desconto_por_procedimentos)/100;
                                                $valor_procedimento_novo = ($procedimento->valor_atual+$procedimento->valor_convenio) - $valor_procedimento_retirar;
                                                $valor_repasse_real = ($valor_procedimento_novo*$porcento_repasse);
                                                $valor_repasse_novo = $procedimento->valor_repasse - $valor_repasse_real;
                                            }
                                        }
                                    @endphp
                                    <td>@if($procedimento->tipo == 'porcentagem') {{$procedimento->valor_repasse}} % (R$ {{(number_format($valor_repasse_novo, 2, ',', '.'))}}) @else R$ {{number_format($valor_repasse_novo, 2, ',', '.')}} @endif</td>
                                    @php
                                        if($procedimento->tipo == 'porcentagem') {
                                            $sub_total_desconto += $valor_repasse_novo;
                                        } else {
                                            $sub_total_desconto += $valor_repasse_novo;
                                        }
                                        
                                    @endphp
                                @else
                                    @if ($procedimento->tipo != null)
                                        <td>@if($procedimento->tipo == 'porcentagem') {{$procedimento->valor_repasse}} % (R$ {{(number_format(($procedimento->valor_atual+$procedimento->valor_convenio)*$procedimento->valor_repasse/100, 2, ',', '.'))}}) @else R$ {{number_format($procedimento->valor_repasse, 2, ',', '.')}} @endif</td>
                                        @php
                                            if($procedimento->tipo == 'porcentagem') {
                                                $sub_total_desconto += (($procedimento->valor_atual+$procedimento->valor_convenio)*$procedimento->valor_repasse/100);
                                            } else {
                                                $sub_total_desconto += $procedimento->valor_repasse;
                                            }
                                            
                                        @endphp
                                    @else
                                        <td>-</td>
                                    @endif
                                @endif
                            </tr>

                            @php
                                $procedimentos_convenios += $procedimento->valor_convenio;
                                $procedimentos += $procedimento->valor_atual;
                            @endphp
                            
                        @endforeach
                        <tr data-toggle-column="first" id="collapse{{$item->id}}" class="collapse show" aria-labelledby="heading{{$item->id}}" style="font-size: 14px">
                            <td>
                                <b>Totais: </b>
                            </td>
                            <td colspan="3">

                            </td>
                            <td>R$ {{number_format($procedimentos, 2, ',', '.')}}</td>
                            <td>R$ {{number_format($procedimentos_convenios, 2, ',', '.')}}</td>
                            <td>
                                S: R$ {{number_format(($procedimentos_convenios+$procedimentos), 2, ',', '.')}}
                                @if ($item->desconto > 0)
                                    <p style="margin: 0px">D: R$ {{number_format($item->desconto, 2, ',', '.')}}</p>
                                    <p style="margin: 0px">C: R$ {{number_format((($procedimentos_convenios+$procedimentos) - $item->desconto), 2, ',', '.')}}</p>
                                @endif
                            </td>
                            <td>
                                <b>R$ {{number_format($sub_total, 2, ',', '.')}}</b>
                            </td>
                            <td>
                                <b>R$ {{number_format($sub_total_desconto, 2, ',', '.')}}</b>
                            </td>
                        </tr>
                    </div>
                        @php
                            $total += $sub_total;
                            $total_desconto += $sub_total_desconto;
                            $total_sem_desconto += $procedimentos_convenios+$procedimentos;
                            $total_geral_desconto += $item->desconto;
                            $total_com_desconto += ($procedimentos_convenios+$procedimentos) - $item->desconto;
                        @endphp
                    
                @endforeach
            </div>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">
                    <b>Total Repasse</b>
                </td>
                <td colspan="3">
                    
                </td>
                <td colspan="2">
                    S: R$ {{number_format(($total_sem_desconto), 2, ',', '.')}}
                    <p style="margin: 0px">D: R$ {{number_format($total_geral_desconto, 2, ',', '.')}}</p>
                    <p style="margin: 0px">C: R$ {{number_format($total_com_desconto, 2, ',', '.')}}</p>
                </td>
                <td>
                    <b>R$ {{number_format($total, 2, ',', '.')}}</b>
                </td>
                <td>
                    <b>R$ {{number_format($total_desconto, 2, ',', '.')}}</b>
                </td>
            </tr>
            
        </tfoot>
    </table>