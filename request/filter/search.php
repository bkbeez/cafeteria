<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/request'); ?>
<?php
    // Init
    $lang = App::lang();
    $result = array('status'=>'success', 'title'=>Lang::get('Success') );
    $page = ((isset($_POST['page'])&&$_POST['page'])?intval($_POST['page']):1);
    $limit = ((isset($_POST['limit'])&&$_POST['limit'])?intval($_POST['limit']):50);
    $keyword = ((isset($_POST['keyword'])&&$_POST['keyword'])?$_POST['keyword']:null);
    $filter_as = ((isset($_POST['filter_as'])&&$_POST['filter_as'])?$_POST['filter_as']:'search_as');
    if( !isset($_SESSION['login']['filter'][$filter_as]['limit'])||$_SESSION['login']['filter'][$filter_as]['limit']!=$limit ){
        $_SESSION['login']['filter'][$filter_as]['limit'] = $limit;
        $page = 1;
    }else if( isset($_SESSION['login']['filter'][$filter_as]['keyword'])&&$_SESSION['login']['filter'][$filter_as]['keyword']!=$keyword ){
        $page = 1;
    }
    // Condition
    $condition = "";
    $parameters = array();
    if( User::get('shop_id') ){
        $parameters['shop_id'] = User::get('shop_id');
        $condition = " AND request.shop_id=:shop_id";
        $_SESSION['login']['filter'][$filter_as]['keyword'] = null;
        if( $keyword ){
            $_SESSION['login']['filter'][$filter_as]['keyword'] = $keyword;
            $parameters['keynum'] = $keyword."%";
            $parameters['keyword'] = "%".$keyword."%";
            $condition .= " AND ( request.shop_name LIKE :keyword";
                $condition .= " OR request.requester LIKE :keyword";
                $condition .= " OR request.amount LIKE :keynum";
            $condition .= " )";
        }
        $_SESSION['login']['filter'][$filter_as]['condition'] = array();
        if( isset($_POST['condition']) ){
            foreach($_POST['condition'] as $key => $value ){
                if($value){
                    if($key=="start_date"){
                        $_SESSION['login']['filter'][$filter_as]['condition'][$key] = Helper::dateSave($value);
                        $parameters[$key] = $_SESSION['login']['filter'][$filter_as]['condition'][$key].' 00:00:00';
                        $condition .= " AND request.request_date>=:".$key;
                    }else if($key=="end_date"){
                        $_SESSION['login']['filter'][$filter_as]['condition'][$key] = Helper::dateSave($value);
                        $parameters[$key] = $_SESSION['login']['filter'][$filter_as]['condition'][$key].' 23:59:59';
                        $condition .= " AND request.request_date>=:".$key;
                    }else if($key=="status"){
                        $_SESSION['login']['filter'][$filter_as]['condition'][$key] = $value;
                        if($value=='ST1'){
                            $condition .= " AND request.status_id=1";
                        }else if($value=='ST2'){
                            $condition .= " AND request.status_id=2";
                        }else if($value=='ST3'){
                            $condition .= " AND request.status_id=3";
                        }else if($value=='ST4'){
                            $condition .= " AND request.status_id=4";
                        }else if($value=='ST5'){
                            $condition .= " AND request.status_id=5";
                        }
                    }else{
                        $_SESSION['login']['filter'][$filter_as]['condition'][$key] = $value;
                        $parameters[$key] = $value;
                        $condition .= " AND request.".$key."=:".$key;
                    }
                }
            }
        }
    }else{
        $condition = " AND request.shop_id='NONE'";
    }
    // Total and Pages
    $sql = "SELECT COUNT(request.id) AS total
            FROM request
            WHERE request.id IS NOT NULL";
    $count = Request::one($sql.$condition, $parameters);
    $result['total'] = ( (isset($count['total'])&&$count['total']) ? intval($count['total']) : 0 );
    $result['pages'] = 1;
    if($result['total']>0){
        if( ($result['total']%$limit)==0 ){
            $result['pages'] = intval($result['total']/$limit);
        }else{
            $result['pages'] = intval($result['total']/$limit)+1;
        }
    }
    $_SESSION['login']['filter'][$filter_as]['pages'] = $result['pages'];
    $result['display'] = number_format($result['pages'],0);
    // Page
    if($page>1&&$page>$result['pages']){
        $page = ($page-1);
    }
    $result['page'] = $page;
    $_SESSION['login']['filter'][$filter_as]['page'] = $result['page'];
    if( !isset($_POST['pages'])||(intval($_POST['pages'])!=$result['pages']) ){
        $result['pagination'] = '';
        for($sel=1;$sel<=intval($result['pages']);$sel++){
            $result['pagination'] .= '<option value="'.$sel.'" '.( ($page==$sel) ? 'selected':null ).'>'.number_format($sel,0).'</option>';
        }
    }
    // Run
    $start = (($page-1)*$limit);
    $sql = "SELECT request.*
            ,IF(request.status_id=5, 'Cancelled'
                ,IF(request.status_id=4, 'Rejected'
                    ,IF(request.status_id=3, 'Received'
                        ,IF(request.status_id=2, 'Confirmed'
                            , 'Waiting'
                        )
                    )
                )
            ) AS status_key
            , IF(request.date_cancel IS NOT NULL, 'cancelled'
                ,IF(request.status_id<0, 'not-available', 'available') 
            ) AS status
            FROM request
            WHERE request.id IS NOT NULL";
    $sql .= $condition;
    $sql .= " ORDER BY request.request_date DESC";
    $sql .= " LIMIT $start, $limit;";
    $htmls = '';
    $lists = Request::sql($sql, $parameters);
    if( isset($lists)&&count($lists)>0 ){
        $lang_view = Lang::get('View');
        foreach($lists as $no => $row){
            $row_no = (($start+1)+$no);
            $date_display = Helper::date($row['request_date']);
            $status = '<em class="fs-sm text-yellow"><i class="uil uil-circle"></i>'.Lang::get($row['status_key']).'... .. .</em>';
            if( $row['status_id']>=4 ){
                $status = '<span class="fs-sm text-red"><i class="uil uil-times-circle"></i>'.Lang::get($row['status_key']).'</span>';
            }else if( $row['status_id']==3 ){
                $status = '<span class="fs-sm text-green"><i class="uil uil-check-circle"></i>'.Lang::get($row['status_key']).'</span>';
            }else if( $row['status_id']==2 ){
                $status = '<span class="fs-sm text-blue"><i class="uil uil-check-circle"></i>'.Lang::get($row['status_key']).'</span>';
            }else if( $row['status_key']=='OnAccepted' ){
                $status = '<span class="fs-sm text-blue"><i class="uil uil-check-circle"></i>'.$status.'</span>';
            } 
            $htmls .= '<tr class="'.$row['status'].'">';
                $htmls .= '<td class="no" scope="row">'.$row_no.'</td>';
                $htmls .= '<td class="date">'.$date_display.'</td>';
                $htmls .= '<td class="shop">';
                    $htmls .= '<span class="date-o"><i class="uil uil-calendar-alt"></i> '.$date_display.'</span>';
                    $htmls .= '<font>'.$row['shop_name'].'</font>';
                    $htmls .= '<span class="fs-sm name-o"><i class="uil uil-user"></i> '.$row['requester'].'</span>';
                    $htmls .= '<span class="fs-sm amount-o"><i class="uil uil-invoice"></i> '.number_format($row['amount'], 2).'฿</span>';
                    $htmls .= '<span class="fs-sm remark-o">'.$status.'</span>';
                $htmls .= '</td>';
                $htmls .= '<td class="name"><font>'.$row['requester'].'</font></td>';
                $htmls .= '<td class="amount"><font>'.number_format($row['amount'], 2).'฿</font></td>';
                $htmls .= '<td class="remark"><font>'.$status.'</font></td>';
                $htmls .= '<td class="actions">';
                if( $row['status_id']>=4 ){
                    $htmls .= '<div class="btn-box delete"><button onclick="manage_events(\'detail\', { \'id\':\''.$row['id'].'\' });" type="button" class="btn btn-sm btn-circle btn-soft-red"><i class="uil uil-file-alt"></i></button><small class=b-tip>'.$lang_view.'</small></div>';
                }else if( $row['status_id']==3 ){
                    $htmls .= '<div class="btn-box green"><button onclick="manage_events(\'detail\', { \'id\':\''.$row['id'].'\' });" type="button" class="btn btn-sm btn-circle btn-soft-green"><i class="uil uil-file-alt"></i></button><small class=b-tip>'.$lang_view.'</small></div>';
                }else if( $row['status_id']==2 ){
                    $htmls .= '<div class="btn-box"><button onclick="manage_events(\'detail\', { \'id\':\''.$row['id'].'\' });" type="button" class="btn btn-sm btn-circle btn-soft-primary"><i class="uil uil-file-alt"></i></button><small class=b-tip>'.$lang_view.'</small></div>';
                }else{
                    $htmls .= '<div class="btn-box warning"><button onclick="manage_events(\'detail\', { \'id\':\''.$row['id'].'\' });" type="button" class="btn btn-sm btn-circle btn-warning"><i class="uil uil-file-edit-alt"></i></button><small class=b-tip>'.$lang_view.'</small></div>';
                }
                $htmls .= '</td>';
            $htmls .= '</tr>';
        }
    }
    $result['htmls'] = $htmls;
    if( $result['total']<=0 ){
        $result['text'] = '0 - 0 / 0';
    }else{
        $result['text'] = number_format(($start+1),0).' - '.( (($start+$limit)>$result['total']) ? number_format($result['total'],0) : number_format(($start+$limit),0) ).' / '.number_format($result['total'],0);
    }
    // Returns
    echo json_encode($result);
    exit();
?>