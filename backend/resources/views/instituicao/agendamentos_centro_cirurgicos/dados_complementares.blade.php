<div class="p-10">
    <input type="hidden" name="in_page_dados_complementares" value="1">
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <h5>Sala Cirurgica</h5>
                    <hr>
                </div>
                <div class="col-md-6">
                    <label class="form-control-label p-0 m-0">
                        Entrada
                    </label>
                    <input type="time" class="form-control"
                    name="sala_cirurgica_entrada" value="{{($agendamento->sala_cirurgica_entrada) ? $agendamento->sala_cirurgica_entrada : ""}}">
                </div>
                <div class="col-md-6">
                    <label class="form-control-label p-0 m-0">
                        Saida
                    </label>
                    <input type="time" class="form-control"
                    name="sala_cirurgica_saida" value="{{($agendamento->sala_cirurgica_saida) ? $agendamento->sala_cirurgica_saida : ""}}">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <h5>Anestesia</h5>
                    <hr>
                </div>
                <div class="col-md-6">
                    <label class="form-control-label p-0 m-0">
                        Inicio
                    </label>
                    <input type="time" class="form-control"
                    name="anestesia_inicio" value="{{($agendamento->anestesia_inicio) ? $agendamento->anestesia_inicio : ""}}">
                </div>
                <div class="col-md-6">
                    <label class="form-control-label p-0 m-0">
                        Fim
                    </label>
                    <input type="time" class="form-control"
                    name="anestesia_fim" value="{{($agendamento->anestesia_fim) ? $agendamento->anestesia_fim : ""}}">
                </div>
            </div>
        </div>
        <div class="col-md-6" style="margin-top: 40px">
            <div class="row">
                <div class="col-md-12">
                    <h5>Cirurgia</h5>
                    <hr>
                </div>
                <div class="col-md-6">
                    <label class="form-control-label p-0 m-0">
                        Inicio
                    </label>
                    <input type="time" class="form-control"
                    name="cirurgia_inicio" value="{{($agendamento->cirurgia_inicio) ? $agendamento->cirurgia_inicio : ""}}">
                </div>
                <div class="col-md-6">
                    <label class="form-control-label p-0 m-0">
                        Fim
                    </label>
                    <input type="time" class="form-control"
                    name="cirurgia_fim" value="{{($agendamento->cirurgia_fim) ? $agendamento->cirurgia_fim : ""}}">
                </div>
            </div>
        </div>
        <div class="col-md-6" style="margin-top: 40px">
            <div class="row">
                <div class="col-md-12">
                    <h5>Limpeza</h5>
                    <hr>
                </div>
                <div class="col-md-6">
                    <label class="form-control-label p-0 m-0">
                        Inicio
                    </label>
                    <input type="time" class="form-control"
                    name="limpeza_inicio" value="{{($agendamento->limpeza_inicio) ? $agendamento->limpeza_inicio : ""}}">
                </div>
                <div class="col-md-6">
                    <label class="form-control-label p-0 m-0">
                        Fim
                    </label>
                    <input type="time" class="form-control"
                    name="limpeza_fim" value="{{($agendamento->limpeza_fim) ? $agendamento->limpeza_fim : ""}}">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $(":input").setMask()
    })
</script>