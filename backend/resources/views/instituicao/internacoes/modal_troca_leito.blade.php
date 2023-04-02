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

<div id="modalTocaLeito" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <span>Transferir leito</span>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form>
                @csrf

                <div class="modal-body">
                    
                    <ul class="nav nav-tabs customtab editarTabs" role="tablist">

                        <li class="nav-item trocarLeiro">
                        <a class="nav-link active tab-trocar-leito" data-toggle="tab" href="#trocar-leito" role="tab">
                            <span class="hidden-xs-down">Trocar leito</span>
                        </a>
                        </li>
                
                        <li class="nav-item historico">
                            <a class="nav-link tab-historico" data-toggle="tab" href="#historico" role="tab">
                                <span class="hidden-xs-down">Histórico</span>
                            </a>
                        </li>
                    </ul>
                
                    <div class="tab-content  tabsEditar">
                        <div class="tab-pane p-20 active" id="trocar-leito" role="tabpanel">
                            <div class="trocar-leito">


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
                                        <label class="form-control-label p-0 m-0">Acomodação atual<span class="text-danger">*</span></label>
                                        <select class="form-control" name="acomodacao_id" disabled>
                                            <option value="" selected>Nenhum</option>
                                            @foreach ($acomodacoes as $acomodacao)
                                                <option {{ (old('acomodacao_id', !empty($leito_atual->acomodacao_id) ? $leito_atual->acomodacao_id : null) == $acomodacao->id) ? 'selected' : '' }} value="{{ $acomodacao->id }}">{{ $acomodacao->descricao }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md form-group">
                                        <label class="form-control-label p-0 m-0">Unidade atual<span class="text-danger">*</span></label>
                                        <select class="form-control selectfild2" name="unidade_id" disabled>
                                            <option value="" selected>Nenhum</option>
                                            @foreach ($unidades as $unidade)
                                                <option {{ (old('unidade_id', !empty($leito_atual->unidade_id) ? $leito_atual->unidade_id : null) == $unidade->id) ? 'selected' : '' }} value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label class="form-control-label p-0 m-0">Leito atual</label>
                                        <select class="form-control p-0 m-0 selectfild2" name="leito_id" disabled>
                                            <option value="" selected>Nenhum</option>
                                            @if(!empty($leitos))
                                                @foreach ($leitos as $item)
                                                    <option {{ (old('unidade_id', !empty($leito_atual->leito_id) ? $leito_atual->leito_id : null) == $item->id) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->descricao }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md form-group @if($errors->has('acomodacao_id')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Nova acomodação <span class="text-danger">*</span></label>
                                        <select class="form-control @if($errors->has('acomodacao_id')) form-control-danger @endif" name="acomodacao_id" id="acomodacao_id">
                                            <option value="" selected>Nenhum</option>
                                            @foreach ($acomodacoes as $acomodacao)
                                                <option value="{{ $acomodacao->id }}">{{ $acomodacao->descricao }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('acomodacao_id'))
                                            <small class="form-control-feedback">{{ $errors->first('acomodacao_id') }}</small>
                                        @endif
                                    </div>
                
                                    <div class="col-md form-group @if($errors->has('unidade_id')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Nova unidade <span class="text-danger">*</span></label>
                                        <select class="form-control selectfild2 @if($errors->has('unidade_id')) form-control-danger @endif" name="unidade_id" id="unidade_id">
                                            <option value="" selected>Nenhum</option>
                                            @foreach ($unidades as $unidade)
                                                <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('unidade_id'))
                                            <small class="form-control-feedback">{{ $errors->first('unidade_id') }}</small>
                                        @endif
                                    </div>
                
                                    <div class="col-md-4 form-group @if($errors->has('leito_id')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Novo Leito</label>
                                        <select class="form-control p-0 m-0 selectfild2" name="leito_id" id="leito_id">
                                            <option value="" selected>Nenhum</option>
                                        </select>
                                        @if($errors->has('leito_id'))
                                            <small class="form-control-feedback">{{ $errors->first('leito_id') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div> 
                        </div>

                        <div class="tab-pane p-20" id="historico" role="tabpanel">
                            <ul class="timeline" style="z-index: 0">
                                @if(!empty($internacoesLeitos))
                                    @foreach($internacoesLeitos as $item)
                                        <li class="">
                                            {{$item->created_at->format("d/m/Y H:i")}} <b><i class="mdi mdi-chevron-right"></i> Unidade:</b> {{$item->unidade->nome}} <b><i class="mdi mdi-chevron-right"></i> leito:</b> {{$item->leito->descricao}}
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
        
        $.ajax("{{ route('instituicao.internacoes.trocaLeito', ['id' => 'internacao_id']) }}".replace('internacao_id', internacao_id), {
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
                $('#modalTocaLeito').modal('hide');
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

    $('#unidade_id').on('change', function(){
        getLeitos()
    })

    function getLeitos(){
        if($('#unidade_id').val() == ''){
            $('#leito_id').find('option').filter(':not([value=""])').remove();
        }else{
            $('#leito_id').find('option').filter(':not([value=""])').remove();
            id = $('#unidade_id').val();
            
            $.ajax({
                url: "{{route('instituicao.internacoes.getLeitos')}}",
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    unidade_id: id
                },
                success: function(retorno){
                    for (i = 0; i < retorno.length; i++) {
                        $('#leito_id').append("<option value = "+ retorno[i]['id'] +" >" + retorno[i]['descricao'] + "</option>");
                    }
                }
            })
        
        }
    }
</script>