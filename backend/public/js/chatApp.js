/**
 * Classe que vai controlar o chat inteiro
 * os parâmetro de construção é um option
 * com os seguntes campos
 * opcoes = {
 *      token: {{ csrf_token() }}, // Token crsf
 *      initial_tab: -1, // Tab para ser aberta ao iniciar -1 para padrão
 *      mensagens: {
 *          elemento: $('.meu-container'), //um jquery ou htmlelement do container no qual as mensagens serão exibidas
 *          input: $('.meu-campo'), //umjquery ou htmlelement do campo no qual as mensagens serão escritas
 *          button: $('.meu-botao'), //umjquery ou htmlelement do botão de enviar
 *          header: $('.meu-header'), //umjquery ou htmlelement do header que identifica a tab atual
 *          url_busca: "https://meu-site.com/minha-rota", //o endereço no qual as mensagens serão buscadas
 *          url_envio: "https://meu-site.com/minha-rota", //o endereço no qual as mensagens serão enviadas
 *          delay: 3000 //o tempo em milissegundos para buscar novas mensagens, não é necessário setar, valor padrão é 2000ms
 *          loading: $('.meu-loading') // O elemento de loading do das mensagens do chat
 *      },
 *      contatos: {
 *          container: $('.meu-container'), //um jquery ou htmlelement do wrapper com scroll dos contatos
 *          elemento: $('.meu-container'), //um jquery ou htmlelement do container no qual os contatos serão exibidos
 *          input: $('.meu-campo'), //umjquery ou htmlelement do campo para busca de contatos
 *          limpar: $('.meu-botao'), //umjquery ou htmlelement do botao de limpar campo
 *          url_busca: "https://meu-site.com/minha-rota", //o endereço no qual os contatos serão buscados
 *          url_imagem: "https://meu-site.com/minha-rota", //o endereço onde as imagens de usuario serão buscadas
 *          delay: 6000 //o tempo em milissegundos para buscar novos contatos, não é necessário setar, valor padrão é 4000ms
 *          loading: $('.meu-loading') // O elemento de loading do dos contatos do chat
 *      },
 * }
 */
class ChatApp {
    constructor(opcoes) {
        for (const key in opcoes) {
            this[key] = opcoes[key];
        }

        // Validando variáveis
        this.mensagens.delay = this.mensagens.delay ? this.mensagens.delay : 3000;
        this.contatos.delay = this.contatos.delay ? this.contatos.delay : 6000;

        // Preparando os elementos
        const elementos_mensagens = [
            'elemento',
            'input',
            'button',
            'header',
            'loading',
        ];
        const elementos_contatos = [
            'container',
            'elemento',
            'input',
            'limpar',
            'loading',
        ];
        for (const key in this.mensagens) {
            if (elementos_mensagens.indexOf(key) != -1) {
                this.mensagens[key] = $(this.mensagens[key]);
            }
            if (elementos_contatos.indexOf(key) != -1) {
                this.contatos[key] = $(this.contatos[key]);
            }
        }

        // Eventos
        let chat = this;
        this.contatos.input.on('keyup', (event) => {
            let length = event.target.value.length;
            if (length == 0 || length > 2) {
                chat.getContatos(chat, null, true);
            }
        });
        this.contatos.limpar.on('click', (event) => {
            chat.contatos.input.val('');
            chat.getContatos(chat, null, true);
        });
        this.mensagens.button.on('click', (event) => chat.sendMessage(chat));
        this.mensagens.input.on('keyup', (event) => {
            if (event.originalEvent.code == "Enter") {
                chat.sendMessage(chat);
            }
        })

        // Método recursivo para identificar e carregar mensagens antigas
        const loadOld = (chat, selfCall, secondCall = false) => {
            const mensagens = chat.mensagens.elemento.find('.mensagem');
            const offset = (mensagens.length > 0) ? $(mensagens[0]).offset().top - chat.mensagens.elemento.offset().top : -1;
            if (offset >= 0 && offset < 200 && (chat.mensagens.next_page ?? false)) {
                if(!chat.mensagens.page_load_thread && !secondCall) {
                    chat.mensagens.page_load_thread = setTimeout(() => selfCall(chat, selfCall, true), 2000);
                } else if(secondCall) {
                    chat.getMensagens(chat, false, true);
                    chat.mensagens.page_load_thread = null;
                }
            }
        }
        this.mensagens.elemento.on('scroll', () => loadOld(chat, loadOld));

        this.initial_tab = this.validateNumber(this.initial_tab, -1);
        this.active_tab = null;
        this.mensagens.current_page = 1;
        this.mensagens.next_page = false;

        // Inicializando flags
        this.mensagens.lock =
            this.mensagens.first_call =
            this.contatos.lock =
            this.mensagens.sendingLock = false;
    }

