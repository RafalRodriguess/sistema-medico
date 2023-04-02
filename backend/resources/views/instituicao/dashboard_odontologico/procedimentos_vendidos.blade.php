<div class="card">
    <div class="card-body">
        <h4 class="card-title">Procedimentos vendidos</h4>
        {{-- <h6 class="card-subtitle">Basic sortable table</h6> --}}
        <table data-toggle="table" data-sort-name="name" data-height="280" data-mobile-responsive="true" data-sort-order="asc" class="table" id="procedimentosVendidos">
            <thead>
                <tr>
                    <th class="name_head" data-field="name_head" data-sortable="true"> Descrição </th>
                    <th class="stargazers_count_head" data-field="stargazers_count_head" data-sortable="true" data-width="100"> Convênio </th>
                    <th class="grupo_head" data-field="grupo_head" data-sortable="true" data-width="100"> Grupo </th>
                    <th class="forks_count_head" data-field="forks_count_head" data-sortable="true" data-width="100"> Quantidade </th>
                </tr>
            </thead>
            <tbody>
                @if ($procedimentos_vendidos)
                    @foreach ($procedimentos_vendidos as $item)
                        <tr>
                            <td>{{$item['descricao']}}</td>
                            <td>{{$item['convenio']}}</td>
                            <td>{{$item['grupo']}}</td>
                            <td>{{$item['quantidade']}}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#procedimentosVendidos').bootstrapTable()
        $(".name_head").css('width', '50%');
        $(".stargazers_count_head").css('width', '20%');
        $(".grupo_head").css('width', '20%');
        $(".forks_count_head").css('width', '10%');
    })
</script>