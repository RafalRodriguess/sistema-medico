<div id="table-scroll" class="table-scroll">
        <div class="table-wrap">
            <table class="table table-bordered table-sm main-table" data-toggle="table" data-search="true" data-show-columns="true">
                <thead>
                    <tr>
                        <th class="fixed-side">Descrição</th>
                        <th >01</th>
                        <th >02</th>
                        <th >03</th>
                        <th >04</th>
                        <th >05</th>
                        <th >06</th>
                        <th >07</th>
                        <th >08</th>
                        <th >09</th>
                        <th >10</th>
                        <th >11</th>
                        <th >12</th>
                        <th >13</th>
                        <th >14</th>
                        <th >15</th>
                        <th >16</th>
                        <th >17</th>
                        <th >18</th>
                        <th >19</th>
                        <th >20</th>
                        <th >21</th>
                        <th >22</th>
                        <th >23</th>
                        <th >24</th>
                        <th >25</th>
                        <th >26</th>
                        <th >27</th>
                        <th >28</th>
                        <th >29</th>
                        <th >30</th>
                        <th >31</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="32" class="fixed-side" style="background-color: #a7dc99;">Entradas</td>
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
                            <td>{{!empty($item['13']) ? number_format($item['13'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['14']) ? number_format($item['14'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['15']) ? number_format($item['15'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['16']) ? number_format($item['16'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['17']) ? number_format($item['17'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['18']) ? number_format($item['18'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['19']) ? number_format($item['19'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['20']) ? number_format($item['20'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['21']) ? number_format($item['21'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['22']) ? number_format($item['22'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['23']) ? number_format($item['23'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['24']) ? number_format($item['24'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['25']) ? number_format($item['25'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['26']) ? number_format($item['26'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['27']) ? number_format($item['27'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['28']) ? number_format($item['28'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['29']) ? number_format($item['29'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['30']) ? number_format($item['30'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['31']) ? number_format($item['31'], 2, ',','.') : '0,00'}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="32" class="fixed-side text-white" style="background-color: #cd3333b7;">Saidas</td>
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
                            <td>{{!empty($item['13']) ? number_format(-$item['13'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['14']) ? number_format(-$item['14'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['15']) ? number_format(-$item['15'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['16']) ? number_format(-$item['16'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['17']) ? number_format(-$item['17'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['18']) ? number_format(-$item['18'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['19']) ? number_format(-$item['19'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['20']) ? number_format(-$item['20'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['21']) ? number_format(-$item['21'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['22']) ? number_format(-$item['22'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['23']) ? number_format(-$item['23'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['24']) ? number_format(-$item['24'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['25']) ? number_format(-$item['25'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['26']) ? number_format(-$item['26'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['27']) ? number_format(-$item['27'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['28']) ? number_format(-$item['28'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['29']) ? number_format(-$item['29'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['30']) ? number_format(-$item['30'], 2, ',','.') : '0,00'}}</td>
                            <td>{{!empty($item['31']) ? number_format(-$item['31'], 2, ',','.') : '0,00'}}</td>
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
                        <th>{{!empty($total['13']) ? number_format($total['13'], 2, ',','.') : '0,00'}}</th>
                        <th>{{!empty($total['14']) ? number_format($total['14'], 2, ',','.') : '0,00'}}</th>
                        <th>{{!empty($total['15']) ? number_format($total['15'], 2, ',','.') : '0,00'}}</th>
                        <th>{{!empty($total['16']) ? number_format($total['16'], 2, ',','.') : '0,00'}}</th>
                        <th>{{!empty($total['17']) ? number_format($total['17'], 2, ',','.') : '0,00'}}</th>
                        <th>{{!empty($total['18']) ? number_format($total['18'], 2, ',','.') : '0,00'}}</th>
                        <th>{{!empty($total['19']) ? number_format($total['19'], 2, ',','.') : '0,00'}}</th>
                        <th>{{!empty($total['20']) ? number_format($total['20'], 2, ',','.') : '0,00'}}</th>
                        <th>{{!empty($total['21']) ? number_format($total['21'], 2, ',','.') : '0,00'}}</th>
                        <th>{{!empty($total['22']) ? number_format($total['22'], 2, ',','.') : '0,00'}}</th>
                        <th>{{!empty($total['23']) ? number_format($total['23'], 2, ',','.') : '0,00'}}</th>
                        <th>{{!empty($total['24']) ? number_format($total['24'], 2, ',','.') : '0,00'}}</th>
                        <th>{{!empty($total['25']) ? number_format($total['25'], 2, ',','.') : '0,00'}}</th>
                        <th>{{!empty($total['26']) ? number_format($total['26'], 2, ',','.') : '0,00'}}</th>
                        <th>{{!empty($total['27']) ? number_format($total['27'], 2, ',','.') : '0,00'}}</th>
                        <th>{{!empty($total['28']) ? number_format($total['28'], 2, ',','.') : '0,00'}}</th>
                        <th>{{!empty($total['29']) ? number_format($total['29'], 2, ',','.') : '0,00'}}</th>
                        <th>{{!empty($total['30']) ? number_format($total['30'], 2, ',','.') : '0,00'}}</th>
                        <th>{{!empty($total['31']) ? number_format($total['31'], 2, ',','.') : '0,00'}}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>