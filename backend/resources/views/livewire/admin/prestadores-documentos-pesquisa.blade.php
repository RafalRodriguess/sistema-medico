<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
            <div class="row">
                  <div class="col-md-10">
                    <div class="form-group" style="margin-bottom: 0px !important;">

                          <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">


                    </div>
                  </div>

                  @can('habilidade_admin', 'cadastrar_documento_prestador')


                                                      <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                         <a href="{{ route('prestadores.documentos.create', [$prestador]) }}">
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
                
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prestador_documentos as $documento)

                <div class="modal fade" id="modal-view-documento-{{ $documento->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">
                                    {{ App\DocumentoPrestador::getTipoDocumentoTexto($documento->tipo) }}: {{ $documento->descricao }} 
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
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $documento->id }}</a></td>
                    <td>{{ $documento->descricao }}</td>
                    <td>{{ App\DocumentoPrestador::getTipoDocumentoTexto($documento->tipo )}}</td>
                    <td>
                        @can('habilidade_admin', 'editar_documento_prestador')
                            <a href="{{ route('prestadores.documentos.edit', [$prestador, $documento]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                    <i class="ti-pencil-alt"></i>
                                </button>
                            </a>
                        @endcan
                        @can('habilidade_admin', 'excluir_documento_prestador')
                            <form action="{{ route('prestadores.documentos.destroy', [$prestador, $documento]) }}" method="post" class="d-inline form-excluir-registro">
                                @method('delete')
                                @csrf
                                <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                    <i class="ti-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @can('habilidade_admin', 'visualizar_documento_prestador')
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
                    {{ $prestador->links() }}
                </td>
            </tr>  --}}
        </tfoot>
    </table>
</div>

<div style="float: right">
    {{ $prestador_documentos->links() }}
</div>
</div>
