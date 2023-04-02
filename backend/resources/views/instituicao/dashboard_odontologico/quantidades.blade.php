<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="round round-lg align-self-center round-info" style="background: #26c6da"><i class="mdi mdi-calendar"></i></div>
                    <div class="m-l-10 align-self-center">
                        <h3 class="m-b-0 font-light">R$ {{number_format($total_criado+$total_aprovado, 2, ',','.')}}</h3>
                        <h5 class="text-muted m-b-0">{{$total_orcamentos}} Total de orçamentos</h5></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="round round-lg align-self-center round-info" style="background: #26c6da"><i class="mdi mdi-calendar-plus"></i></div>
                    <div class="m-l-10 align-self-center">
                        <h3 class="m-b-0 font-light">R$ {{number_format($total_criado, 2, ',','.')}}</h3>
                        <h5 class="text-muted m-b-0">{{$criados->quantidade}} ({{number_format($criados->quantidade*100/$total_orcamentos, 2)}}%) Orçamentos criados</h5></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="round round-lg align-self-center round-warning" style="background: #009688"><i class="mdi mdi-calendar-range"></i></div>
                    <div class="m-l-10 align-self-center">
                        <h3 class="m-b-0 font-light">R$ {{number_format($aprovados->valor, 2, ',','.')}}</h3>
                        <h5 class="text-muted m-b-0">{{$aprovados->quantidade}} ({{number_format($aprovados->quantidade*100/$total_orcamentos, 2)}}%) Orçamentos aprovados</h5></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="round round-lg align-self-center round-primary"><i class="mdi mdi-calendar-clock"></i></div>
                    <div class="m-l-10 align-self-center">
                        <h3 class="m-b-0 font-lgiht">{{$em_tratamentos->quantidade}}</h3>
                        <h5 class="text-muted m-b-0">Orçamentos em tratamento</h5></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="round round-lg align-self-center round-danger" style="background: #f8ac59"><i class="mdi mdi-calendar-check"></i></div>
                    <div class="m-l-10 align-self-center">
                        <h3 class="m-b-0 font-lgiht">{{$finalizados->quantidade}}</h3>
                        <h5 class="text-muted m-b-0">({{number_format($finalizados->quantidade*100/$total_orcamentos, 2)}}%) Tratamentos concluídos</h5></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="round round-lg align-self-center round-info" style="background: #26c6da"><i class="mdi mdi-calendar-plus"></i></div>
                    <div class="m-l-10 align-self-center">
                        <h3 class="m-b-0 font-light">R$ {{($criados->quantidade > 0) ? number_format($total_criado/($criados->quantidade), 2, ',','.') : number_format($total_criado, 2, ',','.')}}</h3>
                        <h5 class="text-muted m-b-0">Ticket medio orçamentos criados</h5></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="round round-lg align-self-center round-warning" style="background: #009688"><i class="mdi mdi-calendar-range"></i></div>
                    <div class="m-l-10 align-self-center">
                        <h3 class="m-b-0 font-light">R$ {{($aprovados->quantidade > 0) ? number_format($aprovados->valor/($aprovados->quantidade), 2, ',','.') : number_format($aprovados->valor, 2, ',','.')}}</h3>
                        <h5 class="text-muted m-b-0">Ticket medio Orçamentos aprovados</h5></div>
                </div>
            </div>
        </div>
    </div>
</div>