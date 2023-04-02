<div class="row">
    <input type="hidden" name="exame_id" id="exame_id" value="">
    <div class="col-md-12 form-group">
        <label class="form-control-label p-0 m-0">
            Exame <span class="text-danger">*</span>
        </label>
        <textarea class="form-control summernoteExame" name="obs_exame" id="obs_exame" cols="30" rows="10"></textarea>
    </div>
    {{-- <div class="col-md-12">
        <input type="checkbox" id="compartilhar_exame" name="compartilhado" class="filled-in chk-col-black"/>
        <label for="compartilhar_exame">Compartilhar exame</label>
    </div> --}}
</div>

<script>
    $(document).ready(function() {
        $('.summernoteExame').summernote({
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