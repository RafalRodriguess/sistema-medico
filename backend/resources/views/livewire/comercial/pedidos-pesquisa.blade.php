<div class="card-body">

    <form action="javascript:void(0)">

        <div class="row" wire:poll.30000ms>

            <div class="col-lg-4 col-md-12">
                <div class="card cardDash card-inverse card-success bgAprovado">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="m-r-20 align-self-center">
                                <h1 class="text-white">{{$numerosPedidos->aprovado}}</h1>
                            </div>
                            <div>
                                <h3 class="card-title">Pedidos Aprovados</h3>
                                <h6 class="card-subtitle">Aguardando Disponibilidade/Envio</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-12">
                <div class="card cardDash ">
                    <div class="card-body">
                        <div class="row text-center ">
                            <div class="col-lg-4 col-md-4 dashPendentes">
                                <h3 class="m-b-0 font-light">{{$numerosPedidos->pendente}}</h3><small>Pendentes</small>
                            </div>
                            <div class="col-lg-4 col-md-4 dashCancelados">
                                <h3 class="m-b-0 font-light">{{$numerosPedidos->cancelado}}</h3><small>Cancelados</small>
                            </div>
                            <div class="col-lg-4 col-md-4 dashEntregues">
                                <h3 class="m-b-0 font-light">{{$numerosPedidos->entregue}}</h3><small>Entregues</small>
                            </div>
                            <div class="col-md-12 m-b-10"></div>
                        </div>
                    </div>
                </div>
            </div>
            @can('habilidade_comercial_sessao', 'visualizar_saldo_pagarme')

            <div class="col-lg-3 col-md-12">
                <div class="card cardDash">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="round round-lg align-self-center round-primary"><i class="ti-wallet"></i></div>
                            <div class="m-l-10 align-self-center">
                                <h5 class="text-muted m-b-0">Saldo</h5>
                                <h3 class="m-b-0 font-light card-title">R${{centavosParaReal($saldo)}}</h3>
                            <small class="m-b-0 font-light ">A receber: R${{centavosParaReal($saldo_a_receber)}}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por cliente ou id...">
                </div>
            </div>
            <div class="col-md-4">

                <div class="form-group" style="margin-bottom: 0px !important;">
                    <select name="status_pedido" class="form-control" wire:model="status">
                        <option value="0">Todos Status</option>
                        <option value="pendente">pendente</option>
                        <option value="aprovado">aprovado</option>
                        <option value="disponivel">disponivel</option>
                        <option value="enviado">enviado</option>
                        <option value="cancelado">cancelado</option>
                        <option value="entregue">entregue</option>
                    </select>
                </div>
            </div>
            <!-- <div class="col-md-1">
                <button id="updateLivPedidos" wire:click="mount" class="btn btn-primary">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div> -->
        </div>
        <div class="row" align="right">
            <div class="col-md-12">
                @if($unreadMessages)
                <a wire:click="getAll" href="javascript:void(0)">
                    <small>
                        <br>
                        Todos pedidos
                    </small>
                </a>
                @else
                <a wire:click="unreadMessages" href="javascript:void(0)">
                    <small>
                        <br>
                        Mensagens nao lidas
                    </small>
                </a>
                @endif
            </div>
        </div>
    </form>

    <hr>

    <table class="tablesaw table-bordered table-hover table" wire:loading.remove wire:target="unreadMessages, getAll, pesquisa, status">
        <thead>
            <tr>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Data/Hora</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Cliente</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Entrega</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Pedido</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Pagamento</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Status</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedidos as $pedido)
            <tr>
                <td class="title"><a href="javascript:void(0)">{{ $pedido->id }}</a></td>

                <td class="dateTimePedido">
                    <b>

                        <?php
                        $start_date = new DateTime($pedido->created_at);
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
                            echo date("d/m", strtotime($pedido->created_at));
                            echo '<br>';
                            echo date("h:i:s", strtotime($pedido->created_at));
                        }

                        ?>

                    </b>
                </td>

                <td>
                    {{ $pedido->nome }}
                </td>

                <td>
                    <b class="bold">
                        @if ($pedido->forma_entrega === 'entrega') Envio ao Cliente <br>
                        @elseif ($pedido->forma_entrega === 'retirada') Retirada no estabelecimento <br> @endif
                    </b>
                    <small>{{ $pedido->rua.' '.$pedido->numero.', '.$pedido->bairro.' - '.$pedido->cidade }}</small>
                </td>

                <td>
                    R${{$pedido->valor_total}}
                    <br>
                    <small> {{count($pedido->produtos)}} itens</small>
                </td>

                <td class="statusPagamento">
                    @if($pedido->forma_pagamento == 'cartao_entrega')
                    Cartão na entrega
                    @endif

                    @if($pedido->forma_pagamento == 'dinheiro')
                    Dinheiro na entrega
                    @endif

                    @if($pedido->forma_pagamento == 'cartao_credito')
                    {{ $pedido->status_pagamento }}
                    <br>
                    <small style="text-transform: lowercase;">(Pagamento online)</small>
                    @endif
                </td>

                <td style="border-right: solid 8px {{ App\Pedido::coresStatus($pedido->status_pedido) }};text-transform:uppercase" class="text-left">
                    <i class="{{ App\Pedido::iconesStatus($pedido->status_pedido) }}"></i> {{ $pedido->status_pedido }}


                    @if(

                    ($pedido->status_pedido == 'enviado' || $pedido->status_pedido == 'disponivel')
                    && $pedido->entrega_comercial
                    && !$pedido->entrega_cliente
                    && !$pedido->entrega_sistema
                    )
                    <i class="fas fa-exclamation-circle" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Entrega confirmada pelo Comercial"></i>
                    @endif


                </td>

                <td>

                    <a>
                        <button onclick="modalProdutos({{$pedido->id}})" type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Detalhes do pedido">
                            <i class="fa fa-file-alt"></i>
                        </button>
                    </a>

                    <a>
                        <button onclick="modalMensagens({{$pedido->id}})" type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Mensagens">
                            <i class="fa fa-envelope"></i>
                        </button>
                        @if(count($pedido->mensagem) > 0)
                        <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                        @endif
                    </a>


                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            {{-- <tr>
            <td colspan="5">
                {{ $pedidos->links() }}
            </td>
            </tr> --}}
        </tfoot>
    </table>
    <div style="float: right">
        {{ $pedidos->links() }}
    </div>
    <div>
        <div wire:loading style="width:100%" wire:target="unreadMessages, getAll, pesquisa, status">
            <div class="container" align="center">
                <div class="row">
                <div class="col-md-4"></div>
                    <div class="col-md-4">
                        Carregando...
                        <br>
                        <img width="70" src="{{ asset('material/assets/images/spinner.gif') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="sectionModal"></div>

