<div id="table-scroll" class="table-scroll">
    <div class="table-wrap">
        <table class="table table-bordered table-sm main-table" data-toggle="table" data-search="true" data-show-columns="true">
            <thead>
                <tr>
                    <th class="fixed-side">Descrição</th>
                    <th class="text-center">{!! implode("</th><th class='text-center'>", $cabecalho) !!}</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th class="fixed-side text-white" style="background-color: #a7dc99;">Entradas</th>
                    @php $totalEntradas = 0 @endphp
                    @foreach ($cabecalho as $i)
                        @php
                            $v = array_sum(array_filter(array_column($entradas, $i)));
                            $totalEntradas += $v;
                        @endphp
                        <td class="text-success text-center" style="background-color: #a7dc99;"><b>{{number_format($v, 2, ",", ".")}}</b></th>
                    @endforeach
                    <th class="text-success text-center" style="background-color: #a7dc99;"><b>{{number_format($totalEntradas, 2, ",", ".")}}</b></th>
                </tr>

                @foreach($entradas as $key => $item)
                    <tr>
                        <th class="fixed-side">{{$key}}</th>
                        @foreach ($cabecalho as $i)
                            <td class="text-center">{{number_format(!empty($item[$i]) ? $item[$i] : 0, 2, ",", ".")}}</td>
                        @endforeach
                        <th class="text-center">{{number_format(array_sum($item), 2, ",", ".")}}</th>
                    </tr>
                @endforeach

                <tr>
                    <th class="fixed-side text-white" style="background-color: #cd3333b7;">Saidas</th>
                    @php $totalSaidas = 0 @endphp
                    @foreach ($cabecalho as $i)
                        @php
                            $v = array_sum(array_filter(array_column($saidas, $i)));
                            $totalSaidas += $v;
                        @endphp
                        <td class="text-white text-center" style="background-color: #cd3333b7;"><b>{{number_format($v, 2, ",", ".")}}</b></th>
                    @endforeach
                    <th class="text-white text-center" style="background-color: #cd3333b7;"><b>{{number_format($totalSaidas, 2, ",", ".")}}</b></th>
                </tr>

                @foreach($saidas as $key => $item)
                    <tr>
                        <th class="fixed-side">{{$key}}</th>
                        @foreach ($cabecalho as $i)
                            <td class="text-center">{{number_format(!empty($item[$i]) ? $item[$i] : 0, 2, ",", ".")}}</td>
                        @endforeach
                        <th class="text-center">{{number_format(array_sum($item), 2, ",", ".")}}</th>                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>