    // Trava as mensagens e exibe o icone de loading no botão de enviar
    lockSendingMensagens(status = true, chat = null) {
        if (!chat) {
            chat = this;
        }

        this.mensagens.sendingLock = status;
        if (status === true) {
            chat.mensagens.button.addClass('disabled');
            chat.mensagens.input.addClass('disabled');
            chat.mensagens.button.find('.send-message-icon').hide();
            chat.mensagens.button.find('.send-message-error').hide();
            chat.mensagens.button.find('.send-message-loading').show();
        } else if (status === false) {
            chat.mensagens.button.removeClass('disabled');
            chat.mensagens.input.removeClass('disabled');
            chat.mensagens.button.find('.send-message-icon').show();
            chat.mensagens.button.find('.send-message-error').hide();
            chat.mensagens.button.find('.send-message-loading').hide();
        } else {
            chat.mensagens.button.find('.send-message-icon').hide();
            chat.mensagens.button.find('.send-message-loading').hide();
            chat.mensagens.button.find('.send-message-error').show();
        }
    }

    start(callback = null) {
        this.lockSendingMensagens(false);
        // Chamar imediatamente
        let chat = this;
        if (!this.active_tab) {
            // Mudar a tab para uma inicial
            this.changeTab(null, true, null);
        } else {
            // Iniciar update automático
            this.getContatos(chat);
            this.getMensagens(chat, true, false, !this.mensagens.first_call);
            this.mensagens.first_call = true;
        }
        if (callback) {
            callback(this.active_tab ?? -1);
        }

        return this;
    }

    stop() {
        this.lockSendingMensagens();
        try {
            clearTimeout(this.contatos_thread);
        } catch { }
        try {
            clearTimeout(this.mensagens_thread);
        } catch { }

        return this;
    }

    // Entra no modo de aguardando cadastro de usuários
    freeze() {
        this.lockSendingMensagens(-1);
        chat.mensagens.loading.hide();
        try {
            clearTimeout(this.mensagens_thread);
        } catch { }

        return this;
    }

    /**
     * Muda a aba ativa e busca as mensagens dela
     * @param {int} tab Id do usuário (instituicao_usuario)
     * @param {boolean} buscar Define se é necessário buscar os contatos antes da troca de tab
     * @param {ChatApp} chat Contexto
     * @param {function} callback
     */
    changeTab(tab = -1, buscar = false, chat = null, callback = null) {
        if (!chat) {
            chat = this;
        }
        if (buscar) {
            return this.getContatos(null, () => chat.changeTab(tab, false, chat, callback));
        }

        tab = chat.validateNumber(tab, -1);
        if (tab <= 0) {
            if (this.initial_tab > 0) {
                tab = this.initial_tab;
            } else if ((chat.contatos.cache ?? []).length > 0) {
                tab = chat.contatos.cache[chat.contatos.cache.keys().next().value].id ?? -1;
            }

            if (tab <= 0) {
                return chat.freeze();
            }
        }

        // Caso seja uma tab diferente da atual e ela seja válida
        this.active_tab = chat.validateNumber(this.active_tab, -1);
        if (this.active_tab != tab) {
            this.stop();

            // Inicia o loading
            this.mensagens.loading.show();
            this.mensagens.cache = null;
            // Busca e carrega os dados da tab que foi carregada
            const contato = (this.contatos.cache ?? []).find(el => el.id == tab);
            if (!contato) {
                return chat.freeze();
            }
            this.mensagens.header.find('#chat-contacts-name').text(contato.nome);
            this.getImagemUsuario(contato.id);
            this.contatos.elemento.find('.selecionado').removeClass('selecionado');
            this.active_tab = tab;
            const elemento = this.contatos.elemento.find(`[el-id="${tab}"]`);
            elemento.addClass('selecionado');
            this.scrollTo(this.contatos.container, elemento[0].scrollTop);
            this.start(callback);
        } else if (callback) {
            callback(-1);
        }
    }

