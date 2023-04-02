class SaidaEstoque {
    /**
     * @param {Array|null} produtos Um array dos produtos que já devem ser 
     * adicionados a lista
     * @param {object} container Elemento jquery com o container de produtos
     * (container onde os elementos serão inseridos)
     * @param {HtmlTemplate} template Template do produto a ser inserido
     */
    constructor(produtos = null, container, template, erros) {
        this.produtos = {
            id: 0,
            itens: Array(),
            index: {}
        };
        this.elemento_container = $(container);
        this.elemento_template = template;
        this.errors = erros;
        // Configurando o process data do template
        const saida = this;
        this.elemento_template.processData = (entrada) => {
            return {
                'self': {
                    id: entrada.__element_id
                },
                'codigo-de-barras-input': {
                    value: entrada.codigo_de_barras,
                    error: 'codigo_de_barras'
                },
                'id-entrada-produto-input': entrada.id,
                'produto-nome': entrada.produto.descricao ?? '',
                'lote-input': entrada.lote,
                'unidade': entrada.produto.unidade ? entrada.produto.unidade.descricao : '',
                'lote-text': entrada.lote,
                'valor-produto': {
                    text: 'R$ ' + Number.parseFloat(entrada.valor ?? 0).toFixed(2).toString().replace('.', ','),
                    'data-value': entrada.valor
                },
                'produto-element': {
                    text: entrada.produto.id ?? '',
                    title: `Lote: "${entrada.lote}" Id. Produto: "${entrada.produto.id}" Desc. Produto: "${entrada.produto.descricao}" Quantidade: "${entrada.quantidade_estoque}"`
                },
                'quantidade-maxima-text': entrada.quantidade_estoque,
                'quantidade-input': {
                    value: entrada.quantidade_selecionada,
                    max: entrada.quantidade_estoque,
                    min: 0,
                    error: 'quantidade',
                    onchange: (e) => saida.atualizarQuantidade(entrada.__local_id, $(e.target).val())
                },
                'remover-produto-button': {
                    onclick: (e) => saida.remover(entrada.__local_id)
                }
            }
        };

        if (produtos && (produtos.length ?? 0) > 0) {
            this.preencher(produtos);
        }
    }

    /**
     * Adiciona um produto a lista
     * @param {object|string|null} entrada Objeto que representa um produto de entrada de estoque
     * @param {boolean} isJson Define se o parametro "entrada" é json ou objeto
     * @return {object} o elemento criado
     */
    adicionar(entrada = null, isJson = false) {
        // Converte json caso necessário
        if (isJson) {
            entrada = JSON.parse(entrada);
        }
        // Verifica se já existe um inserido
        if (this.produtos.itens.find(el => (el.id ?? -1) == (entrada.id ?? -2)) !== undefined) {
            return;
        }
        // Salva e sobe o id local na lista de produtos adicionados
        entrada.__local_id = this.produtos.id++;
        entrada.__element_id = `produto-selecionado-${entrada.__local_id}`;
        entrada.quantidade_selecionada = entrada.quantidade_selecionada ?? 1;
        // Insere o html a partir do template
        this.elemento_container.append(produtoTemplate.create(entrada,
            entrada.__local_id,
            {
                prefix: 'produtos',
                data: this.errors
            }
        ));
        // Pegando a referência do elemento
        entrada.__element = $('#' + entrada.__element_id);
        // Inserindo na lista e índice
        this.produtos.index[entrada.__local_id] = this.produtos.itens.length;
        this.produtos.itens.push(entrada);
        // Disparando evento de adição
        const event = new CustomEvent('OnSaidaProdutoAdded', {
            detail: {
                id_local: entrada.__local_id,
                quantidade: entrada.quantidade_selecionada,
                valor: entrada.valor ?? 0
            }
        });
        window.dispatchEvent(event);
        // Retornando o elemento
        return entrada.__element;
    }

    /**
     * Remove um produto da lista pelo seu id local
     * @param {int} id_local O id local gerado 
     * na função adicionar desta classe
     */
    remover(id_local) {
        if ((this.produtos.index[id_local] ?? undefined) === undefined) {
            return;
        }
        const entrada = this.produtos.itens.splice(this.produtos.index[id_local], 1)[0];
        this.produtos.index[id_local] = undefined;
        entrada.__element.remove();
        // Disparando evento de remoção
        const event = new CustomEvent('OnSaidaProdutoRemoved', {
            detail: {
                id_local: id_local,
                quantidade: entrada.quantidade_selecionada,
                valor: entrada.valor ?? 0
            }
        });
        window.dispatchEvent(event);
    }

    /**
     * Insere um array de produtos na lista
     * @param {Array} produtos Um array de produtos para ser pré inserido
     * na lista
     */
    preencher(produtos) {
        const saida = this;
        produtos.forEach((produto) => saida.adicionar(produto, false));
    }

    /**
     * Método a ser chamado quando a quantidade é alterada
     * @param {int} id_local O id local gerado 
     * na função adicionar desta classe
     * @param {int} quantidade Quantidade do produto atualizada
     */
    atualizarQuantidade(id_local, quantidade) {
        if ((this.produtos.index[id_local] ?? undefined) === undefined) {
            return;
        }
        const old_value = Number.parseFloat((this.produtos.itens[this.produtos.index[id_local]].quantidade_selecionada ?? 0));
        this.produtos.itens[this.produtos.index[id_local]].quantidade_selecionada = Math.max(Number.parseFloat(quantidade), 0);
        // Disparando evento de mudança de valor
        const event = new CustomEvent('OnSaidaQuantidadeChanged', {
            detail: {
                id_local: id_local,
                quantidade: this.produtos.itens[this.produtos.index[id_local]].quantidade_selecionada,
                old_quantidade: old_value,
                valor: this.produtos.itens[this.produtos.index[id_local]].valor ?? 0
            }
        });
        window.dispatchEvent(event);
    }
}

