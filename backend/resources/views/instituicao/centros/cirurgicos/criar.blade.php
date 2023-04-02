



@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Centro Cirúrgico',
        'breadcrumb' => [
            'Centros Cirúrgicos' => route('instituicao.centros.cirurgicos.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">

        <div class="card-body ">
            <form action="{{ route('instituicao.centros.cirurgicos.store') }}" method="post">
                @csrf

                <div class="row">
                    <div wire:ignore class="col-md-6 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="descricao" value="{{ old('descricao') }}">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                    <div class="col-md-6 form-group @if($errors->has('cc_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Centro de Custo <span class="text-danger">*</span></label>
                        <select class="form-control p-0 m-0" name="cc_id">
                            <option selected disabled>Selecione</option>
                            @foreach ($centros_custos as $centro_custo)
                                <option value="{{ $centro_custo->id }}"
                                    @if (old('cc_id')==$centro_custo->id)
                                    selected
                                @endif>{{ $centro_custo->codigo }} {{ $centro_custo->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('cc_id'))
                            <small class="form-control-feedback">{{ $errors->first('cc_id') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row d-flex justify-content-center">
                    <div class="card col-sm-6 p-2 shadow-none">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group row m-1 d-flex justify-content-end">
                                    <label class="col-4 col-form-label">Início</label>
                                    <label class="col-4 col-form-label">Fim</label>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group row m-1">
                                    <label class="col-4 col-form-label pl-2">Segunda Feira</label>
                                    <div class="col-4 @if($errors->has('segunda_feira_inicio')) has-danger @endif">
                                        <input type="time" value="{{ old('segunda_feira_inicio') }}" name="segunda_feira_inicio" class="form-control">
                                    </div>
                                    <div class="col-4 @if($errors->has('segunda_feira_fim')) has-danger @endif">
                                        <input type="time" value="{{ old('segunda_feira_fim') }}" name="segunda_feira_fim" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row m-1">
                                    <label class="col-4 col-form-label pl-2">Terça Feira</label>
                                    <div class="col-4 @if($errors->has('terca_feira_inicio')) has-danger @endif">
                                        <input type="time" value="{{ old('terca_feira_inicio') }}" name="terca_feira_inicio" class="form-control">
                                    </div>
                                    <div class="col-4 @if($errors->has('terca_feira_fim')) has-danger @endif">
                                        <input type="time" value="{{ old('terca_feira_fim') }}" name="terca_feira_fim" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row m-1">
                                    <label class="col-4 col-form-label pl-2">Quarta Feira</label>
                                    <div class="col-4 @if($errors->has('quarta_feira_inicio')) has-danger @endif">
                                        <input type="time" value="{{ old('quarta_feira_inicio') }}" name="quarta_feira_inicio" class="form-control">
                                    </div>
                                    <div class="col-4 @if($errors->has('quarta_feira_fim')) has-danger @endif">
                                        <input type="time" value="{{ old('quarta_feira_fim') }}" name="quarta_feira_fim" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row m-1">
                                    <label class="col-4 col-form-label pl-2">Quinta Feira</label>
                                    <div class="col-4 @if($errors->has('quinta_feira_inicio')) has-danger @endif">
                                        <input type="time" value="{{ old('quinta_feira_inicio') }}" name="quinta_feira_inicio" class="form-control">
                                    </div>
                                    <div class="col-4 @if($errors->has('quinta_feira_fim')) has-danger @endif">
                                        <input type="time" value="{{ old('quinta_feira_fim') }}" name="quinta_feira_fim" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row m-1">
                                    <label class="col-4 col-form-label pl-2">Sexta Feira</label>
                                    <div class="col-4 @if($errors->has('sexta_feira_inicio')) has-danger @endif">
                                        <input type="time" value="{{ old('sexta_feira_inicio') }}" name="sexta_feira_inicio" class="form-control">
                                    </div>
                                    <div class="col-4 @if($errors->has('sexta_feira_fim')) has-danger @endif">
                                        <input type="time" value="{{ old('sexta_feira_fim') }}" name="sexta_feira_fim" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row m-1">
                                    <label class="col-4 col-form-label pl-2">Sábado</label>
                                    <div class="col-4 @if($errors->has('sabado_inicio')) has-danger @endif">
                                        <input type="time" value="{{ old('sabado_inicio') }}" name="sabado_inicio" class="form-control">
                                    </div>
                                    <div class="col-4 @if($errors->has('sabado_fim')) has-danger @endif">
                                        <input type="time" value="{{ old('sabado_fim') }}" name="sabado_fim" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row m-1">
                                    <label class="col-4 col-form-label pl-2">Domingo</label>
                                    <div class="col-4 @if($errors->has('domingo_inicio')) has-danger @endif">
                                        <input type="time" value="{{ old('domingo_inicio') }}" name="domingo_inicio" class="form-control">
                                    </div>
                                    <div class="col-4 @if($errors->has('domingo_fim')) has-danger @endif">
                                        <input type="time" value="{{ old('domingo_fim') }}" name="domingo_fim" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 p-0 m-0 mb-3">
                    <div class="row p-0 m-0 pt-2 pb-2" id="salas-cirurgicas-lista">
                        @if(old('salas_cirurgicas'))
                            @for ($i = 0; $i < count(old('salas_cirurgicas')) ; $i ++)
                                <div class="p-0 m-0 sala-cirurgica-item col-sm-12 mb-2" id="{{ $i }}">
                                    <div class="card p-0 m-0 shadow-none">
                                        <div class="row bg-light border-bottom d-flex justify-content-end p-0 m-0">
                                            <div class="col d-flex p-2 m-0">
                                                <div class="row col-sm-12 d-flex justify-content-between align-self-center p-0 m-0">
                                                    <span class="text-dark sala-cirurgica-titulo">Sala Cirúrgica #{{ $i }}</span>
                                                    <button
                                                        onclick="document.querySelector('#salas-cirurgicas-lista').removeChild(this.parentElement.parentElement.parentElement.parentElement.parentElement)"
                                                        type="button" class="btn btn-danger">
                                                        <i class="ti-close"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row p-1 m-0">
                                            <div class="col-sm-7 p-2 m-0">
                                                <div class="form-group @if($errors->has("salas_cirurgicas.{$i}.descricao")) has-danger @endif">
                                                    <label class="form-control-label p-0 m-0">Descrição <span class="text-danger">*</span></label>
                                                    <input type="text" name="salas_cirurgicas[{{ $i }}][descricao]"
                                                        value='{{ old("salas_cirurgicas.{$i}.descricao") }}' class="form-control campo descricao">
                                                    @if($errors->has("salas_cirurgicas.{$i}.descricao"))
                                                        <small class="form-control-feedback">{{ $errors->first("salas_cirurgicas.{$i}.descricao") }}</small>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-sm-5 p-2 m-0">
                                                <div class="form-group @if($errors->has("salas_cirurgicas.{$i}.sigla")) has-danger @endif">
                                                    <label class="form-control-label p-0 m-0">Sigla <span class="text-danger">*</span></label>
                                                    <input type="text" name="salas_cirurgicas[{{ $i }}][sigla]"
                                                        value='{{ old("salas_cirurgicas.{$i}.sigla") }}' class="form-control campo sigla">
                                                    @if($errors->has("salas_cirurgicas.{$i}.sigla"))
                                                        <small class="form-control-feedback">{{ $errors->first("salas_cirurgicas.{$i}.sigla") }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row p-1 m-0">
                                            <div class="col-sm-4 p-2 m-0">
                                                <div class="form-group @if($errors->has("salas_cirurgicas.{$i}.tempo_minimo_preparo")) has-danger @endif">
                                                    <label class="form-control-label p-0 m-0">Tempo minimo de preparo <span class="text-primary">*</span></label>
                                                    <input type="time" name="salas_cirurgicas[{{ $i }}][tempo_minimo_preparo]"
                                                        value='{{ old("salas_cirurgicas.{$i}.tempo_minimo_preparo") }}' class="form-control campo tempo_minimo_preparo">
                                                    @if($errors->has("salas_cirurgicas.{$i}.tempo_minimo_preparo"))
                                                        <small class="form-control-feedback">{{ $errors->first("salas_cirurgicas.{$i}.tempo_minimo_preparo") }}</small>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-sm-4 p-2 m-0">
                                                <div class="form-group @if($errors->has("salas_cirurgicas.{$i}.tempo_minimo_utilizacao")) has-danger @endif">
                                                    <label class="form-control-label p-0 m-0">Tempo Minimo de utilização <span class="text-primary">*</span></label>
                                                    <input type="time" name="salas_cirurgicas[{{ $i }}][tempo_minimo_utilizacao]"
                                                        value='{{ old("salas_cirurgicas.{$i}.tempo_minimo_utilizacao") }}' class="form-control campo tempo_minimo_utilizacao">
                                                    @if($errors->has("salas_cirurgicas.{$i}.tempo_minimo_utilizacao"))
                                                        <small class="form-control-feedback">{{ $errors->first("salas_cirurgicas.{$i}.tempo_minimo_utilizacao") }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-4 p-2 m-0">
                                                <div class="form-group @if($errors->has("salas_cirurgicas.{$i}.tipo")) has-danger @endif">
                                                    <label class="form-control-label p-0 m-0">Tipo <span class="text-danger">*</span></label>
                                                    <select class="form-control p-0 m-0 campo tipo" name="salas_cirurgicas[{{$i}}][tipo]">
                                                        <option selected disabled>Selecione</option>
                                                        <?php $tipos_salas_cirurgicas = App\SalaCirurgica::getTipos() ?>
                                                        @foreach ($tipos_salas_cirurgicas as $tipos_sala_cirurgica)
                                                            <option value="{{ $tipos_sala_cirurgica }}"
                                                                @if (old("salas_cirurgicas.{$i}.tipo")==$tipos_sala_cirurgica)
                                                                    selected
                                                                @endif>
                                                                {{ App\SalaCirurgica::getTipoTexto($tipos_sala_cirurgica) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has("salas_cirurgicas.{$i}.tipo"))
                                                        <small class="form-control-feedback">{{ $errors->first("salas_cirurgicas.{$i}.tipo") }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        @endif
                    </div>
                </div>

                <div class="row bg-light d-flex border justify-content-between p-0 m-0 mb-3">
                    <div class="col-3 p-3 m-0">
                        <span class="title text-dark">Salas Cirúrgicas</span>
                    </div>
                    <div class="col-1 d-flex p-2 m-0">
                        <div class="row col-sm-12 d-flex justify-content-end align-self-center p-0 m-0">
                            <button type="button" class="btn btn-primary" id="adiciona-sala-cirurgica">
                                <i class="ti-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>


                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.centros.cirurgicos.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>




@endsection



@push('scripts')


    <script type="text/template" id="sala-cirurgica-item">
        <div class="p-0 m-0 sala-cirurgica-item col-sm-12 mb-2">
            <div class="card p-0 m-0 shadow-none">
                <div class="row bg-light border-bottom d-flex justify-content-end p-0 m-0">
                    <div class="col d-flex p-2 m-0">
                        <div class="row col-sm-12 d-flex justify-content-between align-self-center p-0 m-0">
                            <span class="text-dark sala-cirurgica-titulo"></span>
                            <button
                                onclick="document.querySelector('#salas-cirurgicas-lista').removeChild(this.parentElement.parentElement.parentElement.parentElement.parentElement)"
                                type="button" class="btn btn-danger">
                                <i class="ti-close"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row p-1 m-0">
                    <div class="col-sm-7 p-2 m-0">
                        <div class="form-group">
                            <label class="form-control-label p-0 m-0">Descrição <span class="text-danger">*</span></label>
                            <input type="text" class="form-control campo descricao">
                        </div>
                    </div>

                    <div class="col-sm-5 p-2 m-0">
                        <div class="form-group">
                            <label class="form-control-label p-0 m-0">Sigla <span class="text-danger">*</span></label>
                            <input type="text" class="form-control campo sigla">
                        </div>
                    </div>
                </div>

                <div class="row p-1 m-0">
                    <div class="col-sm-4 p-2 m-0">
                        <div class="form-group">
                            <label class="form-control-label p-0 m-0">Tempo minimo de preparo <span class="text-primary">*</span></label>
                            <input type="time" class="form-control campo tempo_minimo_preparo">
                        </div>
                    </div>

                    <div class="col-sm-4 p-2 m-0">
                        <div class="form-group">
                            <label class="form-control-label p-0 m-0">Tempo Minimo de utilização <span class="text-primary">*</span></label>
                            <input type="time" class="form-control campo tempo_minimo_utilizacao">
                        </div>
                    </div>
                    <div class="col-sm-4 p-2 m-0">
                        <div class="form-group">
                            <label class="form-control-label p-0 m-0">Tipo <span class="text-danger">*</span></label>
                            <select class="form-control p-0 m-0 campo tipo">
                                <option selected disabled>Selecione</option>
                                <?php $tipos_salas_cirurgicas = App\SalaCirurgica::getTipos() ?>
                                @foreach ($tipos_salas_cirurgicas as $tipos_sala_cirurgica)
                                    <option value="{{ $tipos_sala_cirurgica }}">
                                        {{ App\SalaCirurgica::getTipoTexto($tipos_sala_cirurgica) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script>
        $(document).ready(function(){

            function hasClass(elemento, classe) {
                return (' '+elemento.className+' ').indexOf(' '+classe+' ')>-1;
            }

            const nomes_campos = [
                'descricao',
                'sigla',
                'tempo_minimo_preparo',
                'tempo_minimo_utilizacao',
                'tipo',
            ];

            function salasCirurgicas(){
                $('#adiciona-sala-cirurgica').on('click', function(){
                    let sala_cirurgica = $($('#sala-cirurgica-item').html())[0];
                    let salas_cirurgicas_lista = document.querySelector('#salas-cirurgicas-lista');
                    let indice = salas_cirurgicas_lista.querySelectorAll('.sala-cirurgica-item').length;
                    let campos = sala_cirurgica.querySelectorAll('.campo');
                    campos.forEach((campo)=>{
                        nomes_campos.forEach((nome_campo)=>{
                            let novo_nome = `salas_cirurgicas[${indice}][${nome_campo}]`;
                            if(hasClass(campo, nome_campo)) campo.name = novo_nome;
                        });
                    });
                    sala_cirurgica.id = indice;
                    sala_cirurgica.querySelector('.sala-cirurgica-titulo').textContent = `Sala Cirúrgica #${indice}`;
                    salas_cirurgicas_lista.appendChild(sala_cirurgica);
                    sala_cirurgica.scrollIntoView();
                    $('.alert').alert()
                });
            }

            salasCirurgicas();

        });
    </script>
@endpush
