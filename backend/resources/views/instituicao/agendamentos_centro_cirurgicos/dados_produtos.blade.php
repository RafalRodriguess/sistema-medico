<div class="p-10">
    <input type="hidden" name="in_page_produtos" value="1">
    <div class="row">
        <div class="col-md-12 produtos">
            <div class="row">
                <div class="col-md-8">
                    <h4>Produtos</h4>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-success waves-effect gerarEstoque">Gerar saida do
                        estoque</button>
                </div>
            </div>
            <div id="produtos-selecionados-container"></div>
            {{-- @if (count($agendamento->produtos) > 0)
                @foreach ($agendamento->produtos as $key => $item)
                    <div class="row item" style="border-bottom: dashed 1px #00000061; margin-top: 10px">
                        <div class="col-md-12">
                            <a href="javascrit:void(0)" class="small remove_produtos">(remover)</a>
                        </div>
                        <div class="col-md-8">
                            <div class="row produtos-row">
                                <div class="form-group col-md-12">
                                    <label for="centro_cirurgico_editar" class="control-label">Produto *:</label>
                                    <select class="form-control select2prodDados" name="produtos[{{$key}}][produto]" id="produtos_id_{{$key}}" style="width: 100%"> 
                                        <option value="{{$item->id}}" data-opme="{{$item->opme}}">{{$item->descricao}} - {{$item->especie->descricao}}</option>
                                    </select>
                                </div>
                                <input type="hidden" name="produtos[{{$key}}][opme]" class="opme" id="produtos_opme_{{$key}}" value="{{($item->opme) ? 'true' : 'false'}}">
                                <div class="form-group col-md-3">
                                    <label for="centro_cirurgico_editar" class="control-label">Quantidade *:</label>
                                    <input type="integer" class="form-control item_prod" id="produtos_quantidade_{{$key}}" name="produtos[{{$key}}][quantidade]" alt="numeric" value="{{$item->pivot->quantidade}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="centro_cirurgico_editar" class="control-label">Fornecedor:</label>
                                    <select class="form-control select2prod selectfornecedor" name="produtos[{{$key}}][fornecedor]" data-id="{{$item->pivot->fornecedor_id}}" id="produtos_fornecedor_{{$key}}" style="width: 100%" onchange="getLotesProdutoFornecedor(this)">
                                        <option value="">Selecione um fornecedor</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="centro_cirurgico_editar" class="control-label">Lote:</label>
                                    <select class="form-control select2prod selectlote" name="produtos[{{$key}}][lote]" data-id="{{$item->pivot->lote_id}}" id="produtos_lote_{{$key}}" style="width: 100%">
                                        <option value="">Selecione um lote</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="centro_cirurgico_editar" class="control-label">Observação:</label>
                            <textarea class="form-control" id="produtos_obs_{{$key}}" name="produtos[{{$key}}][obs]" id="" cols="5" rows="5">{{$item->pivot->obs}}</textarea>
                        </div>
                    </div>
                @endforeach
            @endif --}}
            <div class="form-group col-md-12 add-class-produtos" style="margin-top: 10px">
                <span alt="default" class="add_produtos fas fa-plus-circle" style="cursor: pointer;">
                    <a class="mytooltip" href="javascript:void(0)">
                        <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right"
                            title="" data-original-title="Adicionar Produto"></i>
                    </a>
                </span>
            </div>
        </div>
    </div>
</div>
<script type="text/template" id="produto-template">
    <div class="row item" style="border-bottom: dashed 1px #00000061; margin-top: 10px">
        <div class="col-md-12">
            <a data-name="button-remover" class="small remove_produtos">(remover)</a>
        </div>
        <div class="col-md-8">
            <div class="row produtos-row">
                <div class="form-group col-md-12">
                    <label for="id_produto_#" class="control-label">Produto *</label>
                    <select id="id_produto_#" data-name="in-produto" class="form-control" name="produtos[#][id_entrada_produto]" style="width: 100%"></select>
                </div>
                <input data-name="in-opme" type="hidden" name="produtos[#][opme]" class="opme" id="produtos_opme_#">
                <div class="form-group col-md-3">
                    <label for="quantidade_max_text_#" class="control-label">Máximo</label>
                    <span id="quantidade_max_text_#" data-name="in-quantidade-maxima" class="form-control d-block"></span>
                </div>
                <div class="form-group col-md-3">
                    <label for="quantidade_input_#" class="control-label">Quantidade *</label>
                    <input id="quantidade_input_#" data-name="in-quantidade" class="form-control item_prod" name="produtos[#][quantidade]" type="numeric">
                </div>
                <div class="form-group col-md-6">
                    <label for="fornecedor_text_#" class="control-label">Fornecedor</label>
                    <span id="fornecedor_text_#" data-name="text-fornecedor" class="d-block form-control"></span>
                </div>
                <div class="form-group col-md-3">
                    <label for="lote_text_#" class="control-label">Lote</label>
                    <span id="lote_text_#" data-name="text-lote" class="d-block form-control"></span>
                </div>
            </div>
        </div>
        <div class="form-group col-md-4">
            <label for="observacao_input_#" class="control-label">Observação:</label>
            <textarea id="observacao_input_#" class="form-control" name="produtos[#][obs]" cols="5" rows="5"></textarea>
        </div>
    </div>
