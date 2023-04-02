@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Centro de Custo',
        'breadcrumb' => [
            'Centro de Custo' => route('instituicao.financeiro.cc.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            @livewire('instituicao.financeiro.centro-custo.form-creste-centro-custo', ['setores_exame' => $setores_exame])
        </div>
    </div>
@endsection

@push('scripts')
    <script>
    </script>
@endpush
