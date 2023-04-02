<div class="card-body">
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <label>Profissional: #{{$prestador->id}} - {{$prestador->nome}}</label>
                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_agenda_ausente')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.prestadores.agendaAusente.create', [$prestador]) }}">
                        <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                        </a>
                    </div>
                </div>
            @endcan
        </div>
    </form>
 
    <hr>
 
    <div class="table-responsive">
        <table class="tablesaw table-bordered table-hover table" >
            <thead>
                <tr>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Data</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Inicio</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Fim</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Dia todo</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Motivo</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                    
            </thead>
            <tbody>
                
                
                @foreach($horarios as $item)
                    <tr>
                        <td class="title"><a href="javascript:void(0)">{{ $item->id }}</a></td>
                        <td>{{ date("d/m/Y", strtotime($item->data))}}</td>
                        <td>{{ $item->hora_inicio }}</td>
                        <td>{{ $item->hora_fim }}</td>
                        <td>{{ ($item->dia_todo) ? 'Sim' : 'Não' }}</td>
                        <td>{{ $item->motivo }}</td>
                        <td>
                            @can('habilidade_instituicao_sessao', 'editar_agenda_ausente')
                                <a href="{{ route('instituicao.prestadores.agendaAusente.edit', [$prestador, $item]) }}">
                                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                                <i class="ti-pencil-alt"></i>
                                        </button>
                                </a>
                            @endcan

                            @can('habilidade_instituicao_sessao', 'excluir_agenda_ausente')
                                    <form action="{{ route('instituicao.prestadores.agendaAusente.destroy', [$prestador, $item]) }}" method="post" class="d-inline form-excluir-registro">
                                        @method('delete')
                                        @csrf
                                        <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                                <i class="ti-trash"></i>
                                        </button>
                                    </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div style="float: right">
        {{ $horarios->links() }}
    </div>
 </div>
