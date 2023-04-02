<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
            <div class="row">
                  <div class="col-md-10">
                    <div class="form-group" style="margin-bottom: 0px !important;">

                          <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">


                    </div>
                  </div>
                    @can('habilidade_instituicao_sessao', 'cadastrar_convenio_planos')
                        <div class="col-md-2">
                            <div class="form-group" style="margin-bottom: 0px !important;">
                                <a href="{{ route('instituicao.convenios.planos.create', [$convenio]) }}">
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
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="1">Nome</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($convenioPlanos as $convenioPlano)
            <tr>
                <td class="title"><a href="javascript:void(0)">{{ $convenio->id }}</a></td>

                <td>{{ $convenioPlano->nome }}</td>
                <td>



                    @can('habilidade_instituicao_sessao', 'editar_convenio_planos')
                        <a href="{{ route('instituicao.convenios.planos.edit', [$convenio, $convenioPlano]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                        <i class="ti-pencil-alt"></i>
                                </button>
                        </a>
                    @endcan

                    @can('habilidade_instituicao_sessao', 'excluir_convenio_planos')
                            <form action="{{ route('instituicao.convenios.planos.destroy', [$convenio, $convenioPlano]) }}" method="post" class="d-inline form-excluir-registro">
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
                {{ $comerciais->links() }}
            </td>
        </tr>  --}}
    </tfoot>
</table>
<div style="float: right">
    {{ $convenioPlanos->links() }}
</div>
</div>
