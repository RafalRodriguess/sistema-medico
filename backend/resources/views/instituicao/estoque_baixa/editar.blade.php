@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Atualizar baixa de estoque #{$estoqueBaixa->id}",
        'breadcrumb' => [
            'Baixas de estoque' => route('instituicao.estoque_baixa_produtos.index'),
            "Atualizar baixa de estoque #{$estoqueBaixa->id}",
        ],
    ])
    @endcomponent


    <div class="card" style="padding: 20px;">
        <form action="{{ route('instituicao.estoque_baixa_produtos.update', $estoqueBaixa) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group @error('estoque_id') has-danger @enderror">
                        <label class="form-control-label">Estoque <span class="text-danger">*</span></label>
                        <select name="estoque_id"
                            class="form-control select2basic @error('estoque_id') form-control-danger @enderror">
                            <option value="">Selecione um estoque</option>
                            @foreach ($estoques as $estoque)
                                <option value="{{ $estoque->id }}"
                                    @if (old('estoque_id', $estoqueBaixa->estoque_id) == $estoque->id) selected="selected" @endif>
                                    {{ $estoque->descricao }}
                                </option>
                            @endforeach
                        </select>
                        @error('estoque_id')
                            <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group @error('motivo_baixa_id') has-danger @enderror">
                        <label class="form-control-label">Motivo <span class="text-danger">*</span></label>
                        <select name="motivo_baixa_id"
                            class="form-control select2basic @error('motivo_baixa_id') form-control-danger @enderror">
                            <option value="">Selecione um motivo</option>
                            @foreach ($motivos as $motivo)
                                <option value="{{ $motivo->id }}"
                                    @if (old('motivo_baixa_id', $estoqueBaixa->motivo_baixa_id) == $motivo->id) selected="selected" @endif>
                                    {{ $motivo->descricao }}
                                </option>
                            @endforeach
                        </select>
                        @error('motivo_baixa_id')
                            <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group @error('setor_id') has-danger @enderror">
                        <label class="form-control-label">Setor <span class="text-danger">*</span></label>
                        <select name="setor_id"
                            class="form-control select2basic @error('setor_id') form-control-danger @enderror">
                            <option value="">Selecione um setor</option>
                            @foreach ($setores_exame as $setor)
                                <option value="{{ $setor->id }}"
                                    @if (old('setor_id', $estoqueBaixa->setor_id) == $setor->id) selected="selected" @endif>
                                    {{ $setor->descricao }}
                                </option>
                            @endforeach
                        </select>
                        @error('setor_id')
                            <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="px-3">
                    <div class="form-group @if ($errors->has('data_emissao')) has-danger @endif">
                        <label class="form-control-label">Data Baixa <span class="text-danger">*</span></label>
                        <input type="date" name="data_emissao" value="{{ old('data_emissao',  $estoqueBaixa->data_emissao) }}"
                            class="form-control  @if ($errors->has('data_emissao')) form-control-danger @endif">
                        @if ($errors->has('data_emissao'))
                            <div class="form-control-feedback">{{ $errors->first('data_emissao') }}</div>
                        @endif
                    </div>
                </div>

                <div class="px-3">
                    <div class="form-group @if ($errors->has('data_hora_baixa')) has-danger @endif">
                        <label class="form-control-label"> Hora Baixa <span class="text-danger">*</span></label>
                        <input type="time" name="data_hora_baixa" value="{{ old('data_hora_baixa',  $estoqueBaixa->data_hora_baixa) }}"
                            class="form-control  @if ($errors->has('data_hora_baixa')) form-control-danger @endif">
                        @if ($errors->has('data_hora_baixa'))
                            <div class="form-control-feedback">{{ $errors->first('data_hora_baixa') }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
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
            </div>

            <div class="form-group text-right">
                <a href="{{ route('instituicao.estoque_baixa_produtos.index') }}">
                    <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i
                            class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                </a>
                <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i>
                    Salvar</button>
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
                    <input type="hidden" name="produtos[#][id_entrada_produto]" data-name="id_entrada_produto">

                    <select data-name="produto" class="form-control produto-select" style="width: 100%">
                        <option value="">Selecione um produto</option>
                    </select>
                    <div class="form-control-feedback text-danger" data-error="id_entrada_produto"></div>
                </div>
                
                <div class="form-group col-md-4 col-sm-6">
                    <label class="form-control-label">Lote</label>
                    <span data-name="lote" class="form-control d-block"></span>
                </div>

                <div class="form-group col-md-3 col-sm-6">
                    <label class="form-control-label">Validade</label>
                    <input data-name="validade" type="date" class="form-control" readonly>
                </div>

                <div class="form-group col-md-3 col-sm-6">
                    <label class="form-control-label">Quantidade máxima</label>
                    <span data-name="quantidade-maxima" class="form-control d-block"></span>
                </div>

                <div class="form-group col-md-3 col-sm-6">
                    <label class="form-control-label">Quantidade <span class="text-danger">*</span></label>
                    <input data-name="quantidade" type="number" class="form-control quantidade" name="produtos[#][quantidade]" id="produtos[#][quantidade]" value="0">
                    <div class="form-control-feedback text-danger" data-error="quantidade"></div>
                </div>
            </div>
        </div>
    </script>

    <script>
        // Definindo o container
        const produto_container = $('#produtos-selecionados-container');
        // Definindo o template e o manipulador
        const produto_template = new HtmlTemplate('#template-produto', '#', (produto) => {
            return {
                'self': {
                    id: produto.__local_id,
                    'data-id': produto.__id
                },
                'id_entrada_produto': produto.id ?? null,
                'produto': {
                    option: {
                        value: produto ? JSON.stringify(produto) : null,
                        text: produto.id ?
                            `#${produto.lote} - ${produto.produto.descricao} [un: ${produto.produto.unidade ? produto.produto.unidade.descricao : 'unidade'}]` :
                            null
                    }
                },
                'quantidade': produto.quantidade_selecionada ?? 0,
                'quantidade-maxima': produto.saldo_total ?? 0,
                'lote': produto.lote ?? '',
                'validade': produto.validade ? produto.validade.split(' ')[0] : '',
                'remover-action': {
                    'data-value': produto.__id,
                    'onclick': (e) => {
                        e.preventDefault();
                        const id = $(e.target).attr('data-value');
                        removerProduto(id);
                    }
                }
            };
        });

        // Variáveis
        let produtos_selecionados = new Array();
        let ultimo_id_produto = 0;

        function adicionarProduto(produto = {}) {
            // Salva e sobe o id local na lista de produtos adicionados
            produto.__id = ultimo_id_produto++;
            produto.__local_id = `produto-selecionado-${produto.__id}`;
            produtos_selecionados.push(produto);
            produto_container.append(produto_template.create(produto,
                produto.__id, {
                    prefix: 'produtos',
                    data: '{{ json_encode($errors->messages()) }}'
                }
            ));
            produto.__element = $(`#${produto.__local_id}`);

            produto.__element.find('.produto-select').select2({
                placeholder: 'Busque por nome do produto ou lote',
                ajax: {
                    url: "{{ route('instituicao.ajax.getentradaprodutos') }}",
                    type: 'post',
                    dataType: 'json',
                    quietMillis: 20,
                    data: function(params) {
                        return {
                            search: params.term,
                            paginate: true,
                            '_token': '{{ csrf_token() }}',
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.items, function(obj) {
                                return {
                                    id: JSON.stringify(obj),
                                    text: `#${obj.lote} - ${obj.produto.descricao} [un: ${obj.produto.unidade ? obj.produto.unidade.descricao : 'unidade'}]`
                                };
                            }),
                            pagination: {
                                more: data.next ? true : false
                            }

                        }
                    },
                },
                minimumInputLength: 1,
                language: {
                    searching: function() {
                        return 'Buscando produtos no estoque';
                    },

                    noResults: function() {
                        return 'Nenhum resultado encontrado';
                    },

                    inputTooShort: function(input) {
                        return "Digite " + (input.minimum - input.input.length) + " caracteres para pesquisar";
                    },
                },
                escapeMarkup: function(m) {
                    return m;
                },
            }).on('select2:select', (e) => {
                const element = $($(e.target).parents('.item-produto')[0]);
                const data = JSON.parse($(e.target).val());
                data.__id = element.attr('data-id');
                data.__local_id = `produto-selecionado-${data.__id}`;
                produto_template.update(data, element);
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

            const produtos_old = Array.from({!! json_encode($produtos_selecionados) !!});
            if (produtos_old.length > 0) {
                produtos_old.forEach(item => adicionarProduto(item));
            } else {
                adicionarProduto();
            }
        });
    </script>
@endpush
