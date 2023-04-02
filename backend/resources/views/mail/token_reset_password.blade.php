@component('mail::message',['title' => 'REDEFINIÇÃO DE SENHA'])

<p style="color:#320046;text-align: left;">
Olá <span style="color:#ff0000">{{ucfirst($name)}}</span>
</p>

<p style="color:#320046;text-align: left;">
Utilize o código <span style="color:#ff0000">{{($token)}}</span> para redefinir a sua senha
</p>



@endcomponent
