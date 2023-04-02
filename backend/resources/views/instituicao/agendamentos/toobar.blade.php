<div class="row">

    <div class="col-lg-4 col-md-12">
        <div class="card cardDash card-inverse card-success bgAprovado" style="background: #26c6da;">
            <div class="card-body">
                <div class="d-flex">
                    <div class="m-r-20 align-self-center">
                        <h1 class="text-white">{{$status['pendente']}}</h1>
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
                    <div class="col-lg col-md dashPendentes">
                        <h3 class="m-b-0 font-light" style="font-size: 25px; font-weight: bold;">{{$status['agendado']}}</h3>
                        <small style="font-size: 16px;">Agendamentos</small>
                    </div>
                    <div class="col-lg col-md dashEntregues">
                        <h3 class="m-b-0 font-light">{{$status['confirmado']}}</h3>
                        <small>Confirmados</small>
                    </div>

                    <div class="col-lg col-md dashEmAtendimento">
                        <h3 class="m-b-0 font-light">{{$status['em_atendimento']}}</h3><small>Em atendimento</small>
                    </div>

                    <div class="col-lg col-md dashFinalizados">
                        <h3 class="m-b-0 font-light">{{$status['finalizado']}}</h3><small>Finalizados</small>
                    </div>

                    <div class="col-lg col-md dashAusentes">
                        <h3 class="m-b-0 font-light">{{$status['ausente']}}</h3><small>Ausentes</small>
                    </div>
                    
                    <div class="col-md-12 m-b-10"></div>
                </div>
            </div>
        </div>
    </div>
</div>
