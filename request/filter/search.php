<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/shops'); ?>
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
    // Permission
    $admin_as = Auth::admin();
    // Condition
    $parameters = array();
    //$condition = " AND request.date_delete IS NULL";
    //if( $admin_as ){
        $condition = "";
    //}
    $_SESSION['login']['filter'][$filter_as]['keyword'] = null;
    if( $keyword ){
        $_SESSION['login']['filter'][$filter_as]['keyword'] = $keyword;
        //$parameters['keynum'] = $keyword."%";
        $parameters['keyword'] = "%".$keyword."%";
        $condition .= " AND ( request.shop_name LIKE :keyword";
            $condition .= " OR request.requester LIKE :keyword";
        $condition .= " )";
    }
    $_SESSION['login']['filter'][$filter_as]['condition'] = array();
    /*if( isset($_POST['condition']) ){
        foreach($_POST['condition'] as $key => $value ){
            if($value){
                if($key=="type"){
                    $_SESSION['login']['filter'][$filter_as]['condition'][$key] = $value;
                    if($value!='ALL'){
                        $parameters['type'] = $value;
                        $condition .= " AND request.type_id=:type";
                    }
                }else if($key=="status"){
                    $_SESSION['login']['filter'][$filter_as]['condition'][$key] = $value;
                    if($value=='ST1'){
                        $condition .= " AND request.status_id=1";
                    }else if($value=='ST2'){
                        $condition .= " AND request.status_id=2";
                    }
                }else{
                    $_SESSION['login']['filter'][$filter_as]['condition'][$key] = $value;
                    $parameters[$key] = $value;
                    $condition .= " AND request.".$key."=:".$key;
                }
            }
        }
    }*/
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
        $lang_edit = Lang::get('Edit');
        $lang_delete = Lang::get('Del');
        $lang_owner = Lang::get('ShopOwner');
        $lang_address = Lang::get('Address');
        foreach($lists as $no => $row){
            $row_no = (($start+1)+$no);
            $htmls .= '<tr class="'.$row['status'].'">';
                $htmls .= '<td class="no" scope="row">'.$row_no.'</td>';
                $htmls .= '<td class="date">'.Helper::date($row['request_date']).'</td>';
                $htmls .= '<td class="shop">';
                    //$htmls .= '<font class="type-o">'.$row['type_'.$lang].'</font>';
                    //$htmls .= ( $row['tax_number'] ? '<span class="tax-o"><mark class="doc tax-number"><span class="text-white bg-primary">TAX</span>'.$row['tax_number'].'</mark></span>' : null );
                    $htmls .= '<font><i class="uil uil-shop"></i>'.$row['shop_name'].'</font>';
                    /*$htmls .= '<span class="fs-sm owner-o">';
                        $htmls .= '<i class="uil uil-user"></i> '.$row['owner_name'];
                        $htmls .= '<br><i class="uil uil-phone-volume"></i> '.$row['phone'];
                    $htmls .= '</span>';*/
                    //$htmls .= ( $row['fulladdress'] ? '<span class="fs-sm remark-o"><i class="uil uil-map-marker-alt"></i> '.$row['fulladdress'].'</span>' : null );
                $htmls .= '</td>';
                $htmls .= '<td class="name">';
                    $htmls .= '<font>'.$row['requester'].'</font>';
                $htmls .= '</td>';
                $htmls .= '<td class="amount">';
                    $htmls .= '<font>'.number_format($row['amount'], 2).'฿</font>';
                $htmls .= '</td>';
                $htmls .= '<td class="actions act-3">';
                    $htmls .= '<div class="btn-box"><button onclick="manage_events(\'edit\', { \'id\':\''.$row['id'].'\' });" type="button" class="btn btn-sm btn-circle btn-outline-primary"><i class="uil uil-edit-alt"></i></button><small class=b-tip>'.$lang_edit.'</small></div>';
                    $htmls .= '<div class="btn-box"><button onclick="manage_events(\'address\', { \'id\':\''.$row['id'].'\' });" type="button" class="btn btn-sm btn-circle btn-outline-primary"><i class="uil uil-file-edit-alt"></i></button><small class=b-tip>'.$lang_address.'</small></div>';
                    $htmls .= '<div class="btn-box delete"><button type="button" onclick="manage_events(\'delete\', { \'id\':\''.$row['id'].'\' });" class="btn btn-sm btn-circle btn-outline-danger"><i class="uil uil-trash-alt"></i></button><small class=b-tip>'.$lang_delete.'</small></div>';
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