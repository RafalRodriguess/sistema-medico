<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">

                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control"
                        placeholder="Pesquise por nome prestador...">


                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_modelo_impressao')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.modeloImpressao.create') }}">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
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
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">
                    Prestador</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">
                    Especialidade</th>
                <th scope="col" data-tablesaw-priority="3">
                    Ações
                </th>
                {{-- <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                Via Administração
            </th> --}}
                {{-- <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($modelos as $modelo)
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $modelo->id }}</a></td>
                    <td>{{ $modelo->instituicaoPrestador->prestador->nome }}</td>
                    <td>{{ ($modelo->instituicaoPrestador->especialidade) ? $modelo->instituicaoPrestador->especialidade->descricao : "" }}</td>
                    {{-- <td>{{ $procedimento->via_administracao }}</td> --}}
                    <td>
                        @can('habilidade_instituicao_sessao', 'editar_modelo_impressao')
                            <a href="{{ route('instituicao.modeloImpressao.edit', [$modelo]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true"
                                    aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                    <i class="ti-pencil-alt"></i>
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
                {{ $procedimentos->links() }}
            </td>
        </tr> --}}
        </tfoot>
    </table>
    <div style="float: right">
        {{ $modelos->links() }}
    </div>
</div>
