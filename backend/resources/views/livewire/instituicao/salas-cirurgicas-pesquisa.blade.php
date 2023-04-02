








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
                    <select name="tipo_id" id="tipo_id" class="form-control" 
                        wire:change="queryByTipo($event.target.value)">
                        <option selected value="0">Todos</option>
                        <?php $tipos = App\SalaCirurgica::getTipos() ?>
                        @foreach ($tipos as $tipo)
                            <option value="{{ $tipo }}">
                                {{ App\SalaCirurgica::getTipoTexto($tipo) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            @can('habilidade_instituicao_sessao', 'cadastrar_salas_cirurgicas')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.centros.cirurgicos.salas.create', [$centro_cirurgico]) }}">
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
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Sigla</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Tipo</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salas_cirurgicas as $sala_cirurgica)

                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $sala_cirurgica->id }}</a></td>
                    <td>{{ $sala_cirurgica->descricao }}</td>
                    <td>{{ $sala_cirurgica->sigla }}</td>
                    <td>{{ App\SalaCirurgica::getTipoTexto($sala_cirurgica->tipo) }}</td>
                    <td>
                        @can('habilidade_instituicao_sessao', 'editar_salas_cirurgicas')
                            <a href="{{ route('instituicao.centros.cirurgicos.salas.edit', [$centro_cirurgico, $sala_cirurgica]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                    <i class="ti-pencil-alt"></i>
                                </button>
                            </a>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'excluir_salas_cirurgicas')
                            <form action="{{ route('instituicao.centros.cirurgicos.salas.destroy', [$centro_cirurgico, $sala_cirurgica]) }}" method="post" class="d-inline form-excluir-registro">
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
    {{ $salas_cirurgicas->links() }}
</div>
</div>
