@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Atualizar faturamento SUS',
        'breadcrumb' => ['Atualizar faturamento SUS'],
    ])
    @endcomponent


    <div class="card">
        <form id="main-form" action="{{ route('instituicao.faturamento-sus.importing') }}" method="post"
            enctype="multipart/form-data">
            <div class="card-body ">
                @method('put')
                @csrf
                <div class="row">
                    <div class="col-md-6 col-sm-8 form-group @if ($errors->has('arquivo')) has-danger @endif">
                        <label class="p-0 m-0">Arquivo de faturamento SUS<span class="text-danger">*</span></label>
                        <div class="custom-file">
                            <input type="file" name="arquivo" class="form-control custom-file-input arquivo field file-input">
                            <label class="custom-file-label file-input">Selecione o Arquivo</label>
                        </div>
                        @if ($errors->has('arquivo'))
                            <small class="form-control-feedback">{{ $errors->first('arquivo') }}</small>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-7" id="upload-em-andamento">
                        <label for="upload-progress">Progresso do upload</label>
                        <div id="upload-progress" class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0"
                                aria-valuemin="0" aria-valuemax="100">o%</div>
                        </div>
                    </div>
                    <div class="col-7" id="upload-processando" style="display: none">
                        <div class="d-flex">
                            <label for="upload-progress" class="mr-2 mb-0">Processando upload, isso pode levar alguns
                                minutos</label>
                            <div>
                                <div class="spinner-border text-primary" role="status" style="width: 1rem; height: 1rem;">
                                    <span class="sr-only">Processando...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="text-right">
                    <button type="submit" id="submit-button" class="btn btn-success waves-effect waves-light m-r-10"><i
                            class="mdi mdi-check"></i>
                        Salvar</button>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script>
        function statusHandling(status = 0) {
            switch(status) {
                default:
                case 0:
                    $("#upload-progress .progress-bar").css("width", "0%");
                    $("#upload-progress .progress-bar").text("0%");
                    $("#upload-progress .progress-bar").attr('aria-valuenow', 0);
                    $('#upload-em-andamento').show();
                    $('#upload-processando').hide();
                    $('.file-input').removeClass('disabled');
                    $('#submit-button').removeAttr('disabled');
                    break;
                case 1:
                    $('.file-input').addClass('disabled');
                    $('#submit-button').prop('disabled', true);
                    break;
                case 2:
                    $('#upload-em-andamento').hide();
                    $('#upload-processando').show();

            }
        }

        function progressHandling(event) {
            var percent = 0;
            var position = event.loaded || event.position;
            var total = event.total;
            if (event.lengthComputable) {
                percent = Math.ceil(position / total * 100);
            }

            $("#upload-progress .progress-bar").css("width", percent + "%");
            $("#upload-progress .progress-bar").text(percent + "%");
            $("#upload-progress .progress-bar").attr('aria-valuenow', percent);
            $('#upload-em-andamento').show();

            if(percent >= 99) {
                setTimeout(() => statusHandling(2), 2000);
            }
        }

        $(document).ready(() => {
            $('.custom-file-input').on('change', function(e) {
                const target = $(e.target);
                const label = $(target.parent().find('.custom-file-label'));
                // Parsing label
                let aux = target.val().split('\\');
                label.text(aux[aux.length - 1]);
            });

            $('#main-form').on('submit', (e) => {
                e.preventDefault();
                const form = $('#main-form');
                let formData = new FormData(form[0]);
                statusHandling(1);

                $.ajax(form.attr('action'), {
                    type: "POST",
                    xhr: function() {
                        let myXhr = $.ajaxSettings.xhr();
                        if (myXhr.upload) {
                            myXhr.upload.addEventListener('progress', progressHandling, false);
                        }
                        return myXhr;
                    },
                    success: function(data) {
                        $.toast({
                            heading: 'Sucesso',
                            text: 'Faturamento SUS cadastrado com sucesso!',
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: false,
                            stack: 10
                        });
                        statusHandling(0);
                    },
                    error: function(error) {
                        $.toast({
                            heading: 'Erro',
                            text: 'Não foi possível processar o arquivo!',
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: false,
                            stack: 10
                        });
                        statusHandling(0);
                        console.log(error);
                    },
                    async: true,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    timeout: 360000
                }).then(() => {
                    $('#upload-processando').hide();
                });
            });
        })
    </script>
@endpush
