<table id="demo-foo-row-toggler" class="table table-bordered" data-toggle-column="first" >
    <thead>
        <tr>
            {{-- <th data-breakpoints="xs" hidden></th> --}}
            <th>Conta caixa</th>  
            <th>Entrada</th>
            <th>Saida</th>
            {{-- <th data-breakpoints="all">Tipo</th> --}}
        </tr>
    </thead>
    <tbody>
        @php
            $totalEntradas = 0;
            $totalSaidas = 0;
        @endphp
        @foreach($caixa as $key => $item)    
            <tr>
                <td>{{$key}}</td>
                <td style="color: green">R$ {{number_format($item['entradas'], 2, ",", ".")}}</td>
                <td style="color: red">R$ {{number_format($item['saidas'], 2, ",", ".")}}</td>
            </tr>

            @php
                $totalEntradas += $item['entradas'];
                $totalSaidas += $item['saidas'];
            @endphp

        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <th style="color: green"><b>R$ {{number_format($totalEntradas, 2, ',', '.')}}</b></th>
            <th style="color: red"><b>R$ {{number_format($totalSaidas, 2, ',', '.')}}<b></th>
        </tr>
    </tfoot>
</table>