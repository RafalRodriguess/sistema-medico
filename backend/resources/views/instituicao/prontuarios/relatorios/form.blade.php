<div class="row">
    <input type="hidden" name="relatorio_id" id="relatorio_id" value="">
    <div class="col-md-12 form-group">
        <label class="form-control-label p-0 m-0">
            Relatório <span class="text-danger">*</span>
        </label>
        <textarea class="form-control summernoteRelatorio" name="obs_relatorio" id="obs_relatorio" cols="30" rows="10"></textarea>
    </div>
    {{-- <div class="col-md-12">
        <input type="checkbox" id="compartilhar_relatorio" name="compartilhado" class="filled-in chk-col-black"/>
        <label for="compartilhar_relatorio">Compartilhar relatório</label>
    </div> --}}
</div>

<script>
    $(document).ready(function() {
        $('.summernoteRelatorio').summernote({
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