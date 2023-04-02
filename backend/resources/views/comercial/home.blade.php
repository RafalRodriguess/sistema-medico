@extends('comercial.layout')

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
            <h1 class="card-title">Bem vindo {{ request()->user('comercial')->nome }}</h1>
            </div>
        </div>
    </div>
@endsection