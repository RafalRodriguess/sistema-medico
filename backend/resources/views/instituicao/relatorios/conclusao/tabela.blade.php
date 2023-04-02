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
            <th >Data</th>
            <th >Paciente</th>
            <th >Profissional</th>
            <th >Motivo</th>            
            <th >Ação</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($conclusoes as $item)
            <tr>
                <td>{{date('d/m/Y', strtotime($item->created_at))}}</td>
                <td>{{$item->paciente->nome}}</td>
                <td>{{$item->usuario->prestador[0]->prestador->nome}}</td>
                <td>{{$item->motivo->descricao}}</td>
                <td> 
                    <button type="button" class="btn btn-xs btn-secondary visualizar-conclusao" aria-haspopup="true" aria-expanded="false"
                        data-toggle="tooltip" data-placement="top" data-paciente="{{$item->paciente_id}}" data-agendamento="{{$item->agendamento_id}}" data-conclusao="{{$item->id}}" data-original-title="Visualizar">
                        <i class="far fa-list-alt"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
    </tfoot>
</table>

<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>