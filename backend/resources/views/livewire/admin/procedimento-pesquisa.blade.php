<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
          <div class="col-md-10">
            <div class="form-group" style="margin-bottom: 0px !important;">

              <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por componente...">


          </div>
      </div>
      @can('habilidade_admin', 'cadastrar_procedimentos')
      <div class="col-md-2">
        <div class="form-group" style="margin-bottom: 0px !important;">
           <a href="{{ route('procedimentos.create') }}">
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
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                Tipo
            </th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">
                Modalidade
            </th>
            {{-- <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                Via Administração
            </th> --}}
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($procedimentos as $procedimento)
        <tr>
            <td class="title"><a href="javascript:void(0)">{{ $procedimento->id }}</a></td>
            <td>{{ $procedimento->descricao }}</td>
            <td>{{ $procedimento->tipo }}</td>
            <td>{{ !empty($procedimento->modalidade) ? $procedimento->modalidade->sigla : 'n/a'}}</td>
            {{-- <td>{{ $procedimento->via_administracao }}</td> --}}
            <td>
                @can('habilidade_admin', 'editar_procedimentos')
                <a href="{{ route('procedimentos.edit', [$procedimento]) }}">
                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                    <i class="ti-pencil-alt"></i>
                </button>
            </a>
            @endcan
            @can('habilidade_admin', 'excluir_procedimentos')
            <form action="{{ route('procedimentos.destroy', [$procedimento]) }}" method="post" class="d-inline form-excluir-registro">
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
                {{ $procedimentos->links() }}
            </td>
        </tr>  --}}
    </tfoot>
</table>
<div style="float: right">
    {{ $procedimentos->links() }}
</div>
</div>
