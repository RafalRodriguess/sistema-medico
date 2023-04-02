<div class="scrolling-pagination">
    <table class="tablesaw table-bordered table-hover table">
        <thead>
            <tr>
                <th>Paciente</th>
                <th>Telefone</th>
                <th>Data Solicitado</th>
                <th>Convenio</th>
                <th>Prestador/Especialidade</th>
                <th>Obs</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            @foreach($listaEspera as $item)
                <tr>
                    <td>{{ $item->pessoa->nome }}</td>
                    <td>{{ $item->pessoa->telefone1 }}</td>
                    <td>{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                    <td>{{ ($item->convenio_id) ? $item->convenio->nome : "" }}</td>
                    <td>{{ ($item->prestador_id) ? $item->prestador->nome : $item->especialidade->descricao }}</td>
                    <td>{{ $item->obs }}</td>
                    <td>
                        @can('habilidade_instituicao_sessao', 'agendar_agendamentos_lista_espera')
                            <button type="button" class="btn btn-xs btn-secondary horario_disponivel" aria-haspopup="true" aria-expanded="false" data-horario="{{"08:00:00"}}" data-lista="{{$item->id}}" data-paciente="{{$item->paciente_id}}" data-tipo="avulso" data-toggle="tooltip" data-placement="top" data-dismiss="modal" data-original-title="Agendar paciente">
                                <i class="ti-calendar"></i>
                            </button>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $listaEspera->links() }}
</div>

<script>
    $('[data-toggle="tooltip"]').tooltip()
</script>