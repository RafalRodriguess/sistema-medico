<form method="post" enctype="multipart/form-data" id="formPacienteDados">
    
    @include('instituicao.pessoas.formularioEditar')
    <div class="col-sm-12 p-0 m-0">
        <div class="row no_print">
            <div class="col-sm-12">
                <div class="card shadow-none bg-light">
                    <div class="row d-flex justify-content-between p-2 m-0">
                        <label class="form-control-label p-0 m-0">Carteirinhas</label>
                    </div>
                </div>
                @for ($i = 0; $i < count($pessoa->carteirinha) ; $i ++)
                    <div class="card shadow-none carteirinha-item p-0" id="{{ $i }}">
                        <div class="row m-0 p-0">
                            <div class="col-sm-12 bg-light border-bottom">
                                <div class="row d-flex justify-content-between p-2 m-0">
                                    <label class="form-control-label p-0 m-0">
                                        <span class="title"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row p-2 m-0">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label class="form-control-label p-0 m-0">Convênio</label>
                                    <input type="text" class="form-control" name="carteirinha[{{$i}}][carteirinha]" value="{{$pessoa->carteirinha[$i]->convenio[0]->nome}}" placeholder="Carteirinha" disabled>
                                    @if($errors->has("carteirinha.{$i}.carteirinha"))
                                        <small class="form-text text-danger">{{ $errors->first("carteirinha.{$i}.carteirinha") }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm">
                                <div class="form-group">
                                    <label class="form-control-label p-0 m-0">Planos</label>
                                    <input type="text" class="form-control" name="carteirinha[{{$i}}][plano_id]" value="{{$pessoa->carteirinha[$i]->plano[0]->nome}}" placeholder="Carteirinha" disabled>
                                    @if($errors->has("carteirinha.{$i}.plano_id"))
                                        <small class="form-text text-danger">{{ $errors->first("carteirinha.{$i}.plano_id") }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm">
                                <label class="form-control-label p-0 m-0">Carteirinha</label>
                                <input type="text" class="form-control" name="carteirinha[{{$i}}][carteirinha]" value="{{old("carteirinha.{$i}.carteirinha",$pessoa->carteirinha[$i]->carteirinha)}}" placeholder="Carteirinha" disabled>
                                @if($errors->has("carteirinha.{$i}.carteirinha"))
                                    <small class="form-text text-danger">{{ $errors->first("carteirinha.{$i}.carteirinha") }}</small>
                                @endif
                            </div>

                            <div class="col-sm">
                                <label class="form-control-label p-0 m-0">Validade</label>
                                <input type="date" class="form-control" name="carteirinha[{{$i}}][validade]" value="{{old("carteirinha.{$i}.validade", $pessoa->carteirinha[$i]->validade)}}" placeholder="Validade" disabled>
                                @if($errors->has("carteirinha.{$i}.validade"))
                                    <small class="form-text text-danger">{{ $errors->first("carteirinha.{$i}.validade") }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</form>

<script type="text/template" id="pessoa-nao-associada">
    <small class="form-text text-primary pessoa-nao-registrada pessoa-alerta">
        <i class="ti-check"></i> Disponível
    </small>
</script>

<script type="text/template" id="pessoa-associada">
    <small class="form-text text-danger pessoa-registrada pessoa-alerta">
        <i class="ti-close"></i> Proibido
    </small>
</script>

<script>
    $(document).ready(function(){
        $('input').setMask();
        function blockButtons() {
            $('#salvar').prop('disabled', true);
            $('#adiciona-documento').prop('disabled', true);
        }

        function desblockButtons() {
            $('#salvar').prop('disabled', false);
            $('#adiciona-documento').prop('disabled', false);
        }

        function requestPessoa(doc) {
            $(`input[name="${doc}"]`).on('change',function (e) {
                if($(this).data('prev')!=$(this).val()) {
                    if( ($(this).val()).length == 18 || ($(this).val()).length == 14 ) {
                        $.ajax({
                            url: '{{ route("instituicao.pessoas.getPessoa") }}',
                            method: 'POST', dataType: 'json',
                            data: { valor: $(this).val(), documento: doc, '_token': '{{ csrf_token() }}' },
                            success: function (response) {
                                if (response.status==0) {
                                    /* Se a pessoa já estiver associada à esta instituição */
                                    $(`.${doc}-campo .pessoa-alerta`).remove();
                                    $(`.${doc}-campo`).append($($('#pessoa-associada').html()));
                                    blockButtons()
                                }
                                if (response.status==1) {
                                    /* Se a pessoa não estiver associada à esta instituição */
                                    $(`.${doc}-campo .pessoa-alerta`).remove();
                                    $(`.${doc}-campo`).append($($('#pessoa-nao-associada').html()));
                                    desblockButtons()
                                }
                            }
                        })
                    }
                } else {
                    $(`.${doc}-campo .pessoa-alerta`).remove();
                }
            });
        }

        function personalidade() {
            let personalidade = $('select[name="personalidade"]').val();
            if(personalidade) {
                $('#campos-fisico-juridico').show();
                if(personalidade == 1) {
                    $('#campos-pessoa-juridica').hide();
                    $('#personalidade-selecionada').text('Pessoa Física');
                    $('#campos-pessoa-fisica').show();
                }
                if(personalidade == 2) {
                    $('#personalidade-selecionada').text('Pessoa Jurídica');
                    $('#campos-pessoa-fisica').show();
                    $('#campos-pessoa-juridica').show();
                }
            }
        }

        $('.telefone').each(function(){
            $(this).setMask('(99) 99999-9999', {
                translation: { '9': { pattern: /[0-9]/, optional: false} }
            })
        });

        $('select[name="personalidade"]').on('change', function(){
            personalidade();
        });

        personalidade();
        requestPessoa('cpf');
        requestPessoa('cnpj');


        $(".select2-simples").each(function(){
            $(this).select2({
                tags: true
            });
        });

    });

    $("#salvar").on('click', function(e){
            e.preventDefault()
            paciente_id = $('#paciente_id').val()
            agendamento_id = $("#agendamento_id").val();

            var formData = new FormData($("#formPacienteDados")[0]);
            
            $.ajax("{{route('agendamento.prontuario.pacienteUpdate', ['paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id), {
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function (result) {
                    $.toast({
                        heading: 'Sucesso',
                        text: 'Paciente editado com sucesso',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3000,
                        stack: 10
                    });
                    
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader') 
                }
            })
        })
</script>