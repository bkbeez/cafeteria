<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/shops'); ?>
<?php
    if(!isset($_POST['id'])||!$_POST['id']){
        Status::error( Lang::get('NotFound').Lang::get('Id').' !!!' );
    }else if( !isset($_POST['type_id'])||!$_POST['type_id'] ){
        Status::error( Lang::get('Require').' !!!', array('onfocus'=>"type_id") );
    }else if( !isset($_POST['shop_name'])||!$_POST['shop_name'] ){
        Status::error( Lang::get('Require').' !!!', array('onfocus'=>"shop_name") );
    }else if( !isset($_POST['name'])||!$_POST['name'] ){
        Status::error( Lang::get('Require').' !!!', array('onfocus'=>"name") );
    }
    // Begin
    $parameters = array();
    $parameters['id'] = $_POST['id'];
    $datas  = '`type_id`';
    $datas .= "=:type_id";
    $parameters['type_id'] = $_POST['type_id'];
    $datas .= ',`shop_name`';
    $datas .= "=:shop_name";
    $parameters['shop_name'] = Helper::stringSave($_POST['shop_name']);   
    $datas .= ',`title`';
    $datas .= "=:title";
    $parameters['title'] = ( (isset($_POST['title'])&&$_POST['title']) ? Helper::stringSave($_POST['title']) : null );
    $datas .= ',`name`';
    $datas .= "=:name";
    $parameters['name'] = ( (isset($_POST['name'])&&$_POST['name']) ? Helper::stringSave($_POST['name']) : null );
    $datas .= ',`surname`';
    $datas .= "=:surname";
    $parameters['surname'] = ( (isset($_POST['surname'])&&$_POST['surname']) ? Helper::stringSave($_POST['surname']) : null );
    $datas .= ',`phone`';
    $datas .= "=:phone";
    $parameters['phone'] = ( (isset($_POST['phone'])&&$_POST['phone']) ? Helper::stringSave($_POST['phone']) : null );
    $datas .= ',`status_id`';
    $datas .= "=:status_id";
    $parameters['status_id'] = ( (isset($_POST['status_id'])&&$_POST['status_id']) ? $_POST['status_id'] : 0 );
    $datas .= ',`date_update`';
    $datas .= "=NOW()";
    $datas .= ',`user_update`';
    $datas .= "=:user_update";
    $parameters['user_update'] = User::get('email');
    if( Shop::update("UPDATE `shop` SET $datas WHERE id=:id;", $parameters) ){
        Status::success( Lang::get('SuccessUpdate') );
    }
    Status::error( Lang::get('ErrorUpdate').', <em>'.Lang::get('PleaseTryAgain').'</em> !!!' );
?>