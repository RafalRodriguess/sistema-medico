<div class="card-body">
                                    
    <form action="javascript:void(0)" id="FormTitular">
            <div class="row">
                <div class="row col-md-10">
                  <div class="col-md-6">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                         
                          <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">
                        
                         
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group" style="margin-bottom: 0px !important;" wire:ignore>
                         
                        <select name="tipo_id" class="form-control selectfild2" wire:model="tipo">
                            <option value="">Todos Tipos</option>
                            <option value="Texto">Texto</option>
                            <option value="Escolha Simples">Escolha Simples</option>
                            <option value="Escolha Multipla">Escolha Multipla</option>
                            <option value="Contador">Contador</option>
                        </select>

                         
                    </div>
                  </div>
                </div>
                    @can('habilidade_comercial_sessao', 'cadastrar_perguntas')
                        <div class="col-md-2">
                            <div class="form-group" style="margin-bottom: 0px !important;">
                                <a href="{{ route('comercial.produtoPerguntas.create', [$produto]) }}">
                                <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                                </a>
                            </div>
                        </div>
                    @endcan
                </div>
          </form>

          <hr>


<table class="tablesaw table-bordered table-hover table" >
    <thead>
        <tr>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Titulo</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Tipo</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Obrigatório</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($perguntas as $pergunta)
            <tr>
                <td class="title"><a href="javascript:void(0)">{{ $pergunta->id }}</a></td>
                <td>{{ $pergunta->titulo }}</td>
                <td>{{ $pergunta->tipo }}</td>
                <td>
                    @if ($pergunta->obrigatorio == true)
                        Sim                        
                    @else
                        Não
                    @endif
                </td>
                <td>

                @can('habilidade_comercial_sessao', 'editar_perguntas')
                    <a href="{{ route('comercial.produtoPerguntas.edit', [$produto, $pergunta]) }}">
                            <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                            data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                    <i class="ti-pencil-alt"></i>
                            </button>
                    </a>
                @endcan   
                @can('habilidade_comercial_sessao', 'excluir_perguntas')
                    <form action="{{ route('comercial.produtoPerguntas.destroy', [$produto, $pergunta]) }}" method="post" class="d-inline form-excluir-registro">
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
    <tfoot>
        {{-- <tr>
            <td colspan="5">
                {{ $perguntas->links() }}
            </td>
        </tr>  --}}
    </tfoot>
</table>
<div style="float: right">
    {{ $perguntas->links() }}
</div>
</div>