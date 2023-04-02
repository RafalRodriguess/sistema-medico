<div class="row">
    <div class="col-md-3">
        <input type="checkbox" id="visualizar_prontuarios_paciente" class="filled-in chk-col-black"/>
        <label for="visualizar_prontuarios_paciente">Visualizar prontuários do paciente</label>
    </div>
    <div class="form-group col-md-4">
        {{-- <label class="form-control-label">Modelos</label> --}}
        <select name="modelo_prontuario" id="modelo_prontuario" class="form-control select2Prontuario" style="width: 100%">
            <option value="livre"  {{($tipo_prontuario == 'livre') ? 'selected' : ""}}>Prontuário livre</option>
            <option value="padrao" {{($tipo_prontuario == 'padrao') ? 'selected' : ""}}>Prontuário padrão</option>
            @foreach ($modeloProntuario as $item)
                <option value="{{$item['id']}}" data-tipo="{{$item['tipo']}}">{{$item['descricao']}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-5">
        <button type="button" class="btn btn-info waves-effect waves-light m-r-10 historico-button" style="float: right"><i
            class="fas fa-history"></i>
        Historico</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 salvar-button" style="float: right"><i
            class="far fa-save"></i>
        Salvar</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 imprimir-prontuario" style="float: right"><i
            class="fas fa-print"></i>
        Salvar e imprimir</button>
        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10 novo-button" style="float: right"><i
            class="fas fa-plus"></i>
        Novo</button>
    </div>
</div>
<br>
<div class="row">

    <div class="historico-prontuario col-md-4" style="display: none">
        @include('instituicao.prontuarios.prontuario.lista')
    </div>

    <div class="form-prontuario col-md-12">
        <form id="formProntuario" method="post" enctype="multipart/form-data">
            @csrf
            @include('instituicao.prontuarios.prontuario.form')
        </form>
        <form id="formProntuarioPadrao" method="post" enctype="multipart/form-data" style="display: none">
            @csrf
            <input type="hidden" name="prontuario_id" class="prontuario_id" value="">
            <div class="campos-padrao">
                @include('instituicao.configuracoes.modelo_prontuario.anamnese')
            </div>
        </form>
    </div>

    
</div>
<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 salvar-button" style="float: right"><i
            class="far fa-save"></i>
        Salvar</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 imprimir-prontuario" style="float: right"><i
            class="fas fa-print" ></i>
        Salvar e imprimir</button>
    </div>
</div>

<div class="prontuario-historico">
    @include('instituicao.prontuarios.prontuario.historico')
</div>

<script>

    $(document).ready(function(){
        $(".select2Prontuario").select2();
        tipoProntuario($("#modelo_prontuario").val())

        // var teclaTime; //timer identifier
        // var teclaIntervalo = 1000; //time in ms, 1 second for example

        // $('#prontuario').keyup(function() {
        //     clearTimeout(teclaTime);
        //     if ($('#prontuario').val) {
        //         teclaTime = setTimeout(parouDeDigitar, teclaIntervalo);
        //     }
        // });
        
        // function parouDeDigitar(){
        //     salvarProntuarioAuto();
        // }
    })

    $('#prontuario').bind('input propertychange', 'textarea', function(){
        addIcon("tab-prontuario");
    })

    $('#prontuario').on('change', 'select', function(){
        addIcon("tab-prontuario");
    })

    var prontuarioImprimir = false;
    var reutilizarProntuario = false;
    $("#visualizar_prontuarios_paciente").on('change', function(){
        if($("#visualizar_prontuarios_paciente").is(':checked')){
            $(".form-prontuario").removeClass('col-md-12')
            $(".form-prontuario").addClass('col-md-8')
            $(".historico-prontuario").css('display','block')
        }else{
            $(".form-prontuario").removeClass('col-md-8')
            $(".form-prontuario").addClass('col-md-12')
            $(".historico-prontuario").css('display','none')
        }
    })

    $("#modelo_prontuario").on('change', function(){
        
        $(".prontuario_id").val('')
        $('.summernoteProntuario').summernote('code', '');
        // $('#formProntuarioPadrao').get(0).reset();
        $('#formProntuarioPadrao textarea').each(function(index, element){
            $(element).val("")
        });
        $('#formProntuarioPadrao select').each(function(index, element){
            $(element).val("").change()
        });
        
        var tipo = '';
        if($("#modelo_prontuario").val() == "livre"){
            tipo = 'livre';
            tipoProntuario(tipo)
            setTimeout(() => {
                removeIcon("tab-prontuario")
            }, 0);
        }else if($("#modelo_prontuario").val() == "padrao"){
            tipo = 'padrao';
            tipoProntuario(tipo)
            setTimeout(() => {
                removeIcon("tab-prontuario")
            }, 0);

        }else{
            var modelo = $("#modelo_prontuario :selected").attr('data-tipo')
            getModelo($("#modelo_prontuario").val(), modelo)
        }
        
        
    })

    function getModelo(modelo_id, modelo){

        $.ajax({
            url: "{{route('agendamento.prontuario.getModelo', ['modelo' => 'modelo_id'])}}".replace('modelo_id', modelo_id),
            type: 'get',
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: function(result) {                
                if(modelo == 'old'){
                    $('.summernoteProntuario').summernote('code', result.prontuario.obs);
                    tipoProntuario('livre');
                }else{
                    $(".campos-padrao").html('')
                    $(".campos-padrao").html(result)
                    tipoProntuario('padrao')
                }
            },
            complete: () => {
                addIcon("tab-prontuario")
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader') 
            },
        });
    }

    function tipoProntuario(tipo){
        if(tipo == "livre"){
            $("#formProntuario").css('display', 'block');
            $("#formProntuario").addClass('active');
            $("#formProntuarioPadrao").css('display', 'none');
            $("#formProntuarioPadrao").removeClass('active');
        }else{
            $("#formProntuario").css('display', 'none');       
            $("#formProntuario").removeClass('active');         
            $("#formProntuarioPadrao").css('display', 'block');
            $("#formProntuarioPadrao").addClass('active');
            $(".select2Cid").select2()
        }
    }


    $(".historico-button").on('click', function(e){
        $(".prontuario-historico").find('#modalHistorico').modal('show')
    })

    $(".novo-button").on('click', function(e){
        e.preventDefault()
        Swal.fire({
            title: "Novo!",
            text: 'Deseja criar novo o prontuario ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $(".prontuario_id").val('')
                $('.summernoteProntuario').summernote('code', '');
                $('#formProntuarioPadrao textarea').each(function(index, element){
                    $(element).val("")
                });
                $('#formProntuarioPadrao select').each(function(index, element){
                    $(element).val("").change()
                });
                removeIcon("tab-prontuario")
            }
        })
    })

    $(".salvar-button").on('click', function(e){
        e.preventDefault()
        if($(".prontuario_id").val()){
            Swal.fire({
                title: "Editar!",
                text: 'Deseja editar o prontuário ?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){
                    salvarProntuario();
                }
            })
        }else{
            salvarProntuario()
        }

    });
    
    $(".imprimir-prontuario").on('click', function(e){
        e.preventDefault()
        if($(".prontuario_id").val()){
            Swal.fire({
                title: "Editar!",
                text: 'Deseja editar o prontuário e imprimir ?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){
                    salvarProntuario();
                    prontuarioImprimir = true;
                }
            })
        }else{
            salvarProntuario()
            prontuarioImprimir = true;
        }

    });

    function salvarProntuario(){
        
        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        if($("#formProntuario").hasClass('active')){
            var formData = new FormData($("#formProntuario")[0]);
            var url = "{{route('agendamento.prontuario.prontuarioSalvar', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id)
        }else{
            var formData = new FormData($("#formProntuarioPadrao")[0]);
            var url = "{{route('agendamento.prontuario.prontuarioSalvarPadrao', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id)
        }

        $.ajax({
            url: url,
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
                    text: 'Prontuario salvo com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                $(".historico-prontuario").html('')
                $(".historico-prontuario").html(result)
                atualizaHistoricoProntuario();
                carregaResumoPag();
            },
            complete: () => {
                
                var id = $(".historico-prontuario").find("#prontuarioId").val();
                $(".prontuario_id").val(id)
                if(prontuarioImprimir == true){
                    liberaImprimir();
                    prontuarioImprimir = false;
                }
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader') 
                removeIcon("tab-prontuario")
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

    function salvarProntuarioAuto(){
        
        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        if($("#formProntuario").hasClass('active')){
            var formData = new FormData($("#formProntuario")[0]);
            var url = "{{route('agendamento.prontuario.prontuarioSalvar', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id)
        }else{
            var formData = new FormData($("#formProntuarioPadrao")[0]);
            var url = "{{route('agendamento.prontuario.prontuarioSalvarPadrao', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id)
        }

        $.ajax({
            url: url,
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
                    text: 'Prontuario salvo com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                $(".historico-prontuario").html('')
                $(".historico-prontuario").html(result)
                atualizaHistoricoProntuario();
                carregaResumoPag();
            },
            complete: () => {
                
                var id = $(".historico-prontuario").find("#prontuarioId").val();
                $(".prontuario_id").val(id)
                if(prontuarioImprimir == true){
                    liberaImprimir();
                    prontuarioImprimir = false;
                }
                // $('.loading').css('display', 'none');
                // $('.loading').find('.class-loading').removeClass('loader') 
                removeIcon("tab-prontuario")
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

    function liberaImprimir(){
        var id = $(".prontuario_id").val();
        var url = "{{route('agendamento.prontuario.imprimirProntuario', ['prontuario' => 'item'])}}".replace('item', id)
        newPopup(url);
    }

    function atualizaHistoricoProntuario(){

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.prontuario.prontuarioPacienteHistorico', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
            type: 'get',
            beforeSend: () => {
                $(".prontuario-historico").html('');
            },
            success: function(result) {
                $(".prontuario-historico").html(result);
            },
            complete: () => {
            }
        });
    }
</script>