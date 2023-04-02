<div class="card-body">
                                    
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <input type="text" id="pesquisa" 
                        wire:model.lazy="pesquisa" name="pesquisa"
                        class="form-control" 
                        placeholder="Pesquise por convenio ou nº carteirinha...">
                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_carteirinha')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.carteirinhas.create', [$pessoa]) }}">
                        <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                        </a>
                    </div>
                </div>
            @endcan
        </div>
    </form>

    <hr>

    <table class="tablesaw table-bordered table-hover table" style="overflow-wrap: anywhere">
        <thead>
            <tr>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Convenio</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Plano</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Carteirinha</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Validade</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Status</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($carteirinhas as $item)
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $item->id }}</a></td>
                    <td>{{ $item->convenio[0]->nome }}</td>
                    <td>{{ $item->plano[0]->nome }}</td>
                    <td>{{ $item->carteirinha }}</td>
                    <td>{{ date("d/m/Y", strtotime($item->validade)) }}</td>
                    <td>{{ ($item->status) ? 'Ativo' : 'Inativo'}}</td>
                    <td>
                        @can('habilidade_instituicao_sessao', 'editar_carteirinha')
                            <a href="{{ route('instituicao.carteirinhas.edit', [$pessoa, $item]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                        <i class="ti-pencil-alt"></i>
                                </button>
                            </a>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'excluir_carteirinha')
                            <form action="{{ route('instituicao.carteirinhas.destroy', [$pessoa, $item]) }}" method="post" class="d-inline form-excluir-registro">
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
                    {{ $instituicoes->links() }}
                </td>
            </tr>  --}}
        </tfoot>
    </table>
    <div style="float: right">
        {{ $carteirinhas->links() }}
    </div>
</div>


@push('scripts') 
    <script>
        $(document).ready(function(){


        });
    </script>
@endpush
