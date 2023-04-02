








<div class="card-body">
    
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">

            <div class="col-md-6">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" 
                        name="pesquisa" class="form-control" placeholder="Pesquise por descrição...">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <select name="cc_id" id="cc_id" class="form-control" 
                        wire:change="queryByCentroCusto($event.target.value)">
                        <option selected value="0">Todos</option>
                        @foreach ($centros_custos as $centro_custo)
                            <option value="{{ $centro_custo->id }}">
                                {{ $centro_custo->codigo }} {{ $centro_custo->descricao }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            @can('habilidade_instituicao_sessao', 'cadastrar_centros_cirurgicos')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.centros.cirurgicos.create') }}">
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
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Centro de Custo</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($centros_cirurgicos as $centro_cirurgico)
                
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $centro_cirurgico->id }}</a></td>
                    <td>{{ $centro_cirurgico->descricao }}</td>
                    <td>
                        @if ($centro_cirurgico->centroCusto)
                            {{ $centro_cirurgico->centroCusto->codigo }} {{ $centro_cirurgico->centroCusto->descricao }}
                        @endif
                    </td>
                    <td>
                        @can('habilidade_instituicao_sessao', 'editar_centros_cirurgicos')
                            <a href="{{ route('instituicao.centros.cirurgicos.edit', [$centro_cirurgico]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                    <i class="ti-pencil-alt"></i>
                                </button>
                            </a>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'excluir_centros_cirurgicos')
                            <form action="{{ route('instituicao.centros.cirurgicos.destroy', [$centro_cirurgico]) }}" method="post" class="d-inline form-excluir-registro">
                                @method('delete')
                                @csrf
                                <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                    <i class="ti-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'visualizar_salas_cirurgicas')
                            <a href="{{ route('instituicao.centros.cirurgicos.salas.index', [$centro_cirurgico]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Salas Cirúrgicas">
                                    <i class="ti-more-alt"></i>
                                </button>
                            </a>
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
    {{ $centros_cirurgicos->links() }}
</div>
</div>
