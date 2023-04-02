<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
              <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">

                      <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">


                </div>
              </div>
              @can('habilidade_admin', 'cadastrar_especialidade')
                                                  <div class="col-md-2">
                <div class="form-group" style="margin-bottom: 0px !important;">
                     <a href="{{ route('especialidades.create') }}">
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
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="5">Descrição</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="5">Instituições</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($especialidades as $especialidade)
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $especialidade->id }}</a></td>
                    <td>{{ $especialidade->descricao }}</td>
                    <td>{{ $especialidade->countPrestadoresInstituicoes() }}</td>
                    <td>
                        @can('habilidade_admin', 'editar_especialidade')
                            <a href="{{ route('especialidades.edit', [$especialidade]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false">
                                            <i class="ti-pencil-alt"></i>
                                    </button>
                            </a>
                        @endcan
                        @can('habilidade_admin', 'excluir_especialidade')
                            @if($especialidade->prestadores_instituicao_count == 0)
                            <form action="{{ route('especialidades.destroy', [$especialidade]) }}" method="post" class="d-inline form-excluir-registro">
                                @method('delete')
                                @csrf
                                <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false">
                                        <i class="ti-trash"></i>
                                </button>
                            </form>
                            @endif
                        @endcan



                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            {{-- <tr>
                <td colspan="5">
                    {{ $especialidades->links() }}
                </td>
            </tr>  --}}
        </tfoot>
    </table>
    <div style="float: right">
        {{ $especialidades->links() }}
    </div>
</div>
