<div class="card-body">
                                    
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <input type="text" id="pesquisa" 
                        wire:model.lazy="pesquisa" name="pesquisa"
                        class="form-control" 
                        placeholder="Pesquise por nome...">
                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_setores')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.setores.create') }}">
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
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Nome</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Descrição</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($setores as $setor)
                    {{-- <tr data-toggle="collapse" data-target="#example_{{$setor->id}}" class="accordion-toggle"> --}}
                    <tr>
                        <td class="title"><a href="javascript:void(0)">{{ $setor->id }}</a></td>
                        
                        <td>{{ $setor->nome }}</td>
                        <td>{{ $setor->descricao }}</td>
                        <td>
                            @can('habilidade_instituicao_sessao', 'habilidades_setores')
                                <a href="{{ route('setores.habilidades', [$setor]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Habilidades">
                                            <i class="ti-lock"></i>
                                    </button>
                                </a>
                            @endcan
                            @can('habilidade_instituicao_sessao', 'editar_setores')
                                <a href="{{ route('instituicao.setores.edit', [$setor]) }}">
                                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                                <i class="ti-pencil-alt"></i>
                                        </button>
                                </a>
                            @endcan
                            
                            @can('habilidade_instituicao_sessao', 'excluir_setores')
                                <form action="{{ route('instituicao.setores.destroy', [$setor]) }}" method="post" class="d-inline form-excluir-registro">
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
                    {{-- <tr class="accordian-body collapse" id="example_{{$setor->id}}">
                        <td colspan="6" class="hiddenRow">
                            <div class="col-sm-12">
                                <div class="row d-flex justify-content-center">
                                    <div class="col-3">
                                        {{ $setor->id }}
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr> --}}
                    {{-- Teste --}}
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="float: right">
        {{ $setores->links() }}
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
