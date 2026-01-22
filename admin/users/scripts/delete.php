<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/admin'); ?>
<?php
    if( !isset($_POST['id'])||!$_POST['id'] ){
        Status::error( Lang::get('NotFound').Lang::get('Id').' !!!' );
    }else if( !isset($_POST['email'])||!$_POST['email'] ){
        Status::error( Lang::get('NotFound').Lang::get('Email').' !!!' );
    }
    // Begin
    $parameters = array();
    $parameters['id'] = $_POST['id'];
    $parameters['email'] = $_POST['email'];
    if( User::delete("DELETE FROM `member` WHERE id=:id AND email=:email;", $parameters) ){
        $logs = array();
        $logs['member_id'] = $parameters['id'];
        $logs['mode'] = "DELETE";
        $logs['title'] = "Delete user";
        $logs['remark'] = $parameters['email'];
        //User::log($logs);
        Status::success( Lang::get('SuccessDelete') );
    }
    Status::error( Lang::get('PleaseTryAgain').' !!!', array('title'=>Lang::get('ErrorDelete')) );
?>