    /**
     * Busca e seleciona um contato pelo seu id
     * @param {Number} contato Id do contato
     * @param {ChatApp} chat Contexto
     * @param {function} callback
     */
    loadContato(contato, chat = null, callback = null) {
        if (!chat) {
            chat = this;
        }
        chat.getContatos(chat, () => chat.changeTab(contato, false, chat, callback));
    }

    // #region SCHEDULERS
    scheduleContatos(clear = false) {
        let chat = this;
        if (!clear) {
            this.contatos_thread = setTimeout(() => this.getContatos(chat), this.contatos.delay);
        } else {
            try {
                clearTimeout(this.contatos_thread);
            } catch { }
        }
    }
    scheduleMensagens(clear = false) {
        let chat = this;
        if (!clear) {
            this.mensagens_thread = setTimeout(() => this.getMensagens(chat), this.mensagens.delay);
        } else {
            try {
                clearTimeout(this.mensagens_thread);
            } catch { }
        }
    }
    //#endregion

    // Scrolla um elemento até que outro esteja visível
    scrollTo(element, YPosition, instant = false) {
        let options = null;
        if(instant) {
            options = {duration: 0};
        }
        element.animate({ scrollTop: Math.round(this.validateNumber(YPosition)) }, options);
    }

    // Scrolla o elemento até o final, caso top seja true, scrolla até o topo
    scrollEnd(scroll_element, top = false, instant = false) {
        let options = null;
        if(instant) {
            options = {duration: 0};
        }

        if (!top)
            scroll_element.animate({ scrollTop: Math.round(scroll_element[0].scrollHeight) }, options);
        else
            scroll_element.animate({ scrollTop: 0 }, options);
    }

    getMensagens(chat = null, scroll = false, load_old = false, instant_scroll = false) {
        if (!chat) {
            chat = this;
        }
        chat.scheduleMensagens(true);

        $.ajax(chat.mensagens.url_busca, {
            method: "POST",
            data: {
                "_token": chat.token,
                contato: chat.active_tab,
                ultima_mensagem: chat.mensagens.cache ? chat.mensagens.cache.data_hora : null,
                pages: (load_old && chat.mensagens.next_page) ? chat.mensagens.current_page + 1 : 1
            },
            success: function (response) {
                if (response.result) {
                    const mensagens = response.mensagens.data;
                    chat.mensagens.next_page = (response.mensagens.next_page_url ?? '').length > 0;
                    chat.mensagens.loading.show();
                    chat.mensagens.cache = mensagens[mensagens.length - 1];
                    if (!load_old) {
                        chat.mensagens.elemento.html(response.html);
                    } else {
                        chat.mensagens.current_page ++;
                        chat.mensagens.elemento.find('.carregar-mais-mensagens').remove();
                        const before = chat.mensagens.elemento[0].scrollHeight;
                        chat.mensagens.elemento.prepend(response.html);
                        chat.scrollTo(chat.mensagens.elemento, before, true);
                    }

                    // Scrolla caso esteja quase no fim do scroll
                    if (Math.round(chat.mensagens.elemento[0].scrollHeight) - Math.round(chat.mensagens.elemento[0].clientHeight + chat.mensagens.elemento[0].scrollTop) < 250) {
                        scroll = true;
                    }
                }
            },
        }).then((e) => {
            // Oculta o loading, caso carregando para cima, leva em conta a animação que dura por volta de 250ms ~ 500ms
            if(!load_old)
                chat.mensagens.loading.hide();
            else
                setTimeout(() => chat.mensagens.loading.hide(), 1000);

            chat.scheduleMensagens();
            // Scrollar para o fim do elemento
            if (scroll) {
                chat.scrollEnd(chat.mensagens.elemento, false, instant_scroll);
            }
        });
    }

