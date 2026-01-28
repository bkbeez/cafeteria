<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/stock'); ?>
<?php
    if(!isset($_POST['id'])||!$_POST['id']){
        Status::error( Lang::get('NotFound').Lang::get('Id').' !!!' );
    }else if( !isset($_POST['type_id'])||!$_POST['type_id'] ){
        Status::error( Lang::get('Require').' !!!', array('onfocus'=>"type_id", 'id'=>$_POST['id']) );
    }else if( !isset($_POST['name'])||!$_POST['name'] ){
        Status::error( Lang::get('Require').' !!!', array('onfocus'=>"name", 'id'=>$_POST['id']) );
    }
    // Begin
    $parameters = array();
    $parameters['id'] = $_POST['id'];
    if( isset($_POST['restore'])&&$_POST['restore']=='Y' ){
        if( Stock::update("UPDATE `stock` SET `date_delete`=NULL, `user_delete`=NULL WHERE id=:id;", $parameters) ){
            $htmls  = '<i class="uil uil-toggle-off" onclick="record_events(\'status\', { \'id\':\''.$parameters['id'].'\', \'self\':this });"></i>';
            $htmls .= '<div class="toggle-label">OFF/ON</div>';
            Status::success( Lang::get('SuccessRestore'), array('restore'=>"Y", 'id'=>$parameters['id'], 'htmls'=>$htmls) );
        }
    }else if(isset($_POST['delete'])&&$_POST['delete']=='Y'){
        $check = Request::one("SELECT id FROM request_list WHERE stock_id=:id ORDER BY date_create LIMIT 1;", $parameters);
        if( isset($check['id'])&&$check['id'] ){
            Status::error( Lang::get('ErrorDelete').', <em>'.( (App::lang()=='en') ? 'it was used.' : 'ถูกใช้งานแล้ว' ).'</em> !!!', array('id'=>$_POST['id']) );
        }
        if( Auth::admin() ){
            if( Stock::delete("DELETE FROM `stock` WHERE id=:id;", $parameters) ){
                Status::success( Lang::get('SuccessDelete'), array('delete'=>"Y", 'id'=>$parameters['id']) );
            }
        }else{
            $parameters['user_by'] = User::get('email');
            if( Stock::update("UPDATE `stock` SET `status_id`=0, `date_delete`=NOW(), `user_delete`=:user_by WHERE id=:id;", $parameters) ){
                Status::success( Lang::get('SuccessDelete'), array('delete'=>"Y", 'id'=>$parameters['id']) );
            }
        }
        Status::error( Lang::get('ErrorDelete').', <em>'.Lang::get('PleaseTryAgain').'</em> !!!', array('id'=>$_POST['id']) );
    }else{
        $datas  = '`type_id`';
        $datas .= "=:type_id";
        $parameters['type_id'] = $_POST['type_id'];
        $datas .= ',`name`';
        $datas .= "=:name";
        $parameters['name'] = Helper::stringSave($_POST['name']);   
        $datas .= ',`unit`';
        $datas .= "=:unit";
        $parameters['unit'] = ( (isset($_POST['unit'])&&$_POST['unit']) ? Helper::stringSave($_POST['unit']) : null );
        $datas .= ',`charge`';
        $datas .= "=:charge";
        $parameters['charge'] = ( (isset($_POST['charge'])&&$_POST['charge']) ? Helper::decimalSave($_POST['charge']) : 0 );
        $datas .= ',`date_update`';
        $datas .= "=NOW()";
        $datas .= ',`user_update`';
        $datas .= "=:user_update";
        $parameters['user_update'] = User::get('email');
        if( Stock::update("UPDATE `stock` SET $datas WHERE id=:id;", $parameters) ){
            Status::success( Lang::get('SuccessUpdate'), array('id'=>$parameters['id']) );
        }
        Status::error( Lang::get('ErrorUpdate').', <em>'.Lang::get('PleaseTryAgain').'</em> !!!', array('id'=>$_POST['id']) );
    }
    Status::error( Lang::get('PleaseTryAgain').' !!!', array('id'=>$_POST['id']) );
?>