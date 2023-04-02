@extends('admin.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Administradores',
        'breadcrumb' => [
            'Administradores' => route('administradores.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('administradores.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="form-group @if($errors->has('nome')) has-danger @endif">
                    <label class="form-control-label">Nome *</span></label>
                    <input type="text" name="nome" value="{{ old('nome') }}"
                        class="form-control @if($errors->has('nome')) form-control-danger @endif">
                    @if($errors->has('nome'))
                        <div class="form-control-feedback">{{ $errors->first('nome') }}</div>
                    @endif
                </div>
                <div class="form-group @if($errors->has('email')) has-danger @endif">
                    <label class="form-control-label">E-mail *</label>
                    <input type="email" name="email" name="example-email" value="{{ old('email') }}"
                        class="form-control @if($errors->has('email')) form-control-danger @endif">
                    @if($errors->has('email'))
                        <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                    @endif
                </div>
                <div class="form-group @if($errors->has('cpf')) has-danger @endif">
                    <label class="form-control-label">CPF *</label>
                    <input type="text" name="cpf" alt="cpf" value="{{ old('cpf') }}"
                        class="form-control @if($errors->has('cpf')) form-control-danger @endif">
                    @if($errors->has('cpf'))
                        <div class="form-control-feedback">{{ $errors->first('cpf') }}</div>
                    @endif
                </div>
                <div class="form-group @if($errors->has('password')) has-danger @endif">
                    <label class="form-control-label">Senha *</label>
                    <input type="password" name="password"
                        class="form-control  @if($errors->has('password')) form-control-danger @endif">
                    @if($errors->has('password'))
                        <div class="form-control-feedback">{{ $errors->first('password') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('imagem')) has-danger @endif">
                    <label class="form-control-label">Foto</label>
                    <label style="cursor: pointer;display:block;" data-toggle="tooltip" title="Foto" >
                            <img style="display:block;cursor: pointer;margin-left:auto;  margin-right: auto;" class="rounded center"  id="image"

                                src="{{ asset('material/assets/images/default_photo.png') }} ">
                            <input type="file" class='sr-only' id="input" >

                    </label>

                    @if($errors->has('imagem'))
                        <div class="form-control-feedback">{{ $errors->first('imagem') }}</div>
                    @endif
                </div>

                {{-- So uma coisa que descobrir por agora, é do Laravel mais novo --}}
                {{-- @error('campo')  @enderror --}}
                {{-- @error('campo') {{ $message }}  @enderror --}}
                <div class="form-group @error('perfis_usuario_id') has-danger @enderror">
                    <label class="form-control-label">Perfil *</label>
                    <select name="perfis_usuario_id"
                        class="form-control @error('perfis_usuario_id') form-control-danger @enderror">
                        @foreach ($perfis as $perfil)
                            <option value="{{ $perfil->id }}"
                                @if(old('perfis_usuario_id') == $perfil->id) selected="selected" @endif>
                                {{ $perfil->descricao }}
                            </option>
                        @endforeach
                    </select>
                    @error('perfis_usuario_id')
                        <div class="form-control-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- <div class="button-group">
                    <button type="button" class="btn waves-effect waves-light btn-primary">Primary</button>
                    <button type="button" class="btn waves-effect waves-light btn-secondary">Secondary</button>
                    <button type="button" class="btn waves-effect waves-light btn-success">Success</button>
                    <button type="button" class="btn waves-effect waves-light btn-info">Info</button>
                    <button type="button" class="btn waves-effect waves-light btn-warning">Warning</button>
                    <button type="button" class="btn waves-effect waves-light btn-danger">Danger</button>
                </div> --}}

                <div class="form-group text-right">
                    <a href="{{ route('administradores.index') }}">
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
                    formData.append('foto', blobImage, 'imagem.jpg');
                }

                $.ajax("{{ route('administradores.store') }}", {
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
                        if(response.icon=="success"){
                            window.location="{{ route('administradores.index') }}";
                        }
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
