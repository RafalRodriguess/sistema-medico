<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Arquivo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($arquivos as $item)
                <tr class="arquivo_{{$item->id}}">
                    <td>{{$item->nome}}</td>
                    <td>
                        @if (\Storage::disk('public')->exists($item->diretorio))
                            <button type="button" class="btn btn-xs btn-secondary visualisar-arquivo" aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-original-title="Visualizar" >
                                <i class="ti-eye"></i>
                            </button>
                        @endif
                        <a href="{{route('agendamento.arquivo.baixarArquivo', [$paciente, $item])}}">
                            <button type="button" class="btn btn-xs btn-secondary download-arquivo" aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-original-title="Baixar" >
                                <i class="ti-download"></i>
                            </button>
                        </a>
                        <button type="button" class="btn btn-xs btn-secondary excluir-arquivo" aria-haspopup="true" aria-expanded="false"
                            data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-original-title="Excluir" >
                            <i class="ti-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip()
    })

    $(".excluir-arquivo").on('click', function(e){
        e.stopPropagation()
        e.preventDefault()
        var paciente_id = $("#paciente_id").val();
        var arquivo_id = $(this).attr('data-id');

        Swal.fire({
            title: "Excluir!",
            text: 'Deseja excluir o arquivo ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $.ajax({
                    url: "{{route('agendamento.arquivo.excluirArquivo', ['paciente' => 'paciente_id', 'arquivo' => 'arquivo_id'])}}".replace('paciente_id', paciente_id).replace('arquivo_id', arquivo_id),
                    type: "POST",
                    data: {'_token': '{{csrf_token()}}'},
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: (result) => {
                        $.toast({
                            heading: 'Sucesso',
                            text: 'Arquivo excluido com sucesso!',
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 9000,
                            stack: 10
                        });
                        $(".arquivo_"+arquivo_id).remove()
                        $(".lista-pastas").html('')
                        $(".lista-pastas").html(result)
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') 
                    }
                })
            }
        })
    })
</script>