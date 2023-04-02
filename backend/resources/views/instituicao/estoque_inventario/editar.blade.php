@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title',
        [
            'titulo' => "Atualizar inventário de estoque #$inventario->id",
            'breadcrumb' => [
                'Inventários de estoque' => route('instituicao.estoque_inventario.index'),
                'Atualizar inventário de estoque',
            ],
        ])
    @endcomponent

    <div class="card">
        <div class="card-body">
            <form id="main-form" action="{{ route('instituicao.estoque_inventario.update', [$inventario]) }}" method="POST">
                @csrf
                <div class="row">

                    <div class="col-md-4 col-sm-6">
                        <div class="form-group @error('estoque_id') has-danger @enderror">
                            <label class="form-control-label">Estoque <span class="text-danger">*</span></label>
                            <select name="estoque_id" class="form-control @error('estoque_id') form-control-danger @enderror">
                                <option value="">Selecione um estoque</option>
                                @foreach ($estoques as $estoque)
                                    <option value="{{ $estoque->id }}" @if (old('estoque_id', $inventario->estoque_id) == $estoque->id) selected @endif>
                                        {{ $estoque->descricao }}
                                    </option>
                                @endforeach
                            </select>
                            @error('estoque_id')
                                <div class="form-control-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="form-group @if ($errors->has('data')) has-danger @endif">
                            <label class="form-control-label">Data <span class="text-danger">*</span></label>
                            <input type="date" name="data" value="{{ (new \DateTime(old('data', $inventario->data)))->format('Y-m-d') }}"
                                class="form-control  @if ($errors->has('data')) form-control-danger @endif">
                            @if ($errors->has('data'))
                                <div class="form-control-feedback">{{ $errors->first('data') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="form-group @if ($errors->has('hora')) has-danger @endif">
                            <label class="form-control-label"> Hora <span class="text-danger">*</span></label>
                            <input type="time" name="hora" value="{{ old('hora', $inventario->hora) }}"
                                class="form-control  @if ($errors->has('hora')) form-control-danger @endif">
                            @if ($errors->has('hora'))
                                <div class="form-control-feedback">{{ $errors->first('hora') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="form-group @error('tipo_contagem') has-danger @enderror">
                            <label class="form-control-label">Tipo Contagem <span class="text-danger">*</span></label>
                            <select name="tipo_contagem"
                                class="form-control @error('tipo_contagem') form-control-danger @enderror">
                                <option value="">Selecione um tipo contagem</option>
                                <option @if (old('tipo_contagem', $inventario->tipo_contagem) == 'Geral do Estoque') selected @endif value="Geral do Estoque">Geral do
                                    Estoque</option>
                                <option @if (old('tipo_contagem', $inventario->tipo_contagem) == 'Apenas Alguns Produtos') selected @endif value="Apenas Alguns Produtos">
                                    Apenas Alguns Produtos</option>
                            </select>
                            @error('tipo_contagem')
                                <div class="form-control-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="form-group @if ($errors->has('aberta')) has-danger @endif">
                            <label class="form-control-label">Aberta <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="aberta" value="1" id="aberta1"
                                    @if (old('aberta', $inventario->aberta) == 1) checked @endif>
                                <label class="form-check-label" for="aberta1">
                                    Sim
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="aberta" value="0" id="aberta2"
                                    @if (old('aberta', $inventario->aberta) == 0) checked @endif>
                                <label class="form-check-label" for="aberta2">
                                    Não
                                </label>
                            </div>
                            @error('aberta')
                                <div class="form-control-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row col-12">
                    <div class="card col-12 px-0 py-3 shadow-none">
                        <div class="form-group col-md-12">
                            <div class="col-md-8 px-0">
                                <h4
                                    class="form-control-label p-0 mx-0 mb-4 @if ($errors->has('produtos')) has-danger @endif">
                                    Produtos</h4>
                                <div class="input-group">
                                    <div class="col p-0">
                                        <select id="produto-select" style="width: 100%"
                                            class="form-control @if ($errors->has('produtos')) form-control-danger @endif"></select>
                                    </div>
                                    <div class="px-1">
                                        <button onclick="adicionarProduto()" type="button" class="btn btn-primary"><i
                                                class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                @if ($errors->has('produtos'))
                                    <small
                                        class="form-control-feedback text-danger">{{ $errors->first('produtos') }}</small>
                                @endif
                            </div>
                            <div class="mt-4 table-container">
                                <table class="table table-bordered">
                                    <colgroup>
                                        <col style="width: auto">
                                        <col style="width: 50px">
                                        <col style="width: auto">
                                        <col style="width: 20%">
                                        <col style="width: 20%">
                                        <col style="width: 150px">
                                        <col style="width: 50px">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th title="Código do produto">Produto</th>
                                            <th>Lote</th>
                                            <th>Unidade</th>
                                            <th title="Quantidade presente no lote atual">Quantidade</th>
                                            <th title="Quantidade encontrada no inventário">Inventário</th>
                                            <th>Valor</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody id="produtos-container">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group d-flex justify-content-between">
                    <div>
                        <a href="{{ route('instituicao.estoque_inventario.index') }}">
                            <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i
                                    class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                        </a>
                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i
                                class="mdi mdi-check"></i> Salvar e sair</button>
                    </div>
                    <button type="button" id="save-button" class="btn btn-primary waves-effect waves-light m-r-10"><i class="fas fa-save"></i> Salvar e continuar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/template" id="produtos-template">
        <tr class="produtos-entry centered-table-line">
            <input data-name="produto-input" type="hidden" name="produtos[#][id]">
            <input data-name="lote-input" type="hidden" name="produtos[#][lote]">
            <input data-name="quantidade-input" type="hidden" name="produtos[#][quantidade]">
            <td data-name="produto-nome"></td>
            <td data-name="lote"></td>
            <td data-name="unidade"></td>
            <td data-name="quantidade"></td>
            <td><input data-name="quantidade-inventario" type="number" name="produtos[#][quantidade_inventario]" class="form-control"></td>
            <td data-name="valor"></td>
            <td><button type="button" class="btn btn-danger" onclick="removerProduto(this)"><i class="fas fa-trash-alt"></i></button></td>
        </tr>
    </script>
    <script>
        const produto_container = $('#produtos-container');
        const template_produto = new HtmlTemplate('#produtos-template');
        const select = $('#produto-select');
        var produtos_selecionados = [];
        var ultimo_id_produto = 0;

        function adicionarProduto(produto = null) {
            // Caso não seja passado, busca do select
            if (!produto) {
                produto = JSON.parse(select.val());
            }
            // Verifica se já existe um inserido (array.reduce n está funcionando aqui)
            let result = false;
            produtos_selecionados.map((item) => {
                result |= item.lote == produto.lote;
            });
            //  Caso ja exista retorna
            if (result) {
                return;
            }
            // Salva e sobe o id local na lista de produtos adicionados
            produto.__local_id = `produto-selecionado-${ultimo_id_produto}`;
            produtos_selecionados.push(produto);

            // Insere o html a partir do template
            const elemento = template_produto.create({
                    'self': {
                        id: produto.__local_id
                    },
                    'produto-input': produto.id_produto,
                    'produto-nome': produto.descricao,
                    'lote-input': produto.lote,
                    'quantidade-input': produto.quantidade_maxima,
                    'quantidade-inventario': produto.quantidade_inventario ?? 0,
                    'lote': produto.lote,
                    'valor': 'R$ ' + (new String(produto.valor)).replace('.', ','),
                    'unidade': produto.unidade,
                    'quantidade': {
                        html: `<span class="${produto.quantidade_maxima < 0 ? 'text-danger' : ''}">${produto.quantidade_maxima}</span>`
                    },
                    'remover-produto-button': {
                        onclick: (e) => removerProduto($(e.target).parents('.produtos-item'))
                    }
                },
                ultimo_id_produto++
            );

            produto_container.append(elemento);
        }

        function removerProduto(element) {
            const index = produtos_selecionados.findIndex(el => el.__local_id == element[0].id);
            if (index != -1) {
                $(`#${produtos_selecionados[index].__local_id}`).remove();
                produtos_selecionados.splice(index, 1);
            }
        }

        $(document).ready(() => {
            select.select2({
                placeholder: "Busque a entrada pelo lote ou pelo nome do produto",
                ajax: {
                    url: '{{ route("instituicao.ajax.getentradaprodutos") }}',
                    type: 'post',
                    dataType: 'json',
                    quietMillis: 20,
                    data: function(params) {
                        return {
                            search: params.term,
                            '_token': '{{ csrf_token() }}',
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(obj) {
                                return {
                                    id: JSON.stringify(obj),
                                    text: `${obj.lote} - ${obj.descricao} [Un: ${obj.unidade}]`,
                                    title: `Lote: "${obj.lote}" Id. Produto: "${obj.id}" Desc. Produto: "${obj.descricao}" Forn.: "${obj.fornecedor ?? 'inexistente'}" Quant.: "${obj.quantidade_maxima}"`
                                };
                            })
                        }
                    }
                },
                minimumInputLength: 3,
                language: {
                    searching: function () {
                        return 'Buscando produtos no estoque';
                    },

                    noResults: function () {
                        return 'Nenhum resultado encontrado';
                    },

                    inputTooShort: function (input) {
                        return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar";
                    },
                },
                escapeMarkup: function(m) {
                    return m;
                }
            });

            $('#save-button').on('click', e => {
                const form = $('#main-form');
                const formData = new FormData(form[0]);

                $.ajax(form.attr('action'), {
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        $.toast({
                            heading: 'Dados salvos com sucesso!',
                            text: 'Os dados foram salvos com sucesso.',
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 3000,
                            stack: 10
                        });
                        if(response.icon=="success"){
                            window.location="{{ route('administradores.index') }}";
                        }
                    },
                    error: function (response) {
                        if(response.responseJSON.errors){
                            Object.keys(response.responseJSON.errors).forEach(function(key) {
                                $.toast({
                                    heading: 'Erro ao salvar os dados',
                                    text: response.responseJSON.errors[key][0],
                                    position: 'top-right',
                                    loaderBg: '#ff6849',
                                    icon: 'error',
                                    hideAfter: 9000,
                                    stack: 10
                                });

                            });
                        }
                    }
                });
            });

            // Preenchendo produtos
            const produtos_antigos = Array.from({!! json_encode(old('produtos', $produtos_selecionados)) !!});
            if (produtos_antigos.length > 0) {
                $.ajax('{{ route("instituicao.ajax.getentradaprodutos") }}', {
                    method: "POST",
                    data: {
                        "_token": '{{ csrf_token() }}',
                        lotes_produtos: produtos_antigos.map(item => item.lote ?? null),
                    },
                    success: (response) => {
                        Array.from(response).forEach(entrada => {
                            const index = produtos_antigos.findIndex(el => el.lote == entrada.lote);
                            if(index != -1) {
                                entrada.quantidade_inventario = produtos_antigos[index].quantidade_inventario;
                            }
                            adicionarProduto(entrada);
                        })
                    }
                })
            };
        })
    </script>
@endpush
