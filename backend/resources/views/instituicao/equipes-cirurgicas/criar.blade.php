

@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Equipe Cirúrgica',
        'breadcrumb' => [
            'Equipes Cirúrgicas' => route('instituicao.centros.equipes.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.centros.equipes.store') }}" method="post">
                @csrf

                <div class="row">
                    <div class="col-md-12 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição <span class="text-danger">*</span></label>
                        <input type="text" name="descricao" class="form-control" value="{{ old('descricao') }}">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>
                </div>

                <div class="col-sm-12 p-0 m-0 mb-3">
                    <div class="row p-0 m-0 pt-2 pb-2" id="equipes-cirurgicas-prestadores-lista">
                        <div class="card col-sm-12 shadow-none equipe-cirurgica-prestador-item p-0 m-0 mb-2 mt-1" id="0">
                            <div class="row bg-light border-bottom d-flex
                            justify-content-end p-0 m-0">
                                <div class="col d-flex p-2 m-0">
                                    <div class="row col-sm-12 d-flex justify-content-between align-self-center p-0 m-0">
                                        <span class="text-dark prestador-titulo">
                                            Prestador #0
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row p-1 m-0">
                                <div id="prestadores" class="col-md-6 form-group @if($errors->has("prestadores.{0}.tipo")) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Tipo <span class="text-danger">*</span></label>
                                    <select class="form-control p-0 m-0 campo" name="prestadores[0][tipo]">
                                        <option selected disabled>Selecione</option>
                                        @foreach ($tipos as $tipo)
                                        <option value="{{ $tipo }}"
                                            @if ($tipo == 1)
                                                selected
                                            @else
                                                disabled readonly
                                            @endif >{{ App\EquipeCirurgica::getTipoTexto($tipo) }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has("prestadores.{0}.tipo"))
                                        <small class="form-control-feedback">{{ $errors->first("prestadores.{0}.tipo") }}</small>
                                    @endif
                                </div>
                                <div id="prestadores" class="col-md-6 form-group @if($errors->has("prestadores.0.prestador_id")) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Prestador <span class="text-danger">*</span></label>
                                    <select class="form-control p-0 m-0" name="prestadores[0][prestador_id]">
                                        <option selected disabled>Selecione</option>
                                        @foreach ($prestadores as $prestador)
                                        <option value="{{ $prestador->id }}"
                                            @if (old("prestadores.{0}.prestador_id") == $prestador->id)
                                            selected
                                        @endif>{{ $prestador->nome }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has("prestadores.0.prestador_id"))
                                        <small class="form-control-feedback">{{ $errors->first("prestadores.0.prestador_id") }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if (old('prestadores'))
                            @for ($i = 1; $i < count(old('prestadores')); $i++)
                                <div class="card col-sm-12 shadow-none equipe-cirurgica-prestador-item p-0 m-0 mb-2 mt-1"
                                id="{{ $i }}">
                                    <div class="row bg-light border-bottom d-flex
                                    justify-content-end p-0 m-0">
                                        <div class="col d-flex p- m-0">
                                            <div class="row col-sm-12 d-flex justify-content-between align-self-center p-0 m-0">
                                                <span class="text-dark prestador-titulo">
                                                    Prestador #{{ $i }}
                                                </span>
                                                <button
                                                    onclick="$(this).parent().parent().parent().parent().remove();"
                                                    type="button" class="btn btn-danger">
                                                    <i class="ti-close"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-1 m-0">
                                        <div id="prestadores" class="col-md-6 form-group @if($errors->has("prestadores.{$i}.tipo")) has-danger @endif">
                                            <label class="form-control-label p-0 m-0">Tipo <span class="text-danger">*</span></label>
                                            <select class="form-control p-0 m-0 campo tipo prestadores-options" name="prestadores[{{ $i }}][tipo]">
                                                <option selected disabled>Selecione</option>
                                                @foreach ($tipos as $tipo)
                                                <option value="{{ $tipo }}"
                                                    @if (old("prestadores.{$i}.tipo") == $tipo)
                                                    selected
                                                @endif>{{ App\EquipeCirurgica::getTipoTexto($tipo) }}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has("prestadores.{$i}.tipo"))
                                                <small class="form-control-feedback">{{ $errors->first("prestadores.{$i}.tipo") }}</small>
                                            @endif
                                        </div>
                                        <div id="prestadores" class="col-md-6 form-group @if($errors->has("prestadores.{$i}.prestador_id")) has-danger @endif">
                                            <label class="form-control-label p-0 m-0">Prestador <span class="text-danger">*</span></label>
                                            <select class="form-control p-0 m-0 campo prestador_id prestadores-options" name="prestadores[{{ $i }}][prestador_id]">
                                                <option selected disabled>Selecione</option>
                                                @foreach ($prestadores as $prestador)
                                                <option value="{{ $prestador->id }}"
                                                    @if (old("prestadores.{$i}.prestador_id") == $prestador->id)
                                                    selected
                                                @endif>{{ $prestador->nome }}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has("prestadores.{$i}.prestador_id"))
                                                <small class="form-control-feedback">{{ $errors->first("prestadores.{$i}.prestador_id") }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        @endif
                    </div>
                </div>

                <div class="row bg-light d-flex border justify-content-between p-0 m-0 mb-3">
                    <div class="col-3 p-3 m-0">
                        <span class="title text-dark">Prestador</span>
                    </div>
                    <div class="col-1 d-flex p-2 m-0">
                        <div class="row col-sm-12 d-flex justify-content-end align-self-center p-0 m-0">
                            <button type="button" class="btn btn-primary" id="adiciona-equipe-cirurgica-prestador">
                                <i class="ti-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.centros.equipes.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>



@endsection

@push('scripts')

    <script type="text/template" id="equipe-cirurgica-prestador-item">

        <div class="card col-sm-12 shadow-none equipe-cirurgica-prestador-item p-0 m-0 mb-2 mt-1">
            <div class="row bg-light border-bottom d-flex justify-content-end p-0 m-0">
                <div class="col d-flex p-2 m-0">
                    <div class="row col-sm-12 d-flex justify-content-between align-self-center p-0 m-0">
                        <span class="text-dark prestador-titulo"></span>
                        <button onclick="$(this).parent().parent().parent().parent().remove();"
                            type="button" class="btn btn-sm btn-danger">
                            <i class="ti-close"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row p-1 m-0">
                <div id="prestadores" class="col-md-6 form-group">
                    <label class="form-control-label p-0 m-0">Tipo <span class="text-danger">*</span></label>
                    <select class="form-control p-0 m-0 campo tipo prestadores-options">
                        <option selected disabled>Selecione</option>
                        @foreach ($tipos as $tipo)
                            <option value="{{ $tipo }}">{{ App\EquipeCirurgica::getTipoTexto($tipo) }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="prestadores" class="col-md-6 form-group">
                    <label class="form-control-label p-0 m-0">Prestadores <span class="text-danger">*</span></label>
                    <select class="form-control p-0 m-0 campo prestador_id prestadores-options">
                        <option selected disabled>Selecione</option>
                        @foreach ($prestadores as $prestador)
                            <option value="{{ $prestador->id }}">{{ $prestador->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </script>

    <script type="text/javascript">

        $(document).ready(() => {

            const nomes_campos = [
                'tipo',
                'prestador_id',
            ];

            function hasClass(elemento, classe) {
                return (' '+elemento.className+' ').indexOf(' '+classe+' ')>-1;
            }


            function addEquipeCirurgicaPrestador()
            {
                $('#adiciona-equipe-cirurgica-prestador').on('click', function(){
                    let equipeCirurgicaPrestador = $($('#equipe-cirurgica-prestador-item').html())[0];
                    let equipeCirurgicaPrestadoresLista = $('#equipes-cirurgicas-prestadores-lista')[0];

                    let indice = equipeCirurgicaPrestadoresLista.querySelectorAll('.equipe-cirurgica-prestador-item').length;
                    let campos = equipeCirurgicaPrestador.querySelectorAll('.campo');
                    campos.forEach((campo)=>{
                        nomes_campos.forEach((nome_campo)=>{
                            let novo_nome = `prestadores[${indice}][${nome_campo}]`;
                            if(hasClass(campo, nome_campo)) campo.name = novo_nome;
                        });
                    });
                    equipeCirurgicaPrestador.id = indice;
                    equipeCirurgicaPrestador.querySelector('.prestador-titulo').textContent = `Prestador #${indice}`;
                    equipeCirurgicaPrestadoresLista.appendChild(equipeCirurgicaPrestador);
                });
            }

            addEquipeCirurgicaPrestador();

        });
    </script>
@endpush
