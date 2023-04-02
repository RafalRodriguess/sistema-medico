<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group" style="margin-bottom: 0px !important;" wire:ignore>

                    <select name="pesquisa" class="form-control select2agenda" wire:model="pesquisa">
                    <option value="0">Paciente</option>
                    </select>


                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group" style="margin-bottom: 0px !important;" wire:ignore>

                    <select name="exibirTodos" class="form-control selectfild2" wire:model="exibirTodos">
                    <option value="0">Somente não agendados</option>
                    <option value="1">Todos</option>
                    </select>


                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_agendamentos_lista_espera')
                <div class="col-md-3">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.agendamentosListaEspera.create') }}">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                        </a>
                    </div>
                </div>
            @endcan
        </div>
    </form>

    <hr>

<div class="table-responsive">
    <table class="tablesaw table-bordered table-hover table">
        <thead>
            <tr>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Paciente</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Telefone</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Data</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Convenio</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Prestador/Especialidade</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Obs</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($listaEspera as $item)

                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $item->id }}</a></td>
                    <td>{{ $item->pessoa->nome }}</td>
                    <td>{{ $item->pessoa->telefone1 }}</td>
                    <td>{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                    <td>{{ ($item->convenio_id) ? $item->convenio->nome : "" }}</td>
                    <td>{{ ($item->prestador_id) ? $item->prestadorExcluidos->nome : $item->especialidadeExcluidos->descricao }}</td>
                    <td>{{ $item->obs }}</td>
                    <td>
                        @can('habilidade_instituicao_sessao', 'editar_agendamentos_lista_espera')
                            <a href="{{ route('instituicao.agendamentosListaEspera.edit', [$item]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                    <i class="ti-pencil-alt"></i>
                                </button>
                            </a>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'excluir_agendamentos_lista_espera')
                            <form action="{{ route('instituicao.agendamentosListaEspera.destroy', [$item]) }}"
                                method="post" class="d-inline form-excluir-registro">
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
    </table>
</div>

<div style="float: right">
    {{ $listaEspera->links() }}
</div>
</div>

@push('scripts')
    <script>
        $(document).ready(function(){
            $(".select2agenda").select2({
                placeholder: "Pesquise por nome ou cpf",
                allowClear: true,
                minimumInputLength: 3,
                // tags: true,
                // createTag: function (params) {
                // var term = $.trim(params.term);

                // return {
                //     id: term,
                //     text: term + ' (Novo Paciente)',
                //     newTag: true
                // }
                // },
                language: {
                searching: function () {
                    return 'Buscando paciente (aguarde antes de selecionar)…';
                },
                
                inputTooShort: function (input) {
                    return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar"; 
                },
                },    
                
                ajax: {
                    url:"{{route('instituicao.agendamentos.getPacientes')}}",
                    dataType: 'json',
                    delay: 100,

                    data: function (params) {
                    return {
                        q: params.term || '', // search term
                        page: params.page || 1
                    };
                    },
                    processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: _.map(data.results, item => ({
                            id: Number.parseInt(item.id),
                            text: `${item.nome} ${(item.cpf) ? '- ('+item.cpf+')': ''}`,
                        })),
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                    },
                    cache: true
                },

            })
        })
    </script>
@endpush