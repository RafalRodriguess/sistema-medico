    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="round round-lg align-self-center round-info" style="background: #26c6da"><i class="mdi mdi-account-alert"></i></div>
                        <div class="m-l-10 align-self-center">
                            <h3 class="m-b-0 font-light">{{$status['agendados']}}</h3>
                            <h5 class="text-muted m-b-0">Pacientes Agendados</h5></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="round round-lg align-self-center round-warning" style="background: #009688"><i class="mdi mdi-account-check"></i></div>
                        <div class="m-l-10 align-self-center">
                            <h3 class="m-b-0 font-lgiht">{{$status['confirmados']}}</h3>
                            <h5 class="text-muted m-b-0">Pacientes Confirmados</h5></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="round round-lg align-self-center round-primary"><i class="mdi mdi-checkbox-marked-circle-outline"></i></div>
                        <div class="m-l-10 align-self-center">
                            <h3 class="m-b-0 font-lgiht">{{$status['atendidos']}}</h3>
                            <h5 class="text-muted m-b-0">Pacientes Atendidos</h5></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="round round-lg align-self-center round-danger" style="background: #dbdbdc"><i class="mdi mdi-account-remove"></i></div>
                        <div class="m-l-10 align-self-center">
                            <h3 class="m-b-0 font-lgiht">{{$status['ausentes']}}</h3>
                            <h5 class="text-muted m-b-0">Pacientes Ausentes</h5></div>
                    </div>
                </div>
            </div>
        </div>
    </div>