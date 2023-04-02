@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Composição de Medicamento',
        'breadcrumb' => [
            'Medicamentos' => route('instituicao.medicamentos.index'),
            'Novo',
        ],
    ])
    @endcomponent

    <div class="card">
        <div class="card-body">
            <form action="{{ route('instituicao.medicamentos.store') }}" id="formMedicamento" method="post">
                <div class="form" id="modalMedicamento">

                </div>
                <div class="form-group text-right">
                    <a href="{{ route('instituicao.medicamentos.index') }}">
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
        $(document).ready(function(){
            $.ajax({
                url: "{{route('instituicao.medicamentos.getFormulario')}}",
                type: 'get',
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function(result) {
                    $(".form").html(result)
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')
                },
            });
        })

        $("#formMedicamento").on('submit', function(e){
            e.preventDefault()
            e.stopPropagation()

            var formData = new FormData($(this)[0])

            $.ajax({
                url: "{{route('instituicao.medicamentos.store')}}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: (result) => {
                    $.toast({
                        heading: 'Sucesso',
                        text: 'Medicamento salvo com sucesso!',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 9000,
                        stack: 10
                    });
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')
                },
                error: function(response) {
                    if(response.responseJSON.errors){
                        Object.keys(response.responseJSON.errors).forEach(function(key) {
                            $.toast({
                                heading: 'Erro',
                                text: response.responseJSON.errors[key][0],
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 9000,
                                stack: 10
                            });

                        });
                    }
                }
            })
        });
    </script>
@endpush
