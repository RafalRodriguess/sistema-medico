<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
            <div class="row">
                  <div class="col-md-10">
                    <div class="form-group" style="margin-bottom: 0px !important;">

                          <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">


                    </div>
                  </div>

                  @can('habilidade_admin', 'cadastrar_prestador')


                                                      <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                         <a href="{{ route('prestadores.create') }}">
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
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Nome</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">CPF/CNPJ</th>
                
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prestador as $value)
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $value->id }}</a></td>
                    <td>{{ $value->nome }}</td>
                    <td>{{ ($value->personalidade == 1) ? $value->cpf : $value->cnpj }}</td>
                    <td>
                        @can('habilidade_admin', 'editar_prestador')
                            <a href="{{ route('prestadores.edit', [$value]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                            <i class="ti-pencil-alt"></i>
                                    </button>
                            </a>
                        @endcan
                        @can('habilidade_admin', 'excluir_prestador')
                            @if($value->instituicoes_count == 0)
                                <form action="{{ route('prestadores.destroy', [$value]) }}" method="post" class="d-inline form-excluir-registro">
                                    @method('delete')
                                    @csrf
                                    <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                            <i class="ti-trash"></i>
                                    </button>
                                </form>
                            @endif
                        @endcan
                        @can('habilidade_admin', 'visualizar_documento_prestador')
                            <a href="{{ route('prestadores.documentos.index', [$value]) }}">
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
                    {{ $prestador->links() }}
                </td>
            </tr>  --}}
        </tfoot>
    </table>
</div>

<div style="float: right">
    {{ $prestador->links() }}
</div>
</div>
