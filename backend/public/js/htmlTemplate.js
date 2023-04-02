/**
 * Classe que permite a hidratação de elementos HTML a partir de um template
 * e um objeto com os atributos desejados
 */
class HtmlTemplate {
    /**
     * @param {string} selector Seletor do elemento html template que será utilizado para
     * esse objeto
     * @param {string} input_offset_placeholder A string que será substituida por um offset
     * utilizado quando há arrays ou ids que variam de acordo com um valor (offset), como por
     * exemplo ao iteragir por um array de resultados o nome ou id de alguns campos varia
     * (e.g: produto[1][nome] o número 1 é o offset e no template no lugar do 1 deve ter a string
     * especificada neste parâmetro).
     * @param {function} processData Função que processa os dados que serão usados na função create,
     * deve retornar um objeto no format utilizado na função create, quando definida ela será usada
     * no parâmetro params das funções de create e update
     */
    constructor(selector, input_offset_placeholder = '#', processData = null) {
        this.template = $(selector);
        this.input_offset_placeholder = input_offset_placeholder;
        this.processData = processData ?? (data => data);
    }

    /**
     * Constroi um elemento jQuery a partir do template selecionado na instância desse objeto
     * e os parametros passados nesse método
     * @param {Object|Array} params Os parâmetros que serão utilizados para criar o objeto html, 
     * caso processData não tenha sido definidos, as chaves do object (de nivel 1) representam 
     * os nomes de cada elemento a ser alterado  nome de um elemento é definito em seu atributo 
     * "data-name" no própio html, o objeto params pode ser um array de objetos parâmetros, nesse caso
     * este array será mesclado em um único objeto, a chave 'self' sempre referencia o elemento raiz do template, 
     * cada chave deve ter um valor, caso este seja do tipo string ou number e ele seja um INPUT 
     * o valor dessa chave é intendido como o valor do elemento, caso SELECT a função val do
     * jquery é chamada para o elemento com o valor passado, caso não seja INPUT 
     * nem SELECT ele é inserido como html interno do elemento, este valor pode também ser um objeto, 
     * neste caso as chaves deste objeto serão os nomes do atributos que serão alterados o elemento pode 
     * possuir o parâmetro "error" que define qual chave de erro (nome de campo definido na validação
     * do formulário) será usado nele, isto só tem efeito quando existe um elemento com atributo data-error
     * com valor igual a chave do elemento e.g.: caso haja um input chamado id_usuario e erros no formulario
     * na validacao de id_usuario estes erros serão exibidos no elemento com data-error="id_usuario", ao utilizar
     * o atributo error para id_pessoa, isto vai fazer com que os erros no campo id_pessoa aparecam no elemento
     * com o data-error="id_usuario", caso a atributo error seja true utiliza o nome da elemento ("data-name") e.g.:
     * create({
     *      self: {
     *          id: 'id-do-elemeto-raiz'
     *      },
     *      'quantidade-input': 2,
     *      'conteudo-text': 'texto que será inserido',
     *      'data-vencimento': {
     *          value: "2022-03-01",
     *          error: 'data_vencimento' // Se houver erro neste campo, adiciona ao elemento com data-error='data-vencimento'
     *      },
     *      'delete-button': {
     *          onclick: '$(this).parent().remove()'
     *          text: 'Deletar'
     *      }
     * })
     * @param {string|number|null} input_offset Caso os inputs sejam um array, este valor vai determinar
     * um offset para esse array, os nomes de input e select que tiverem a string do parâmetro
     * input_offset_placeholder serão substituidas por este valor, caso nulo isso não é feito
     * @param {object} errors Um objeto com os atributos prefix, caso exista um prefixo no nome
     * dos campos (e.g.: pagamentos[0][valor] neste caso o prefixo é pagamentos), e um atributo data
     * com os erros que vieram do backend, utilize '{{ json_encode($errors->messages()) }}' para inserir no atributo data de errors
     * @returns {object} O elemento jquery criado nesse processo
     */
    create(params, input_offset = null, errors = { data: '{}' }) {
        // Processando os dados
        params = this.processData(params);
        // Validando os erros e gerando o JSON
        try {
            errors.data = JSON.parse((errors.data ?? '{}').replace(/&quot;/g, '"'));
        } catch { }

        // Gerando um elemento
        const element = $(this.template.html());
        const error_list = this.parseErrors(errors);
        const error_has_prefix = (errors.prefix ?? '').length > 0;
        // Trocando os placeholders dos offsets para o valor passado em offset caso não nulo
        if (input_offset !== null) {
            const offset_el = this.input_offset_placeholder;
            const to_replace = ['name', 'id', 'for'];
            element.find('[name], [id], [for]').each(function (key, element) {
                element = $(element);
                to_replace.forEach((item) => {
                    if (element.attr(item)) {
                        $(element).attr(item, $(element).attr(item).replace(offset_el ?? '#', input_offset));
                    }
                });
            });
        }
        return this.parseParams(params, element, error_list, error_has_prefix, input_offset);
    }

