<table id="demo-foo-row-toggler" class="table table-bordered" data-toggle-column="first" style="margin: 0px">
    <thead>
        <tr>
            @foreach ($colunas as $item)
                <th>{{$item}}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @if (count($dados) > 0)
            @foreach($dados as $item)
                <tr>
                    @foreach ($item as $keyI => $data)
                        @if ($keyI == $posicao)
                            <td>
                                @foreach ($data as $key => $alteracao)
                                    @if (is_array($alteracao))
                                        <p>
                                            @foreach ($alteracao as $keyA => $array)
                                                <b style="color: black;">{{$keyA}}</b>: {{$array}} -
                                            @endforeach
                                        </p>
                                    @else
                                        <b style="color: black;">{{$key}}</b>: {{$alteracao}} -
                                    @endif
                                @endforeach
                            </td>
                        @else
                            <td>{{$data}}</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        @endif
    </tbody>
</table>