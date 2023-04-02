@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar entrada de estoque',
        'breadcrumb' => [
            'Entradas de estoque' => route('instituicao.estoque_entrada.index'),
            'Nova entrada',
        ],
    ])
    @endcomponent


    <div class="card">
        <form action="{{ route('instituicao.estoque_entrada.store') }}" method="POST">
            <div class="card-body row">
                @csrf
                <div class="col-sm-6">
                    <div class="form-group @error('id_tipo_documento') has-danger @enderror">
                        <label class="form-control-label">Tipo de Documento <span class="text-danger">*</span></label>
                        <select name="id_tipo_documento"
                            class="form-control select2basic @error('id_tipo_documento') form-control-danger @enderror">
                            <option value="">Selecione um tipo de documento</option>
                            @foreach ($tiposDocumentos as $tiposDocumento)
                                <option value="{{ $tiposDocumento->id }}"
                                    @if (old('id_tipo_documento') == $tiposDocumento->id) selected="selected" @endif>
                                    {{ $tiposDocumento->descricao }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_tipo_documento')
                            <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group @error('id_estoque') has-danger @enderror">
                        <label class="form-control-label">Estoque <span class="text-danger">*</span></label>
                        <select name="id_estoque"
                            class="form-control select2basic @error('id_estoque') form-control-danger @enderror">
                            <option value="">Selecione um estoque</option>
                            @foreach ($estoques as $estoque)
                                <option value="{{ $estoque->id }}"
                                    @if (old('id_estoque') == $estoque->id) selected="selected" @endif>
                                    {{ $estoque->descricao }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_estoque')
                            <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group @if ($errors->has('consignado')) has-danger @endif">
                        <label class="form-control-label">Consignado <span class="text-danger">*</span></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="consignado" value="1" id="consignado1"
                                @if (old('consignado', 1) == 1) checked @endif>
                            <label class="form-check-label" for="consignado1">
                                Sim
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="consignado" value="0" id="consignado2"
                                @if (old('consignado', 1) == 0) checked @endif>
                            <label class="form-check-label" for="consignado2">
                                Não
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group num_parcelas">
                        <label class="form-control-label">Contabiliza</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="contabiliza" value="1"
                                id="contabiliza1" @if (old('contabiliza', 1) == 1) checked @endif>
                            <label class="form-check-label" for="contabiliza1">
                                Sim
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="contabiliza" value="0"
                                id="contabiliza2" @if (old('contabiliza', 1) == 0) checked @endif>
                            <label class="form-check-label" for="contabiliza2">
                                Não
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group @if ($errors->has('numero_documento')) has-danger @endif">
                        <label class="form-control-label">Numero Documento</label>
                        <input type="text" name="numero_documento" value="{{ old('numero_documento') }}"
                            class="form-control  @if ($errors->has('numero_documento')) form-control-danger @endif">
                        @if ($errors->has('numero_documento'))
                            <div class="form-control-feedback">{{ $errors->first('numero_documento') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group @if ($errors->has('serie')) has-danger @endif">
                        <label class="form-control-label">Série</label>
                        <input type="text" name="serie" value="{{ old('serie') }}"
                            class="form-control  @if ($errors->has('serie')) form-control-danger @endif">
                        @if ($errors->has('serie'))
                            <div class="form-control-feedback">{{ $errors->first('serie') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group @error('id_fornecedor') has-danger @enderror">
                        <label class="form-control-label">Fornecedor <span class="text-danger">*</span></label>
                        <select id="id_fornecedor" name="id_fornecedor"
                            class="form-control select2basic @error('id_fornecedor') form-control-danger @enderror">
                            <option value="">Selecione fornecedor</option>
                            @foreach ($fornecedores as $key => $fornecedor)
                                <option value="{{ $fornecedor->id }}" @if (old('id_fornecedor') == $fornecedor->id) selected @endif>
                                    {{ $fornecedor->id }} - {{ $fornecedor->nome_fantasia ?? $fornecedor->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_fornecedor')
                            <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group @if ($errors->has('data_emissao')) has-danger @endif">
                        <label class="form-control-label">Data Emissão <span class="text-danger">*</span></label>
                        <input type="date" name="data_emissao" value="{{ old('data_emissao') }}"
                            class="form-control  @if ($errors->has('data_emissao')) form-control-danger @endif">
                        @if ($errors->has('data_emissao'))
                            <div class="form-control-feedback">{{ $errors->first('data_emissao') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group @if ($errors->has('data_hora_entrada')) has-danger @endif">
                        <label class="form-control-label"> Hora Emissão <span class="text-danger">*</span></label>
                        <input type="time" name="data_hora_entrada" value="{{ old('data_hora_entrada') }}"
                            class="form-control  @if ($errors->has('data_hora_entrada')) form-control-danger @endif">
                        @if ($errors->has('data_hora_entrada'))
                            <div class="form-control-feedback">{{ $errors->first('data_hora_entrada') }}</div>
                        @endif
                    </div>
                </div>
                <div class='produtos col-12'>
                    <div class="card">
                        <div class="col-sm-12 border-bottom bg-light p-3">
                            <label class="form-control-label p-0 m-0">Produtos</label>
                        </div>
                        <br>

                        <div id="produtos-selecionados-container"></div>
                        @if ($errors->has('produtos'))
                            <div class="form-control-feedback">{{ $errors->first('produtos') }}</div>
                        @endif

                        <div class="form-group col-md-12 add-class-produto">
                            <span alt="default" class="add-produto fas fa-plus-circle">
                                <a class="mytooltip" href="javascript:void(0)">
                                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right"
                                        title="" data-original-title="Adicionar Produtos"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group text-right col-12">
                    <a href="{{ route('instituicao.estoque_entrada.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i
                                class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i
                            class="mdi mdi-check"></i> Salvar</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    {{-- Template do produto --}}
    <script type="text/template" id="template-produto">
        <div class="col-md-12 item-produto">
            <div class="row">
                <div class="col-12 p-0 divisor"><hr></div>
                <div class="col-md-12">
                    <a data-name="remover-action" href="javascrit:void(0)" class="small remove-produto">(remover)</a>
                </div>

                <div class="form-group col-md-6">
                    <label class="form-control-label">Produto <span class="text-danger">*</span></label>

                    <select data-name="produto_id" name="produtos[#][id]" class="form-control produto-select" style="width: 100%">
                        <option value="">Selecione um produto</option>
                    </select>
                    <div class="form-control-feedback text-danger" data-error="produto_id"></div>
                </div>

                <div class="form-group col-md-3 col-sm-6">
                    <label class="form-control-label">Valor de custo por unidade <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">R$</span>
                        </div>
                        <input data-name="valor_custo" type="text" class="form-control produtos-valor" name="produtos[#][valor_custo]" id="produtos[#][valor_custo]" value="0,00">
                    </div>
                    <div class="form-control-feedback text-danger" data-error="valor_custo"></div>
                </div>

                <div class="form-group col-md-3 col-sm-6">
                    <label class="form-control-label">Valor de venda</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">R$</span>
                        </div>
                        <input data-name="valor" type="text" class="form-control produtos-valor" name="produtos[#][valor]" id="produtos[#][valor]" value="0,00">
                    </div>
                    <div class="form-control-feedback text-danger" data-error="valor"></div>
                </div>

                <div class="form-group col-md-3 col-sm-6">
                    <label class="form-control-label">Quantidade <span class="text-danger">*</span></label>
                    <input data-name="quantidade" type="number" class="form-control quantidade" name="produtos[#][quantidade]" id="produtos[#][quantidade]" value="0">
                    <div class="form-control-feedback text-danger" data-error="quantidade"></div>
                </div>
                <div class="form-group col-md-4 col-sm-6">
                    <label class="form-control-label">Lote <span class="text-danger">*</span></label>
                    <input data-name="lote" type="text" class="form-control" name="produtos[#][lote]" id="produtos[#][lote]" value="">
                    <div class="form-control-feedback text-danger" data-error="lote"></div>
                </div>
                <div class="form-group col-md-3 col-sm-6">
                    <label class="form-control-label">Validade</label>
                    <input data-name="validade" type="date" class="form-control" name="produtos[#][validade]" id="produtos[#][validade]">
                    <div class="form-control-feedback text-danger" data-error="validade"></div>
                </div>
            </div>
        </div>
    </script>

    <script>
        const produto_container = $('#produtos-selecionados-container');
        const produto_template = new HtmlTemplate('#template-produto');
        let produtos_selecionados = new Array();
        let ultimo_id_produto = 0;
        let threads_search_lote = Array();

        function adicionarProduto(produto = {}) {
            // Salva e sobe o id local na lista de produtos adicionados
            produto.__id = ultimo_id_produto++;
            produto.__local_id = `produto-selecionado-${produto.__id}`;
            produtos_selecionados.push(produto);

            produto_container.append(produto_template.create({
                    'self': {
                        id: produto.__local_id
                    },
                    'produto_id': {
                        option: {
                            value: produto.id,
                            text: produto.id ? `#${produto.id} - ${produto.descricao} [un: ${produto.unidade}]` : ''
                        },
                        error: 'id'
                    },
                    'valor_custo': {
                        value: (Number.parseFloat(produto.valor_custo ?? 0).toFixed(2) + '').replace('.', ','),
                        min: 0
                    },
                    'valor': (Number.parseFloat(produto.valor ?? 0).toFixed(2) + '').replace('.', ','),
                    'quantidade': produto.quantidade ?? '0',
                    'validade': produto.validade ? produto.validade.split(' ')[0] : '',
                    'lote': {
                        value: produto.lote ?? '',
                        'data-id': produto.__id
                    },
                    'remover-action': {
                        'data-value': produto.__id,
                        'onclick': (e) => {
                            e.preventDefault();
                            const id = $(e.target).attr('data-value');
                            removerProduto(id);
                        }
                    }
                },
                produto.__id, {
                    prefix: 'produtos',
                    data: '{{ json_encode($errors->messages()) }}'
                }
            ));
            produto.__element = $(`#${produto.__local_id}`);

            produto.__element.find('[data-name="lote"]').on('keyup', (e) => {
                const lote = $(e.target).val();
                const id = $(e.target).attr('data-id');

                if (threads_search_lote[id] ?? null) {
                    clearTimeout(threads_search_lote[id]);
                }
                threads_search_lote[id] = setTimeout(() => {
                    $.ajax("{{ route('instituicao.ajax.getlote') }}", {
                        method: "POST",
                        data: {
                            "_token": '{{ csrf_token() }}',
                            search: lote,
                            strict: 1
                        },
                        success: (response) => {
                            if (response.ammount && response.ammount > 0) {
                                $.toast({
                                    heading: 'Alerta',
                                    text: 'O lote selecionado já está cadastrado no sistema, você tem certeza que inseriu o lote correto?',
                                    position: 'top-right',
                                    loaderBg: '#ff6849',
                                    icon: 'warning',
                                    hideAfter: false,
                                    stack: 10
                                });
                            }
                        }
                    });
                }, 1000);
            });

            produto.__element.find('.produtos-valor').setMask({
                mask: '99,999999999',
                type: 'reverse',
                defaultValue: '000'
            }).on('keyup', (e) => {
                const val = parseFloat(e.target.value.replace(',', ''));
                if (val <= 0) {
                    e.target.value = '0,00';
                } else if (val < 10) {
                    e.target.value = '0,0' + val;
                } else if (val < 100) {
                    e.target.value = '0,' + val;
                }
            });

            produto.__element.find(".selectfild2").select2();


            produto.__element.find('.produto-select').select2({
                placeholder: "Busque o produto",
                ajax: {
                    url: "{{route('instituicao.ajax.buscar-produtos')}}",
                    type: 'post',
                    dataType: 'json',
                    quietMillis: 20,
                    data: function(params) {
                        return {
                            search: params.term,
                            '_token': '{{csrf_token()}}',
                            paginate: true
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.results, function(obj) {
                                return {
                                    id: obj.id,
                                    text: `#${obj.id} - ${obj.descricao} [un: ${obj.unidade.descricao}]`
                                }
                            }),
                            pagination: {
                                more: data.pagination.more
                            }
                        }
                    }
                },
                language: {
                    searching: function () {
                        return 'Buscando ...';
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

            if (produto.__id == 0) {
                produto.__element.find('.remove-produto').hide();
                produto.__element.find('.divisor').hide();
            }
        }

        function removerProduto(id) {
            const index = produtos_selecionados.findIndex(el => el.__id == id);
            if (index != -1) {
                const produto = produtos_selecionados[index];
                produto.__element.remove();
                produtos_selecionados.splice(index, -1);
            }
        }

        $(document).ready(() => {
            $('.select2basic').select2();

            $('.add-produto').on('click', () => adicionarProduto());

            const produtos_old = Array.from({!! json_encode($produtos) !!});
            if (produtos_old.length > 0) {
                produtos_old.forEach(item => adicionarProduto(item));
            } else {
                adicionarProduto();
            }
        })
    </script>
@endpush
