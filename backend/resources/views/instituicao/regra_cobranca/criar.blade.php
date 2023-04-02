@extends('instituicao.layout')

    @section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Regra de Cobrança',
        'breadcrumb' => [
            'Regras de Cobrança' => route('instituicao.regrasCobranca.index'),
            'Novo',
        ],
        ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.regrasCobranca.store') }}" method="post">
                @csrf

                <div class="row">
                    <div class=" col-md-12 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label">Descrição: *</span></label>
                        <input type="text" name="descricao" value="{{ old('descricao') }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                        <div class="form-control-feedback">{{ $errors->first('descricao') }}</div>
                        @endif
                    </div>                
                    <div class=" col-md-4 form-group @if($errors->has('cir_mesma_via')) has-danger @endif">
                        <label class="form-control-label">% Cir Mesma Via: *</span></label>
                        <input type="text" alt="porcentagem" name="cir_mesma_via" value="{{ old('cir_mesma_via') }}"
                        class="form-control @if($errors->has('cir_mesma_via')) form-control-danger @endif">
                        @if($errors->has('cir_mesma_via'))
                        <div class="form-control-feedback">{{ $errors->first('cir_mesma_via') }}</div>
                        @endif
                    </div>
                    <div class=" col-md-4 form-group @if($errors->has('cir_via_diferente')) has-danger @endif">
                        <label class="form-control-label">% Cir Via Diferente: *</span></label>
                        <input type="text" alt="porcentagem" name="cir_via_diferente" value="{{ old('cir_via_diferente') }}"
                        class="form-control @if($errors->has('cir_via_diferente')) form-control-danger @endif">
                        @if($errors->has('cir_via_diferente'))
                        <div class="form-control-feedback">{{ $errors->first('cir_via_diferente') }}</div>
                        @endif
                    </div>
                    <div class=" col-md-4 form-group @if($errors->has('base_via_acesso')) has-danger @endif">
                        <label class="form-control-label">Base de Calc Via de Acesso: *</span></label>
                        <select class="form-control select2 @if($errors->has('base_via_acesso')) form-control-danger @endif" name="base_via_acesso" id="base_via_acesso" style="width: 100%">
                            <option value="cirurgia" @if (old('base_via_acesso') == "cirurgia")
                                selected
                            @endif>Cirurgia</option>
                            <option value="prestador" @if (old('base_via_acesso') == "prestador")
                                selected
                            @endif>Prestador</option>
                        </select>
                        @if($errors->has('base_via_acesso'))
                        <div class="form-control-feedback">{{ $errors->first('base_via_acesso') }}</div>
                        @endif
                    </div>

                    <div class="col-md-2">
                        <input type="checkbox" id="horario_especial" name="horario_especial" value="1" @if (old("horario_especial")) checked @endif class="filled-in chk-col-teal"/>
                        <label for="horario_especial">Horario Especial</label>
                        @if($errors->has('horario_especial'))
                        <div class="form-control-feedback">{{ $errors->first('horario_especial') }}</div>
                        @endif

                    </div>
                    <div class="col-md-2">
                        <input type="checkbox" id="internacao" name="internacao" value="1" @if (old("internacao")) checked @endif class="filled-in chk-col-teal"/>
                        <label for="internacao">Internação</label>
                        @if($errors->has('internacao'))
                        <div class="form-control-feedback">{{ $errors->first('internacao') }}</div>
                        @endif

                    </div>
                    <div class="col-md-2">
                        <input type="checkbox" id="ambulatorial" name="ambulatorial" value="1" @if (old("ambulatorial")) checked @endif class="filled-in chk-col-teal"/>
                        <label for="ambulatorial">Ambulatorial</label>
                        @if($errors->has('ambulatorial'))
                        <div class="form-control-feedback">{{ $errors->first('ambulatorial') }}</div>
                        @endif

                    </div>
                    <div class="col-md-2">
                        <input type="checkbox" id="urgencia_emergencia" name="urgencia_emergencia" value="1" @if (old("urgencia_emergencia")) checked @endif class="filled-in chk-col-teal"/>
                        <label for="urgencia_emergencia">Urgencia Emergencia</label>
                        @if($errors->has('urgencia_emergencia'))
                        <div class="form-control-feedback">{{ $errors->first('urgencia_emergencia') }}</div>
                        @endif

                    </div>
                    <div class="col-md-2">
                        <input type="checkbox" id="externo" name="externo" value="1" @if (old("externo")) checked @endif class="filled-in chk-col-teal"/>
                        <label for="externo">Externo</label>
                        @if($errors->has('externo'))
                        <div class="form-control-feedback">{{ $errors->first('externo') }}</div>
                        @endif

                    </div>
                    <div class="col-md-2">
                        <input type="checkbox" id="home_care" name="home_care" value="1" @if (old("home_care")) checked @endif class="filled-in chk-col-teal"/>
                        <label for="home_care">Home Care</label>
                        @if($errors->has('home_care'))
                        <div class="form-control-feedback">{{ $errors->first('home_care') }}</div>
                        @endif

                    </div>
                </div>

                <div class="form-group text-right">
                    <hr>
                    <a href="{{ route('instituicao.regrasCobranca.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
