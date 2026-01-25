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
    $owner_name = '';
    $check = Shop::one("SELECT name, surname, surname, TRIM(CONCAT(COALESCE(title,''),name,' ',COALESCE(surname,''))) AS owner_name FROM shop WHERE id=:id LIMIT 1;", $parameters);
    $datas  = '`title`';
    $datas .= "=:title";
    $parameters['title'] = null;
    if(isset($_POST['title'])&&$_POST['title']){
        $parameters['title'] = Helper::stringSave($_POST['title']);
        $owner_name = $parameters['title'];
    }
    $datas .= ',`name`';
    $datas .= "=:name";
    $parameters['name'] = null;
    if(isset($_POST['name'])&&$_POST['name']){
        $parameters['name'] = Helper::stringSave($_POST['name']);
        $owner_name .= $parameters['name'];
    }
    $datas .= ',`surname`';
    $datas .= "=:surname";
    $parameters['surname'] = null;
    if(isset($_POST['surname'])&&$_POST['surname']){
        $parameters['surname'] = Helper::stringSave($_POST['surname']);
        $owner_name .= ' '.$parameters['surname'];
    }
    if( $check['owner_name']==$owner_name ){
        if( $check['title']==$parameters['title'] ){
            Status::error( Lang::get('NotFoundChange').' !!!', array('onfocus'=>"title") );
        }else if( $check['name']==$parameters['name'] ){
            Status::error( Lang::get('NotFoundChange').' !!!', array('onfocus'=>"name") );
        }else if( $check['surname']==$parameters['surname'] ){
            Status::error( Lang::get('NotFoundChange').' !!!', array('onfocus'=>"surname") );
        }
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
        $logs['title'] = "Change owner";
        $logs['value'] = $check['owner_name'];
        $logs['remark'] = $owner_name;
        Log::shop($logs);
        $display = ( ($owner_name!='') ? $owner_name : '-' );
        /*if( User::get()&&User::get('shop_id') ){
            Shop::update("UPDATE `shop` SET $datas WHERE id=:id;", $parameters);
        }*/
        Status::success( Lang::get('SuccessUpdate'), array('display'=>$display) );
    }
    Status::error( Lang::get('ErrorUpdate').', <em>'.Lang::get('PleaseTryAgain').'</em> !!!' );
?>