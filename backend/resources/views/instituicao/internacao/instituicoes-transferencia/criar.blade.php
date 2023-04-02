


@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Instituições para transferência',
        'breadcrumb' => [
            'Instituições para transferência' => route('instituicao.internacao.instituicoes-transferencia.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.internacao.instituicoes-transferencia.store') }}" method="post">
                @csrf

                <div class="row">
                    <div class="col-md-7 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="descricao" value="{{ old('descricao') }}">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>
                    <div class="col-md-3 form-group @if($errors->has('cnes')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">CNES</label>
                        <input type="number" class="form-control" name="cnes" value="{{ old('cnes') }}">
                        @if($errors->has('cnes'))
                            <small class="form-control-feedback">{{ $errors->first('cnes') }}</small>
                        @endif
                    </div>
                    <div class="col-md-2 form-group @if($errors->has('cep')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">CEP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" alt="cep" name="cep" value="{{ old('cep') }}">
                        @if($errors->has('cep'))
                            <small class="form-control-feedback">{{ $errors->first('cep') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">



                    <div class="col-md-2 form-group @if($errors->has('estado')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Estado <span class="text-danger">*</span></label>
                        <select id="estado" class="form-control @if($errors->has('estado')) form-control-danger @endif" name="estado">
                            <option selected disabled>Selecione</option>
                            <option value="AC" @if (old('estado') == 'AC')
                                selected="selected"
                            @endif>Acre</option>
                            <option value="AL" @if (old('estado') == 'AL')
                                selected="selected"
                            @endif>Alagoas</option>
                            <option value="AP" @if (old('estado') == 'AP')
                                selected="selected"
                            @endif>Amapá</option>
                            <option value="AM" @if (old('estado') == 'AM')
                                selected="selected"
                            @endif>Amazonas</option>
                            <option value="BA" @if (old('estado') == 'BA')
                                selected="selected"
                            @endif>Bahia</option>
                            <option value="CE" @if (old('estado') == 'CE')
                                selected="selected"
                            @endif>Ceará</option>
                            <option value="DF" @if (old('estado') == 'DF')
                                selected="selected"
                            @endif>Distrito Federal</option>
                            <option value="GO" @if (old('estado') == 'GO')
                                selected="selected"
                            @endif>Goiás</option>
                            <option value="ES" @if (old('estado') == 'ES')
                                selected="selected"
                            @endif>Espírito Santo</option>
                            <option value="MA" @if (old('estado') == 'MA')
                                selected="selected"
                            @endif>Maranhão</option>
                            <option value="MT" @if (old('estado') == 'MT')
                                selected="selected"
                            @endif>Mato Grosso</option>
                            <option value="MS" @if (old('estado') == 'MS')
                                selected="selected"
                            @endif>Mato Grosso do Sul</option>
                            <option value="MG" @if (old('estado') == 'MG')
                                selected="selected"
                            @endif>Minas Gerais</option>
                            <option value="PA" @if (old('estado') == 'PA')
                                selected="selected"
                            @endif>Pará</option>
                            <option value="PB" @if (old('estado') == 'PB')
                                selected="selected"
                            @endif>Paraiba</option>
                            <option value="PR" @if (old('estado') == 'PR')
                                selected="selected"
                            @endif>Paraná</option>
                            <option value="PE" @if (old('estado') == 'PE')
                                selected="selected"
                            @endif>Pernambuco</option>
                            <option value="PI" @if (old('estado') == 'PI')
                                selected="selected"
                            @endif>Piauí­</option>
                            <option value="RJ" @if (old('estado') == 'RJ')
                                selected="selected"
                            @endif>Rio de Janeiro</option>
                            <option value="RN" @if (old('estado') == 'RN')
                                selected="selected"
                            @endif>Rio Grande do Norte</option>
                            <option value="RS" @if (old('estado') == 'RS')
                                selected="selected"
                            @endif>Rio Grande do Sul</option>
                            <option value="RO" @if (old('estado') == 'RO')
                                selected="selected"
                            @endif>Rondônia</option>
                            <option value="RR" @if (old('estado') == 'RR')
                                selected="selected"
                            @endif>Roraima</option>
                            <option value="SP" @if (old('estado') == 'SP')
                                selected="selected"
                            @endif>São Paulo</option>
                            <option value="SC" @if (old('estado') == 'SC')
                                selected="selected"
                            @endif>Santa Catarina</option>
                            <option value="SE" @if (old('estado') == 'SE')
                                selected="selected"
                            @endif>Sergipe</option>
                            <option value="TO" @if (old('estado') == 'TO')
                                selected="selected"
                            @endif>Tocantins</option>
                        </select>
                        @if($errors->has('estado'))
                            <small class="form-text text-danger">{{ $errors->first('estado') }}</small>
                        @endif
                    </div>

                    <div class="col-md-2 form-group @if($errors->has('cidade')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Cidade <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="cidade" value="{{ old('cidade') }}">
                        @if($errors->has('cidade'))
                            <small class="form-control-feedback">{{ $errors->first('cidade') }}</small>
                        @endif
                    </div>

                    <div class="col-md-2 form-group @if($errors->has('bairro')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Bairro <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="bairro" value="{{ old('bairro') }}">
                        @if($errors->has('bairro'))
                            <small class="form-control-feedback">{{ $errors->first('bairro') }}</small>
                        @endif
                    </div>

                    <div class="col-md-3 form-group @if($errors->has('rua')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Rua <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="rua" value="{{ old('rua') }}">
                        @if($errors->has('rua'))
                            <small class="form-control-feedback">{{ $errors->first('rua') }}</small>
                        @endif
                    </div>

                    <div class="col-md-2 form-group @if($errors->has('numero')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Número <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="numero" value="{{ old('numero') }}">
                        @if($errors->has('numero'))
                            <small class="form-control-feedback">{{ $errors->first('numero') }}</small>
                        @endif
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-3 form-group @if($errors->has('complemento')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Complemento <span class="text-primary">*</span></label>
                        <input type="text" class="form-control" name="complemento" value="{{ old('complemento') }}">
                        @if($errors->has('complemento'))
                            <small class="form-control-feedback">{{ $errors->first('complemento') }}</small>
                        @endif
                    </div>

                    <div class="col-md-3 form-group @if($errors->has('telefone')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Telefone</label>
                        <input id="telefone" class="telefone form-control @if($errors->has('telefone')) form-control-danger @endif" name="telefone" value="{{ old('telefone') }}">
                        @if($errors->has('telefone'))
                            <small class="form-text text-danger">{{ $errors->first('telefone') }}</small>
                        @endif
                    </div>
                    <div class="col-md-3 form-group @if($errors->has('email')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">E-mail</label>
                        <input id="email" class="form-control @if($errors->has('email')) form-control-danger @endif" name="email" value="{{ old('email') }}">
                        @if($errors->has('email'))
                            <small class="form-text text-danger">{{ $errors->first('email') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.internacao.instituicoes-transferencia.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $('.telefone').each(function(){
                $(this).setMask('(99) 99999-9999', {
                    translation: { '9': { pattern: /[0-9]/, optional: false} }
                })
            });
        })
    </script>
@endpush
