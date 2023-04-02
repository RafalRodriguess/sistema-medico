<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">

                        <input type="text" id="filtro" name="filtro" class="form-control" wire:model.lazy="filtro" placeholder="Pesquise por nome...">


                </div>
            </div>
        </div>
    </form>

    <hr>

    <div class="table-responsive">
        <table class="tablesaw table-bordered table-hover table" style="overflow-wrap: anywhere">
            <thead>
                <tr>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
                    <th></th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Nome</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($instituicoes as $instituicao)
                    <tr style="cursor: pointer;" onclick="acessar_insituicao('{{ route('instituicao.eu.escolher_instituicao', [$instituicao]) }}')">
                        <td class="title"><a href="javascript:void(0)">{{ $instituicao->id }}</a></td>
                        <td>
                            <img src="
                            @if ($instituicao->imagem)
                                {{ \Storage::cloud()->url($instituicao->imagem) }}
                            @endif
                            " alt="" style="width: 50px; height: 50px;">
                        </td>
                        <td> {{ $instituicao->nome }} </td>
                        <td>
                            <a href="{{ route('instituicao.eu.escolher_instituicao', [$instituicao]) }}">
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
                        {{ $instituicoes->links() }}
                    </td>
                </tr>  --}}
            </tfoot>
        </table>
    </div>
    <div style="float: right">
        {{ $instituicoes->links() }}
    </div>
</div>