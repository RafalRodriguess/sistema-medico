<div class="">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">

                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">


                </div>
            </div>
            @can('habilidade_comercial_sessao', 'cadastrar_fretes')
            <div class="col-md-2">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <a href="{{ route('comercial.fretes_entrega.create') }}">
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
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="1">Cidade</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="1">Bairro</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="2">Taxa de Entrega</th> 
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="2">Valor Mínimo</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($itens as $item)
            <tr>
                <td class="title"><a href="javascript:void(0)">{{ $item->id }}</a></td>
               
               
                <td>{{ $item->cidade }}</td>
                <td>{{ $item->bairro }}</td>
                <td>R${{ $item->valor }}</td>
                <td>R${{ $item->valor_minimo }}</td>
               
               
                <td>
                    @can('habilidade_comercial_sessao', 'editar_fretes')
                    <a href="{{ route('comercial.fretes_entrega.edit', [$item]) }}">
                        <button type="button" class="btn btn-xs btn-secondary">
                            <i class="ti-pencil-alt"></i>
                        </button>
                    </a>
                    @endcan

                    @can('habilidade_comercial_sessao', 'excluir_fretes')
                    <form action="{{ route('comercial.fretes_entrega.destroy', [$item]) }}" method="post" class="d-inline form-excluir-registro">
                        @method('delete')
                        @csrf
                        <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"">
                            <i class="ti-trash"></i>
                        </button>
                    </form>
                    @endcan

                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>

        </tfoot>
    </table>
    <div style="float: right">
        {{ $itens->links() }}
    </div>
</div>