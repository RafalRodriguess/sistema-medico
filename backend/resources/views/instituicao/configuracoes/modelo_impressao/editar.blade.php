@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Editar Modelo de impressão',
        'breadcrumb' => [
            'Modelo de impressão' => route('instituicao.modeloImpressao.index'),
            'Editar',
        ],
    ])
    @endcomponent

    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.modeloImpressao.update', [$modelo]) }}" method="post">
                @method('put')
                @csrf
               <div class="row">
                   <input type="hidden" name="instituicao_prestador_id" value="{{$modelo->instituicao_prestador_id}}">
                    <div class=" col-md-6 form-group @if($errors->has('tamanho_folha')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Tamanho da folha</label>
                        <select class="form-control @if($errors->has('tamanho_folha')) form-control-danger @endif" name="tamanho_folha" id="tamanho_folha">
                                <option value="A4" @if (old('tamanho_folha', $modelo->tamanho_folha) == "A4") selected @endif>
                                    A4
                                </option>
                                <option value="A5" @if (old('tamanho_folha', $modelo->tamanho_folha) == "A5") selected @endif>
                                    A5
                                </option>
                        </select>
                        @if($errors->has('tamanho_folha'))
                            <small class="form-control-feedback">{{ $errors->first('tamanho_folha') }}</small>
                        @endif
                    </div>
                    <div class=" col-md-6 form-group @if($errors->has('tamanho_fonte')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Tamanho da fonte</label>
                        <select class="form-control @if($errors->has('tamanho_fonte')) form-control-danger @endif" name="tamanho_fonte" id="tamanho_fonte">
                                <option value="12" @if (old('tamanho_fonte', $modelo->tamanho_fonte) == "12") selected @endif>Default</option>
                                <option value="10" @if (old('tamanho_fonte', $modelo->tamanho_fonte) == "10") selected @endif>10</option>
                                <option value="11" @if (old('tamanho_fonte', $modelo->tamanho_fonte) == "11") selected @endif>11</option>
                                <option value="12">12</option>
                                <option value="13" @if (old('tamanho_fonte', $modelo->tamanho_fonte) == "13") selected @endif>13</option>
                                <option value="14" @if (old('tamanho_fonte', $modelo->tamanho_fonte) == "14") selected @endif>14</option>
                                <option value="15" @if (old('tamanho_fonte', $modelo->tamanho_fonte) == "15") selected @endif>15</option>
                                <option value="16" @if (old('tamanho_fonte', $modelo->tamanho_fonte) == "16") selected @endif>16</option>
                        </select>
                        @if($errors->has('tamanho_fonte'))
                            <small class="form-control-feedback">{{ $errors->first('tamanho_fonte') }}</small>
                        @endif
                    </div>
                    <div class=" col-md-3 form-group @if($errors->has('margem_cabecalho')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Margem do cabeçalho: (cm) <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Margem de distância entre o início do papel timbrado e espaço vazio da folha"></i></label>
                        <input type="text" alt="altura" name="margem_cabecalho" value="{{ old('margem_cabecalho', $modelo->margem_cabecalho) }}"
                        class="form-control @if($errors->has('margem_cabecalho')) form-control-danger @endif">
                        @if($errors->has('margem_cabecalho'))
                            <small class="form-control-feedback">{{ $errors->first('margem_cabecalho') }}</small>
                        @endif
                    </div>
                    <div class=" col-md-3 form-group @if($errors->has('margem_esquerda')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Margem da esquerda: (cm) <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Margem de distância entre a borda esquerda do papel timbrado e espaço vazio da folha"></i></label>
                        <input type="text" alt="altura" name="margem_esquerda" value="{{ old('margem_esquerda', $modelo->margem_esquerda) }}"
                        class="form-control @if($errors->has('margem_esquerda')) form-control-danger @endif">
                        @if($errors->has('margem_esquerda'))
                            <small class="form-control-feedback">{{ $errors->first('margem_esquerda') }}</small>
                        @endif
                    </div>
                    <div class=" col-md-3 form-group @if($errors->has('margem_rodape')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Margem do rodapé: (cm) <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Margem de distância entre o rodapé do papel timbrado e espaço vazio da folha"></i></label>
                        <input type="text" alt="altura" name="margem_rodape" value="{{ old('margem_rodape', $modelo->margem_rodape) }}"
                        class="form-control @if($errors->has('margem_rodape')) form-control-danger @endif">
                        @if($errors->has('margem_rodape'))
                            <small class="form-control-feedback">{{ $errors->first('margem_rodape') }}</small>
                        @endif
                    </div>
                    <div class=" col-md-3 form-group @if($errors->has('margem_direita')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Margem da direita: (cm) <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Margem de distância entre a borda direita do papel timbrado e espaço vazio da folha"></i></label>
                        <input type="text" alt="altura" name="margem_direita" value="{{ old('margem_direita', $modelo->margem_direita) }}"
                        class="form-control @if($errors->has('margem_direita')) form-control-danger @endif">
                        @if($errors->has('margem_direita'))
                            <small class="form-control-feedback">{{ $errors->first('margem_direita') }}</small>
                        @endif
                    </div>
                    <div class=" col-md-6 form-group @if($errors->has('cabecalho')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Cabeçalho</label>
                        <textarea class="form-control summernote @if($errors->has('cabecalho')) form-control-danger @endif" name="cabecalho" id="cabecalho" cols="30" rows="10">
                            {{ old('cabecalho', $modelo->cabecalho) }}</textarea>
                        @if($errors->has('cabecalho'))
                            <small class="form-control-feedback">{{ $errors->first('cabecalho') }}</small>
                        @endif
                    </div>
                    <div class=" col-md-6 form-group @if($errors->has('rodape')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Rodapé</label>
                        <textarea class="form-control summernote @if($errors->has('rodape')) form-control-danger @endif" name="rodape" id="rodape" cols="30" rows="10">
                            {{ old('rodape', $modelo->rodape) }}</textarea>
                        @if($errors->has('rodape'))
                            <small class="form-control-feedback">{{ $errors->first('rodape') }}</small>
                        @endif
                    </div>
               </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.modeloImpressao.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 350,
                lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
                toolbar: [
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['fontsize', ['fontsize']],
                    ['fontname', ['fontname']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['color', ['color']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['hr']],
                    ['view', ['fullscreen']],
                    ['misc', ['codeview']]
                ],
            });
        })
    </script>
@endpush
