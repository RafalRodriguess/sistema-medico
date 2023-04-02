
<div class="row">
    <div class="form-group col-md-4">
        {{-- <label class="form-control-label">Modelos</label> --}}
        <select name="modelo_encaminhamento" id="modelo_encaminhamento" class="form-control select2Encaminhamento" style="width: 100%">
            <option value="">Selecione um modelo</option>
            @foreach ($modeloEncaminhamento as $item)
                <option value="{{$item['id']}}">{{$item['descricao']}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-8">
        <button type="button" class="btn btn-info waves-effect waves-light m-r-10 historico-encaminhamento-button" style="float: right"><i
            class="fas fa-history"></i>
        Historico</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 salvar-encaminhamento-button" style="float: right"><i
            class="far fa-save"></i>
        Salvar</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 imprimir-encaminhamento" style="float: right"><i
            class="fas fa-print"></i>
        Salvar e imprimir</button>
        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10 novo-encaminhamento-button" style="float: right"><i
            class="fas fa-plus"></i>
        Novo</button>
    </div>
</div>
<hr>
<div class="row">

    <div class="form-encaminhamento col-md-12">
        <form id="formEncaminhamento" method="post" enctype="multipart/form-data">
            @csrf
            @include('instituicao.prontuarios.encaminhamentos.form')
        </form>
    </div>

    
</div>
<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 salvar-encaminhamento-button" style="float: right"><i
            class="far fa-save"></i>
        Salvar</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 imprimir-encaminhamento" style="float: right"><i
            class="fas fa-print"></i>
        Salvar e imprimir</button>
    </div>
</div>

<div class="encaminhamento-historico">
    @include('instituicao.prontuarios.encaminhamentos.historico')
</div>

<script>
    var reutilizarEncaminhamento = false;
    var encaminhamentoImprimir = false;

    $(document).ready(function(){
        $(".select2Encaminhamento").select2();

        // var teclaTime; //timer identifier
        // var teclaIntervalo = 1000; //time in ms, 1 second for example

        // $('#encaminhamento').keyup(function() {
        //     clearTimeout(teclaTime);
        //     if ($('#encaminhamento').val) {
        //         teclaTime = setTimeout(parouDeDigitar, teclaIntervalo);
        //     }
        // });
        
        // function parouDeDigitar(){
        //     salvarEncaminhamentoAuto();
        // }
    })

    $('#encaminhamento').bind('input propertychange', 'textarea', function(){
        addIcon("tab-encaminhamento")
    })

    $("#modelo_encaminhamento").on('change', function(){
        if($("#modelo_encaminhamento").val() != ""){
            var modelo_id = $("#modelo_encaminhamento").val();

            $.ajax({
                url: "{{route('agendamento.encaminhamento.modeloEncaminhamento', ['modelo' => 'modelo_id'])}}".replace('modelo_id', modelo_id),
                type: 'get',
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function(result) {
                    $('.summernoteEncaminhamento').summernote('code', '');
                    $('.summernoteEncaminhamento').summernote('code', result.texto);
                    addIcon("tab-encaminhamento")
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader') 
                }
            });
        }
    })

    $(".historico-encaminhamento-button").on('click', function(e){
        $(".encaminhamento-historico").find('#modalHistoricoEncaminhamento').modal('show')
    })

    $(".novo-encaminhamento-button").on('click', function(e){
        e.preventDefault()
        Swal.fire({
            title: "Novo!",
            text: 'Deseja criar novo o encaminhamento ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $("#encaminhamento_id").val('')
                $('.summernoteEncaminhamento').summernote('code', '');
                removeIcon("tab-encaminhamento")
                // $('#compartilhar_encaminhamento').prop('checked', false);
            }
        })
    })

    $(".salvar-encaminhamento-button").on('click', function(e){
        e.preventDefault()
        if($("#encaminhamento_id").val()){
            Swal.fire({
                title: "Editar!",
                text: 'Deseja editar o encaminhamento ?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){
                    salvarEncaminhamento();
                }
            })
        }else{
            salvarEncaminhamento()
        }
    });

    $(".imprimir-encaminhamento").on('click', function(e){
        e.preventDefault()
        if($("#encaminhamento_id").val()){
            Swal.fire({
                title: "Editar!",
                text: 'Deseja editar o encaminhamento e imprimir ?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){
                    salvarEncaminhamento();
                    encaminhamentoImprimir = true;
                }
            })
        }else{
            salvarEncaminhamento()
            encaminhamentoImprimir = true;
        }
    });

    function salvarEncaminhamento(){
        var formData = new FormData($("#formEncaminhamento")[0]);

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.encaminhamento.encaminhamentoSalvar', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
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
                    text: 'Encaminhamento salvo com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                $("#formEncaminhamento").find("#encaminhamento_id").val(result.id)
                atualizaHistoricoEncaminhamento();
                carregaResumoPag();
            },
            complete: () => {
                var id = $("#encaminhamento_id").val()
                if(encaminhamentoImprimir == true){
                    liberaImprimirEncaminhamento(id);
                    encaminhamentoImprimir = false;
                }
                removeIcon("tab-encaminhamento")
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

    function salvarEncaminhamentoAuto(){
        var formData = new FormData($("#formEncaminhamento")[0]);

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.encaminhamento.encaminhamentoSalvar', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
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
                    text: 'Encaminhamento salvo com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                $("#formEncaminhamento").find("#encaminhamento_id").val(result.id)
                atualizaHistoricoEncaminhamento();
                carregaResumoPag();
            },
            complete: () => {
                var id = $("#encaminhamento_id").val()
                if(encaminhamentoImprimir == true){
                    liberaImprimirEncaminhamento(id);
                    encaminhamentoImprimir = false;
                }
                removeIcon("tab-encaminhamento")
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

    function liberaImprimirEncaminhamento(id){
        var url = "{{route('agendamento.encaminhamento.imprimirEncaminhamento', ['encaminhamento' => 'item'])}}".replace('item', id)
        newPopup(url);
    }

    function atualizaHistoricoEncaminhamento(){

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.encaminhamento.encaminhamentoPacienteHistorico', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
            type: 'get',
            beforeSend: () => {
                $(".encaminhamento-historico").html('');
            },
            success: function(result) {
                $(".encaminhamento-historico").html(result);
            },
            complete: () => {
            }
        });
    }
</script>