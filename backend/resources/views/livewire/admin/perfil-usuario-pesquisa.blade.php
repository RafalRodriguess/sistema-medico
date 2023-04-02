<div class="card-body">
                                   
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
              <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">
                     
                      <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por descrição...">
                    
                     
                </div>
              </div>
              @can('habilidade_admin', 'cadastrar_perfis_usuarios')
                                                  <div class="col-md-2">
                <div class="form-group" style="margin-bottom: 0px !important;">
                     <a href="{{ route('perfis_usuarios.create') }}">
                      <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                     </a>
                </div>
             </div>
             @endcan
                                         </div>
      </form>

      <hr>

    <table class="tablesaw table-bordered table-hover table" >
        <thead>
            <tr>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Descrição</th>
                {{-- <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Nome válido</th> --}}
                
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($perfis_usuarios as $perfil_usuario)
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $perfil_usuario->id }}</a></td>
                    <td>{{ $perfil_usuario->descricao }}</td>
                    {{-- <td>{{ $perfil_usuario->nome_valido }}</td> --}}
                    <td>
                        @can('habilidade_admin', 'habilidades_perfis_usuarios')
                            <a href="{{ route('perfis_usuarios.habilidades', [$perfil_usuario]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Habilidades">
                                        <i class="ti-lock"></i>
                                </button>
                            </a>
                        @endcan
                        @can('habilidade_admin', 'editar_perfis_usuarios')
                            <a href="{{ route('perfis_usuarios.edit', [$perfil_usuario]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false">
                                            <i class="ti-pencil-alt"></i>
                                    </button>
                            </a>
                        @endcan
                        @can('habilidade_admin', 'excluir_perfis_usuarios')
                            <form action="{{ route('perfis_usuarios.destroy', [$perfil_usuario]) }}" method="post" class="d-inline form-excluir-registro">
                                @method('delete')
                                @csrf
                                <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false">
                                        <i class="ti-trash"></i>
                                </button>
                            </form>
                        @endcan
                    
                    </td>
                </tr>
            @endforeach
        </tbody>
        {{-- <tfoot>
            <tr>
                <td colspan="5">
                    {{ $perfis_usuarios->links() }}
                </td>
            </tr> 
        </tfoot> --}}
    </table>
    <div style="float: right">
        {{ $perfis_usuarios->links() }}
    </div>
</div>