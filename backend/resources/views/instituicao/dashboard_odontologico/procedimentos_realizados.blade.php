<div class="card">
    <div class="card-body">
        <h4 class="card-title">Procedimentos realizados</h4>
        {{-- <h6 class="card-subtitle">Basic sortable table</h6> --}}
        <table data-toggle="table" data-sort-name="name" data-height="280" data-mobile-responsive="true" data-sort-order="asc" class="table" id="procedimentosRealizados">
            <thead>
                <tr>
                    <th class="name_head_2" data-field="name_2" data-sortable="true"> Descrição </th>
                    <th class="stargazers_count_head_2" data-field="stargazers_count_2" data-sortable="true" data-width="100"> Convênio </th>
                    <th class="grupo_head_2" data-field="grupo_2" data-sortable="true" data-width="100"> Grupo </th>
                    <th class="forks_count_head_2" data-field="forks_count_2" data-sortable="true" data-width="100"> Quantidade </th>
                </tr>
            </thead>
            <tbody>
                @if($procedimentos_realizados)
                    @foreach ($procedimentos_realizados as $item)
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
        $('#procedimentosRealizados').bootstrapTable()
        $(".name_head_2").css('width', '50%');
        $(".stargazers_count_head_2").css('width', '20%');
        $(".grupo_head_2").css('width', '20%');
        $(".forks_count_head_2").css('width', '10%');
    })
</script>