    getContatos(chat = null, callback = null, scroll = false) {
        if (!chat) {
            chat = this;
        }
        const ultimos_contatos = chat.contatos.cache_busca ? chat.contatos.cache_busca : null;
        const contato_recente = (Array.isArray(chat.contatos.cache) && chat.contatos.cache.length > 0) ? chat.contatos.cache[0].id : null;
        chat.scheduleContatos(true);

        $.ajax(chat.contatos.url_busca, {
            method: "POST",
            data: {
                "_token": chat.token,
                busca: chat.contatos.input.val(),
                ultimos_contatos: ultimos_contatos,
                contato_recente: contato_recente
            },
            success: function (response) {
                let contato = null;
                if (response.result) {
                    chat.contatos.loading.show();
                    chat.contatos.cache = response.contatos;
                    if (Array.isArray(response.contatos) && response.contatos.length > 0) {
                        chat.contatos.cache_busca = {};
                        response.contatos.forEach((item) => chat.contatos.cache_busca[item.id] = -1);
                    }
                    chat.contatos.elemento.html(response.html);
                    contato = chat.contatos.elemento.find(`[el-id="${chat.active_tab}"]`);
                    contato.addClass('selecionado');
                }
                if (scroll) {
                    if(contato) {
                        chat.scrollTo(chat.contatos.elemento, contato.offset().top - chat.contatos.elemento.offset().top, true);
                    } else {
                        chat.scrollEnd(chat.contatos.elemento, true, true);
                    }
                }

                chat.contatos.elemento.children().on('click', function (event) {
                    chat.changeTab($(event.target).parents('.contato').attr('el-id'));
                });
                if (callback) {
                    callback();
                }
                chat.contatos.loading.hide();
            }
        }).then((e) => {
            chat.scheduleContatos();
        });
    }

    /**
     * Carrega a imagem do usuário destinatário da conversa atual
     * @param {Number} usuario Id do usuario
     * @param {ChatApp} chat contexto
     */
    getImagemUsuario(usuario, chat = null) {
        if (!chat) {
            chat = this;
        }

        $.ajax(chat.contatos.url_imagem, {
            method: "POST",
            data: {
                "_token": chat.token,
                usuario: usuario
            },
            success: function (response) {
                chat.mensagens.header.find("#chat-contacts-img-container").html(response);
            }
        });
    }

    // Envia a mensagem da caixa de texto de mensagem
    sendMessage(chat = null) {
        let message = chat.mensagens.input.val();
        if (!chat) {
            chat = this;
        }
        if (chat.mensagens.sendingLock || !message || message.length == 0) {
            return;
        }

        chat.lockSendingMensagens();
        chat.stop();
        $.ajax(chat.mensagens.url_envio, {
            method: "POST",
            data: {
                "_token": chat.token,
                destinatario: chat.active_tab,
                mensagem: message
            },
            success: function () {
                chat.mensagens.input.val('');
            }
        }).then(() => {
            chat.start();
            chat.lockSendingMensagens(false);
        });
    }

    /**
     * Tenta converter qualquer coisa em inteiro, caso não consiga retorna o
     * valor passado no parâmetro fallback
     * @param {*} value Um valor qualquer a ser convertido para inteiro
     * @param {Number} fallback Valor padrão caso não seja possível converter
     * @returns {Number} O valor convertido ou o fallback
     */
    validateNumber(value, fallback = 0) {
        return Number.isNaN(parseInt(value)) ? fallback : parseInt(value);
    }
}

