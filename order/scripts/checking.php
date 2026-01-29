<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/order'); ?>
<?php
    if( !isset($_POST['request_id'])||!$_POST['request_id'] ){
        Status::error( Lang::get('PleaseCheckAgain').' !!!', array('title'=>Lang::get('NotFound').Lang::get('Id')) );
    }
    if( isset($_POST['status'])&&$_POST['status']=='Y' ){
        if( !isset($_POST['list'])||count($_POST['list'])<=0 ){
            Status::error( Lang::get('PleaseCheckAgain').' !!!', array('title'=>Lang::get('NotFound').Lang::get('List')) );
        }
        $requests = array();
        $requests['id'] = $_POST['request_id'];
        $check = Request::one("SELECT * FROM request WHERE id=:id LIMIT 1;", $requests);
        if( isset($check['status_id'])&&$check['status_id']==1 ){
            $today = new datetime();
            $parameters = array();
            $fields = "`id`";
            $values = ":id";
            $parameters['id'] = $check['shop_id'].'-'.$today->format("YmdHis");
            $fields .= ',`request_id`';
            $values .= ",:request_id";
            $parameters['request_id'] = $check['id'];
            $fields .= ',`shop_id`';
            $values .= ",:shop_id";
            $parameters['shop_id'] = $check['shop_id'];
            $fields .= ',`shop_name`';
            $values .= ",:shop_name";
            $parameters['shop_name'] = $check['shop_name'];
            $fields .= ',`amount`';
            $values .= ",:amount";
            $parameters['amount'] = 0;
            $fields .= ',`orderer`';
            $values .= ",:orderer";
            $parameters['orderer'] = User::get('fullname');
            $fields .= ',`order_date`';
            $values .= ",:order_date";
            $parameters['order_date'] = $today->format("Y-m-d H-i-s");
            $fields .= ',`status_id`';
            $values .= ",1";
            $fields .= ',`date_create`';
            $values .= ",NOW()";
            $fields .= ',`user_create`';
            $values .= ",:user_by";
            $parameters['user_by'] = User::get('email');
            $quantity = 0;
            $list = "INSERT INTO `order_list` (`id`,`order_id`,`request_list_id`,`stock_id`,`name`,`request`,`quantity`,`unit`,`price`,`amount`,`date_create`,`user_create`) VALUES";
            $lists = array();
            $lists['order_id'] = $parameters['id'];
            $lists['user_by'] = $parameters['user_by'];
            foreach($_POST['list'] as $seq => $item){
                if( $seq>0 ) { $list .= ","; }
                $list .= " (:id_".$seq.",:order_id,:request_list_id_".$seq.",:stock_id_".$seq.",:name_".$seq.",:request_".$seq.",:quantity_".$seq.",:unit_".$seq.",:price_".$seq.",:amount_".$seq.",NOW(),:user_by)";
                $lists['id_'.$seq] = $parameters['id'].'-'.sprintf("%1$02d", ($seq+1));
                $lists['request_list_id_'.$seq] = $item['request_list_id'];
                $lists['stock_id_'.$seq] = $item['stock_id'];
                $lists['name_'.$seq] = $item['name'];
                $lists['request_'.$seq] = intval($item['request']);
                $lists['quantity_'.$seq] = intval($item['quantity']);
                $lists['unit_'.$seq] = $item['unit'];
                $lists['price_'.$seq] = doubleval($item['price']);
                $lists['amount_'.$seq] = doubleval($item['amount']);
                $parameters['amount'] += $lists['amount_'.$seq];
                $quantity += $lists['quantity_'.$seq];
            }
            if( $quantity<=0 ){
                Status::error( ((App::lang()=='en')?'Order quantity more than 1 items':'จำนวนจ่ายต้องมีอย่างน้อย 1 รายการ').' !!!', array('title'=>((App::lang()=='en')?'Please fill quantity':'โปรดระบุจำนวนเบิก')) );
            }
            if( Order::create("INSERT INTO `order` ($fields) VALUES ($values)", $parameters) ){
                if( Order::create($list, $lists) ){
                    $requests = array();
                    $requests['id'] = $_POST['request_id'];
                    $datas  = '`status_id`';
                    $datas .= "=:status_id";
                    $requests['status_id'] = 2;
                    $datas .= ',`checker`';
                    $datas .= "=:checker";
                    $requests['checker'] = User::get('fullname');
                    $datas .= ',`check_date`';
                    $datas .= "=NOW()";
                    $datas .= ',`date_accept`';
                    $datas .= "=NOW()";
                    $datas .= ',`user_accept`';
                    $datas .= "=:user_accept";
                    $requests['user_accept'] = User::get('email');
                    Request::update("UPDATE `request` SET $datas WHERE id=:id;", $requests);
                    Status::success( Lang::get('SuccessAccept'), array('title'=>Lang::get('Confirmed')) );
                }else{
                    Order::delete("DELETE FROM `order` WHERE id=:id;", array('id'=>$parameters['id']));
                }
            }
        }
    }else{
        if( !isset($_POST['remark'])||!$_POST['remark'] ){
            Status::error( Lang::get('PleaseEnter').' !!!', array('title'=>Lang::get('NotFound').Lang::get('Remark')) );
        }
        $requests = array();
        $requests['id'] = $_POST['request_id'];
        $datas  = '`status_id`';
        $datas .= "=:status_id";
        $requests['status_id'] = 4;
        $datas .= ',`checker`';
        $datas .= "=:checker";
        $requests['checker'] = User::get('fullname');
        $datas .= ',`check_date`';
        $datas .= "=NOW()";
        $datas .= ',`note_reject`';
        $datas .= "=:note_reject";
        $requests['note_reject'] = ( (isset($_POST['remark'])&&$_POST['remark']) ? Helper::stringSave($_POST['remark']) : null );
        $datas .= ',`date_reject`';
        $datas .= "=NOW()";
        $datas .= ',`user_reject`';
        $datas .= "=:user_reject";
        $requests['user_reject'] = User::get('email');
        if( Request::update("UPDATE `request` SET $datas WHERE id=:id;", $requests) ){
            Status::success( Lang::get('SuccessReject'), array('title'=>Lang::get('Rejected')) );
        }
    }
    Status::error( Lang::get('PleaseTryAgain').' !!!', array('title'=>Lang::get('ErrorSave')) );
?>