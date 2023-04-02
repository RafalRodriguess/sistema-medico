<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-info waves-effect waves-light m-r-10 historico-refracao-button" style="float: right"><i
            class="fas fa-history"></i>
        Historico</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 salvar-refracao-button" style="float: right"><i
            class="far fa-save"></i>
        Salvar</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 imprimir-refracao" style="float: right"><i
            class="fas fa-print"></i>
        Salvar e imprimir</button>
        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10 novo-refracao-button" style="float: right"><i
            class="fas fa-plus"></i>
        Novo</button>
    </div>
</div>
<hr>
<div class="row">

    <div class="form-refracao col-md-12">
        <form id="formRefracao" method="post" enctype="multipart/form-data">
            @csrf
            <div class="campos-form">
                @include('instituicao.prontuarios.refracoes.form')
            </div>
        </form>
    </div>

    
</div>
<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 salvar-refracao-button" style="float: right"><i
            class="far fa-save"></i>
        Salvar</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 imprimir-refracao" style="float: right"><i
            class="fas fa-print"></i>
        Salvar e imprimir</button>
    </div>
</div>

<div class="refracao-historico">
    @include('instituicao.prontuarios.refracoes.historico')
</div>

<script>
    var reutilizarRefracao = false;
    var refracaoImprimir = false;

    $(document).ready(function(){
        $(".select2Refracao").select2();

        // var teclaTime; //timer identifier
        // var teclaIntervalo = 1000; //time in ms, 1 second for example

        // $('#refracao').keyup(function() {
        //     clearTimeout(teclaTime);
        //     if ($('#refracao').val) {
        //         teclaTime = setTimeout(parouDeDigitar, teclaIntervalo);
        //     }
        // });
        
        // function parouDeDigitar(){
        //     salvarRefracaoAuto();
        // }
    })

    $('#refracao').bind('input propertychange', 'textarea', function(){
        addIcon("tab-refracao")
    })

    $('#refracao').on('change', 'select', function(){
        addIcon("tab-refracao")
    })

    $(".historico-refracao-button").on('click', function(e){
        $(".refracao-historico").find('#modalHistoricoRefracao').modal('show')
    })

    $(".novo-refracao-button").on('click', function(e){
        e.preventDefault()
        Swal.fire({
            title: "Novo!",
            text: 'Deseja criar nova refração ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $("#refracao_id").val('')
                $('#formRefracao textarea').each(function(index, element){
                    $(element).val("")
                });
                $('#formRefracao .selectfild2Refracao').each(function(index, element){
                    $(element).val("").change()
                });
                $('#formRefracao .selectfild2RefracaoPlano').each(function(index, element){
                    $(element).val("plano").change()
                });
                $('#formRefracao .checkboxRefracao').each(function(index, element){
                    $(element).prop('checked', false);
                });
                removeIcon("tab-refracao")
                // $('#compartilhar_exame').prop('checked', false);
            }
        })
    })

    $(".salvar-refracao-button").on('click', function(e){
        e.preventDefault()
        if($("#refracao_id").val()){
            Swal.fire({
                title: "Editar!",
                text: 'Deseja editar a refração ?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){
                    salvarRefracao();
                }
            })
        }else{
            salvarRefracao()
        }

    });

    $(".imprimir-refracao").on('click', function(e){
        e.preventDefault()
        if($("#refracao_id").val()){
            Swal.fire({
                title: "Editar!",
                text: 'Deseja editar a refração e imprimir ?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){
                    salvarRefracao();
                    refracaoImprimir = true;
                }
            })
        }else{
            salvarRefracao()
            refracaoImprimir = true;
        }
    });

    function salvarRefracao(){
        var formData = new FormData($("#formRefracao")[0]);

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.refracao.refracaoSalvar', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
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
                    text: 'Refração salvo com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                $("#formRefracao").find("#refracao_id").val(result.id)
                atualizaHistoricoRefracao();
                carregaResumoPag();
            },
            complete: () => {
                
                var id = $("#refracao_id").val()
                if(refracaoImprimir == true){
                    liberaImprimirRefracao(id);
                    refracaoImprimir = false;
                }
                removeIcon("tab-refracao")
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

    function salvarRefracaoAuto(){
        var formData = new FormData($("#formRefracao")[0]);

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.refracao.refracaoSalvar', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
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
                    text: 'Refração salvo com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                $("#formRefracao").find("#refracao_id").val(result.id)
                atualizaHistoricoRefracao();
                carregaResumoPag();
            },
            complete: () => {
                
                var id = $("#refracao_id").val()
                if(refracaoImprimir == true){
                    liberaImprimirRefracao(id);
                    refracaoImprimir = false;
                }
                removeIcon("tab-refracao")
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

    function liberaImprimirRefracao(id){
        var url = "{{route('agendamento.refracao.imprimirRefracao', ['refracao' => 'item'])}}".replace('item', id)
        newPopup(url);
    }

    function atualizaHistoricoRefracao(){

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.refracao.refracaoPacienteHistorico', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
            type: 'get',
            beforeSend: () => {
                $(".refracao-historico").html('');
            },
            success: function(result) {
                $(".refracao-historico").html(result);
            },
            complete: () => {
            }
        });
    }
</script>