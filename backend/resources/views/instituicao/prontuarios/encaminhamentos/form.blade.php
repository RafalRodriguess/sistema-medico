<div class="row">
    <input type="hidden" name="encaminhamento_id" id="encaminhamento_id" value="">
    <div class="col-md-12 form-group">
        <label class="form-control-label p-0 m-0">
            Encaminhamento <span class="text-danger">*</span>
        </label>
        <textarea class="form-control summernoteEncaminhamento" name="obs_encaminhamento" id="obs_encaminhamento" cols="30" rows="10"></textarea>
    </div>
    {{-- <div class="col-md-12">
        <input type="checkbox" id="compartilhar_encaminhamento" name="compartilhado" class="filled-in chk-col-black"/>
        <label for="compartilhar_encaminhamento">Compartilhar encaminhamento</label>
    </div> --}}
</div>

<script>
    $(document).ready(function() {
        $('.summernoteEncaminhamento').summernote({
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