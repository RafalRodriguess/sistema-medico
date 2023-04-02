
<div class="row">
    <div class="form-group col-md-4">
        {{-- <label class="form-control-label">Modelos</label> --}}
        <select name="modelo_conclusao" id="modelo_conclusao" class="form-control select2Conclusao" style="width: 100%">
            <option value="">Selecione um modelo</option>
            @foreach ($modeloConclusao as $item)
                <option value="{{$item['id']}}">{{$item['descricao']}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-8">
        <button type="button" class="btn btn-info waves-effect waves-light m-r-10 historico-conclusao-button" style="float: right"><i
            class="fas fa-history"></i>
        Historico</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 salvar-conclusao-button" style="float: right"><i
            class="far fa-save"></i>
        Salvar</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 imprimir-conclusao" style="float: right"><i
            class="fas fa-print"></i>
        Salvar e imprimir</button>
        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10 novo-conclusao-button" style="float: right"><i
            class="fas fa-plus"></i>
        Novo</button>
    </div>
</div>
<hr>
<div class="row">

    <div class="form-conclusao col-md-12">
        <form id="formConclusao" method="post" enctype="multipart/form-data">
            @csrf
            @include('instituicao.prontuarios.conclusoes.form')
        </form>
    </div>

    
</div>
<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 salvar-conclusao-button" style="float: right"><i
            class="far fa-save"></i>
        Salvar</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 imprimir-conclusao" style="float: right"><i
            class="fas fa-print"></i>
        Salvar e imprimir</button>
    </div>
</div>

<div class="conclusao-historico">
    @include('instituicao.prontuarios.conclusoes.historico')
</div>

<script>
    var reutilizarConclusao = false;
    var conclusaoImprimir = false;

    $(document).ready(function(){
        $(".select2Conclusao").select2();

        // var teclaTime; //timer identifier
        // var teclaIntervalo = 1000; //time in ms, 1 second for example

        // $('#conclusao').keyup(function() {
        //     clearTimeout(teclaTime);
        //     if ($('#conclusao').val) {
        //         teclaTime = setTimeout(parouDeDigitar, teclaIntervalo);
        //     }
        // });
        
        // function parouDeDigitar(){
        //     salvarConclusaoAuto();
        // }
    })

    $('#conclusao').bind('input propertychange', 'textarea', function(){
        addIcon("tab-conclusao")
    })

    $("#modelo_conclusao").on('change', function(){
        if($("#modelo_conclusao").val() != ""){
            var modelo_id = $("#modelo_conclusao").val();

            $.ajax({
                url: "{{route('agendamento.conclusao.modeloConclusao', ['modelo' => 'modelo_id'])}}".replace('modelo_id', modelo_id),
                type: 'get',
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function(result) {
                    $('.summernoteConclusao').summernote('code', '');
                    $('.summernoteConclusao').summernote('code', result.texto);
                    $('#motivo_conclusao_id').val(result.motivo_conclusao_id).change();
                    addIcon("tab-conclusao")
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader') 
                }
            });
        }
    })

    $(".historico-conclusao-button").on('click', function(e){
        $(".conclusao-historico").find('#modalHistoricoConclusao').modal('show')
    })

    $(".novo-conclusao-button").on('click', function(e){
        e.preventDefault()
        Swal.fire({
            title: "Novo!",
            text: 'Deseja criar novo o conclusão ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $("#conclusao_id").val('')
                $('.summernoteConclusao').summernote('code', '');
                removeIcon("tab-conclusao")
                // $('#compartilhar_conclusao').prop('checked', false);
            }
        })
    })

    $(".salvar-conclusao-button").on('click', function(e){
        e.preventDefault()
        if($("#conclusao_id").val()){
            Swal.fire({
                title: "Editar!",
                text: 'Deseja editar o conclusão ?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){
                    salvarConclusao();
                }
            })
        }else{
            salvarConclusao()
        }
    });

    $(".imprimir-conclusao").on('click', function(e){
        e.preventDefault()
        if($("#conclusao_id").val()){
            Swal.fire({
                title: "Editar!",
                text: 'Deseja editar o conclusão e imprimir ?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){
                    salvarConclusao();
                    conclusaoImprimir = true;
                }
            })
        }else{
            salvarConclusao()
            conclusaoImprimir = true;
        }
    });

    function salvarConclusao(){
        var formData = new FormData($("#formConclusao")[0]);

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.conclusao.conclusaoSalvar', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
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
                    text: 'Conclusão salvo com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                $("#formConclusao").find("#conclusao_id").val(result.id)
                atualizaHistoricoConclusao();
                carregaResumoPag();
            },
            complete: () => {
                var id = $("#conclusao_id").val()
                if(conclusaoImprimir == true){
                    liberaImprimirConclusao(id);
                    conclusaoImprimir = false;
                }
                removeIcon("tab-conclusao")
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

    function salvarConclusaoAuto(){
        var formData = new FormData($("#formConclusao")[0]);

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.conclusao.conclusaoSalvar', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
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
                    text: 'Conclusão salvo com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                $("#formConclusao").find("#conclusao_id").val(result.id)
                atualizaHistoricoConclusao();
                carregaResumoPag();
            },
            complete: () => {
                var id = $("#conclusao_id").val()
                if(conclusaoImprimir == true){
                    liberaImprimirConclusao(id);
                    conclusaoImprimir = false;
                }
                removeIcon("tab-conclusao")
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

    function liberaImprimirConclusao(id){
        var url = "{{route('agendamento.conclusao.imprimirConclusao', ['conclusao' => 'item'])}}".replace('item', id)
        newPopup(url);
    }

    function atualizaHistoricoConclusao(){

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.conclusao.conclusaoPacienteHistorico', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
            type: 'get',
            beforeSend: () => {
                $(".conclusao-historico").html('');
            },
            success: function(result) {
                $(".conclusao-historico").html(result);
            },
            complete: () => {
            }
        });
    }
</script>