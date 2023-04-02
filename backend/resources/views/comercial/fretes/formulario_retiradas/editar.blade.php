@extends('comercial.layout')

@section('conteudo')
@component('components/page-title', [
'titulo' => "Editar endereço de retirada",
'breadcrumb' => [
'Retiradas' => route('comercial.fretes_retiradas'),
'Filtros' => route('comercial.fretes_retiradas'),
'Editar',
],
])
@endcomponent

@push('scripts')

<script src="{{ asset('material/assets/plugins/moment/moment.js') }}"></script>

<script src="{{ asset('material/assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>

<script src="{{ asset('material/assets/plugins/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>

@endpush

@push('estilos')
<link href="{{ asset('material/assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
@endpush


<div class="card">
    <div class="card-body">
        <form action="{{ route('comercial.fretes_retirada.update', [$filtro]) }}" method="post" enctype="multipart/form-data">
            @method('put')
            @csrf

            <div class="row">


                <div class="col-md-12">
                    <label>Insira o endereço:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-map"></i></span>
                        </div>
                        <input type="text" name="valor" id="autocomplete" class="form-control" placeholder="Digite uma cidade">
                    </div>
                    <br>
                    <hr>
                    <br>
                </div>

                <div class="form-group col-md-4 @if($errors->has('nome')) has-danger @endif">
                    <label class="form-control-label">Identificação do local: *</span></label>
                    <input type="text" name="nome" class="form-control" required value="{{ old('nome', $filtro->nome) }}">
                </div>

                <div class="form-group col-md-6 @if($errors->has('rua')) has-danger @endif">
                    <label class="form-control-label">Rua: *</span></label>
                    <input type="text" name="rua" class="form-control ruaGmap" required value="{{ old('rua', $filtro->rua) }}">
                </div>

                <div class="form-group col-md-2 @if($errors->has('numero')) has-danger @endif">
                    <label class="form-control-label">Número: *</span></label>
                    <input type="text" name="numero" class="form-control numeroGmap" required value="{{ old('numero', $filtro->numero) }}">
                </div>

                <div class="form-group col-md-4 @if($errors->has('bairro')) has-danger @endif">
                    <label class="form-control-label">Bairro: *</span></label>
                    <input type="text" name="bairro" class="form-control bairroGmap" required readonly value="{{ old('bairro', $filtro->bairro) }}">
                </div>

                <div class="form-group col-md-3 @if($errors->has('cidade')) has-danger @endif">
                    <label class="form-control-label">Cidade: *</span></label>
                    <input type="text" name="cidade" class="form-control CidadeGmap" required readonly value="{{ old('cidade', $filtro->cidade) }}">
                </div>

                <div class="form-group col-md-3 @if($errors->has('estado')) has-danger @endif">
                    <label class="form-control-label">Estado: *</span></label>
                    <input type="text" name="estado" class="form-control estadoGmap" required readonly value="{{ old('estado', $filtro->estado) }}">
                </div>

                <div class="form-group col-md-2 @if($errors->has('cep')) has-danger @endif">
                    <label class="form-control-label">CEP: *</span></label>
                    <input type="text" name="cep" alt="cep" class="form-control cepGmap" required value="{{ old('cep', $filtro->cep) }}">
                </div>


                <div class="col-md-12">
                    <h4>Prazo de retirada</h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-3 form-group @if($errors->has('valor')) has-danger @endif">
                            <label class="form-control-label">Prazo inicial em: *</label>
                            <div class="input-group">
                                <select name="tipo_prazo_minimo" class="form-control" id="selectPrazoMinimo">
                                    <option value="minutos" @if (old('tipo_prazo_minimo', $filtro->tipo_prazo_minimo) == "minutos")
                                        selected="selected"
                                        @endif>Minutos</option>
                                    <option value="horas" @if (old('tipo_prazo_minimo', $filtro->tipo_prazo_minimo) == "horas")
                                        selected="selected"
                                        @endif>Horas</option>
                                    <option value="dias" @if (old('tipo_prazo_minimo', $filtro->tipo_prazo_minimo) == "dias")
                                        selected="selected"
                                        @endif>Dias</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 @if($errors->has('prazo_minimo')) has-danger @endif">
                            <label class="form-control-label">Prazo inicial da retirada: *</label>
                            <div class="input-group ">
                                <input type="text" name="prazo_minimo" class="form-control @if($errors->has('valor_parcela')) form-control-danger @endif inputPrazoMinimo" value="{{ old('prazo_minimo', $filtro->prazo_minimo) }}">
                                @if($errors->has('prazo_minimo'))
                                <div class="form-control-feedback">{{ $errors->first('prazo_minimo') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-3 form-group @if($errors->has('valor')) has-danger @endif">
                            <label class="form-control-label">Prazo máximo em: *</label>
                            <div class="input-group">
                                <select name="tipo_prazo_maximo" class="form-control" id="selectPrazoMaximo">
                                    <option value="minutos" @if (old('tipo_prazo_maximo', $filtro->tipo_prazo_maximo) == "minutos")
                                        selected="selected"
                                        @endif>Minutos</option>
                                    <option value="horas" @if (old('tipo_prazo_maximo', $filtro->tipo_prazo_maximo) == "horas")
                                        selected="selected"
                                        @endif>Horas</option>
                                    <option value="dias" @if (old('tipo_prazo_maximo', $filtro->tipo_prazo_maximo) == "dias")
                                        selected="selected"
                                        @endif>Dias</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 @if($errors->has('prazo_maximo')) has-danger @endif">
                            <label class="form-control-label">Prazo máximo para retirada: *</label>
                            <div class="input-group">
                                <input type="text" name="prazo_maximo" class="form-control @if($errors->has('valor_parcela')) form-control-danger @endif inputPrazoMaximo" value="{{ old('prazo_maximo', $filtro->prazo_maximo) }}">
                                @if($errors->has('prazo_maximo'))
                                <div class="form-control-feedback">{{ $errors->first('prazo_maximo') }}</div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>



                <div class="col-md-12">
                    <h4>Horários de Retirada</h4>
                    <hr>
                    <div class="row">
                        <div class="col col-md-3">
                            <div class="demo-checkbox @if($errors->has('inicio_segunda') || $errors->has('fim_segunda')) has-danger @endif" >
                                <input type="checkbox" id="segunda" @if($horarios['segunda'] || old('segunda') ) checked @endif name="segunda" class="filled-in"  />
                                <label for="segunda">Segunda-feira</label>
                                <hr>
                                <div style="display:flex">
                                    <span class="input-group-text">
                                        <i class="far fa-clock"></i>
                                    </span>
                                    <input class="form-control timepicker" value="{{ old('inicio_segunda', $horarios['inicio_segunda']) }}" placeholder="Início" name="inicio_segunda">
                                    <input class="form-control timepicker" value="{{ old('fim_segunda', $horarios['fim_segunda']) }}" placeholder="Fim" name="fim_segunda">
                                </div>
                                @if($errors->has('inicio_segunda') || $errors->has('fim_segunda'))
                                <div class="form-control-feedback">Campo obrigatório</div>
                                @endif
                            </div>
                        </div>

                        <div class="col col-md-3">
                            <div class="demo-checkbox @if($errors->has('inicio_terca') || $errors->has('fim_terca')) has-danger @endif">
                                <input type="checkbox" id="terca" @if($horarios['terca'] || old('terca') ) checked @endif name="terca" class="filled-in"  />
                                <label for="terca">Terça-feira</label>
                                <hr>
                                <div style="display:flex">
                                    <span class="input-group-text">
                                        <i class="far fa-clock"></i>
                                    </span>
                                    <input class="form-control timepicker" value="{{ old('inicio_terca', $horarios['inicio_terca']) }}" placeholder="Início" name="inicio_terca">
                                    <input class="form-control timepicker" value="{{ old('fim_terca', $horarios['fim_terca']) }}" placeholder="Fim" name="fim_terca">
                                </div>
                                @if($errors->has('inicio_terca') || $errors->has('fim_terca'))
                                <div class="form-control-feedback">Campo obrigatório</div>
                                @endif
                            </div>
                        </div>

                        <div class="col col-md-3">
                            <div class="demo-checkbox @if($errors->has('inicio_quarta') || $errors->has('fim_quarta')) has-danger @endif">
                                <input type="checkbox" id="quarta" @if($horarios['quarta'] || old('quarta') ) checked @endif name="quarta" class="filled-in"  />
                                <label for="quarta">Quarta-feira</label>
                                <hr>
                                <div style="display:flex">
                                    <span class="input-group-text">
                                        <i class="far fa-clock"></i>
                                    </span>
                                    <input class="form-control timepicker" value="{{ old('inicio_quarta', $horarios['inicio_quarta']) }}" placeholder="Início" name="inicio_quarta">
                                    <input class="form-control timepicker" value="{{ old('fim_quarta', $horarios['fim_quarta']) }}" placeholder="Fim" name="fim_quarta">
                                </div>
                                @if($errors->has('inicio_quarta') || $errors->has('fim_quarta'))
                                <div class="form-control-feedback">Campo obrigatório</div>
                                @endif
                            </div>
                        </div>

                        <div class="col col-md-3">
                            <div class="demo-checkbox @if($errors->has('inicio_quinta') || $errors->has('fim_quinta')) has-danger @endif">
                                <input type="checkbox" id="quinta" @if($horarios['quinta'] || old('quinta') ) checked @endif name="quinta" class="filled-in"  />
                                <label for="quinta">Quinta-feira</label>
                                <hr>
                                <div style="display:flex">
                                    <span class="input-group-text">
                                        <i class="far fa-clock"></i>
                                    </span>
                                    <input class="form-control timepicker" value="{{ old('inicio_quinta', $horarios['inicio_quinta']) }}" placeholder="Início" name="inicio_quinta">
                                    <input class="form-control timepicker" value="{{ old('fim_quinta', $horarios['fim_quinta']) }}" placeholder="Fim" name="fim_quinta">
                                </div>
                                @if($errors->has('inicio_quinta') || $errors->has('fim_quinta'))
                                <div class="form-control-feedback">Campo obrigatório</div>
                                @endif
                            </div>
                        </div>

                        <div class="col col-md-3">
                            <div class="demo-checkbox @if($errors->has('inicio_sexta') || $errors->has('fim_sexta')) has-danger @endif">
                                <input type="checkbox" id="sexta" @if($horarios['sexta'] || old('sexta') ) checked @endif name="sexta" class="filled-in"  />
                                <label for="sexta">Sexta-feira</label>
                                <hr>
                                <div style="display:flex">
                                    <span class="input-group-text">
                                        <i class="far fa-clock"></i>
                                    </span>
                                    <input class="form-control timepicker" value="{{ old('inicio_sexta', $horarios['inicio_sexta']) }}" placeholder="Início" name="inicio_sexta">
                                    <input class="form-control timepicker" value="{{ old('fim_sexta', $horarios['fim_sexta']) }}" placeholder="Fim" name="fim_sexta">
                                </div>
                                @if($errors->has('inicio_sexta') || $errors->has('fim_sexta'))
                                <div class="form-control-feedback">Campo obrigatório</div>
                                @endif
                            </div>
                        </div>

                        <div class="col col-md-3">
                            <div class="demo-checkbox @if($errors->has('inicio_sabado') || $errors->has('fim_sabado')) has-danger @endif">
                                <input type="checkbox" id="sabado" @if($horarios['sabado'] || old('sabado') ) checked @endif name="sabado" class="filled-in"  />
                                <label for="sabado">Sábado</label>
                                <hr>
                                <div style="display:flex">
                                    <span class="input-group-text">
                                        <i class="far fa-clock"></i>
                                    </span>
                                    <input class="form-control timepicker" value="{{ old('inicio_sabado', $horarios['inicio_sabado']) }}" placeholder="Início" name="inicio_sabado">
                                    <input class="form-control timepicker" value="{{ old('fim_sabado', $horarios['fim_sabado']) }}" placeholder="Fim" name="fim_sabado">
                                </div>
                                @if($errors->has('inicio_sabado') || $errors->has('fim_sabado'))
                                <div class="form-control-feedback">Campo obrigatório</div>
                                @endif
                            </div>
                        </div>

                        <div class="col col-md-3">
                            <div class="demo-checkbox @if($errors->has('inicio_domingo') || $errors->has('fim_domingo')) has-danger @endif">
                                <input type="checkbox" id="domingo" @if($horarios['domingo'] || old('domingo') ) checked @endif name="domingo" class="filled-in"  />
                                <label for="domingo">Domingo</label>
                                <hr>
                                <div style="display:flex">
                                    <span class="input-group-text">
                                        <i class="far fa-clock"></i>
                                    </span>
                                    <input class="form-control timepicker" value="{{ old('inicio_domingo', $horarios['inicio_domingo']) }}" placeholder="Início" name="inicio_domingo">
                                    <input class="form-control timepicker" value="{{ old('fim_domingo', $horarios['fim_domingo']) }}" placeholder="Fim" name="fim_domingo">
                                </div>
                                @if($errors->has('inicio_domingo') || $errors->has('fim_domingo'))
                                <div class="form-control-feedback">Campo obrigatório</div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>



            </div>

            <hr>

            <div class="form-group text-right">
                <a href="{{route('comercial.fretes_retiradas')}}">
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
    var placeSearch, autocomplete;
    var componentForm = {
        numeroGmap: ['street_number', 'short_name'],
        ruaGmap: ['route', 'long_name'],
        bairroGmap: ['sublocality_level_1', 'long_name'],
        cidadeGmap: ['administrative_area_level_2', 'long_name'],
        estadoGmap: ['administrative_area_level_1', 'short_name'],
        paisGmap: ['country', 'long_name'],
        cepGmap: ['postal_code', 'short_name']
    };

    function initAutocomplete() {

        // Create the autocomplete object, restricting the search predictions to
        // geographical location types.
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById('autocomplete'), {
                types: ['geocode'],
                componentRestrictions: {
                    country: 'br'
                }
            });

        // Avoid paying for data that you don't need by restricting the set of
        // place fields that are returned to just the address components.
        autocomplete.setFields(['address_component']);

        // When the user selects an address from the drop-down, populate the
        // address fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
    }

    function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();


        $.each(componentForm, function(key, value) {
            $('.' + key).val('')
            var itemAddress = value[0];
            var name = value[1];

            for (var i = 0; i < place.address_components.length; i++) {

                var addressType = place.address_components[i].types[0];


                if (itemAddress == addressType) {
                    var val = place.address_components[i][name];
                    $('.' + key).val(val)
                    // document.getElementById(itemInput).value = val;
                }
            }


        });
    }
