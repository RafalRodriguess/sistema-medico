@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Configurar insituição #{$instituicao->id} {$instituicao->nome}",
        'breadcrumb' => [
            'Usuários' => route('instituicao.instituicoes_usuarios.index'),
            'vincular contas',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('instituicao.configuracoes.salvarConfig', [$instituicao]) }}" method="post" id="form">
                @csrf

                <div class="form-group border p-3">
                    <h3 class="form-control-label">Campos obrigatórios no paciente</h3>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="checkbox checkbox-primary col-sm-3">
                            <input id="checkbox-paciente-cpf" type="checkbox" @if(!empty($config->pessoas->cpf)) checked @endif name="pessoas[cpf]">
                            <label for="checkbox-paciente-cpf"> CPF </label>
                        </div>

                        <div class="checkbox checkbox-primary col-sm-3" >
                            <input id="checkbox-paciente-nascimento" type="checkbox" @if(!empty($config->pessoas->nascimento)) checked @endif name="pessoas[nascimento]">
                            <label for="checkbox-paciente-nascimento"> Data Nascimento </label>
                        </div>

                        <div class="checkbox checkbox-primary col-sm-3" >
                            <input id="checkbox-paciente-telefone1" type="checkbox" @if(!empty($config->pessoas->telefone1)) checked @endif name="pessoas[telefone1]">
                            <label for="checkbox-paciente-telefone1"> Telefone 1 </label>
                        </div>

                        <div class="checkbox checkbox-primary col-sm-3" >
                            <input id="checkbox-paciente-telefone2" type="checkbox" @if(!empty($config->pessoas->telefone2)) checked @endif name="pessoas[telefone2]">
                            <label for="checkbox-paciente-telefone2"> Telefone 2 </label>
                        </div>

                        <div class="checkbox checkbox-primary col-sm-3" >
                            <input id="checkbox-paciente-telefone3" type="checkbox" @if(!empty($config->pessoas->telefone3)) checked @endif name="pessoas[telefone3]">
                            <label for="checkbox-paciente-telefone3"> Telefone 3 </label>
                        </div>

                        <div class="checkbox checkbox-primary col-sm-3" >
                            <input id="checkbox-paciente-endereco" type="checkbox" @if(!empty($config->pessoas->endereco)) checked @endif name="pessoas[endereco]">
                            <label for="checkbox-paciente-endereco"> Endereço </label>
                        </div>

                        <div class="checkbox checkbox-primary col-sm-3" >
                            <input id="checkbox-paciente-sexo" type="checkbox" @if(!empty($config->pessoas->sexo)) checked @endif name="pessoas[sexo]">
                            <label for="checkbox-paciente-sexo"> Sexo </label>
                        </div>
                    </div>
                    
                </div>

                <div class="form-group border p-3">
                    <h3 class="form-control-label">Limite de agendamento de encaixe</h3>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="form-group col-sm-3">
                            <label class="form-control-label">Max de encaixes <span><i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Deixe zerado para infinitos encaixes"></i></span></label>
                            <input type="text" alt="numeric" name="agendamentos[max_encaixe]" value="{{ (!empty($config->agendamentos->max_encaixe)) ? $config->agendamentos->max_encaixe : 0}}"  class="form-control form-control-danger">
                        </div>
                    </div>
                    
                </div>

                <div class="form-group border p-3">
                    <h3 class="form-control-label">Modelos de recibo</h3>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label class="form-control-label">Modelo de recibo contas a receber</label>
                            <select name="modelo_recibo[modelo_receber_id]" class="form-control">
                                <option value="">Selecione</option>
                                @foreach($modelos_recibo as $item)
                                    <option value="{{$item->id}}" @if(!empty($config->modelo_recibo->modelo_receber_id) && $config->modelo_recibo->modelo_receber_id == $item->id) selected @endif>
                                        {{$item->descricao}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="form-control-label">Modelo de recibo contas a pagar</label>
                            <select name="modelo_recibo[modelo_pagar_id]" class="form-control">
                                <option value="">Selecione</option>
                                @foreach($modelos_recibo as $item)
                                    <option value="{{$item->id}}" @if(!empty($config->modelo_recibo->modelo_pagar_id) && $config->modelo_recibo->modelo_pagar_id == $item->id) selected @endif>
                                        {{$item->descricao}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="form-control-label">Modelo de recibo atendimento</label>
                            <select name="modelo_recibo[modelo_atendimento_id]" class="form-control">
                                <option value="">Selecione</option>
                                @foreach($modelos_recibo as $item)
                                    <option value="{{$item->id}}" @if(!empty($config->modelo_recibo->modelo_atendimento_id) && $config->modelo_recibo->modelo_atendimento_id == $item->id) selected @endif>
                                        {{$item->descricao}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                </div>

                <div class="form-groupn text-right">
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts');
    <script>

        $( document ).ready(function() {
            $("#form").submit(function(e){
                console.log()
                e.preventDefault()

                var formData = new FormData($(this)[0]);

                $.ajax("{{ route('instituicao.configuracoes.salvarConfig', [$instituicao]) }}", {
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        $.toast({
                            heading: response.title,
                            text: response.text,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: response.icon,
                            hideAfter: 3000,
                            stack: 10
                        });
                    },
                    error: function (response) {
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

            $("input").setmask();
        })
    </script>
@endpush
