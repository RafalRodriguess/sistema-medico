<div class="card-body">
                                    
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">
                         
                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">
                        
                         
                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_usuario')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.instituicoes_usuarios.create') }}">
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
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">E-mail</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Status</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($instituicaousuario as $usuario)
            <tr @if(!$usuario->pivot->status) style="background-color: #faebeb" @endif>
                <td class="title"><a href="javascript:void(0)">{{ $usuario->id }}</a></td>
                <td>{{ $usuario->nome }}</td>
                <td>{{ $usuario->cpf }}</td>
                <td>{{ $usuario->email }}</td>
                <td>{{ ($usuario->pivot->status) ? 'Ativo' : 'Inativo' }}</td>
                <td>
                    @can('habilidade_instituicao_sessao', 'habilidade_usuario')
                        <a href="{{ route('instituicao.instituicoes_usuarios.habilidade', [$usuario]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                 data-toggle="tooltip" data-placement="top" data-original-title="Habilidades">
                                        <i class="ti-lock"></i>
                                </button>
                       </a>
                    @endcan
                    {{-- @if (\Auth::user('instituicao')->id == $usuario->id) --}}
                        @can('habilidade_instituicao_sessao', 'editar_usuario')        
                            <a href="{{ route('instituicao.instituicoes_usuarios.edit', [$usuario]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                            <i class="ti-pencil-alt"></i>
                                    </button>
                            </a>

                            <a href="{{ route('instituicao.instituicoes_usuarios.vincularContas', [$usuario]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Vincular contas">
                                        <i class="mdi mdi-bank"></i>
                                </button>
                            </a>
                        @endcan
                    {{-- @endif --}}
                    {{-- @can('habilidade_instituicao_sessao', 'excluir_usuario')
                        <form action="{{ route('instituicao.instituicoes_usuarios.destroy', [$usuario]) }}" method="post" class="d-inline form-excluir-registro">
                            @method('delete')
                            @csrf
                            <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false"
                            data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                    <i class="ti-trash"></i>
                            </button>
                        </form>
                    @endcan --}}
                    
                    @can('habilidade_instituicao_sessao', 'editar_visualizar_prestadores')
                        <button type="button" class="btn btn-xs btn-secondary visualizar_prestadores"  aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Visualizar Prestadores" data-id="{{$usuario->id}}">
                            <i class="mdi mdi-account-settings-variant"></i>
                        </button>
                    @endcan
                    
                    @can('habilidade_instituicao_sessao', 'editar_visualizar_setores_usuario')
                        <button type="button" class="btn btn-xs btn-secondary visualizar_setores"  aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Visualizar Setores" data-id="{{$usuario->id}}">
                            <i class="mdi mdi-account-settings-variant"></i>
                        </button>
                    @endcan

                    @can('habilidade_instituicao_sessao', 'excluir_usuario')
                        <button type="button" class="btn btn-xs btn-secondary btnStatus"  aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Ativar / Inativar" data-id="{{$usuario->id}}">
                            <i class="mdi mdi-account-alert"></i>
                        </button>
                    @endcan
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        {{-- <tr>
            <td colspan="5">
                {{ $instituicaousuario->links() }}
            </td>
        </tr>  --}}
    </tfoot>
</table>
<div style="float: right">
    {{ $instituicaousuario->links() }}
</div>
</div>

<div class="modal_prestadores"></div>

@push('scripts');

    <script>
        $(".btnStatus").on('click', function(){
            $.ajax({
                type: "POST",
                data: {
                    '_token': '{{csrf_token()}}',
                    id: $(this).attr('data-id')
                },
                url: "{{route('instituicao.instituicoes_usuarios.status')}}",
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
                    if(result.icon=="success"){
                        window.location="{{ route('instituicao.instituicoes_usuarios.index') }}";
                    }
                }
            })
        })

        $(".visualizar_prestadores").on('click', function() {
            id = $(this).attr('data-id');

            var url = "{{ route('instituicao.instituicoes_usuarios.visualizarPrestadores', ['usuario' => 'usuarioId']) }}".replace('usuarioId', id);
            var data = {
                '_token': '{{csrf_token()}}'
            };
            var modal = 'modalPrestadores';

            $('#loading').removeClass('loading-off');
            $('.modal_prestadores').load(url, data, function(resposta, status) {
                $('#' + modal).modal();
                $('#loading').addClass('loading-off');
            });
        })

        $(".visualizar_setores").on('click', function() {
            id = $(this).attr('data-id');

            var url = "{{ route('instituicao.instituicoes_usuarios.visualizarSetores', ['usuario' => 'usuarioId']) }}".replace('usuarioId', id);
            var data = {
                '_token': '{{csrf_token()}}'
            };
            var modal = 'modalSetores';

            $('#loading').removeClass('loading-off');
            $('.modal_prestadores').load(url, data, function(resposta, status) {
                $('#' + modal).modal();
                $('#loading').addClass('loading-off');
            });
        })
    </script>
@endpush