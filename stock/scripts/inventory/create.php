<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/stock'); ?>
<?php
    if( !isset($_POST['form_as'])||!$_POST['form_as'] ){
        Status::error( Lang::get('NotFound').Lang::get('Data').' !!!' );
    }
    // Begin
    $parameters = array();
    $fields = "`id`";
    $values = ":id";
    $parameters['id'] = Stock::generateId();
    $fields .= ',`type_id`';
    $values .= ",:type_id";
    $parameters['type_id'] = "OTHER";
    $fields .= ',`sequence`';
    $values .= ",:sequence";
    $checkseq = Stock::one("SELECT sequence FROM stock ORDER BY sequence DESC;");
    $parameters['sequence'] = ( (isset($checkseq['sequence'])&&$checkseq['sequence']) ? (intval($checkseq['sequence'])+1) : 1 );
    $fields .= ',`name`';
    $values .= ",:name";
    $parameters['name'] = Lang::get('List').' '.$parameters['sequence'];
    $fields .= ',`unit`';
    $values .= ",:unit";
    $parameters['unit'] = "ชิ้น";
    $fields .= ',`date_create`';
    $values .= ",NOW()";
    $fields .= ',`user_create`';
    $values .= ",:user_create";
    $parameters['user_create'] = User::get('email');
    if( Stock::create("INSERT INTO `stock` ($fields) VALUES ($values)", $parameters) ){
        $htmls = '<form name="saving" class="form-manage AT-'.$parameters['id'].'" action="'.$_POST['form_as'].'/scripts/inventory/update.php" method="POST" enctype="multipart/form-data" target="_blank">';
            $htmls .= '<input type="hidden" name="id" value="'.$parameters['id'].'"/>';
            $htmls .= '<div class="card mb-2 lift">';
                $htmls .= '<div class="card-body p-1">';
                    $htmls .= '<div class="row gx-1">';
                        $htmls .= '<div class="col-2 col-type">';
                            $htmls .= '<div class="seq-box">';
                                $htmls .= '<div>'.Lang::get('NO.').'</div><span class="num"></span>';
                            $htmls .= '</div>';
                            $htmls .= '<div class="form-floating form-select-wrapper mb-0">';
                                $htmls .= '<select name="type_id" class="form-select" onchange="record_events(\'update\', { \'id\':\''.$parameters['id'].'\' });">';
                                    $htmls .= Stock::typeOption(( (isset($parameters['type_id'])&&$parameters['type_id']) ? $parameters['type_id'] : null ));
                                $htmls .= '</select>';
                                $htmls .= '<label for="type">'.Lang::get('Type').'</label>';
                            $htmls .= '</div>';
                        $htmls .= '</div>';
                        $htmls .= '<div class="col-3 col-name">';
                            $htmls .= '<div class="form-floating mb-0">';
                                $htmls .= '<input name="name" value="'.$parameters['name'].'" type="text" class="form-control" placeholder="..." onchange="record_events(\'update\', { \'id\':\''.$parameters['id'].'\' });">';
                                $htmls .= '<label for="name">'.Lang::get('Name').' <span class="text-red">*</span></label>';
                            $htmls .= '</div>';
                        $htmls .= '</div>';
                        $htmls .= '<div class="col-2 col-unit">';
                            $htmls .= '<div class="form-floating mb-0">';
                                $htmls .= '<input name="unit" value="'.$parameters['unit'].'" type="text" class="form-control" placeholder="..." onchange="record_events(\'update\', { \'id\':\''.$parameters['id'].'\' });">';
                                $htmls .= '<label for="unit">'.Lang::get('Unit').'</label>';
                            $htmls .= '</div>';
                        $htmls .= '</div>';
                        $htmls .= '<div class="col-2 col-charge">';
                            $htmls .= '<div class="form-floating mb-0">';
                                $htmls .= '<input name="charge" value="" type="text" class="form-control" placeholder="..." onchange="record_events(\'update\', { \'id\':\''.$parameters['id'].'\' });">';
                                $htmls .= '<label for="charge">'.Lang::get('Charge').' <sup>('.Lang::get('Baht').')</sup></label>';
                            $htmls .= '</div>';
                        $htmls .= '</div>';
                        $htmls .= '<div class="col-1 col-btns text-center toggle">';
                            $htmls .= '<i class="uil uil-toggle-off" onclick="record_events(\'status\', { \'id\':\''.$parameters['id'].'\', \'self\':this });"></i>';
                            $htmls .= '<div class="toggle-label">OFF/ON</div>';
                        $htmls .= '</div>';
                        $htmls .= '<div class="col-1 col-btns text-center save">';
                            $htmls .= '<button type="submit" class="btn btn-icon btn-outline-success w-100"><i class="uil uil-save"></i></button>';
                        $htmls .= '</div>';
                        $htmls .= '<div class="col-1 col-btns text-center dels">';
                            $htmls .= '<button type="button" class="btn btn-icon btn-outline-danger w-100" onclick="record_events(\'delete\', { \'id\':\''.$parameters['id'].'\' });"><i class="uil uil-trash"></i></button>';
                        $htmls .= '</div>';
                    $htmls .= '</div>';
                $htmls .= '</div>';
            $htmls .= '</div>';
        $htmls .= '</form>';
        Status::success( Lang::get('SuccessCreate'), array('id'=>$parameters['id'], 'htmls'=>$htmls) );
    }
    Status::error( Lang::get('ErrorAdd').', <em>'.Lang::get('PleaseTryAgain').'</em> !!!' );
?>