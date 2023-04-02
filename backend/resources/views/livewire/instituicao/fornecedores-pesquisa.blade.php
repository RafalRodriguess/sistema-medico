<div class="card-body">
                                    
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">
                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_fornecedores')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.fornecedores.create') }}">
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
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Nome/Nome Fantasia</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Personalidade</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">CPF/CNPJ</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fornecedores as $fornecedor)
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $fornecedor->id }}</a></td>
                    <td>
                        @if ($fornecedor->nome_fantasia) {{ $fornecedor->nome_fantasia}} @else {{ $fornecedor->nome}} @endif
                    </td>
                    <td> {{ App\Pessoa::getPersonalidadeTexto($fornecedor->personalidade) }} </td>
                    <td>
                        @if ($fornecedor->cpf) {{ $fornecedor->cpf }} @else {{ $fornecedor->cnpj }} @endif
                    </td>
                    <td>
                        @can('habilidade_instituicao_sessao', 'editar_fornecedores')
                            <a href="{{ route('instituicao.fornecedores.edit', [$fornecedor]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                        <i class="ti-pencil-alt"></i>
                                </button>
                            </a>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'excluir_fornecedores')
                            <form action="{{ route('instituicao.fornecedores.destroy', [$fornecedor]) }}" method="post" class="d-inline form-excluir-registro">
                                @method('delete')
                                @csrf
                                <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                    <i class="ti-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'visualizar_documentos_fornecedores')
                            <a href="{{ route('instituicao.fornecedores.documentos.index', [$fornecedor]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Documentos">
                                    <i class="ti-folder"></i>
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
                    {{ $instituicoes->links() }}
                </td>
            </tr>  --}}
        </tfoot>
    </table>
    <div style="float: right">
        {{ $fornecedores->links() }}
    </div>
</div>