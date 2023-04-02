<div class="card-body">
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-5">
                <div class="form-group" style="margin-bottom: 0px !important;" wire:ignore>

                    <select name="paciente_id" class="form-control selectfild2" wire:model="paciente_id">
                        <option value="0">Todos Pacientes</option>
                        @foreach ($pacientes as $paciente)
                            <option value="{{ $paciente->id }}">{{ $paciente->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-5">
                <div class="form-group" style="margin-bottom: 0px !important;" wire:ignore>
                    <select name="medico_id" class="form-control selectfild2" wire:model="medico_id">
                        <option value="0">Todas Medicos</option>
                        @foreach ($medicos as $medico)
                            <option value="{{ $medico->id }}">{{ $medico->prestador->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            @can('habilidade_instituicao_sessao', 'cadastrar_pre_internacao')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.preInternacoes.create') }}">
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
                    <th >ID</th>
                    <th >Paciente</th>
                    <th >Previsão</th>
                    <th >Médico</th>
                    <th >Possui Responsável</th>
                    <th >Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($preInternacoes as $preInternacao)
                    <tr>
                        <td class="title"><a href="javascript:void(0)">{{ $preInternacao->id }}</a></td>
                        
                        <td>{{ $preInternacao->paciente->nome }}</td>
                        <td>{{ ($preInternacao->previsao) ? $preInternacao->previsao->format('d/m/Y H:i') : ''}}</td>
                        <td>{{ ($preInternacao->medico) ? $preInternacao->medico->nome : ''}}</td>
                        <td>{{ ($preInternacao->possui_responsavel == 1) ? 'Sim' : 'Não' }}</td>
                        <td>{{ ($preInternacao->status == 1) ? 'Ativo' : 'Inativo' }}</td>
                        <td>
                            
                            @can('habilidade_instituicao_sessao', 'editar_pre_internacao')
                                <a href="{{ route('instituicao.preInternacoes.edit', [$preInternacao]) }}">
                                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                                <i class="ti-pencil-alt"></i>
                                        </button>
                                </a>
                            @endcan
                            
                            @can('habilidade_instituicao_sessao', 'excluir_pre_internacao')
                                <form action="{{ route('instituicao.preInternacoes.destroy', [$preInternacao]) }}" method="post" class="d-inline form-excluir-registro">
                                    @method('delete')
                                    @csrf
                                    <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                            <i class="ti-trash"></i>
                                    </button>
                                </form>
                            @endcan
                        
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div style="float: right">
        {{ $preInternacoes->links() }}
    </div>
 </div>
