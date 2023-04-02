<div class="col-md-12 scrolling-pagination-teste" style="height: 300px; overflow-y: scroll;">
    <div class="scrolling-pagination">
        @foreach($procedimentos as $item)
            <div class="col-sm-12 proc_btn_hidden" style="margin-bottom: 5px;">
                <div class="btn-group col-sm mx-0" data-toggle="buttons">
                    {{-- @php
                        $item_proc = $item->instituicaoProcedimentosConvenios[0]->pivot;
                    @endphp --}}

            
                    {{-- <label class="btn btn-outline-primary btn-proc">
                        <input type="checkbox" class="checkProc btn-check" value="{{$item_proc->id}}" id="proc_{{$item->procedimento->id}}" data-convenio_id="{{$item_proc->convenios_id}}" data-valor="{{$item_proc->valor}}">{{$item->procedimento->descricao}}                                                    
                    </label> --}}

                    <label class="btn btn-outline-primary btn-proc button-procedimento @if (array_key_exists($item->id, $procedimentosSelect))
                        active
                    @endif" data-id="{{$item->id}}" id="proc_{{$item->id}}" data-descricao="{{$item->descricao}}">
                        {{-- <input type="checkbox" class="btn-check checkProc" value="{{$item_proc->id}}" id="proc_{{$item->id}}" data-convenio_id="{{$item_proc->convenios_id}}" data-valor="{{$item_proc->valor}}"> --}}
                        {{$item->descricao}}
                    </label>
                </div>
            </div>
            
        @endforeach
        {{ $procedimentos->links() }}
    </div>
</div>

<script>
    $('ul.pagination').hide();
    $(function() {
            // console.log('aqui')
            
                
        $('#modalPacotesProcedimentos').find('.scrolling-pagination-teste').jscroll({
            loadingHtml: '<div class="spinner-border text-secondary" role="status"><span class="sr-only">Loading...</span></div>',
            autoTrigger: true,
            padding: 0,
            // debug: true,
            refresh: true,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.scrolling-pagination',
            callback: function() {
                $(".button-procedimento").each(function(index, element){
                    // console.log($(element).attr('data-id'))
                    var found = false;
                    procedimentos_exames.map((proc) =>  {if(parseInt(proc[1]) == parseInt($(element).attr('data-id'))) {
                        found = true;
                    }})

                    if(found == true){
                        // console.log(found)
                        $(element).addClass('active');
                    }
                })
                
                $('ul.pagination').remove();
            }
        });
            
        });
</script>