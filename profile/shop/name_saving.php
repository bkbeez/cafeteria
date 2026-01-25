<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/profile'); ?>
<?php
    if(!isset($_POST['id'])||!$_POST['id']){
        Status::error( Lang::get('NotFound').Lang::get('Id').' !!!' );
    }else if( !isset($_POST['shop_name'])||!$_POST['shop_name'] ){
        Status::error( Lang::get('Require').' !!!', array('onfocus'=>"shop_name") );
    }
    // Begin
    $parameters = array();
    $parameters['id'] = $_POST['id'];
    // Check
    $check = Shop::one("SELECT * FROM shop WHERE id=:id LIMIT 1;", $parameters);
    $datas  = '`shop_name`';
    $datas .= "=:shop_name";
    $parameters['shop_name'] = Helper::stringSave($_POST['shop_name']);
    if( $check['shop_name']==$parameters['shop_name'] ){
        Status::error( Lang::get('NotFoundChange').' !!!', array('onfocus'=>"shop_name") );
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
        $logs['title'] = "Change name";
        $logs['value'] = $check['shop_name'];
        $logs['remark'] = $parameters['shop_name'];
        Log::shop($logs);
        $display = ( $parameters['shop_name'] ? $parameters['shop_name'] : '-' );
        Status::success( Lang::get('SuccessUpdate'), array('display'=>$display) );
    }
    Status::error( Lang::get('ErrorUpdate').', <em>'.Lang::get('PleaseTryAgain').'</em> !!!' );
?>