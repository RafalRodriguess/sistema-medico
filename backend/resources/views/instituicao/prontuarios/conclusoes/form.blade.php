<div class="row">
    <input type="hidden" name="conclusao_id" id="conclusao_id" value="">
    <div class="col-md-12 form-group">
        <label class="form-control-label p-0 m-0">
            Motivo <span class="text-danger">*</span>
        </label>
        <select class="form-control select2" name="motivo_conclusao_id" id="motivo_conclusao_id">
            @foreach ($motivoConclusao as $item)
                <option value="{{$item->id}}">{{$item->descricao}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-12 form-group">
        <label class="form-control-label p-0 m-0">
            Conclusão <span class="text-danger">*</span>
        </label>
        <textarea class="form-control summernoteConclusao" name="obs_conclusao" id="obs_conclusao" cols="30" rows="10"></textarea>
    </div>
    {{-- <div class="col-md-12">
        <input type="checkbox" id="compartilhar_conclusao" name="compartilhado" class="filled-in chk-col-black"/>
        <label for="compartilhar_conclusao">Compartilhar conclusao</label>
    </div> --}}
</div>

<script>
    $(document).ready(function() {
        $('.summernoteConclusao').summernote({
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