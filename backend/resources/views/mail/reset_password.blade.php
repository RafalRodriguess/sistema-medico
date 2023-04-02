@component('mail::message',['title' => 'REDEFINIÇÃO DE SENHA'])

<p style="color:#320046;text-align: left;">
Olá <span style="color:#ff0000">{{ucfirst($name)}}</span>
</p>

<p style="color:#320046;text-align: left;">
Clique no botão abaixo para redefinir a sua senha
</p>

@component('mail::button', ['url' => $link,'color' => 'blue'])
Redefinir Senha
@endcomponent


@endcomponent