    /**
     * Atualiza o elemento passado com os dados informados em params, utiliza o formato de dados
     * da função create caso processData não tenha sido definidos
     * @param {object} params Parâmetros a serem alterados no elemento
     * @param {object} element Elemento a ser alterado
     * @return {object} O elemento alterado
     */
    update(params, element) {
        params = this.processData(params);
        return this.parseParams(params, $(element));
    }

    /**
     * Manipula os parâmetros passados, inserindo e alterando o html de acordo com a necessidade
     * e com os subelementos
     * @param {object} params Objeto idêntico ao passado nas funções create e update
     * @param {object} element 
     */
    parseParams(params, element, error_list = null, error_has_prefix = null, input_offset = null) {
        // Fazendo merge dos params caso seja um array
        params = this.mergeObjects(params);
        if(params === null) {
            return;
        }
        // Preenchendo esse elemento com os dados passados por parâmetro
        for (const name in params) {
            var subelements = Array();
            // Adicionando a possibilidade de idenficar o self
            if (name == 'self') {
                subelements = Array.from(element);
            } else {
                subelements = Array.from(element.find(`[data-name="${name}"]`));
                if (error_list) {
                    // Caso exista elemento de erro
                    const error_element = $(element).find(`[data-error="${name}"]`);
                    if (error_element[0] != null && error_element[0] != undefined) {
                        const error_name = params[name] && (params[name].error ?? null) ? (params[name].error !== true ? params[name].error : name) : name;
                        let errors = [];
                        if (error_has_prefix && error_list[error_name]) {
                            errors = error_list[error_name][input_offset] ? error_list[error_name][input_offset] : [];
                        } else {
                            errors = error_list[error_name] ?? [];
                        }

                        let error_message = errors[0] ?? '';
                        if (error_element && error_message.length > 0) {
                            error_element.text(error_message);
                        }
                    }
                }
            }

            // Caso tenha múltiplos sub-elementos de mesmo nome
            subelements.forEach(function (subelement, key) {
                // Caso o parâmetro passado seja simples (Não objeto) infere o que deve ser feito
                if ((params[name] ?? 0).constructor !== Object) {
                    switch (subelements[key].tagName) {
                        // Caso seja um input ele seta o valor ou marca a checkbox
                        case 'INPUT':
                            if ($(subelements[key]).attr('type') == 'checkbox' && params[name]) {
                                $(subelements[key]).prop('checked', true);
                            } else {
                                $(subelements[key]).val(params[name]);
                            }
                            break;
                        // Para evitar select vazio, verifica quando select
                        case 'SELECT':
                            if (params[name] !== null) {
                                $(subelements[key]).val(params[name]);
                            }
                            break;
                        // Caso seja qualquer outro elemento seta o texto
                        default:
                            $(subelements[key]).text(params[name]);
                    }
                } else {
                    // Do contrrário, caso seja objeto iteraje sobre o objeto e decide o que fazer com cada atributo
                    for (const attr in params[name]) {
                        // Defidindo o que fazer com cada tipo de atributo passado
                        switch (attr) {
                            case 'text':
                                $(subelements[key]).text(params[name][attr]);
                                break;
                            case 'value':
                                // Evitar selects vazios
                                if (subelements[key].tagName != 'SELECT' || (subelements[key].tagName == 'SELECT' && params[name][attr] !== null)) {
                                    $(subelements[key]).val(params[name][attr]);
                                }
                                break;
                            // Caso seja pedido para alterar o html interno
                            case 'html':
                                $(subelements[key]).html(params[name][attr]);
                                break;
                            // Caso seja passado options, gera as várias options do select
                            case 'options':
                                if (subelements[key].tagName == 'SELECT' && (params[name][attr] ?? 0).constructor === Array) {
                                    params[name][attr].forEach(function (option, key) {
                                        const element = $(`<option value="${(option.value ?? key)}" selected="">${option.text}</option>`);
                                        if (params[name][attr].length == 1 || option.selected) {
                                            element.prop('selected', true);
                                        }
                                        $(subelements[key]).append(element);
                                    });
                                }
                                break;
                            // Caso seja passado uma option, gera a option e seleciona
                            case 'option':
                                if (params[name][attr].text ?? null) {
                                    const element = $(`<option value="${params[name][attr].value}" selected="">${params[name][attr].text}</option>`);
                                    $(subelements[key]).append(element);
                                }
                                break;
                            // Somente caso vá usar o método update, passar o valor true ou null pelo parametro icheck garante sincronia com o icheck
                            case 'icheck':
                                if (params[name][attr]) {
                                    $(subelements[key]).prop('checked', true).iCheck('check').trigger('change');
                                } else {
                                    $(subelements[key]).removeAttr('checked');
                                }
                                break;
                            default:
                                // Se for um evento
                                if (/on(.)+/.test(attr) && (attr.split('on')[0] ?? '').length == 0) {
                                    let event = attr.split('on');
                                    event.splice(0, 1);
                                    $(subelements[key]).on(event.join(''), params[name][attr]);
                                }
                                else {
                                    // Qualquer outra coisa é um attributo
                                    $(subelements[key]).attr(attr, params[name][attr]);
                                }
                        }
                    }
                }
            });
        }

        return element;
    }

