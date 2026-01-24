<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/shops'); ?>
<?php
    if( !isset($_POST['type_id'])||!$_POST['type_id'] ){
        Status::error( Lang::get('Require').' !!!', array('onfocus'=>"type_id") );
    }else if( !isset($_POST['shop_name'])||!$_POST['shop_name'] ){
        Status::error( Lang::get('Require').' !!!', array('onfocus'=>"shop_name") );
    }else if( !isset($_POST['name'])||!$_POST['name'] ){
        Status::error( Lang::get('Require').' !!!', array('onfocus'=>"name") );
    }
    // Begin
    $parameters = array();
    $fields = "`id`";
    $values = ":id";
    $parameters['id'] = Shop::generateId();
    $fields .= ',`type_id`';
    $values .= ",:type_id";
    $parameters['type_id'] = $_POST['type_id'];
    $fields .= ',`shop_name`';
    $values .= ",:shop_name";
    $parameters['shop_name'] = Helper::stringSave($_POST['shop_name']);
    $fields .= ',`title`';
    $values .= ",:title";
    $parameters['title'] = ( (isset($_POST['title'])&&$_POST['title']) ? Helper::stringSave($_POST['title']) : null );
    $fields .= ',`name`';
    $values .= ",:name";
    $parameters['name'] = ( (isset($_POST['name'])&&$_POST['name']) ? Helper::stringSave($_POST['name']) : null );
    $fields .= ',`surname`';
    $values .= ",:surname";
    $parameters['surname'] = ( (isset($_POST['surname'])&&$_POST['surname']) ? Helper::stringSave($_POST['surname']) : null );
    $fields .= ',`phone`';
    $values .= ",:phone";
    $parameters['phone'] = ( (isset($_POST['phone'])&&$_POST['phone']) ? Helper::stringSave($_POST['phone']) : null );
    $fields .= ',`status_id`';
    $values .= ",:status_id";
    $parameters['status_id'] = ( (isset($_POST['status_id'])&&$_POST['status_id']) ? $_POST['status_id'] : 0 );
    $fields .= ',`date_create`';
    $values .= ",NOW()";
    $fields .= ',`user_create`';
    $values .= ",:user_create";
    $parameters['user_create'] = User::get('email');
    if( Shop::create("INSERT INTO `shop` ($fields) VALUES ($values)", $parameters) ){
        Status::success( Lang::get('SuccessCreate') );
    }
    Status::error( Lang::get('ErrorAdd').', <em>'.Lang::get('PleaseTryAgain').'</em> !!!' );
?>