<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <input type="text" id="pesquisa"
                        wire:model.lazy="pesquisa" name="pesquisa"
                        class="form-control"
                        placeholder="Pesquise por nome do solicitante...">
                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'visualizar_cadastro_procedimentos')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.solicitacaoCompras.create') }}">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                        </a>
                    </div>
                </div>
            @endcan
        </div>
    </form>

<hr>

<table class="tablesaw table-bordered table-hover table" >
    <thead>
        <tr>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Nome Solicitante</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
        </tr>
    </thead>

    <tbody>
        @foreach($solicitacao_compras as $solicitacao_compra)
        <tr>
            <td class="title"><a href="javascript:void(0)">{{ $solicitacao_compra->id }}</a></td>
            <td>{{ $solicitacao_compra->nome_solicitante }}</td> 
            <td>
                @can('habilidade_instituicao_sessao', 'editar_cadastro_procedimentos')
                <a href="{{ route('instituicao.solicitacaoCompras.edit', [$solicitacao_compra->id]) }}">
                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                    <i class="ti-pencil-alt"></i>
                </button>
            </a>
            @endcan
            @can('habilidade_instituicao_sessao', 'excluir_cadastro_procedimentos')
            <form action="{{ route('instituicao.solicitacaoCompras.destroy', [$solicitacao_compra->id]) }}" method="post" class="d-inline form-excluir-registro">
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
                {{ $procedimentos->links() }}
            </td>
        </tr>  --}}
    </tfoot>
</table>
<div style="float: right">
    {{ $solicitacao_compras->links() }}
</div>
</div>
