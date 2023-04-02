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

<div class="modal right fade bs-example-modal-lg" id="modalHistoricoSolicitacao" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Histórico</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <div class="modal-body">
                <ul class="timeline">
                    @foreach ($solicitacoes as $item)    
                        <li class="solicitacao_{{$item->id}}">
                            <div style="border: 1px solid; border-radius: 30px; padding: 10px;">
                                {{-- <p><i class="fas fa-user-md"></i> {{$item->usuario->nome}}</p> --}}
                                <p>
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                @if (date('Y-m-d', strtotime($item->created_at)) == date('Y-m-d'))    
                                                    <td>
                                                        <button type="button" class="btn btn-xs btn-secondary editar-solicitacao-historico" aria-haspopup="true" aria-expanded="false"
                                                            data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-original-title="Editar" style="width: 100%">
                                                            <i class="ti-pencil-alt"></i>
                                                        </button>
                                                    </td>
                                                @else
                                                    
                                                @endif
                                                
                                                @if ($item->atendido == 0)    
                                                    <td>
                                                        <button type="button" class="btn btn-xs btn-secondary excluir-solicitacao-historico" aria-haspopup="true" aria-expanded="false"
                                                            data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-original-title="Excluir" style="width: 100%">
                                                            <i class="ti-trash"></i>
                                                        </button>
                                                    </td>
                                                @endif
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
    $(".editar-solicitacao-historico").on('click', function(e){
        e.stopPropagation()
        var solicitacao_id = $(this).attr('data-id');
        reutilizarSolicitacao = false;
        editarReutilizarSolicitacao(solicitacao_id)
    })

    function editarReutilizarSolicitacao(solicitacao_id)
    {
        $("#solicitacao_id").val('')
        // $("#obs_laudo").val('')
        
        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.internacoes.estoque.getSolicitacao', ['solicitacao' => 'solicitacao_id'])}}".replace('solicitacao_id', solicitacao_id),
            type: 'get',
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: function(result) {
                if(reutilizarSolicitacao == false){
                    $("#solicitacao_id").val(result.id)
                }

                console.log(result)
                $('#estoque-origem-select').val(result.estoque_origem_id).change();
                $('#agendamento_atendimentos_id').val(result.agendamento_atendimentos_id);
                $('#agendamento_atendimentos_nome').val("#"+result.atendimento.agendamento_id+" "+result.atendimento.agendamento.data+" - "+result.atendimento.pessoa.nome);
                $('#observacoes').val(result.observacoes);
                $('#prestador-select').val(result.instituicoes_prestadores_id).change();
                $("#urgente").prop(':checked', result.urgente);
                $("#produtos-container").html('');
                produtos_selecionados = [];
                for(i = 0; i < result.solicitacao_estoque_produtos.length; i++){
                    console.log('aqui')
                    var produtos = result.solicitacao_estoque_produtos[i];
                    
                    elemento = $($('#produto-template').html());
                    elemento.attr('id', `entrada-produto-${produtos.produto.id}`);
                    elemento.find('.produto-id').val(produtos.produto.id);
                    elemento.find('.produto-id').attr('name', elemento.find('.produto-id').attr('name').replace('#', i));
                    elemento.find('.quantidade-input').attr('name', elemento.find('.quantidade-input').attr('name').replace('#', i));
                    elemento.find('.quantidade-input').val(produtos.produto.quantidade ?? 1);
                    elemento.find('.name').text(produtos.produto.descricao);
                    elemento.find('.classe').text((produtos.produto.classe.descricao));
                    elemento.find('.unidade').text(produtos.produto.unidade.descricao);
                    elemento.find('.button-remove').attr('onclick', `removeProduct(${produtos.produto.id})`);
                    $('#produtos-container').append(elemento);
                }
                // $(".form-avaliacao").css('display', 'block');
                $("#modalHistoricoSolicitacao").modal('hide');
            },
            complete: () => {
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader') 
            },
        });
    }
    
    $(".excluir-solicitacao-historico").on('click', function(e){
        e.stopPropagation()
        $("#solicitacao_id").val('')

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();
        var solicitacao_id = $(this).attr('data-id');

        Swal.fire({
            title: "Excluir!",
            text: 'Deseja excluir a solicitação de estoque ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $.ajax({
                    url: "{{route('agendamento.internacoes.estoque.deleteSolicitacao', ['solicitacao' => 'solicitacao_id'])}}".replace('solicitacao_id', solicitacao_id),
                    type: 'post',
                    data: {"_token": "{{ csrf_token() }}"},
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function(result) {
                        $(".solicitacao-historico").html('')
                        $(".solicitacao-historico").html(result)
                        atualizaHistoricoSolicitacao();
                        $("#modalHistoricoSolicitacao").modal('hide');
                        $('.modal-backdrop').css('display', 'none');
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