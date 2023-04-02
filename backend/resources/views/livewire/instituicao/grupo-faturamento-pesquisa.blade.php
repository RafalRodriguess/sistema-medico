<div>
    {{-- Success is as dangerous as failure. --}}
</div>
<div class="card-body">
    
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
          <div class="col-md-10">
            <div class="form-group" style="margin-bottom: 0px !important;">
               
              <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por descrição...">
              
              
          </div>
      </div>
      @can('habilidade_instituicao_sessao', 'cadastrar_grupo_faturamento')
      <div class="col-md-2">
        <div class="form-group" style="margin-bottom: 0px !important;">
        <a href="{{ route('instituicao.grupoFaturamento.create') }}">
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
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Tipo</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Val. Grupo de Faturamento</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Rateio Nf</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Incide ISS</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($grupos as $grupo)
        <tr>
            <td class="title"><a href="javascript:void(0)">{{ $grupo->id }}</a></td>
            <td>{{ $grupo->descricao }}</td>
            <td>{{ App\GrupoFaturamento::tipoValoresTexto($grupo->tipo) }}</td>
            <td>{{ ($grupo->val_grupo_faturamento) ? 'Sim' : 'Não' }}</td>
            <td>{{ ($grupo->rateio_nf) ? 'Sim' : 'Não' }}</td>
            <td>{{ ($grupo->incide_iss) ? 'Sim' : 'Não' }}</td>
            <td>
                @can('habilidade_instituicao_sessao', 'editar_grupo_faturamento')
                <a href="{{ route('instituicao.grupoFaturamento.edit', [$grupo]) }}">
                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                    <i class="ti-pencil-alt"></i>
                </button>
            </a>
            @endcan
            @can('habilidade_instituicao_sessao', 'ativar_grupo_faturamento')
                <form action="{{ route('instituicao.grupoFaturamento.ativarDesativar', [$grupo]) }}" method="post" class="d-inline form-ativar-desativar">
                    @method('post')
                    @csrf
                    <button type="button" class="btn btn-xs btn-secondary btn-ativar-desativar"  aria-haspopup="true" aria-expanded="false"
                    data-toggle="tooltip" data-placement="top" data-original-title="{{ $grupo->ativo == 1 ? 'Desativar' : 'Ativar' }}">
                    @if(!$grupo->ativo) <i class="fas fa-check"></i> @else <i class="fas fa-times"></i> @endif
                    </button>
                </form>
            @endcan
            @can('habilidade_instituicao_sessao', 'excluir_grupo_faturamento') 
                <form action="{{ route('instituicao.grupoFaturamento.destroy', [$grupo]) }}" method="post" class="d-inline form-excluir-registro">
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
                {{ $grupos->links() }}
            </td>
        </tr>  --}}
    </tfoot>
</table>
<div style="float: right">
    {{ $grupos->links() }}
</div>
</div>