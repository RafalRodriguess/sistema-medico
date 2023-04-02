
<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <input type="text" id="pesquisa"  wire:model.lazy="pesquisa" name="pesquisa"
                        class="form-control" placeholder="Pesquise por regra...">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <select name="origem_id" id="origem_id" class="form-control"
                        wire:change="queryByorigem($event.target.value)">
                        <option selected value="0">Todas as Origens</option>
                        @foreach ($origens as $origem)
                            <option value="{{ $origem->id }}">{{ $origem->descricao }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <select name="especialidade_id" id="especialidade_id" class="form-control"
                        wire:change="queryByEspecialidade($event.target.value)">
                        <option selected value="0">Todas as Especialidade</option>
                        @foreach ($especialidades as $especialidade)
                            <option value="{{ $especialidade->id }}">{{ $especialidade->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @can('habilidade_instituicao_sessao', 'cadastrar_escalas_medicas')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.escalas-medicas.create') }}">
                        <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                        </a>
                    </div>
                </div>
            @endcan
        </div>
    </form>

    <hr>

<div class="table-responsive">
        <table class="tablesaw table-bordered table-hover table" >
            <thead>
                <tr>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Regra</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Data</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Especialidade</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Origem</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($escalas_medicas as $escala_medica)
                    <tr>
                        <td class="title">{{ $escala_medica->id }}</td>
                        <td>{{ $escala_medica->regra }}</td>
                        <td>{{ $escala_medica->data}}</td>
                        <td>@if ($escala_medica->especialidade) {{ $escala_medica->especialidade->nome }} @endif</td>
                        <td>@if ($escala_medica->origem) {{ $escala_medica->origem->descricao }} @endif</td>
                        <td>
                            @can('habilidade_instituicao_sessao', 'editar_escalas_medicas')
                                <a href="{{ route('instituicao.escalas-medicas.edit', [$escala_medica]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                        <i class="ti-pencil-alt"></i>
                                    </button>
                                </a>
                            @endcan

                            @can('habilidade_instituicao_sessao', 'duplicar_escalas_medicas')
                                <form action="{{ route('instituicao.escalasmedicas.duplicar', [$escala_medica]) }}" method="post" class="d-inline form-excluir-registro">
                                    @method('post')
                                    @csrf
                                    <input type="hidden" name="id" value="{{$escala_medica->id}}">
                                    <input type="hidden" name="regra" value="{{$escala_medica->regra}}">
                                    <input type="hidden" name="data" value="{{$escala_medica->data}}">
                                    <input type="hidden" name="origem_id" value="{{$escala_medica->origem_id}}">
                                    <input type="hidden" name="especialidade_id" value="{{$escala_medica->especialidade->id}}">
                                    <button type="submit" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Duplicar">
                                        <i class="ti-layers"></i>
                                    </button>
                                </form>
                            @endcan

                            @can('habilidade_instituicao_sessao', 'excluir_escalas_medicas')
                                <form action="{{ route('instituicao.escalas-medicas.destroy', [$escala_medica]) }}" method="post" class="d-inline form-excluir-registro">
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
                    {{-- Teste --}}
                    {{-- <tr>
                        <td colspan="6" class="hiddenRow">
                            <div class="accordian-body collapse" id="example_{{$escala_medica->id}}">   Demo Content1
                            </div>
                        </td>
                    </tr> --}}
                    {{-- Teste --}}
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="float: right">
        {{ $escalas_medicas->links() }}
    </div>
</div>


{{-- @push('scripts')

    <script type="text/javascript">

        $(document).ready(function(){

            $('.accordian-body').on('show.bs.collapse', function () {
                $(this).closest("table")
                    .find(".collapse.in")
                    .not(this)
                    .collapse('toggle')
            })

        });

    </script>

@endpush --}}


