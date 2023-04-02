<div class="modal fade bs-example-modal-lg" id="modalUploadArquivo" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Upload</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form id="form_upload" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="form-control-label">Pasta *</label>
                            <select name="nome_pasta" id="nome_pasta"
                                class="form-control" style="width: 100%">
                                @foreach ($pastas as $pasta)
                                    <option value="{{ $pasta->slug }}">
                                        {{ $pasta->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class=" col-md-12 form-group">
                            <label class="form-control-label p-0 m-0">Nome *</label>
                            <input type="text" name="nome_arquivo" class="form-control">
                            <small>Obs: se subir mais de um arquivo tera o mesmo nome com final numerado</small>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <label for="arquivo_upload">Arquivo *</label>
                            <input type="file" name="arquivo_upload[]" id="arquivo_upload" class="dropifyUpload" multiple="multiple"/>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success waves-effect text-left salvar-upload-arquivo">Salvar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $(document).ready(function() {
        $("#nome_pasta").select2({
            placeholder: "preencha o campo para nova pasta ou selecione uma existente",
            tags: true,
        });
        $(".dropifyUpload").dropify()
    })

    $(".salvar-upload-arquivo").on('click', function(e){
        e.stopPropagation()
        e.preventDefault()
        var paciente_id = $("#paciente_id").val();

        var formData = new FormData($("#form_upload")[0])

        $.ajax({
            url: "{{route('agendamento.arquivo.novoArquvo', ['paciente' => 'paciente_id'])}}".replace('paciente_id', paciente_id),
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
                    text: 'Arquivo salvo com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                $('#modalUploadArquivo').modal('hide')
                $(".lista-pastas").html('')
                $(".lista-arquivos").html('')
                $(".lista-pastas").html(result)
            },
            complete: () => {
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
    })
</script>