@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title',
        [
            'titulo' => 'Vincular Tuss',
            'breadcrumb' => ['Vincular Tuss'],
        ])
    @endcomponent

    @can('habilidade_instituicao_sessao', 'cadastrar_vincular_tuss')
        <div class="card">
            <div class="card-body ">
                <h3>Novos vinculos</h3>
                <div class="row">
                    <div class="col text-right">
                        @can('habilidade_instituicao_sessao', 'importar_vincular_tuss')
                            <a href="{{ route('instituicao.vinculoTuss.selecionarImportacao') }}"
                                class="btn btn-info waves-effect waves-light">Importar</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    @endcan

    <div class="card">
        <div class="card-body">
            <div class="scrolling-pagination">
                <table class="tablesaw table-bordered table-hover table">
                    <thead>
                        <tr>
                            <th>Cod</th>
                            <th>Terminologia</th>
                            <th>Termo</th>
                            <th>Descrição detalhada</th>
                            <th>Data de inicio da vigência</th>
                            <th>Data de fim da vigência</th>
                            <th>Data de fim da implantação</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tabelaTuss as $item)
                            <tr>
                                <td>{{ $item->cod_termo }}</td>
                                <td>{{ $item->terminologia->cod_tabela }} - {{ $item->terminologia->descricao }}</td>
                                <td>{{ $item->termo }}</td>
                                <td>{{ $item->descricao_detalhada }}</td>
                                <td>{{ date('d/m/Y', strtotime($item->data_vigencia)) }}</td>
                                <td>{{ $item->data_vigencia_fim ? date('d/m/Y', strtotime($item->data_vigencia_fim)) : '' }}
                                </td>
                                <td>{{ date('d/m/Y', strtotime($item->data_implantacao_fim)) }}</td>
                                <td>
                                    @can('habilidade_instituicao_sessao', 'excluir_vincular_tuss')
                                        <form action="{{ route('instituicao.vinculoTuss.destroy', [$item]) }}" method="post"
                                            class="d-inline form-excluir-registro">
                                            @method('post')
                                            @csrf
                                            <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"
                                                aria-haspopup="true" aria-expanded="false" data-toggle="tooltip"
                                                data-placement="top" data-original-title="Excluir">
                                                <i class="ti-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $tabelaTuss->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>
    <script type="text/javascript">
        var quantidade_proc = 0;
        $('ul.pagination').hide();

        $(function() {
            $('.scrolling-pagination').jscroll({
                loadingHtml: '<div class="spinner-border text-secondary" role="status"><span class="sr-only">Loading...</span></div>',
                autoTrigger: true,
                padding: 0,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.scrolling-pagination',
                callback: function() {
                    $('ul.pagination').remove();
                }
            });
        });

        $('.procedimento-itens').on('click', '.item-proc .remove-proc', function(e) {
            e.preventDefault()

            $(e.currentTarget).parents('.item-proc').remove();
            if ($('.procedimento-itens').find('.item-proc').length == 0) {
                quantidade_proc = 0;
            }
        });
    </script>
@endpush
