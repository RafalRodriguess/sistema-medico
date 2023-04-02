<table class="tablesaw table-bordered table-hover table" style="overflow-wrap: anywhere">
    <thead>
        <tr>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Nome</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">CPF</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pacientes as $pessoa)
            <tr>
                <td class="title"><a href="javascript:void(0)">{{ $pessoa->id }}</a></td>
                <td>{{ $pessoa->nome}}</td>
                <td>{{ $pessoa->cpf }}</td>
                <td><button type="button" class="btn btn-xs btn-secondary selectPaciente" value="{{ $pessoa->id }}" onClick="getPaciente(this.value)")><i class="mdi mdi-check"></i></button></td>
            </tr>
        @endforeach
    </tbody>
</table>