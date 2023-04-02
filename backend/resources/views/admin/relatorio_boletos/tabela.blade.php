<style>
    .status{
        background-color: #d1dade;
        color: #5e5e5e;
        font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 10px;
        font-weight: 600;
        padding: 3px 8px;
        text-shadow: none;
    }
</style>
<table id="demo-foo-row-toggler" class="table table-bordered" data-toggle-column="first" style="margin: 0px">
    <thead>
        <tr>
            <th >Instituição</th>
            <th >Total Boletos</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dados as $item)
           <tr>
                <td>{{$item->nome}}</td>
                <td>{{count($item->contasReceber)}}</td>
           </tr>
        @endforeach
    </tbody>
</table>