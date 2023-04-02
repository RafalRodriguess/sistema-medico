<div id="atendimento-modal" class="card">
        <input type="hidden" name="senhas_triagem_id" value="{{ $senha->id }}">
        <div class="modal-header">
            <h3 class="modal-title">Iniciar atendimento</h4>
                <button type="button" class="close close-button" aria-hidden="true">×</button>
        </div>
        <div class="atendimento-modal-body">
            <div class="row col-12 px-0 pt-2 m-0">
                <div class="col-12 p-0">
                    <div class="row col-12 px-0 pt-2 m-0">
                        <div class="form-group col-md-6">
                            <label for="paciente-select" class="control-label">Paciente </label>
                            <span class="form-control d-flex">
                                {{ $atendimento_urgencia->paciente->nome }}
                            </span>
                        </div>
                    </div>
                    <div class="col-12 form-group">
                        <hr class="m-0">
                    </div>
                </div>

                <div class="form-group col-md-4">
                    <label for="origem-select" class="control-label">Origem </label>
                    <span class="form-control d-flex">{{ $atendimento_urgencia->origem->descricao }}</span>
                </div>
                <div class="form-group col-md-3">
                    <label for="data_atendimento" class="control-label">Data </label>
                    <span class="form-control d-flex">
                        {{ $atendimento_urgencia->data }}
                    </span>
                </div>
                <div class="form-group col-md-3">
                    <label for="hora_atendimento" class="control-label">Hora <span
                            class="text-danger">*</span></label>
                    <span class="form-control d-flex">
                        {{ $atendimento_urgencia->hora }}
                    </span>
                </div>

                <div class="form-group
                        col-md-2">
                    <label for="data_atendimento" class="control-label">Senha </label>
                    <input type="text" class="form-control" value="{{ $senha->valor }}" disabled>
                </div>


                <div class="form-group col-md-4">
                    <label for="local-procedencia-select" class="control-label">Local de procedência </label>
                    <span class="form-control d-flex">
                        {{ $atendimento_urgencia->procedencia ? $atendimento_urgencia->procedencia->descricao : '' }}
                    </span>
                </div>
                <div class="form-group col-md-4">
                    <label for="destino-select" class="control-label">Destino</label>
                    <span class="form-control d-flex">
                        {{ $atendimento_urgencia->destino ? $atendimento_urgencia->destino->descricao : '' }}
                    </span>
                </div>
                <div class="form-group col-md-4">
                    <label for="medico-select" class="control-label">Prestador </label>
                    <span class="form-control d-flex">
                        {{ $atendimento_urgencia->prestador->nome }}
                    </span>
                </div>

                <div class="form-group col-md-4">
                    <label for="especialidades-select" class="control-label">Especialidade </label>
                    <span class="form-control d-flex">
                        @if ($atendimento_urgencia->especialidade)
                                {{ $atendimento_urgencia->especialidade->descricao }}
                        @endif
                    </span>
                </div>
                <div class="form-group col-md-4">
                    <label for="carater-atendimento-select" class="control-label">Caráter de atendimento  </label>
                    <span class="form-control d-flex">
                        @if ($atendimento_urgencia->caraterAtendimento)
                            {{ $atendimento_urgencia->caraterAtendimento->nome }}
                        @endif
                    </span>
                </div>
                <div class="form-group col-md-2">
                    <label for="cid-input" class="control-label">CID</label>
                    <input id="cid-input" type="text" class="form-control" name="cid"
                        value="{{ $atendimento_urgencia->cid }}">
                </div>

                <div class="form-group col-md-9">
                    <label for="observacao-input" class="control-label">Observações </label>
                    <textarea id="observacao-input" type="text" rows="6" class="form-control" name="observacoes">
                        {{ $atendimento_urgencia->observacoes }}
                    </textarea>
                </div>


                <div class="row col-12">
                    <div class="col-12 form-group">
                        <hr class="m-0">
                    </div>
                    <div class="col-12">
                        <div id="vincular-carteirinha" class="row">
                            <div class="form-group col-md-6">
                                <label for="carteirinha_id" class="control-label">Carteirinha </label>
                                <span class="form-control d-flex">
                                    @if ($atendimento_urgencia->carteirinha)
                                        {{ $atendimento_urgencia->carteirinha->carteirinha }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group col-12 p-0">
                    <div class="col-12 form-group m-0">
                        <hr class="mt-0">
                        <h4 class="mb-3">Procedimentos:</h5>
                    </div>
                    <div id="container-procedimentos" class="col-12 p-0">
                        @php
                            $count = 0;
                        @endphp
                        @if (count($atendimento_urgencia->procedimentosAtendimentoUrgencia) > 0)
                            @foreach ($atendimento_urgencia->procedimentosAtendimentoUrgencia as $procedimento_atendimento)
                                @php
                                    $count++;
                                @endphp
                                <div class="col-md-12 item-convenio-procedimento" el-id="{{$count}}">
                                    <div class="row">
                                        <div class="form-group dados_parcela col-md-4">
                                            <label class="form-control-label">Convênio </label>
                                            <span class="form-control d-flex">{{ $procedimento_atendimento->convenio->nome }}</span>
                                        </div>
                                        <div class="form-group col-md-4 pr-0">
                                            <label class="form-control-label">Procedimento </label>
                                            <span class="form-control d-flex">
                                                {{ $procedimento_atendimento->procedimento->descricao }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer text-right">
            <button type="button" class="btn btn-secondary modal-cancelar">Voltar</button>
        </div>
</div>
<script>
    window.__modal_ready = function() {
        $('#atendimento-modal').on('click', '.modal-cancelar', window.closeGenericModal);
    };
</script>