<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/shops'); ?>
<?php
    if(!isset($_POST['id'])||!$_POST['id']){
        Status::error( Lang::get('NotFound').Lang::get('Id').' !!!' );
    }else if( !isset($_POST['tax_number'])||!$_POST['tax_number'] ){
        Status::error( Lang::get('Require').' !!!', array('onfocus'=>"tax_number") );
    }else if( !isset($_POST['address'])||!$_POST['address'] ){
        Status::error( Lang::get('Require').' !!!', array('onfocus'=>"address") );
    }else if( !isset($_POST['province'])||!$_POST['province'] ){
        Status::error( Lang::get('Require').' !!!', array('onfocus'=>"province") );
    }
    // Begin
    $parameters = array();
    $parameters['id'] = $_POST['id'];
    $datas  = '`tax_number`';
    $datas .= "=:tax_number";
    $parameters['tax_number'] = Helper::stringSave($_POST['tax_number']);
    $datas .= ',`address`';
    $datas .= "=:address";
    $parameters['address'] = Helper::stringSave($_POST['address']);   
    $datas .= ',`province`';
    $datas .= "=:province";
    $parameters['province'] = Helper::stringSave($_POST['province']);
    $datas .= ',`zipcode`';
    $datas .= "=:zipcode";
    $parameters['zipcode'] = ( (isset($_POST['zipcode'])&&$_POST['zipcode']) ? Helper::stringSave($_POST['zipcode']) : null );
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