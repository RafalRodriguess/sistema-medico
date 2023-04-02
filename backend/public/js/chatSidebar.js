/**
 * Classe que gerencia as o mini chat da barra lateral
 * @param opcoes = {
 *      loading: $('.meu-loading') // O elemento de loading
 *      sidebar: $('.meu-elemento'), // elemento raiz da sidebar
 *      elemento: $('.meu-elemento'), // elemento onde o chat será exibido
 *      button: $('.meu-button'), // botão que chama o chat
 *      url_busca: "https://minha-rota", // Rota onde os contatos serao buscadas
 *      url_redirecionamento: "https://minha-rota", // Rota que os contatos redirecionaram ao serem clicados
 *      token: "meutoken", // Token csrf para fazer as requests ajax
 *      delay: 8000, // Tempo em ms entre atualizacoes, padrão é 3000
 *      locked: false // Define se a sidebar será desativada e impossibilitada de funcionar, útil para remover ela em páginas onde
 *      ela não deve aparecer
 * }
 */
 class ChatSidebar {
    constructor(opcoes) {
        for (const key in opcoes) {
            this[key] = opcoes[key];
        }
        // Preparando os elementos
        this.sidebar = $(this.sidebar);
        this.elemento = $(this.elemento);
        this.loading = $(this.loading);
        this.button = $(this.button);
        let chatsidebar = this;
        this.button.on('click', (e) => {
            if(chatsidebar.sidebar.css('display') == 'block') {
                chatsidebar.sidebar.hide();
            } else {
                chatsidebar.sidebar.show();
            }
        });
        this.delay = this.delay ?? 8000;
        this.locked = this.locked ?? false;
    }

    start()
    {
        this.stop_flag = false;
        if(this.locked) {
            this.disable();
            return;
        }
        let sidebar = this;
        this.getContatos(sidebar, () => {
            sidebar.button.show();
        });
    }

    stop(force = true)
    {
        if(this.thread)
        {
            try {
                clearTimeout(this.thread);
            } catch { }
        }
        if(force) {
            this.stop_flag = true;
            this.sidebar.hide();
            this.button.hide();
        }
    }

    schedule() {
        let notificacoes = this;
        if (!this.stop_flag) {
            this.thread = setTimeout(() => this.getContatos(notificacoes), this.delay);
        } else {
            try {
                clearTimeout(this.thread);
            } catch { }
        }
    }

    disable()
    {
        this.stop(true);
        this.locked = true;
    }

    getContatos(sidebar = null, callback = null) {
        if (!sidebar) {
            sidebar = this;
        }

        sidebar.stop(false);
        $.ajax(sidebar.url_busca, {
            method: "POST",
            data: {
                "_token": sidebar.token,
                ultimos_contatos: sidebar.cache,
                ignorar_ordenacoes: true,
                exibir_ultima_mensagem: true
            },
            success: function (response) {
                if (response.result) {
                    sidebar.loading.show();
                    if(Array.isArray(response.contatos) && response.contatos.length > 0) {
                        sidebar.cache = {};
                        response.contatos.forEach((item) => sidebar.cache[item.id] = item['mensagem_visualizada'] ?? -1);
                    }
                    sidebar.elemento.html(response.html);
                    setTimeout(function () {
                        sidebar.elemento[0].scrollTo(0, 0);
                    },2);
                    sidebar.elemento.find('.contato').each((key, item) => {
                        const el = $(item);
                        el.on('click', () => {
                            window.open(sidebar.url_redirecionamento + "?tab=" + el.attr('el-id'), '_blank');
                        });
                    });
                }

                sidebar.loading.hide();
            }
        }).then(() => {
            sidebar.schedule();
            if(callback) {
                callback();
            }
        });
    }
}
