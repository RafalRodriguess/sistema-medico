
<div class="row">
    <div class="form-group col-md-4">
        {{-- <label class="form-control-label">Modelos</label> --}}
        <select name="modelo_exame" id="modelo_exame" class="form-control select2Exame" style="width: 100%">
            <option value="">Selecione um modelo</option>
            @foreach ($modeloExame as $item)
                <option value="{{$item['id']}}">{{$item['descricao']}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-2">
        {{-- <label class="form-control-label">Modelos</label> --}}
        <button type="button" class="btn btn-info waves-effect waves-light m-r-10 addProcedimentosExame">
        Procedimento(s)</button>
    </div>
    <div class="col-md-6">
        <button type="button" class="btn btn-info waves-effect waves-light m-r-10 historico-exame-button" style="float: right"><i
            class="fas fa-history"></i>
        Historico</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 salvar-exame-button" style="float: right"><i
            class="far fa-save"></i>
        Salvar</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 imprimir-exame" style="float: right"><i
            class="fas fa-print"></i>
        Salvar e imprimir</button>
        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10 novo-exame-button" style="float: right"><i
            class="fas fa-plus"></i>
        Novo</button>
    </div>
</div>
<hr>
<div class="row">

    <div class="form-exame col-md-12">
        <form id="formExame" method="post" enctype="multipart/form-data">
            @csrf
            @include('instituicao.prontuarios.exames.form')
        </form>
    </div>

    
</div>
<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 salvar-exame-button" style="float: right"><i
            class="far fa-save"></i>
        Salvar</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 imprimir-exame" style="float: right"><i
            class="fas fa-print"></i>
        Salvar e imprimir</button>
    </div>
</div>

<div class="exame-historico">
    @include('instituicao.prontuarios.exames.historico')
</div>

<div wire:ignore class="modal inmodal no_print" id="modalPacotesProcedimentos" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        {{-- <div class="modal-content" style="background: #f8fafb;padding: 20px 30px 30px 30px"> --}}
        <div class="modal-content" style="background: #f8fafb;"></div>
    </div>
</div>

<script>
    var reutilizarexame = false;
    var exameImprimir = false;
    var procedimentos_exames = [];

    $(document).ready(function(){
        $(".select2Exame").select2();

        // var teclaTime; //timer identifier
        // var teclaIntervalo = 1000; //time in ms, 1 second for example

        // $('#exame').keyup(function() {
        //     clearTimeout(teclaTime);
        //     if ($('#exame').val) {
        //         teclaTime = setTimeout(parouDeDigitar, teclaIntervalo);
        //     }
        // });
        
        // function parouDeDigitar(){
        //     salvarExameAuto();
        // }
    })

    $('#exame').bind('input propertychange', 'textarea', function(){
        addIcon("tab-exame")
    })

    $("#modelo_exame").on('change', function(){
        if($("#modelo_exame").val() != ""){
            var modelo_id = $("#modelo_exame").val();

            $.ajax({
                url: "{{route('agendamento.exame.modeloExame', ['modelo' => 'modelo_id'])}}".replace('modelo_id', modelo_id),
                type: 'get',
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function(result) {
                    $('.summernoteExame').summernote('code', '');
                    $('.summernoteExame').summernote('code', result.texto);
                    addIcon("tab-exame")
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader') 
                }
            });
        }
    })

    $(".historico-exame-button").on('click', function(e){
        $(".exame-historico").find('#modalHistoricoExame').modal('show')
    })

    $(".novo-exame-button").on('click', function(e){
        e.preventDefault()
        Swal.fire({
            title: "Novo!",
            text: 'Deseja criar novo o exame ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $("#exame_id").val('')
                $('.summernoteExame').summernote('code', '');
                removeIcon("tab-exame")
                // $('#compartilhar_exame').prop('checked', false);
            }
        })
    })

    $(".salvar-exame-button").on('click', function(e){
        e.preventDefault()
        if($("#exame_id").val()){
            Swal.fire({
                title: "Editar!",
                text: 'Deseja editar o exame ?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){
                    salvarExame();
                }
            })
        }else{
            salvarExame()
        }

    });

    $(".imprimir-exame").on('click', function(e){
        e.preventDefault()
        if($("#exame_id").val()){
            Swal.fire({
                title: "Editar!",
                text: 'Deseja editar o exame e imprimir ?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){
                    salvarExame();
                    exameImprimir = true;
                }
            })
        }else{
            salvarExame()
            exameImprimir = true;
        }
    });

    function salvarExame(){
        var formData = new FormData($("#formExame")[0]);

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.exame.exameSalvar', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
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
                    text: 'Exame salvo com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                $("#formExame").find("#exame_id").val(result.id)
                atualizaHistoricoExame();
                carregaResumoPag();
            },
            complete: () => {
                
                var id = $("#exame_id").val()
                if(exameImprimir == true){
                    liberaImprimirExame(id);
                    exameImprimir = false;
                }
                removeIcon("tab-exame")
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

    function salvarExameAuto(){
        var formData = new FormData($("#formExame")[0]);

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.exame.exameSalvar', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
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
                    text: 'Exame salvo com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                $("#formExame").find("#exame_id").val(result.id)
                atualizaHistoricoExame();
                carregaResumoPag();
            },
            complete: () => {
                
                var id = $("#exame_id").val()
                if(exameImprimir == true){
                    liberaImprimirExame(id);
                    exameImprimir = false;
                }
                removeIcon("tab-exame")
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

    function liberaImprimirExame(id){
        var url = "{{route('agendamento.exame.imprimirExame', ['exame' => 'item'])}}".replace('item', id)
        newPopup(url);
    }

    function atualizaHistoricoExame(){

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.exame.examePacienteHistorico', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
            type: 'get',
            beforeSend: () => {
                $(".exame-historico").html('');
            },
            success: function(result) {
                $(".exame-historico").html(result);
            },
            complete: () => {
            }
        });
    }

    $(".addProcedimentosExame").on('click', function(e){
        e.preventDefault();

        $.ajax({
            url: "{{route('agendamento.exame.getSelectProcedimentos')}}",
            type: "GET",
            data: {
                "_token": "{{ csrf_token() }}",
            },
            datatype: "json",
            processData: false,
            contentType: false,
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
                procedimentos_exames = [];
            },
            success: function(result) {
                $('#modalPacotesProcedimentos .modal-content').html(result);
                $('#modalPacotesProcedimentos').modal('show');
            },
            complete: () => {
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader')
            }
        });
    })
</script>