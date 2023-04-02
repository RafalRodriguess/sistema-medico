@extends('admin.layout')

@section('conteudo')
    <div class="row">
        <div class="col-12">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Bem vindo ao Asa Saúde {{ request()->user('admin')->nome }} =)</h4>
                    <h6 class="card-subtitle">Utilize o menu ao lado para acessar os módulos do sistema administrativo.</h6>
                  
                </div>
            </div>
            
        </div>
    </div>
@endsection