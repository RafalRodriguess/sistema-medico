







<div class="card-body">
    
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" 
                        name="pesquisa" class="form-control" placeholder="Pesquise por motivo...">
                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_motivos_cancelamento_altas')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.internacao.motivos-cancelamento-altas.create') }}">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                        </a>
                    </div>
                </div>
            @endcan
        </div>
    </form>

    <hr>

<div class="table-responsive">
    <table class="tablesaw table-bordered table-hover table">
        <thead>
            <tr>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Descrição</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Tipo</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Ativo</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($motivos_cancelamento_altas as $motivo_cancelamento_alta)

                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $motivo_cancelamento_alta->id }}</a></td>
                    <td>{{ $motivo_cancelamento_alta->descricao_motivo_cancelamento_alta }}</td>
                    <td>{{ App\MotivoCancelamentoAlta::getTipoTexto($motivo_cancelamento_alta->tipo) }}</td>
                    <td>@if ($motivo_cancelamento_alta->ativo==1) Sim @else Não @endif</td>
                    <td>
                        @can('habilidade_instituicao_sessao', 'editar_motivos_cancelamento_altas')
                            <a href="{{ route('instituicao.internacao.motivos-cancelamento-altas.edit', [$motivo_cancelamento_alta]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                    <i class="ti-pencil-alt"></i>
                                </button>
                            </a>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'excluir_motivos_cancelamento_altas')
                            <form action="{{ route('instituicao.internacao.motivos-cancelamento-altas.destroy', [$motivo_cancelamento_alta]) }}" method="post" class="d-inline form-excluir-registro">
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
        <tfoot>
            {{-- <tr>
                <td colspan="5">
                    {{ $prestador->links() }}
                </td>
            </tr>  --}}
        </tfoot>
    </table>
</div>

<div style="float: right">
    {{ $motivos_cancelamento_altas->links() }}
</div>
</div>
