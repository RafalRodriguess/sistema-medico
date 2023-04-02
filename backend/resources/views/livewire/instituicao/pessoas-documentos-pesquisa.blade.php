

<div class="card-body">
                                    
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <select name="tipo" class="form-control" id="tipo" wire:model.lazy="tipo">
                        <option selected value="0">Todos os Tipos</option>
                        @foreach ($tipos_documentos as $tipo)
                            <option value="{{ $tipo }}">{{ App\PessoaDocumento::getTipoTexto($tipo) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_documentos_pessoas')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.pessoas.documentos.create', [$pessoa]) }}">
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
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Tipo</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Descrição</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Data de Upload</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documentos as $documento)

                <div class="modal fade" id="modal-view-documento-{{ $documento->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">
                                    {{ App\PessoaDocumento::getTipoTexto($documento->tipo) }}: {{ $documento->descricao }} 
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body bg-dark p-0">
                                <object data="{{ \Storage::disk('public')->url($documento->file_path_name) }}" 
                                    width="100%" height="auto" class="m-0 p-0"></object>
                            </div>
                            <div class="modal-footer">
                                <a href="{{ route('instituicao.pessoasDocumentosDownload', $documento->file_path_name) }}">
                                    <button type="button" class="btn btn-success download-pessoas-documentos">Baixar <i class="mdi mdi-download"></i></button>
                                </a>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $documento->id }}</a></td>
                    <td>{{ App\PessoaDocumento::getTipoTexto($documento->tipo)}}</td>
                    <td> {{ $documento->descricao }} </td>
                    <td>{{ $documento->formatadaDataUpload() }}</td>
                    <td>
                        @can('habilidade_instituicao_sessao', 'editar_documentos_pessoas')
                            <a href="{{ route('instituicao.pessoas.documentos.edit', [$pessoa, $documento]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                        <i class="ti-pencil-alt"></i>
                                </button>
                            </a>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'excluir_documentos_pessoas')
                            <form action="{{ route('instituicao.pessoas.documentos.destroy', [$pessoa, $documento]) }}" method="post" class="d-inline form-excluir-registro">
                                @method('delete')
                                @csrf
                                <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                        <i class="ti-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'visualizar_documentos_pessoas')
                            <a data-toggle="modal" data-target="#modal-view-documento-{{ $documento->id }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Visualizar Documento">
                                    <i class="ti-eye"></i>
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
        {{ $documentos->links() }}
    </div>
</div>


@push('scripts') 
    <script>
        $(document).ready(function(){


        });
    </script>
@endpush