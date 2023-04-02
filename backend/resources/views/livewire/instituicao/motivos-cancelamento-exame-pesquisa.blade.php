<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">

                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control"
                        placeholder="Pesquise por componente...">


                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_motivos_cancelamento_exame')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.motivoscancelamentoexame.create') }}">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                        </a>
                    </div>
                </div>
            @endcan
        </div>
    </form>

    <hr>


    <table class="tablesaw table-bordered table-hover table">
        <thead>
            <tr>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
                <th scope="col" data-tablesaw-sortable-col>Exame</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                    data-tablesaw-priority="3">Tipo</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                    data-tablesaw-priority="3">
                    Ativo
                </th>
                {{-- <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                Via Administração
            </th> --}}
                {{-- <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($motivos as $motivo)
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $motivo->id }}</a></td>
                    @can('habilidade_instituicao_sessao', 'visualizar_procedimentos')
                        <td><a href="{{ route('instituicao.procedimentos.index', $motivo->procedimento_id) }}" target="_blank">{{mb_strimwidth($motivo->procedimento->descricao, 0, 50, '...')}}</a></td>
                    @else
                        <td>{{ mb_strimwidth($motivo->procedimento->descricao, 0, 50, '...') }}</td>
                    @endcan
                    <td>{{ $motivo->tipo }}</td>
                    <td>@if($motivo->ativo) <i class="fas fa-check text-success"></i> @else <i class="fas fa-times text-danger"></i> @endif</td>
                    {{-- <td>{{ $procedimento->via_administracao }}</td> --}}
                    <td>
                        @can('habilidade_instituicao_sessao', 'editar_motivos_cancelamento_exame')
                            <a href="{{ route('instituicao.motivoscancelamentoexame.edit', [$motivo]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true"
                                    aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                    <i class="ti-pencil-alt"></i>
                                </button>
                            </a>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'editar_motivos_cancelamento_exame')
                            <form action="{{ route('instituicao.motivoscancelamentoexame.switch', [$motivo]) }}" method="post"
                                class="d-inline form-editar-registro">
                                @method('put')
                                @csrf
                                <input type="hidden" name="ativo" value="{{ !$motivo->ativo }}">
                                <button type="submit" class="btn btn-xs btn-secondary"
                                    data-toggle="tooltip" data-placement="top" data-original-title="{{ $motivo->ativo == 1 ? 'Desativar' : 'Ativar' }}">
                                    @if(!$motivo->ativo) <i class="fas fa-check"></i> @else <i class="fas fa-times"></i> @endif
                                </button>
                            </form>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'excluir_motivos_cancelamento_exame')
                            <form action="{{ route('instituicao.motivoscancelamentoexame.destroy', [$motivo]) }}" method="post"
                                class="d-inline form-excluir-registro">
                                @method('delete')
                                @csrf
                                <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"
                                    aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                    <i class="ti-trash"></i>
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            {{-- <tr>
            <td colspan="5">
                {{ $procedimentos->links() }}
            </td>
        </tr> --}}
        </tfoot>
    </table>
    <div style="float: right">
        {{ $motivos->links() }}
    </div>
</div>
