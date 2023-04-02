<style>
    ul.timeline {
        list-style-type: none;
        position: relative;
    }
    ul.timeline:before {
        content: ' ';
        background: #d4d9df;
        display: inline-block;
        position: absolute;
        left: 29px;
        width: 2px;
        height: 100%;
        z-index: 400;
    }
    ul.timeline > li {
        margin: 20px 0;
        padding-left: 45px;
    }
    ul.timeline > li:before {
        content: ' ';
        background: white;
        display: inline-block;
        position: absolute;
        border-radius: 50%;
        border: 3px solid #22c0e8;
        left: 20px;
        width: 20px;
        height: 20px;
        z-index: 400;
    }
</style>

<div id="modalTocaMedico" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <span>Transferir Médico</span>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form>
                @csrf
                <div class="modal-body">
                    
                    <ul class="nav nav-tabs customtab editarTabs" role="tablist">
                        <li class="nav-item trocarMedico">
                            <a class="nav-link active tab-trocar-medico" data-toggle="tab" href="#trocar-medico" role="tab">
                                <span class="hidden-xs-down">Trocar médico</span>
                            </a>
                        </li>
                
                        <li class="nav-item historicoMedico">
                            <a class="nav-link tab-historico-medico" data-toggle="tab" href="#historico_medico" role="tab">
                                <span class="hidden-xs-down">Histórico</span>
                            </a>
                        </li>
                    </ul>
                
                    <div class="tab-content  tabsEditar">
                        <div class="tab-pane p-20 active" id="trocar-medico" role="tabpanel">
                            <div class="trocar-medico">
                                <div class="row">
                                    <div class="col-md-2 form-group">
                                        <label class="form-control-label p-0 m-0">Internação Id</label>
                                        <input type="text" readonly name="internacao_id" id="internacao_id" class="form-control" value="{{$internacao->id}}"/>
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
                                        <label class="form-control-label p-0 m-0">Médico atual<span class="text-danger">*</span></label>
                                        <select class="form-control" name="medico_id" disabled>
                                            <option value="" selected>Nenhum</option>
                                            @foreach ($medicos as $item)
                                                <option {{ (old('medico_id', !empty($medico_atual->medico_id) ? $medico_atual->medico_id : null) == $item->id) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md form-group @if($errors->has('medico_id')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Novo médico <span class="text-danger">*</span></label>
                                        <select class="form-control @if($errors->has('medico_id')) form-control-danger @endif" name="medico_id" id="medico_id">
                                            <option value="" selected>Nenhum</option>
                                            @foreach ($medicos as $item)
                                                <option value="{{ $item->id }}">{{ $item->id }} - {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('medico_id'))
                                            <small class="form-control-feedback">{{ $errors->first('medico_id') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane p-20" id="historico_medico" role="tabpanel">
                            <ul class="timeline" style="z-index: 0">
                                @if(!empty($internacoesMedicoss))
                                    @foreach($internacoesMedicoss as $item)
                                        <li class="">
                                            {{$item->created_at->format("d/m/Y H:i")}} <b><i class="mdi mdi-chevron-right"></i> Médico:</b> {{$item->medico->nome}}
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
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
        
        $.ajax("{{ route('instituicao.internacoes.trocaMedico', ['id' => 'internacao_id']) }}".replace('internacao_id', internacao_id), {
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
                $('#modalTocaMedico').modal('hide');
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