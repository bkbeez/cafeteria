<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/profile'); ?>
<?php
    if(!isset($_POST['id'])||!$_POST['id']){
        Status::error( Lang::get('NotFound').Lang::get('Id').' !!!' );
    }else if( !isset($_POST['tax_number'])||!$_POST['tax_number'] ){
        Status::error( Lang::get('Require').' !!!', array('onfocus'=>"tax_number") );
    }
    // Begin
    $parameters = array();
    $parameters['id'] = $_POST['id'];
    // Check
    $check = Shop::one("SELECT * FROM shop WHERE id=:id LIMIT 1;", $parameters);
    $datas  = '`tax_number`';
    $datas .= "=:tax_number";
    $parameters['tax_number'] = Helper::stringSave($_POST['tax_number']);
    if( $check['tax_number']==$parameters['tax_number'] ){
        Status::error( Lang::get('NotFoundChange').' !!!', array('onfocus'=>"tax_number") );
    }
    $datas .= ',`date_update`';
    $datas .= "=NOW()";
    $datas .= ',`user_update`';
    $datas .= "=:user_update";
    $parameters['user_update'] = User::get('email');
    if( Shop::update("UPDATE `shop` SET $datas WHERE id=:id;", $parameters) ){
        $logs = array();
        $logs['shop_id'] = $parameters['id'];
        $logs['mode'] = "CHANGE";
        $logs['title'] = "Change tax number";
        $logs['value'] = $check['tax_number'];
        $logs['remark'] = $parameters['tax_number'];
        Log::shop($logs);
        $display = ( $parameters['tax_number'] ? $parameters['tax_number'] : '-' );
        Status::success( Lang::get('SuccessUpdate'), array('display'=>$display) );
    }
    Status::error( Lang::get('ErrorUpdate').', <em>'.Lang::get('PleaseTryAgain').'</em> !!!' );
?>