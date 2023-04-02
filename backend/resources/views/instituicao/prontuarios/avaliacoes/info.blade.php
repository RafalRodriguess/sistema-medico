
<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-info waves-effect waves-light m-r-10 historico-avaliacao-button" style="float: right">
            <i class="fas fa-history"></i> Historico
        </button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 salvar-avaliacao-button" style="float: right">
            <i class="far fa-save"></i> Salvar
        </button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 imprimir-avaliacao" style="float: right">
            <i class="fas fa-print"></i> Salvar e imprimir
        </button>
        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10 novo-avaliacao-button" style="float: right">
            <i class="fas fa-plus"></i> Novo
        </button>
    </div>
</div>
<hr>
<div class="row">
    <div class="form-avaliacao col-md-12">
        <form id="formAvaliacao" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="form-group col-md-3">
                    {{-- <label class="form-control-label">Modelos</label> --}}
                    <select name="medico_id" id="medico_id" class="form-control select2" style="width: 100%">
                        <option value="">Selecione um médico</option>
                        @foreach ($medicos as $item)
                            <option value="{{$item['id']}}">{{$item['nome']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    {{-- <label class="form-control-label">Modelos</label> --}}
                    <select name="especialidade_id" id="especialidade_id" class="form-control select2" style="width: 100%">
                        <option value="">Selecione uma especialidade</option>
                        @foreach ($especialidades as $item)
                            <option value="{{$item['id']}}">{{$item['descricao']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @include('instituicao.prontuarios.avaliacoes.form')
        </form>
    </div>

    
</div>
<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 salvar-avaliacao-button" style="float: right">
            <i class="far fa-save"></i> Salvar
        </button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 imprimir-avaliacao" style="float: right">
            <i class="fas fa-print"></i> Salvar e imprimir
        </button>
    </div>
</div>

<div class="avaliacao-historico">
    @include('instituicao.prontuarios.avaliacoes.historico')
</div>

<script>
    var reutilizarAvaliacao = false;
    var avaliacaoImprimir = false;

    $(document).ready(function(){
        $(".select2").select2();
    })

    $('#avaliacao').bind('input propertychange', 'textarea', function(){
        addIcon("tab-avaliacao")
    })

    $(".historico-avaliacao-button").on('click', function(e){
        $(".avaliacao-historico").find('#modalHistoricoAvaliacao').modal('show')
    })

    $(".novo-avaliacao-button").on('click', function(e){
        e.preventDefault()
        Swal.fire({
            title: "Novo!",
            text: 'Deseja criar novo o avaliacao ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $("#avaliacao_id").val('')
                $('.summernoteAvaliacao').summernote('code', '');
                // $(".form-avaliacao").css('display', 'none');
                $("#modelo_avaliacao").val("").change()
                removeIcon("tab-avaliacao")
                // $('#compartilhar_avaliacao').prop('checked', false);
            }
        })
    })

    $(".salvar-avaliacao-button").on('click', function(e){
        e.preventDefault()
        if($("#avaliacao_id").val()){
            Swal.fire({
                title: "Editar!",
                text: 'Deseja editar o avaliacao ?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){
                    salvarAvaliacao();
                }
            })
        }else{
            salvarAvaliacao()
        }
    });

    $(".imprimir-avaliacao").on('click', function(e){
        e.preventDefault()
        if($("#avaliacao_id").val()){
            Swal.fire({
                title: "Editar!",
                text: 'Deseja editar o avaliacao e imprimir ?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){
                    salvarAvaliacao();
                    avaliacaoImprimir = true;
                }
            })
        }else{
            salvarAvaliacao()
            avaliacaoImprimir = true;
        }
    });

    function salvarAvaliacao(){
        var formData = new FormData($("#formAvaliacao")[0]);

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.internacoes.avaliacao.store', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
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
                    text: 'Avaliação salvo com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                $("#formAvaliacao").find("#avaliacao_id").val(result.id)
                atualizaHistoricoAvaliacao();
                carregaResumoPag();
            },
            complete: () => {
                var id = $("#avaliacao_id").val()
                if(avaliacaoImprimir == true){
                    liberaImprimirAvaliacao(id);
                    avaliacaoImprimir = false;
                }
                removeIcon("tab-avaliacao")
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

    function salvarAvaliacaoAuto(){
        var formData = new FormData($("#formAvaliacao")[0]);

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.internacoes.avaliacao.store', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
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
                    text: 'Avaliacao salvo com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                $("#formAvaliacao").find("#avaliacao_id").val(result.id)
                atualizaHistoricoAvaliacao();
                carregaResumoPag();
            },
            complete: () => {
                var id = $("#avaliacao_id").val()
                if(avaliacaoImprimir == true){
                    liberaImprimirAvaliacao(id);
                    avaliacaoImprimir = false;
                }
                removeIcon("tab-avaliacao")
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

    function liberaImprimirAvaliacao(id){
        var url = "{{route('agendamento.internacoes.avaliacao.imprimirAvaliacao', ['avaliacao' => 'avaliacao_id'])}}".replace('avaliacao_id', id)
        newPopup(url);
    }

    function atualizaHistoricoAvaliacao(){

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url:"{{route('agendamento.internacoes.avaliacao.avaliacaoHistorico', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
            type: 'get',
            beforeSend: () => {
                $(".avaliacao-historico").html('');
            },
            success: function(result) {
                $(".avaliacao-historico").html(result);
            },
            complete: () => {
            }
        });
    }
</script>