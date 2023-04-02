<table id="demo-foo-row-toggler" class="table table-bordered" data-toggle-column="first" style="margin: 0px">
    <thead>
        <tr>
            <th >Avaliador</th>
            <th >Negociador</th>
            <th >Paciente</th>            
            <th >Convênios</th>
            <th >Forma de Pagamento</th>
            <th >Nº de Parcelas</th>
            <th >Total Orçamento</th>
            <th >Total Orçamento Procedimento</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
            $total_procedimento = 0;
            $formas_pagamento = [];
            $convenios = [];
        @endphp
        @foreach($orcamentos as $item)
            @php
                $convenios = [];
                $total_procedimento += $item->total_procedimentos - $item->desconto;
                // $formas_pagamento[$item->parcelas[0]->forma_pagamento]['total_procedimento'] = $total_procedimento;
            @endphp
            @if (count($item->parcelas) > 1)    
                <tr>
                    <td rowspan="{{count($item->parcelas)}}">@if ($item->avaliador) {{$item->avaliador->nome}} @endif</td>
                    <td rowspan="{{count($item->parcelas)}}">{{$item->negociador->nome}}</td>
                    <td rowspan="{{count($item->parcelas)}}">{{$item->paciente->nome}}</td>
                    <td rowspan="{{count($item->parcelas)}}">
                        @foreach ($item->itens as $key => $iten)
                           
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
                    <td rowspan="{{count($item->parcelas)}}">{{number_format(($item->total_procedimentos - $item->desconto), 2, ',','.')}}</td>
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
                <td>{{number_format(($item->total_procedimentos - $item->desconto), 2, ',','.')}}</td>
            </tr>
            @endif
        @endforeach
    </tbody>
    <tfoot>
        {{-- @foreach ($formas_pagamento as $value)     --}}
            {{-- <tr> --}}
                {{-- <td colspan="6" style="text-align: right"><b>{{$value['descricao']}}</b></td> --}}
                {{-- <td><b>R$ {{number_format($value['valor'], 2, ',', '.')}}</b></td> --}}
                {{-- <td><b>R$ {{number_format($value['total_procedimento'], 2, ',', '.')}}</b></td> --}}
            {{-- </tr> --}}
        {{-- @endforeach --}}
        <tr>
            <td colspan="6" style="text-align: right"><b>Total</b></td>
            <td><b>R$ {{number_format($total, 2, ',', '.')}}</b></td>
            <td><b>R$ {{number_format($total_procedimento, 2, ',', '.')}}</b></td>
        </tr>
        
    </tfoot>
</table>