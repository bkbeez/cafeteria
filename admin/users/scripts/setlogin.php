<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/admin'); ?>
<?php
    if(!isset($_POST['id'])||!$_POST['id']){
        Status::error( Lang::get('NotFound').Lang::get('Id').' !!!' );
    }else if( !isset($_POST['is_pass'])||!$_POST['is_pass'] ){
        Status::error( Lang::get('Require').' !!!' );
    }else if( $_POST['is_pass']=='Y'&&(!isset($_POST['password_default'])||!$_POST['password_default']) ){
        Status::error( Lang::get('Require').' !!!', array('onfocus'=>"password_default") );
    }
    // Begin
    $parameters = array();
    $parameters['id'] = $_POST['id'];
    $parameters['email'] = $_POST['email'];
    $datas  = '`is_pass`';
    $datas .= "=:is_pass";
    $parameters['is_pass'] = $_POST['is_pass'];
    if( $parameters['is_pass']=='A' ){
        Status::success( Lang::get('SuccessUpdate') );
    }else{
        $datas .= ',`password`';
        $datas .= "=:password";
        $parameters['password'] = null;
        $datas .= ',`password_default`';
        $datas .= "=:password_default";
        $parameters['password_default'] = null;
        if( $parameters['is_pass']=='Y' ){
            $parameters['password_default'] = Helper::stringSave($_POST['password_default']);
            $parameters['password'] = password_hash($parameters['password_default'], PASSWORD_BCRYPT);
        }
        $datas .= ',`date_update`';
        $datas .= "=NOW()";
        $datas .= ',`user_update`';
        $datas .= "=:user_update";
        $parameters['user_update'] = User::get('email');
        if( User::update("UPDATE `member` SET $datas WHERE id=:id AND email=:email;", $parameters) ){
            Status::success( Lang::get('SuccessUpdate') );
        }
    }
    Status::error( Lang::get('ErrorUpdate').', <em>'.Lang::get('PleaseTryAgain').'</em> !!!' );
?>