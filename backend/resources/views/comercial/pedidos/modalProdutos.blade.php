<div class="modal inmodal" id="modalProdutos" tabindex="-1" role="dialog" aria-hidden="true">
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
          <div class="row">
            <div class="col-md-12">

              Produtos:
            </div>
            <br>
            <br>
          </div>
          <?php $totalProdutos = 0 ?>

          @foreach($pedido->produtos as $produto)
          <?php $totalProdutos += $produto->valor*$produto->quantidade ?>
          <div class="row">
            <div class="col-md-2" align="center">
              <img src="
                    @if ($produto->imagem)
                        {{ \Storage::cloud()->url($produto->imagem) }}
                    @endif
                    " alt="" style="height: 50px;">
            </div>
            <div class="col-md-10">
              {{$produto->nome}} <br>
              <span style="color:#0baabe;">
                {{$produto->quantidade}}X R${{valor($produto->valor)}}
              </span>
              <div class="row">
                <div class="col-md-12">
                  @foreach($produto->perguntas as $pergunta)
                  @if($pergunta->tipo_pergunta == 'Texto')
                  <div>
                    <small><b>{{$pergunta->texto_pergunta}}</b></small> <br>
                    <small>R: {{$pergunta->texto_resposta}}</small>
                    <hr class="linhaSeparador">
                  </div>
                  @endif
                  @endforeach

                  @foreach($produto->perguntas as $pergunta)
                  @if($pergunta->tipo_pergunta == 'Escolha Simples')
                  <div>
                    <small class="info-left"><b>{{$pergunta->texto_pergunta}}: </b>{{$pergunta->texto_resposta}}</small>
                    <small class="info-right"><b>R${{valor($pergunta->valor)}}</b></small>
                    <hr class="linhaSeparador">
                  </div>
                  @endif
                  @endforeach

                  @foreach($produto->perguntas as $pergunta)
                  @if($pergunta->tipo_pergunta == 'Escolha Multipla')
                  <div>
                    <small class="info-left"><b>{{$pergunta->texto_pergunta}}: </b>{{$pergunta->texto_resposta}}</small>
                    <small class="info-right"><b>R${{valor($pergunta->valor)}}</b></small>
                    <hr class="linhaSeparador">
                  </div>
                  @endif
                  @endforeach

                  @foreach($produto->perguntas as $pergunta)
                  @if($pergunta->tipo_pergunta == 'Contador')
                  <div>
                    <small class="info-left"><b>{{$pergunta->texto_pergunta}}: </b>{{$pergunta->quantidade.'X '.$pergunta->texto_resposta}}</small>
                    <small class="info-right"><b>R${{valor($pergunta->valor*$pergunta->quantidade)}}</b></small>
                    <hr class="linhaSeparador">
                  </div>
                  @endif
                  @endforeach


                </div>
              </div>
            </div>
          </div>
          <hr>
          @endforeach

          <div class="row">
            <div class="col-md-12">
              Detalhes do pedido:


            </div>

            <div class="col-md-6">
              <small> {{ date("d/m/Y", strtotime($pedido->created_at)) }} às {{ date("H:i:s", strtotime($pedido->created_at)) }}</small>

              <br>
              <b> {{ $endereco->nome }} </b>
              <br>
              <small> {{ $endereco->cpf }} </small>
              <br>
              <small>
                {{ $endereco->rua.' '.$endereco->numero.', '.$endereco->bairro.' - '.$endereco->cidade.' '.$endereco->estado }}
              </small>
            </div>

            <div class="col-md-6">

              <b> Valores </b>
              <small>
                <br>
                <span class="valores-left"> Produtos:</span> <span class="valores-right">R${{valor($totalProdutos)}}</span>
                <br>
                <span class="valores-left"> Taxa de Entrega:</span> <span class="valores-right">R${{valor($pedido->valor_entrega)}}</span>

                @if($pedido->valor_parcela > 0 && $pedido->free_parcela < $pedido->parcelas)
                  <br>
                  <span class="valores-left"> Juros parcelamento:</span> <span class="valores-right">{{valor($pedido->valor_parcela)}}%/a.m</span>
                  @endif
              </small>
              <hr>
              <span class="valores-left"> Valor Total:</span> <span class="valores-right">R${{valor($pedido->valor_total)}}</span>
            </div>

          </div>

          <div class="row">
            <div class="col-md-12" align="center">
              <hr>


              <?php

              $btns = array(

                'aprovar' => '<button class="btn btnStatus waves-effect waves-light changeStatus btnAprovar" data-acao="aprovar" type="button"><span class="btn-label"><i class="'.App\Pedido::iconesStatus('aprovado').'"></i></span>Aprovar Pedido</button>',

                'cancelar' => ' <button class="btn btnStatus waves-effect waves-light changeStatus btnCancelar" data-acao="cancelar" type="button"><span class="btn-label"><i class="'.App\Pedido::iconesStatus('cancelado').'"></i></span>Cancelar Pedido</button>',

                'enviar' => '<button class="btn btnStatus waves-effect waves-light changeStatus btnEnviar" data-acao="enviar" type="button"><span class="btn-label"><i class="'.App\Pedido::iconesStatus('enviado').'"></i></span>Enviar Pedido</button>',

                'entregar' => '<button class="btn btnStatus waves-effect waves-light changeStatus btnEntregar" data-acao="entregar" type="button"><span class="btn-label"><i class="'.App\Pedido::iconesStatus('entregue').'"></i></span>Entregar Pedido</button>',

                'disponibilizar' => '<button class="btn btnStatus waves-effect waves-light changeStatus btnDisponivel" data-acao="disponibilizar" type="button"><span class="btn-label"><i class="'.App\Pedido::iconesStatus('disponivel').'"></i></span>Disponível para retirada</button>',

              );

              ?>

              <?php

              switch ($pedido->status_pedido) {
                case 'pendente':
                  echo $btns['aprovar'];
                  echo $btns['cancelar'];
                  break;
                case 'aprovado':
                  if($pedido->forma_entrega == 'entrega')
                  echo $btns['enviar'];
                  if($pedido->forma_entrega == 'retirada')
                  echo $btns['disponibilizar'];
                  echo $btns['cancelar'];
                  break;
                case 'disponivel':
                  echo $btns['cancelar'];
                  if(!$pedido->entrega_comercial)
                  echo $btns['entregar'];
                  break;
                case 'entregue':
                  echo $btns['cancelar'];
                  break;
                case 'enviado':
                  echo $btns['cancelar'];
                  if(!$pedido->entrega_comercial)
                  echo $btns['entregar'];
                  break;
              }

              ?>

              @if($pedido->status_pedido == 'disponivel' || $pedido->status_pedido == 'enviado' )


              <br>
              @if($pedido->entrega_comercial && !$pedido->entrega_cliente && !$pedido->entrega_sistema)
              <br>
                Confirmado a entrega pelo comercial em {{$pedido->entrega_comercial}}
              <br><br>
              @endif

              <div class="alert alert-warning" role="alert">
                <small>
                  O status do pedido será alterado para <strong> ENTREGUE </strong> apenas quando o cliente confirmar a entrega ou após 24h da confirmação do comercial
                </small>
              </div>

              @endif

            </div>
          </div>


        </div>


      </div>

      <div class="modal-footer">
        <button type="button" style='margin:0;' class="btn btn-secondary" data-dismiss="modal">Voltar</button>
      </div>
    </div>
  </div>
