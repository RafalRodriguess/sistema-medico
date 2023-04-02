@extends('comercial.layout')

@section('conteudo')
@component('components/page-title', [
'titulo' => "Editar Cidade #{$filtro->id} ({$filtro->cidade})",
'breadcrumb' => [
'Fretes',
'Entregas' => route('comercial.fretes_entregas'),
'Editar Cidade',
],
])
@endcomponent

<div class="card">
    <div class="card-body">
        <form action="{{ route('comercial.fretes_entrega.update', [$filtro]) }}" method="post" enctype="multipart/form-data">
            @method('put')
            @csrf


            <div class="row">
                <div class="col-md-6 form-group @if($errors->has('titulo')) has-danger @endif">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Insira o nome da cidade:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-map"></i></span>
                                </div>
                                <input type="text" name="valor" id="autocomplete" class="form-control" placeholder="Digite uma cidade">
                            </div>
                            <br>
                        </div>
                        <div class="col-md-12 form-group @if($errors->has('cidade')) has-danger @endif">
                            <label class="form-control-label">Cidade: *</span></label>
                            <input type="text" name="cidade" class="cidadeGmap form-control" readonly required value="{{ old('cidade', $filtro->cidade) }}" class="form-control @if($errors->has('cidade')) form-control-danger @endif">
                            @if($errors->has('cidade'))
                            <div class="form-control-feedback">{{ $errors->first('cidade') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12 form-group @if($errors->has('valor')) has-danger @endif">
                            <label class="form-control-label">Taxa de entrega: *</label>
                            <div class="input-group ">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">R$</span>
                                </div>

                                <input type="text" name="valor" alt="money" required value="{{ old('valor', $filtro->valor) }}" class="form-control @if($errors->has('valor')) form-control-danger @endif">
                                @if($errors->has('valor'))
                                <div class="form-control-feedback">{{ $errors->first('valor') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-control-label">Valor mínimo: *</label>
                            <div class="input-group ">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">R$</span>
                                </div>
                                <input type="text" name="valor_minimo" alt="money" class="form-control " required value="{{ old('valor_minimo', $filtro->valor_minimo) }}" class="form-control @if($errors->has('valor_minimo')) form-control-danger @endif">
                                @if($errors->has('valor_minimo'))
                                <div class="form-control-feedback">{{ $errors->first('valor_minimo') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <h4>Prazo de entrega</h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 form-group @if($errors->has('valor')) has-danger @endif">
                            <label class="form-control-label">Prazo em: *</label>
                            <div class="input-group">
                                <select name="tipo_prazo" class="form-control" id="selectPrazo">
                                    <option value="minutos" @if (old('tipo_prazo', $filtro->tipo_prazo) == "minutos")
                                        selected="selected"
                                        @endif>Minutos</option>
                                    <option value="horas" @if (old('tipo_prazo', $filtro->tipo_prazo) == "horas")
                                        selected="selected"
                                        @endif>Horas</option>
                                    <option value="dias" @if (old('tipo_prazo', $filtro->tipo_prazo) == "dias")
                                        selected="selected"
                                        @endif>Dias</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 @if($errors->has('prazo_minimo')) has-danger @endif">
                            <label class="form-control-label">Prazo mínimo: *</label>
                            <div class="input-group ">
                                <input type="text" name="prazo_minimo" class="form-control @if($errors->has('valor_parcela')) form-control-danger @endif inputPrazo" value="{{ old('prazo_minimo', $filtro->prazo_minimo) }}">
                                @if($errors->has('prazo_minimo'))
                                <div class="form-control-feedback">{{ $errors->first('prazo_minimo') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4 @if($errors->has('prazo_maximo')) has-danger @endif">
                            <label class="form-control-label">Prazo máximo: *</label>
                            <div class="input-group">
                                <input type="text" name="prazo_maximo" class="form-control @if($errors->has('valor_parcela')) form-control-danger @endif inputPrazo" value="{{ old('prazo_maximo', $filtro->prazo_maximo) }}">
                                @if($errors->has('prazo_maximo'))
                                <div class="form-control-feedback">{{ $errors->first('prazo_maximo') }}</div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

            </div>



            <hr>

            <div class="form-group text-right">
                <a href="{{route('comercial.fretes_entregas')}}">
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
                types: ['(cities)'],
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
        $('#selectPrazo').change(function() {
            $('.inputPrazo').val('')
        })
        $(".inputPrazo").TouchSpin({
            min: 0,
            max: 100,
            step: 1,
        });

    });
</script>
@endpush

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDzV_N9HyxsiVO3nIWRJifBIQ88g1n02P8&libraries=places&callback=initAutocomplete" defer></script>

