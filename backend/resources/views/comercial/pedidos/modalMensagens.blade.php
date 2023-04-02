<div class="modal inmodal" id="modalMensagens" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                Pedido #{{$pedido->id}}
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <small aria-hidden="true"><i class="fa fa-times"></i></small>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="chat-right-aside">
                        <!-- <div class="chat-main-header">
                            <div class="p-20 b-b">
                                <h3 class="box-title">Chat Message</h3>
                            </div>
                        </div> -->
                        <div class="chat-rbox">
                            <ul class="chat-list p-20" id="listMessages">

                                @livewire('comercial.pedidos-mensagens-pesquisa', ['pedido' => $pedido])

                            </ul>
                        </div>
                        <div class="card-body b-t">
                            <div class="row">
                                <div class="col-10">
                                    <textarea placeholder="Escreva sua mensagem aqui" class="form-control b-0" id="mensagemBox"></textarea>
                                </div>
                                <div class="col-2" align="center">
                                    <button onClick="enviaMensagem()" type="button" class="btn btn-info btn-circle btn-lg"><i class="fas fa-paper-plane"></i> </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<style>
    #mensagemBox {
        background: #efefef;
    }

    .chat-time {
        width: 100% !important;
    }

    .chat-rbox {
        overflow-y: scroll;
        height: 400px;
    }

    /* width */
    .chat-rbox::-webkit-scrollbar {
        width: 7px;
    }

    /* Track */
    .chat-rbox::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    /* Handle */
    .chat-rbox::-webkit-scrollbar-thumb {
        background: #1E88E5;
        border-radius: 10px;
    }

    /* Handle on hover */
    .chat-rbox::-webkit-scrollbar-thumb:hover {
        background: #0F4473;
    }
</style>


<script>
    function enviaMensagem() {

        var mensagem = $('#mensagemBox').val()

        if (mensagem.replace(/\s/g, '').length > 0) {

            $.ajax({
                url: '{{route("comercial.envia_mensagem", [$pedido])}}',
                method: 'POST',
                dataType: 'json',
                data: {
                    mensagem: mensagem,
                    '_token': '{{csrf_token()}}'
                },
                beforeSend: function() {
                    $('#loading').removeClass('loading-off');
                },
                success: function(response) {

                    if (response) {
                        $('#listMessages').append(
                            `
                            <li class="reverse">
                                <div class="chat-content">
                                    <h5>{{$comercial->nome_fantasia}}</h5>
                                    <div class="box bg-light-info">
                                        ` + response.mensagem + `
                                        <br>
                                        <div class="chat-time">agora</div>
                                    </div>
                                </div>
                                <div class="chat-img"><img  class="logo-chat-comercial" @if ($comercial->logo)
            src="{{ \Storage::cloud()->url($comercial->logo) }}"
            @else
            src="{{ asset('material/assets/images/default_logo.png') }} "
            @endif alt="user" /></div>
                            </li>
                            `
                        )
                        $('.chat-rbox').scrollTop($('.chat-rbox')[0].scrollHeight);
                        $('#mensagemBox').val('')

                    }
                    $('#loading').addClass('loading-off');
                },
                error: function(response) {
                    $('#loading').addClass('loading-off');
                }
            })
        }

    }

    $(document).ready(function() {
        $('.chat-rbox').scrollTop($('.chat-rbox')[0].scrollHeight);
    });
</script>


<?php

function formatTimePassed($date)
{

    $start_date = new DateTime($date);
    $since_start = $start_date->diff(new DateTime(date('Y-m-d H:i:s')));
    $dia = $since_start->d;
    $sec = $since_start->s;
    $min = $since_start->i;
    $hor = $since_start->h;

    if ($hor == 0 && $dia == 0 && $min == 0) {
        echo  $sec . ' Segundos atrás';
    }

    if ($hor == 0 && $dia == 0 && $min > 0) {
        echo  $min . ' Minutos atrás';
    }

    if ($hor > 0 && $dia == 0) {
        echo  $hor . ' Horas atrás';
    }

    if ($dia > 0) {
        echo date("d/m/Y", strtotime($date));
        echo ' às ';
        echo date("h:i:s", strtotime($date));
    }
}
?>