

@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Escala Médica',
        'breadcrumb' => [
            'Escalas Médicas' => route('instituicao.escalas-medicas.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.escalas-medicas.store') }}" method="post">
                @csrf

                <div class="row">
                    <div class="col-md-10 form-group @if($errors->has('regra')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Regra <span class="text-danger">*</span></label>
                        <input type="text" name="regra" class="form-control" value="{{ old('regra') }}">
                        @if($errors->has('regra'))
                            <small class="form-control-feedback">{{ $errors->first('regra') }}</small>
                        @endif
                    </div>
                    <div class="col-md-2 form-group @if($errors->has('data')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Data <span class="text-danger">*</span></label>
                        <input type="date" name="data" class="form-control" value="{{ old('data') }}">
                        @if($errors->has('data'))
                            <small class="form-control-feedback">{{ $errors->first('data') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div id="especialidades" class="col-md-4 form-group @if($errors->has('especialidade_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Especialidade <span class="text-danger">*</span></label>
                        <select class="form-control p-0 m-0"
                            value="{{ old('especialidade_id') }}" name="especialidade_id">
                            <option selected disabled>Selecione</option>
                            @foreach ($especialidades as $especialidade)
                                <option value="{{ $especialidade->id }}" @if (old('especialidade_id')==$especialidade->id)
                                    selected
                                @endif >{{ $especialidade->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('especialidade_id'))
                            <small class="form-control-feedback">{{ $errors->first('especialidade_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md-4 form-group @if($errors->has('origem_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Origem</label>
                        <select class="form-control p-0 m-0" name="origem_id">
                            <option selected disabled>Selecione</option>
                            @foreach ($origens as $origem)
                                <option value="{{ $origem->id }}" @if (old('origem_id')==$origem->id)
                                    selected
                                @endif >{{ $origem->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('origem_id'))
                            <small class="form-control-feedback">{{ $errors->first('origem_id') }}</small>
                        @endif
                    </div>
                    {{-- <div class="col-md-2 form-group @if($errors->has('horario_inicio')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Horário de Inicio <span class="text-danger">*</span></label>
                        <input type="time" name="horario_inicio" class="form-control" value="{{ old('horario_inicio') }}">
                        @if($errors->has('horario_inicio'))
                            <small class="form-control-feedback">{{ $errors->first('horario_inicio') }}</small>
                        @endif
                    </div>
                    <div class="col-md-2 form-group @if($errors->has('horario_termino')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Horário de termino <span class="text-danger">*</span></label>
                        <input type="time" name="horario_termino" class="form-control" value="{{ old('horario_termino') }}">
                        @if($errors->has('horario_termino'))
                            <small class="form-control-feedback">{{ $errors->first('horario_termino') }}</small>
                        @endif
                    </div> --}}
                </div>

                <div class="col-sm-12 p-0 m-0 mb-3">
                    <div class="row p-0 m-0 pt-2 pb-2" id="prestadores-lista">
                        @if (old('prestadores'))
                            @for ($i = 0; $i < count(old('prestadores')); $i++)
                                <div class="card col-sm-12 shadow-none prestador-item p-0 m-0 mb-2 mt-1" id="{{ $i }}">
                                    <div class="row bg-light border-bottom d-flex justify-content-end p-0 m-0">
                                        <div class="col d-flex p-2 m-0">
                                            <div class="row col-sm-12 d-flex justify-content-between align-self-center p-0 m-0">
                                                <span class="text-dark">
                                                    {{ App\Especialidade::find(old('especialidade_id'))->nome }} #{{ $i }}
                                                </span>
                                                <button onclick="$(this).parent().parent().parent().parent().remove();"
                                                    type="button" class="btn btn-danger">
                                                    <i class="ti-close"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-1 m-0">
                                        <div class="col-md-4 form-group @if($errors->has("prestadores.{$i}.prestador_id")) has-danger @endif">
                                            <label class="form-control-label p-0 m-0">Prestadores <span class="text-danger">*</span></label>
                                            <select class="form-control p-0 m-0 select2Live old-prestadores"
                                                data-prev="{{ old("prestadores.{$i}.prestador_id") }}"
                                                name="prestadores[{{$i}}][prestador_id]" value='{{ old("prestadores.{$i}.prestador_id") }}'>
                                                <option selected disabled>Selecione</option>
                                            </select>
                                            @if($errors->has("prestadores.{$i}.prestador_id"))
                                                <small class="form-control-feedback">{{ $errors->first("prestadores.{$i}.prestador_id") }}</small>
                                            @endif
                                        </div>
                                        <div class="col-md-2 form-group @if($errors->has("prestadores.{$i}.entrada")) has-danger @endif">
                                            <label class="form-control-label p-0 m-0">Horário de Entrada <span class="text-danger">*</span></label>
                                            <input type="time" class="form-control"
                                                name="prestadores[{{$i}}][entrada]" value='{{ old("prestadores.{$i}.entrada") }}'>
                                            @if($errors->has("prestadores.{$i}.entrada"))
                                                <small class="form-control-feedback">{{ $errors->first("prestadores.{$i}.entrada") }}</small>
                                            @endif
                                        </div>
                                        <div class="col-md-2 form-group @if($errors->has("prestadores.{$i}.saida")) has-danger @endif">
                                            <label class="form-control-label p-0 m-0">Horário de Saída <span class="text-danger">*</span></label>
                                            <input type="time" class="form-control"
                                                name="prestadores[{{$i}}][saida]" value='{{ old("prestadores.{$i}.saida") }}'>
                                            @if($errors->has("prestadores.{$i}.saida"))
                                                <small class="form-control-feedback">{{ $errors->first("prestadores.{$i}.saida") }}</small>
                                            @endif
                                        </div>
                                        <div class="col-md-4 form-group @if($errors->has("prestadores.{$i}.observacao")) has-danger @endif">
                                            <label class="form-control-label p-0 m-0">Observação</label>
                                            <input type="text" class="form-control"
                                                name="prestadores[{{$i}}][observacao]" value='{{ old("prestadores.{$i}.observacao") }}'>
                                            @if($errors->has("prestadores.{$i}.observacao"))
                                                <small class="form-control-feedback">{{ $errors->first("prestadores.{$i}.observacao") }}</small>
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
                        <span class="title text-dark">Prestadores</span>
                    </div>
                    <div class="col-1 d-flex p-2 m-0">
                        <div class="row col-sm-12 d-flex justify-content-end align-self-center p-0 m-0">
                            <button type="button" class="btn btn-primary" id="adiciona-prestador">
                                <i class="ti-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.escalas-medicas.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')

    <script type="text/template" id="prestador-option">
        <option class="prestador-option"></option>
    </script>

    <script type="text/template" id="prestador-item">
        <div class="card col-sm-12 shadow-none prestador-item p-0 m-0 mb-2 mt-1">
            <div class="row bg-light border-bottom d-flex justify-content-end p-0 m-0">
                <div class="col d-flex p-2 m-0">
                    <div class="row col-sm-12 d-flex justify-content-between align-self-center p-0 m-0">
                        <span class="text-dark prestador-titulo"></span>
                        <button
                            onclick="$(this).parent().parent().parent().parent().remove();"
                            type="button" class="btn btn-danger">
                            <i class="ti-close"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row p-1 m-0">
                <div id="prestadores" class="col-md-4 form-group">
                    <label class="form-control-label p-0 m-0">Prestadores <span class="text-danger">*</span></label>
                    <select class="form-control p-0 m-0 campo prestador_id prestadores-options">
                        <option selected disabled>Selecione</option>
                    </select>
                </div>
                <div class="col-md-2 form-group">
                    <label class="form-control-label p-0 m-0">Horário de Entrada <span class="text-danger">*</span></label>
                    <input type="time" class="form-control campo entrada">
                </div>
                <div class="col-md-2 form-group">
                    <label class="form-control-label p-0 m-0">Horário de Saída <span class="text-danger">*</span></label>
                    <input type="time" class="form-control campo saida">
                </div>
                <div class="col-md-4 form-group">
                    <label class="form-control-label p-0 m-0">Observação</label>
                    <input type="text" class="form-control campo observacao">
                </div>
            </div>
        </div>
    </script>

    <script type="text/javascript">

        $(document).ready(() => {

            var especialidade = {};

            $('.old-prestadores').each(function(){
                let value = $(this).data('prev');
                let prestadorOptions = $(this)[0];
                if (value > 0) {
                    $.ajax({
                        url: '{{ route("instituicao.getPrestadoresByEspecialidade") }}',
                        method: 'POST', dataType: 'json',
                        data: {
                            especialidade_id : value,
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            if (response.length > 0) {
                                response.forEach(function (prestador) {
                                    let prestadorOption = $($('#prestador-option').html())[0];
                                    prestadorOption.value = prestador.id
                                    prestadorOption.textContent = prestador.nome
                                    if(prestador.id==value) prestadorOption.selected = true;
                                    prestadorOptions.appendChild(prestadorOption);
                                });
                            }
                        }
                    })
                } else {
                    let selectedEspecialidadeID = $('#especialidades select option:selected').val();
                    if (selectedEspecialidadeID > 0) {
                        $.ajax({
                            url: '{{ route("instituicao.getPrestadoresByEspecialidade") }}',
                            method: 'POST', dataType: 'json',
                            data: {
                                especialidade_id : selectedEspecialidadeID,
                                '_token': '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                if (response.length > 0) {
                                    response.forEach(function (prestador) {
                                        let prestadorOption = $($('#prestador-option').html())[0];
                                        prestadorOption.value = prestador.id
                                        prestadorOption.textContent = prestador.nome
                                        prestadorOptions.appendChild(prestadorOption);
                                    });
                                }
                            }
                        })
                    }
                }
            });

            function hasClass(elemento, classe) {
                return (' '+elemento.className+' ').indexOf(' '+classe+' ')>-1;
            }

            const nomes_campos = [
                'prestador_id',
                'entrada',
                'saida',
                'observacao'
            ];

            function addPrestador(){
                $('#adiciona-prestador').on('click', function(){

                    if(especialidade.id){
                        let prestadorItem = $($('#prestador-item').html())[0];
                        let prestadoresLista = document.querySelector('#prestadores-lista');
                        let indice = prestadoresLista.querySelectorAll('.prestador-item').length;
                        let campos = prestadorItem.querySelectorAll('.campo');
                        campos.forEach((campo)=>{
                            nomes_campos.forEach((nome_campo)=>{
                                let novo_nome = `prestadores[${indice}][${nome_campo}]`;
                                if(hasClass(campo, nome_campo)) campo.name = novo_nome;
                            });
                        });
                        prestadorItem.id = indice;
                        prestadorItem.querySelector('.prestador-titulo').textContent = `${especialidade.nome} #${indice}`;
                        $.ajax({
                            url: '{{ route("instituicao.getPrestadoresByEspecialidade") }}',
                            method: 'POST', dataType: 'json',
                            data: {
                                especialidade_id : especialidade.id,
                                '_token': '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                if (response.length == 0) {
                                    Swal.fire('Não permitido', `Não há nenhum prestador com
                                    a especialidade ${especialidade.nome} na instituição`, 'info');
                                    return;
                                }
                                if (response.length > 0) {
                                    response.forEach(function (prestador) {
                                        let prestadorOption = $($('#prestador-option').html())[0];
                                        prestadorOption.value = prestador.id
                                        prestadorOption.textContent = prestador.nome
                                        prestadorItem.querySelector('.prestadores-options').appendChild(prestadorOption);
                                        prestadoresLista.appendChild(prestadorItem);
                                        prestadorItem.scrollIntoView();
                                    });
                                }
                            }
                        })
                    } else {
                        Swal.fire('Não permitido', 'Selecione uma especialidade', 'info')
                    }
                });
            }

            function blockOrAllowSubmit(especialidade_id){
                $.ajax({
                    url: '{{ route("instituicao.getPrestadoresByEspecialidade") }}',
                    method: 'POST', dataType: 'json',
                    data: {
                        especialidade_id : especialidade_id,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if(response.length == 0){
                            let btnSubmit = $('button[type="submit"]');
                            btnSubmit.attr('disabled', true);
                        }
                    }
                })
            }



            async function setEspecialidadeID(){
                let especialidadeOption = $('#especialidades select option:selected');
                if(especialidadeOption.val() > 0) {
                    let prevPrestadores = $('#prestadores-lista .prestador-item');
                    if(especialidade.id && prevPrestadores.length > 0){
                        let mensagemUM = `
                            Há um campo de prestador com especialidade ${especialidade.nome} aberto`;
                        let mensagemVarios = `
                            Há ${prevPrestadores.length} campos de prestador com
                            especialidade ${especialidade.nome} abertos`;
                        let mensagem = (prevPrestadores.length==1)? mensagemUM: mensagemVarios;
                        let isConfirm = await Swal.fire({
                            title: "Tem certeza?",
                            text: mensagem,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: 'Sim, trocar especialidade!',
                            cancelButtonText: "Não, cancelar!",
                        })
                        if (isConfirm.value) {
                            $('.prestador-option').each(function(){ $(this).remove(); });
                            $('.prestador-item').each(function(){ $(this).remove(); });
                            especialidade.id = especialidadeOption.val();
                            especialidade.nome = especialidadeOption.text();
                            blockOrAllowSubmit(especialidade.id);
                        } else {
                            $('#especialidades select option').each(function(){
                                if ($(this).val()==especialidade.id) $(this)[0].selected = true;
                            })
                            return;
                        }
                    }
                    especialidade.id = especialidadeOption.val();
                    especialidade.nome = especialidadeOption.text();
                    blockOrAllowSubmit(especialidade.id);
                }
            }

            $('#especialidades select').on('change', function() {
                setEspecialidadeID();
            });

            setEspecialidadeID();

            addPrestador();

        });
    </script>
@endpush
