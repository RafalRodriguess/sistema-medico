@extends('instituicao.layout')

@section('conteudo')
@component('components/page-title', [
    'titulo' => 'Cadastrar Grupo',
    'breadcrumb' => [
        'Grupos' => route('instituicao.gruposProcedimentos.index'),
        'Novo',
    ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.gruposProcedimentos.store') }}" method="post">
                @csrf

                <div class="row">
                    <div class=" col-md-6 form-group @if($errors->has('nome')) has-danger @endif">
                        <label class="form-control-label">Nome: *</span></label>
                        <input type="text" name="nome" value="{{ old('nome') }}"
                        class="form-control @if($errors->has('nome')) form-control-danger @endif">
                        @if($errors->has('nome'))
                        <div class="form-control-feedback">{{ $errors->first('nome') }}</div>
                        @endif
                    </div>
                    <div class=" col-md-6 form-group @if($errors->has('tipo')) has-danger @endif">
                        <label class="form-control-label">Tipo:</span></label>
                        <select class="form-control select2 @if($errors->has('tipo')) form-control-danger @endif" name="tipo" id="tipo" style="width: 100%">
                            <option value="">Selecione um tipo</option>
                            @foreach ($tipos as $item)
                                <option value="{{$item}}" @if (old("tipo") == $item)
                                    selected
                                @endif>{{App\GrupoFaturamento::tipoValoresTexto($item)}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('tipo'))
                        <div class="form-control-feedback">{{ $errors->first('tipo') }}</div>
                        @endif
                    </div>
                    <div class=" col-md-6 form-group @if($errors->has('grupo_faturamento_id')) has-danger @endif">
                        <label class="form-control-label">Grupo de faturamento:</span></label>
                        <select class="form-control select2 @if($errors->has('grupo_faturamento_id')) form-control-danger @endif" name="grupo_faturamento_id" id="grupo_faturamento_id" style="width: 100%">
                            <option value="">Selecione um grupo</option>
                            @foreach ($gruposFaturamento as $item)
                                <option value="{{$item->id}}" @if (old("grupo_faturamento_id") == $item->id)
                                    selected
                                @endif>{{$item->descricao}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('grupo_faturamento_id'))
                        <div class="form-control-feedback">{{ $errors->first('grupo_faturamento_id') }}</div>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <input type="checkbox" id="principal" name="principal" value="1" @if (old("principal")) checked @endif class="filled-in chk-col-teal"/>
                        <label for="principal">Procedimento principal</label>
                        @if($errors->has('principal'))
                        <div class="form-control-feedback">{{ $errors->first('principal') }}</div>
                        @endif

                    </div>

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
                    <hr>
                    <a href="{{ route('instituicao.gruposProcedimentos.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
    @endsection
