/**
 * Classe que gerencia as notificações de chat do sistema
 * @param opcoes = {
*       loading: $('.meu-loading') // O elemento de loading
 *      elemento: $('.meu-dropdown'), // Raiz do dropdown onde as notificacoes serão exibidas
 *      url_mensagem: "https://minha-rota", // Rota donde as notificacoes serao buscadas
 *      token: "meutoken", // Token csrf para fazer as requests ajax
 *      delay: 8000, // Tempo em ms entre atualizacoes, padrão é 3000
 *      popups: new NotificacoesPopup() // Gerenciador de popups
 * }
 */
class ChatNotifications {
    constructor(opcoes) {
        for (const key in opcoes) {
            this[key] = opcoes[key];
        }
        // Preparando os elementos
        this.elemento = $(this.elemento);
        this.notify = $(this.elemento.find('.notify'));
        this.loading = $(this.loading);
        this.delay = this.delay ?? 8000;
        this.thread_check = null;
        if (this.token && this.url_mensagem) {
            this.start();
        }
    }

    start() {
        this.stop_flag = false;
        this.buscar();
    }

    stop(force = true) {
        if (this.thread) {
            try {
                clearTimeout(this.thread);
            } catch { }
        }
        if (force) {
            this.stop_flag = true;
        }
    }

    schedule() {
        let notificacoes = this;
        if (!this.stop_flag) {
            this.thread = setTimeout(() => this.buscar(notificacoes), this.delay);
        } else {
            try {
                clearTimeout(this.thread);
            } catch { }
        }
    }

    buscar(notificacoes = null) {
        if (!notificacoes) {
            notificacoes = this;
        }
        notificacoes.stop(false);
        $.ajax(this.url_mensagem, {
            method: "POST",
            data: {
                "_token": this.token,
                ultima_mensagem: notificacoes.ultima_msg ?? null,
                ignorar_ordenacoes: true
            },
            success: function (response) {
                const old_cache = notificacoes.cache;
                if (response.result) {
                    notificacoes.ultima_msg = response.mensagens[0] ? (response.mensagens[0].data_hora ?? null) : null;
                    notificacoes.cache = response.mensagens ?? null;
                    notificacoes.loading.show();
                    notificacoes.elemento.find('.message-center').html(response.html);
                }

                if (notificacoes.cache.length > 0) {
                    notificacoes.notify.show();
                    notificacoes.thread_check = setTimeout(notificacoes.checkNotifications(old_cache ?? [], notificacoes), 500);
                } else {
                    notificacoes.notify.hide();
                }

                notificacoes.loading.hide();
            }
        }).then(() => {
            notificacoes.schedule();
        });
    }

    checkNotifications(old_cache, notificacoes = null) {
        if (!notificacoes) {
            notificacoes = this;
        }
        let check = [];
        notificacoes.cache.forEach((item, key) => {
            if (!(old_cache[key] ?? false) || item.id != old_cache[key].id) {
                check.push(item);
            }
        });

        check.forEach(item => {
            // Usando o cache do navegador para identificar mensagens já exibidas
            if (!window.localStorage.getItem(['mensagens-popup', item.mensagem_id])) {
                notificacoes.popups.push(item.nome, item.mensagem, item.data_hora, item.usuario_id);
                window.localStorage.setItem(['mensagens-popup', item.mensagem_id], true);
            }
        });
    }
}

/**
 * @param {number} id O id popup
 * @param {HtmlTemplate} template O template do popup
 * @param {object} options {
 *      'titulo': "Titulo do popup",
 *      'conteudo': "Conteudo do popup",
 *      'imagem': "Html da imagem do pupup",
 *      'delay': 5000, // O tempo antes do popup sumir
 *      'url_mensagem': 'http://meuredirecionamento', // Url de redirecionamento para o chat
 *      'data_hora': '2021-11-14 09:12:00',
 * }
 */
