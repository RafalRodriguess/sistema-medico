<div>

    @foreach ($itens as $msg)
    @if($msg->remetente == 'cliente')

    <li>
        <div class="chat-img"><img class="logo-chat-comercial" src="{{ asset('material/assets/images/client_default.png') }}" alt="user" /></div>
        <div class="chat-content">

            <h5>{{$usuario->nome}}</h5>
            <div class="box bg-light-inverse">
                {{$msg->mensagem}}
                <br>
                <div class="chat-time">{{formatTimePassed($msg->created_at)}}</div>
            </div>
        </div>
    </li>

    @endif


    @if($msg->remetente == 'comercial')

    <li class="reverse">
        <div class="chat-content">
            <h5>{{$comercial->nome_fantasia}}</h5>
            <div class="box bg-light-info">
                <div class="chat-time" style="text-align: revert;">
                    <small> {{formatTimePassed($msg->created_at)}}</small>
                </div>
                {{$msg->mensagem}}
                <br>
                @if($msg->data_visto)
                <div class="chat-time" style="text-align: revert;">
                    <i class="mdi mdi-check-all"></i><small> {{formatTimePassed($msg->data_visto)}}</small>
                </div>
                @endif

            </div>
        </div>



        <div class="chat-img"><img class="logo-chat-comercial" @if ($comercial->logo)
            src="{{ \Storage::cloud()->url($comercial->logo) }}"
            @else
            src="{{ asset('material/assets/images/default_logo.png') }} "
            @endif alt="user" /></div>
    </li>
    @endif


    @endforeach

</div>


<style>
    .logo-chat-comercial {
        width: 45px;
        height: 45px;
        object-fit: cover;
    }
</style>