<div id="table-scroll" class="table-scroll">
    <div class="table-wrap">
        <table class="table table-bordered table-sm main-table" data-toggle="table" data-search="true" data-show-columns="true">
            <thead>
                <tr>
                    <th class="fixed-side">Descrição</th>
                    <th >Janeiro</th>
                    <th >Fevereiro</th>
                    <th >Março</th>
                    <th >Abril</th>
                    <th >Maio</th>
                    <th >Junho</th>
                    <th >Julho</th>
                    <th >Agosto</th>
                    <th >Setembro</th>
                    <th >Outubro</th>
                    <th >Novembro</th>
                    <th >Dezembro</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="13" class="fixed-side" style="background-color: #a7dc99;">Entradas</td>
                </tr>
                @foreach($entradas as $key => $item)
                    <tr>
                        <td class="fixed-side">{{$key}}</th>
                        <td>{{!empty($item['01']) ? number_format($item['01'], 2, ',','.') : '0,00'}}</td>
                        <td>{{!empty($item['02']) ? number_format($item['02'], 2, ',','.') : '0,00'}}</td>
                        <td>{{!empty($item['03']) ? number_format($item['03'], 2, ',','.') : '0,00'}}</td>
                        <td>{{!empty($item['04']) ? number_format($item['04'], 2, ',','.') : '0,00'}}</td>
                        <td>{{!empty($item['05']) ? number_format($item['05'], 2, ',','.') : '0,00'}}</td>
                        <td>{{!empty($item['06']) ? number_format($item['06'], 2, ',','.') : '0,00'}}</td>
                        <td>{{!empty($item['07']) ? number_format($item['07'], 2, ',','.') : '0,00'}}</td>
                        <td>{{!empty($item['08']) ? number_format($item['08'], 2, ',','.') : '0,00'}}</td>
                        <td>{{!empty($item['09']) ? number_format($item['09'], 2, ',','.') : '0,00'}}</td>
                        <td>{{!empty($item['10']) ? number_format($item['10'], 2, ',','.') : '0,00'}}</td>
                        <td>{{!empty($item['11']) ? number_format($item['11'], 2, ',','.') : '0,00'}}</td>
                        <td>{{!empty($item['12']) ? number_format($item['12'], 2, ',','.') : '0,00'}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="13" class="fixed-side text-white" style="background-color: #cd3333b7;">Saidas</td>
                </tr>
                @foreach($saidas as $key => $item)
                    <tr>
                        <td class="fixed-side">{{$key}}</th>
                        <td>{{!empty($item['01']) ? number_format(-$item['01'], 2, ',','.') : '0,00'}}</td>
                        <td>{{!empty($item['02']) ? number_format(-$item['02'], 2, ',','.') : '0,00'}}</td>
                        <td>{{!empty($item['03']) ? number_format(-$item['03'], 2, ',','.') : '0,00'}}</td>
                        <td>{{!empty($item['04']) ? number_format(-$item['04'], 2, ',','.') : '0,00'}}</td>
                        <td>{{!empty($item['05']) ? number_format(-$item['05'], 2, ',','.') : '0,00'}}</td>
                        <td>{{!empty($item['06']) ? number_format(-$item['06'], 2, ',','.') : '0,00'}}</td>
                        <td>{{!empty($item['07']) ? number_format(-$item['07'], 2, ',','.') : '0,00'}}</td>
                        <td>{{!empty($item['08']) ? number_format(-$item['08'], 2, ',','.') : '0,00'}}</td>
                        <td>{{!empty($item['09']) ? number_format(-$item['09'], 2, ',','.') : '0,00'}}</td>
                        <td>{{!empty($item['10']) ? number_format(-$item['10'], 2, ',','.') : '0,00'}}</td>
                        <td>{{!empty($item['11']) ? number_format(-$item['11'], 2, ',','.') : '0,00'}}</td>
                        <td>{{!empty($item['12']) ? number_format(-$item['12'], 2, ',','.') : '0,00'}}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th class="fixed-side">Total</th>
                    <th>{{!empty($total['01']) ? number_format($total['01'], 2, ',','.') : '0,00'}}</th>
                    <th>{{!empty($total['02']) ? number_format($total['02'], 2, ',','.') : '0,00'}}</th>
                    <th>{{!empty($total['03']) ? number_format($total['03'], 2, ',','.') : '0,00'}}</th>
                    <th>{{!empty($total['04']) ? number_format($total['04'], 2, ',','.') : '0,00'}}</th>
                    <th>{{!empty($total['05']) ? number_format($total['05'], 2, ',','.') : '0,00'}}</th>
                    <th>{{!empty($total['06']) ? number_format($total['06'], 2, ',','.') : '0,00'}}</th>
                    <th>{{!empty($total['07']) ? number_format($total['07'], 2, ',','.') : '0,00'}}</th>
                    <th>{{!empty($total['08']) ? number_format($total['08'], 2, ',','.') : '0,00'}}</th>
                    <th>{{!empty($total['09']) ? number_format($total['09'], 2, ',','.') : '0,00'}}</th>
                    <th>{{!empty($total['10']) ? number_format($total['10'], 2, ',','.') : '0,00'}}</th>
                    <th>{{!empty($total['11']) ? number_format($total['11'], 2, ',','.') : '0,00'}}</th>
                    <th>{{!empty($total['12']) ? number_format($total['12'], 2, ',','.') : '0,00'}}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>