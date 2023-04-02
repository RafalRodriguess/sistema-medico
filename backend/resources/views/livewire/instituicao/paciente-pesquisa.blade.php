<div class="card-body">
                                    
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">
                        
                        <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">
                    
                        
                </div>
            </div>
        </div>
    </form>

    <hr>


<table class="tablesaw table-bordered table-hover table" style="overflow-wrap: anywhere">
    <thead>
        <tr>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Nome</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Telefone</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Data Nascimento</th>
            {{-- <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Endereço</th> --}}
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pacientes as $paciente)
            <tr>
                <td class="title"><a href="javascript:void(0)">{{ $paciente->id }}</a></td>
                <td>
                    {{ $paciente->nome}}
                </td>
                <td> {{ $paciente->telefone }} </td>
                <td>
                   {{ date('d/m/Y', strtotime($paciente->data_nascimento)) }}
                </td>
                <td>
                    <a href="{{ route('instituicao.visualizarPaciente', [$paciente]) }}">
                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                        data-toggle="tooltip" data-placement="top" data-original-title="Visualizar">
                                <i class="ti-search"></i>
                        </button>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        {{-- <tr>
            <td colspan="5">
                {{ $instituicoes->links() }}
            </td>
        </tr>  --}}
    </tfoot>
</table>
<div style="float: right">
    {{ $pacientes->links() }}
</div>
</div>