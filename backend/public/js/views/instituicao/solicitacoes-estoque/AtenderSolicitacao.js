class AtenderSolicitacao {
    /**
     * @param {array} produtos Produtos solicitados que devem ser atendidos
     * @param {HtmlTemplate} template Template html do elemento que representa um atendimento 
     * @param {object} container Um elemento jquery onde os elementos de produtos atendidos
     * serão inseridos 
     */
    constructor(produtos_solicitados, template_produtos_solicitados, template_produtos_atendidos, container_solicitados, container_atendidos)
    {
        this.produtos_solicitados = {
            itens: Array(),
            id: 0,
            index: {},
            quantidades: {}
        };
        this.produtos_atendidos = {
            id: 0,
            index: {},
            itens: Array()
        }
        this.elements = {
            container_solicitados: container_solicitados,
            container_atendidos: container_atendidos
        }
        this.templates = {
            solicitados: template_produtos_solicitados,
            atendidos: template_produtos_atendidos,
        }
        const ref = this;
        // Processamento dos dados do backend de produtos solicitados
        this.templates.solicitados.processData = (solicitado) => {
            return {
                'self': {
                    'id': solicitado.__element_id
                },
                'produto-solicitado-id': solicitado.id,
                'produto-id': solicitado.produto.id,
                'produto-id-texto': solicitado.produto.id,
                'produto-descricao': solicitado.produto.descricao,
                'produto-unidade': solicitado.produto.unidade.descricao,
                'quantidade': solicitado.quantidade,
                'motivo-divergencia': solicitado.motivos_divergencia_id,
                'motivo-divergencia-texto': solicitado.motivo_divergencia ? solicitado.motivo_divergencia.descricao : '',
                'confirma': solicitado.confirma_item ? true : null,
                'confirma-icone': {
                    html: (solicitado.confirma_item ? true : null) ? "<i class=\"fas fa-check text-success\"></i>" : "<i class=\"fas fa-times text-danger\"></i>"
                }
            };
        };
        // Processamento dos dados do backend de produtos atendidos
        this.templates.atendidos.processData = (atendido) => {
            return {
                'self': {
                    'id': atendido.__element_id
                },
                'id_entrada_produto': atendido.id,
                'codigo_de_barras': atendido.codigo_de_barras ?? '',
                'produto-descricao': atendido.produto.descricao,
                'produto-lote': atendido.lote,
                'produto-unidade': atendido.produto.unidade.descricao,
                'produto-maximo': atendido.saldo_total,
                'quantidade': {
                    value:  atendido.quantidade_selecionada ?? 0,
                    min: 0,
                    max: atendido.saldo_total,
                    onchange: (e) => ref.alterarQuantidade(atendido.__local_id, $(e.target).val()),
                    onkeyup: (e) => ref.alterarQuantidade(atendido.__local_id, $(e.target).val())
                },
                'quantidade-texto': atendido.quantidade_selecionada ?? 0,
                'remover-produto-button': {
                    onclick: (e) => ref.remover(atendido.__local_id)
                }
            };
        };

        if(produtos_solicitados.length > 0) {
            this.inicializar(produtos_solicitados);
        }
    }

    /**
     * Método que inicializa a lista de produtos solicitados
     */
    inicializar(produtos_solicitados) {
        produtos_solicitados.forEach((solicitado) => {
            solicitado.__local_id = this.produtos_solicitados.id++;
            solicitado.__element_id = `produto-requisitado-${solicitado.__local_id}`;
            // Insere o html a partir do template
            this.elements.container_solicitados.append(this.templates.solicitados.create(solicitado,
                solicitado.__local_id,
                {
                    prefix: 'produtos',
                    data: this.errors
                }
            ));
            // Pegando a referência do elemento
            solicitado.__element = $('#' + solicitado.__element_id);
            // Inserindo na lista e índice
            this.produtos_solicitados.index[solicitado.__local_id] = this.produtos_solicitados.itens.length;
            this.produtos_solicitados.itens.push(solicitado);
            // Adicionando no cálculo de quantidades
            this.produtos_solicitados.quantidades[solicitado.produto.id] = null;
        });
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
            try {
                entrada = JSON.parse(entrada);
            } catch {
            }
        }
        // Verifica se já existe um inserido e se foi requisitado
        if (
            !entrada || 
            this.produtos_atendidos.itens.find(el => (el.id ?? -1) == (entrada.id ?? -2)) !== undefined ||
            Object.keys(this.produtos_solicitados.quantidades).indexOf((entrada.produto ? entrada.produto.id : -1).toString()) === -1
        ) {
            return;
        }
        // Salva e sobe o id local na lista de produtos adicionados
        entrada.__local_id = this.produtos_atendidos.id++;
        entrada.__element_id = `produto-atendido-${entrada.__local_id}`;
        entrada.quantidade_selecionada = entrada.quantidade_selecionada ?? 1;
        // Insere o html a partir do template
        this.elements.container_atendidos.append(this.templates.atendidos.create(entrada,
            entrada.__local_id,
            {
                prefix: 'produtos_recebidos',
                data: this.errors
            }
        ));
        // Pegando a referência do elemento
        entrada.__element = $('#' + entrada.__element_id);
        // Inserindo na lista e índice
        this.produtos_atendidos.index[entrada.__local_id] = this.produtos_atendidos.itens.length;
        this.produtos_atendidos.itens.push(entrada);
        this.calcularQuantidades();
        // Retornando o elemento
        return entrada.__element;
    }

    /**
     * Remove um produto da lista pelo seu id local
     * @param {int} id_local O id local gerado 
     * na função adicionar desta classe
     */
    remover(id_local) {
        if ((this.produtos_atendidos.index[id_local] ?? undefined) === undefined) {
            return;
        }
        const entrada = this.produtos_atendidos.itens.splice(this.produtos_atendidos.index[id_local], 1)[0];
        this.produtos_atendidos.index[id_local] = undefined;
        entrada.__element.remove();
        this.calcularQuantidades();
    }

    alterarQuantidade(id_local, quantidade) {
        const produto = this.produtos_atendidos.itens[this.produtos_atendidos.index[id_local]];
        if(this.produtos_solicitados.quantidades[produto.produto.id] !== null) {
            this.produtos_solicitados.quantidades[produto.produto.id] += parseFloat(quantidade) - produto.quantidade_selecionada;
        } else {
            this.produtos_solicitados.quantidades[produto.produto.id] = parseFloat(quantidade);
        }
        this.produtos_atendidos.itens[this.produtos_atendidos.index[id_local]].quantidade_selecionada = quantidade;
        this.calcularQuantidades(true);
    }

    calcularQuantidades(fast = false) {
        if(!fast) {
            // Recalcular quantidades selecionadas
            this.produtos_solicitados.quantidades = {};
            this.produtos_atendidos.itens.forEach(atendido => {
                this.produtos_solicitados.quantidades[atendido.produto.id] = parseFloat((this.produtos_solicitados.quantidades[atendido.produto.id] ?? 0) + atendido.quantidade_selecionada);
            });
        }
        this.produtos_solicitados.itens.forEach((solicitado, key) => {
            const quantidade = this.produtos_solicitados.quantidades[solicitado.produto.id] ?? 0;
            this.produtos_solicitados.itens[key].quantidade_selecionada = parseFloat(quantidade);
            solicitado.__element.find('[data-name="quantidade-atendida"]').text(quantidade);
        });
    }

    preencher(produtos_selecionados) {
        produtos_selecionados.map(produto_atendido => {
            let entrada = produto_atendido.entradaProduto;
            entrada.codigo_de_barras = produto_atendido.codigo_de_barras;
            entrada.quantidade_selecionada = produto_atendido.quantidade;
            entrada.motivoDivergencia = produto_atendido.motivoDivergencia;
            this.adicionar(entrada);
        });
    }
}