</script>
@endpush


@push('scripts')
<script>
    $(document).ready(function() {
        $('#selectPrazoMinimo').change(function() {
            if($(this).val()=='minutos'){
                $('#selectPrazoMaximo option[value="minutos"]').removeAttr('disabled');
                $('#selectPrazoMaximo option[value="horas"]').removeAttr('disabled');
                $('#selectPrazoMaximo option[value="dias"]').removeAttr('disabled');
                $('#selectPrazoMaximo').val('');
            }else if($(this).val()=='horas'){
                $('#selectPrazoMaximo option[value="minutos"]').attr('disabled', 'disabled');
                $('#selectPrazoMaximo option[value="horas"]').removeAttr('disabled');
                $('#selectPrazoMaximo option[value="dias"]').removeAttr('disabled');
                $('#selectPrazoMaximo').val('');

            }else if($(this).val()=='dias'){
                $('#selectPrazoMaximo option[value="minutos"]').attr('disabled', 'disabled');
                $('#selectPrazoMaximo option[value="horas"]').attr('disabled', 'disabled');
                $('#selectPrazoMaximo option[value="dias"]').removeAttr('disabled');
                $('#selectPrazoMaximo').val('');
            }
        })

        $('#selectPrazoMinimo').change(function() {
            $('.inputPrazoMinimo').val(0)
        })

        $('#selectPrazoMaximo').change(function() {
            $('.inputPrazoMaximo').val(0)
        })

        $(".inputPrazoMaximo").TouchSpin({
            min: 0,
            max: 100,
            step: 1,
        });

        $(".inputPrazoMinimo").TouchSpin({
            min: 0,
            max: 100,
            step: 1,
        });

    });
</script>
@endpush

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDzV_N9HyxsiVO3nIWRJifBIQ88g1n02P8&libraries=places&callback=initAutocomplete" defer></script>

@push('scripts')
<script>
    $(document).ready(function() {


        $('.timepicker').bootstrapMaterialDatePicker({
            format: 'HH:mm',
            time: true,
            date: false,
            cancel: 'Fechar',
        });

    });
</script>
@endpush

<style>
    .demo-checkbox label {
        min-width: 0px !important;
        margin-bottom: 0px !important;
        margin-right: 10px;
    }

    .demo-checkbox {
        padding: 10px;
        border: solid 1px #bcbcbc;
        margin-bottom: 10px;
    }

    .timepicker {
        margin: 0px 10px;
    }
</style>
