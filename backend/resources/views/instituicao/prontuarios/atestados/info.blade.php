
<div class="row">
    <div class="form-group col-md-4">
        {{-- <label class="form-control-label">Modelos</label> --}}
        <select name="modelo_atestado" id="modelo_atestado" class="form-control select2Atestado" style="width: 100%">
            <option value="">Selecione um modelo</option>
            @foreach ($modeloAtestado as $item)
                <option value="{{$item['id']}}">{{$item['descricao']}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-8">
        <button type="button" class="btn btn-info waves-effect waves-light m-r-10 historico-atestado-button" style="float: right"><i
            class="fas fa-history"></i>
        Historico</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 salvar-atestado-button" style="float: right"><i
            class="far fa-save"></i>
        Salvar</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 imprimir-atestado" style="float: right"><i
            class="fas fa-print"></i>
        Salvar e imprimir</button>
        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10 novo-atestado-button" style="float: right"><i
            class="fas fa-plus"></i>
        Novo</button>
    </div>
</div>
<hr>
<div class="row">

    <div class="form-atestado col-md-12">
        <form id="formAtestado" method="post" enctype="multipart/form-data">
            @csrf
            @include('instituicao.prontuarios.atestados.form')
        </form>
    </div>

    
</div>
<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 salvar-atestado-button" style="float: right"><i
            class="far fa-save"></i>
        Salvar</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 imprimir-atestado" style="float: right"><i
            class="fas fa-print"></i>
        Salvar e imprimir</button>
    </div>
</div>

<div class="atestado-historico">
    @include('instituicao.prontuarios.atestados.historico')
</div>

<script>
    var reutilizarAtestado = false;
    var atestadoImprimir = false;

    $(document).ready(function(){
        $(".select2Atestado").select2();

        // var teclaTime; //timer identifier
        // var teclaIntervalo = 1000; //time in ms, 1 second for example

        // $('#atestado').keyup(function() {
        //     clearTimeout(teclaTime);
        //     if ($('#atestado').val) {
        //         teclaTime = setTimeout(parouDeDigitar, teclaIntervalo);
        //     }
        // });
        
        // function parouDeDigitar(){
        //     salvarAtestadoAuto();
        // }
    })

    $('#atestado').bind('input propertychange', 'textarea', function(){
        addIcon("tab-atestado")
    })

    $("#modelo_atestado").on('change', function(){
        if($("#modelo_atestado").val() != ""){
            var modelo_id = $("#modelo_atestado").val();

            $.ajax({
                url: "{{route('agendamento.atestado.modeloAtestado', ['modelo' => 'modelo_id'])}}".replace('modelo_id', modelo_id),
                type: 'get',
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function(result) {
                    $('.summernoteAtestado').summernote('code', '');
                    $('.summernoteAtestado').summernote('code', result.texto);
                    addIcon("tab-atestado")
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader') 
                }
            });
        }
    })

    $(".historico-atestado-button").on('click', function(e){
        $(".atestado-historico").find('#modalHistoricoAtestado').modal('show')
    })

    $(".novo-atestado-button").on('click', function(e){
        e.preventDefault()
        Swal.fire({
            title: "Novo!",
            text: 'Deseja criar novo o atestado ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $("#atestado_id").val('')
                $('.summernoteAtestado').summernote('code', '');
                removeIcon("tab-atestado")
                // $('#compartilhar_atestado').prop('checked', false);
            }
        })
    })

    $(".salvar-atestado-button").on('click', function(e){
        e.preventDefault()
        if($("#atestado_id").val()){
            Swal.fire({
                title: "Editar!",
                text: 'Deseja editar o atestado ?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){
                    salvarAtestado();
                }
            })
        }else{
            salvarAtestado()
        }
    });

    $(".imprimir-atestado").on('click', function(e){
        e.preventDefault()
        if($("#atestado_id").val()){
            Swal.fire({
                title: "Editar!",
                text: 'Deseja editar o atestado e imprimir ?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){
                    salvarAtestado();
                    atestadoImprimir = true;
                }
            })
        }else{
            salvarAtestado()
            atestadoImprimir = true;
        }
    });

    function salvarAtestado(){
        var formData = new FormData($("#formAtestado")[0]);

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.atestado.atestadoSalvar', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: (result) => {
                $.toast({
                    heading: 'Sucesso',
                    text: 'Atestado salvo com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                $("#formAtestado").find("#atestado_id").val(result.id)
                atualizaHistoricoAtestado();
                carregaResumoPag();
            },
            complete: () => {
                var id = $("#atestado_id").val()
                if(atestadoImprimir == true){
                    liberaImprimirAtestado(id);
                    atestadoImprimir = false;
                }
                removeIcon("tab-atestado")
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader') 
            },
            error: function(response) {
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
    }

    function salvarAtestadoAuto(){
        var formData = new FormData($("#formAtestado")[0]);

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.atestado.atestadoSalvar', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: () => {
                // $('.loading').css('display', 'block');
                // $('.loading').find('.class-loading').addClass('loader')
            },
            success: (result) => {
                $.toast({
                    heading: 'Sucesso',
                    text: 'Atestado salvo com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                $("#formAtestado").find("#atestado_id").val(result.id)
                atualizaHistoricoAtestado();
                carregaResumoPag();
            },
            complete: () => {
                var id = $("#atestado_id").val()
                if(atestadoImprimir == true){
                    liberaImprimirAtestado(id);
                    atestadoImprimir = false;
                }
                removeIcon("tab-atestado")
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader') 
            },
            error: function(response) {
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
    }

    function liberaImprimirAtestado(id){
        var url = "{{route('agendamento.atestado.imprimirAtestado', ['atestado' => 'item'])}}".replace('item', id)
        newPopup(url);
    }

    function atualizaHistoricoAtestado(){

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.atestado.atestadoPacienteHistorico', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
            type: 'get',
            beforeSend: () => {
                $(".atestado-historico").html('');
            },
            success: function(result) {
                $(".atestado-historico").html(result);
            },
            complete: () => {
            }
        });
    }
</script>