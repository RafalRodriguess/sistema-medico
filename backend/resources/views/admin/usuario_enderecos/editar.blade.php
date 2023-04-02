@extends('admin.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar Endereço Usuario #{$usuario->id} {$usuario->nome} - {$endereco->rua}, {$endereco->numero}",
        'breadcrumb' => [
            'Usuários' => route('usuarios.index'),
            'Endereço Usuario' => route('usuario_enderecos.index', [$usuario]),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('usuario_enderecos.update', ['usuario' => $usuario,
            'endereco' => $endereco]) }}" method="post">
                @method('put')
                @csrf

            <input type="hidden" value="{{$usuario->id}}" name="usuario_id">

                <div class="form-group @if($errors->has('cep')) has-danger @endif">
                    <label class="form-control-label">CEP *</label>
                    <input type="text" name="cep" alt="cep" id="cep" value="{{ old('cep', $endereco->cep) }}"
                        class="form-control  @if($errors->has('cep')) form-control-danger @endif">
                    @if($errors->has('cep'))
                        <div class="form-control-feedback">{{ $errors->first('cep') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('rua')) has-danger @endif">
                    <label class="form-control-label">Rua *</label>
                    <input type="text" name="rua" id="rua" value="{{ old('rua', $endereco->rua) }}"
                        class="form-control  @if($errors->has('rua')) form-control-danger @endif">
                    @if($errors->has('rua'))
                        <div class="form-control-feedback">{{ $errors->first('rua') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('numero')) has-danger @endif">
                    <label class="form-control-label">Numero *</label>
                    <input type="text" name="numero" value="{{ old('numero', $endereco->numero) }}"
                        class="form-control  @if($errors->has('numero')) form-control-danger @endif">
                    @if($errors->has('numero'))
                        <div class="form-control-feedback">{{ $errors->first('numero') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('bairro')) has-danger @endif">
                    <label class="form-control-label">Bairro *</label>
                    <input type="text" name="bairro" id="bairro" value="{{ old('bairro', $endereco->bairro) }}"
                        class="form-control  @if($errors->has('bairro')) form-control-danger @endif">
                    @if($errors->has('bairro'))
                        <div class="form-control-feedback">{{ $errors->first('bairro') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('cidade')) has-danger @endif">
                    <label class="form-control-label">Cidade *</label>
                    <input type="text" name="cidade" id="cidade" value="{{ old('cidade', $endereco->cidade) }}"
                        class="form-control  @if($errors->has('cidade')) form-control-danger @endif">
                    @if($errors->has('cidade'))
                        <div class="form-control-feedback">{{ $errors->first('cidade') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('estado')) has-danger @endif">
                    <label class="form-control-label">Estado *</label>
                    <select class="form-control @if($errors->has('estado')) form-control-danger @endif" name="estado" id="estado" >
                        <option value="">Selecione</option>
                        <option value="AC" @if (old('estado', $endereco->estado) == 'AC')
                            selected="selected"
                        @endif>Acre</option>
                        <option value="AL" @if (old('estado', $endereco->estado) == 'AL')
                            selected="selected"
                        @endif>Alagoas</option>
                        <option value="AP" @if (old('estado', $endereco->estado) == 'AP')
                            selected="selected"
                        @endif>Amapá</option>
                        <option value="AM" @if (old('estado', $endereco->estado) == 'AM')
                            selected="selected"
                        @endif>Amazonas</option>
                        <option value="BA" @if (old('estado', $endereco->estado) == 'BA')
                            selected="selected"
                        @endif>Bahia</option>
                        <option value="CE" @if (old('estado', $endereco->estado) == 'CE')
                            selected="selected"
                        @endif>Ceará</option>
                        <option value="DF" @if (old('estado', $endereco->estado) == 'DF')
                            selected="selected"
                        @endif>Distrito Federal</option>
                        <option value="GO" @if (old('estado', $endereco->estado) == 'GO')
                            selected="selected"
                        @endif>Goiás</option>
                        <option value="ES" @if (old('estado', $endereco->estado) == 'ES')
                            selected="selected"
                        @endif>Espírito Santo</option>
                        <option value="MA" @if (old('estado', $endereco->estado) == 'MA')
                            selected="selected"
                        @endif>Maranhão</option>
                        <option value="MT" @if (old('estado', $endereco->estado) == 'MT')
                            selected="selected"
                        @endif>Mato Grosso</option>
                        <option value="MS" @if (old('estado', $endereco->estado) == 'MS')
                            selected="selected"
                        @endif>Mato Grosso do Sul</option>
                        <option value="MG" @if (old('estado', $endereco->estado) == 'MG')
                            selected="selected"
                        @endif>Minas Gerais</option>
                        <option value="PA" @if (old('estado', $endereco->estado) == 'PA')
                            selected="selected"
                        @endif>Pará</option>
                        <option value="PB" @if (old('estado', $endereco->estado) == 'PB')
                            selected="selected"
                        @endif>Paraiba</option>
                        <option value="PR" @if (old('estado', $endereco->estado) == 'PR')
                            selected="selected"
                        @endif>Paraná</option>
                        <option value="PE" @if (old('estado', $endereco->estado) == 'PE')
                            selected="selected"
                        @endif>Pernambuco</option>
                        <option value="PI" @if (old('estado', $endereco->estado) == 'PI')
                            selected="selected"
                        @endif>Piauí­</option>
                        <option value="RJ" @if (old('estado', $endereco->estado) == 'RJ')
                            selected="selected"
                        @endif>Rio de Janeiro</option>
                        <option value="RN" @if (old('estado', $endereco->estado) == 'RN')
                            selected="selected"
                        @endif>Rio Grande do Norte</option>
                        <option value="RS" @if (old('estado', $endereco->estado) == 'RS')
                            selected="selected"
                        @endif>Rio Grande do Sul</option>
                        <option value="RO" @if (old('estado', $endereco->estado) == 'RO')
                            selected="selected"
                        @endif>Rondônia</option>
                        <option value="RR" @if (old('estado', $endereco->estado) == 'RR')
                            selected="selected"
                        @endif>Roraima</option>
                        <option value="SP" @if (old('estado', $endereco->estado) == 'SP')
                            selected="selected"
                        @endif>São Paulo</option>
                        <option value="SC" @if (old('estado', $endereco->estado) == 'SC')
                            selected="selected"
                        @endif>Santa Catarina</option>
                        <option value="SE" @if (old('estado', $endereco->estado) == 'SE')
                            selected="selected"
                        @endif>Sergipe</option>
                        <option value="TO" @if (old('estado', $endereco->estado) == 'TO')
                            selected="selected"
                        @endif>Tocantins</option>
                    </select>
                    @if($errors->has('estado'))
                        <div class="form-control-feedback">{{ $errors->first('estado') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('complemento')) has-danger @endif">
                    <label class="form-control-label">Complemento</label>
                    <input type="text" name="complemento" id="complemento" value="{{ old('complemento', $endereco->complemento) }}"
                        class="form-control  @if($errors->has('complemento')) form-control-danger @endif">
                    @if($errors->has('complemento'))
                        <div class="form-control-feedback">{{ $errors->first('complemento') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('referencia')) has-danger @endif">
                    <label class="form-control-label">Referencia</label>
                    <input type="text" name="referencia" id="referencia" value="{{ old('referencia', $endereco->referencia) }}"
                        class="form-control  @if($errors->has('referencia')) form-control-danger @endif">
                    @if($errors->has('referencia'))
                        <div class="form-control-feedback">{{ $errors->first('referencia') }}</div>
                    @endif
                </div>



                {{-- So uma coisa que descobrir por agora, é do Laravel mais novo --}}
                {{-- @error('campo')  @enderror --}}
                {{-- @error('campo') {{ $message }}  @enderror --}}

                {{-- <div class="button-group">
                    <button type="button" class="btn waves-effect waves-light btn-primary">Primary</button>
                    <button type="button" class="btn waves-effect waves-light btn-secondary">Secondary</button>
                    <button type="button" class="btn waves-effect waves-light btn-success">Success</button>
                    <button type="button" class="btn waves-effect waves-light btn-info">Info</button>
                    <button type="button" class="btn waves-effect waves-light btn-warning">Warning</button>
                    <button type="button" class="btn waves-effect waves-light btn-danger">Danger</button>
                </div> --}}

                <div class="form-group text-right">
                    <a href="{{ route('usuario_enderecos.index', [$usuario]) }}">
                    <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
