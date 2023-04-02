@extends('comercial.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar comercial #{$comercial->id} {$comercial->nome_fantasia}",
        'breadcrumb' => [
            'Comercial' => route('comercial.comercial_loja.edit'),
            'Editar',
        ],
    ])
    @endcomponent

    <div class="card">
        <div class="card-body">
            <form action="{{ route('comercial.comercial_loja.update') }}" method="post" enctype="multipart/form-data">
                @method('put')
                @csrf

                <div class="form-group @if($errors->has('nome_fantasia')) has-danger @endif">
                    <label class="form-control-label">Nome Fantasia *</span></label>
                    <input type="text" name="nome_fantasia" value="{{ old('nome_fantasia', $comercial->nome_fantasia) }}"
                        class="form-control @if($errors->has('nome_fantasia')) form-control-danger @endif" disabled>
                    @if($errors->has('nome_fantasia'))
                        <div class="form-control-feedback">{{ $errors->first('nome_fantasia') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('cnpj')) has-danger @endif">
                    <label class="form-control-label">CNPJ *</label>
                    <input type="text" name="cnpj" alt="cnpj" value="{{ old('cnpj', $comercial->cnpj) }}"
                        class="form-control @if($errors->has('cnpj')) form-control-danger @endif" disabled>
                    @if($errors->has('cnpj'))
                        <div class="form-control-feedback">{{ $errors->first('cnpj') }}</div>
                    @endif
                </div>
                <div class="form-group @if($errors->has('razao_social')) has-danger @endif">
                    <label class="form-control-label">Razão Social *</label>
                    <input type="text" name="razao_social" value="{{ old('razao_social', $comercial->razao_social) }}"
                        class="form-control  @if($errors->has('razao_social')) form-control-danger @endif" disabled>
                    @if($errors->has('razao_social'))
                        <div class="form-control-feedback">{{ $errors->first('razao_social') }}</div>
                    @endif
                </div>
                <hr style="margin: 50px 0px 15px 0px; border: 1px dashed">
                <div class="form-group @if($errors->has('email')) has-danger @endif">
                    <label class="form-control-label">E-mail *</label>
                    <input type="email" name="email" name="example-email" value="{{ old('email', $comercial->email) }}"
                        class="form-control @if($errors->has('email')) form-control-danger @endif">
                    @if($errors->has('email'))
                        <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                    @endif
                </div>
                <div class="form-group @if($errors->has('telefone')) has-danger @endif">
                    <label class="form-control-label">Telefone *</label>
                    <input type="text" name="telefone" alt="phone" value="{{ old('telefone', $comercial->telefone) }}"
                        class="form-control  @if($errors->has('telefone')) form-control-danger @endif">
                    @if($errors->has('telefone'))
                        <div class="form-control-feedback">{{ $errors->first('telefone') }}</div>
                    @endif
                </div>
                <hr style="margin: 50px 0px 15px 0px; border: 1px dashed">
                <div class="form-group @if($errors->has('cep')) has-danger @endif">
                    <label class="form-control-label">CEP *</label>
                    <input type="text" name="cep" alt="cep" id="cep" value="{{ old('cep',$comercial->cep) }}"
                        class="form-control  @if($errors->has('cep')) form-control-danger @endif">
                    @if($errors->has('cep'))
                        <div class="form-control-feedback">{{ $errors->first('cep') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('rua')) has-danger @endif">
                    <label class="form-control-label">Rua *</label>
                    <input type="text" name="rua" id="rua" value="{{ old('rua', $comercial->rua) }}"
                        class="form-control  @if($errors->has('rua')) form-control-danger @endif">
                    @if($errors->has('rua'))
                        <div class="form-control-feedback">{{ $errors->first('rua') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('numero')) has-danger @endif">
                    <label class="form-control-label">Numero *</label>
                    <input type="text" name="numero" value="{{ old('numero', $comercial->numero) }}"
                        class="form-control  @if($errors->has('numero')) form-control-danger @endif">
                    @if($errors->has('numero'))
                        <div class="form-control-feedback">{{ $errors->first('numero') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('bairro')) has-danger @endif">
                    <label class="form-control-label">Bairro *</label>
                    <input type="text" name="bairro" id="bairro" value="{{ old('bairro', $comercial->bairro) }}"
                        class="form-control  @if($errors->has('bairro')) form-control-danger @endif">
                    @if($errors->has('bairro'))
                        <div class="form-control-feedback">{{ $errors->first('bairro') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('cidade')) has-danger @endif">
                    <label class="form-control-label">Cidade *</label>
                    <input type="text" name="cidade" id="cidade" value="{{ old('cidade', $comercial->cidade) }}"
                        class="form-control  @if($errors->has('cidade')) form-control-danger @endif">
                    @if($errors->has('cidade'))
                        <div class="form-control-feedback">{{ $errors->first('cidade') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('estado')) has-danger @endif">
                    <label class="form-control-label">Estado *</label>
                    <select class="form-control @if($errors->has('estado')) form-control-danger @endif" name="estado" id="estado" >
                        <option value="">Selecione</option>
                        <option value="AC" @if (old('estado', $comercial->estado) == 'AC')
                            selected="selected"
                        @endif>Acre</option>
                        <option value="AL" @if (old('estado', $comercial->estado) == 'AL')
                            selected="selected"
                        @endif>Alagoas</option>
                        <option value="AP" @if (old('estado', $comercial->estado) == 'AP')
                            selected="selected"
                        @endif>Amapá</option>
                        <option value="AM" @if (old('estado', $comercial->estado) == 'AM')
                            selected="selected"
                        @endif>Amazonas</option>
                        <option value="BA" @if (old('estado', $comercial->estado) == 'BA')
                            selected="selected"
                        @endif>Bahia</option>
                        <option value="CE" @if (old('estado', $comercial->estado) == 'CE')
                            selected="selected"
                        @endif>Ceará</option>
                        <option value="DF" @if (old('estado', $comercial->estado) == 'DF')
                            selected="selected"
                        @endif>Distrito Federal</option>
                        <option value="GO" @if (old('estado', $comercial->estado) == 'GO')
                            selected="selected"
                        @endif>Goiás</option>
                        <option value="ES" @if (old('estado', $comercial->estado) == 'ES')
                            selected="selected"
                        @endif>Espírito Santo</option>
                        <option value="MA" @if (old('estado', $comercial->estado) == 'MA')
                            selected="selected"
                        @endif>Maranhão</option>
                        <option value="MT" @if (old('estado', $comercial->estado) == 'MT')
                            selected="selected"
                        @endif>Mato Grosso</option>
                        <option value="MS" @if (old('estado', $comercial->estado) == 'MS')
                            selected="selected"
                        @endif>Mato Grosso do Sul</option>
                        <option value="MG" @if (old('estado', $comercial->estado) == 'MG')
                            selected="selected"
                        @endif>Minas Gerais</option>
                        <option value="PA" @if (old('estado', $comercial->estado) == 'PA')
                            selected="selected"
                        @endif>Pará</option>
                        <option value="PB" @if (old('estado', $comercial->estado) == 'PB')
                            selected="selected"
                        @endif>Paraiba</option>
                        <option value="PR" @if (old('estado', $comercial->estado) == 'PR')
                            selected="selected"
                        @endif>Paraná</option>
                        <option value="PE" @if (old('estado', $comercial->estado) == 'PE')
                            selected="selected"
                        @endif>Pernambuco</option>
                        <option value="PI" @if (old('estado', $comercial->estado) == 'PI')
                            selected="selected"
                        @endif>Piauí­</option>
                        <option value="RJ" @if (old('estado', $comercial->estado) == 'RJ')
                            selected="selected"
                        @endif>Rio de Janeiro</option>
                        <option value="RN" @if (old('estado', $comercial->estado) == 'RN')
                            selected="selected"
                        @endif>Rio Grande do Norte</option>
                        <option value="RS" @if (old('estado', $comercial->estado) == 'RS')
                            selected="selected"
                        @endif>Rio Grande do Sul</option>
                        <option value="RO" @if (old('estado', $comercial->estado) == 'RO')
                            selected="selected"
                        @endif>Rondônia</option>
                        <option value="RR" @if (old('estado', $comercial->estado) == 'RR')
                            selected="selected"
                        @endif>Roraima</option>
                        <option value="SP" @if (old('estado', $comercial->estado) == 'SP')
                            selected="selected"
                        @endif>São Paulo</option>
                        <option value="SC" @if (old('estado', $comercial->estado) == 'SC')
                            selected="selected"
                        @endif>Santa Catarina</option>
                        <option value="SE" @if (old('estado', $comercial->estado) == 'SE')
                            selected="selected"
                        @endif>Sergipe</option>
                        <option value="TO" @if (old('estado', $comercial->estado) == 'TO')
                            selected="selected"
                        @endif>Tocantins</option>
                    </select>
                    @if($errors->has('estado'))
                        <div class="form-control-feedback">{{ $errors->first('estado') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('complemento')) has-danger @endif">
                    <label class="form-control-label">Complemento</label>
                    <input type="text" name="complemento" id="complemento" value="{{ old('complemento', $comercial->complemento) }}"
                        class="form-control  @if($errors->has('complemento')) form-control-danger @endif">
                    @if($errors->has('complemento'))
                        <div class="form-control-feedback">{{ $errors->first('complemento') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('referencia')) has-danger @endif">
                    <label class="form-control-label">Referencia</label>
                    <input type="text" name="referencia" id="referencia" value="{{ old('referencia', $comercial->referencia) }}"
                        class="form-control  @if($errors->has('referencia')) form-control-danger @endif">
                    @if($errors->has('referencia'))
                        <div class="form-control-feedback">{{ $errors->first('referencia') }}</div>
                    @endif
                </div>

                <hr style="margin: 50px 0px 15px 0px; border: 1px dashed">
                <div class="form-group @if($errors->has('imagem')) has-danger @endif">
                    <label class="form-control-label">Logo</label>
                    <label style="cursor: pointer;display:block;" data-toggle="tooltip" title="Logo" >
                            <img style="display:block;cursor: pointer;margin-left:auto;  margin-right: auto;" class="rounded center" id="image"
                            @if ($comercial->logo)
                                src="{{ \Storage::cloud()->url($comercial->logo) }}"
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
                    <input type="checkbox" id="exibir" name="exibir" class="filled-in" @if ($comercial->exibir == 1)
                    checked
                @endif/>
                    <label for="exibir">Exibir Empresa<label>
                </div>

                <div class="form-group">
                    <input type="checkbox" id="cartao_credito" name="cartao_credito" class="filled-in" @if ($comercial->cartao_credito == 1)
                    checked
                    @endif/>
                    <label for="cartao_credito">Aceita forma pagamento, cartão de credito<label>
                </div>

                <div class="form-group">
                    <input type="checkbox" id="cartao_entrega" name="cartao_entrega" class="filled-in" @if ($comercial->cartao_entrega == 1)
                    checked
                    @endif/>
                    <label for="cartao_entrega">Aceita forma pagamento, cartão na hora da entrega ou retirada<label>
                </div>

                <div class="form-group">
                    <input type="checkbox" id="dinheiro" name="dinheiro" class="filled-in" @if ($comercial->dinheiro == 1)
                    checked
                    @endif/>
                    <label for="dinheiro">Aceita forma de pagamento, dinheiro na hora da entrega ou retirada<label>
                </div>

                <!-- <div class="form-group @if($errors->has('realiza_entrega')) has-danger @endif">
                    <input type="checkbox" id="realiza_entrega" name="realiza_entrega" class="filled-in" @if ($comercial->realiza_entrega == 1)
                        checked
                    @endif/>
                    <label for="realiza_entrega">Realiza Entrega<label>
                </div>

                <div class="form-group @if($errors->has('retirada_loja')) has-danger @endif">
                    <input type="checkbox" id="retirada_loja" name="retirada_loja" class="filled-in" @if ($comercial->retirada_loja == 1)
                        checked
                    @endif/>
                    <label for="retirada_loja">Retirada em loja<label>
                </div>

                <div class="form-group">
                    <input type="checkbox" id="aceita_cartao" class="filled-in" @if ($comercial->pagamento_cartao)
                    checked
                    @endif onclick="forma_pagamento()"/>
                    <label for="aceita_cartao">Aceita Cartão</label>
                </div>

                <div class="form-group pagamento_cartao @if($errors->has('pagamento_cartao')) has-danger @endif" @if (!$comercial->pagamento_cartao)
                    style="display: none;"
                @endif>
                    <label class="form-control-label">Formas pagamentos cartão</label>
                    <select name="pagamento_cartao" id="pagamento_cartao"
                        class="form-control">
                        <option value="">Selecione...</option>
                        <option value="ambos" @if(old('pagamento_cartao', $comercial->pagamento_cartao) == 'ambos') selected="selected" @endif>
                            Cartão debito e credito
                        </option>
                        <option value="debito" @if(old('pagamento_cartao', $comercial->pagamento_cartao) == 'debito') selected="selected" @endif>
                            Cartão debito
                        </option>
                        <option value="credito" @if(old('pagamento_cartao', $comercial->pagamento_cartao) == 'credito') selected="selected" @endif>
                            Cartão credito
                        </option>
                    </select>
                </div> -->

                <div class="form-group text-right">
                        {{-- <a href="{{ route('comercial.comercial_loja.index') }}">
                                <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                        </a> --}}
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

                <h5 class="modal-title">Defina a logo</h5>

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

@push('scripts')
    <script>
        $( document ).ready(function() {
            if($('#aceita_cartao').is(":checked")){
                $('.pagamento_cartao').css('display','block')
            }else{
                $('.pagamento_cartao').css('display','none')
                $('#pagamento_cartao').val('')
            }
        });

        function forma_pagamento(){
            if($('#aceita_cartao').is(":checked")){
                $('.pagamento_cartao').css('display','block')
            }else{
                $('.pagamento_cartao').css('display','none')
                $('#pagamento_cartao').val('')
            }
        }

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

                $.ajax("{{ route('comercial.comercial_loja.update') }}", {
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
    </script>
@endpush
