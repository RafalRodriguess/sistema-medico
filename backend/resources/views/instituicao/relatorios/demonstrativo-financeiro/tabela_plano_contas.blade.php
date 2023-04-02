<table id="demo-foo-row-toggler" class="table table-bordered" data-toggle-column="first" >
    <thead>
        <tr>
            {{-- <th data-breakpoints="xs" hidden></th> --}}
            <th>Codigo</th>  
            <th>Descricao</th>            
            <th>Valor parcela</th>
            <th>Valor pago</th>
            <th></th>
            {{-- <th data-breakpoints="all">Tipo</th> --}}
        </tr>
    </thead>
    <tbody>
        @foreach($planoContas as $key => $item)   
            <tr>
                <td>{{$key}}</td>
                <td>{{$item['descricao']}}</td>
                <td>R$ {{number_format($item['valor_parcela'], 2, ',', '.')}}</td>
                <td>R$ {{number_format($item['valor_pago'], 2, ',', '.')}}</td>
                <td>
                    @if ($item['padrao'] == 0)
                        <i class="mdi mdi-arrow-right-bold" style="color: green"></i>
                    @elseif ($item['padrao'] == 1)
                        <i class="mdi mdi-arrow-left-bold" style="color: red"></i>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td></td>
        </tr>
    </tfoot>
</table>