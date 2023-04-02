<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
            <div class="row">
                  <div class="col-md-10">
                    <div class="form-group" style="margin-bottom: 0px !important;">

                          <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome fantasia ou razão social...">


                    </div>
                  </div>
                    @can('habilidade_admin', 'cadastrar_comercial')
                        <div class="col-md-2">
                            <div class="form-group" style="margin-bottom: 0px !important;">
                                <a href="{{ route('comercial.create') }}">
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
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Nome Fantasia</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">CNPJ</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                E-mail
            </th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                Telefone
            </th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($comerciais as $comercial)
            <tr>
                <td class="title"><a href="javascript:void(0)">{{ $comercial->id }}</a></td>
                <td>
                    <img src="
                    @if ($comercial->logo)
                        {{ \Storage::cloud()->url($comercial->logo) }}
                    @endif
                    " alt="" style="height: 50px;">
                </td>
                <td>{{ $comercial->nome_fantasia }}</td>
                <td>{{ $comercial->cnpj }}</td>
                <td>{{ $comercial->email }}</td>
                <td>{{ $comercial->telefone }}</td>
                <td>
                    @can('habilidade_admin', 'editar_comercial')
                        <a href="{{ route('comercial.edit', [$comercial]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                        <i class="ti-pencil-alt"></i>
                                </button>
                        </a>
                    @endcan
               @can('habilidade_admin', 'excluir_comercial')
                    <form action="{{ route('comercial.destroy', [$comercial]) }}" method="post" class="d-inline form-excluir-registro">
                        @method('delete')
                        @csrf
                        <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false"
                        data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                <i class="ti-trash"></i>
                        </button>
                    </form>
                @endcan
                @can('habilidade_admin', 'editar_conta_bancaria_comercial')
                <a href="{{route('comercial.banco', [$comercial])}}">
                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                    data-toggle="tooltip" data-placement="top" data-original-title="Gerenciar conta bancária">
                            <i class="mdi mdi-bank"></i>
                    </button>
                </a>
                @endcan
                @can('habilidade_admin', 'visualizar_usuario_comercial')
                <a href="{{ route('comercial_usuarios.index', [$comercial]) }}">
                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                    data-toggle="tooltip" data-placement="top" data-original-title="Gerenciar usuários">
                            <i class="ti-user"></i>
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
                {{ $comerciais->links() }}
            </td>
        </tr>  --}}
    </tfoot>
</table>
<div style="float: right">
    {{ $comerciais->links() }}
</div>
</div>
