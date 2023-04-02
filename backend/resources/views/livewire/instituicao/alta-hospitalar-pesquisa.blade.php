<div class="card-body">
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            @can('habilidade_instituicao_sessao', 'cadastrar_pre_internacao')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.altasHospitalar.create') }}">
                        <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                        </a>
                    </div>
                </div>
            @endcan
        </div>
    </form>
 
    <hr>
 
    <div class="table-responsive">
        <table class="tablesaw table-bordered table-hover table" >
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Internacao ID</th>
                    <th scope="col">Paciente</th>
                    <th scope="col">Data Alta</th>
                    <th scope="col">Cancelada</th>
                    <th scope="col">Data Cancelamento</th>                    
            </thead>
            <tbody>
                
                
                @foreach($altasHospitalar as $altas)
                    <tr>
                        <td class="title"><a href="javascript:void(0)">{{ $altas->id }}</a></td>
                        <td>{{ $altas->internacao_id}}</td>
                        <td>{{ $altas->internacao->paciente->nome." (".$altas->internacao->paciente->cpf.")"}}</td>
                        <td>{{ date("d/m/Y H:i:s", strtotime($altas->data_alta))}}</td>
                        <td>{{ ($altas->data_cancel_alta) ? "Sim" : "Não"}}</td>
                        <td>{{ ($altas->data_cancel_alta) ? date("d/m/Y H:i:s", strtotime($altas->data_cancel_alta)) : ""}}</td>
                        <td>
                            @can('habilidade_instituicao_sessao', 'cancelar_alta_hospitalar')
                                <a href="{{ route('instituicao.altasHospitalar.edit', [$altas]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Cancelar Alta">
                                        <i class="mdi-account-remove"></i>
                                    </button>
                                </a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div style="float: right">
        {{ $altasHospitalar->links() }}
    </div>
 </div>
