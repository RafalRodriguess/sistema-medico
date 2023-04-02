<table id="demo-foo-row-toggler" class="table table-bordered" data-toggle-column="first" style="margin: 0px">
    <thead>
        <tr>
            <th >Paciente</th>
            <th >Valor Aprovado</th>
            <th >Desconto</th>            
            <th >Valor a Receber</th>
            <th >Negociador</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
            $total_desconto = 0;
            $total_a_receber = 0;
        @endphp
        @foreach($orcamentos as $item)
            @php
                $total += $item->valor_aprovado;
                $total_desconto += $item->desconto;
                $total_a_receber += $item->valor_aprovado - $item->desconto;
            @endphp
           <tr>
                <td>{{$item->paciente->nome}}</td>
                <td>R$ {{number_format($item->valor_aprovado, 2,',','.')}}</td>
                <td>R$ {{number_format($item->desconto, 2,',','.')}}</td>
                <td>R$ {{number_format(($item->valor_aprovado - $item->desconto), 2,',','.')}}</td>
                <td>{{($item->negociador) ? $item->negociador->nome : '-'}}</td>
           </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td><b>Total</b></td>
            <td>R$ {{number_format($total, 2, ',','.')}}</td>
            <td>R$ {{number_format($total_desconto, 2, ',','.')}}</td>
            <td>R$ {{number_format($total_a_receber, 2, ',','.')}}</td>
            <td></td>
        </tr>
    </tfoot>
</table>