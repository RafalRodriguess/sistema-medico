<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">

                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control"
                        placeholder="Pesquise por componente...">


                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_totens')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.triagem.classificacoes.create') }}">
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
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                    data-tablesaw-priority="3">Cor</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                    data-tablesaw-priority="3">
                    Descrição
                </th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                    data-tablesaw-priority="3">
                    Prioridade
                </th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($classificacoes as $classificacao)
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $classificacao->id }}</a></td>
                    <td><span data-color="{{ $classificacao->cor }}" class="btn color-sticker" style="background-color: {{ $classificacao->cor }}; pointer-events: none; font-weight: bold;">{{ strtoupper($classificacao->cor) }}</span></td>
                    <td>{{ $classificacao->descricao }}</td>
                    <td>{{ $classificacao->prioridade }}</td>
                    <td>
                        @can('habilidade_instituicao_sessao', 'editar_totens')
                            <a href="{{ route('instituicao.triagem.classificacoes.edit', [$classificacao]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true"
                                    aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                    <i class="ti-pencil-alt"></i>
                                </button>
                            </a>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'excluir_totens')
                            <form action="{{ route('instituicao.triagem.classificacoes.destroy', [$classificacao]) }}" method="post"
                                class="d-inline form-excluir-registro">
                                @method('delete')
                                @csrf
                                <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"
                                    aria-haspopup="true" aria-expanded="false"
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
        {{ $classificacoes->links() }}
    </div>
</div>
@push('scripts')
    <script>
        /**
         * #region Métodos de calcular inverso da cor
         * @source https://stackoverflow.com/questions/35969656/how-can-i-generate-the-opposite-color-according-to-current-color
         * */

        function padZero(str, len) {
            len = len || 2;
            var zeros = new Array(len).join('0');
            return (zeros + str).slice(-len);
        }

        function invertColor(hex) {
            if (hex.indexOf('#') === 0) {
                hex = hex.slice(1);
            }
            // convert 3-digit hex to 6-digits.
            if (hex.length === 3) {
                hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
            }
            if (hex.length !== 6) {
                throw new Error('Invalid HEX color.');
            }
            // invert color components
            var r = (255 - parseInt(hex.slice(0, 2), 16)).toString(16),
                g = (255 - parseInt(hex.slice(2, 4), 16)).toString(16),
                b = (255 - parseInt(hex.slice(4, 6), 16)).toString(16);
            // pad each with zeros and return
            return '#' + padZero(r) + padZero(g) + padZero(b);
        }

        // #endregion

        $(document).ready(() => {
            $('.color-sticker').each((key, item) => {
                const element = $(item);
                let corFundo = element.attr('data-color');
                element.css('color', invertColor(corFundo));
            });
        });
    </script>
@endpush