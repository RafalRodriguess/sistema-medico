
@extends('comercial.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar produto #{$produto->id} {$produto->nome}",
        'breadcrumb' => [
            'Produtos' => route('comercial.produtos.index'),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('comercial.produtos.update', [$produto]) }}" method="post" enctype="multipart/form-data">
                @method('put')
                @csrf

                <div class="form-group @if($errors->has('nome')) has-danger @endif">
                    <label class="form-control-label">Nome *</span></label>
                    <input type="text" name="nome" value="{{ old('nome', $produto->nome) }}"
                        class="form-control @if($errors->has('nome')) form-control-danger @endif">
                    @if($errors->has('nome'))
                        <div class="form-control-feedback">{{ $errors->first('nome') }}</div>
                    @endif
                </div>



                <div class="form-group @if($errors->has('preco')) has-danger @endif">
                    <label class="form-control-label">Preço *</label>
                    <input type="text" name="preco" alt="money" value="{{ old('preco', $produto->preco) }}"
                        class="form-control @if($errors->has('preco')) form-control-danger @endif">
                    @if($errors->has('preco'))
                        <div class="form-control-feedback">{{ $errors->first('preco') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('breve_descricao')) has-danger @endif">
                    <label class="form-control-label">Breve Descrição *</label>
                    <input type="text" name="breve_descricao" value="{{ old('breve_descricao', $produto->breve_descricao) }}"
                        class="form-control @if($errors->has('breve_descricao')) form-control-danger @endif">
                    @if($errors->has('breve_descricao'))
                        <div class="form-control-feedback">{{ $errors->first('breve_descricao') }}</div>
                    @endif
                </div>
                <div class="form-group @if($errors->has('descricao_completa')) has-danger @endif">
                    <label class="form-control-label">Descrição *</label>
                <textarea name="descricao_completa" cols="30" rows="5" class="form-control  @if($errors->has('descricao_completa')) form-control-danger @endif">{{$produto->descricao_completa}}</textarea>
                    @if($errors->has('descricao_completa'))
                        <div class="form-control-feedback">{{ $errors->first('descricao_completa') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('imagem')) has-danger @endif">
                    <label class="form-control-label">Imagem *</label>
                    <label style="cursor: pointer;display:block;" data-toggle="tooltip" title="Imagem" >
                            <img style="display:block;cursor: pointer;margin-left:auto;  margin-right: auto;" class="rounded center" id="image"
                            @if ($produto->imagem)
                                src="{{ \Storage::cloud()->url($produto->imagem) }}"
                            @else
                                src="{{ asset('material/assets/images/default_image.png') }} "
                            @endif>
                            <input type="file" class='sr-only'  id="input" >

                    </label>

                    @if($errors->has('imagem'))
                        <div class="form-control-feedback">{{ $errors->first('imagem') }}</div>
                    @endif
                </div>
                <hr style="margin: 50px 0px 15px 0px; border: 1px dashed">
                {{-- So uma coisa que descobrir por agora, é do Laravel mais novo --}}
                {{-- @error('campo')  @enderror --}}
                {{-- @error('campo') {{ $message }}  @enderror --}}
                <div class="form-group @error('categoria_id') has-danger @enderror">
                    <label class="form-control-label">Categoria *</label>
                    <select name="categoria_id" id="categoria_id" onchange="getsubcategoria()"
                        class="form-control select2 multiple @error('categoria_id') form-control-danger @enderror" >
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}"
                                @if(old('categoria_id', $produto->categoria_id) == $categoria->id) selected="selected" @endif>
                                {{ $categoria->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria_id')
                        <div class="form-control-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group @error('sub_categoria_id') has-danger @enderror">
                    <label class="form-control-label">Sub Categoria</label>
                    <select name="sub_categoria_id" id="sub_categoria_id"
                        class="form-control selectfild2 @error('sub_categoria_id') form-control-danger @enderror">
                            <option value="">Selecione...</option>
                            @if($sub_categorias)
                                @foreach ($sub_categorias as $sub_categoria)
                                    <option value="{{ $sub_categoria->id }}"
                                            @if(old('sub_categoria_id', $produto->sub_categoria_id) === $sub_categoria->id) selected="selected" @endif>
                                        {{ $sub_categoria->nome }}
                                    </option>
                                @endforeach
                            @endif
                    </select>
                    @error('sub_categoria_id')
                        <div class="form-control-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group @error('marca') has-danger @enderror">
                    <label class="form-control-label">Marca</label>
                    <select name="marca" id="marca"
                        class="form-control select2 @error('marca') form-control-danger @enderror">
                            <option value="{{old('marca')}}"></option>
                            @foreach ($marcas as $marca)
                                <option value="{{ $marca->slug }}"
                                    @if ($produto->marcas)
                                        @if(old('marca', $produto->marcas->slug) === $marca->slug) selected="selected" @endif
                                    @endif
                                    >
                                    {{ $marca->nome }}
                                </option>
                            @endforeach
                    </select>
                    @error('marca')
                        <div class="form-control-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <hr style="margin: 50px 0px 15px 0px; border: 1px dashed">
                <div class="form-group @error('tipo_produto') has-danger @enderror">
                    <label class="form-control-label">Tipo *</label>
                    <select name="tipo_produto" id="tipo_produto"
                        class="form-control @error('tipo_produto') form-control-danger @enderror" onchange="tipoproduto()">
                            <option value="medicamento" @if (old('tipo_produto', $produto->tipo_produto) == "medicamento")
                                selected="selected"
                            @endif>Medicamento</option>
                            <option value="outro" @if (old('tipo_produto', $produto->tipo_produto) == "outro")
                                selected="selected"
                            @endif>Produto</option>
                    </select>
                    @error('tipo_produto')
                        <div class="form-control-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="composicao medicamento_tipo">
                    <div class="form-group" >
                        <span alt="default" class="add fas fa-plus-circle">
                            <a class="mytooltip" href="javascript:void(0)">
                                <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar Composição. Obs: campos em selecione não seram adicionados"></i>
                                {{-- <span class="tooltip-content5">
                                    <span class="tooltip-text3">
                                        <span class="tooltip-inner2">
                                        Adicionar Composição. Obs: campos em selecione não seram adicionados
                                        </span>
                                    </span>
                                </span> --}}
                            </a>
                        </span>
                    </div>
                    @if ($produto->tipo_produto == "medicamento")
                        @for ($i = 0, $max = count($componentes); $i < $max; $i++)
                            <div class="item">
                                <div class="@if($errors->has("composicao.{$i}")) has-danger @endif">
                                    <div class="form-group" >
                                        <label class="form-control-label">
                                            Composição *
                                            <a href="#" class="small remove">(remover)</a>
                                        </label>
                                        <select class="form-control select2 @error("composicao.{$i}") form-control-danger @enderror" name="composicao[]">
                                            <option value="">Selecione</option>
                                            @foreach ($medicamentos as $medicamento)
                                                <option value="{{$medicamento->id}}"
                                                        @if(old("composicao.{$i}", $componentes[$i]->id) == $medicamento->id) selected @endif>
                                                    {{$medicamento->componente}}
                                                    {{$medicamento->quantidade}}{{$medicamento->unidade}}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error("composicao.{$i}")
                                            <div class="form-control-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="@if($errors->has("quantidade.{$i}")) has-danger @endif">
                                    <div class="form-group" >
                                        <label class="form-control-label">
                                            Quantidade *
                                        </label>
                                        <input type="text" alt="money" name="quantidade[]" value="{{ old("quantidade.{$i}", $componentes[$i]->pivot->quantidade) }}"
                                            class="form-control @if($errors->has('quantidade')) form-control-danger @endif">
                                        @error('quantidade.{$i}')
                                            <div class="form-control-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="@if($errors->has("unidade.{$i}")) has-danger @endif">
                                    <div class="form-group" >
                                        <label class="form-control-label">
                                            Unidade *
                                        </label>
                                        <input type="text" name="unidade[]" value="{{ old("unidade.{$i}", $componentes[$i]->pivot->unidade) }}"
                                            class="form-control @if($errors->has('unidade')) form-control-danger @endif">
                                        @error('unidade.{$i}')
                                            <div class="form-control-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endfor
                    @else
                        <div class="item">
                            <div class="@if($errors->has("composicao.0")) has-danger @endif">
                                <div class="form-group" >
                                    <label class="form-control-label">
                                        Composição *
                                        <a href="#" class="small remove">(remover)</a>
                                    </label>
                                    <select class="form-control select2 @error("composicao.0") form-control-danger @enderror" name="composicao[]">
                                        <option value="">Selecione</option>
                                        @foreach ($medicamentos as $medicamento)
                                            <option value="{{$medicamento->id}}"
                                                    @if(old('composicao.0') == $medicamento->id) selected @endif>
                                                {{$medicamento->componente}}
                                                {{$medicamento->quantidade}}{{$medicamento->unidade}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('composicao.0')
                                        <div class="form-control-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="@if($errors->has("quantidade.0")) has-danger @endif">
                                <div class="form-group" >
                                    <label class="form-control-label">
                                        Quantidade *
                                    </label>
                                    <input type="text" alt="money" name="quantidade[]" value="{{ old('quantidade') }}"
                                        class="form-control @if($errors->has('quantidade')) form-control-danger @endif">
                                    @error('quantidade.0')
                                        <div class="form-control-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="@if($errors->has("unidade.0")) has-danger @endif">
                                <div class="form-group" >
                                    <label class="form-control-label">
                                        Quantidade *
                                    </label>
                                    <input type="text" name="unidade[]" value="{{ old("unidade.0") }}"
                                        class="form-control @if($errors->has('unidade')) form-control-danger @endif">
                                    @error('unidade.0')
                                        <div class="form-control-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endif

                    @php($oldComposicoes = old('composicao') ?: [])
                    @for($i = 1, $max = count($oldComposicoes); $i < $max; $i++)
                        <div class="item">
                            <div class="@if($errors->has("composicao.{$i}")) has-danger @endif">
                                <div class="form-group" >
                                    <label class="form-control-label @error("composicao.{$i}") form-control-danger @enderror">
                                        Composição *
                                        <a href="#" class="small remove">(remover)</a>
                                    </label>
                                    <select class="form-control select2" name="composicao[]">
                                        <option value="">Selecione</option>
                                        @foreach ($medicamentos as $medicamento)
                                            <option value="{{$medicamento->id}}"
                                                    @if(old("composicao.{$i}") == $medicamento->id) selected @endif>
                                                {{$medicamento->componente}}
                                                {{$medicamento->quantidade}}{{$medicamento->unidade}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error(old("composicao.{$i}"))
                                        <div class="form-control-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="@if($errors->has("quantidade.{$i}")) has-danger @endif">
                                <div class="form-group" >
                                    <label class="form-control-label">
                                        Quantidade *
                                        <a href="#" class="small remove">(remover)</a>
                                    </label>
                                    <input type="text" alt="money" name="quantidade[]" value="{{ old("quantidade.{$i}") }}"
                                        class="form-control @if($errors->has('quantidade')) form-control-danger @endif">
                                    @error("quantidade.{$i}")
                                        <div class="form-control-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="@if($errors->has("unidade.{$i}")) has-danger @endif">
                                <div class="form-group" >
                                    <label class="form-control-label">
                                        Unidade *
                                        <a href="#" class="small remove">(remover)</a>
                                    </label>
                                    <input type="text" name="unidade[]" value="{{ old("unidade.{$i}") }}"
                                        class="form-control @if($errors->has('unidade')) form-control-danger @endif">
                                    @error("unidade.{$i}")
                                        <div class="form-control-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                <div class="form-group medicamento_tipo">
                    <label class="form-control-label">Tarja</label>
                    <select name="tarja" id="tarja"
                        class="form-control">
                            <option value="mpis" @if (old('tarja', $produto->tarja) == 'mpis')
                                selected
                            @endif>MPIs</option>
                            {{-- <option value="amarela" @if ($produto->tarja == 'amarela')
                                selected
                            @endif>Amarela</option> --}}
                            <option value="preta" @if (old('tarja', $produto->tarja) == 'preta')
                                selected
                            @endif>Preta</option>
                            <option value="vermelha" @if (old('tarja', $produto->tarja) == 'vermelha')
                                selected
                            @endif>Vermelha</option>
                    </select>
                </div>

                <div class="form-group medicamento_tipo">
                    <input type="checkbox" id="generico" class="filled-in" name="generico" @if ($produto->generico == 1)
                        checked
                    @endif/>
                    <label for="generico">Generico</label>
                </div>

                <div class="form-group medicamento_tipo @if($errors->has('nome_farmaceutico')) has-danger @endif">
                    <label class="form-control-label">Nome farmaceutico *</label>
                    <input type="text" name="nome_farmaceutico" value="{{ old('nome_farmaceutico', $produto->nome_farmaceutico) }}"
                        class="form-control @if($errors->has('nome_farmaceutico')) form-control-danger @endif">
                    @if($errors->has('nome_farmaceutico'))
                        <div class="form-control-feedback">{{ $errors->first('nome_farmaceutico') }}</div>
                    @endif
                </div>

                {{-- <div class="form-group @if($errors->has('comercial_id')) has-danger @endif">
                    <label class="form-control-label">comerciais</label>
                    <select name="comercial_id"
                        class="form-control @if($errors->has('comercial_id')) form-control-danger @endif">
                        @foreach ($comercial as $comerciais)
                            <option value="{{ $comerciais->id }}"
                                @if(old('comercial_id', $produto->comercial_id) == $comerciais->id) selected="selected" @endif>
                                {{ $comerciais->nome_fantasia }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('comercial_id'))
                        <div class="form-control-feedback">{{ $errors->first('comercial_id') }}</div>
                    @endif
                </div> --}}

                <div class="form-group text-right">
                        <a href="{{ route('comercial.produtos.index') }}">
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

@push('scripts');
    <script>
        $( document ).ready(function() {
            tipoproduto();
            // getsubcategoria();
            $("#marca").select2({
                tags: true
            });
        });

        $('.composicao').on('click', '.add', function(){
            $('.composicao').append($($('#item-composicao').html()));

            $('.composicao').find('.quantidade_mask').attr('alt', 'money');
            $('.composicao').find('.quantidade_mask').setMask();
            $('.composicao').find('.quantidade_mask').removeClass('quantidade_mask');
            $(".selectfild2").select2();
        });

        $('.composicao').on('click', '.item .remove', function(e){
            e.preventDefault()

            $(e.currentTarget).parents('.item').remove();
            if ($('.composicao').find('.item').length == 0) {
                $('.composicao').append($($('#item-composicao').html()));
                $(".selectfild2").select2();
            }
        });

        function tipoproduto(){
            let produto = $('#tipo_produto option:selected').val()

            if(produto == "medicamento"){
                $('.medicamento_tipo').css('display','block')
            }else{
                $('.medicamento_tipo').css('display','none')
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

                $.ajax("{{ route('comercial.produtos.update', [$produto]) }}", {
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


        function getsubcategoria(){
            let categoria = $('#categoria_id option:selected').val();

            $.ajax({
                type: "GET",
                data: {categoria: categoria},
                url: "{{route('comercial.getsubcategorias')}}",
                datatype: "json",
                success: function(sub_categoria) {
                    if(sub_categoria != null){
                        subcategoria = JSON.parse(sub_categoria);
                        var options = $('#sub_categoria_id');
                        options.find('option').filter(':not([value=""])').remove();
                        $.each(subcategoria, function (key, value) {
                                    // $('<option').val(value.id).text(value.Nome).appendTo(options);
                                    options.append('<option value='+value.id+'>'+value.nome+'</option>')
                            //options += '<option value="' + key + '">' + value + '</option>';
                        });
                    }
                }

            });
        }
    </script>
    <script type="text/template" id="item-composicao">
        <div class="item">
            <div>
                <div class="form-group" >
                    <label class="form-control-label">
                        Composição *
                        <a href="#" class="small remove">(remover)</a>
                    </label>
                    <select class="form-control selectfild2" name="composicao[]">
                        <option value="">Selecione</option>
                        @foreach ($medicamentos as $medicamento)
                            <option value="{{$medicamento->id}}">{{$medicamento->componente}} {{$medicamento->quantidade}} {{$medicamento->unidade}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <div class="form-group" >
                    <label class="form-control-label">
                        Quantidade *

                    </label>
                    <input type="text" alt="money" name="quantidade[]"
                        class="form-control quantidade_mask">
                </div>
            </div>

            <div>
                <div class="form-group" >
                    <label class="form-control-label">
                        Unidade *

                    </label>
                    <input type="text" name="unidade[]"
                        class="form-control">
                </div>
            </div>
        </div>
    </script>
@endpush