/**
 * Classe que vai controlar o modal de usuários
 * os parâmetro de construção é um option
 * com os seguntes campos
 * opcoes = {
 *      token: 'meutoken', token csrf para requisições post
 *      url_busca: "http://minha_url", // Url da busca
 *      url_acao: "http://minha_url", // Url da adicao de contatos
 *      chat: meu_chat, // objeto do chat para integração
 *      modal: $('.meu-modal'), // objeto dom jquery do modal
 *      button_close: $('.meu-botao'), // objeto dom jquery do botão de fechar modal
 *      input: $('.meu-input'), // objeto dom jquery do input de busca
 *      button_clear: $('.meu-botao'), // objeto dom jquery do botão de limpar busca
 *      result_container: $('.meu-container'), // objeto dom jquery do container onde os usuários encontrados serão inseridos
 *      loading: $('.meu-loading'), // objeto dom jquery do loading
 * }
 **/
class ChatModalUsuarios {
    constructor(opcoes) {
        for (const key in opcoes) {
            this[key] = opcoes[key];
        }
        // Preparando os elementos
        const elementos = [
            'modal',
            'button_close',
            'input',
            'button_clear',
            'result_container',
            'loading'
        ];
        for (const key in opcoes) {
            if (elementos.indexOf(key) != -1) {
                this[key] = $(this[key]);
            }
        }

        let modal = this;
        this.input.on('keyup', () => modal.getUsuarios(modal));
        this.button_close.on('click', () => modal.switch());
        this.button_clear.on('click', () => modal.clear());
        this.status = this.modal.css('display') != 'none';
        this.thread_busca =
            this.thread_start =
            this.thread_adicionar = null;
    }

    clear(modal = null) {
        if (!modal) {
            modal = this;
        }
        modal.result_container.empty();
        modal.input.val('');
    }

    switch(modal = null) {
        if (!modal) {
            modal = this;
        }

        try {
            clearTimeout(modal.thread_busca);
        } catch { }
        try {
            clearTimeout(modal.thread_adicionar);
        } catch { }
        try {
            clearTimeout(modal.thread_start);
        } catch { }

        if (modal.status) {
            modal.modal.hide();
            modal.thread_start = setTimeout(() => modal.chat.start(), 500);
        } else {
            modal.modal.show();
            modal.thread_start = setTimeout(() => modal.chat.stop(), 500);
        }
        modal.status = !modal.status;
        modal.clear();
    }

    setLoading(status = true, modal = null) {
        if (!modal) {
            modal = this;
        }
        if (status) {
            modal.result_container.hide();
            modal.loading.show();
        } else {
            modal.loading.hide();
            modal.result_container.show();
        }
    }

    getUsuarios(modal = null) {
        if (!modal) {
            modal = this;
        }

        modal.setLoading();
        try {
            clearTimeout(modal.thread_busca);
        } catch { }
        modal.thread_busca = setTimeout(() => {
            $.ajax(modal.url_busca, {
                method: "POST",
                data: {
                    "_token": modal.token,
                    busca: modal.input.val()
                },
                success: function (response) {
                    if (response.result) {
                        modal.result_container.html(response.html);
                        modal.result_container.children().each((key, item) => {
                            const id = $(item).attr("el-id");
                            if (modal.chat.contatos.cache.findIndex(el => el.id == id) != -1) {
                                $(item).find('.button-adicionar-contato').remove();
                            }
                        });
                        modal.result_container.find('.button-adicionar-contato').each((key, item) => {
                            $(item).on('click', (e) => {
                                modal.addContato($(e.target).attr('el-id'), modal);
                            });
                        });
                    }
                },
            }).then((e) => {
                modal.setLoading(false);
            });
        }, 1000);
    }

    addContato(contato, modal = null) {
        if (!modal) {
            modal = this;
        }
        try {
            clearTimeout(modal.thread_adicionar);
        } catch { }
        if (!contato) {
            return;
        }

        modal.setLoading();
        modal.thread_adicionar = setTimeout(() => {
            $.ajax(modal.url_acao, {
                method: "POST",
                data: {
                    "_token": modal.token,
                    usuario_id: contato
                },
                success: function (response) {
                    if (response.result) {
                        modal.chat.loadContato(contato, modal.chat, () => {
                            modal.switch(modal);
                        });
                    }
                },
            }).then((e) => {
                modal.setLoading(false);
            })
        }, 1000);
    }
}
