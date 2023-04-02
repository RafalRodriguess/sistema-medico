<aside id="sidebar-chat" class="shadow-sm" style="display: none">
    <div class="sidebar-chat-header">
        <span class="title">Chat</span>
        <button type="button" id="sidebar-close-button" class="close-button sidebar-chat-open-close-button"><i
                class="fas fa-times"></i></button>
    </div>
    <div class="sidebar-chat-body">
        <div id="sidebar-chat-contatos" class="contatos-container">
        </div>

        <div id="sidebar-chat-loading" class="loading-container">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</aside>

<button type="button" id="sidebar-chat-button" class="sidebar-chat-open-close-button" style="display: none"><i
        class="fas fa-envelope"></i></button>

<script type="text/template" id="template-popup-notificacao">
    <div id="notificacao-popup-#" class="notificacao-chat card visible">
        <div class="card-container d-flex flex-wrap">
            <div data-name="imagem-usuario" class="card-header p-0"></div>
            <div class="conteudo-container p-0">
                <div data-name="nome-usuario" class="card-header"></div>
                <div data-name="conteudo-mensagem" class="card-body">teste</div>
            </div>
        </div>
        <div class="card-footer"><div data-name="hora-mensagem"></div><div data-name="data-mensagem"></div></div>
    </div>
</script>
<div id="toasts-notificacao">
</div>
