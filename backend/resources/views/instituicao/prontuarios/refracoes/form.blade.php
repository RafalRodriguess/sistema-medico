<div class="row">
    <div class="col-md-12">
        <input type="hidden" name="refracao_id" id="refracao_id" value="{{($refracao) ? $refracao->id : ''}}">

        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#ref_atual" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Refração atual</span></a> </li>
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#ac_visual" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Ac. visual</span></a> </li>
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#ref_estatica" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Refração estática</span></a> </li>
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#ref_dinamica" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Refração dinâmica</span></a> </li>
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#presc_oculos" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Presc. de óculos</span></a> </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content tabcontent-border">
            <div class="tab-pane p-20 active" id="ref_atual" role="tabpanel">
                <div class="row">
                    <div class="col-md-2">
                        <h3 style="margin-top: 35px;">OD</h3>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="form-control-label">Esférico</label>
                                <select name="refracao_atual[ref_atual_od_esferico]" class="form-control selectfild2RefracaoPlano" style="width: 100%">
                                    <?php
                                        $value = 30;
                                        while ($value > 0):
                                            $val = number_format($value, 2, ',', '.');
                                            $value -= 0.25;
                                            ?>
                                            <option value="-<?= $val ?>" <?= (isset($refracao->refracao['refracao_atual']['ref_atual_od_esferico']) && $refracao->refracao['refracao_atual']['ref_atual_od_esferico'] == ('-'.(string)$val))?'selected' : '' ?>>
                                                -<?= $val ?>
                                            </option>
                                            <?php
                                        endwhile;
                                        ?>
                                        <option value="plano"
                                            <?= (isset($refracao->refracao['refracao_atual']['ref_atual_od_esferico']) && $refracao->refracao['refracao_atual']['ref_atual_od_esferico'] == 'plano')?'selected' : '' ?>
                                            <?= (!isset($refracao->refracao['refracao_atual']))?'selected' : '' ?>
                                        >
                                            PLANO
                                        </option>
                                        <?php
                                        $value = 0.25;
                                        while ($value <= 30):
                                            $val = number_format($value, 2, ',', '.');
                                            $value += 0.25;
                                            ?>
                                            <option value="+<?= $val ?>" <?= (isset($refracao->refracao['refracao_atual']['ref_atual_od_esferico']) && $refracao->refracao['refracao_atual']['ref_atual_od_esferico'] == ('+'.(string)$val))? 'selected' : '' ?>>
                                                +<?= $val ?>
                                            </option>
                                            <?php
                                        endwhile;
                                        ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">Cilíndrico</label>
                                <select name="refracao_atual[ref_atual_od_cilindrico]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 0.25;
                                    while ($value <= 8):
                                        $val = number_format($value, 2, ',', '.');
                                        $value += 0.25;
                                        ?>
                                        <option value="-<?= $val ?>" <?= (isset($refracao->refracao['refracao_atual']['ref_atual_od_cilindrico']) && $refracao->refracao['refracao_atual']['ref_atual_od_cilindrico'] == ('-'.(string)$val))?'selected' : '' ?>>
                                            -<?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">Eixoº</label>
                                <select name="refracao_atual[ref_atual_od_eixo]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 5;
                                    while ($value <= 180):
                                        $val = $value;
                                        $value += 5;
                                        ?>
                                        <option value="<?= $val ?>" <?= (isset($refracao->refracao['refracao_atual']['ref_atual_od_eixo']) && $refracao->refracao['refracao_atual']['ref_atual_od_eixo'] == $val)? 'selected' : '' ?>>
                                            <?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">Adição</label>
                                <select name="refracao_atual[ref_atual_od_adicao]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 0.5;
                                    while ($value <= 4):
                                        $val = number_format($value, 2, ',', '.');
                                        $value += 0.25;
                                        ?>
                                        <option value="+<?= $val ?>"  <?= (isset($refracao->refracao['refracao_atual']['ref_atual_od_adicao']) && $refracao->refracao['refracao_atual']['ref_atual_od_adicao'] == ('+'.(string)$val))? 'selected' : '' ?>>
                                            +<?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <h3>OE</h3>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <select name="refracao_atual[ref_atual_oe_esferico]" class="form-control selectfild2RefracaoPlano" style="width: 100%">
                                    <?php
                                        $value = 30;
                                        while ($value > 0):
                                            $val = number_format($value, 2, ',', '.');
                                            $value -= 0.25;
                                            ?>
                                            <option value="-<?= $val ?>" <?= (isset($refracao->refracao['refracao_atual']['ref_atual_oe_esferico']) && $refracao->refracao['refracao_atual']['ref_atual_oe_esferico'] == ('-'.(string)$val))?'selected' : '' ?>>
                                                -<?= $val ?>
                                            </option>
                                            <?php
                                        endwhile;
                                        ?>
                                        <option value="plano"
                                            <?= (isset($refracao->refracao['refracao_atual']['ref_atual_oe_esferico']) && $refracao->refracao['refracao_atual']['ref_atual_oe_esferico'] == 'plano')?'selected' : '' ?>
                                            <?= (!isset($refracao->refracao['refracao_atual']))?'selected' : '' ?>
                                        >
                                            PLANO
                                        </option>
                                        <?php
                                        $value = 0.25;
                                        while ($value <= 30):
                                            $val = number_format($value, 2, ',', '.');
                                            $value += 0.25;
                                            ?>
                                            <option value="+<?= $val ?>" <?= (isset($refracao->refracao['refracao_atual']['ref_atual_oe_esferico']) && $refracao->refracao['refracao_atual']['ref_atual_oe_esferico'] == ('+'.(string)$val))? 'selected' : '' ?>>
                                                +<?= $val ?>
                                            </option>
                                            <?php
                                        endwhile;
                                        ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <select name="refracao_atual[ref_atual_oe_cilindrico]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 0.25;
                                    while ($value <= 8):
                                        $val = number_format($value, 2, ',', '.');
                                        $value += 0.25;
                                        ?>
                                        <option value="-<?= $val ?>" <?= (isset($refracao->refracao['refracao_atual']['ref_atual_oe_cilindrico']) && $refracao->refracao['refracao_atual']['ref_atual_oe_cilindrico'] == ('-'.(string)$val))?'selected' : '' ?>>
                                            -<?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <select name="refracao_atual[ref_atual_oe_eixo]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 5;
                                    while ($value <= 180):
                                        $val = $value;
                                        $value += 5;
                                        ?>
                                        <option value="<?= $val ?>" <?= (isset($refracao->refracao['refracao_atual']['ref_atual_oe_eixo']) && $refracao->refracao['refracao_atual']['ref_atual_oe_eixo'] == $val)? 'selected' : '' ?>>
                                            <?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <select name="refracao_atual[ref_atual_oe_adicao]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 0.5;
                                    while ($value <= 4):
                                        $val = number_format($value, 2, ',', '.');
                                        $value += 0.25;
                                        ?>
                                        <option value="+<?= $val ?>"  <?= (isset($refracao->refracao['refracao_atual']['ref_atual_oe_adicao']) && $refracao->refracao['refracao_atual']['ref_atual_oe_adicao'] == ('+'.(string)$val))? 'selected' : '' ?>>
                                            +<?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label class="form-control-label">Observações</label>
                        <textarea class="form-control" name="refracao_atual[ref_atual_obs]" id="refracao_atual[ref_atual_obs]" cols="10" rows="5"><?= (isset($refracao->refracao['refracao_atual']['ref_atual_obs']))? $refracao->refracao['refracao_atual']['ref_atual_obs'] : '' ?></textarea>
                    </div>
                </div>
            </div>
            <div class="tab-pane p-20" id="ac_visual" role="tabpanel">
                <div class="row">
                    <div class="col-md-2">
                        <h3 style="margin-top: 35px;">OD</h3>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-control-label">S/C</label>
                                <select name="acuidade_visual[acuidade_od_sc]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == '')?'selected' : '' ?> value=""></option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == '20/15')?'selected' : '' ?> value="20/15">20/15</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == '20/20')?'selected' : '' ?> value="20/20">20/20</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == '20/20')?'selected' : '' ?> value="20/20">20/25</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == '20/30')?'selected' : '' ?> value="20/30">20/30</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == '20/40')?'selected' : '' ?> value="20/40">20/40</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == '20/50')?'selected' : '' ?> value="20/50">20/50</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == '20/60')?'selected' : '' ?> value="20/60">20/60</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == '20/70')?'selected' : '' ?> value="20/70">20/70</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == '20/80')?'selected' : '' ?> value="20/80">20/80</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == '20/100')?'selected' : '' ?> value="20/100">20/100</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == '20/150')?'selected' : '' ?> value="20/150">20/150</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == '20/200')?'selected' : '' ?> value="20/200">20/200</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == '20/300')?'selected' : '' ?> value="20/300">20/300</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == '20/400')?'selected' : '' ?> value="20/400">20/400</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == 'CD 6m')?'selected' : '' ?> value="CD 6m">CD 6m</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == 'CD 5m')?'selected' : '' ?> value="CD 5m">CD 5m</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == 'CD 4m')?'selected' : '' ?> value="CD 4m">CD 4m</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == 'CD 3m')?'selected' : '' ?> value="CD 3m">CD 3m</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == 'CD 2m')?'selected' : '' ?> value="CD 2m">CD 2m</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == 'CD 1m')?'selected' : '' ?> value="CD 1m">CD 1m</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == 'CD 80cm')?'selected' : '' ?> value="CD 80cm">CD 80cm</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == 'CD 70cm')?'selected' : '' ?> value="CD 70cm">CD 70cm</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == 'CD 60cm')?'selected' : '' ?> value="CD 60cm">CD 60cm</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == 'CD 50cm')?'selected' : '' ?> value="CD 50cm">CD 50cm</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == 'CD 40cm')?'selected' : '' ?> value="CD 40cm">CD 40cm</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == 'CD 30cm')?'selected' : '' ?> value="CD 30cm">CD 30cm</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == 'CD 20cm')?'selected' : '' ?> value="CD 20cm">CD 20cm</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == 'CD 10cm')?'selected' : '' ?> value="CD 10cm">CD 10cm</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == 'MM/Vultos')?'selected' : '' ?> value="MM/Vultos">MM/Vultos</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == 'PL')?'selected' : '' ?> value="PL">PL</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc'] == 'SPL')?'selected' : '' ?> value="SPL">SPL</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-control-label">C/C</label>
                                <select name="acuidade_visual[acuidade_od_cc]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == '')?'selected' : '' ?> value=""></option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == '20/15')?'selected' : '' ?> value="20/15">20/15</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == '20/20')?'selected' : '' ?> value="20/20">20/20</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == '20/20')?'selected' : '' ?> value="20/20">20/25</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == '20/30')?'selected' : '' ?> value="20/30">20/30</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == '20/40')?'selected' : '' ?> value="20/40">20/40</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == '20/50')?'selected' : '' ?> value="20/50">20/50</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == '20/60')?'selected' : '' ?> value="20/60">20/60</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == '20/70')?'selected' : '' ?> value="20/70">20/70</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == '20/80')?'selected' : '' ?> value="20/80">20/80</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == '20/100')?'selected' : '' ?> value="20/100">20/100</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == '20/150')?'selected' : '' ?> value="20/150">20/150</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == '20/200')?'selected' : '' ?> value="20/200">20/200</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == '20/300')?'selected' : '' ?> value="20/300">20/300</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == '20/400')?'selected' : '' ?> value="20/400">20/400</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == 'CD 6m')?'selected' : '' ?> value="CD 6m">CD 6m</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == 'CD 5m')?'selected' : '' ?> value="CD 5m">CD 5m</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == 'CD 4m')?'selected' : '' ?> value="CD 4m">CD 4m</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == 'CD 3m')?'selected' : '' ?> value="CD 3m">CD 3m</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == 'CD 2m')?'selected' : '' ?> value="CD 2m">CD 2m</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == 'CD 1m')?'selected' : '' ?> value="CD 1m">CD 1m</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == 'CD 80cm')?'selected' : '' ?> value="CD 80cm">CD 80cm</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == 'CD 70cm')?'selected' : '' ?> value="CD 70cm">CD 70cm</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == 'CD 60cm')?'selected' : '' ?> value="CD 60cm">CD 60cm</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == 'CD 50cm')?'selected' : '' ?> value="CD 50cm">CD 50cm</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == 'CD 40cm')?'selected' : '' ?> value="CD 40cm">CD 40cm</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == 'CD 30cm')?'selected' : '' ?> value="CD 30cm">CD 30cm</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == 'CD 20cm')?'selected' : '' ?> value="CD 20cm">CD 20cm</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == 'CD 10cm')?'selected' : '' ?> value="CD 10cm">CD 10cm</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == 'MM/Vultos')?'selected' : '' ?> value="MM/Vultos">MM/Vultos</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == 'PL')?'selected' : '' ?> value="PL">PL</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc'] == 'SPL')?'selected' : '' ?> value="SPL">SPL</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <input type="checkbox" id="acuidade_visual_acuidade_od_sc_ck" name="acuidade_visual[acuidade_od_sc_ck]" value="1" class="filled-in chk-col-black form-control checkboxRefracao" <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_sc_ck']) && $refracao->refracao['acuidade_visual']['acuidade_od_sc_ck'] == '1')? 'checked' : '' ?>   />
                                <label class="form-control-label" for="acuidade_visual_acuidade_od_sc_ck">Parcial</label>
                            </div>
                            <div class="form-group col-md-6">
                                <input type="checkbox" id="acuidade_visual_acuidade_od_cc_ck" name="acuidade_visual[acuidade_od_cc_ck]" value="1" class="filled-in chk-col-black form-control checkboxRefracao" <?= (isset($refracao->refracao['acuidade_visual']['acuidade_od_cc_ck']) && $refracao->refracao['acuidade_visual']['acuidade_od_cc_ck'] == '1')? 'checked' : '' ?>   />
                                <label class="form-control-label" for="acuidade_visual_acuidade_od_cc_ck">Parcial</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <h3>OE</h3>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <select name="acuidade_visual[acuidade_oe_sc]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == '')?'selected' : '' ?> value=""></option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == '20/15')?'selected' : '' ?> value="20/15">20/15</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == '20/20')?'selected' : '' ?> value="20/20">20/20</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == '20/20')?'selected' : '' ?> value="20/20">20/25</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == '20/30')?'selected' : '' ?> value="20/30">20/30</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == '20/40')?'selected' : '' ?> value="20/40">20/40</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == '20/50')?'selected' : '' ?> value="20/50">20/50</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == '20/60')?'selected' : '' ?> value="20/60">20/60</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == '20/70')?'selected' : '' ?> value="20/70">20/70</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == '20/80')?'selected' : '' ?> value="20/80">20/80</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == '20/100')?'selected' : '' ?> value="20/100">20/100</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == '20/150')?'selected' : '' ?> value="20/150">20/150</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == '20/200')?'selected' : '' ?> value="20/200">20/200</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == '20/300')?'selected' : '' ?> value="20/300">20/300</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == '20/400')?'selected' : '' ?> value="20/400">20/400</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == 'CD 6m')?'selected' : '' ?> value="CD 6m">CD 6m</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == 'CD 5m')?'selected' : '' ?> value="CD 5m">CD 5m</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == 'CD 4m')?'selected' : '' ?> value="CD 4m">CD 4m</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == 'CD 3m')?'selected' : '' ?> value="CD 3m">CD 3m</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == 'CD 2m')?'selected' : '' ?> value="CD 2m">CD 2m</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == 'CD 1m')?'selected' : '' ?> value="CD 1m">CD 1m</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == 'CD 80cm')?'selected' : '' ?> value="CD 80cm">CD 80cm</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == 'CD 70cm')?'selected' : '' ?> value="CD 70cm">CD 70cm</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == 'CD 60cm')?'selected' : '' ?> value="CD 60cm">CD 60cm</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == 'CD 50cm')?'selected' : '' ?> value="CD 50cm">CD 50cm</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == 'CD 40cm')?'selected' : '' ?> value="CD 40cm">CD 40cm</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == 'CD 30cm')?'selected' : '' ?> value="CD 30cm">CD 30cm</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == 'CD 20cm')?'selected' : '' ?> value="CD 20cm">CD 20cm</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == 'CD 10cm')?'selected' : '' ?> value="CD 10cm">CD 10cm</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == 'MM/Vultos')?'selected' : '' ?> value="MM/Vultos">MM/Vultos</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == 'PL')?'selected' : '' ?> value="PL">PL</option>
                                    <option  <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] == 'SPL')?'selected' : '' ?> value="SPL">SPL</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <select name="acuidade_visual[acuidade_oe_cc]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == '')?'selected' : '' ?> value=""></option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == '20/15')?'selected' : '' ?> value="20/15">20/15</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == '20/20')?'selected' : '' ?> value="20/20">20/20</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == '20/20')?'selected' : '' ?> value="20/20">20/25</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == '20/30')?'selected' : '' ?> value="20/30">20/30</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == '20/40')?'selected' : '' ?> value="20/40">20/40</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == '20/50')?'selected' : '' ?> value="20/50">20/50</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == '20/60')?'selected' : '' ?> value="20/60">20/60</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == '20/70')?'selected' : '' ?> value="20/70">20/70</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == '20/80')?'selected' : '' ?> value="20/80">20/80</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == '20/100')?'selected' : '' ?> value="20/100">20/100</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == '20/150')?'selected' : '' ?> value="20/150">20/150</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == '20/200')?'selected' : '' ?> value="20/200">20/200</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == '20/300')?'selected' : '' ?> value="20/300">20/300</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == '20/400')?'selected' : '' ?> value="20/400">20/400</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == 'CD 6m')?'selected' : '' ?> value="CD 6m">CD 6m</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == 'CD 5m')?'selected' : '' ?> value="CD 5m">CD 5m</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == 'CD 4m')?'selected' : '' ?> value="CD 4m">CD 4m</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == 'CD 3m')?'selected' : '' ?> value="CD 3m">CD 3m</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == 'CD 2m')?'selected' : '' ?> value="CD 2m">CD 2m</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == 'CD 1m')?'selected' : '' ?> value="CD 1m">CD 1m</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == 'CD 80cm')?'selected' : '' ?> value="CD 80cm">CD 80cm</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == 'CD 70cm')?'selected' : '' ?> value="CD 70cm">CD 70cm</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == 'CD 60cm')?'selected' : '' ?> value="CD 60cm">CD 60cm</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == 'CD 50cm')?'selected' : '' ?> value="CD 50cm">CD 50cm</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == 'CD 40cm')?'selected' : '' ?> value="CD 40cm">CD 40cm</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == 'CD 30cm')?'selected' : '' ?> value="CD 30cm">CD 30cm</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == 'CD 20cm')?'selected' : '' ?> value="CD 20cm">CD 20cm</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == 'CD 10cm')?'selected' : '' ?> value="CD 10cm">CD 10cm</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == 'MM/Vultos')?'selected' : '' ?> value="MM/Vultos">MM/Vultos</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == 'PL')?'selected' : '' ?> value="PL">PL</option>
                                    <option <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] == 'SPL')?'selected' : '' ?> value="SPL">SPL</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <input type="checkbox" id="acuidade_visual_acuidade_oe_sc_ck" name="acuidade_visual[acuidade_oe_sc_ck]" value="1" class="filled-in chk-col-black form-control checkboxRefracao" <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc_ck']) && $refracao->refracao['acuidade_visual']['acuidade_oe_sc_ck'] == '1')? 'checked' : '' ?>   />
                                <label class="form-control-label" for="acuidade_visual_acuidade_oe_sc_ck">Parcial</label>
                            </div>
                            <div class="form-group col-md-6">
                                <input type="checkbox" id="acuidade_visual_acuidade_oe_cc_ck" name="acuidade_visual[acuidade_oe_cc_ck]" value="1" class="filled-in chk-col-black form-control checkboxRefracao" <?= (isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc_ck']) && $refracao->refracao['acuidade_visual']['acuidade_oe_cc_ck'] == '1')? 'checked' : '' ?>   />
                                <label class="form-control-label" for="acuidade_visual_acuidade_oe_cc_ck">Parcial</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane p-20" id="ref_estatica" role="tabpanel">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-10"><b><h5>Longe:</h5></b></div>
                    <div class="col-md-2">
                        <h3 style="margin-top: 35px;">OD</h3>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="form-control-label">Esférico</label>
                                <select name="refracao_estatica[ref_estatica_l_od_esferico]" class="form-control selectfild2RefracaoPlano" style="width: 100%">
                                    <?php
                                        $value = 30;
                                        while ($value > 0):
                                            $val = number_format($value, 2, ',', '.');
                                            $value -= 0.25;
                                            ?>
                                            <option value="-<?= $val ?>" <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_esferico']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_esferico'] == ('-'.(string)$val))?'selected' : '' ?>>
                                                -<?= $val ?>
                                            </option>
                                            <?php
                                        endwhile;
                                        ?>
                                        <option value="plano"
                                            <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_esferico']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_esferico'] == 'plano')?'selected' : '' ?>
                                            <?= (!isset($refracao->refracao['refracao_estatica']))?'selected' : '' ?>
                                        >
                                            PLANO
                                        </option>
                                        <?php
                                        $value = 0.25;
                                        while ($value <= 30):
                                            $val = number_format($value, 2, ',', '.');
                                            $value += 0.25;
                                            ?>
                                            <option value="+<?= $val ?>" <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_esferico']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_esferico'] == ('+'.(string)$val))? 'selected' : '' ?>>
                                                +<?= $val ?>
                                            </option>
                                            <?php
                                        endwhile;
                                        ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">Cilíndrico</label>
                                <select name="refracao_estatica[ref_estatica_l_od_cilindrico]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 0.25;
                                    while ($value <= 8):
                                        $val = number_format($value, 2, ',', '.');
                                        $value += 0.25;
                                        ?>
                                        <option value="-<?= $val ?>" <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_cilindrico']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_cilindrico'] == ('-'.(string)$val))?'selected' : '' ?>>
                                            -<?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">Eixoº</label>
                                <select name="refracao_estatica[ref_estatica_l_od_eixo]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 5;
                                    while ($value <= 180):
                                        $val = $value;
                                        $value += 5;
                                        ?>
                                        <option value="<?= $val ?>" <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_eixo']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_eixo'] == $val)? 'selected' : '' ?>>
                                            <?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">Av</label>
                                <select name="refracao_estatica[ref_estatica_l_od_av]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == '')?'selected' : '' ?> value=""></option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == '20/15')?'selected' : '' ?> value="20/15">20/15</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == '20/20')?'selected' : '' ?> value="20/20">20/20</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == '20/20')?'selected' : '' ?> value="20/20">20/25</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == '20/30')?'selected' : '' ?> value="20/30">20/30</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == '20/40')?'selected' : '' ?> value="20/40">20/40</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == '20/50')?'selected' : '' ?> value="20/50">20/50</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == '20/60')?'selected' : '' ?> value="20/60">20/60</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == '20/70')?'selected' : '' ?> value="20/70">20/70</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == '20/80')?'selected' : '' ?> value="20/80">20/80</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == '20/100')?'selected' : '' ?> value="20/100">20/100</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == '20/150')?'selected' : '' ?> value="20/150">20/150</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == '20/200')?'selected' : '' ?> value="20/200">20/200</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == '20/300')?'selected' : '' ?> value="20/300">20/300</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == '20/400')?'selected' : '' ?> value="20/400">20/400</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == 'CD 6m')?'selected' : '' ?> value="CD 6m">CD 6m</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == 'CD 5m')?'selected' : '' ?> value="CD 5m">CD 5m</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == 'CD 4m')?'selected' : '' ?> value="CD 4m">CD 4m</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == 'CD 3m')?'selected' : '' ?> value="CD 3m">CD 3m</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == 'CD 2m')?'selected' : '' ?> value="CD 2m">CD 2m</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == 'CD 1m')?'selected' : '' ?> value="CD 1m">CD 1m</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == 'CD 80cm')?'selected' : '' ?> value="CD 80cm">CD 80cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == 'CD 70cm')?'selected' : '' ?> value="CD 70cm">CD 70cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == 'CD 60cm')?'selected' : '' ?> value="CD 60cm">CD 60cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == 'CD 50cm')?'selected' : '' ?> value="CD 50cm">CD 50cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == 'CD 40cm')?'selected' : '' ?> value="CD 40cm">CD 40cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == 'CD 30cm')?'selected' : '' ?> value="CD 30cm">CD 30cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == 'CD 20cm')?'selected' : '' ?> value="CD 20cm">CD 20cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == 'CD 10cm')?'selected' : '' ?> value="CD 10cm">CD 10cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == 'MM/Vultos')?'selected' : '' ?> value="MM/Vultos">MM/Vultos</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == 'PL')?'selected' : '' ?>  value="PL">PL</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] == 'SPL')?'selected': '' ?> value="SPL">SPL</option>
                                </select>
                            </div>
                            <div class="col-md-9"></div>
                            <div class="form-group col-md-3">
                                <input type="checkbox" id="refracao_estatica_ref_estatica_l_od_av_ck" name="refracao_estatica[ref_estatica_l_od_av_ck]" value="1" class="filled-in chk-col-black form-control checkboxRefracao" <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av_ck']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av_ck'] == '1')? 'checked' : '' ?>   />
                                <label class="form-control-label" for="refracao_estatica_ref_estatica_l_od_av_ck">Parcial</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <h3>OE</h3>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <select name="refracao_estatica[ref_estatica_l_oe_esferico]" class="form-control selectfild2RefracaoPlano" style="width: 100%">
                                    <?php
                                        $value = 30;
                                        while ($value > 0):
                                            $val = number_format($value, 2, ',', '.');
                                            $value -= 0.25;
                                            ?>
                                            <option value="-<?= $val ?>" <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_esferico']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_esferico'] == ('-'.(string)$val))?'selected' : '' ?>>
                                                -<?= $val ?>
                                            </option>
                                            <?php
                                        endwhile;
                                        ?>
                                        <option value="plano"
                                            <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_esferico']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_esferico'] == 'plano')?'selected' : '' ?>
                                            <?= (!isset($refracao->refracao['refracao_estatica']))?'selected' : '' ?>
                                        >
                                            PLANO
                                        </option>
                                        <?php
                                        $value = 0.25;
                                        while ($value <= 30):
                                            $val = number_format($value, 2, ',', '.');
                                            $value += 0.25;
                                            ?>
                                            <option value="+<?= $val ?>" <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_esferico']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_esferico'] == ('+'.(string)$val))? 'selected' : '' ?>>
                                                +<?= $val ?>
                                            </option>
                                            <?php
                                        endwhile;
                                        ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <select name="refracao_estatica[ref_estatica_l_oe_cilindrico]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 0.25;
                                    while ($value <= 8):
                                        $val = number_format($value, 2, ',', '.');
                                        $value += 0.25;
                                        ?>
                                        <option value="-<?= $val ?>" <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_cilindrico']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_cilindrico'] == ('-'.(string)$val))?'selected' : '' ?>>
                                            -<?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <select name="refracao_estatica[ref_estatica_l_oe_eixo]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 5;
                                    while ($value <= 180):
                                        $val = $value;
                                        $value += 5;
                                        ?>
                                        <option value="<?= $val ?>" <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_eixo']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_eixo'] == $val)? 'selected' : '' ?>>
                                            <?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <select name="refracao_estatica[ref_estatica_l_oe_av]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == '')?'selected' : '' ?> value=""></option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == '20/15')?'selected' : '' ?> value="20/15">20/15</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == '20/20')?'selected' : '' ?> value="20/20">20/20</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == '20/20')?'selected' : '' ?> value="20/20">20/25</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == '20/30')?'selected' : '' ?> value="20/30">20/30</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == '20/40')?'selected' : '' ?> value="20/40">20/40</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == '20/50')?'selected' : '' ?> value="20/50">20/50</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == '20/60')?'selected' : '' ?> value="20/60">20/60</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == '20/70')?'selected' : '' ?> value="20/70">20/70</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == '20/80')?'selected' : '' ?> value="20/80">20/80</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == '20/100')?'selected' : '' ?> value="20/100">20/100</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == '20/150')?'selected' : '' ?> value="20/150">20/150</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == '20/200')?'selected' : '' ?> value="20/200">20/200</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == '20/300')?'selected' : '' ?> value="20/300">20/300</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == '20/400')?'selected' : '' ?> value="20/400">20/400</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == 'CD 6m')?'selected' : '' ?> value="CD 6m">CD 6m</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == 'CD 5m')?'selected' : '' ?> value="CD 5m">CD 5m</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == 'CD 4m')?'selected' : '' ?> value="CD 4m">CD 4m</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == 'CD 3m')?'selected' : '' ?> value="CD 3m">CD 3m</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == 'CD 2m')?'selected' : '' ?> value="CD 2m">CD 2m</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == 'CD 1m')?'selected' : '' ?> value="CD 1m">CD 1m</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == 'CD 80cm')?'selected' : '' ?> value="CD 80cm">CD 80cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == 'CD 70cm')?'selected' : '' ?> value="CD 70cm">CD 70cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == 'CD 60cm')?'selected' : '' ?> value="CD 60cm">CD 60cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == 'CD 50cm')?'selected' : '' ?> value="CD 50cm">CD 50cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == 'CD 40cm')?'selected' : '' ?> value="CD 40cm">CD 40cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == 'CD 30cm')?'selected' : '' ?> value="CD 30cm">CD 30cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == 'CD 20cm')?'selected' : '' ?> value="CD 20cm">CD 20cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == 'CD 10cm')?'selected' : '' ?> value="CD 10cm">CD 10cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == 'MM/Vultos')?'selected' : '' ?> value="MM/Vultos">MM/Vultos</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == 'PL')?'selected' : '' ?>  value="PL">PL</option>
                                    <option <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] == 'SPL')?'selected': '' ?> value="SPL">SPL</option>
                                </select>
                            </div>
                            <div class="col-md-9"></div>
                            <div class="form-group col-md-3">
                                <input type="checkbox" id="refracao_estatica_ref_estatica_l_oe_av_ck" name="refracao_estatica[ref_estatica_l_oe_av_ck]" value="1" class="filled-in chk-col-black form-control checkboxRefracao" <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av_ck']) && $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av_ck'] == '1')? 'checked' : '' ?>   />
                                <label class="form-control-label" for="refracao_estatica_ref_estatica_l_oe_av_ck">Parcial</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-10"><b><h5>Perto:</h5></b></div>
                    <div class="col-md-2">
                        <h3 style="margin-top: 35px;">OD</h3>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="form-control-label">Adição</label>
                                <select name="refracao_estatica[ref_estatica_p_od_adicao]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 0.5;
                                    while ($value <= 4):
                                        $val = number_format($value, 2, ',', '.');
                                        $value += 0.25;
                                        ?>
                                        <option value="+<?= $val ?>" <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_p_od_adicao']) && $refracao->refracao['refracao_estatica']['ref_estatica_p_od_adicao'] == ('+'.(string)$val))? 'selected' : '' ?>>
                                            +<?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="form-control-label">Jaeger</label>
                                <select name="refracao_estatica[ref_estatica_p_od_jaeger]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option  <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_p_od_jaeger']) && $refracao->refracao['refracao_estatica']['ref_estatica_p_od_jaeger'] ==  '')? 'selected' : '' ?> value=""></option>
                                    <option  <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_p_od_jaeger']) && $refracao->refracao['refracao_estatica']['ref_estatica_p_od_jaeger'] ==  'J1')? 'selected' : '' ?> value="J1">J1</option>
                                    <option  <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_p_od_jaeger']) && $refracao->refracao['refracao_estatica']['ref_estatica_p_od_jaeger'] ==  'J2')? 'selected' : '' ?> value="J2">J2</option>
                                    <option  <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_p_od_jaeger']) && $refracao->refracao['refracao_estatica']['ref_estatica_p_od_jaeger'] ==  'J3')? 'selected' : '' ?> value="J3">J3</option>
                                    <option  <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_p_od_jaeger']) && $refracao->refracao['refracao_estatica']['ref_estatica_p_od_jaeger'] ==  'J4')? 'selected' : '' ?> value="J4">J4</option>
                                    <option  <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_p_od_jaeger']) && $refracao->refracao['refracao_estatica']['ref_estatica_p_od_jaeger'] ==  'J5')? 'selected' : '' ?> value="J5">J5</option>
                                    <option  <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_p_od_jaeger']) && $refracao->refracao['refracao_estatica']['ref_estatica_p_od_jaeger'] ==  'J6')? 'selected' : '' ?> value="J6">J6</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <h3>OE</h3>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <select name="refracao_estatica[ref_estatica_p_oe_adicao]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 0.5;
                                    while ($value <= 4):
                                        $val = number_format($value, 2, ',', '.');
                                        $value += 0.25;
                                        ?>
                                        <option value="+<?= $val ?>" <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_p_oe_adicao']) && $refracao->refracao['refracao_estatica']['ref_estatica_p_oe_adicao'] == ('+'.(string)$val))? 'selected' : '' ?>>
                                            +<?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <select name="refracao_estatica[ref_estatica_p_oe_jaeger]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option  <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_p_oe_jaeger']) && $refracao->refracao['refracao_estatica']['ref_estatica_p_oe_jaeger'] ==  '')? 'selected' : '' ?> value=""></option>
                                    <option  <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_p_oe_jaeger']) && $refracao->refracao['refracao_estatica']['ref_estatica_p_oe_jaeger'] ==  'J1')? 'selected' : '' ?> value="J1">J1</option>
                                    <option  <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_p_oe_jaeger']) && $refracao->refracao['refracao_estatica']['ref_estatica_p_oe_jaeger'] ==  'J2')? 'selected' : '' ?> value="J2">J2</option>
                                    <option  <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_p_oe_jaeger']) && $refracao->refracao['refracao_estatica']['ref_estatica_p_oe_jaeger'] ==  'J3')? 'selected' : '' ?> value="J3">J3</option>
                                    <option  <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_p_oe_jaeger']) && $refracao->refracao['refracao_estatica']['ref_estatica_p_oe_jaeger'] ==  'J4')? 'selected' : '' ?> value="J4">J4</option>
                                    <option  <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_p_oe_jaeger']) && $refracao->refracao['refracao_estatica']['ref_estatica_p_oe_jaeger'] ==  'J5')? 'selected' : '' ?> value="J5">J5</option>
                                    <option  <?= (isset($refracao->refracao['refracao_estatica']['ref_estatica_p_oe_jaeger']) && $refracao->refracao['refracao_estatica']['ref_estatica_p_oe_jaeger'] ==  'J6')? 'selected' : '' ?> value="J6">J6</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane p-20" id="ref_dinamica" role="tabpanel">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-10"><b><h5>Longe:</h5></b></div>
                    <div class="col-md-2">
                        <h3 style="margin-top: 35px;">OD</h3>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="form-control-label">Esférico</label>
                                <select name="refracao_dinamica[ref_dinamica_l_od_esferico]" class="form-control selectfild2RefracaoPlano" style="width: 100%">
                                    <?php
                                        $value = 30;
                                        while ($value > 0):
                                            $val = number_format($value, 2, ',', '.');
                                            $value -= 0.25;
                                            ?>
                                            <option value="-<?= $val ?>" <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_esferico']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_esferico'] == ('-'.(string)$val))?'selected' : '' ?>>
                                                -<?= $val ?>
                                            </option>
                                            <?php
                                        endwhile;
                                        ?>
                                        <option value="plano"
                                            <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_esferico']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_esferico'] == 'plano')?'selected' : '' ?>
                                            <?= (!isset($refracao->refracao['refracao_dinamica']))?'selected' : '' ?>
                                        >
                                            PLANO
                                        </option>
                                        <?php
                                        $value = 0.25;
                                        while ($value <= 30):
                                            $val = number_format($value, 2, ',', '.');
                                            $value += 0.25;
                                            ?>
                                            <option value="+<?= $val ?>" <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_esferico']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_esferico'] == ('+'.(string)$val))? 'selected' : '' ?>>
                                                +<?= $val ?>
                                            </option>
                                            <?php
                                        endwhile;
                                        ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">Cilíndrico</label>
                                <select name="refracao_dinamica[ref_dinamica_l_od_cilindrico]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 0.25;
                                    while ($value <= 8):
                                        $val = number_format($value, 2, ',', '.');
                                        $value += 0.25;
                                        ?>
                                        <option value="-<?= $val ?>" <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_cilindrico']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_cilindrico'] == ('-'.(string)$val))?'selected' : '' ?>>
                                            -<?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">Eixoº</label>
                                <select name="refracao_dinamica[ref_dinamica_l_od_eixo]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 5;
                                    while ($value <= 180):
                                        $val = $value;
                                        $value += 5;
                                        ?>
                                        <option value="<?= $val ?>" <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_eixo']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_eixo'] == $val)? 'selected' : '' ?>>
                                            <?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">Av</label>
                                <select name="refracao_dinamica[ref_dinamica_l_od_av]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == '')?'selected' : '' ?> value=""></option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == '20/15')?'selected' : '' ?> value="20/15">20/15</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == '20/20')?'selected' : '' ?> value="20/20">20/20</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == '20/20')?'selected' : '' ?> value="20/20">20/25</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == '20/30')?'selected' : '' ?> value="20/30">20/30</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == '20/40')?'selected' : '' ?> value="20/40">20/40</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == '20/50')?'selected' : '' ?> value="20/50">20/50</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == '20/60')?'selected' : '' ?> value="20/60">20/60</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == '20/70')?'selected' : '' ?> value="20/70">20/70</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == '20/80')?'selected' : '' ?> value="20/80">20/80</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == '20/100')?'selected' : '' ?> value="20/100">20/100</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == '20/150')?'selected' : '' ?> value="20/150">20/150</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == '20/200')?'selected' : '' ?> value="20/200">20/200</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == '20/300')?'selected' : '' ?> value="20/300">20/300</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == '20/400')?'selected' : '' ?> value="20/400">20/400</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == 'CD 6m')?'selected' : '' ?> value="CD 6m">CD 6m</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == 'CD 5m')?'selected' : '' ?> value="CD 5m">CD 5m</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == 'CD 4m')?'selected' : '' ?> value="CD 4m">CD 4m</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == 'CD 3m')?'selected' : '' ?> value="CD 3m">CD 3m</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == 'CD 2m')?'selected' : '' ?> value="CD 2m">CD 2m</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == 'CD 1m')?'selected' : '' ?> value="CD 1m">CD 1m</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == 'CD 80cm')?'selected' : '' ?> value="CD 80cm">CD 80cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == 'CD 70cm')?'selected' : '' ?> value="CD 70cm">CD 70cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == 'CD 60cm')?'selected' : '' ?> value="CD 60cm">CD 60cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == 'CD 50cm')?'selected' : '' ?> value="CD 50cm">CD 50cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == 'CD 40cm')?'selected' : '' ?> value="CD 40cm">CD 40cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == 'CD 30cm')?'selected' : '' ?> value="CD 30cm">CD 30cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == 'CD 20cm')?'selected' : '' ?> value="CD 20cm">CD 20cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == 'CD 10cm')?'selected' : '' ?> value="CD 10cm">CD 10cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == 'MM/Vultos')?'selected' : '' ?> value="MM/Vultos">MM/Vultos</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == 'PL')?'selected' : '' ?>  value="PL">PL</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] == 'SPL')?'selected': '' ?> value="SPL">SPL</option>
                                </select>
                            </div>
                            <div class="col-md-9"></div>
                            <div class="form-group col-md-3">
                                <input type="checkbox" id="refracao_dinamica_ref_dinamica_l_od_av_ck" name="refracao_dinamica[ref_dinamica_l_od_av_ck]" value="1" class="filled-in chk-col-black form-control checkboxRefracao" <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av_ck']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av_ck'] == '1')? 'checked' : '' ?>   />
                                <label class="form-control-label" for="refracao_dinamica_ref_dinamica_l_od_av_ck">Parcial</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <h3>OE</h3>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <select name="refracao_dinamica[ref_dinamica_l_oe_esferico]" class="form-control selectfild2RefracaoPlano" style="width: 100%">
                                    <?php
                                        $value = 30;
                                        while ($value > 0):
                                            $val = number_format($value, 2, ',', '.');
                                            $value -= 0.25;
                                            ?>
                                            <option value="-<?= $val ?>" <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_esferico']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_esferico'] == ('-'.(string)$val))?'selected' : '' ?>>
                                                -<?= $val ?>
                                            </option>
                                            <?php
                                        endwhile;
                                        ?>
                                        <option value="plano"
                                            <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_esferico']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_esferico'] == 'plano')?'selected' : '' ?>
                                            <?= (!isset($refracao->refracao['refracao_dinamica']))?'selected' : '' ?>
                                        >
                                            PLANO
                                        </option>
                                        <?php
                                        $value = 0.25;
                                        while ($value <= 30):
                                            $val = number_format($value, 2, ',', '.');
                                            $value += 0.25;
                                            ?>
                                            <option value="+<?= $val ?>" <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_esferico']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_esferico'] == ('+'.(string)$val))? 'selected' : '' ?>>
                                                +<?= $val ?>
                                            </option>
                                            <?php
                                        endwhile;
                                        ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <select name="refracao_dinamica[ref_dinamica_l_oe_cilindrico]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 0.25;
                                    while ($value <= 8):
                                        $val = number_format($value, 2, ',', '.');
                                        $value += 0.25;
                                        ?>
                                        <option value="-<?= $val ?>" <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_cilindrico']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_cilindrico'] == ('-'.(string)$val))?'selected' : '' ?>>
                                            -<?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <select name="refracao_dinamica[ref_dinamica_l_oe_eixo]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 5;
                                    while ($value <= 180):
                                        $val = $value;
                                        $value += 5;
                                        ?>
                                        <option value="<?= $val ?>" <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_eixo']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_eixo'] == $val)? 'selected' : '' ?>>
                                            <?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <select name="refracao_dinamica[ref_dinamica_l_oe_av]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == '')?'selected' : '' ?> value=""></option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == '20/15')?'selected' : '' ?> value="20/15">20/15</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == '20/20')?'selected' : '' ?> value="20/20">20/20</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == '20/20')?'selected' : '' ?> value="20/20">20/25</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == '20/30')?'selected' : '' ?> value="20/30">20/30</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == '20/40')?'selected' : '' ?> value="20/40">20/40</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == '20/50')?'selected' : '' ?> value="20/50">20/50</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == '20/60')?'selected' : '' ?> value="20/60">20/60</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == '20/70')?'selected' : '' ?> value="20/70">20/70</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == '20/80')?'selected' : '' ?> value="20/80">20/80</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == '20/100')?'selected' : '' ?> value="20/100">20/100</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == '20/150')?'selected' : '' ?> value="20/150">20/150</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == '20/200')?'selected' : '' ?> value="20/200">20/200</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == '20/300')?'selected' : '' ?> value="20/300">20/300</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == '20/400')?'selected' : '' ?> value="20/400">20/400</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == 'CD 6m')?'selected' : '' ?> value="CD 6m">CD 6m</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == 'CD 5m')?'selected' : '' ?> value="CD 5m">CD 5m</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == 'CD 4m')?'selected' : '' ?> value="CD 4m">CD 4m</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == 'CD 3m')?'selected' : '' ?> value="CD 3m">CD 3m</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == 'CD 2m')?'selected' : '' ?> value="CD 2m">CD 2m</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == 'CD 1m')?'selected' : '' ?> value="CD 1m">CD 1m</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == 'CD 80cm')?'selected' : '' ?> value="CD 80cm">CD 80cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == 'CD 70cm')?'selected' : '' ?> value="CD 70cm">CD 70cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == 'CD 60cm')?'selected' : '' ?> value="CD 60cm">CD 60cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == 'CD 50cm')?'selected' : '' ?> value="CD 50cm">CD 50cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == 'CD 40cm')?'selected' : '' ?> value="CD 40cm">CD 40cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == 'CD 30cm')?'selected' : '' ?> value="CD 30cm">CD 30cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == 'CD 20cm')?'selected' : '' ?> value="CD 20cm">CD 20cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == 'CD 10cm')?'selected' : '' ?> value="CD 10cm">CD 10cm</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == 'MM/Vultos')?'selected' : '' ?> value="MM/Vultos">MM/Vultos</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == 'PL')?'selected' : '' ?>  value="PL">PL</option>
                                    <option <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] == 'SPL')?'selected': '' ?> value="SPL">SPL</option>
                                </select>
                            </div>
                            <div class="col-md-9"></div>
                            <div class="form-group col-md-3">
                                <input type="checkbox" id="refracao_dinamica_ref_dinamica_l_oe_av_ck" name="refracao_dinamica[ref_dinamica_l_oe_av_ck]" value="1" class="filled-in chk-col-black form-control checkboxRefracao" <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av_ck']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av_ck'] == '1')? 'checked' : '' ?>   />
                                <label class="form-control-label" for="refracao_dinamica_ref_dinamica_l_oe_av_ck">Parcial</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-10"><b><h5>Perto:</h5></b></div>
                    <div class="col-md-2">
                        <h3 style="margin-top: 35px;">OD</h3>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="form-control-label">Adição</label>
                                <select name="refracao_dinamica[ref_dinamica_p_od_adicao]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 0.5;
                                    while ($value <= 4):
                                        $val = number_format($value, 2, ',', '.');
                                        $value += 0.25;
                                        ?>
                                        <option value="+<?= $val ?>" <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_p_od_adicao']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_p_od_adicao'] == ('+'.(string)$val))? 'selected' : '' ?>>
                                            +<?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="form-control-label">Jaeger</label>
                                <select name="refracao_dinamica[ref_dinamica_p_od_jaeger]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option  <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_p_od_jaeger']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_p_od_jaeger'] ==  '')? 'selected' : '' ?> value=""></option>
                                    <option  <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_p_od_jaeger']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_p_od_jaeger'] ==  'J1')? 'selected' : '' ?> value="J1">J1</option>
                                    <option  <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_p_od_jaeger']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_p_od_jaeger'] ==  'J2')? 'selected' : '' ?> value="J2">J2</option>
                                    <option  <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_p_od_jaeger']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_p_od_jaeger'] ==  'J3')? 'selected' : '' ?> value="J3">J3</option>
                                    <option  <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_p_od_jaeger']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_p_od_jaeger'] ==  'J4')? 'selected' : '' ?> value="J4">J4</option>
                                    <option  <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_p_od_jaeger']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_p_od_jaeger'] ==  'J5')? 'selected' : '' ?> value="J5">J5</option>
                                    <option  <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_p_od_jaeger']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_p_od_jaeger'] ==  'J6')? 'selected' : '' ?> value="J6">J6</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <h3>OE</h3>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <select name="refracao_dinamica[ref_dinamica_p_oe_adicao]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 0.5;
                                    while ($value <= 4):
                                        $val = number_format($value, 2, ',', '.');
                                        $value += 0.25;
                                        ?>
                                        <option value="+<?= $val ?>" <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_p_oe_adicao']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_p_oe_adicao'] == ('+'.(string)$val))? 'selected' : '' ?>>
                                            +<?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <select name="refracao_dinamica[ref_dinamica_p_oe_jaeger]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option  <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_p_oe_jaeger']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_p_oe_jaeger'] ==  '')? 'selected' : '' ?> value=""></option>
                                    <option  <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_p_oe_jaeger']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_p_oe_jaeger'] ==  'J1')? 'selected' : '' ?> value="J1">J1</option>
                                    <option  <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_p_oe_jaeger']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_p_oe_jaeger'] ==  'J2')? 'selected' : '' ?> value="J2">J2</option>
                                    <option  <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_p_oe_jaeger']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_p_oe_jaeger'] ==  'J3')? 'selected' : '' ?> value="J3">J3</option>
                                    <option  <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_p_oe_jaeger']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_p_oe_jaeger'] ==  'J4')? 'selected' : '' ?> value="J4">J4</option>
                                    <option  <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_p_oe_jaeger']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_p_oe_jaeger'] ==  'J5')? 'selected' : '' ?> value="J5">J5</option>
                                    <option  <?= (isset($refracao->refracao['refracao_dinamica']['ref_dinamica_p_oe_jaeger']) && $refracao->refracao['refracao_dinamica']['ref_dinamica_p_oe_jaeger'] ==  'J6')? 'selected' : '' ?> value="J6">J6</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane p-20" id="presc_oculos" role="tabpanel">
                <div class="row">
                    <div class="col-md-12 form-group text-right pb-2">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10 copiar_estatico"><i class="mdi mdi-content-copy"></i> Copiar Estático</button>
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10 copiar_dinamico"><i class="mdi mdi-content-copy"></i> Copiar Dinâmico</button>
                    </div>
                    <div class="col-md-2">
                        <h3 style="margin-top: 35px;">OD</h3>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="form-control-label">Esférico</label>
                                <select name="prescricao_oculos[prescricao_od_esferico]" class="form-control selectfild2RefracaoPlano" style="width: 100%">
                                    <?php
                                        $value = 30;
                                        while ($value > 0):
                                            $val = number_format($value, 2, ',', '.');
                                            $value -= 0.25;
                                            ?>
                                            <option value="-<?= $val ?>" <?= (isset($refracao->refracao['prescricao_oculos']['prescricao_od_esferico']) && $refracao->refracao['prescricao_oculos']['prescricao_od_esferico'] == ('-'.(string)$val))?'selected' : '' ?>>
                                                -<?= $val ?>
                                            </option>
                                            <?php
                                        endwhile;
                                        ?>
                                        <option value="plano"
                                            <?= (isset($refracao->refracao['prescricao_oculos']['prescricao_od_esferico']) && $refracao->refracao['prescricao_oculos']['prescricao_od_esferico'] == 'plano')?'selected' : '' ?>
                                            <?= (!isset($refracao->refracao['prescricao_oculos']))?'selected' : '' ?>
                                        >
                                            PLANO
                                        </option>
                                        <?php
                                        $value = 0.25;
                                        while ($value <= 30):
                                            $val = number_format($value, 2, ',', '.');
                                            $value += 0.25;
                                            ?>
                                            <option value="+<?= $val ?>" <?= (isset($refracao->refracao['prescricao_oculos']['prescricao_od_esferico']) && $refracao->refracao['prescricao_oculos']['prescricao_od_esferico'] == ('+'.(string)$val))? 'selected' : '' ?>>
                                                +<?= $val ?>
                                            </option>
                                            <?php
                                        endwhile;
                                        ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">Cilíndrico</label>
                                <select name="prescricao_oculos[prescricao_od_cilindrico]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 0.25;
                                    while ($value <= 8):
                                        $val = number_format($value, 2, ',', '.');
                                        $value += 0.25;
                                        ?>
                                        <option value="-<?= $val ?>" <?= (isset($refracao->refracao['prescricao_oculos']['prescricao_od_cilindrico']) && $refracao->refracao['prescricao_oculos']['prescricao_od_cilindrico'] == ('-'.(string)$val))?'selected' : '' ?>>
                                            -<?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">Eixoº</label>
                                <select name="prescricao_oculos[prescricao_od_eixo]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 5;
                                    while ($value <= 180):
                                        $val = $value;
                                        $value += 5;
                                        ?>
                                        <option value="<?= $val ?>" <?= (isset($refracao->refracao['prescricao_oculos']['prescricao_od_eixo']) && $refracao->refracao['prescricao_oculos']['prescricao_od_eixo'] == $val)? 'selected' : '' ?>>
                                            <?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">Adição</label>
                                <select name="prescricao_oculos[prescricao_od_adicao]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 0.5;
                                    while ($value <= 4):
                                        $val = number_format($value, 2, ',', '.');
                                        $value += 0.25;
                                        ?>
                                        <option value="+<?= $val ?>"  <?= (isset($refracao->refracao['prescricao_oculos']['prescricao_od_adicao']) && $refracao->refracao['prescricao_oculos']['prescricao_od_adicao'] == ('+'.(string)$val))? 'selected' : '' ?>>
                                            +<?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <h3>OE</h3>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <select name="prescricao_oculos[prescricao_oe_esferico]" class="form-control selectfild2RefracaoPlano" style="width: 100%">
                                    <?php
                                        $value = 30;
                                        while ($value > 0):
                                            $val = number_format($value, 2, ',', '.');
                                            $value -= 0.25;
                                            ?>
                                            <option value="-<?= $val ?>" <?= (isset($refracao->refracao['prescricao_oculos']['prescricao_oe_esferico']) && $refracao->refracao['prescricao_oculos']['prescricao_oe_esferico'] == ('-'.(string)$val))?'selected' : '' ?>>
                                                -<?= $val ?>
                                            </option>
                                            <?php
                                        endwhile;
                                        ?>
                                        <option value="plano"
                                            <?= (isset($refracao->refracao['prescricao_oculos']['prescricao_oe_esferico']) && $refracao->refracao['prescricao_oculos']['prescricao_oe_esferico'] == 'plano')?'selected' : '' ?>
                                            <?= (!isset($refracao->refracao['prescricao_oculos']))?'selected' : '' ?>
                                        >
                                            PLANO
                                        </option>
                                        <?php
                                        $value = 0.25;
                                        while ($value <= 30):
                                            $val = number_format($value, 2, ',', '.');
                                            $value += 0.25;
                                            ?>
                                            <option value="+<?= $val ?>" <?= (isset($refracao->refracao['prescricao_oculos']['prescricao_oe_esferico']) && $refracao->refracao['prescricao_oculos']['prescricao_oe_esferico'] == ('+'.(string)$val))? 'selected' : '' ?>>
                                                +<?= $val ?>
                                            </option>
                                            <?php
                                        endwhile;
                                        ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <select name="prescricao_oculos[prescricao_oe_cilindrico]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 0.25;
                                    while ($value <= 8):
                                        $val = number_format($value, 2, ',', '.');
                                        $value += 0.25;
                                        ?>
                                        <option value="-<?= $val ?>" <?= (isset($refracao->refracao['prescricao_oculos']['prescricao_oe_cilindrico']) && $refracao->refracao['prescricao_oculos']['prescricao_oe_cilindrico'] == ('-'.(string)$val))?'selected' : '' ?>>
                                            -<?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <select name="prescricao_oculos[prescricao_oe_eixo]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 5;
                                    while ($value <= 180):
                                        $val = $value;
                                        $value += 5;
                                        ?>
                                        <option value="<?= $val ?>" <?= (isset($refracao->refracao['prescricao_oculos']['prescricao_oe_eixo']) && $refracao->refracao['prescricao_oculos']['prescricao_oe_eixo'] == $val)? 'selected' : '' ?>>
                                            <?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <select name="prescricao_oculos[prescricao_oe_adicao]" class="form-control selectfild2Refracao" style="width: 100%">
                                    <option value=""></option>
                                    <?php
                                    $value = 0.5;
                                    while ($value <= 4):
                                        $val = number_format($value, 2, ',', '.');
                                        $value += 0.25;
                                        ?>
                                        <option value="+<?= $val ?>"  <?= (isset($refracao->refracao['prescricao_oculos']['prescricao_oe_adicao']) && $refracao->refracao['prescricao_oculos']['prescricao_oe_adicao'] == ('+'.(string)$val))? 'selected' : '' ?>>
                                            +<?= $val ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="form-control-label">DP(mm)</label>
                        <select name="prescricao_oculos[prescricao_dp]" class="form-control selectfild2Refracao" style="width: 100%">
                            <option value=""></option>
                            <?php
                            $value = 48;
                            while ($value <= 80):
                                $val = $value;
                                $value += 1;
                                ?>
                                <option value="<?= $val ?>" <?= (isset($refracao->refracao['prescricao_oculos']['prescricao_dp']) && $refracao->refracao['prescricao_oculos']['prescricao_dp']  == $val)?'selected' : '' ?>>
                                    <?= $val ?>
                                </option>
                                <?php
                            endwhile;
                            ?>
                        </select>
                    </div>
                    <div class="col-md-12 form-group">
                        <label class="form-control-label">Observações</label>
                        <textarea class="form-control" name="prescricao_oculos[prescricao_obs]" id="prescricao_oculos[prescricao_obs]" cols="10" rows="5"><?= (isset($refracao->refracao['prescricao_oculos']['prescricao_obs']))? $refracao->refracao['prescricao_oculos']['prescricao_obs'] : '' ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $(".selectfild2RefracaoPlano").select2()
        $(".selectfild2Refracao").select2()
    })

    $(".copiar_estatico").on('click', function(){
        $('[name="prescricao_oculos[prescricao_od_esferico]"]').val($('[name="refracao_estatica[ref_estatica_l_od_esferico]"]').val()).select2();
        $('[name="prescricao_oculos[prescricao_od_cilindrico]"]').val($('[name="refracao_estatica[ref_estatica_l_od_cilindrico]"]').val()).select2();
        $('[name="prescricao_oculos[prescricao_od_eixo]"]').val($('[name="refracao_estatica[ref_estatica_l_od_eixo]"]').val()).select2();
        $('[name="prescricao_oculos[prescricao_od_adicao]"]').val($('[name="refracao_estatica[ref_estatica_p_od_adicao]"]').val()).select2();

        $('[name="prescricao_oculos[prescricao_oe_esferico]"]').val($('[name="refracao_estatica[ref_estatica_l_oe_esferico]"]').val()).select2();
        $('[name="prescricao_oculos[prescricao_oe_cilindrico]"]').val($('[name="refracao_estatica[ref_estatica_l_oe_cilindrico]"]').val()).select2();
        $('[name="prescricao_oculos[prescricao_oe_eixo]"]').val($('[name="refracao_estatica[ref_estatica_l_oe_eixo]"]').val()).select2();
        $('[name="prescricao_oculos[prescricao_oe_adicao]"]').val($('[name="refracao_estatica[ref_estatica_p_oe_adicao]"]').val()).select2();
    });

    $(".copiar_dinamico").on('click', function () {
        //$('[id^="prescricao_"]').val('').select2();
        $('[name="prescricao_oculos[prescricao_od_esferico]"]').val($('[name="refracao_dinamica[ref_dinamica_l_od_esferico]"]').val()).select2();
        $('[name="prescricao_oculos[prescricao_od_cilindrico]"]').val($('[name="refracao_dinamica[ref_dinamica_l_od_cilindrico]"]').val()).select2();
        $('[name="prescricao_oculos[prescricao_od_eixo]"]').val($('[name="refracao_dinamica[ref_dinamica_l_od_eixo]"]').val()).select2();
        $('[name="prescricao_oculos[prescricao_od_adicao]"]').val($('[name="refracao_dinamica[ref_dinamica_p_od_adicao]"]').val()).select2();

        $('[name="prescricao_oculos[prescricao_oe_esferico]"]').val($('[name="refracao_dinamica[ref_dinamica_l_oe_esferico]"]').val()).select2();
        $('[name="prescricao_oculos[prescricao_oe_cilindrico]"]').val($('[name="refracao_dinamica[ref_dinamica_l_oe_cilindrico]"]').val()).select2();
        $('[name="prescricao_oculos[prescricao_oe_eixo]"]').val($('[name="refracao_dinamica[ref_dinamica_l_oe_eixo]"]').val()).select2();
        $('[name="prescricao_oculos[prescricao_oe_adicao]"]').val($('[name="refracao_dinamica[ref_dinamica_p_oe_adicao]"]').val()).select2();
    })
</script>
