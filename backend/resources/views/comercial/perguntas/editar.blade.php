@extends('comercial.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar pergunta #{$produto->id} {$produto->nome}",
        'breadcrumb' => [
            'Loja',
            'Produtos' => route('comercial.produtos.index'),
            'Pergunta' => route('comercial.produtoPerguntas.index', [$produto]),
        ],
    ])
    @endcomponent

    <div class="card">
        <div class="card-body">
            <form action="{{ route('comercial.produtoPerguntas.update', [$produto, $pergunta]) }}" method="post" enctype="multipart/form-data">
                @method('put')
                @csrf

                <div class="form-group @if($errors->has('titulo')) has-danger @endif">
                    <label class="form-control-label">Titulo *</span></label>
                    <input type="text" name="titulo" value="{{ old('titulo', $pergunta->titulo) }}"
                        class="form-control @if($errors->has('titulo')) form-control-danger @endif">
                    @if($errors->has('titulo'))
                        <div class="form-control-feedback">{{ $errors->first('titulo') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <input type="checkbox" id="obrigatorio" class="filled-in" name="obrigatorio" value="1"
                    @if ($pergunta->obrigatorio)
                        checked
                    @endif/>
                    <label for="obrigatorio">Obrigatório</label>
                </div>

                <div class="form-group @error('tipo') has-danger @enderror">
                    <label class="form-control-label">Tipo *</label>
                    <select name="tipo" id="tipo"
                        class="form-control @error('tipo') form-control-danger @enderror" onchange="tipoAlternativas()">
                            <option value="Texto" @if (old('tipo', $pergunta->tipo) == "Texto")
                                selected="selected"
                            @endif>Texto</option>
                            <option value="Escolha Simples" @if (old('tipo', $pergunta->tipo) == "Escolha Simples")
                                selected="selected"
                            @endif>Escolha Simples</option>
                            <option value="Escolha Multipla" @if (old('tipo', $pergunta->tipo) == "Escolha Multipla")
                                selected="selected"
                            @endif>Escolha Multipla</option>
                            <option value="Contador" @if (old('tipo', $pergunta->tipo) == "Contador")
                                selected="selected"
                            @endif>Contador</option>
                    </select>
                    @error('tipo')
                        <div class="form-control-feedback">{{ $message }}</div>
                    @enderror
                </div>



                <div class="quantidade">
                    <hr style="margin: 50px 0px 15px 0px; border: 1px dashed">
                    <div class="form-group contador ">
                        <label class="form-control-label quantidade_minima"></label>
                        <input type="number" name="quantidade_minima" value="{{ old('quantidade_minima', $pergunta->quantidade_minima) }}"
                            class="form-control ">

                    </div>
                    <div class="form-group contador ">
                        <label class="form-control-label quantidade_maxima"></label>
                        <input type="number" name="quantidade_maxima" value="{{ old('quantidade_maxima', $pergunta->quantidade_maxima) }}"
                            class="form-control">

                    </div>
                </div>



                <div class="alternativas alternativas_tipo">
                    <hr style="margin: 50px 0px 15px 0px; border: 1px dashed">
                    <div class="form-group" >
                        <span alt="default" class="add fas fa-plus-circle">
                            <a class="mytooltip" href="javascript:void(0)">
                                <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar Alternativas"></i>
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
                    @if ($alternativas)
                        @for ($i = 0, $max = count($alternativas); $i < $max; $i++)
                            <div class="item">
                                <div class="form-group todos @if($errors->has("alternativa.{$i}")) has-danger @endif">
                                    <label class="form-control-label">Alternativas *
                                        <a href="#" class="small remove">(remover)</a>
                                    </label>
                                    <input type="text" name="alternativa[]" value="{{ old("alternativa.{$i}", $alternativas[$i]->alternativa) }}"
                                        class="form-control @if($errors->has("alternativa.{$i}")) form-control-danger @endif">
                                    @if($errors->has("alternativa.{$i}"))
                                        <div class="form-control-feedback">{{ $errors->first("alternativa.{$i}") }}</div>
                                    @endif
                                </div>

                                <div class="form-group todos">
                                    <label class="form-control-label">Preço</label>
                                    <input type="text" name="preco[]" alt="money" value="{{ old("preco.{$i}", $alternativas[$i]->preco) }}"
                                        class="form-control ">

                                </div>

                                <div class="form-group contador ">
                                    <label class="form-control-label">Quantidade Maxima Itens</label>
                                    <input type="number" name="quantidade_maxima_itens[]" value="{{ old("quantidade_maxima_itens.{$i}", $alternativas[$i]->quantidade_maxima_itens) }}"
                                        class="form-control ">

                                </div>
                            </div>
                        @endfor
                    @else
                        <div class="item">
                            <div class="form-group todos @if($errors->has('alternativa.0')) has-danger @endif">
                                <label class="form-control-label">Alternativas *
                                    <a href="#" class="small remove">(remover)</a>
                                </label>
                                <input type="text" name="alternativa[]" value="{{ old('alternativa.0') }}"
                                    class="form-control @if($errors->has('alternativa.0')) form-control-danger @endif">
                                @if($errors->has('alternativa.0'))
                                    <div class="form-control-feedback">{{ $errors->first('alternativa.0') }}</div>
                                @endif
                            </div>

                            <div class="form-group todos">
                                <label class="form-control-label">Preço</label>
                                <input type="text" name="preco[]" alt="money" value="{{ old('preco.0') }}"
                                    class="form-control ">

                            </div>

                            <div class="form-group contador ">
                                <label class="form-control-label">Quantidade Maxima Itens</label>
                                <input type="number" name="quantidade_maxima_itens[]" value="{{ old('quantidade_maxima_itens.0') }}"
                                    class="form-control ">

                            </div>
                        </div>
                    @endif
                    @php($oldTexto = old('texto') ?: [])
                        @for($i = 1, $max = count($oldTexto); $i < $max; $i++)
                            <div class="item">
                                <div class="form-group todos @if($errors->has("alternativa{$i}")) has-danger @endif">
                                    <label class="form-control-label">Alternativas *
                                        <a href="#" class="small remove">(remover)</a>
                                    </label>
                                    <input type="text" name="alternativa[]" value="{{ old("alternativa{$i}") }}"
                                        class="form-control @if($errors->has("alternativa{$i}")) form-control-danger @endif">
                                    @if($errors->has("alternativa{$i}"))
                                        <div class="form-control-feedback">{{ $errors->first("alternativa{$i}") }}</div>
                                    @endif
                                </div>
                                <div class="form-group todos">
                                    <label class="form-control-label">Preço</label>
                                    <input type="text" name="preco[]" alt="money" value="{{ old("preco{$i}") }}"
                                        class="form-control ">

                                </div>
                                <div class="form-group contador ">
                                    <label class="form-control-label">Quantidade Maxima Itens</label>
                                    <input type="number" name="quantidade_maxima_itens[]" value="{{ old("quantidade_maxima_itens{$i}") }}"
                                        class="form-control ">

                                </div>
                            </div>
                        @endfor
                </div>

                <div class="form-group text-right">
                    <a href="{{ route('comercial.produtoPerguntas.index', [$produto]) }}">
                    <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts');
    <script>
        $( document ).ready(function() {
            tipoAlternativas();
        });

        $('.alternativas').on('click', '.add', function(){
            $('.alternativas').append($($('#item-alternativas').html()));
            tipoAlternativas();

            $('.alternativas').find('.novo_preco').attr('alt', 'money');
            $('.alternativas').find('.novo_preco').setMask();
            $('.alternativas').find('.novo_preco').removeClass('novo_preco');

            $(".selectfild2").select2();
        });

        $('.alternativas').on('click', '.item .remove', function(e){
            e.preventDefault()

            $(e.currentTarget).parents('.item').remove();
            if ($('.alternativas').find('.item').length == 0) {
                $('.alternativas').append($($('#item-alternativas').html()));

                $(".selectfild2").select2();
            }

        });

        function tipoAlternativas(){
            let tipo = $('#tipo option:selected').val()

            if(tipo == "Texto"){
                $('.alternativas_tipo').css('display','none')
                $('.quantidade').css('display', 'none')
            }else{

                $('.alternativas_tipo').css('display','block')
                if(tipo == "Contador"){
                    $('.alternativas_tipo').find('.contador').css('display', 'block');
                }else{
                    $('.alternativas_tipo').find('.contador').css('display', 'none');
                    $('.quantidade').css('display', 'none')
                }

                if(tipo == 'Contador'){
                    $('.quantidade').css('display', 'block')
                    $('.quantidade').find('.quantidade_maxima').html('Quantidade máxima de items adicionados')
                    $('.quantidade').find('.quantidade_minima').html('Quantidade minima de items adicionados')
                }
                if( tipo == 'Escolha Multipla')
                {
                    $('.quantidade').css('display', 'block')
                    $('.quantidade').find('.quantidade_maxima').html('Quantidade máxima de opções escolhidas')
                    $('.quantidade').find('.quantidade_minima').html('Quantidade minima de opções escolhidas')
                }

            }
        }



    </script>
    <script type="text/template" id="item-alternativas">
        <div class="item">
            <div class="form-group todos">
                <label class="form-control-label">Alternativas *
                    <a href="#" class="small remove">(remover)</a>
                </label>
                <input type="text" name="alternativa[]"
                    class="form-control ">

            </div>

            <div class="form-group todos ">
                <label class="form-control-label">Preço</label>
                <input type="text" name="preco[]" value="0.00"
                    class="form-control novo_preco">

            </div>

            <div class="form-group contador ">
                <label class="form-control-label">Quantidade Maxima Itens</label>
                <input type="number" name="quantidade_maxima_itens[]"
                    class="form-control ">

            </div>
        </div>
    </script>
@endpush
