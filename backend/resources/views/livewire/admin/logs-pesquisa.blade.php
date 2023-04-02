<div>
    {{-- Do your work, then step back. --}}
</div>
<div class="card-body">




<table class="tablesaw table-bordered table-hover table" style="overflow-wrap: anywhere">
    <thead>
        <tr>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">
            </th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
            <th style="white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 500px;" scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="4">Mensagem</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Nível</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="2">Canal</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="1">Horario</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
            <tr>
                <td class="details-control" style="border-right: solid 8px text-transform:uppercase" class="text-left"></td>
                <td class="title"><a href="javascript:void(0)">{{ $log->id }}</a></td>
                <td style="white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 500px;"> {{ $log->message }} </td>
                <td> {{ $log->level_name }} </td>
                <td> {{ $log->channel }} </td>
                <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $log->record_datetime )->format('d/m/Y H:i:s') }}</td>

            </tr>
            <tr role="detail" class="tr-detail" >
                <td colspan="100%">
                    <p><strong>{{ $log->message }}</strong></p>
                    <p><small>
                        @foreach(json_decode($log->context) as $key => $value)
                            @if($key=='exception')
                                @foreach($value as $key2 => $value2)
                                @if($key2=='errorInfo')
                                    <pre class='json_display' data-teste='@json($value2)'></pre>
                                @else
                                    <table>
                                        {!!($value2)!!}
                                    </table>
                                @endif
                                @endforeach
                            @else
                            <p>{{ $key }} =>
                                @if(is_object($value) || is_array($value))
                                    <pre class='json_display' data-teste='@json($value)'></pre>
                                @else
                                    {{($value)}}
                                @endif
                            </p>
                            @endif

                        @endforeach
                         </small></p>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div style="float: right">
    {{ $logs->links() }}
</div>
</div>

@push('estilos')

    <style>

    .json_display{
        background-color: #d5f0ee;
    }

    tr.tr-detail{
        border: 2px solid rgb(213, 240, 238);
        border-top: 0px solid rgb(213, 240, 238);
        display: none;
    }
    td.details-control {
        cursor: pointer;
    }

    tr.details td.details-control:before {
        content: '\f151';
        font-family: 'Font Awesome\ 5 Free';
        font-size: 22px;
        color: black;
    }

     td.details-control:before {
        content: '\f150';
        font-family: 'Font Awesome\ 5 Free';
        font-size: 22px;
        color: gray;
    }
    </style>
@endpush

@push('scripts');


<script>

    $( document ).ready(function() {

        $('.json_display').each(function(){
            $(this).jsonViewer($(this).data('teste'),{
                collapsed:true,
                rootCollapsable:true
            })
            // new jsonViewer(this, $(this).data('teste'))
        })

        $('tbody').on( 'click', 'tr td.details-control', function () {

            var tr = $(this).closest('tr');


            tr_detail = $(tr).nextAll("tr[role='detail']:first");

            if(tr_detail.is(":visible")){
                $(tr).removeClass( 'details' )
                tr_detail.hide()
            }else{
                $(tr).addClass( 'details' )
                tr_detail.show()
            }

        } );

    })
</script>
@endpush
