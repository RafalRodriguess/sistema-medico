<div id="modalRealizaAlta" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <span>Realizar Alta Médica</span>
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

                            <div class="col-md form-group">
                                <label class="form-control-label p-0 m-0">Data internação</label>
                                <input type="datetime-local" disabled name="data_internacao" class="form-control" value="{{$internacao->data_internacao}}"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md form-group">
                                <label class="form-control-label p-0 m-0">Data alta</label>
                                <input type="datetime-local" name="data_alta" class="form-control" value="{{$internacao->data_alta}}"/>
                            </div>

                            <div class="col-md form-group">
                                <label class="form-control-label p-0 m-0">Motivo de alta</label>
                                <select class="form-control p-0 m-0 selectfild2" name="motivo_alta_id" id="motivo_alta_id">
                                    <option value="" selected>Nenhum</option>
                                    @foreach ($motivoAlta as $item)
                                        <option {{ (old('motivo_alta_id') == $item->id) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->descricao_motivo_alta }}</option>
                                    @endforeach
                                </select>                                    
                            </div>

                            <div class="col-md-2 form-group">
                                <label class="form-control-label p-0 m-0">Infecção</label>
                                <select class="form-control p-0 m-0" name="infeccao_alta" id="infeccao_alta">
                                    <option value="0" selected>Não</option>
                                    <option value="1" selected>Sim</option>
                                </select>                                    
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md form-group">
                                <label class="form-control-label p-0 m-0">Declaração de óbito</label>
                                <input type="text" name="declaracao_obito_alta" class="form-control" value="{{$internacao->declaracao_obito_alta}}"/>
                            </div>

                            <div class="col-md form-group">
                                <label class="form-control-label p-0 m-0">Procedimento de alta</label>
                                <select class="form-control p-0 m-0 selectfild2" name="procedimento_alta_id" id="procedimento_alta_id">
                                    <option value="" selected>Nenhum</option>
                                    @foreach ($procedimentos as $item)
                                        <option {{ (old('procedimento_alta_id') == $item->id) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->descricao }}</option>
                                    @endforeach
                                </select>                                    
                            </div>
                            
                            <div class="col-md form-group">
                                <label class="form-control-label p-0 m-0">Especialidade de alta</label>
                                <select class="form-control p-0 m-0 selectfild2" name="especialidade_alta_id" id="especialidade_alta_id">
                                    <option value="" selected>Nenhum</option>
                                    @foreach ($especialidades as $item)
                                        <option {{ (old('especialidade_alta_id') == $item->id) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->descricao }}</option>
                                    @endforeach
                                </select>                                    
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 form-group @if($errors->has('obs_alta')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Observação <span class="text-danger">*</span></label>
                                <textarea rows='4' class="form-control @if($errors->has('obs_alta')) form-control-danger @endif" name="obs_alta" id="obs_alta">{{ old('obs_alta') }}</textarea>
                                @if($errors->has('obs_alta'))
                                    <small class="form-control-feedback">{{ $errors->first('obs_alta') }}</small>
                                @endif
                            </div>
                        </div>                        
                    </div>
                </div>

                <div class="modal-footer">
                    <input type="submit" class="btn btn-success waves-effect waves-light m-r-10" value="Confirmar alta">
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
        
        $.ajax("{{ route('instituicao.internacoes.realizarAlta', ['id' => 'internacao_id']) }}".replace('internacao_id', internacao_id), {
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
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
                if(response.icon == 'success'){
                    
                    window.livewire.emit('refresh');
                    $('#modalRealizaAlta').modal('hide');
                }
                
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