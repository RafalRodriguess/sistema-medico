<div class="card-body">

        <form action="javascript:void(0)" id="FormTitular">
                <div class="row">
                        <div class="col-md-10">
                        <div class="form-group" style="margin-bottom: 0px !important;">

                                <input type="text" wire:model.lazy="pesquisa" id="filtro" name="filtro" class="form-control" placeholder="Pesquise por nome...">


                        </div>
                        </div>

                                                    </div>
                </form>

                <hr>


    <table class="tablesaw table-bordered table-hover table" style="overflow-wrap: anywhere">
        <thead>
            <tr>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
                <th></th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Nome Fantasia</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">CNPJ</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                    E-mail
                </th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                    Telefone
                </th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comerciais as $comercial)
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $comercial->id }}</a></td>
                    <td>
                        <img src="
                        @if ($comercial->logo)
                            {{ \Storage::cloud()->url($comercial->logo) }}
                        @endif
                        " alt="" style="width: 50px; height: 50px;">
                    </td>
                    <td>{{ $comercial->nome_fantasia }}</td>
                    <td>{{ $comercial->cnpj }}</td>
                    <td>{{ $comercial->email }}</td>
                    <td>{{ $comercial->telefone }}</td>
                    <td>

                        <a href="{{ route('comercial.eu.escolher_comercial', [$comercial]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Acessar">
                                        <i class="ti-import"></i>
                                </button>
                        </a>

                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            {{-- <tr>
                <td colspan="5">
                    {{ $comerciais->links() }}
                </td>
            </tr>  --}}
        </tfoot>
    </table>
    {{-- <div style="float: right">
        {{ $usuario->comercial->links() }}
    </div> --}}
</div>