</div>

<?php
function valor($number)
{
  return number_format($number, 2, ',', ' ');
}
?>

<style>
  .linhaSeparador {
    margin-top: 0rem;
    margin-bottom: 0rem;
  }

  .info-right {
    text-align: right;
    width: 20%;
    color: #0baabe;
    display: inline-block;
  }

  .info-left {
    text-align: left;
    width: 79%;
    display: inline-block;
  }

  .valores-left {
    text-align: left;
    width: 65%;
    display: inline-block;

  }

  .valores-right {
    text-align: right;
    width: 30%;
    display: inline-block;
    font-weight: 600;
  }


  .btnStatus:hover {
    color: white;

  }

  .btnStatus {
    color: white;
    margin: 5px !important;
  }
</style>




<style>
  .btnAprovar {
    background-color: <?= App\Pedido::coresStatus('aprovado') ?>;
  }

  .btnCancelar {
    background-color: <?= App\Pedido::coresStatus('cancelado') ?>;
  }

  .btnEnviar {
    background-color: <?= App\Pedido::coresStatus('enviado') ?>;
  }

  .btnEntregar {
    background-color: <?= App\Pedido::coresStatus('entregue') ?>;
  }

  .btnDisponivel {
    background-color: <?= App\Pedido::coresStatus('disponivel') ?>;
  }
</style>


<script>
  $('.changeStatus').click(function() {

    var acao = $(this).data('acao');
    var mensagens = {
      aprovar: 'Certeza que deseja aprovar este pedido?',
      cancelar: 'Certeza que deseja cancelar este pedido? Após a confirmação o valor do pedido será estornado ao cliente.',
      enviar: 'Confirmar envio deste pedido?',
      entregar: 'Confirmar entrega deste pedido?',
      disponibilizar: 'Confirmar disponibilidade? Após a confirmação o cliente poderá retirar o pedido no local escolhido.',
    };

    Swal.fire({
      title: "Atenção!",
      text: mensagens[acao],
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Sim, confirmar!",
      cancelButtonText: "Não, cancelar!",
    }).then(function(result) {
      if (result.value) {

        $.ajax({
          url: '{{route("comercial.altera_status", [$pedido])}}',
          method: 'POST',
          dataType: 'json',
          data: {
            acao: acao,
            '_token': '{{csrf_token()}}'
          },
          beforeSend: function() {
            $('#loading').removeClass('loading-off');
          },
          success: function(response) {
            $('#loading').addClass('loading-off');
            $('#modalProdutos').modal('toggle');
            $('#updateLivPedidos').click();
          },
          error: function(response) {
            $('#loading').addClass('loading-off');
          }
        })

      }
    });

  })
</script>