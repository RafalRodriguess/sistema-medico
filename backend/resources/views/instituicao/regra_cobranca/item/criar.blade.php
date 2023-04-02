@extends('instituicao.layout')

    @section('conteudo')
    @component('components/page-title', [
        'titulo' => "Adicionar Itens regra de cobrança",
        'breadcrumb' => [
            'Regra de Cobrança' => route('instituicao.regrasCobranca.index'),
            "{$regra->descricao}",
            'Itens' => route('instituicao.regrasCobrancaItens.index', [$regra]),
            'Adicionar itens',
            ],
        ])
    @endcomponent

    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.regrasCobrancaItens.store', [$regra]) }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="row itens">

                            @php($oldItens = old('itens') ?: [])
                            @for($i = 0, $max = count($oldItens); $i < $max; $i++)
                                <div class="col-md-12 item">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="javascrit:void(0)" class="small remove">(remover)</a>
                                        </div>
                                        <div class="form-group col-md-3 @if($errors->has("itens.{$i}.grupo_procedimento_id")) has-danger @endif">
                                            <label class="form-control-label">Gurpo procedimento *</span></label>
                                            <select name="itens[{{$i}}][grupo_procedimento_id]" class="form-control @if($errors->has("itens.{$i}.grupo_procedimento_id")) form-control-danger @endif" name="itens[{{$i}}][grupo_procedimento_id]">
                                                @foreach ($grupos as $item)
                                                    <option value="{{$item->id}}" @if (old("itens.{$i}.grupo_procedimento_id") == $item->id)
                                                        selected
                                                    @endif>{{$item->nome}}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has("itens.{$i}.grupo_procedimento_id"))
                                                <div class="form-control-feedback">{{ $errors->first("itens.{$i}.grupo_procedimento_id") }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group col-md-3 @if($errors->has("itens.{$i}.faturamento_id")) has-danger @endif">
                                            <label class="form-control-label">Faturamento *</span></label>
                                            <select name="itens[{{$i}}][faturamento_id]" class="form-control @if($errors->has("itens.{$i}.faturamento_id")) form-control-danger @endif" name="itens[{{$i}}][faturamento_id]">
                                                @foreach ($faturamentos as $item)
                                                    <option value="{{$item->id}}" @if (old("itens.{$i}.faturamento_id") == $item->id)
                                                        selected
                                                    @endif>{{$item->descricao}}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has("itens.{$i}.faturamento_id"))
                                                <div class="form-control-feedback">{{ $errors->first("itens.{$i}.faturamento_id") }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group col-md-2 @if($errors->has("itens.{$i}.pago")) has-danger @endif">
                                            <label class="form-control-label">Pago % *</span></label>
                                            <input type="text" alt="porcentagem" class="form-control @if($errors->has("itens.{$i}.pago")) form-control-danger @endif" name="itens[{{$i}}][pago]" id="itens[{{$i}}][pago]" value="{{old("itens.{$i}.pago", 100.00)}}">
                                            @if($errors->has("itens.{$i}.pago"))
                                                <div class="form-control-feedback">{{ $errors->first("itens.{$i}.pago") }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group col-md-3 @if($errors->has("itens.{$i}.base")) has-danger @endif">
                                            <label class="form-control-label">Base *</span></label>
                                            <select name="itens[{{$i}}][base]" class="form-control @if($errors->has("itens.{$i}.base")) form-control-danger @endif" name="itens[{{$i}}][base]">
                                                @foreach ($bases as $item)
                                                    <option value="{{$item}}" @if (old("itens.{$i}.base") == $item)
                                                        selected
                                                    @endif>{{App\RegraCobrancaItem::baseTexto($item)}}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has("itens.{$i}.base"))
                                                <div class="form-control-feedback">{{ $errors->first("itens.{$i}.base") }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endfor
                            

                            <div class="form-group col-md-12 add-class" >
                                <span alt="default" class="add fas fa-plus-circle">
                                    <a class="mytooltip" href="javascript:void(0)">
                                        <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar regras"></i>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group text-right">
                    <a href="{{ route('instituicao.regrasCobrancaItens.index', [$regra]) }}">
                    <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        var quantidade = 0;
        
        $('.itens').on('click', '.add', function(){
            add();
        });

        function add(){            
            $($('#itens-add').html()).insertBefore(".add-class");

            $('.mask_item').setMask();
            $('.mask_item').removeClass('mask_item');

            $(".select2fildnew").select2();
            $(".select2fildnew").removeClass("select2fildnew")

            $("[name^='itens[#]']").each(function(index, element) {
                const name = $(element).attr('name');

                $(element).attr('name', name.replace('#',quantidade));
            })

            quantidade++;
        }

        $('.itens').on('click', '.item .remove', function(e){
            e.preventDefault()

            $(e.currentTarget).parents('.item').remove();
            if ($('.itens').find('.item').length == 0) {
                quantidade = 0;
            }
        });

    </script>

    <script type="text/template" id="itens-add">
        <div class="col-md-12 item">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove">(remover)</a>
                </div>
                <div class="form-group col-md-3">
                    <label class="form-control-label">Gurpo procedimento *</span></label>
                    <select name="itens[#][grupo_procedimento_id]" class="form-control select2fildnew">
                        @foreach ($grupos as $item)
                            <option value="{{$item->id}}" >{{$item->nome}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3 ">
                    <label class="form-control-label">Faturamento *</span></label>
                    <select name="itens[#][faturamento_id]" class="form-control  select2fildnew" >
                        @foreach ($faturamentos as $item)
                            <option value="{{$item->id}}" >{{$item->descricao}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2 ">
                    <label class="form-control-label">Pago % *</span></label>
                    <input type="text" alt="porcentagem" class="form-control mask_item" name="itens[#][pago]" value="100.00">
                </div>
                <div class="form-group col-md-3 ">
                    <label class="form-control-label">Base *</span></label>
                    <select name="itens[#][base]" class="form-control  select2fildnew" >
                        @foreach ($bases as $item)
                            <option value="{{$item}}">{{App\RegraCobrancaItem::baseTexto($item)}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </script>
@endpush
