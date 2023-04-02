@extends('layouts.material')
@push('fonts')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cousine&display=swap" rel="stylesheet">
@endpush

@section('navbar')
    @include('instituicao.partials.navbar')
@endsection

@includeIf(session('instituicao'), 'sidebar view')
@section('sidebar-nav')
    @include('instituicao.partials.sidebar')
@endsection

@section('sidebar-footer')
    @include('instituicao.partials.sidebarfooter')
@endsection

@section('right-sidebar')
    @can('habilidade_instituicao_sessao', 'utilizar_chat')
        @include('instituicao.partials.sidebarchat')
    @endcan
@endsection

@push('scripts')
    @can('habilidade_instituicao_sessao', 'utilizar_chat')
        <script type="text/javascript" src="{{ asset('js/chatNotifications.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/chatSidebar.js') }}"></script>
        <script>
            var notificacoes_chat = null;
            var notificacoes_popup = null;
            var sidebar_chat = {
                start: () => null,
                // Permite que a sidebar seja desativada antes do evento ready
                disable: () => {
                    this.desabilitado = true;
                }
            };

            $(document).ready(function() {
                notificacoes_popup = new NotificacoesPopup({
                    token: "{{ csrf_token() }}",
                    url_imagem: "{{ route('instituicao.chat.getImagemUsuario') }}",
                    url_mensagem: "{{ route('instituicao.chat.index') }}",
                    elemento: $('#toasts-notificacao'),
                    template: '#template-popup-notificacao',
                    delay: 4000
                });

                notificacoes_chat = new ChatNotifications({
                    elemento: $('#chat-notifications-dropdown'),
                    url_mensagem: "{{ route('instituicao.chat.notificacoes') }}",
                    token: "{{ csrf_token() }}",
                    loading: $('#chat-notifications-dropdown .loading-container'),
                    popups: notificacoes_popup
                });

                sidebar_chat = new ChatSidebar({
                    sidebar: $('#sidebar-chat'),
                    elemento: $('#sidebar-chat-contatos'),
                    loading: $('#sidebar-chat-loading'),
                    button: $('.sidebar-chat-open-close-button'),
                    url_busca: "{{ route('instituicao.chat.buscarContatos') }}",
                    url_redirecionamento: "{{ route('instituicao.chat.index') }}",
                    token: "{{ csrf_token() }}",
                });

                setTimeout(() => {
                    if(sidebar_chat.desabilitado) {
                        sidebar_chat.disable();
                    } else {
                        sidebar_chat.start();
                    }
                }, 1000);

                window.defaultHeight = window.outerHeight;
            });

            addEventListener('onsuspend', () => {
                document.visibilityState = 'visible';
            });
        </script>
    @endcan
    <script>
        /**
         * Evento disparado quando a tela é redimensionada os dois switchs que definem ações a serem executadas
         * um para quando o navegador entra em fullscreen e outro quando sai, ambos tem o propósito de definir
         * qual rota que tal classes serão inseridas quando entrar/sair de fullscreen.
         **/
        window.onresize = () => {

            /**
             * Array que determina as rotas e classes para cada uma delas, cada
             * rota é definida por um objeto onde o atributo route defina a rota
             * e o atributo classes é um array das classes que serão adicionadas
             * e removidas de acordo com a situação
             **/
            const routes = [{
                route: "{{ route('instituicao.chat.index') }}",
                classes: ['minimal-fullscreen', 'single-view-fullscreen']
            }];


            // Gera o caminho atual
            let path = window.location.pathname;
            if (path.length > 0 && path.charAt(path.length - 1) == '/') {
                path = path.substr(0, path.length - 1);
            }
            const current_route = window.location.origin + path;

            // Caso entre no fullscreen
            if (window.outerHeight > window.defaultHeight) {
                window._isFullscreen = true;
                routes.forEach(item => {
                    if (item.route == current_route) {
                        if (Array.isArray(item.classes)) {
                            item.classes.forEach(style => {
                                $(document.body).addClass(style);
                            });
                        } else {
                            $(document.body).addClass(item.classes);
                        }
                    }
                });
            } else if (window._isFullscreen) { // Caso saia do fullscreen
                routes.forEach(item => {
                    if (item.route == current_route) {
                        if (Array.isArray(item.classes)) {
                            item.classes.forEach(style => {
                                $(document.body).removeClass(style);
                            });
                        } else {
                            $(document.body).removeClass(item.classes);
                        }
                    }
                });
            }
        };
    </script>
@endpush
