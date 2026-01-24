<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/shops'); ?>
<?php
    if( !isset($_POST['id'])||!$_POST['id'] ){
        Status::error( Lang::get('NotFound').Lang::get('Id').' !!!' );
    }
    // Begin
    $parameters = array();
    $parameters['id'] = $_POST['id'];
    if( Shop::delete("DELETE FROM `shop` WHERE id=:id;", $parameters) ){
        Shop::delete("DELETE FROM `shop_user` WHERE shop_id=:id;", $parameters);
        Status::success( Lang::get('SuccessDelete') );
    }
    Status::error( Lang::get('PleaseTryAgain').' !!!', array('title'=>Lang::get('ErrorDelete')) );
?>