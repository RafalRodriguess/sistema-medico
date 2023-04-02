@foreach ($agendamentos as $key => $item)
    <div class="agendamento-item">
        <div class="col-md-12"><h4>Paciente: {{$item->pessoa->nome}} <small style="float: right">Data atendimento: {{date('d/m/Y H:i', strtotime($item->data))}}</small></h4> </div>
        <input type="hidden" name="agendamentos[{{$key}}][agendamento_id]" value="{{$item->id}}">

        <div class="card">
            <div class="card-body">
                @foreach ($item->agendamentoProcedimento as $keyProc => $proc)
                    <div class="row">
                        <input type="hidden" name="agendamento[{{$item->id}}][{{$keyProc}}][agendamento_procedimento_id]" value="{{$proc->id}}">
                        <div class="col-md-3 form-group">
                            <label class="form-control-label">Convenio</label>
                            <select class="form-control select2tabela convenio_id" name="agendamento[{{$item->id}}][{{$keyProc}}][convenio_id]" id="agendamento[{{$item->id}}][{{$keyProc}}][convenio_id]">
                                @foreach ($convenios as $convenio)
                                    <option value="{{$convenio->id}}" @if ($proc->procedimentoInstituicaoConvenio->convenios->id == $convenio->id)
                                        selected
                                    @endif>{{$convenio->nome}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="form-control-label">Procedimento</label>
                            <select class="form-control select2ProcedimentoPesquisa procedimentos" name="agendamento[{{$item->id}}][{{$keyProc}}][procedimento_id]" id="agendamento[{{$item->id}}][{{$keyProc}}][procedimento_id]">
                                <option value="{{$proc->procedimentoInstituicaoConvenio->procedimentoInstituicao->id}}">{{$proc->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->descricao}}</option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label class="form-control-label">Valor procedimento</label>
                            <input type="text" alt="decimal" class="form-control" name="agendamento[{{$item->id}}][{{$keyProc}}][valor_atual]" value="{{$proc->valor_atual}}" >
                        </div>
                        <div class="col-md-2 form-group">
                            <label class="form-control-label">Valor Repasse</label>
                            <input type="text" alt="decimal" class="form-control" name="agendamento[{{$item->id}}][{{$keyProc}}][valor_repasse]" value="{{$proc->valor_repasse}}" >
                        </div>
                        <div class="col-md-2 form-group">
                            <label class="form-control-label">Valor Convenio</label>
                            <input type="text" alt="decimal" class="form-control" name="agendamento[{{$item->id}}][{{$keyProc}}][valor_convenio]" value="{{$proc->valor_convenio}}" >
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endforeach

<script>
    $(document).ready(function(){
        $(".select2tabela").select2()
        $("input").setMask()
        $(".select2ProcedimentoPesquisa").select2({
            placeholder: "Pesquise por procedimento",
            allowClear: true,
            minimumInputLength: 3,
            language: {
            searching: function () {
                return 'Buscando procedimentos (aguarde antes de selecionar)…';
            },

            inputTooShort: function (input) {
                return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar";
            },
            },

            ajax: {
                url:"{{route('instituicao.agendamentosProcedimento.getProcedimentos')}}",
                dataType: 'json',
                delay: 100,

                data: function (params) {
                    var convenio_id = $(this).parents('.agendamento-item').find(".convenio_id").val();
                return {
                    q: params.term || '', // search term
                    page: params.page || 1,
                    convenio_id: convenio_id
                };
                },
                processResults: function (data, params) {
                params.page = params.page || 1;

                // console.log(data.results)
                return {
                    results: _.map(data.results, item => ({
                        id: Number.parseInt(item.procedimento_instituicao[0].id),
                        text: `${item.descricao}`,
                    })),
                    pagination: {
                        more: data.pagination.more
                    }
                };
                },
                cache: true
            },

        })
    })
</script>