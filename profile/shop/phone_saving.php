<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/profile'); ?>
<?php
    if(!isset($_POST['id'])||!$_POST['id']){
        Status::error( Lang::get('NotFound').Lang::get('Id').' !!!' );
    }else if( !isset($_POST['phone'])||!$_POST['phone'] ){
        Status::error( Lang::get('Require').' !!!', array('onfocus'=>"phone") );
    }
    // Begin
    $parameters = array();
    $parameters['id'] = $_POST['id'];
    // Check
    $check = Shop::one("SELECT * FROM shop WHERE id=:id LIMIT 1;", $parameters);
    $datas  = '`phone`';
    $datas .= "=:phone";
    $parameters['phone'] = Helper::stringSave($_POST['phone']);
    if( $check['phone']==$parameters['phone'] ){
        Status::error( Lang::get('NotFoundChange').' !!!', array('onfocus'=>"phone") );
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
        $logs['title'] = "Change phone";
        $logs['value'] = $check['phone'];
        $logs['remark'] = $parameters['phone'];
        Log::shop($logs);
        $display = ( $parameters['phone'] ? $parameters['phone'] : '-' );
        Status::success( Lang::get('SuccessUpdate'), array('display'=>$display) );
    }
    Status::error( Lang::get('ErrorUpdate').', <em>'.Lang::get('PleaseTryAgain').'</em> !!!' );
?>