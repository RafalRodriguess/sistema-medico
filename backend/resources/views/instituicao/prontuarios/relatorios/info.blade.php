
<div class="row">
    <div class="form-group col-md-4">
        {{-- <label class="form-control-label">Modelos</label> --}}
        <select name="modelo_relatorio" id="modelo_relatorio" class="form-control select2Relatorio" style="width: 100%">
            <option value="">Selecione um modelo</option>
            @foreach ($modeloRelatorio as $item)
                <option value="{{$item['id']}}">{{$item['descricao']}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-8">
        <button type="button" class="btn btn-info waves-effect waves-light m-r-10 historico-relatorio-button" style="float: right"><i
            class="fas fa-history"></i>
        Historico</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 salvar-relatorio-button" style="float: right"><i
            class="far fa-save"></i>
        Salvar</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 imprimir-relatorio" style="float: right"><i
            class="fas fa-print"></i>
        Salvar e imprimir</button>
        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10 novo-relatorio-button" style="float: right"><i
            class="fas fa-plus"></i>
        Novo</button>
    </div>
</div>
<hr>
<div class="row">

    <div class="form-relatorio col-md-12">
        <form id="formRelatorio" method="post" enctype="multipart/form-data">
            @csrf
            @include('instituicao.prontuarios.relatorios.form')
        </form>
    </div>

    
</div>
<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 salvar-relatorio-button" style="float: right"><i
            class="far fa-save"></i>
        Salvar</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 imprimir-relatorio" style="float: right"><i
            class="fas fa-print"></i>
        Salvar e imprimir</button>
    </div>
</div>

<div class="relatorio-historico">
    @include('instituicao.prontuarios.relatorios.historico')
</div>

<script>
    var reutilizarRelatorio = false;
    var relatorioImprimir = false;

    $(document).ready(function(){
        $(".select2Relatorio").select2();

        // var teclaTime; //timer identifier
        // var teclaIntervalo = 1000; //time in ms, 1 second for example

        // $('#relatorio').keyup(function() {
        //     clearTimeout(teclaTime);
        //     if ($('#relatorio').val) {
        //         teclaTime = setTimeout(parouDeDigitar, teclaIntervalo);
        //     }
        // });
        
        // function parouDeDigitar(){
        //     salvarRelatorioAuto();
        // }
    })

    $('#relatorio').bind('input propertychange', 'textarea', function(){
        addIcon("tab-relatorio")
    })

    $("#modelo_relatorio").on('change', function(){
        if($("#modelo_relatorio").val() != ""){
            var modelo_id = $("#modelo_relatorio").val();

            $.ajax({
                url: "{{route('agendamento.relatorio.modeloRelatorio', ['modelo' => 'modelo_id'])}}".replace('modelo_id', modelo_id),
                type: 'get',
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function(result) {
                    $('.summernoteRelatorio').summernote('code', '');
                    $('.summernoteRelatorio').summernote('code', result.texto);
                    addIcon("tab-relatorio")
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader') 
                }
            });
        }
    })

    $(".historico-relatorio-button").on('click', function(e){
        $(".relatorio-historico").find('#modalHistoricoRelatorio').modal('show')
    })

    $(".novo-relatorio-button").on('click', function(e){
        e.preventDefault()
        Swal.fire({
            title: "Novo!",
            text: 'Deseja criar novo o relatorio ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $("#relatorio_id").val('')
                $('.summernoteRelatorio').summernote('code', '');
                removeIcon("tab-relatorio")
                // $('#compartilhar_relatorio').prop('checked', false);
            }
        })
    })

    $(".salvar-relatorio-button").on('click', function(e){
        e.preventDefault()
        if($("#relatorio_id").val()){
            Swal.fire({
                title: "Editar!",
                text: 'Deseja editar o relatorio ?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){
                    salvarRelatorio();
                }
            })
        }else{
            salvarRelatorio()
        }

    });

    $(".imprimir-relatorio").on('click', function(e){
        e.preventDefault()
        if($("#relatorio_id").val()){
            Swal.fire({
                title: "Editar!",
                text: 'Deseja editar o relatório e imprimir ?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){
                    salvarRelatorio();
                    relatorioImprimir = true;
                }
            })
        }else{
            salvarRelatorio()
            relatorioImprimir = true;
        }
    });

    function salvarRelatorio(){
        var formData = new FormData($("#formRelatorio")[0]);

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.relatorio.relatorioSalvar', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
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
                    text: 'Relatorio salvo com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                $("#formRelatorio").find("#relatorio_id").val(result.id)
                atualizaHistoricoRelatorio();
                carregaResumoPag();
            },
            complete: () => {
                var id = $("#relatorio_id").val()
                if(relatorioImprimir == true){
                    liberaImprimirRelatorio(id);
                    relatorioImprimir = false;
                }
                removeIcon("tab-relatorio")
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

    function salvarRelatorioAuto(){
        var formData = new FormData($("#formRelatorio")[0]);

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.relatorio.relatorioSalvar', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
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
                    text: 'Relatorio salvo com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                $("#formRelatorio").find("#relatorio_id").val(result.id)
                atualizaHistoricoRelatorio();
                carregaResumoPag();
            },
            complete: () => {
                var id = $("#relatorio_id").val()
                if(relatorioImprimir == true){
                    liberaImprimirRelatorio(id);
                    relatorioImprimir = false;
                }
                removeIcon("tab-relatorio")
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

    function liberaImprimirRelatorio(id){
        var url = "{{route('agendamento.relatorio.imprimirRelatorio', ['relatorio' => 'item'])}}".replace('item', id)
        newPopup(url);
    }

    function atualizaHistoricoRelatorio(){

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.relatorio.relatorioPacienteHistorico', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
            type: 'get',
            beforeSend: () => {
                $(".relatorio-historico").html('');
            },
            success: function(result) {
                $(".relatorio-historico").html(result);
            },
            complete: () => {
            }
        });
    }
</script>