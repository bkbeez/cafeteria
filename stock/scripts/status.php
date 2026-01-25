<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/stock'); ?>
<?php
    if(!isset($_POST['id'])||!$_POST['id']){
        Status::error( Lang::get('NotFound').Lang::get('Id').' !!!' );
    }else if( !isset($_POST['status'])||!$_POST['status'] ){
        Status::error( Lang::get('NotFound').Lang::get('Status').' !!!' );
    }
    // Begin
    $parameters = array();
    $parameters['id'] = $_POST['id'];
    $datas  = '`order_as`';
    $datas .= "=:order_as";
    $parameters['order_as'] = ( (isset($_POST['status'])&&$_POST['status']) ? $_POST['status'] : 'N' );
    $datas .= ',`date_update`';
    $datas .= "=NOW()";
    if( Stock::update("UPDATE `stock` SET $datas WHERE id=:id;", $parameters) ){
        Status::success( Lang::get('SuccessChange') );
    }
    Status::error( Lang::get('ErrorChange').', <em>'.Lang::get('PleaseTryAgain').'</em> !!!' );
?>