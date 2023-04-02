
<div class="row">

    <div class="col-md-4 lista-pastas">
        @include('instituicao.prontuarios.arquivos.lista_pasta')
    </div>
    
    <div class="col-md-8 lista-arquivos">

    </div>
</div>

<div id="modal_arquivo_visulizar"></div>
<div class="modal_visualizar_documento"></div>

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip()
    })

    function addArquivo(id){
        var paciente_id = $("#paciente_id").val();

        var url = "{{ route('agendamento.arquivo.getModalUpload', ['paciente' => 'paciente_id']) }}".replace('paciente_id', paciente_id);
        var data = {
            '_token': '{{csrf_token()}}'
        };
        var modal = 'modalUploadArquivo';
        
        $('#loading').removeClass('loading-off');
        $('#modal_arquivo_visulizar').load(url, data, function(resposta, status) {
            $('#' + modal).modal();
            if(id != null){
                $('#'+modal).find('#nome_pasta').val(id).change()
            }
            $('#loading').addClass('loading-off');
        });
    
    }

    $(".lista-arquivos").on('click', '.visualisar-arquivo', function() {
        var paciente_id = $("#paciente_id").val();
        var arquivo_id = $(this).attr('data-id');
        console.log(arquivo_id)
        var url = "{{ route('agendamento.arquivo.visualizarArquivo', ['paciente' => 'paciente_id', 'arquivo' => 'arquivo_id']) }}".replace('paciente_id', paciente_id).replace('arquivo_id', arquivo_id);
        var data = {
            '_token': '{{csrf_token()}}'
        };
        var modal = 'modal-view-documento';
        
        $('#loading').removeClass('loading-off');
        $('.modal_visualizar_documento').load(url, data, function(resposta, status) {
            $('#' + modal).modal();
            $('#loading').addClass('loading-off');
        });
    });
</script>