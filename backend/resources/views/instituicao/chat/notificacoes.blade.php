@foreach ($mensagens as $mensagem)
    <a target="_blank" href="{{ route('instituicao.chat.index', ['tab' => $mensagem->remetente->id]) }}" class="notification-message">

        @include('instituicao.chat.imagem-usuario', ['usuario' => $mensagem->remetente])

        @php
            $data_hora = (new \DateTime($mensagem->data_hora));
        @endphp
        <div class="mail-content">
            <p class="mensagem-usuario">{{$mensagem->remetente->nome}}</p>
            <p class="mensagem">{{$mensagem->mensagem}}</p>
            <p class="mensagem-data-hora">{{ $data_hora->format('H:i - d/m/Y') }}</p>
        </div>
    </a>
@endforeach
