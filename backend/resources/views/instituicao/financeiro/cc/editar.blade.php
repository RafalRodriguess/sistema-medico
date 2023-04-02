@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => "Editar Centro de Custo #{$centro_custo->id} {$centro_custo->nome}",
        'breadcrumb' => [
            'Centro de Custo' => route('instituicao.financeiro.cc.index'),
            'Alteração',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            @livewire('instituicao.financeiro.centro-custo.form-edit-centro-custo', ['centro_custo' => $centro_custo, 'setores_exame' => $setores_exame])
        </div>
    </div>
@endsection

@push('scripts')
    <script>
    </script>
@endpush
