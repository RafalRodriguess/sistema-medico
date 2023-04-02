@extends('instituicao.layout')

    @section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Grupo de Faturamento',
        'breadcrumb' => [
            'Grupos de Faturamento' => route('instituicao.grupoFaturamento.index'),
            'Novo',
        ],
        ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.grupoFaturamento.store') }}" method="post">
                @csrf

                <div class="row">
                    <div class=" col-md-6 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label">Descrição: *</span></label>
                        <input type="text" name="descricao" value="{{ old('descricao') }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                        <div class="form-control-feedback">{{ $errors->first('descricao') }}</div>
                        @endif
                    </div>
                    <div class=" col-md-6 form-group @if($errors->has('tipo')) has-danger @endif">
                        <label class="form-control-label">Tipo: *</span></label>
                        <select class="form-control select2 @if($errors->has('tipo')) form-control-danger @endif" name="tipo" id="tipo" style="width: 100%">
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

                    <div class="col-md-4">
                        <input type="checkbox" id="val_grupo_faturamento" name="val_grupo_faturamento" value="1" @if (old("val_grupo_faturamento")) checked @endif class="filled-in chk-col-teal"/>
                        <label for="val_grupo_faturamento">Val. grupo de faturamento</label>
                        @if($errors->has('val_grupo_faturamento'))
                        <div class="form-control-feedback">{{ $errors->first('val_grupo_faturamento') }}</div>
                        @endif

                    </div>
                    <div class="col-md-4">
                        <input type="checkbox" id="rateio_nf" name="rateio_nf" value="1" @if (old("rateio_nf")) checked @endif class="filled-in chk-col-teal"/>
                        <label for="rateio_nf">Rateia Nf</label>
                        @if($errors->has('rateio_nf'))
                        <div class="form-control-feedback">{{ $errors->first('rateio_nf') }}</div>
                        @endif

                    </div>
                    <div class="col-md-4">
                        <input type="checkbox" id="incide_iss" name="incide_iss" value="1" @if (old("incide_iss")) checked @endif class="filled-in chk-col-teal"/>
                        <label for="incide_iss">Incide ISS</label>
                        @if($errors->has('incide_iss'))
                        <div class="form-control-feedback">{{ $errors->first('incide_iss') }}</div>
                        @endif

                    </div>
                </div>

                <div class="form-group text-right">
                    <hr>
                    <a href="{{ route('instituicao.grupoFaturamento.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
