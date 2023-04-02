<style>
    .status{
        background-color: #d1dade;
        color: #5e5e5e;
        font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 10px;
        font-weight: 600;
        padding: 3px 8px;
        text-shadow: none;
    }
</style>
<table id="demo-foo-row-toggler" class="table table-bordered" data-toggle-column="first" style="margin: 0px">
    <thead>
        <tr>
            <th >Status</th>
            <th >Paciente</th>
            <th >Data da Avaliação</th>
            <th >Data da Aprovação</th>            
            <th >Avaliador</th>
            <th >Responsável</th>
            <th >Negociador</th>
            {{-- <th >Valor Total</th> --}}
            <th >Valor Aprovado</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
            $total_aprovado = 0;
        @endphp
        @foreach($orcamentos as $item)
            @php
                $total += $item->valor_total;
                $total_aprovado += $item->valor_aprovado;
            @endphp
           <tr>
                <td><span class="status">{{$item->status}}</span></td>
                <td>{{$item->paciente->nome}}</td>
                <td>{{$item->created_at->format('d/m/Y')}}</td>
                <td>{{($item->data_aprovacao) ? $item->data_aprovacao->format('d/m/Y') : ''}}</td>
                <td>{{($item->avaliador) ? $item->avaliador->nome : 'Não informado'}}</td>
                <td>{{($item->responsavel) ? $item->responsavel->nome : 'Não informado'}}</td>
                <td>{{($item->negociador) ? $item->negociador->nome : 'Não informado'}}</td>
                {{-- <td>R$ {{number_format($item->valor_total, 2,',','.')}}</td> --}}
                <td>@if ($item->status == 'criado' || $item->status == 'reprovado')
                    R$ {{number_format($item->valor_total, 2,',','.')}}
                @else
                    R$ {{($item->valor_aprovado) ? number_format($item->valor_aprovado, 2,',','.') : '0.0'}}
                @endif</td>
           </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7" style="text-align: right"><b>Total</b></td>
            {{-- <td>R$ {{number_format($total, 2, ',','.')}}</td> --}}
            <td>R$ {{number_format($total_aprovado, 2, ',','.')}}</td>
        </tr>
    </tfoot>
</table>