@push('scripts')

<script>
    function modalProdutos(pedido) {

        var url = "{{ route('comercial.get_modal_produtos') }}";
        var data = {
            pedido: pedido,
            '_token': '{{csrf_token()}}'
        };
        var modal = 'modalProdutos';

        $('#loading').removeClass('loading-off');
        $('#sectionModal').load(url, data, function(resposta, status) {
            $('#' + modal).modal();
            $('#loading').addClass('loading-off');
        });


    }
</script>



<script>
    function modalMensagens(pedido) {

        var url = "{{ route('comercial.get_modal_mensagens') }}";
        var data = {
            pedido: pedido,
            '_token': '{{csrf_token()}}'
        };
        var modal = 'modalMensagens';

        $('#loading').removeClass('loading-off');
        $('#sectionModal').load(url, data, function(resposta, status) {
            $('#' + modal).modal();
            $('#loading').addClass('loading-off');
        });


    }
</script>

@endpush

<style>
    .tablesaw {
        font-size: 15px !important;
    }

    .dateTimePedido {
        text-align: center !important;
        color: #1c81d9;
        font-weight: 600;
        font-size: 13px;
    }

    .statusPagamento {
        text-align: center !important;
        text-transform: uppercase;
    }

    .cardDash {
        height: 100px;
    }

    .bold {
        font-weight: 600;
    }

    .dashPendentes {
        border-bottom: solid 6px <?= App\Pedido::coresStatus('pendente') ?>;
    }

    .dashCancelados {
        border-bottom: solid 6px <?= App\Pedido::coresStatus('cancelado') ?>;
    }

    .dashEntregues {
        border-bottom: solid 6px <?= App\Pedido::coresStatus('entregue') ?>;
    }

    .notify {
        position: relative;
        top: -17px;
        right: 2px;
    }

    .text_red{
        color: red
    }
</style>


@push('scripts')
<script type="text/javascript">
document.addEventListener('livewire:update', () => {
    $('[data-toggle="tooltip"]').tooltip()
});
</script>
@endpush