    /**
     * Manipula a lista de erros para facilitar a análise na hora
     * de gerar o elemento
     * @param {Array} error_list Lista de erros recebida do back end
     * para ser convertida em uma forma mais eficiente para análise
     */
    parseErrors(error_list) {
        let result = {};
        const prefix = error_list.prefix ?? '';
        const has_prefix = prefix.length > 0;
        const data = error_list.data ?? {};
        if (has_prefix) {
            for (const field in data) {
                const field_components = field.split('.');
                const field_name = field_components[2];

                if (field_components[0] != prefix) {
                    continue;
                }

                if (!result[field_name]) {
                    result[field_name] = {};
                }

                /*
                    Substitui o nome que originalmente fica 'prefixo'.'offset'.'campo' por somente 'campo'
                    no texto das menstagens
                */
                result[field_name][field_components[1]] = (data[field] ?? []).map(el => el.replace(field, field_name));
            }
            return result;
        } else {
            return data;
        }
    }

    /**
     * A partir de um array de objetos, mescla os objetos em um só objeto
     * @param {Array|Object} objects Array de objetos ou objeto, caso seja
     * só um objeto retorna o mesmo
     * @returns {Object|null}
     */
    mergeObjects(objects) {
        try {
            if (objects.constructor === Array) {
                let temp = {};
                for (const item in objects) {
                    for (const param in objects[item]) {
                        temp[param] = objects[item][param];
                    }
                }
                objects = temp;
            }
        } catch {
            objects = null;
        }
        return objects;
    }
}
