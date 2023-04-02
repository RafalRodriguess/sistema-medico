<style>
    .modal.right .modal-dialog {
		position: fixed;
		margin: auto;
		width: 320px;
		height: 100%;
	}

	.modal.right .modal-content {
		height: 100%;
		overflow-y: auto;
	}
	
	.modal.right .modal-body {
		padding: 15px 15px 80px;
	}

	.modal.right.fade .modal-dialog {
		right: 0;
	}

    ul.timeline {
        list-style-type: none;
        position: relative;
    }
    ul.timeline:before {
        content: ' ';
        background: #d4d9df;
        display: inline-block;
        position: absolute;
        left: 29px;
        width: 2px;
        height: 100%;
        z-index: 400;
    }
    ul.timeline > li {
        margin: 20px 0;
        padding-left: 45px;
    }
    ul.timeline > li:before {
        content: ' ';
        background: white;
        display: inline-block;
        position: absolute;
        border-radius: 50%;
        border: 3px solid #22c0e8;
        left: 20px;
        width: 20px;
        height: 20px;
        z-index: 400;
    }
</style>

<div class="modal right fade bs-example-modal-lg" id="modalHistoricoLaudo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Histórico</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <ul class="timeline">
                    @foreach ($laudos as $item)    
                        <li class="laudo_{{$item->id}}">
                            <div style="border: 1px solid; border-radius: 30px; padding: 10px;">
                                <p><i class="fas fa-user-md"></i> {{$item->usuario->nome}}</p>
                                <p>
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                @if (date('Y-m-d', strtotime($item->created_at)) == date('Y-m-d'))    
                                                    <td>
                                                        <button type="button" class="btn btn-xs btn-secondary editar-laudo-historico" aria-haspopup="true" aria-expanded="false"
                                                            data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-original-title="Editar" style="width: 100%">
                                                            <i class="ti-pencil-alt"></i>
                                                        </button>
                                                    </td>
                                                @else
                                                    {{-- <td>
                                                        <button type="button" class="btn btn-xs btn-secondary reutilizar-laudo-historico" aria-haspopup="true" aria-expanded="false"
                                                            data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-original-title="Reutilizar" style="width: 100%">
                                                            <i class="ti-back-left"></i>
                                                        </button>
                                                    </td> --}}
                                                    {{-- @if ($item->compartilhado == 1)
                                                        <td>
                                                            <button type="button" class="btn btn-xs btn-secondary compartilhar-laudo-historico" aria-haspopup="true" aria-expanded="false"
                                                                data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-tipo="nao" data-original-title="Cancelar compartilhar" style="width: 100%">
                                                                <i class="ti-download"></i>
                                                            </button>
                                                        </td>
                                                    @else
                                                        <td>
                                                            <button type="button" class="btn btn-xs btn-secondary compartilhar-laudo-historico" aria-haspopup="true" aria-expanded="false"
                                                                data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-tipo="sim" data-original-title="Compartilhar" style="width: 100%">
                                                                <i class="ti-upload"></i>
                                                            </button>
                                                        </td>
                                                    @endif --}}
                                                @endif
                                                <td>
                                                    <a href="javascript:newPopup('{{route('agendamento.laudo.imprimirLaudo', $item)}}')">
                                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                                                data-toggle="tooltip" data-placement="top" data-original-title="Imprimir" style="width: 100%">
                                                        <i class="ti-printer"></i>
                                                    </button>
                                                    </a>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-xs btn-secondary excluir-laudo-historico" aria-haspopup="true" aria-expanded="false"
                                                        data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-original-title="Excluir" style="width: 100%">
                                                        <i class="ti-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </p>
                                <p style="text-align: right;"><small>Realizado em {{ date('d/m/Y H:i', strtotime($item->created_at) ) }}</small></p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Fechar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
    $(".editar-laudo-historico").on('click', function(e){
        e.stopPropagation()
        var laudo_id = $(this).attr('data-id');
        reutilizarLaudo = false;
        editarReutilizarLaudo(laudo_id)
    })

    $(".reutilizar-laudo-historico").on('click', function(e){
        e.stopPropagation()
        var laudo_id = $(this).attr('data-id');
        reutilizarLaudo = true;
        editarReutilizarLaudo(laudo_id)
    })

    function editarReutilizarLaudo(laudo_id)
    {
        $("#laudo_id").val('')
        // $("#obs_laudo").val('')
        $('.summernoteLaudo').summernote('code', '');

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.laudo.pacienteGetLaudo', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id', 'laudo' => 'laudo_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id).replace('laudo_id', laudo_id),
            type: 'get',
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: function(result) {
                if(reutilizarLaudo == false){
                    $("#laudo_id").val(result.id)
                }
                // $("#obs_laudo").val(result.laudo.obs)
                $('.summernoteLaudo').summernote('code', result.laudo.obs);
                $(".form-laudo").css('display', 'block');
                if(result.compartilhado == 1){
                    // $("#compartilhar_laudo").prop('checked', true);
                }else{
                    // $("#compartilhar_laudo").prop('checked', false);
                }
            },
            complete: () => {
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader') 
            },
        });
    }
    
    $(".excluir-laudo-historico").on('click', function(e){
        e.stopPropagation()
        $("#laudo_id").val('')

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();
        var laudo_id = $(this).attr('data-id');

        Swal.fire({
            title: "Excluir!",
            text: 'Deseja excluir o laudo ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $.ajax({
                    url: "{{route('agendamento.laudo.pacienteExcluirLaudo', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id', 'laudo' => 'laudo_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id).replace('laudo_id', laudo_id),
                    type: 'post',
                    data: {"_token": "{{ csrf_token() }}"},
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function(result) {
                        $(".historico-laudo").html('')
                        $(".historico-laudo").html(result)
                        $(".laudo_"+laudo_id).remove()
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') 
                    },
                });
            }
        })

        
    })

    $(".compartilhar-laudo-historico").on('click', function(e){
        e.stopPropagation()
        $("#laudo_id").val('')

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();
        var laudo_id = $(this).attr('data-id');
        var tipo = $(this).attr('data-tipo');
        var text = "";
        var toast = "";
        if(tipo == 'sim'){
            text = "Deseja compartilhar o laudo ?";
            toast = "Laudo compartilhado com sucesso!"
        }else{
            text = "Deseja não compartilhar mais o laudo ?";
            toast = "Laudo não é mais compartilhado!"
        }
        Swal.fire({
            title: "Compartilhar!",
            text: text,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $.ajax({
                    url: "{{route('agendamento.laudo.compartilharLaudo', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id', 'laudo' => 'laudo_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id).replace('laudo_id', laudo_id),
                    type: 'post',
                    data: {"_token": "{{ csrf_token() }}"},
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function(result) {
                        $.toast({
                            heading: 'Sucesso',
                            text: toast,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 9000,
                            stack: 10
                        });
                        $(".laudo-historico").find('#modalHistoricoLaudo').modal('hide')
                        setTimeout(() => {
                            atualizaHistoricoLaudo()
                        }, 500);
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') 
                    },
                });
            }
        })

        
    })
</script>