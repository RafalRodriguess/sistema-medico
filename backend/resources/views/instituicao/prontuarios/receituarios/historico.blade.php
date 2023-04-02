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

@if (count($receituarios) > 0)
    <input type="hidden" name="receituario_id_editar" id="receituario_id_editar" value="{{$receituarios[0]->id}}">
@endif
<div class="modal right fade bs-example-modal-lg" id="modalHistoricoReceituario" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Histórico</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                @if (count($receituarios) > 0)
                    <ul class="timeline">
                        @foreach ($receituarios as $item)    
                            <li class="receituario_{{$item->id}}">
                                <div style="border: 1px solid; border-radius: 30px; padding: 10px;">
                                    <p><i class="fas fa-user-md"></i> {{$item->usuario->nome}}</p>
                                    <p>
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    @if (date('Y-m-d', strtotime($item->created_at)) == date('Y-m-d'))  
                                                        <td>
                                                            <button type="button" class="btn btn-xs btn-secondary editar-receituario-historico" aria-haspopup="true" aria-expanded="false"
                                                                data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-original-title="Editar" style="width: 100%">
                                                                <i class="ti-pencil-alt"></i>
                                                            </button>
                                                        </td>
                                                    @else
                                                        <td>
                                                            <button type="button" class="btn btn-xs btn-secondary reutilizar-receituario-historico" aria-haspopup="true" aria-expanded="false"
                                                                data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-original-title="Reutilizar" style="width: 100%">
                                                                <i class="ti-back-left"></i>
                                                            </button>
                                                        </td>
                                                        {{-- @if ($item->compartilhado == 1)
                                                            <td>
                                                                <button type="button" class="btn btn-xs btn-secondary compartilhar-receituario-historico" aria-haspopup="true" aria-expanded="false"
                                                                    data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-tipo="nao" data-original-title="Cancelar compartilhar" style="width: 100%">
                                                                    <i class="ti-download"></i>
                                                                </button>
                                                            </td>
                                                        @else
                                                            <td>
                                                                <button type="button" class="btn btn-xs btn-secondary compartilhar-receituario-historico" aria-haspopup="true" aria-expanded="false"
                                                                    data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-tipo="sim" data-original-title="Compartilhar" style="width: 100%">
                                                                    <i class="ti-upload"></i>
                                                                </button>
                                                            </td>
                                                        @endif --}}
                                                    @endif
                                                    <td>
                                                        <a href="javascript:newPopup('{{route('agendamento.receituario.imprimirReceituario', $item)}}')">
                                                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                                                    data-toggle="tooltip" data-placement="top" data-original-title="Imprimir" style="width: 100%">
                                                            <i class="ti-printer"></i>
                                                        </button>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-xs btn-secondary excluir-receituario-historico" aria-haspopup="true" aria-expanded="false"
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
                @endif
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
    $(".editar-receituario-historico").on('click', function(e){
        e.stopPropagation()
        reutilizarReceituario = false;
        $("#modelo_receituario").val("").change()
        var receituario_id = $(this).attr('data-id');
        callAjaxEditarReutilizar(receituario_id)
    })

    function callAjaxEditarReutilizar(receituario_id){
        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.receituario.pacienteGetReceituario', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id', 'receituario' => 'receituario_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id).replace('receituario_id', receituario_id),
            type: 'get',
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: function(result) {
                editarReceituario(result);
            },
            complete: () => {
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader') 
            },
        });
    }
    
    $(".reutilizar-receituario-historico").on('click', function(e){
        e.stopPropagation()
        reutilizarReceituario = true;
        $("#modelo_receituario").val("").change()
        var receituario_id = $(this).attr('data-id');
        callAjaxEditarReutilizar(receituario_id)
    })
    
    $(".excluir-receituario-historico").on('click', function(e){
        e.stopPropagation()

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();
        var receituario_id = $(this).attr('data-id');

        Swal.fire({
            title: "Excluir!",
            text: 'Deseja excluir o receituario ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $.ajax({
                    url: "{{route('agendamento.receituario.pacienteExcluirReceituario', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id', 'receituario' => 'receituario_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id).replace('receituario_id', receituario_id),
                    type: 'post',
                    data: {"_token": "{{ csrf_token() }}"},
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function(result) {
                        $(".receituario_"+receituario_id).remove()
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') 
                    },
                });
            }
        })

        
    })

    $(".compartilhar-receituario-historico").on('click', function(e){
        e.stopPropagation()

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();
        var receituario_id = $(this).attr('data-id');
        var tipo = $(this).attr('data-tipo');
        var text = "";
        var toast = "";
        if(tipo == 'sim'){
            text = "Deseja compartilhar o receituario ?";
            toast = "Receituário compartilhado com sucesso!"
        }else{
            text = "Deseja não compartilhar mais o receituario ?";
            toast = "Receituário não é mais compartilhado!"
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
                    url: "{{route('agendamento.receituario.compartilharReceituario', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id', 'receituario' => 'receituario_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id).replace('receituario_id', receituario_id),
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
                        $(".receituario-historico").find('#modalHistoricoReceituario').modal('hide')
                        setTimeout(() => {
                            atualizaHistoricoReceituario()
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