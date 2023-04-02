<div class="row">
    <div class="col-md-4">
        {{-- <input type="checkbox" id="visualizar_receituario_paciente" class="filled-in chk-col-black"/>
        <label for="visualizar_receituario_paciente">Visualizar receituário do paciente</label> --}}
        <select name="modelo_receituario" id="modelo_receituario" class="form-control select2Receituario" style="width: 100%">
            <option value="">Selecione um modelo</option>
            @foreach ($modeloReceituario as $item)
                <option value="{{$item['id']}}">{{$item['descricao']}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-8">
        <button type="button" class="btn btn-info waves-effect waves-light m-r-10 historico-button-receituario" style="float: right"><i
            class="fas fa-history"></i>
        Historico</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 salvar-button-receituario" style="float: right"><i
            class="far fa-save"></i>
        Salvar</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 imprimir-button-receituario" style="float: right"><i
            class="fas fa-print"></i>
        Salvar e imprimir</button>
        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10 novo-button-receituario" style="float: right"><i
            class="fas fa-plus"></i>
        Novo</button>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-4">
        <input type="checkbox" id="receituario_controle_especial" class="filled-in chk-col-black"/>
        <label for="receituario_controle_especial">Receituário de controle especial</label>
    </div>
    <div class="col-md-8" style="text-align: right">
        <input type="checkbox" id="receituario_livre" class="filled-in chk-col-black"/>
        <label for="receituario_livre">Utilizar receituário livre</label>
    </div>
</div>
<hr>
<div class="row">
    @if ($receituario)
        {{-- <input type="hidden" name="receituario_id_editar" id="receituario_id_editar" value="{{$receituario->id}}"> --}}
    @endif

    <div class="form-receituario active col-md-12">
        <form id="formReceituario" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="receituario_medicamento_id" id="receituario_medicamento_id">
            <input type="hidden" name="receituario_medicamento_tipo" id="receituario_medicamento_tipo" value="">
            {{-- <input type="hidden" name="compartilhado" class="compartilhado_receituario" value="0"> --}}
            @include('instituicao.prontuarios.receituarios.form')
        </form>
    </div>
    <div class="form-receituario-livre col-md-12" style="display: none">
        <form id="formReceituarioLivre" method="post" enctype="multipart/form-data">
            @csrf
            {{-- <input type="hidden" name="compartilhado" class="compartilhado_receituario" value="0"> --}}
            @include('instituicao.prontuarios.receituarios.form-livre')
        </form>
    </div>

    {{-- <div class="col-md-12">
        <input type="checkbox" id="compartilhar_receituario" name="compartilhado_receituario" class="filled-in chk-col-black"/>
        <label for="compartilhar_receituario">Compartilhar receituário</label>
    </div> --}}
</div>
<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 salvar-button-receituario" style="float: right"><i
            class="far fa-save"></i>
        Salvar</button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 imprimir-button-receituario" style="float: right"><i
            class="fas fa-print"></i>
        Salvar e imprimir</button>
    </div>
</div>

<div class="receituario-historico">
    @include('instituicao.prontuarios.receituarios.historico')
</div>

<div class="modal inmodal fade bs-example-modal-lg" id="modalMedicamento" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Cadastro de medicamento</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form id="novoMedicamento" method="post" enctype="multipart/form-data">
                    @include('instituicao.medicamentos.formulario')
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success waves-effect text-left salvar_formulario_medicamentos">Salvar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    // $("#compartilhar_receituario").on('click', function(){
    //     verficaCompartilhado()
    // })

    // function verficaCompartilhado(){
    //     if($("#compartilhar_receituario").is(':checked')){
    //         $(".compartilhado_receituario").val(1)
    //     }else{
    //         $(".compartilhado_receituario").val(0)
    //     }
    // }
    
    var reutilizarReceituario = false;
    var receituarioImprimir = false;

    $(document).ready(function(){
        $(".select2Receituario").select2();

        // var teclaTime; //timer identifier
        // var teclaIntervalo = 1000; //time in ms, 1 second for example

        // $('#receituario').keyup(function() {
        //     clearTimeout(teclaTime);
        //     if ($('#receituario').val) {
        //         teclaTime = setTimeout(parouDeDigitar, teclaIntervalo);
        //     }
        // });
        
        // function parouDeDigitar(){
        //     if($(".form-receituario-livre").hasClass('active')){
        //         salvarReceituarioAuto('formReceituarioLivre');
        //     }else{
        //         salvarReceituarioAuto('formReceituario');
        //     }
        // }
    })

    $('#receituario').bind('input propertychange', 'textarea', function(){
        addIcon("tab-receituario")
    })

    $('#receituario').on('change', 'select', function(){
        addIcon("tab-receituario")
    })


    $("#modelo_receituario").on('change', function(){
        if($("#modelo_receituario").val() != ""){
            var modelo_id = $("#modelo_receituario").val();

            $.ajax({
                url: "{{route('agendamento.receituario.modeloReceituario', ['modelo' => 'modelo_id'])}}".replace('modelo_id', modelo_id),
                type: 'get',
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function(result) {
                    reutilizarReceituario = true
                    editarReceituario(result);
                    addIcon("tab-receituario")
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader') 
                }
            });
        }
    })

    $(".historico-button-receituario").on('click', function(e){
        $(".receituario-historico").find('#modalHistoricoReceituario').modal('show')
    })

    $(".novo-button-receituario").on('click', function(e){
        $("#receituario_livre_id").val('')
        $("#receituario_medicamento_id").val('')
        $('.summernoteReceituario').summernote('code', '');
        // $('#compartilhar_receituario').prop('checked', false);
        $(".form-receituario").find('.item-medicamento').remove()
        $("#receituario_controle_especial").prop('checked', false);
        // verficaCompartilhado()
        removeIcon("tab-receituario")
        quantidade_medicamento = 0;
        quantidade_composicao_receituario = 0;
        addMedicamento();
    });
    
    $("#receituario_controle_especial").on('change', function(e){
        if($("#receituario_controle_especial").is(":checked")){
            $("#formReceituarioLivre").find("#receituario_livre_tipo").val(1)
            $("#receituario_medicamento_tipo").val(1)
        }else{
            $("#formReceituarioLivre").find("#receituario_livre_tipo").val(0)
            $("#receituario_medicamento_tipo").val(0)
        }
    });

    $(".salvar-button-receituario").on('click', function(e){
        e.stopImmediatePropagation()
        e.stopPropagation()
        e.preventDefault()
        if($(".form-receituario-livre").hasClass('active')){
            if($("#receituario_livre_id").val()){
                Swal.fire({
                    title: "Editar!",
                    text: 'Deseja editar o receituario ?',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: "Não, cancelar!",
                    confirmButtonText: "Sim, confirmar!",
                }).then(function(result) {
                    if(result.value){
                        salvarReceituario('formReceituarioLivre');
                    }
                })
            }else{
                salvarReceituario('formReceituarioLivre');
            }
        }else{
            if($("#receituario_medicamento_id").val()){
                Swal.fire({
                    title: "Editar!",
                    text: 'Deseja editar o receituario ?',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: "Não, cancelar!",
                    confirmButtonText: "Sim, confirmar!",
                }).then(function(result) {
                    if(result.value){
                        salvarReceituario('formReceituario');
                    }
                })
            }else{
                salvarReceituario('formReceituario');
            }
        }
    });
    
    $(".imprimir-button-receituario").on('click', function(e){
        e.stopImmediatePropagation()
        e.stopPropagation()
        e.preventDefault()
        if($(".form-receituario-livre").hasClass('active')){
            if($("#receituario_livre_id").val()){
                Swal.fire({
                    title: "Editar!",
                    text: 'Deseja editar e imprimir o receituario ?',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: "Não, cancelar!",
                    confirmButtonText: "Sim, confirmar!",
                }).then(function(result) {
                    if(result.value){
                        receituarioImprimir = true;
                        salvarReceituario('formReceituarioLivre');
                    }
                })
            }else{
                receituarioImprimir = true;
                salvarReceituario('formReceituarioLivre');
            }
        }else{
            if($("#receituario_medicamento_id").val()){
                Swal.fire({
                    title: "Editar!",
                    text: 'Deseja editar e imprimir o receituario ?',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: "Não, cancelar!",
                    confirmButtonText: "Sim, confirmar!",
                }).then(function(result) {
                    if(result.value){
                        receituarioImprimir = true;
                        salvarReceituario('formReceituario');
                    }
                })
            }else{
                receituarioImprimir = true;
                salvarReceituario('formReceituario');
            }
        }
    });

    function salvarReceituario(form){
        
        var formData = new FormData($("#"+form+"")[0]);

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();
        var url = '';
        if(form == 'formReceituario'){
            url = "{{route('agendamento.receituario.receituarioSalvar', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id);
        }else{
            url = "{{route('agendamento.receituario.receituarioSalvarLivre', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id);
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
                    text: 'Receituario salvo com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                if(form == 'formReceituario'){
                    $("#receituario_medicamento_id").val(result.id)
                }else{
                    $("#receituario_livre_id").val(result.id)
                }
                atualizaHistoricoReceituario(form);
                carregaResumoPag();
            },
            complete: () => {
                var id = "";
                if(receituarioImprimir == true){
                    if(form == 'formReceituario'){
                        id = $("#receituario_medicamento_id").val()
                    }else{
                        id = $("#receituario_livre_id").val()
                    }
                    liberaImprimirReceituario(id);
                    receituarioImprimir = false;
                }
                removeIcon("tab-receituario")
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

    function salvarReceituarioAuto(form){
        
        var formData = new FormData($("#"+form+"")[0]);

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();
        var url = '';
        if(form == 'formReceituario'){
            url = "{{route('agendamento.receituario.receituarioSalvar', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id);
        }else{
            url = "{{route('agendamento.receituario.receituarioSalvarLivre', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id);
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
                    text: 'Receituario salvo com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                if(form == 'formReceituario'){
                    $("#receituario_medicamento_id").val(result.id)
                }else{
                    $("#receituario_livre_id").val(result.id)
                }
                atualizaHistoricoReceituario(form);
                carregaResumoPag();
            },
            complete: () => {
                var id = "";
                if(receituarioImprimir == true){
                    if(form == 'formReceituario'){
                        id = $("#receituario_medicamento_id").val()
                    }else{
                        id = $("#receituario_livre_id").val()
                    }
                    liberaImprimirReceituario(id);
                    receituarioImprimir = false;
                }
                removeIcon("tab-receituario")
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

    function atualizaHistoricoReceituario(form){
        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.receituario.receituarioPacienteHistorico', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
            type: 'get',
            beforeSend: () => {
                $(".receituario-historico").html('');
            },
            success: function(result) {
                $(".receituario-historico").html(result);
            },
            complete: () => {
                var id = $(".receituario-historico").find("#receituario_id_editar").val();
                if(form == 'formReceituario'){
                    $("#receituario_medicamento_id").val(id)
                }else{
                    $("#receituario_livre_id").val(id)
                }
            }
        });
    }

    $("#receituario_livre").on('change', function() {
        if($("#receituario_livre").is(":checked")){
            $(".form-receituario").css('display', 'none')
            $(".form-receituario").removeClass('active')
            $(".form-receituario-livre").css('display', 'block')
            $(".form-receituario-livre").addClass('active')
        }else{
            $(".form-receituario").css('display', 'block')
            $(".form-receituario").addClass('active')
            $(".form-receituario-livre").css('display', 'none')
            $(".form-receituario-livre").removeClass('active')
        }
    })

    function editarReceituario(result){
        $(".receituario-historico").find("#modalHistoricoReceituario").modal('hide');
        var tipo = 0;
        if (result.tipo == 'especial') { tipo = 1 };

        if(result.compartilhado == 1){
            // $(".compartilhado_receituario").val(1);
            // $("#compartilhar_receituario").prop('checked', true);
        }else{
            // $("#compartilhar_receituario").prop('checked', false);
            // $(".compartilhado_receituario").val(0);
        }

        if(result.estrutura == 'livre'){
            $(".form-receituario").css('display', 'none')
            $(".form-receituario").removeClass('active')
            $(".form-receituario-livre").css('display', 'block')
            $(".form-receituario-livre").addClass('active')
            if(reutilizarReceituario == false){
                $("#receituario_livre_id").val(result.id)
            }else{
                $("#receituario_livre_id").val("")
            }
            $('.summernoteReceituario').summernote('code', result.receituario['receituario']);
            $("#formReceituarioLivre").find("#receituario_livre_tipo").val(tipo)
            if(tipo == 1){
                $("#receituario_controle_especial").prop('checked', true);
            }else{
                $("#receituario_controle_especial").prop('checked', false);
            }
            $("#receituario_livre").prop('checked', true);
            
        }else{
            $("#receituario_livre").prop('checked', false);
            $(".form-receituario").css('display', 'block')
            $(".form-receituario").addClass('active')
            $(".form-receituario-livre").css('display', 'none')
            $(".form-receituario-livre").removeClass('active')
            $("#receituario_medicamento_tipo").val(tipo)
            if(reutilizarReceituario == false){
                $("#receituario_medicamento_id").val(result.id)
            }else{
                $("#receituario_medicamento_id").val("")
            }
            if(tipo == 1){
                $("#receituario_controle_especial").prop('checked', true);
            }else{
                $("#receituario_controle_especial").prop('checked', false);
            }
            quantidade_medicamento = 0;
            quantidade_composicao_receituario = 0;
            $(".form-receituario").find('.item-medicamento').remove()
            if(result.receituario.hasOwnProperty('medicamentos')){

                for (let index = 0; index < result.receituario['medicamentos'].length; index++) {
                    const element = result.receituario['medicamentos'][index];
                    quantidade_medicamento++;
    
                    $($('#item-medicamento').html()).insertBefore(".add-class-medicamento");
    
                    $('.mask_item').setMask();
                    $('.mask_item').removeClass('mask_item');
                    $("[data-toggle='tooltip']").tooltip()
                    $(".selectfild2Medicamento").select2();
    
                    $("[name^='medicamentos[#]']").each(function(index, element) {
                        const name = $(element).attr('name');
    
                        $(element).attr('name', name.replace('#',quantidade_medicamento));
                    })
    
                    $("[name^='medicamentos["+quantidade_medicamento+"][medicamento']").val(result.receituario['medicamentos'][index]['medicamento']['medicamento_id']).change()
                    $("[name^='medicamentos["+quantidade_medicamento+"][medicamento']").attr('onchange', 'getMedicamento(this)');
                    $("[name^='medicamentos["+quantidade_medicamento+"][quantidade']").val(result.receituario['medicamentos'][index]['quantidade'])
                    $("[name^='medicamentos["+quantidade_medicamento+"][posologia']").val(result.receituario['medicamentos'][index]['posologia'])
    
                    $("[name^='medicamentos["+quantidade_medicamento+"][medicamento']").parents(".item-medicamento").find('.add-class-composicao').addClass('add-class-composicao-'+quantidade_medicamento);
                    $("[name^='medicamentos["+quantidade_medicamento+"][medicamento']").parents(".item-medicamento").find('.add-class-composicao').attr('onclick', 'addMedicamentoComposicaoReceituario('+quantidade_medicamento+')');
                    $("[name^='medicamentos["+quantidade_medicamento+"][medicamento']").parents(".item-medicamento").find('.add-class-composicao').removeClass('add-class-composicao');
    
                    
                    if(result.receituario['medicamentos'][index]['medicamento']['composicao'] != null){
                        $("[name^='medicamentos["+quantidade_medicamento+"][medicamento']").parents(".item-medicamento").find(".adicionar_composicao_campo").css('display', 'block');
                        
                        for (let posicao = 0; posicao < Object.keys(result.receituario['medicamentos'][index]['medicamento']['composicao']).length; posicao++) {
                            const value = result.receituario['medicamentos'][index]['medicamento']['composicao'][posicao];
                            quantidade_composicao_receituario++;
                    
                            $($('#item-composicao-medicamento-receituario').html()).insertBefore(".add-class-composicao-"+quantidade_medicamento);
    
                            $("[name^='composicoes[#]']").each(function(index, element) {
                                const name = $(element).attr('name');
    
                                $(element).attr('name', name.replace('#', quantidade_medicamento+"]["+quantidade_composicao_receituario));
                            })
    
                            $("[name^='composicoes["+quantidade_medicamento+"]["+quantidade_composicao_receituario+"][substancia']").val(value['substancia'])
                            $("[name^='composicoes["+quantidade_medicamento+"]["+quantidade_composicao_receituario+"][concentracao']").val(value['concentracao'])
                        }
                    }
                }
            }else{
                modeloMedicamentos(result);
            }
        }
    }

    function modeloMedicamentos(result){
        for (let index = 0; index < result.receituario.length; index++) {
            const element = result.receituario[index];
            quantidade_medicamento++;

            $($('#item-medicamento').html()).insertBefore(".add-class-medicamento");

            $('.mask_item').setMask();
            $('.mask_item').removeClass('mask_item');
            $("[data-toggle='tooltip']").tooltip()
            $(".selectfild2Medicamento").select2();

            $("[name^='medicamentos[#]']").each(function(index, element) {
                const name = $(element).attr('name');

                $(element).attr('name', name.replace('#',quantidade_medicamento));
            })

            $("[name^='medicamentos["+quantidade_medicamento+"][medicamento']").val(result.receituario[index]['medicamento']['medicamento_id']).change()
            $("[name^='medicamentos["+quantidade_medicamento+"][medicamento']").attr('onchange', 'getMedicamento(this)');
            $("[name^='medicamentos["+quantidade_medicamento+"][quantidade']").val(result.receituario[index]['quantidade'])
            $("[name^='medicamentos["+quantidade_medicamento+"][posologia']").val(result.receituario[index]['posologia'])

            $("[name^='medicamentos["+quantidade_medicamento+"][medicamento']").parents(".item-medicamento").find('.add-class-composicao').addClass('add-class-composicao-'+quantidade_medicamento);
            $("[name^='medicamentos["+quantidade_medicamento+"][medicamento']").parents(".item-medicamento").find('.add-class-composicao').attr('onclick', 'addMedicamentoComposicaoReceituario('+quantidade_medicamento+')');
            $("[name^='medicamentos["+quantidade_medicamento+"][medicamento']").parents(".item-medicamento").find('.add-class-composicao').removeClass('add-class-composicao');

            
            if(result.receituario[index]['medicamento']['composicao'] != null){
                $("[name^='medicamentos["+quantidade_medicamento+"][medicamento']").parents(".item-medicamento").find(".adicionar_composicao_campo").css('display', 'block');
                
                for (let posicao = 0; posicao < Object.keys(result.receituario[index]['medicamento']['composicao']).length; posicao++) {
                    const value = result.receituario[index]['medicamento']['composicao'][posicao];
                    quantidade_composicao_receituario++;
            
                    $($('#item-composicao-medicamento-receituario').html()).insertBefore(".add-class-composicao-"+quantidade_medicamento);

                    $("[name^='composicoes[#]']").each(function(index, element) {
                        const name = $(element).attr('name');

                        $(element).attr('name', name.replace('#', quantidade_medicamento+"]["+quantidade_composicao_receituario));
                    })

                    $("[name^='composicoes["+quantidade_medicamento+"]["+quantidade_composicao_receituario+"][substancia']").val(value['substancia'])
                    $("[name^='composicoes["+quantidade_medicamento+"]["+quantidade_composicao_receituario+"][concentracao']").val(value['concentracao'])
                }
            }
        }
    }

    function liberaImprimirReceituario(id){
        var url = "{{route('agendamento.receituario.imprimirReceituario', ['receituario' => 'item'])}}".replace('item', id)
        newPopup(url);
    }
</script>


<script type="text/template" id="item-medicamento">
    <div class="col-md-12 item-medicamento">
        <div class="row">
            <div class="col-md-12">
                <a href="javascrit:void(0)" class="small remove-medicamento">(remover)</a>
            </div>
            <div class="form-group dados_parcela col-md-10">
                <label class="form-control-label">Medicamento *
                    <span alt="default" class="addMedicamento fas fa-plus-circle" style="cursor: pointer;" onclick="cadastrarMedicamento()">
                        <a class="mytooltip" href="javascript:void(0)" >
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Cadastrar medicamento"></i>
                        </a>
                    </span>
                </label>
                <select name="medicamentos[#][medicamento]" class="form-control selectfild2Medicamento" style="width: 100%">
                    <option value="">Selecione um medicamento</option>
                    @foreach ($medicamentos as $item)
                        <option value="{{$item->id}}" data-tipo="{{$item->tipo}}">{{$item->nome}} ({{$item->concentracao}} - {{$item->forma_farmaceutica}})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2">
                <label class="form-control-label">Quantidade *</span></label>
                <input type="text" class="form-control mask_item" name="medicamentos[#][quantidade]" required>
            </div>
            <div class="col-md-3"></div>
            <div class="row col-md-6 adicionar_composicao_campo" style="display: none">
                <div class="card">
                    <div class="card-body">
                        <h5>Composição</h5>
                        <div class="composicao_medicamento_itens_receituario row">
                            <div class="form-group col-md-12 add-class-composicao" >
                                <span alt="default" class="add-composicao fas fa-plus-circle">
                                    <a class="mytooltip" href="javascript:void(0)">
                                        <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar composição"></i>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="form-control-label">Posologia</span></label>
                <textarea class="form-control " name="medicamentos[#][posologia]" cols="2" rows="2"></textarea>
            </div>
        </div>
        <hr style="width: 100%">
    </div>
</script>

<script type="text/template" id="item-composicao-medicamento-receituario">
    <div class="col-md-12 item-composicao-medicamento">
        <div class="row">
            <div class="col-md-12">
                <div class="small" onclick="removerComposicao(this)"  style="color: blue; cursor: pointer;">(remover)</div>
            </div>
            <div class="form-group dados_parcela col-md-8">
                <label class="form-control-label">Substancia *:</label>
                <input type="text" name="composicoes[#][substancia]" class="form-control">
            </div>
            <div class="form-group col-md-4">
                <label class="form-control-label">Concentração *</span></label>
                <input type="text" class="form-control" name="composicoes[#][concentracao]" placeholder="Ex.: 10 mg/ml, 100 mg, 1000UI">
            </div>
        </div>
    </div>
</script>