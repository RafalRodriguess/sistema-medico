<div class="row">
    <input type="hidden" name="laudo_id" id="laudo_id" value="">
    <div class="col-md-12 form-group">
        <label class="form-control-label p-0 m-0">
            Laudo <span class="text-danger">*</span>
        </label>
        <textarea class="form-control summernoteLaudo" name="obs_laudo" id="obs_laudo" cols="30" rows="10"></textarea>
    </div>
    {{-- <div class="col-md-12">
        <input type="checkbox" id="compartilhar_laudo" name="compartilhado" class="filled-in chk-col-black"/>
        <label for="compartilhar_laudo">Compartilhar laudo</label>
    </div> --}}
</div>

<script>
    $(document).ready(function() {
        $('.summernoteLaudo').summernote({
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