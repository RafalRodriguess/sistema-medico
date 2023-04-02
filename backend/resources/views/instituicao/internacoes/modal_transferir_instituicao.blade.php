<div id="modaltransferirInstituicao" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <span>Transferir de instituição</span>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form>
                @csrf

                <div class="modal-body">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 form-group">
                                <label class="form-control-label p-0 m-0">Internação Id</label>
                                <input type="text" disabled name="id" id="internacao_id" class="form-control" value="{{$internacao->id}}"/>
                            </div>
                            
                            <div class="col-md-2 form-group">
                                <label class="form-control-label p-0 m-0">Paciente Id</label>
                                <input type="text" disabled name="paciente_id" class="form-control" value="{{$internacao->paciente_id}}"/>
                            </div>

                            <div class="col-md form-group">
                                <label class="form-control-label p-0 m-0">Nome Paciente</label>
                                <input type="text" disabled name="paciente_nome" class="form-control" value="{{$internacao->paciente->nome}}"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md form-group">
                                <label class="form-control-label p-0 m-0">Transferir para<span class="text-danger">*</span></label>
                                <select class="form-control select2" name="instituicao_transferencia_id">
                                    <option value="">Selecione a instituição</option>
                                    @foreach ($instituicoes_trasferencia as $item)
                                        <option {{ (old('instituicao_transferencia_id', $internacao->instituicao_transferencia_id) == $item->id) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->descricao }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 form-group">
                                <label class="form-control-label p-0 m-0">data trasnferencia</label>
                                <input type="date" name="data_transferencia" class="form-control" value="{{$internacao->data_transferencia}}"/>
                            </div>

                            <div class="col-md-12 form-group">
                                <label class="form-control-label p-0 m-0">Transferir para<span class="text-danger">*</span></label>
                                <textarea class="form-control" rows='5' name="obs_transferencia">{{ old('obs_transferencia', $internacao->obs_transferencia) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <input type="submit" class="btn btn-success waves-effect waves-light m-r-10" value="Confirmar">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    
    $("form").submit(function(e){
        e.preventDefault()

        var formData = new FormData($(this)[0]);
        var internacao_id = $("#internacao_id").val()
        
        $.ajax("{{ route('instituicao.internacoes.transferirInstituicao', ['internacao' => 'internacao_id']) }}".replace('internacao_id', internacao_id), {
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: function (response) {
                $.toast({
                    heading: response.title,
                    text: response.text,
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: response.icon,
                    hideAfter: 3000,
                    stack: 10
                });
                $('#modaltransferirInstituicao').modal('hide');
            },
            complete: () => {
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader')
            },
            error: function (response) {
                if(response.responseJSON.errors){
                    Object.keys(response.responseJSON.errors).forEach(function(key) {
                        $.toast({
                            heading: 'Erro',
                            text: response.responseJSON.errors[key][0],
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 9000,
                            stack: 10
                        });
                    });
                }
            }
        })
    })
</script>