

<div class="row">
    <input type="hidden" name="receituario_livre_id" id="receituario_livre_id" value="">
    <input type="hidden" name="receituario_livre_tipo" id="receituario_livre_tipo" value="">
    <div class="col-md-12 form-group">
        <label class="form-control-label p-0 m-0"></label>
        <textarea class="form-control summernoteReceituario" name="receituario_livre" id="receituario_livre" cols="30" rows="10"></textarea>
    </div>
    {{-- <div class="summernote col-md-12">
    </div> --}}
</div>



<script>
    $(document).ready(function() {
        $('.summernoteReceituario').summernote({
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