class Popup {
    constructor(id, template, options) {
        this.id = id;
        this.template = template;
        for (const key in options) {
            this[key] = options[key];
        }

        this.delay = options.delay ?? 4000;
        this.options = options;
        this.timeout = null;
        this.ondisable = options.ondisable ?? null;
        // Fazendo manual pq o new Date() n ta funcionando
        if (this.options.data_hora && this.options.data_hora.includes(' ')) {
            this.data = this.options.data_hora.split(' ');
            this.hora = this.data[1].split(':');
            this.data = this.data[0].split('-');
        }
        this.max_visible = 3;
    }

    generate(clear_cache = false) {
        let popup = this;
        if (!this.element || clear_cache) {
            this.element = this.template.create({
                'nome-usuario': this.options.titulo ?? '',
                'conteudo-mensagem': this.options.conteudo ?? '',
                'imagem-usuario': {
                    'html': this.options.imagem ?? ''
                },
                'data-mensagem': this.data ? `${this.data[2]}/${this.data[1]}/${this.data[0]}` : '',
                'hora-mensagem': this.hora ? `${this.hora[0]}:${this.hora[1]} h` : ''
            }, this.id).on('click', () => popup.url_mensagem ? window.open(`${popup.url_mensagem}?tab=${popup.id}`, '_blank') : '');
        }
        this.timeout = setTimeout(() => {
            popup.hide();
        }, this.delay);
        return this.element;
    }

    hide() {
        let popup = this;
        this.element.removeClass('visible');
        this.timeout_disable = setTimeout(() => {
            popup.disable();
        }, 500);
    }

    disable(runevent = true) {
        this.element.remove();
        if (this.ondisable && runevent) {
            this.ondisable(this.id);
        }
    }
}

/**
 *  Classe que gerencia a exibição de popups
 *  @param {object} opcoes {
 *      'token': 'meu_token', // Token csrf para execução de ajax
 *      'url_imagem': 'https://minhaurl', // Url para busca de imagens
 *      'url_mensagem': 'https://minhaurl', // Url redirecionar para o chat
 *      'elemento': $('.meu-elemento'), // Elemento dom / jquery onde as mensagens serão inseridas
 *      'template': '.meu-template', // Seletor que identifica o container do template para popups
 *      'delay': 5000 // Tempo em ms que leva para o popup sumir
 * }
 */
class NotificacoesPopup {
    constructor(opcoes) {
        for (const key in opcoes) {
            this[key] = opcoes[key];
        }

        this.elemento = $(this.elemento);
        this.template = new HtmlTemplate(this.template);
        this.queue = new Array();
        this.popups = new Map();
        this.cache = new Map();
        this.delay = this.delay ?? 4000;
        this.max_popups = 3;
    }

    async getImagemUsuario(id) {
        let popup = this;
        return $.ajax(popup.url_imagem, {
            method: "POST",
            data: {
                "_token": popup.token,
                usuario: id
            },
            success: response => response
        });
    }

    push(nome, mensagem, data_hora, id) {
        let popup = this;
        const build = imagem => {
            const message = new Popup(
                id,
                popup.template,
                {
                    'titulo': nome,
                    'conteudo': mensagem,
                    'imagem': imagem,
                    'delay': popup.delay,
                    'url_mensagem': popup.url_mensagem,
                    'ondisable': () => popup.pop(id),
                    'data_hora': data_hora
                }
            );
            if (popup.popups.size < this.max_popups) {
                popup.elemento.append(message.generate());
                popup.popups.set(id, message);
            } else {
                popup.queue.push(message);
            }
        };

        if (!popup.cache.has(id)) {
            this.getImagemUsuario(id).then(imagem => {
                popup.cache.set(id, imagem);
                build(imagem);
            });
        } else {
            build(popup.cache.get(id));
        }
    }

    pop(id, hide = false) {
        if (hide) {
            this.popups.get(id).disable(false);
        }
        this.popups.delete(id);
        if (this.queue.length > 0) {
            const message = this.queue.shift();
            this.push(message.options.titulo, message.options.conteudo, message.options.data_hora, message.id);
        }
    }
}
