@extends('layouts.totem')
@section('conteudo')
    <div class="col-12 m-0 p-3">
        <div class="card col-lg-10 col-md-10 col-sm-12 mx-auto my-0">
            <div class="card-header">
                <div class="row" style="padding-bottom: 25px;">
                    <div class="col-xs-12 col-sm-12 col-md-12 m-t-10 text-center">
                        <img src="{{ asset('material/assets/images/logo.png') }}" width="100" >
                    </div>
                </div>
                <strong class="d-inline-block text-center subtitle">Escolha uma fila para gerar uma senha</strong>
            </div>
            <div class="card-body">
                @foreach ($filas as $fila)
                    <div class="form-group col-lg-10 col-md-10 mb-3 mx-auto">
                        <button onclick="enviar(this)" value="{{ $fila->id }}" type="submit" class="btn btn-secondary button-select-fila">{{ strtoupper($fila->filaTriagem->descricao) }}</button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        window.onafterprint = () => $('#hidden-printable').html('');

        var target;
        function enviar(target) {
            $.ajax({
                route: '{{ route("instituicao.triagem.senhas.retirar-senha", $totem) }}',
                method: 'POST',
                data: {
                    '_token': '{{csrf_token()}}',
                    filas_totem_id: $(target).attr('value')
                },
                success: function (response) {
                    $('#hidden-printable').html(response);
                    window.print();
                },
            })
        }
    </script>
@endpush