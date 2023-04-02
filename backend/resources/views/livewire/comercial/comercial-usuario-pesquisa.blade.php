<div class="card-body">
                                    
    <form action="javascript:void(0)" id="FormTitular">
            <div class="row">
                  <div class="col-md-10">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                         
                          <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">
                        
                         
                    </div>
                  </div>
                  @can('habilidade_comercial_sessao', 'cadastrar_usuario')
                        <div class="col-md-2">
                            <div class="form-group" style="margin-bottom: 0px !important;">
                                <a href="{{ route('comercial.comerciais_usuarios.create') }}">
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
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Nome</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">CPF</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                E-mail
            </th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($comercialusuario as $usuario)
            <tr>
                <td class="title"><a href="javascript:void(0)">{{ $usuario->id }}</a></td>
                <td>{{ $usuario->nome }}</td>
                <td>{{ $usuario->cpf }}</td>
                <td>{{ $usuario->email }}</td>
                <td>
                    @can('habilidade_comercial_sessao', 'habilidades_usuario')
                        <a href="{{ route('comercial.comerciais_usuarios.habilidade', [$usuario]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                 data-toggle="tooltip" data-placement="top" data-original-title="Habilidades">
                                        <i class="ti-lock"></i>
                                </button>
                       </a>
                    @endcan
                    @if (\Auth::user('comercial')->id == $usuario->id)
                        @can('habilidade_comercial_sessao', 'editar_usuario')        
                            <a href="{{ route('comercial.comerciais_usuarios.edit', [$usuario]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                            <i class="ti-pencil-alt"></i>
                                    </button>
                            </a>
                        @endcan
                    @endif
                    @can('habilidade_comercial_sessao', 'excluir_usuario')
                        <form action="{{ route('comercial.comerciais_usuarios.destroy', [$usuario]) }}" method="post" class="d-inline form-excluir-registro">
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
                {{ $comercialusuario->links() }}
            </td>
        </tr>  --}}
    </tfoot>
</table>
<div style="float: right">
    {{ $comercialusuario->links() }}
</div>
</div>