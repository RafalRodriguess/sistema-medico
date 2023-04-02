<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">

                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">


                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_convenios')
            <div class="col-md-2">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <a href="{{ route('instituicao.convenios.create') }}">
                        <button type="button" class="btn waves-effect waves-light btn-block btn-info">Vincular</button>
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
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Descrição</th>

                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($convenios as $convenio)
            <tr>
                <td class="title"><a href="javascript:void(0)">{{ $convenio->id }}</a></td>
                <td>{{ $convenio->nome }}</td>
                <td>
                    
                    @can('habilidade_instituicao_sessao', 'editar_convenios')
                    <a href="{{ route('instituicao.convenios.edit', [$convenio]) }}">
                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-stethoscope"></i> Procedimentos
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
                    {{ $convenios->links() }}
            </td>
            </tr> --}}
        </tfoot>
    </table>
    <div style="float: right">
        {{ $convenios->links() }}
    </div>
</div>