@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar instituicao #{$instituicao->id} {$instituicao->nome}",
        'breadcrumb' => [
            'Instituição' => route('instituicao.instituicao_loja.edit'),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form id="form" action="{{ route('instituicao.instituicao_loja.update', [$instituicao]) }}" method="post" enctype="multipart/form-data">
                @method('put')
                @csrf

                <div class="row">
                    <div class="col-md-6 form-group @if($errors->has('nome')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Nome Fantasia *</span></label>
                        <input required type="text" name="nome" value="{{ old('nome', $instituicao->nome) }}"
                            class="form-control @if($errors->has('nome')) form-control-danger @endif">
                        @if($errors->has('nome'))
                            <div class="form-control-feedback">{{ $errors->first('nome') }}</div>
                        @endif
                    </div>

                    <div class="col-md-6 form-group @if($errors->has('razao_social')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Razão Social *</label>
                        <input type="text" name="razao_social" value="{{ old('razao_social', $instituicao->razao_social) }}"
                            class="form-control @if($errors->has('razao_social')) form-control-danger @endif">
                        @if($errors->has('razao_social'))
                            <div class="form-control-feedback">{{ $errors->first('razao_social') }}</div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2 form-group @if($errors->has('cnpj')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">CNPJ *</label>
                        <input type="text" name="cnpj" alt="cnpj" value="{{ old('cnpj', $instituicao->cnpj) }}"
                            class="form-control @if($errors->has('cnpj')) form-control-danger @endif">
                        @if($errors->has('cnpj'))
                            <div class="form-control-feedback">{{ $errors->first('cnpj') }}</div>
                        @endif
                    </div>

                    <div class="col-md-3 form-group @if($errors->has('inscricao_estadual')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Inscrição Estadual</label>
                        <input type="text" name="inscricao_estadual" value="{{ old('inscricao_estadual', $instituicao->inscricao_estadual) }}"
                            class="form-control @if($errors->has('inscricao_estadual')) form-control-danger @endif">
                        @if($errors->has('inscricao_estadual'))
                            <div class="form-control-feedback">{{ $errors->first('inscricao_estadual') }}</div>
                        @endif
                    </div>

                    <div class="col-md-3 form-group @if($errors->has('inscricao_municipal')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Inscrição Municipal *</label>
                        <input required type="text" name="inscricao_municipal" value="{{ old('inscricao_municipal', $instituicao->inscricao_municipal) }}"
                            class="form-control @if($errors->has('inscricao_municipal')) form-control-danger @endif">
                        @if($errors->has('inscricao_municipal'))
                            <div class="form-control-feedback">{{ $errors->first('inscricao_municipal') }}</div>
                        @endif
                    </div>

                    <div class="col-md-3 form-group @if($errors->has('cnes')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">CNES *</label>
                        <input type="text" name="cnes" value="{{ old('cnes', $instituicao->cnes) }}"
                            class="form-control @if($errors->has('cnes')) form-control-danger @endif">
                        @if($errors->has('cnes'))
                            <div class="form-control-feedback">{{ $errors->first('cnes') }}</div>
                        @endif
                    </div>
                    <div class="col-md-3 form-group @if($errors->has('tipo')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Tipo *</label>
                        <select name="tipo" class="form-control">
                            <?php $tipos = App\Instituicao::getTipos(); ?>
                            @foreach($tipos as $tipo)
                                @if($instituicao->tipo == $tipo)
                                    <option value="{{ $tipo }}" selected>{{ App\Instituicao::getTipoText($tipo) }}</option>
                                @endif
                                @if($instituicao->tipo != $tipo)
                                    <option value="{{ $tipo }}">{{ App\Instituicao::getTipoText($tipo) }}</option>
                                @endif
                            @endforeach
                        </select>
                        @if($errors->has('tipo'))
                            <div class="form-control-feedback">{{ $errors->first('tipo') }}</div>
                        @endif
                    </div>
                    <hr>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group @if($errors->has('ramo')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Ramo *</label>

                        <select name="ramo" class="form-control">
                            <option value="" disabled selected>Selecione</option>

                            @foreach($ramos as $ramo)
                                <option value="{{ $ramo->id }}" {{($instituicao->ramo_id == $ramo->id) ? 'selected' : ''}}>{{ $ramo->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('ramo'))
                            <div class="form-control-feedback">{{ $errors->first('ramo') }}</div>
                        @endif
                    </div>


                    <div class="col-md-5 form-group @if($errors->has('email')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">E-mail *</label>
                        <input required type="email" name="email" name="example-email" value="{{ old('email', $instituicao->email) }}"
                            class="form-control @if($errors->has('email')) form-control-danger @endif">
                        @if($errors->has('email'))
                            <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <div class="col-md-3 form-group @if($errors->has('telefone')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Telefone *</label>
                        <input required type="text" name="telefone" alt="phone" value="{{ old('telefone', $instituicao->telefone) }}"
                            class="form-control  @if($errors->has('telefone')) form-control-danger @endif">
                        @if($errors->has('telefone'))
                            <div class="form-control-feedback">{{ $errors->first('telefone') }}</div>
                        @endif
                    </div>
                </div>

                <hr style="border-top: 1px dashed rgba(0,0,0,.1)!important">

                <div class="row">
                    <div class="col-md-2 form-group @if($errors->has('cep')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">CEP *</label>
                        <input required type="text" name="cep" alt="cep" id="cep" value="{{ old('cep',$instituicao->cep) }}"
                            class="form-control  @if($errors->has('cep')) form-control-danger @endif">
                        @if($errors->has('cep'))
                            <div class="form-control-feedback">{{ $errors->first('cep') }}</div>
                        @endif
                    </div>

                    <div class="col-md-4 form-group @if($errors->has('rua')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Rua *</label>
                        <input required type="text" name="rua" id="rua" value="{{ old('rua', $instituicao->rua) }}"
                            class="form-control  @if($errors->has('rua')) form-control-danger @endif">
                        @if($errors->has('rua'))
                            <div class="form-control-feedback">{{ $errors->first('rua') }}</div>
                        @endif
                    </div>

                    <div class="col-md-2 form-group @if($errors->has('numero')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Numero *</label>
                        <input required type="text" name="numero" value="{{ old('numero', $instituicao->numero) }}"
                            class="form-control  @if($errors->has('numero')) form-control-danger @endif">
                        @if($errors->has('numero'))
                            <div class="form-control-feedback">{{ $errors->first('numero') }}</div>
                        @endif
                    </div>

                    <div class="col-md-4 form-group @if($errors->has('bairro')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Bairro *</label>
                        <input required type="text" name="bairro" id="bairro" value="{{ old('bairro', $instituicao->bairro) }}"
                            class="form-control  @if($errors->has('bairro')) form-control-danger @endif">
                        @if($errors->has('bairro'))
                            <div class="form-control-feedback">{{ $errors->first('bairro') }}</div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group @if($errors->has('cidade')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Cidade *</label>
                        <input required type="text" name="cidade" id="cidade" value="{{ old('cidade', $instituicao->cidade) }}"
                            class="form-control  @if($errors->has('cidade')) form-control-danger @endif">
                        @if($errors->has('cidade'))
                            <div class="form-control-feedback">{{ $errors->first('cidade') }}</div>
                        @endif
                    </div>

                    <div class="col-md-2 form-group @if($errors->has('estado')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Estado *</label>
                        <select required class="form-control @if($errors->has('estado')) form-control-danger @endif" name="estado" id="estado" >
                            <option value="">Selecione</option>
                            <option value="AC" @if (old('estado', $instituicao->estado) == 'AC')
                                selected="selected"
                            @endif>Acre</option>
                            <option value="AL" @if (old('estado', $instituicao->estado) == 'AL')
                                selected="selected"
                            @endif>Alagoas</option>
                            <option value="AP" @if (old('estado', $instituicao->estado) == 'AP')
                                selected="selected"
                            @endif>Amapá</option>
                            <option value="AM" @if (old('estado', $instituicao->estado) == 'AM')
                                selected="selected"
                            @endif>Amazonas</option>
                            <option value="BA" @if (old('estado', $instituicao->estado) == 'BA')
                                selected="selected"
                            @endif>Bahia</option>
                            <option value="CE" @if (old('estado', $instituicao->estado) == 'CE')
                                selected="selected"
                            @endif>Ceará</option>
                            <option value="DF" @if (old('estado', $instituicao->estado) == 'DF')
                                selected="selected"
                            @endif>Distrito Federal</option>
                            <option value="GO" @if (old('estado', $instituicao->estado) == 'GO')
                                selected="selected"
                            @endif>Goiás</option>
                            <option value="ES" @if (old('estado', $instituicao->estado) == 'ES')
                                selected="selected"
                            @endif>Espírito Santo</option>
                            <option value="MA" @if (old('estado', $instituicao->estado) == 'MA')
                                selected="selected"
                            @endif>Maranhão</option>
                            <option value="MT" @if (old('estado', $instituicao->estado) == 'MT')
                                selected="selected"
                            @endif>Mato Grosso</option>
                            <option value="MS" @if (old('estado', $instituicao->estado) == 'MS')
                                selected="selected"
                            @endif>Mato Grosso do Sul</option>
                            <option value="MG" @if (old('estado', $instituicao->estado) == 'MG')
                                selected="selected"
                            @endif>Minas Gerais</option>
                            <option value="PA" @if (old('estado', $instituicao->estado) == 'PA')
                                selected="selected"
                            @endif>Pará</option>
                            <option value="PB" @if (old('estado', $instituicao->estado) == 'PB')
                                selected="selected"
                            @endif>Paraiba</option>
                            <option value="PR" @if (old('estado', $instituicao->estado) == 'PR')
                                selected="selected"
                            @endif>Paraná</option>
                            <option value="PE" @if (old('estado', $instituicao->estado) == 'PE')
                                selected="selected"
                            @endif>Pernambuco</option>
                            <option value="PI" @if (old('estado', $instituicao->estado) == 'PI')
                                selected="selected"
                            @endif>Piauí­</option>
                            <option value="RJ" @if (old('estado', $instituicao->estado) == 'RJ')
                                selected="selected"
                            @endif>Rio de Janeiro</option>
                            <option value="RN" @if (old('estado', $instituicao->estado) == 'RN')
                                selected="selected"
                            @endif>Rio Grande do Norte</option>
                            <option value="RS" @if (old('estado', $instituicao->estado) == 'RS')
                                selected="selected"
                            @endif>Rio Grande do Sul</option>
                            <option value="RO" @if (old('estado', $instituicao->estado) == 'RO')
                                selected="selected"
                            @endif>Rondônia</option>
                            <option value="RR" @if (old('estado', $instituicao->estado) == 'RR')
                                selected="selected"
                            @endif>Roraima</option>
                            <option value="SP" @if (old('estado', $instituicao->estado) == 'SP')
                                selected="selected"
                            @endif>São Paulo</option>
                            <option value="SC" @if (old('estado', $instituicao->estado) == 'SC')
                                selected="selected"
                            @endif>Santa Catarina</option>
                            <option value="SE" @if (old('estado', $instituicao->estado) == 'SE')
                                selected="selected"
                            @endif>Sergipe</option>
                            <option value="TO" @if (old('estado', $instituicao->estado) == 'TO')
                                selected="selected"
                            @endif>Tocantins</option>
                        </select>
                        @if($errors->has('estado'))
                            <div class="form-control-feedback">{{ $errors->first('estado') }}</div>
                        @endif
                    </div>

                    <div class="col-md-6 form-group @if($errors->has('complemento')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Complemento</label>
                        <input type="text" name="complemento" id="complemento" value="{{ old('complemento', $instituicao->complemento) }}"
                            class="form-control  @if($errors->has('complemento')) form-control-danger @endif">
                        @if($errors->has('complemento'))
                            <div class="form-control-feedback">{{ $errors->first('complemento') }}</div>
                        @endif
                    </div>
                </div>


                <hr style="border-top: 1px dashed rgba(0,0,0,.1)!important">

                <div class="form-group @if($errors->has('imagem')) has-danger @endif">
                    <label class="form-control-label">Imagem</label>
                    <label style="cursor: pointer;display:block;" data-toggle="tooltip" title="Imagem" data-original-title="Mude sua logo">
                            <img style="display:block;cursor: pointer;margin-left:auto;  margin-right: auto;" class="rounded center" alt="Logo" id="image"
                            @if ($instituicao->imagem)
                                src="{{ \Storage::cloud()->url($instituicao->imagem) }}"
                            @else
                                src="{{ asset('material/assets/images/default_logo.png') }} "
                            @endif>
                            <input type="file" class='sr-only'  id="input" >

                    </label>
                    @if($errors->has('imagem'))
                        <div class="form-control-feedback">{{ $errors->first('imagem') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <input type="checkbox" id="finalizar_consultorio" name="finalizar_consultorio" class="filled-in" @if ($instituicao->finalizar_consultorio == 1)
                    checked
                    @endif value="1"/>
                    <label for="finalizar_consultorio">Finalizar antedimento quando finalizar atendimento no consultorio<label>
                </div>

                <hr style="border-top: 1px dashed rgba(0,0,0,.1)!important">


                <div class="form-group text-right">
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

                <h5 class="modal-title">Defina a imagem</h5>

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
                    aspectRatio: 4/3
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

            $("form#form").submit(function(e){
                e.preventDefault()

                var formData = new FormData($(this)[0]);
                if(blobImage){
                    formData.append('imagem', blobImage, 'imagem.jpg');
                }

                $.ajax('{{ route("instituicao.instituicao_loja.update", [$instituicao]) }}', {
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

            $('#estado').select2();
        })
    </script>
@endpush