class PagamentosSaida {
    constructor(pagamentos = null, container, template, valor_total, valor_restante, erros) {
        this.template = template;
        this.produtos = {};
        this.pagamentos = {
            id: 0,
            itens: Array(),
            index: {}
        };
        this.valores = {
            total: 0, // Valor dos produtos
            pago: 0 // Valor dos pagamentos
        };
        this.elementos = {
            container: $(container),
            valor_total: $(valor_total),
            valor_restante: $(valor_restante)
        };
        this.errors = erros;

        const ref = this;
        this.template.processData = (pagamento) => {
            return {
                'self': {
                    id: pagamento.__element_id
                },
                'remover-button': {
                    'style': (pagamento.__local_id == 0) ? 'display: none' : '',
                    'onclick': () => ref.remover(pagamento.__local_id)
                },
                'adicionar-button': {
                    'onclick': () => ref.adicionar()
                },
                'titulo': `Pagamento #${pagamento.__local_id + 1}`,
                'forma-pagamento': {
                    value: pagamento.forma_pagamento ?? null,
                    error: 'forma_pagamento'
                },
                'valor': {
                    value: this.toBrl(pagamento.valor_parcela ?? 0),
                    error: true,
                    onkeyup: (e) => ref.atualizarParcela(pagamento.__local_id, $(e.target).val()),
                    onclick: (e) => $(e.target).select()
                },
                'data': {
                    value: pagamento.data_vencimento ?? '',
                    error: true
                },
                'recebido': pagamento.data_pago ? true : null,
            };
        };

        if (pagamentos && (pagamentos.length ?? 0) > 0) {
            this.preencher(pagamentos);
        }

        // Registrando listeners
        window.addEventListener('OnSaidaProdutoAdded', e => ref.valorAlterado(e.detail.id_local, (e.detail.quantidade ?? 0) * e.detail.valor));
        window.addEventListener('OnSaidaProdutoRemoved', e => ref.valorAlterado(e.detail.id_local, 0));
        window.addEventListener('OnSaidaQuantidadeChanged', e => ref.valorAlterado(e.detail.id_local, (e.detail.quantidade ?? 0) * e.detail.valor));
    }

    valorAlterado(produto, valor) {
        this.produtos[produto] = Number.parseFloat(valor);
        this.calcularValores();
    }

    calcularValores() {
        this.valores.total = 0;
        for (const i in this.produtos) {
            this.valores.total += this.produtos[i];
        }

        // Caso só haja um pagamento altere o valor dele
        if (this.pagamentos.itens.length == 1) {
            for (const i in this.pagamentos.itens) {
                this.pagamentos.itens[i].valor_parcela = this.valores.total;
                this.pagamentos.itens[i].__element.find('[data-name="valor"]').val(this.toBrl(this.valores.total));
            }
        }

        this.valores.pago = 0;
        for (const i in this.pagamentos.itens) {
            this.valores.pago += parseFloat(this.pagamentos.itens[i].valor_parcela ?? 0);
        }

        this.elementos.valor_total.text('R$ ' + this.toBrl(this.valores.total));
        this.elementos.valor_restante.text('R$ ' + this.toBrl((this.valores.total - this.valores.pago)));
        if (this.valores.total - this.valores.pago != 0) {
            this.elementos.valor_restante.removeClass('text-primary');
            this.elementos.valor_restante.addClass('text-danger');
        } else {
            this.elementos.valor_restante.removeClass('text-danger');
            this.elementos.valor_restante.addClass('text-primary');
        }
    }

    adicionar(pagamento = null) {
        if (!pagamento) {
            pagamento = {};
        }
        pagamento.__local_id = this.pagamentos.id++;
        pagamento.__element_id = `pagamento-${pagamento.__local_id}`;
        this.pagamentos.index[pagamento.__local_id] = this.pagamentos.itens.length;

        this.elementos.container.append(pagamentoTemplate.create(pagamento,
            pagamento.__local_id,
            {
                prefix: 'pagamentos',
                data: this.errors
            }));

        pagamento.__element = $('#' + pagamento.__element_id);
        this.pagamentos.itens.push(pagamento);

        this.calcularValores();
    }

    remover(id_local) {
        if ((this.pagamentos.index[id_local] ?? undefined) === undefined) {
            return;
        }
        const pagamento = this.pagamentos.itens.splice(this.pagamentos.index[id_local], 1)[0];
        this.pagamentos.index[id_local] = undefined;
        pagamento.__element.remove();
        this.calcularValores();
    }

    preencher(pagamentos) {
        const ref = this;
        pagamentos.forEach(pagamento => ref.adicionar(pagamento));
    }

    atualizarParcela(id_local, valor) {
        const index = this.pagamentos.index[id_local] ?? undefined;
        if (index === undefined) {
            return;
        }
        this.pagamentos.itens[index].valor_parcela = this.toFloat(valor);
        this.calcularValores();
    }

    limpar() {
        this.pagamentos = {
            id: 0,
            itens: [],
            index: { }
        };
        this.elementos.container.empty();
    }

    toBrl(float, fixed = 2)
    {
        return parseFloat(float).toFixed(fixed).toString().replace('.', ',');
    }

    toFloat(string, fixed = 2)
    {
        return parseFloat(string.replace(',', '.')).toFixed(fixed);
    }
}
