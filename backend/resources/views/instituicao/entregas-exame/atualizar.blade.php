    <div class="row">
        <div class="form-group col-md-7">
            <label for="instituicao_paciente_id" class="form-label">Paciente</label>
            <span class="form-control d-block disabled">
                {{ $entrega->paciente->nome }}
            </span>
        </div>
        <div class="form-group col-md-5">
            <label for="local_entrega_id" class="form-label">Local de entrega</label>
            <span class="form-control d-block disabled">
                {{ $entrega->localEntrega->descricao }}
            </span>
        </div>
        <div class="form-group col-md-4">
            <label for="setor_exame_id" class="form-label">Setor</label>
            <span class="form-control d-block disabled">
                {{ $entrega->setorExame->descricao }}
            </span>
        </div>
        <div class="form-group col-md-4">
            <label for="status" class="form-label">Situação<span class="text-danger">*</span></label>
            <select name="status" id="status_input" class="form-control" placeholder="Situação">
                @foreach ($statuses as $key => $status)
                    <option value="{{ $key }}" @if ($key == old('status', $entrega->status)) selected="selected" @endif>
                        {{ $status }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-12">
            <label for="observacao" class="form-label">Observações</label>
            <textarea name="observacao" id="observacao" rows="4" class="form-control">{{ old('observacao', $entrega->observacao) }}</textarea>
        </div>
        <div class="form-group col-12 pt-3 mb-3" style="border: 1px dashed rgba(0,0,0,.1);border-width: 1px 0 0 0;">
            <h5 class="mb-3">Procedimentos:</h5>
            <div class="row">
                @foreach ($procedimentos as $procedimento)
                    <div class="col-md-4">
                        <div class="card py-1 px-2">
                            <div>#{{ $procedimento->id }}</div>
                            <div class="flex-1">{{ $procedimento->descricao }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <script>
        window.__modal_ready = function() {
            $('#status_input').select2();
            
            window.__modal_submit = (form) => {
                let data = form.serializeArray();
                $.ajax("{{ route('instituicao.entregas-exame.finalizar-atualizacao', $entrega) }}", {
                    method: 'POST',
                    data: data,
                    data_type: 'json',
                    success: function(response) {

                        $.toast({
                            heading: 'Sucesso',
                            text: 'Situação atualizada com sucesso',
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 3000
                        });
                        // Fechando o modal
                        if (window.__modal_onsubmit) {
                            window.__modal_onsubmit();
                        }
                    },
                    error: function(response) {
                        if (response.responseJSON.errors) {
                            Object.keys(response.responseJSON.errors).forEach(function(key) {
                                $.toast({
                                    heading: 'Erro',
                                    text: response.responseJSON.errors[key][0],
                                    position: 'top-right',
                                    loaderBg: '#ff6849',
                                    icon: 'error',
                                    hideAfter: 9000
                                });

                            });
                        }
                    }
                });
            };
        }
    </script>
