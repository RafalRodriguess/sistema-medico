@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => "Editar Produtos #{$produto->id} {$produto->descricao}",
        'breadcrumb' => [
            'Produto' => route('instituicao.produtos.index'),
            'Atualização',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.produtos.update', [$produto]) }}" method="post">
                @method('put')
                @csrf

                <div class="row">
                    <div class=" col-md-12 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição<span class="text-danger">*</span></label>
                        <input type="text" name="descricao" value="{{ old('descricao', $produto->descricao) }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-6 form-group @if($errors->has('unidade_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Unidade<span class="text-danger">*</span></label>
                        <select class="form-control select2 @if($errors->has('unidade_id')) form-control-danger @endif" name="unidade_id" id="unidade_id" required style="width: 100%">
                            <option value="">Selecione</option>
                            @foreach($unidades as $unidade)
                            <option value="{{ $unidade->id }}" @if(old('unidade_id', $produto->unidade_id)==$unidade->id) selected @endif >{{ $unidade->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('unidade_id'))
                            <small class="form-control-feedback">{{ $errors->first('unidade_id') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-6 form-group @if($errors->has('classe_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Classe<span class="text-danger">*</span></label>
                        <select class="form-control select2 @if($errors->has('classe_id')) form-control-danger @endif" name="classe_id" id="classe_id" required style="width: 100%">
                            <option value="">Selecione</option>
                            @foreach($classes as $classe)
                                <option value="{{ $classe->id }}" @if(old('classe_id', $produto->classe_id)==$classe->id) selected @endif >{{ $classe->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('classe_id'))
                            <small class="form-control-feedback">{{ $errors->first('classe_id') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-6 form-group @if($errors->has('especie_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Especie<span class="text-danger">*</span></label>
                        <select class="form-control select2 @if($errors->has('especie_id')) form-control-danger @endif" name="especie_id" id="especie_id" required style="width: 100%">
                            <option value="">Selecione</option>
                            @foreach($especies as $especie)
                                <option value="{{ $especie->id }}" @if(old('especie_id', $produto->especie_id)==$especie->id) selected @endif >{{ $especie->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('especie_id'))
                            <small class="form-control-feedback">{{ $errors->first('especie_id') }}</small>
                        @endif
                    </div>


                    <div class=" col-md-6    form-group @if($errors->has('tipo')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Tipo<span class="text-danger">*</span></label>
                        <select class="form-control @if($errors->has('tipo')) form-control-danger @endif" name="tipo" id="tipo" required>
                            <option value="">Selecione</option>
                            <option value="normal" @if(old('tipo', $produto->tipo)=='normal') selected @endif >Normal</option>
                            <option value="re_processado" @if(old('tipo', $produto->tipo)=='re_processado') selected @endif >Re-processado</option>
                            <option value="consignado" @if(old('tipo', $produto->tipo)=='consignado') selected @endif >Consignado</option>
                        </select>
                        @if($errors->has('tipo'))
                            <small class="form-control-feedback">{{ $errors->first('tipo') }}</small>
                        @endif
                    </div>

                    <div class="col-12 mb-3">
                        <hr>
                        <h4>Complementares</h4>
                    </div>

                    <div class=" col-md-6 form-group @if($errors->has('id_procedimento_sus')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Procedimento SUS</label>
                        <select class="form-control select2 @if($errors->has('id_procedimento_sus')) form-control-danger @endif" name="id_procedimento_sus" id="id_procedimento_sus"  style="width: 100%">
                            <option value="1">Selecione</option>
                        </select>
                        @if($errors->has('id_procedimento_sus'))
                            <small class="form-control-feedback">{{ $errors->first('id_procedimento_sus') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-6 form-group @if($errors->has('id_procedimento_conv')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Procedimento Convênios</label>
                        <select class="form-control select2 @if($errors->has('id_procedimento_conv')) form-control-danger @endif" name="id_procedimento_conv" id="id_procedimento_conv"  style="width: 100%">
                            <option value="1">Selecione</option>
                        </select>
                        @if($errors->has('id_procedimento_conv'))
                            <small class="form-control-feedback">{{ $errors->first('id_procedimento_conv') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-6 form-group @if($errors->has('classificacao_abc')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Classificação ABC </label>
                        <select class="form-control select2 @if($errors->has('classificacao_abc')) form-control-danger @endif" name="classificacao_abc" id="classificacao_abc"  style="width: 100%">
                            <option value="">Selecione</option>
                            <option value="A" @if(old('classificacao_abc', $produto->classificacao_abc)=='A') selected @endif>Cuidado rígido</option>
                            <option value="B" @if(old('classificacao_abc', $produto->classificacao_abc)=='B') selected @endif>Cuidado Normal</option>
                            <option value="C" @if(old('classificacao_abc', $produto->classificacao_abc)=='C') selected @endif>Cuidado Moderado</option>
                        </select>
                        @if($errors->has('classificacao_abc'))
                            <small class="form-control-feedback">{{ $errors->first('classificacao_abc') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-6 form-group @if($errors->has('classificacao_xyz')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Classificação XYZ </label>
                        <select class="form-control select2 @if($errors->has('classificacao_xyz')) form-control-danger @endif" name="classificacao_xyz" id="classificacao_xyz"  style="width: 100%">
                            <option value="">Selecione</option>
                            <option value="X" @if(old('classificacao_xyz', $produto->classificacao_xyz)=='X') selected @endif>Pouco Importante</option>
                            <option value="Y" @if(old('classificacao_xyz', $produto->classificacao_xyz)=='Y') selected @endif>Importante</option>
                            <option value="Z" @if(old('classificacao_xyz', $produto->classificacao_xyz)=='Z') selected @endif>Muito Importante</option>
                        </select>
                        @if($errors->has('classificacao_xyz'))
                            <small class="form-control-feedback">{{ $errors->first('classificacao_xyz') }}</small>
                        @endif
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="checkbox" id="kit" @if(old('kit', $produto->kit)==true)  checked @endif value="1" name="kit" class="filled-in" />
                            <label for="kit">Kit<label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="checkbox" id="mestre" @if(old('mestre', $produto->mestre)==true) checked @endif  value="1" name="mestre" class="filled-in" />
                            <label for="mestre">Mestre<label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="checkbox" id="generico"  @if(old('generico', $produto->generico)==true) checked @endif  value="1"   name="generico" class="filled-in" />
                            <label for="generico">Genérico<label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="checkbox" id="opme"  @if(old('opme', $produto->opme)==true) checked @endif  value="1"   name="opme" class="filled-in" />
                            <label for="opme">OPME<label>
                        </div>
                    </div>

                </div>

            </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.produtos.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
