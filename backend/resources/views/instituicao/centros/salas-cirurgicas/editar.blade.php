





@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Editar Sala Cirúrgica',
        'breadcrumb' => [
            'Salas Cirúrgicas' => route('instituicao.centros.cirurgicos.salas.index', [$centro_cirurgico]),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">

        <div class="card-body ">
            <form action="{{ route('instituicao.centros.cirurgicos.salas.update', [$centro_cirurgico, $sala_cirurgica]) }}" method="post">
                @method('put')
                @csrf

                <div class="col-sm-12 p-0 m-0 mb-3">
                    <div class="p-0 m-0 sala-cirurgica-item col-sm-12 mb-2">
                        <div class="p-0 m-0 shadow-none">
                            <div class="row p-1 m-0">
                                <div class="col-sm-7 p-2 m-0">
                                    <div class="form-group @if($errors->has("descricao")) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Descrição <span class="text-danger">*</span></label>
                                        <input type="text" name="descricao"
                                            value='{{ old("descricao", $sala_cirurgica->descricao) }}' class="form-control campo descricao">
                                        @if($errors->has("descricao"))
                                            <small class="form-control-feedback">{{ $errors->first("descricao") }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-5 p-2 m-0">
                                    <div class="form-group @if($errors->has("sigla")) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Sigla <span class="text-danger">*</span></label>
                                        <input type="text" name="sigla"
                                            value='{{ old("sigla", $sala_cirurgica->sigla) }}' class="form-control campo sigla">
                                        @if($errors->has("sigla"))
                                            <small class="form-control-feedback">{{ $errors->first("sigla") }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row p-1 m-0">
                                <div class="col-sm-4 p-2 m-0">
                                    <div class="form-group @if($errors->has("tempo_minimo_preparo")) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Tempo minimo de preparo <span class="text-primary">*</span></label>
                                        <input type="time" name="tempo_minimo_preparo"
                                            value='{{ old("tempo_minimo_preparo", $sala_cirurgica->tempo_minimo_preparo) }}' class="form-control campo tempo_minimo_preparo">
                                        @if($errors->has("tempo_minimo_preparo"))
                                            <small class="form-control-feedback">{{ $errors->first("tempo_minimo_preparo") }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-4 p-2 m-0">
                                    <div class="form-group @if($errors->has("tempo_minimo_utilizacao")) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Tempo Minimo de utilização <span class="text-primary">*</span></label>
                                        <input type="time" name="tempo_minimo_utilizacao"
                                            value='{{ old("tempo_minimo_utilizacao", $sala_cirurgica->tempo_minimo_utilizacao) }}' class="form-control campo tempo_minimo_utilizacao">
                                        @if($errors->has("stempo_minimo_utilizacao"))
                                            <small class="form-control-feedback">{{ $errors->first("tempo_minimo_utilizacao") }}</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-4 p-2 m-0">
                                    <div class="form-group @if($errors->has("tipo")) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Tipo <span class="text-danger">*</span></label>
                                        <select class="form-control p-0 m-0 campo tipo" name="tipo">
                                            <option selected disabled>Selecione</option>
                                            <?php $tipos_salas_cirurgicas = App\SalaCirurgica::getTipos() ?>
                                            @foreach ($tipos_salas_cirurgicas as $tipos_sala_cirurgica)
                                                <option value="{{ $tipos_sala_cirurgica }}"
                                                    @if ($sala_cirurgica->tipo==$tipos_sala_cirurgica)
                                                        selected
                                                    @endif
                                                    @if (old("tipo")==$tipos_sala_cirurgica)
                                                        selected
                                                    @endif>
                                                    {{ App\SalaCirurgica::getTipoTexto($tipos_sala_cirurgica) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if($errors->has("tipo"))
                                            <small class="form-control-feedback">{{ $errors->first("tipo") }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.centros.cirurgicos.salas.index', [$centro_cirurgico]) }}">
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
    </script>
@endpush
