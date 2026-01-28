<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/request'); ?>
<?php
    if( !isset($_POST['shop_id'])||!$_POST['shop_id'] ){
        Status::error( Lang::get('PleaseCheckAgain').' !!!', array('title'=>Lang::get('NotFound').Lang::get('Shop')) );
    }else if( !isset($_POST['list'])||count($_POST['list'])<=0 ){
        Status::error( Lang::get('PleaseCheckAgain').' !!!', array('title'=>Lang::get('NotFound').Lang::get('List')) );
    }
    // Begin
    $today = new datetime();
    $parameters = array();
    $fields = "`id`";
    $values = ":id";
    $parameters['id'] = $_POST['shop_id'].'-'.$today->format("YmdHis");
    $fields .= ',`shop_id`';
    $values .= ",:shop_id";
    $parameters['shop_id'] = $_POST['shop_id'];
    $fields .= ',`shop_name`';
    $values .= ",:shop_name";
    $parameters['shop_name'] = Shop::getName($_POST['shop_id']);
    $fields .= ',`amount`';
    $values .= ",:amount";
    $parameters['amount'] = 0;
    $fields .= ',`requester`';
    $values .= ",:requester";
    $parameters['requester'] = User::get('fullname');
    $fields .= ',`request_date`';
    $values .= ",:request_date";
    $parameters['request_date'] = $today->format("Y-m-d H-i-s");
    $fields .= ',`request_by`';
    $values .= ",:request_by";
    $parameters['request_by'] = ( (isset($_POST['request_by'])&&$_POST['request_by']) ? $_POST['request_by'] : 'SHOP' );
    $fields .= ',`status_id`';
    $values .= ",1";
    $fields .= ',`date_create`';
    $values .= ",NOW()";
    $fields .= ',`user_create`';
    $values .= ",:user_by";
    $parameters['user_by'] = User::get('email');
    $quantity = 0;
    $list = "INSERT INTO `request_list` (`id`,`request_id`,`stock_id`,`name`,`quantity`,`unit`,`price`,`amount`,`date_create`,`user_create`) VALUES";
    $lists = array();
    $lists['request_id'] = $parameters['id'];
    $lists['user_by'] = $parameters['user_by'];
    foreach($_POST['list'] as $seq => $item){
        if( $seq>0 ) { $list .= ","; }
        $list .= " (:id_".$seq.",:request_id,:stock_id_".$seq.",:name_".$seq.",:quantity_".$seq.",:unit_".$seq.",:price_".$seq.",:amount_".$seq.",NOW(),:user_by)";
        $lists['id_'.$seq] = $parameters['id'].'-'.sprintf("%1$02d", ($seq+1));
        $lists['stock_id_'.$seq] = $item['stock_id'];
        $lists['name_'.$seq] = $item['name'];
        $lists['quantity_'.$seq] = intval($item['quantity']);
        $lists['unit_'.$seq] = $item['unit'];
        $lists['price_'.$seq] = doubleval($item['price']);
        $lists['amount_'.$seq] = doubleval($item['amount']);
        $parameters['amount'] += $lists['amount_'.$seq];
        $quantity += $lists['quantity_'.$seq];
    }
    if( $quantity<=0 ){
        Status::error( ((App::lang()=='en')?'Request quantity more than 1 items':'จำนวนเบิกต้องมีอย่างน้อย 1 รายการ').' !!!', array('title'=>((App::lang()=='en')?'Please fill quantity':'โปรดระบุจำนวนเบิก')) );
    }
    if( Request::create("INSERT INTO `request` ($fields) VALUES ($values)", $parameters) ){
        if( Request::create($list, $lists) ){
            Status::success( ( (App::lang()=='en') ? 'Requisition form was saved.' : 'รายการเบิกภาชนะของท่านถูกบันทึกแล้ว' ), array('id'=>$parameters['id']) );
        }else{
            Request::delete("DELETE FROM `request` WHERE id=:id;", array('id'=>$parameters['id']));
        }
    }
    Status::error( Lang::get('PleaseTryAgain').' !!!', array('title'=>Lang::get('ErrorCreate')) );
?>