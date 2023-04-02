@php

//dd(request()->route());

@endphp




@extends('layouts/material')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar cliente #{$cliente->id} {$cliente->nome}",
        'breadcrumb' => [
            'Clientes' => route('clientes.index'),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('clientes.update', [$cliente]) }}" method="post">
                @method('put')
                @csrf

                <div class="form-body">

                        <h3 class="card-title">Dados pessoais</h3>
                        <hr>

                        <div class="row">


                                <div class="col-md-6">
                                        <div class="form-group @if($errors->has('nome')) has-danger @endif">
                                            <label class="form-control-label">Nome</span></label>
                                            <input type="text" name="nome" value="{{ old('nome', $cliente->nome) }}"
                                                class="form-control @if($errors->has('nome')) form-control-danger @endif">
                                            @if($errors->has('nome'))
                                                <div class="form-control-feedback">{{ $errors->first('nome') }}</div>
                                            @endif
                                        </div>
                                </div>

                                <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Tipo <span class="text-danger">*</span></label>
                                            <select name="tipo" class="form-control" data-placeholder="Selecione" tabindex="1">
                                                <option @if(old('tipo', $cliente->tipo) == 'fisica') selected="selected" @endif value="fisica">Física</option>
                                                <option @if(old('tipo', $cliente->tipo) == 'juridica') selected="selected" @endif value="juridica">Jurírica</option>
                                            </select>
                                        </div>
                                    </div>

                                <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Data de Nascimento</label>
                                            <input type="text" name="data_nascimento" alt="date" class="form-control" value="{{ old('data_nascimento', $cliente->data_nascimento) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">CPF/CNPJ</label>
                                                <input type="text" name="cpf_cnpj" value="{{ old('cpf_cnpj', $cliente->cpf_cnpj) }}" class="form-control">
                                            </div>
                                    </div>

                        </div>

                        <div class="row">

                                <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">RG</label>
                                            <input type="text" name="rg" value="{{ old('rg', $cliente->rg) }}" class="form-control">
                                        </div>
                                </div>

                                <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Telefone principal</label>
                                            <input type="text" name="telefone_1" alt="phone" value="{{ old('telefone_1', $cliente->telefone_1) }}" class="form-control">
                                        </div>
                                </div>

                                <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Telefone secundário</label>
                                            <input type="text" name="telefone_2" alt="phone" value="{{ old('telefone_2', $cliente->telefone_2) }}" class="form-control">
                                        </div>
                                </div>

                                <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Email</label>
                                            <input type="text" name="email" value="{{ old('email', $cliente->email) }}" class="form-control">
                                        </div>
                                </div>

                        </div>

                        <h3 class="card-title">Endereço</h3>
                        <hr>

                        <div class="row">

                                <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Rua</label>
                                            <input type="text" name="rua" value="{{ old('rua', $cliente->rua) }}" class="form-control">
                                        </div>
                                </div>

                                <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Número</label>
                                            <input type="text" name="numero" value="{{ old('numero', $cliente->numero) }}" class="form-control">
                                        </div>
                                </div>

                                <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Bairro</label>
                                            <input type="text" name="bairro" value="{{ old('bairro', $cliente->bairro) }}" class="form-control">
                                        </div>
                                </div>

                                <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Cidade</label>
                                            <input type="text" name="cidade" value="{{ old('cidade', $cliente->cidade) }}" class="form-control">
                                        </div>
                                </div>


                        </div>

                        <div class="row">

                                <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Estado</label>
                                            <input type="text" name="estado" value="{{ old('estado', $cliente->estado) }}" class="form-control">
                                        </div>
                                </div>

                                <div class="col-md-7">
                                        <div class="form-group">
                                            <label class="control-label">Complemento</label>
                                            <input type="text" name="complemento" value="{{ old('complemento', $cliente->complemento) }}" class="form-control">
                                        </div>
                                </div>

                        </div>

                        <h3 class="card-title">Adicionais</h3>
                        <hr>

                        <div class="row">

                                <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Observações:</label>
                                            <textarea name="obs" cols="30" class="form-control">{{ old('obs', $cliente->obs) }}</textarea>
                                        </div>
                                </div>

                        </div>

                </div>


                <div class="form-group text-right">
                        <a href="{{ route('clientes.index') }}">
                                <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                        </a>
                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
