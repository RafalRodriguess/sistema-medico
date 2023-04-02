
<div class="row">
    <div class="form-group col-md-4">
        {{-- <label class="form-control-label">Modelos</label> --}}
        <select name="modelo_laudo" id="modelo_laudo" class="form-control select2Laudo" style="width: 100%">
            <option value="">Selecione um modelo</option>
            @foreach ($modeloLaudo as $item)
                <option value="{{$item['id']}}">{{$item['descricao']}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-8">
        <button type="button" class="btn btn-info waves-effect waves-light m-r-10 historico-laudo-button" style="float: right"><i
            class="fas fa-history"></i>
        Historico</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 salvar-laudo-button" style="float: right"><i
            class="far fa-save"></i>
        Salvar</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 imprimir-laudo" style="float: right"><i
            class="fas fa-print"></i>
        Salvar e imprimir</button>
        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10 novo-laudo-button" style="float: right"><i
            class="fas fa-plus"></i>
        Novo</button>
    </div>
</div>
<hr>
<div class="row">

    <div class="form-laudo col-md-12" style="display: none">
        <form id="formLaudo" method="post" enctype="multipart/form-data">
            @csrf
            @include('instituicao.prontuarios.laudos.form')
        </form>
    </div>

    
</div>
<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 salvar-laudo-button" style="float: right"><i
            class="far fa-save"></i>
        Salvar</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 imprimir-laudo" style="float: right"><i
            class="fas fa-print"></i>
        Salvar e imprimir</button>
    </div>
</div>

<div class="laudo-historico">
    @include('instituicao.prontuarios.laudos.historico')
</div>

<script>
    var reutilizarLaudo = false;
    var laudoImprimir = false;

    $(document).ready(function(){
        $(".select2Laudo").select2();

        // var teclaTime; //timer identifier
        // var teclaIntervalo = 1000; //time in ms, 1 second for example

        // $('#laudo').keyup(function() {
        //     clearTimeout(teclaTime);
        //     if ($('#laudo').val) {
        //         teclaTime = setTimeout(parouDeDigitar, teclaIntervalo);
        //     }
        // });
        
        // function parouDeDigitar(){
        //     salvarLaudoAuto();
        // }
    })

    $('#laudo').bind('input propertychange', 'textarea', function(){
        addIcon("tab-laudo")
    })

    $("#modelo_laudo").on('change', function(){
        if($("#modelo_laudo").val() != ""){
            var modelo_id = $("#modelo_laudo").val();

            $.ajax({
                url: "{{route('agendamento.laudo.modeloLaudo', ['modelo' => 'modelo_id'])}}".replace('modelo_id', modelo_id),
                type: 'get',
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function(result) {
                    $('.summernoteLaudo').summernote('code', '');
                    $('.summernoteLaudo').summernote('code', result.texto);
                    $(".form-laudo").css('display', 'block');
                    addIcon("tab-laudo")
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader') 
                }
            });
        }
    })

    $(".historico-laudo-button").on('click', function(e){
        $(".laudo-historico").find('#modalHistoricoLaudo').modal('show')
    })

    $(".novo-laudo-button").on('click', function(e){
        e.preventDefault()
        Swal.fire({
            title: "Novo!",
            text: 'Deseja criar novo o laudo ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $("#laudo_id").val('')
                $('.summernoteLaudo').summernote('code', '');
                $(".form-laudo").css('display', 'none');
                $("#modelo_laudo").val("").change()
                removeIcon("tab-laudo")
                // $('#compartilhar_laudo').prop('checked', false);
            }
        })
    })

    $(".salvar-laudo-button").on('click', function(e){
        e.preventDefault()
        if($("#laudo_id").val()){
            Swal.fire({
                title: "Editar!",
                text: 'Deseja editar o laudo ?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){
                    salvarLaudo();
                }
            })
        }else{
            salvarLaudo()
        }
    });

    $(".imprimir-laudo").on('click', function(e){
        e.preventDefault()
        if($("#laudo_id").val()){
            Swal.fire({
                title: "Editar!",
                text: 'Deseja editar o laudo e imprimir ?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){
                    salvarLaudo();
                    laudoImprimir = true;
                }
            })
        }else{
            salvarLaudo()
            laudoImprimir = true;
        }
    });

    function salvarLaudo(){
        var formData = new FormData($("#formLaudo")[0]);

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.laudo.laudoSalvar', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
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
                    text: 'Laudo salvo com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                $("#formLaudo").find("#laudo_id").val(result.id)
                atualizaHistoricoLaudo();
                carregaResumoPag();
            },
            complete: () => {
                var id = $("#laudo_id").val()
                if(laudoImprimir == true){
                    liberaImprimirLaudo(id);
                    laudoImprimir = false;
                }
                removeIcon("tab-laudo")
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

    function salvarLaudoAuto(){
        var formData = new FormData($("#formLaudo")[0]);

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.laudo.laudoSalvar', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
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
                    text: 'Laudo salvo com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                $("#formLaudo").find("#laudo_id").val(result.id)
                atualizaHistoricoLaudo();
                carregaResumoPag();
            },
            complete: () => {
                var id = $("#laudo_id").val()
                if(laudoImprimir == true){
                    liberaImprimirLaudo(id);
                    laudoImprimir = false;
                }
                removeIcon("tab-laudo")
                // $('.loading').css('display', 'none');
                // $('.loading').find('.class-loading').removeClass('loader') 
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

    function liberaImprimirLaudo(id){
        var url = "{{route('agendamento.laudo.imprimirLaudo', ['laudo' => 'item'])}}".replace('item', id)
        newPopup(url);
    }

    function atualizaHistoricoLaudo(){

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.laudo.laudoPacienteHistorico', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
            type: 'get',
            beforeSend: () => {
                $(".laudo-historico").html('');
            },
            success: function(result) {
                $(".laudo-historico").html(result);
            },
            complete: () => {
            }
        });
    }
</script>