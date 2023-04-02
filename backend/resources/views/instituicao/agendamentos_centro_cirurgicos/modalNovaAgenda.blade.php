<div id="modalNovoAgenda" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="novo_agendamento" action="{{route('instituicao.agendamentoCentroCirurgico.salvarAgendamento')}}" method="post">
                <div class="modal-header">
                    <h4 class="modal-title">Novo agendamento</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="centro_cirurgico_novo" class="control-label">Centro cirúrgico:</label>
                            <input type="text" class="form-control centro_cirurgico_novo" id="centro_cirurgico_novo" name="centro_cirurgico_novo" value="{{$centro_cirurgico->descricao}}" readonly>
                            <input type="hidden" class="form-control" id="centro_cirurgico_novo_id" name="centro_cirurgico_novo_id" value="{{$centro_cirurgico->id}}">
                        </div>
                        <div class="col-md-4 agendar_novo">
                            <label for="sala_cirurgica_novo" class="control-label">Sala:</label>
                            <select class="form-control select2agenda" name="sala_cirurgica_novo" id="sala_cirurgica_novo" style="width: 100%">
                                @foreach ($salas_cirurgicas as $item)
                                    <option value="{{$item->id}}">{{$item->descricao}}</option>
                                @endforeach
                            </select>
                        </div> 
                        <div class="form-group col-md-2">
                            <label for="data_novo" class="control-label">Data:</label>
                            <input type="text" class="form-control" id="data_novo" name="data_novo" value="{{\Carbon\Carbon::parse($dados['data'])->format('d/m/Y')}}" readonly>
                        </div>
                        <div class="form-group col-md-2 clockpicker" data-autoclose="true">
                            <label for="hora_inicio_novo" class="control-label">Hora inicio:</label>
                            <input type="text" class="form-control " id="hora_inicio_novo" name="hora_inicio_novo" readonly value="{{\Carbon\Carbon::parse($hora_inicio)->format('H:i')}}">
                        </div>
                        <div class="form-group col-md-2 clockpicker interditar" data-autoclose="true" style="display: none">
                            <label for="hora_final_novo" class="control-label">Hora final:</label>
                            <input type="text" class="form-control " id="hora_final_novo" name="hora_final_novo" readonly value="{{\Carbon\Carbon::parse($hora_final)->format('H:i')}}">
                        </div>
                        <div class="col-md-3 agendar_novo">
                            <label for="cirurgia_novo" class="control-label">Cirurgia:</label>
                            <select class="form-control select2agenda" name="cirurgia_novo" id="cirurgia_novo" style="width: 100%">
                            </select>
                        </div> 
                        <div class="col-md-3 agendar_novo">
                            <label for="cirurgiao_novo" class="control-label">Cirurgião Principal:</label>
                            <select class="form-control select2agenda" name="cirurgiao_novo" id="cirurgiao_novo" style="width: 100%">
                                @foreach ($prestadores as $item)
                                    <option value="{{$item->id}}">{{$item->nome}}</option>
                                @endforeach
                            </select>
                        </div> 
                        <div class="form-group col-md-3" style="margin-top: 31px">
                            <input type="checkbox" id="interditar_novo" name="interditar_novo" class="filled-in" value="1"/>
                            <label for="interditar_novo">Interditar</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success waves-effect" >Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $(".clockpicker").clockpicker({
            donetext: 'Fechar'
        });

        $(".select2agenda").select2();

        getCirurgias();
    })

    $("#sala_cirurgica_novo").on('change', function() {
        getCirurgias();
    })

    $("#interditar_novo").on('change', function(){
        var interditar = $("#interditar_novo").is(':checked');
        if(interditar){
            $(".agendar_novo").css('display', 'none');
            $(".interditar").css('display', 'block');
        }else{
            $(".agendar_novo").css('display', 'block');
            $(".interditar").css('display', 'none');
        }
    });

    $("#novo_agendamento").on('submit', function(e){
        e.preventDefault();

        var formData = new FormData($(this)[0]);

        $.ajax("{{route('instituicao.agendamentoCentroCirurgico.salvarAgendamento')}}", {
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (result) {
                if(result.tipo == true){
                    getCentroCirurgico();
                    $("#modalNovoAgenda").modal('hide');
                    $.toast({
                        heading: 'Sucesso',
                        text: 'Agendamento cadastrado com sucesso',
                        position: 'top-right',
                        loaderBg:'#ff6849',
                        icon: 'success',
                        hideAfter: 3000,
                        stack: 10
                    });
                }else{
                    $.toast({
                        heading: result.header,
                        text: result.text,
                        position: 'top-right',
                        loaderBg:'#ff6849',
                        icon: result.icon,
                        hideAfter: 3000,
                        stack: 10
                    });
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

    function getCirurgias(){
        var sala_id = $("#sala_cirurgica_novo option:selected").val()
        $.ajax({
            type: "POST",
            data: {sala_id: sala_id, '_token': '{{csrf_token()}}'},
            url: "{{route('instituicao.agendamentoCentroCirurgico.cirurgiasSalas')}}",
            datatype: "json",
            success: function(cirurgias) {
                if(cirurgias.length > 0){
                    var options = $('#cirurgia_novo');
                    options.find('option').remove();
                    $.each(cirurgias, function (key, value) {
                        options.append('<option value='+value.id+'>'+value.descricao+'</option>')
                    });
                }else{
                    var options = $('#cirurgia_novo');
                    options.find('option').remove();
                    $.toast({
                        heading: "Atenção",
                        text: "Sala se encontra sem nunhuma cirúrgia!",
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: "warning",
                        hideAfter: 3000,
                        stack: 10
                    });
                }
            }

        });
    }
</script>