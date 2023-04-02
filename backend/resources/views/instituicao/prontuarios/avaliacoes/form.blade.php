<div class="row">
    <input type="hidden" name="avaliacao_id" id="avaliacao_id" value="">
    <div class="col-md-12 form-group">
        <label class="form-control-label p-0 m-0">
            Avaliacão <span class="text-danger">*</span>
        </label>
        <textarea class="form-control summernoteAvaliacao" name="avaliacao" id="avaliacao_texto" cols="30" rows="10"></textarea>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.summernoteAvaliacao').summernote({
            height: 350,
            lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
            fontSizes: ['8', '9', '10', '11', '12', '14', '18', '19', '20', '21', '22', '23', '24', '36', '48' , '64', '82', '150'],
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