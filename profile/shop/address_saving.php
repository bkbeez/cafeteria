<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/profile'); ?>
<?php
    if(!isset($_POST['id'])||!$_POST['id']){
        Status::error( Lang::get('NotFound').Lang::get('Id').' !!!' );
    }else if( !isset($_POST['address'])||!$_POST['address'] ){
        Status::error( Lang::get('Require').' !!!', array('onfocus'=>"address") );
    }else if( !isset($_POST['province'])||!$_POST['province'] ){
        Status::error( Lang::get('Require').' !!!', array('onfocus'=>"province") );
    }else if( !isset($_POST['zipcode'])||!$_POST['zipcode'] ){
        Status::error( Lang::get('Require').' !!!', array('onfocus'=>"zipcode") );
    }
    // Begin
    $parameters = array();
    $parameters['id'] = $_POST['id'];
    // Check
    $fulladdress = '';
    $check = Shop::one("SELECT address, province, zipcode, IF(address IS NOT NULL, TRIM(CONCAT(address,' ',COALESCE(province,''),' ',COALESCE(zipcode,''))),NULL) AS fulladdress FROM shop WHERE id=:id LIMIT 1;", $parameters);
    $datas  = '`address`';
    $datas .= "=:address";
    $parameters['address'] = null;
    if(isset($_POST['address'])&&$_POST['address']){
        $parameters['address'] = Helper::stringSave($_POST['address']);
        $fulladdress = $parameters['address'];
    }
    $datas .= ',`province`';
    $datas .= "=:province";
    $parameters['province'] = null;
    if(isset($_POST['province'])&&$_POST['province']){
        $parameters['province'] = Helper::stringSave($_POST['province']);
        $fulladdress .= ' '.$parameters['province'];
    }
    $datas .= ',`zipcode`';
    $datas .= "=:zipcode";
    $parameters['zipcode'] = null;
    if(isset($_POST['zipcode'])&&$_POST['zipcode']){
        $parameters['zipcode'] = Helper::stringSave($_POST['zipcode']);
        $fulladdress .= ' '.$parameters['zipcode'];
    }
    if( $check['fulladdress']==$fulladdress ){
        if( $check['address']==$parameters['address'] ){
            Status::error( Lang::get('NotFoundChange').' !!!', array('onfocus'=>"address") );
        }else if( $check['province']==$parameters['province'] ){
            Status::error( Lang::get('NotFoundChange').' !!!', array('onfocus'=>"province") );
        }else if( $check['zipcode']==$parameters['zipcode'] ){
            Status::error( Lang::get('NotFoundChange').' !!!', array('onfocus'=>"zipcode") );
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
        $logs['title'] = "Change address";
        $logs['value'] = $check['fulladdress'];
        $logs['remark'] = $fulladdress;
        Log::shop($logs);
        $display = ( ($fulladdress!='') ? $fulladdress : '-' );
        Status::success( Lang::get('SuccessUpdate'), array('display'=>$display) );
    }
    Status::error( Lang::get('ErrorUpdate').', <em>'.Lang::get('PleaseTryAgain').'</em> !!!' );
?>