</script>


<script>
    let produtos_container = $('#produtos-selecionados-container');
    let produtos = {
        id: 0,
        itens: Array(),
        index: {}
    };
    let produto_template = new HtmlTemplate('#produto-template', '#', (entrada) => {
        let result = [{
            'self': {
                id: entrada.__element_id
            },
            'button-remover': {
                onclick: (e) => {
                    e.preventDefault();
                    removerProduto(entrada.__local_id);
                }
            },
            'in-quantidade-maxima': entrada.saldo_total ?? 0,
            'in-quantidade': {
                value: entrada.quantidade_selecionada ?? 0,
                min: 0,
                max: entrada.saldo_total ?? 0
            },
            'text-lote': entrada.lote ?? ''
        }];
        if (entrada.produto) {
            console.log(entrada.produto);
            result.push({
                'in-produto': {
                    option: {
                        id: entrada.produto ? entrada.produto.id : '',
                        text: entrada.produto ?
                            `${entrada.produto.descricao} - #${entrada.lote} ${entrada.especie ? '- ' + entrada.especie.descricao : ''}` :
                            ''
                    }
                },
                'in-opme': entrada.produto ? entrada.produto.opme : '',
            })
        }
        if (entrada.fornecedor) {
            result.push({
                'text-fornecedor': entrada.fornecedor ? entrada.fornecedor.nome_fantasia : '',
            });
        }
        return result;
    });

    function adicionarProduto(entrada = {}) {
        // evitando duplicatas
        if (produtos.itens.find(el => (el.id ?? -1) == (entrada.id ?? -2)) !== undefined) {
            return;
        }
        // Preparando entrada e inserindo no container
        entrada.__local_id = produtos.id++;
        entrada.__element_id = `produto-item-${entrada.__local_id}`;
        produtos_container.append(produto_template.create(
            entrada,
            entrada.__local_id, {
                prefix: 'produtos',
                data: '{{ json_encode($errors->messages()) }}'
            }
        ));
        entrada.__element = $('#' + entrada.__element_id);
        // Inserindo id e índice no dicionário
        produtos.index[entrada.__local_id] = produtos.itens.length;
        produtos.itens.push(entrada);
        // Preparando detalhes de cada item
        entrada.__element.find('[data-name="in-produto"]').select2({
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
                    return "Digite " + (input.minimum - input.input.length) +
                        " caracteres para pesquisar";
                },
            },
            escapeMarkup: function(m) {
                return m;
            },
        }).on('select2:select', e => {
            const val = JSON.parse($(e.target).val());
            atualizarProduto(entrada.__local_id, val);
        });
    }

    function removerProduto(id_local) {
        if ((produtos.index[id_local] ?? undefined) === undefined) {
            return;
        }
        const produto = produtos.itens.splice(produtos.index[id_local], 1)[0];
        produto.__element.remove();
        produtos.index[id_local] = undefined;
    }

    function atualizarProduto(id_local, dados) {
        if ((produtos.index[id_local] ?? undefined) === undefined) {
            return;
        }
        produtos.itens[produtos.index[id_local]] = produto_template.mergeObjects([produtos.itens[produtos.index[
            id_local]], dados]);
        produto_template.update(produtos.itens[produtos.index[id_local]], produtos.itens[produtos.index[id_local]]
            .__element);
    }

    function preencher(entradas) {
        entradas.forEach(entrada => {
            // evitando duplicatas
            if (produtos.itens.find(el => (el.id ?? -1) == (entrada.entrada.id ?? -2)) !== undefined) {
                return;
            }
        });
    }

    $(document).ready(() => {
        $("[data-toggle='tooltip']").tooltip()
        $('.add_produtos').on('click', () => {
            adicionarProduto();
        });

        $(".gerarEstoque").on('click', function(e) {
            var agendamentoId = $("#agendamento_editar_id").val()
            $("#modalEscolhaEstoque").find('.agendamentoIdEstoque').val(agendamentoId);
            if ($("#saida_estoque_id").val() != "") {
                gerarEstoqueSalvar();
            } else {
                $("#modalEscolhaEstoque").modal('show')
            }
            // $("#modal_visualizar").modal('hide')
        });
    });
</script>
