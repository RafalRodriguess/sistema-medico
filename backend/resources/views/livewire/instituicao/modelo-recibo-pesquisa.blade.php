<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">

                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control"
                        placeholder="Pesquise por descrição...">


                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_modelo_recibo')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.modelosRecibo.create') }}">
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
                <th scope="col">ID</th>
                <th scope="col">Descrição</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($modelos as $modelo)
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $modelo->id }}</a></td>
                    <td>{{ $modelo->descricao }}</td>
                    <td>
                        <button type="button" class="btn btn-xs btn-secondary visualizarModelo" data-id="{{$modelo->id}}" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Visualizar modelo">
                            <i class="mdi mdi-eye"></i>
                        </button>
                        @can('habilidade_instituicao_sessao', 'editar_modelo_recibo')
                            <a href="{{ route('instituicao.modelosRecibo.edit', [$modelo]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                    <i class="ti-pencil-alt"></i>
                                </button>
                            </a>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'excluir_modelo_recibo')
                            <form action="{{ route('instituicao.modelosRecibo.destroy', [$modelo]) }}" method="post" class="d-inline form-excluir-registro">
                                @method('delete')
                                @csrf
                                <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                        <i class="ti-trash"></i>
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div style="float: right">
        {{ $modelos->links() }}
    </div>
    <div id="visualizarRecibo"></div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.visualizarModelo').on('click', function(){
                modelo_id = $(this).data('id')
                console.log()

                $.ajax("{{route('instituicao.modelosRecibo.getModelo', ['modelo' => 'modelo_id'])}}".replace('modelo_id', modelo_id),{
                    type: 'GET',
                    data: {'_token': '{{csrf_token()}}'},
                    success: function (result) {
                        $("#visualizarRecibo").html(result);
                        $("#modalVisualizarRecibo").modal('show');
                    }
                });
            });
        });
    </script>
@endpush
