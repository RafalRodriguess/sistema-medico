


<div class="card-body">
    
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">
                        <input type="text" id="pesquisa" wire:model.lazy="pesquisa" 
                        name="pesquisa" class="form-control" placeholder="Pesquise por nome...">
                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_leitos')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.internacao.leitos.create', [$unidade_internacao]) }}">
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
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Descricao</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Tipo</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Situação</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Quantidade</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Caracteristicas</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leitos as $leito)

                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $leito->id }}</a></td>
                    <td>{{ $leito->descricao }}</td>
                    <td>{{ App\UnidadeLeito::getTipoTexto($leito->tipo) }}</td>
                    <td>{{ App\UnidadeLeito::getSituacaoTexto($leito->situacao) }}</td>
                    <td>{{ $leito->quantidade }}</td>
                    <td>
                        <?php $caracteristica_texto = "" ?>
                        @foreach ($leito->caracteristicas as $caracteristica)
                            <?php $caracteristica_texto = "$caracteristica_texto"."$caracteristica"."; "?>
                        @endforeach
                        {{ $caracteristica_texto }}
                    </td>
                    <td>
                        @can('habilidade_instituicao_sessao', 'editar_leitos')
                            <a href="{{ route('instituicao.internacao.leitos.edit', [$unidade_internacao, $leito]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                    <i class="ti-pencil-alt"></i>
                                </button>
                            </a>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'excluir_leitos')
                            <form action="{{ route('instituicao.internacao.leitos.destroy', [$unidade_internacao, $leito]) }}" method="post" class="d-inline form-excluir-registro">
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
    {{ $leitos->links() }}
</div>
</div>
