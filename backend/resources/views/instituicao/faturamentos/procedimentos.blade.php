@extends('instituicao.layout')

    @section('conteudo')
    @component('components/page-title', [
        'titulo' => "Faturamento #{$faturamento->id} {$faturamento->descricao}",
        'breadcrumb' => [
            'Faturamento' => route('instituicao.faturamento.index'),
            'Procedimentos',
        ],
        ])
    @endcomponent

    <div class="card">
        <div class="card-body ">
            <h3>Novos procedimentos</h3>
            <input type="hidden" name="faturamento_id" value="{{$faturamento->id}}" id="faturamento_id">
            <form action="{{ route('instituicao.faturamento.salvarProcedimento', [$faturamento]) }}" method="post">
                @method('post')
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="row procedimento-itens">

                            @php($oldProc = old('proc') ?: [])
                            @for($i = 0, $max = count($oldProc); $i < $max; $i++)
                                <div class="col-md-12 item-proc">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="javascrit:void(0)" class="small remove-proc">(remover)</a>
                                        </div>
                                        <div class="form-group col-md-2 @if($errors->has("proc.{$i}.data_vigencia")) has-danger @endif">
                                            <label class="form-control-label">Data vigência *</span></label>
                                            <input type="text" alt="date" class="form-control @if($errors->has("proc.{$i}.data_vigencia")) form-control-danger @endif" name="proc[{{$i}}][data_vigencia]" id="proc[{{$i}}][data_vigencia]" value="{{old("proc.{$i}.data_vigencia")}}">
                                            @if($errors->has("proc.{$i}.data_vigencia"))
                                                <div class="form-control-feedback">{{ $errors->first("proc.{$i}.data_vigencia") }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group col-md-2 @if($errors->has("proc.{$i}.procedimento_id")) has-danger @endif">
                                            <label class="form-control-label">Procedimento *</span></label>
                                            <input type="number" class="form-control @if($errors->has("proc.{$i}.procedimento_id")) form-control-danger @endif" name="proc[{{$i}}][procedimento_id]" id="proc[{{$i}}][procedimento_id]" value="{{old("proc.{$i}.procedimento_id")}}">
                                            @if($errors->has("proc.{$i}.procedimento_id"))
                                                <div class="form-control-feedback">{{ $errors->first("proc.{$i}.procedimento_id") }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group col-md-4 @if($errors->has("proc.{$i}.descricao")) has-danger @endif">
                                            <label class="form-control-label">Descrição *</span></label>
                                            <input type="text" class="form-control @if($errors->has("proc.{$i}.descricao")) form-control-danger @endif" name="proc[{{$i}}][descricao]" id="proc[{{$i}}][descricao]" value="{{old("proc.{$i}.descricao")}}">
                                            @if($errors->has("proc.{$i}.descricao"))
                                                <div class="form-control-feedback">{{ $errors->first("proc.{$i}.descricao") }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group col-md-2 @if($errors->has("proc.{$i}.vl_honorario")) has-danger @endif">
                                            <label class="form-control-label">Vl Honorário *</span></label>
                                            <input type="text" alt="decimal-4" class="form-control @if($errors->has("proc.{$i}.vl_honorario")) form-control-danger @endif" name="proc[{{$i}}][vl_honorario]" id="proc[{{$i}}][vl_honorario]" value="{{old("proc.{$i}.vl_honorario")}}">
                                            @if($errors->has("proc.{$i}.vl_honorario"))
                                                <div class="form-control-feedback">{{ $errors->first("proc.{$i}.vl_honorario") }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group col-md-2 @if($errors->has("proc.{$i}.vl_operacao")) has-danger @endif">
                                            <label class="form-control-label">Vl Operac. *</span></label>
                                            <input type="text" alt="decimal-4" class="form-control @if($errors->has("proc.{$i}.vl_operacao")) form-control-danger @endif" name="proc[{{$i}}][vl_operacao]" id="proc[{{$i}}][vl_operacao]" value="{{old("proc.{$i}.vl_operacao")}}">
                                            @if($errors->has("proc.{$i}.vl_operacao"))
                                                <div class="form-control-feedback">{{ $errors->first("proc.{$i}.vl_operacao") }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group col-md-2 @if($errors->has("proc.{$i}.vl_total")) has-danger @endif">
                                            <label class="form-control-label">Vl Total *</span></label>
                                            <input type="text" alt="decimal-4" class="form-control @if($errors->has("proc.{$i}.vl_total")) form-control-danger @endif" name="proc[{{$i}}][vl_total]" id="proc[{{$i}}][vl_total]" value="{{old("proc.{$i}.vl_total")}}">
                                            @if($errors->has("proc.{$i}.vl_total"))
                                                <div class="form-control-feedback">{{ $errors->first("proc.{$i}.vl_total") }}</div>
                                            @endif
                                        </div>
                                        <div class="col-md-2">
                                            <input type="checkbox" id="proc[#][ativo]" name="proc[#][ativo]" @if (old("proc.{$i}.ativo"))
                                                checked
                                            @endif value="1" class="filled-in chk-col-teal"/>
                                            <label for="proc[#][ativo]">Ativo</label>
                                        </div>
                                    </div>
                                </div>
                            @endfor


                            <div class="form-group col-md-12 add-class" >
                                <span alt="default" class="add-procedimento fas fa-plus-circle">
                                    <a class="mytooltip" href="javascript:void(0)">
                                        <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar procedimentos"></i>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group input-group">
                    <div>
                        <a href="{{ route('instituicao.faturamento.selecionarImportacao', $faturamento) }}" class="btn btn-info waves-effect waves-light">Importar</a>
                    </div>
                    <div class="col text-right">
                        <a href="{{ route('instituicao.faturamento.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                        </a>
                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="scrolling-pagination">
                <table class="tablesaw table-bordered table-hover table">
                    <thead>
                        <tr>
                            <th>Data vigência</th>
                            <th>Procedimento</th>
                            <th>Descrição</th>
                            <th>Vl Honorario</th>
                            <th>Vl Operac.</th>
                            <th>Vl Total</th>
                            <th>Ativo</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($procedimentos as $item)
                            <tr>
                                <td>{{date('d/m/Y', strtotime($item->data_vigencia))}}</td>
                                <td>{{$item->procedimento_id}}</td>
                                <td>{{$item->descricao}}</td>
                                <td>{{$item->vl_honorario}}</td>
                                <td>{{$item->vl_operacao}}</td>
                                <td>{{$item->vl_total}}</td>
                                <td class="ativo">{{($item->ativo) ? 'Sim' : 'Não'}}</td>
                                <td>
                                    <button type="button" class="btn btn-xs btn-secondary btn-ativar-desativar"  aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-ativo="{{$item->ativo}}" data-original-title="{{ $item->ativo == 1 ? 'Desativar' : 'Ativar' }}">
                                        @if(!$item->ativo) <i class="fas fa-check"></i> @else <i class="fas fa-times"></i> @endif
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $procedimentos->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>
    <script type="text/javascript">
        var quantidade_proc = 0;
        $('ul.pagination').hide();

        $(function() {
            $('.scrolling-pagination').jscroll({
                loadingHtml: '<div class="spinner-border text-secondary" role="status"><span class="sr-only">Loading...</span></div>',
                autoTrigger: true,
                padding: 0,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.scrolling-pagination',
                callback: function() {
                    $('ul.pagination').remove();
                }
            });
        });

        $('.procedimento-itens').on('click', '.add-procedimento', function(){
            addProc();
        });

        function addProc(){
            $($('#item-proc').html()).insertBefore(".add-class");

            $('.mask_item').setMask();
            $('.mask_item').removeClass('mask_item');

            $("[name^='proc[#]']").each(function(index, element) {
                const name = $(element).attr('name');

                $(element).attr('name', name.replace('#',quantidade_proc));
            })

            $("[id^='proc[#]']").each(function(index, element) {
                const id = $(element).attr('id');

                $(element).attr('id', id.replace('#',quantidade_proc));
            })

            $("[for^='proc[#]']").each(function(index, element) {
                const variabel = $(element).attr('for');

                $(element).attr('for', variabel.replace('#',quantidade_proc));
            })

            quantidade_proc++;
        }

        $('.procedimento-itens').on('click', '.item-proc .remove-proc', function(e){
            e.preventDefault()

            $(e.currentTarget).parents('.item-proc').remove();
            if ($('.procedimento-itens').find('.item-proc').length == 0) {
                quantidade_proc = 0;
            }
        });

        $('.btn-ativar-desativar').on('click', function(e) {
        e.preventDefault();
            var texto = 'Ativar';
            var ativo = "Sim";
            var icon = '<i class="fas fa-times"></i>';
            if($(this).attr('data-ativo') == 1){
                texto = 'Desativar';
                ativo = "Não";
                icon = '<i class="fas fa-check"></i>';
            }

            var id = $(this).attr('data-id');
            var element = $(this);
            var faturamento_id = $("#faturamento_id").val();


            Swal.fire({
                title: "Deseja "+texto+"?",
                text: "Ao confirmar você estará "+texto+"!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim, confirmar!",
                cancelButtonText: "Não, cancelar!",
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: '{{route("instituicao.faturamento.statusProcedimento", ["faturamento" => "faturamento_id", "proc" => "proc_id"])}}'.replace('proc_id', id).replace('faturamento_id', faturamento_id),
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            '_token': '{{csrf_token()}}'
                        },
                        beforeSend: () => {
                            $('.loading').css('display', 'block');
                            $('.loading').find('.class-loading').addClass('loader')
                        },
                        success: function() {
                            $.toast({
                                heading: 'Sucesso',
                                text: "Procedimento "+texto+" com sucesso!",
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'success',
                                hideAfter: 9000,
                                stack: 10
                            });

                            element.parents("tr").find('.ativo').html(ativo);
                            element.html(icon);

                            if(texto == "Ativar"){
                                element.attr('data-original-title', "Desativar");
                                element.attr('data-ativo', 1);
                            }else{
                                element.attr('data-original-title', "Ativar");
                                element.attr('data-ativo', 0);
                            }

                        },
                        complete: () => {
                            $('.loading').css('display', 'none');
                            $('.loading').find('.class-loading').removeClass('loader')
                        },

                    })
                }
            });
        });
    </script>

    <script type="text/template" id="item-proc">
        <div class="col-md-12 item-proc">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove-proc">(remover)</a>
                </div>
                <div class="form-group col-md-2">
                    <label class="form-control-label">Data vigência *</span></label>
                    <input type="text" alt="date" class="form-control mask_item" name="proc[#][data_vigencia]" id="proc[#][data_vigencia]" value="">
                </div>
                <div class="form-group col-md-2">
                    <label class="form-control-label">Procedimento *</span></label>
                    <input type="number" class="form-control" name="proc[#][procedimento_id]" id="proc[#][procedimento_id]" value="">
                </div>
                <div class="form-group col-md-4">
                    <label class="form-control-label">Descrição *</span></label>
                    <input type="text" class="form-control" name="proc[#][descricao]" id="proc[#][descricao]" value="">
                </div>
                <div class="form-group col-md-2">
                    <label class="form-control-label">Vl Honorário *</span></label>
                    <input type="text" alt="decimal-4" class="form-control mask_item" name="proc[#][vl_honorario]" id="proc[#][vl_honorario]" value="">
                </div>
                <div class="form-group col-md-2">
                    <label class="form-control-label">Vl Operac. *</span></label>
                    <input type="text" alt="decimal-4" class="form-control mask_item" name="proc[#][vl_operacao]" id="proc[#][vl_operacao]" value="">
                </div>
                <div class="form-group col-md-2">
                    <label class="form-control-label">Vl Total *</span></label>
                    <input type="text" alt="decimal-4" class="form-control mask_item" name="proc[#][vl_total]" id="proc[#][vl_total]" value="">
                </div>
                <div class="col-md-2">
                    <input type="checkbox" id="proc[#][ativo]" name="proc[#][ativo]" value="1" class="filled-in chk-col-teal"/>
                    <label for="proc[#][ativo]">Ativo</label>
                </div>
            </div>
        </div>
    </script>
@endpush
