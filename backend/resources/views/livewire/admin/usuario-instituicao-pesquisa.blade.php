<div class="card-body">
                                    
    <form action="javascript:void(0)" id="FormTitular">
            <div class="row">
                  <div class="col-md-10">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                         
                          <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">
                        
                         
                    </div>
                  </div>
                  @can('habilidade_admin', 'cadastrar_usuario_instituicao')
                                                      <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                         <a href="{{ route('instituicao_usuarios.create', [$instituicao]) }}">
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
        @foreach($instituicaousuarios as $usuario)
            <tr>
                <td class="title"><a href="javascript:void(0)">{{ $usuario->id }}</a></td>
                <td>{{ $usuario->nome }}</td>
                <td>{{ $usuario->cpf }}</td>
                <td>{{ $usuario->email }}</td>
                <td>
                    @can('habilidade_admin', 'habilidades_usuario_instituicao')
                        <a href="{{ route('habilidadesInstituicao', [$instituicao, $usuario]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                 data-toggle="tooltip" data-placement="top" data-original-title="Habilidades">
                                        <i class="ti-lock"></i>
                                </button>
                       </a>
                    @endcan
                @can('habilidade_admin', 'editar_usuario_instituicao')    
                    <a href="{{ route('instituicao_usuarios.edit', [$instituicao, $usuario]) }}">
                            <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                            data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                    <i class="ti-pencil-alt"></i>
                            </button>
                    </a>
                @endcan
               @can('habilidade_admin', 'excluir_usuario_instituicao')
                    <form action="{{ route('instituicao_usuarios.destroy', [$instituicao, $usuario]) }}" method="post" class="d-inline form-excluir-registro">
                        @method('delete')
                        @csrf
                        <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false"
                        data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                <i class="ti-trash"></i>
                        </button>
                    </form>
                @endcan

                    <button type="button" class="btn btn-xs btn-secondary btnStatus"  aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Ativar / Inativar" data-id="{{$usuario->id}}" data-instituicao="{{$instituicao->id}}">
                        <i class="mdi mdi-account-alert"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        {{-- <tr>
            <td colspan="5">
                {{ $instituicaousuarios->links() }}
            </td>
        </tr>  --}}
    </tfoot>
</table>
<div style="float: right">
    {{ $instituicaousuarios->links() }}
</div>
</div>

@push('scripts')
    <script>
         $(".btnStatus").on('click', function(){
            instituicao_id = $(this).attr('data-instituicao')
            $.ajax({
                type: "POST",
                data: {
                    '_token': '{{csrf_token()}}',
                    id: $(this).attr('data-id')
                },
                url: "{{route('admin.instituicao_usuarios.status', ['instituicao' => 'instituicao_id'])}}".replace('instituicao_id', instituicao_id),
                datatype: "json",
                success: function(result) {
                    // result = JSON.parse(result);
                    $.toast({
                        heading: result.title,
                        text: result.text,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: result.icon,
                        hideAfter: 3000,
                        stack: 10
                    });
                    
                }
            })
        })
    </script>
@endpush