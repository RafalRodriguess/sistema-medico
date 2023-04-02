@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar usuário #{$instituicaoUsuario->id} {$instituicaoUsuario->nome}",
        'breadcrumb' => [
            'Usuários' => route('instituicao.instituicoes_usuarios.index'),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('instituicao.instituicoes_usuarios.update', [$instituicaoUsuario]) }}" method="post" enctype="multipart/form-data">
                @method('put')
                @csrf

                <div class="form-group @if($errors->has('cpf')) has-danger @endif">
                    <label class="form-control-label">CPF *</label>
                    <input type="text" name="cpf" alt="cpf" value="{{ old('cpf', $instituicaoUsuario->cpf) }}"
                        class="form-control @if($errors->has('cpf')) form-control-danger @endif">
                    @if($errors->has('cpf'))
                        <div class="form-control-feedback">{{ $errors->first('cpf') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('nome')) has-danger @endif">
                    <label class="form-control-label">Nome *</span></label>
                    <input type="text" name="nome" value="{{ old('nome', $instituicaoUsuario->nome) }}"
                        class="form-control @if($errors->has('nome')) form-control-danger @endif">
                    @if($errors->has('nome'))
                        <div class="form-control-feedback">{{ $errors->first('nome') }}</div>
                    @endif
                </div>
                <div class="form-group @if($errors->has('email')) has-danger @endif">
                    <label class="form-control-label">E-mail *</label>
                    <input type="email" name="email" name="example-email" value="{{ old('email', $instituicaoUsuario->email) }}"
                        class="form-control @if($errors->has('email')) form-control-danger @endif">
                    @if($errors->has('email'))
                        <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('password')) has-danger @endif">
                    <label class="form-control-label">Senha</label>
                    <input type="password" name="password"
                        class="form-control  @if($errors->has('password')) form-control-danger @endif">
                    @if($errors->has('password'))
                        <div class="form-control-feedback">{{ $errors->first('password') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('perfil_id')) has-danger @endif">
                    <label class="form-control-label">Perfil *</label>
                    <select name="perfil_id" class="form-control  @if($errors->has('perfil_id')) form-control-danger @endif">
                        <option value="" selected disabled>Selecione</option>
                        @foreach($perfil as $item)
                        <option value="{{ $item->id }}" @if(old('perfil_id', $usuario_perfil) == $item->id) selected @endif >{{ $item->nome }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('perfil_id'))
                        <div class="form-control-feedback">{{ $errors->first('perfil_id') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('desconto_maximo')) has-danger @endif">
                    <label class="form-control-label">Porcentagem de desconto maximo em orçamentos e agendamentos *</label>
                    <input type="text" alt="decimal-us" name="desconto_maximo" id="desconto_maximo" value="{{ old('desconto_maximo', $usuario_desconto) }}"
                        class="form-control @if($errors->has('desconto_maximo')) form-control-danger @endif">
                    @if($errors->has('desconto_maximo'))
                        <div class="form-control-feedback">{{ $errors->first('desconto_maximo') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('imagem')) has-danger @endif">
                    <label class="form-control-label">Foto</label>
                    <label style="cursor: pointer;display:block;" data-toggle="tooltip" title="Foto" >
                            <img style="display:block;cursor: pointer;margin-left:auto;  margin-right: auto;" class="rounded center" id="image"
                            @if ($instituicaoUsuario->foto)
                                src="{{ \Storage::cloud()->url($instituicaoUsuario->foto) }}"
                            @else
                                src="{{ asset('material/assets/images/default_photo.png') }} "
                            @endif>
                            <input type="file" class='sr-only'  id="input" >

                    </label>

                    @if($errors->has('imagem'))
                        <div class="form-control-feedback">{{ $errors->first('imagem') }}</div>
                    @endif
                </div>
                {{-- <div class="form-group @if($errors->has('instituicao_id')) has-danger @endif">
                    <label class="form-control-label">instituicoes</label>
                    <select name="instituicao_id"
                        class="form-control @if($errors->has('instituicao_id')) form-control-danger @endif">
                        @foreach ($instituicao as $instituicoes)
                            <option value="{{ $instituicoes->id }}"
                                @if(old('instituicao_id', $instituicaoUsuario->instituicao_id) == $instituicoes->id) selected="selected" @endif>
                                {{ $instituicoes->nome_fantasia }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('instituicao_id'))
                        <div class="form-control-feedback">{{ $errors->first('instituicao_id') }}</div>
                    @endif
                </div> --}}

                <div class="form-group text-right">
                        <a href="{{ route('instituicao.instituicoes_usuarios.index') }}">
                                <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                        </a>
                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal inmodal" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>

                <h5 class="modal-title">Defina a foto</h5>

            </div>
            <div class="modal-body">
                <div >
                <img style="max-width: 100%;" id="imageModal" src="">
                </div>
            </div>
            <div class="modal-footer">

                <button type="button" style='margin:0;' class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="crop">Definir</button>

            </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts');
    <script>

        $( document ).ready(function() {
            var blobImage;
            var input= document.getElementById('input');
            var image= document.getElementById('image');
            var imageModal= document.getElementById('imageModal');

            input.addEventListener('change',function(e){
                var files = e.target.files;
                var done = function (url) {
                input.value = '';
                imageModal.src = url;
                $('#modal').modal('show');
                };
                var reader;
                var file;
                var url;

                if (files && files.length > 0) {
                file = files[0];

                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function (e) {
                    done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
                }
            });

            $('#modal').on('shown.bs.modal', function () {
                cropper = new Cropper(imageModal,{
                    aspectRatio: 4 / 3
                });
                }).on('hidden.bs.modal', function () {
                cropper.destroy();
                cropper = null;
            });

            document.getElementById('crop').addEventListener('click', function () {
                var initialAvatarURL;
                var canvas;

                if (cropper) {
                    canvas = cropper.getCroppedCanvas({
                        width: 300,
                        height: 300,
                    });
                    initialAvatarURL = image.src;
                    image.src = canvas.toDataURL();

                    canvas.toBlob(function (blob) {
                        blobImage = blob;
                    });
                }
                $('#modal').modal('hide');
            });

            $("form").submit(function(e){
                e.preventDefault()

                var formData = new FormData($(this)[0]);

                if(blobImage){
                    formData.append('imagem', blobImage, 'imagem.jpg');
                }

                $.ajax("{{ route('instituicao.instituicoes_usuarios.update', [$instituicaoUsuario]) }}", {
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        $.toast({
                            heading: response.title,
                            text: response.text,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: response.icon,
                            hideAfter: 3000,
                            stack: 10
                        });
                    },
                    error: function (response) {
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
            });


        })
    </script>
@endpush
