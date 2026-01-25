<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/stock'); ?>
<?php
    if(!isset($_POST['id'])||!$_POST['id']){
        Status::error( Lang::get('NotFound').Lang::get('Id').' !!!' );
    }
    // Begin
    $parameters = array();
    $parameters['id'] = $_POST['id'];
    $datas  = '`charge`';
    $datas .= "=:charge";
    $parameters['charge'] = ( (isset($_POST['charge'])&&$_POST['charge']) ? Helper::decimalSave($_POST['charge']) : 0 );
    $datas .= ',`total`';
    $datas .= "=:total";
    $parameters['total'] = ( (isset($_POST['total'])&&$_POST['total']) ? Helper::numberSave($_POST['total']) : 0 );
    $datas .= ',`expose`';
    $datas .= "=:expose";
    $parameters['expose'] = ( (isset($_POST['expose'])&&$_POST['expose']) ? Helper::numberSave($_POST['expose']) : 0 );
    $datas .= ',`date_change`';
    $datas .= "=NOW()";
    $datas .= ',`user_change`';
    $datas .= "=:user_change";
    $parameters['user_change'] = User::get('email');
    if( Stock::update("UPDATE `stock` SET $datas WHERE id=:id;", $parameters) ){
        Status::success( Lang::get('SuccessChange'), array('id'=>$parameters['id']) );
    }
    Status::error( Lang::get('ErrorChange').', <em>'.Lang::get('PleaseTryAgain').'</em> !!!', array('id'=>$_POST['id']) );
?>