@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => "Editar Leito #{$leito->id}",
        'breadcrumb' => [
            'Leitos' => route('instituicao.internacao.leitos.index', [$unidade_internacao]),
            'Atualizar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.internacao.leitos.update', [$unidade_internacao, $leito]) }}" method="post">
                @method('put')
                @csrf

                <div class="col-sm-12 p-0 m-0 leito-item mb-2">
                    <div class="row p-1 m-0">
                        <div class="col-sm-4 p-2 m-0">
                            <div class="form-group @if($errors->has("descricao")) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Descrição <span class="text-danger">*</span></label>
                                <input type="text" name="descricao" value='{{ old("descricao", $leito->descricao) }}' class="form-control campo descricao">
                                @if($errors->has("descricao"))
                                    <small class="form-control-feedback">{{ $errors->first("descricao") }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-3 p-2 m-0">
                            <div class="form-group @if($errors->has("tipo")) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Tipo <span class="text-danger">*</span></label>
                                <select class="form-control p-0 m-0 campo tipo" name="tipo">
                                    <option selected disabled>Selecione</option>
                                    <?php $tipos_leitos = App\UnidadeLeito::getTipos() ?>
                                    @foreach ($tipos_leitos as $tipo_leito_id)
                                        <option value="{{ $tipo_leito_id }}" @if ($leito->tipo==$tipo_leito_id)
                                            selected
                                        @endif @if (old("tipo")==$tipo_leito_id)
                                            selected
                                        @endif>{{ App\UnidadeLeito::getTipoTexto($tipo_leito_id) }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has("tipo"))
                                    <small class="form-control-feedback">{{ $errors->first("tipo") }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-3 p-2 m-0">
                            <div class="form-group @if($errors->has("situacao")) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Situação <span class="text-danger">*</span></label>
                                <select class="form-control p-0 m-0 campo situacao" name="situacao">
                                    <option selected disabled>Selecione</option>
                                    <?php $situacoes_leitos = App\UnidadeLeito::getSituacoes() ?>
                                    @foreach ($situacoes_leitos as $situacao_leito_id)
                                        <option value="{{ $situacao_leito_id }}" @if ($leito->situacao==$situacao_leito_id)
                                            selected
                                        @endif @if (old("situacao")==$situacao_leito_id)
                                            selected
                                        @endif>{{ App\UnidadeLeito::getSituacaoTexto($situacao_leito_id) }}</option>
                                    @endforeach
                                </select>
                                @if($errors->get("situacao"))
                                    <small class="form-control-feedback">{{ $errors->first("situacao") }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-2 p-2 m-0">
                            <div class="form-group @if($errors->has("quantidade")) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Quantidade <span class="text-danger">*</span></label>
                                <input type="number" class="form-control campo quantidade"
                                    value='{{ old("quantidade", $leito->quantidade) }}' name="quantidade">
                                @if($errors->has("quantidade"))
                                    <small class="form-control-feedback">{{ $errors->first("quantidade") }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6 p-2 m-0">
                            <div class="form-group @if($errors->has("sala")) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Localização Física <span class="text-danger">*</span></label>
                                <input type="text" class="form-control campo sala"
                                    value='{{ old("sala", $leito->sala) }}' name="sala">
                                @if($errors->has("sala"))
                                    <small class="form-control-feedback">{{ $errors->first("quantidade") }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6 p-2 m-0">
                            <div class="form-group">
                                <label class="form-control-label p-0 m-0">Caracteristicas <span class="text-primary">*</span></label>
                                <select class="form-control p-0 m-0 campo caracteristicas"
                                    name="caracteristicas[]" multiple>
                                    <?php $caracteristicas_propostas = App\UnidadeLeito::getCaracteristicasPropostas() ?>
                                    @foreach ($caracteristicas_propostas as $caracteristica)
                                        <option value="{{ $caracteristica }}"
                                            @foreach ($leito->caracteristicas as $old_caracteristica)
                                                @if ($old_caracteristica==$caracteristica)
                                                    selected
                                                @endif
                                            @endforeach
                                            @if (old("caracteristicas.*"))
                                                @for ($j = 0 ; $j < count(old("caracteristicas.*")); $j++)
                                                    @if (old("caracteristicas.{$j}")==$caracteristica)
                                                        selected
                                                    @endif
                                                @endfor
                                            @endif>{{ $caracteristica }}</option>
                                    @endforeach
                                    @foreach ($leito->caracteristicas as $old_caracteristica)
                                        @if (!in_array($old_caracteristica, $caracteristicas_propostas))
                                            <option value="{{ $old_caracteristica }}" selected>
                                                {{ $old_caracteristica }}
                                            </option>
                                        @endif
                                    @endforeach
                                    @if (old("caracteristicas.*"))
                                        @for ($j = 0 ; $j < count(old("caracteristicas.*")); $j++)
                                            @if (!in_array(old("caracteristicas.{$j}"), $caracteristicas_propostas))
                                                <option value="{{ old("caracteristicas.{$j}") }}" selected>
                                                    {{ old("caracteristicas.{$j}") }}
                                                </option>
                                            @endif
                                        @endfor
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4 p-2 m-0">
                            <div class="form-group">
                                <label class="form-control-label p-0 m-0">Acomodação <span class="text-primary">*</span></label>
                                <select class="form-control p-0 m-0 campo acomodacao_id"
                                    name="acomodacao_id">
                                    <option selected disabled>Selecione</option>
                                    @foreach ($acomodacoes as $acomodacao)
                                        <option value="{{ $acomodacao->id }}" @if ($leito->acomodacao_id==$acomodacao->id)
                                            selected
                                        @endif @if (old("acomodacao_id")==$acomodacao->id)
                                            selected
                                        @endif>{{ $acomodacao->descricao }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4 p-2 m-0">
                            <div class="form-group">
                                <label class="form-control-label p-0 m-0">Especifico de Especialidade <span class="text-primary">*</span></label>
                                <select class="form-control p-0 m-0 campo especialidade_id"
                                    name="especialidade_id">
                                    <option selected disabled>Selecione</option>
                                    <?php $especialidades = App\Especialidade::all() ?>
                                    @foreach ($especialidades as $especialidade)
                                        <option value="{{ $especialidade->id }}" @if ($leito->especialidade_id==$especialidade->id)
                                            selected
                                        @endif @if (old("especialidade_id")==$especialidade->id)
                                            selected
                                        @endif>{{ $especialidade->descricao }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4 p-2 m-0">
                            <div class="form-group">
                                <label class="form-control-label p-0 m-0">Especifico de Médico <span class="text-primary">*</span></label>
                                <select class="form-control p-0 m-0 campo medico_id"
                                    name="medico_id">
                                    <option selected disabled>Selecione</option>
                                    @foreach ($medicos as $medico)
                                        <option value="{{ $medico->id }}" @if ($leito->medico_id==$medico->id)
                                            selected
                                        @endif @if (old("medico_id")==$medico->id)
                                            selected
                                        @endif>{{ $medico->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.internacao.leitos.index', [$unidade_internacao]) }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">

        $(document).ready(function(){

            $(".caracteristicas").select2({
                tags: true,
                tokenSeparators: [',', ' ']
            });

        });
    </script>
@endpush
