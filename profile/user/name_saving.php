<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/profile'); ?>
<?php
    if(!isset($_POST['id'])||!$_POST['id']){
        Status::error( Lang::get('NotFound').Lang::get('Id').' !!!' );
    }else if( !isset($_POST['name'])||!$_POST['name'] ){
        Status::error( Lang::get('Require').' !!!', array('onfocus'=>"name") );
    }
    // Begin
    $parameters = array();
    $parameters['id'] = $_POST['id'];
    // Check
    $user_name = '';
    $check = User::one("SELECT name, surname, surname, TRIM(CONCAT(COALESCE(title,''),name,' ',COALESCE(surname,''))) AS user_name FROM member WHERE id=:id LIMIT 1;", $parameters);
    $datas  = '`title`';
    $datas .= "=:title";
    $parameters['title'] = null;
    if(isset($_POST['title'])&&$_POST['title']){
        $parameters['title'] = Helper::stringSave($_POST['title']);
        $user_name = $parameters['title'];
    }
    $datas .= ',`name`';
    $datas .= "=:name";
    $parameters['name'] = null;
    if(isset($_POST['name'])&&$_POST['name']){
        $parameters['name'] = Helper::stringSave($_POST['name']);
        $user_name .= $parameters['name'];
    }
    $datas .= ',`surname`';
    $datas .= "=:surname";
    $parameters['surname'] = null;
    if(isset($_POST['surname'])&&$_POST['surname']){
        $parameters['surname'] = Helper::stringSave($_POST['surname']);
        $user_name .= ' '.$parameters['surname'];
    }
    if( $check['user_name']==$user_name ){
        Status::error( Lang::get('NotFoundChange').' !!!', array('onfocus'=>"name") );
    }
    $datas .= ',`date_update`';
    $datas .= "=NOW()";
    $datas .= ',`user_update`';
    $datas .= "=:user_update";
    $parameters['user_update'] = User::get('email');
    if( User::update("UPDATE `member` SET $datas WHERE id=:id;", $parameters) ){
        $logs = array();
        $logs['member_id'] = $parameters['id'];
        $logs['mode'] = "CHANGE";
        $logs['title'] = "Change name";
        $logs['value'] = $check['user_name'];
        $logs['remark'] = $user_name;
        Log::member($logs);
        $_SESSION['login']['user']['title'] = $parameters['title'];
        $_SESSION['login']['user']['name'] = $parameters['name'];
        $_SESSION['login']['user']['surname'] = $parameters['surname'];
        $_SESSION['login']['user']['fullname'] = $user_name;
        $display = ( ($user_name!='') ? $user_name : '-' );
        Status::success( Lang::get('SuccessUpdate'), array('display'=>$display) );
    }
    Status::error( Lang::get('ErrorUpdate').', <em>'.Lang::get('PleaseTryAgain').'</em> !!!' );
?>