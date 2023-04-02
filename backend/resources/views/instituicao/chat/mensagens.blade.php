@if (!empty($mensagens->nextPageUrl()))
    <span class="d-block mx-auto carregar-mais-mensagens">Carregando ...</span>
@endif
@foreach ($mensagens as $mensagem)
    @php
        $data_hora = new \DateTime($mensagem->data_hora);
    @endphp
    @if ($usuario->id != $mensagem->instituicao_usuarios_destinatario)
        <div class="mensagem destinatario" id="mensagem-{{ $mensagem->id }}">
            <div class="mensagem-header">
                <div class="mensagem-data-wrapper">
                    <span class="mensagem-data">{{ $data_hora->format('d/m/Y') }}</span>
                </div>
                <h5 class="mensagem-nome">{{ $mensagem->remetente->nome }}</h5>
            </div>
            <div class="mensagem-body">
                <div class="mensagem-hora-wrapper">
                    <span class="mensagem-hora">{{ $data_hora->format('H:i') }}h</span>
                </div>
                @php
                    $has_mensagem = !empty($mensagem->mensagem);
                @endphp
                <div class="mensagem-conteudo @if (!$has_mensagem) mensagem-vazia @endif">
                    {{ $has_mensagem ? $mensagem->mensagem : 'Mensagem indisponível.' }}</div>
            </div>
        </div>
    @else
        <div class="mensagem" id="mensagem-{{ $mensagem->id }}">
            <div class="mensagem-header">
                <h5 class="mensagem-nome">{{ $mensagem->remetente->nome }}</h5>
                <div class="mensagem-data-wrapper">
                    <span class="mensagem-data">{{ $data_hora->format('d/m/Y') }}</span>
                </div>
            </div>
            <div class="mensagem-body">
                @php
                    $has_mensagem = !empty($mensagem->mensagem);
                @endphp
                <div class="mensagem-conteudo @if (!$has_mensagem) mensagem-vazia @endif">
                    {{ $has_mensagem ? $mensagem->mensagem : 'Mensagem indisponível.' }}</div>
                <div class="mensagem-hora-wrapper">
                    <span class="mensagem-hora">{{ $data_hora->format('H:i') }}h</span>
                </div>
            </div>
        </div>
    @endif
@endforeach
