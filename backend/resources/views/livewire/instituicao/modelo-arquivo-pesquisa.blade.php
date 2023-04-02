<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">

                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control"
                        placeholder="Pesquise por descrição...">


                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_modelo_arquivo')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.modeloArquivo.create') }}">
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
                    Descrição</th>
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
                    <td>{{ $modelo->descricao }}</td>
                    {{-- <td>{{ $procedimento->via_administracao }}</td> --}}
                    <td>
                        @can('habilidade_instituicao_sessao', 'editar_modelo_arquivo')
                            <a href="{{ route('instituicao.modeloArquivo.edit', [$modelo]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true"
                                    aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                    <i class="ti-pencil-alt"></i>
                                </button>
                            </a>
                        @endcan
                        <button type="button" class="btn btn-xs btn-secondary visualisar-arquivo" aria-haspopup="true" aria-expanded="false"
                            data-toggle="tooltip" data-placement="top" data-id="{{$modelo->id}}" data-original-title="Visualizar" >
                            <i class="ti-eye"></i>
                        </button>
                        <a href="{{route('instituicao.modeloArquivo.baixarArquivo', [$modelo])}}">
                            <button type="button" class="btn btn-xs btn-secondary download-arquivo" aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-id="{{$modelo->id}}" data-original-title="Baixar" >
                                <i class="ti-download"></i>
                            </button>
                        </a>
                        @can('habilidade_instituicao_sessao', 'excluir_modelo_arquivo')
                            <form action="{{ route('instituicao.modeloArquivo.destroy', [$modelo]) }}" method="post" class="d-inline form-excluir-registro">
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
        </tr> --}}
        </tfoot>
    </table>
    <div style="float: right">
        {{ $modelos->links() }}
    </div>
</div>

<div id="modal_visualizar_documento"></div>

@push('scripts')
    <script>
        $(".visualisar-arquivo").on('click', function() {
            var arquivo_id = $(this).attr('data-id');
            
            var url = "{{ route('instituicao.modeloArquivo.visualizarArquivo', ['arquivo' => 'arquivo_id']) }}".replace('arquivo_id', arquivo_id);
            var data = {
                '_token': '{{csrf_token()}}'
            };
            var modal = 'modal-view-documento';
            
            $('#loading').removeClass('loading-off');
            $('#modal_visualizar_documento').load(url, data, function(resposta, status) {
                $('#' + modal).modal();
                $('#loading').addClass('loading-off');
            });
        });
    </script>
@endpush
