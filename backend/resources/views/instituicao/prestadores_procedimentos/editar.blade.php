@extends('instituicao.layout')

@section('conteudo')
@component('components/page-title', [
'titulo' => 'Vincular Convênios',
'breadcrumb' => [
'Administração',
'Prestadores' => route('instituicao.prestadores.index'),
"Editar #{$procedimento->id} {$procedimento->descricao}",
],
])
@endcomponent


<div class="card">
    <div class="card-body">
        <form action="{{ route('instituicao.salvar.procedimentos.editar') }}" method="post">
            @csrf
            <input type="hidden" name="instituicao_prestador" value="{{$instituicao_prestador->id}}">
            <input type="hidden" name="procedimento" value="{{$procedimento->id}}">
            <input type="hidden" name="desativados">
            <div class="row">

                <div class="col-md-12 form-group">
                    <br>
                    <h3>{{$prestador->nome}}</h3>
                   <strong> {{$procedimento->descricao}}</strong>

                </div>




            </div>


            <div id="procedimentos" class="row">



                @foreach ($convenios as $convenio)
                <div class="col-md-6 itensConv">
                    <div class="row" style="background-color: #f0f0f0;margin:10px;    padding: 10px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Status:</label>
                                <div class="switch">
                                    <label> Desativado<input name="input_procedimento[{{$convenio['id']}}]" class="inputCheck @if(isset($arrayconvenios_ativo[$convenio['procedimento_convenio_instuicao'][0]['pivot']['id']])) ja_ativado  @endif" type="checkbox" value="{{$convenio['id']}}" @if(isset($arrayconvenios_ativo[$convenio['procedimento_convenio_instuicao'][0]['pivot']['id']])) checked onclick="desativa($(this))" data-idconvenio="{{$arrayconvenios_ativo[$convenio['procedimento_convenio_instuicao'][0]['pivot']['id']]}}" @endif><span class="lever"></span>Ativado
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h2><span class="nameConv">
                                    {{$convenio['nome']}}

                                </span><br>
                            </h2>
                            <small>
                                {{$convenio['descricao']}}
                            </small>
                        </div>
                    </div>

                </div>
                @endforeach
            </div>
            <div class="form-group text-right">
                <a href="{{ route('instituicao.prestadores.procedimentos', [$prestador->id]) }}">
                    <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                </a>
                <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection


<style>
    .itensConv {

        border-bottom: solid 1px #7c787894;
        padding: 15px 0px;
        margin: 20px 0px;
    }

    .btnRemoveProc {
        margin-top: 30px;
        background: #ff00009c !important;
        color: white !important;
        border: none !important;
    }
</style>


<script>
    function desativa(el) {
        var arr = [];
        $(".ja_ativado").each(function(index) {
            if(!$( this )[0].checked){
                arr.push($(this).data('idconvenio'));
            }
        });
        $('input[name=desativados]').val(arr)
    }
</script>

