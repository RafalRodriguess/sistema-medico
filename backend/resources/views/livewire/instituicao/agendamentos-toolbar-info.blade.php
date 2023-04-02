<div class="row">

    <div class="col-lg-4 col-md-12">
        <div class="card cardDash card-inverse card-success bgAprovado" style="background: #26c6da;">
            <div class="card-body">
                <div class="d-flex">
                    <div class="m-r-20 align-self-center">
                        <h1 class="text-white">{{$qtdAgendamentos->pendente}}</h1>
                    </div>
                    <div>
                        <h3 class="card-title">Agendamentos Pendentes</h3>
                        <h6 class="card-subtitle">Aguardando confirmação</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8 col-md-12">
        <div class="card cardDash ">
            <div class="card-body">
                <div class="row text-center ">
                    <div class="col-lg-3 col-md-3 dashPendentes">
                        <h3 class="m-b-0 font-light">{{$qtdAgendamentos->agendado}}</h3><small>Agendados</small>
                    </div>
                    <div class="col-lg-3 col-md-3 dashEntregues">
                        <h3 class="m-b-0 font-light">{{$qtdAgendamentos->confirmado}}</h3><small>Confirmados</small>
                    </div>

                    <div class="col-lg-3 col-md-3 dashCancelados">
                        <h3 class="m-b-0 font-light">{{$qtdAgendamentos->em_atendimento}}</h3><small>Em atendimento</small>
                    </div>

                    <div class="col-lg-3 col-md-3 dashCancelados">
                        <h3 class="m-b-0 font-light">{{$qtdAgendamentos->finalizado}}</h3><small>Finalizados</small>
                    </div>
                    
                    <div class="col-md-12 m-b-10"></div>
                </div>
            </div>
        </div>
    </div>
    @can('habilidade_instituicao_sessao', 'visualizar_saldo_pagarme')

    {{-- <div class="col-lg-3 col-md-12">
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
    </div> --}}
    @endcan
</div>


@push('script');
    <script>
        $('#data').on('change', function(){
            console.log($("#data").val())
            @this.call('render');
            @this.call('updatingData');
        })

        function callRenderPage(){
            @this.call('render');
            @this.call('updatingData');
        }

    </script>
@endpush