@extends('instituicao.layout')

@section('conteudo')
    <div id="chat-container-wrapper">
        <div id="chat-container" class="card mx-auto shadow-sm">
            <div class="col-md-3 p-0 chat-sidebar">
                <div class="chat-header">
                    <div class="chat-user">
                        @include('instituicao.chat.imagem-usuario', ['usuario' => $usuario])
                        <span class="chat-user-name">{{ $usuario->nome }}</span>
                    </div>
                    <div class="chat-search">
                        <input id="chat-contacts-search" type="text" class="search form-control">
                        <button id="clear-search-button" class="d-block clear-search"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="chat-contacts-container">
                    <div id="contacts-loading-element" class="loading-container">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div id="chat-contacts"></div>
                </div>
                <button class="btn btn-primary button-add-contact button-fechar-modal"><i class="fas fa-comment-alt"></i></button>
            </div>
            <div class="col-md-9 p-0 chat-messages-tab">
                <div id="chat-box-header" class="chat-header flex-row">
                    <span id="chat-contacts-name" class="chat-user-name text-right"></span>
                    <div id="chat-contacts-img-container"></div>
                </div>
                <div id="messages-loading-element" class="loading-container" style="display: none">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div id="chat-message-box" class="bg-light"></div>
                <div class="chat-text-input">
                    <input type="text" id="message-text-input" class="form-control">
                    <button type="button" id="send-message-button" class="btn btn-primary">
                        <i class="fas fa-times send-message-error" style="display: none"></i>
                        <i class="fas fa-paper-plane send-message-icon" style="display: none"></i>
                        <i class="spinner-border send-message-loading" role="status"></i>
                    </button>
                </div>
            </div>
        </div>
        <div id="modal-usuarios-busca" class="modal-generico" style="display: none">
            <div class="card col-md-6 col-sm-10 mx-auto p-1">
                <div class="d-flex justify-content-end">
                    <button type="button" class="button-fechar-modal btn btn-sm">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="card-body pt-1">
                    <div class="position-relative">
                        <input type="text" class="search form-control" id="modal-buscar-usuarios-input" placeholder="Busque por nome de usuário ...">
                        <button id="modal-clear-button" type="button" class="d-block clear-search"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="resultados-container position-relative overflow-auto mt-2 border">
                        <div id="resultados-busca-usuarios" class="d-flex flex-column">

                        </div>
                        <div id="loading-modal" class="loading-container" style="display: none">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('js/chatApp.js') }}"></script>
    <script>
        var chat = null;
        var chat_modal = null;

        $(document).ready(function() {
            chat =  new ChatApp({
                token: '{{ csrf_token() }}',
                initial_tab: parseInt('{{ $tab ?? -1 }}'),
                mensagens: {
                    elemento: $('#chat-message-box'),
                    input: $('#message-text-input'),
                    header: $('#chat-box-header'),
                    url_busca: "{{ route('instituicao.chat.buscarMensagens') }}",
                    url_envio: "{{ route('instituicao.chat.enviarMensagem') }}",
                    button: $('#send-message-button'),
                    loading: $('#messages-loading-element'),
                },
                contatos: {
                    container: $('.chat-contacts-container'),
                    elemento: $('#chat-contacts'),
                    input: $('#chat-contacts-search'),
                    limpar: $('#clear-search-button'),
                    url_busca: "{{ route('instituicao.chat.buscarContatos') }}",
                    url_imagem: "{{ route('instituicao.chat.getImagemUsuario') }}",
                    loading: $('#contacts-loading-element')
                }
            }).start(null, true);

            chat_modal = new ChatModalUsuarios({
                token: '{{ csrf_token() }}',
                url_busca: '{{ route("instituicao.chat.buscarUsuarios") }}',
                url_acao: '{{ route("instituicao.chat.adicionarContato") }}',
                chat: chat,
                modal: $('#modal-usuarios-busca'),
                button_close: $('.button-fechar-modal'),
                input: $('#modal-buscar-usuarios-input'),
                button_clear: $('#modal-clear-button'),
                result_container: $('#resultados-busca-usuarios'),
                loading: $('#loading-modal')
            });
        });

        addEventListener('load', (e) => {
            if(sidebar_chat) {
                sidebar_chat.disable();
            }
        });
    </script>
@endpush
