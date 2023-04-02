<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">

                        <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">


                </div>
            </div>
            @can('habilidade_admin', 'cadastrar_instituicao')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicoes.create') }}">
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
            <th></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Nome</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Qtd. Prestadores</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Qtd. Agendas</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($instituicoes as $instituicao)
            <tr>
                <td class="title"><a href="javascript:void(0)">{{ $instituicao->id }}</a></td>
                <td>
                    <img src="
                    @if ($instituicao->imagem)
                        {{ \Storage::cloud()->url($instituicao->imagem) }}
                    @endif
                    " alt="" style="width: 50px; height: 50px;">
                </td>
                <td> {{ $instituicao->nome }} </td>
                <td> {{ $instituicao->prestadoresQtd()->count() }} </td>
                <td> {{ $instituicao->qtdAgenda }} </td>
                <td>
                    @can('habilidade_admin', 'editar_instituicao')
                        <a href="{{ route('instituicoes.edit', [$instituicao]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                        <i class="ti-pencil-alt"></i>
                                </button>
                        </a>
                    @endcan
               @can('habilidade_admin', 'habilitar_instituicao')
                    <form action="{{ route('habilitarDesabilitar', [$instituicao]) }}" method="post" class="d-inline form-habilitar-desabilitar">
                        @method('put')
                        @csrf
                        <button type="button" class="btn btn-xs btn-secondary btn-habilitar-desabilitar"  aria-haspopup="true" aria-expanded="false"
                        data-toggle="tooltip" data-placement="top" data-original-title="Habilitar/Desabilitar">
                            @if ($instituicao->habilitado == false)
                                <i class="ti-close"></i>
                            @else
                                <i class="ti-check"></i>
                            @endif

                        </button>
                    </form>
                @endcan
                {{-- @can('habilidade_admin', 'editar_conta_bancaria_instituicao')
                <a href="{{route('instituicoes.banco', [$instituicao])}}">
                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                    data-toggle="tooltip" data-placement="top" data-original-title="Gerenciar conta bancária">
                            <i class="mdi mdi-bank"></i>
                    </button>
                </a>
                @endcan --}}
                @can('habilidade_admin', 'visualizar_usuario_instituicao')
                <a href="{{ route('instituicao_usuarios.index', [$instituicao]) }}">
                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                    data-toggle="tooltip" data-placement="top" data-original-title="Gerenciar usuários">
                            <i class="ti-user"></i>
                    </button>
                </a>
                @endcan

                @can('habilidade_admin', 'visualizar_instituicao')
                <a href="{{ route('instituicoes.backup', [$instituicao]) }}" target="_blank">
                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                    data-toggle="tooltip" data-placement="top" data-original-title="Exportar dados da instituição">
                            <i class="ti-download"></i>
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
    {{ $instituicoes->links() }}
</div>
</div>

