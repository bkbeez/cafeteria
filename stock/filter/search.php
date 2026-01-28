<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/stock'); ?>
<?php
    // Init
    $lang = App::lang();
    $result = array('status'=>'success', 'title'=>Lang::get('Success') );
    $page = ((isset($_POST['page'])&&$_POST['page'])?intval($_POST['page']):1);
    $limit = ((isset($_POST['limit'])&&$_POST['limit'])?intval($_POST['limit']):1000);
    $keyword = ((isset($_POST['keyword'])&&$_POST['keyword'])?$_POST['keyword']:null);
    $filter_as = ((isset($_POST['filter_as'])&&$_POST['filter_as'])?$_POST['filter_as']:'search_as');
    if( !isset($_SESSION['login']['filter'][$filter_as]['limit'])||$_SESSION['login']['filter'][$filter_as]['limit']!=$limit ){
        $_SESSION['login']['filter'][$filter_as]['limit'] = $limit;
        $page = 1;
    }else if( isset($_SESSION['login']['filter'][$filter_as]['keyword'])&&$_SESSION['login']['filter'][$filter_as]['keyword']!=$keyword ){
        $page = 1;
    }
    // Condition
    $parameters = array();
    $condition = " AND stock.status_id=1";
    $_SESSION['login']['filter'][$filter_as]['keyword'] = null;
    if( $keyword ){
        $_SESSION['login']['filter'][$filter_as]['keyword'] = $keyword;
        $parameters['keynum'] = $keyword."%";
        $parameters['keyword'] = "%".$keyword."%";
        $condition .= " AND ( stock.name LIKE :keyword";
            $condition .= " OR stock.unit LIKE :keyword";
            $condition .= " OR stock.total LIKE :keynum";
            $condition .= " OR stock.balance LIKE :keynum";
        $condition .= " )";
    }
    $_SESSION['login']['filter'][$filter_as]['condition'] = array();
    if( isset($_POST['condition']) ){
        foreach($_POST['condition'] as $key => $value ){
            if($value){
                if($key=="type"){
                    $_SESSION['login']['filter'][$filter_as]['condition'][$key] = $value;
                    if($value!='ALL'){
                        $parameters['type'] = $value;
                        $condition .= " AND stock.type_id=:type";
                    }
                }else if($key=="status"){
                    $_SESSION['login']['filter'][$filter_as]['condition'][$key] = $value;
                    if($value=='ST1'){
                        $condition .= " AND stock.order_as='Y'";
                    }else if($value=='ST2'){
                        $condition .= " AND stock.order_as='N'";
                    }
                }else{
                    $_SESSION['login']['filter'][$filter_as]['condition'][$key] = $value;
                    $parameters[$key] = $value;
                    $condition .= " AND stock.".$key."=:".$key;
                }
            }
        }
    }
    // Total and Pages
    $sql = "SELECT COUNT(stock.id) AS total
            FROM stock
            WHERE stock.id IS NOT NULL";
    $count = Stock::one($sql.$condition, $parameters);
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
    $sql = "SELECT stock.*
            , stock_type.name_th AS type_th
            , stock_type.name_en AS type_en
            , (stock.total-stock.expose) AS balance
            , IF(stock.order_as='Y', 'available', 'not-available') AS status
            FROM stock
            INNER JOIN stock_type ON stock.type_id=stock_type.id
            WHERE stock.id IS NOT NULL";
    $sql .= $condition;
    $sql .= " ORDER BY stock.sequence, stock.id";
    $sql .= " LIMIT $start, $limit;";
    $htmls = '';
    $lists = Stock::sql($sql, $parameters);
    if( isset($lists)&&count($lists)>0 ){
        $lang_edit = Lang::get('Edit');
        $lang_inventory = Lang::get('Inventory');
        $lang_expose = Lang::get('Expose');
        $lang_balance = Lang::get('Balance');
        $lang_allow = ( ($lang=='en') ? 'Allow' : 'อนุญาตเบิก' );
        foreach($lists as $no => $row){
            $row_no = (($start+1)+$no);
            $htmls .= '<tr id="stock-'.$row['id'].'" class="'.$row['status'].'">';
                $htmls .= '<td class="no" scope="row">'.$row_no.'</td>';
                $htmls .= '<td class="name">';
                    $htmls .= '<font>'.$row['name'].'</font>';
                    $htmls .= '<span class="fs-sm unit-o">';
                        $htmls .= '&rang; '.( ($row['charge']>0) ? number_format($row['charge'],2).'฿' : '-' ).'/'.$row['unit'];
                    $htmls .= '</span>';
                    $htmls .= '<span class="fs-sm quantity-o">';
                        $htmls .= '<span>&rang; '.$lang_inventory.' '.number_format($row['total'],0).'</span>';
                        $htmls .= '<span>&rang; '.$lang_expose.' '.number_format($row['expose'],0).'</span>';
                        $htmls .= '<span>&rang; '.$lang_balance.' '.number_format($row['balance'],0).'</span>';
                    $htmls .= '</span>';
                $htmls .= '</td>';
                $htmls .= '<td class="unit">'.( ($row['charge']>0) ? number_format($row['charge'],2).'฿' : '-' ).'/'.$row['unit'].'</td>';
                $htmls .= '<td class="quantity">'.number_format($row['total'],0).'</td>';
                $htmls .= '<td class="quantity">'.number_format($row['expose'],0).'</td>';
                $htmls .= '<td class="quantity">'.number_format($row['balance'],0).'</td>';
                $htmls .= '<td class="status">';
                    $htmls .= '<i class="uil uil-toggle-'.( ($row['order_as']=='Y') ? 'on' : 'off' ).'" onclick="manage_events(\'status\', { \'self\':this, \'id\':\''.$row['id'].'\' });"></i>';
                    $htmls .= '<div class="toggle-label">'.$lang_allow.'</div>';
                $htmls .= '</td>';
                $htmls .= '<td class="actions">';
                    $htmls .= '<div class="btn-box"><button onclick="manage_events(\'manage\', { \'id\':\''.$row['id'].'\' });" type="button" class="btn btn-sm btn-circle btn-outline-primary"><i class="uil uil-edit-alt"></i></button><small class=b-tip>'.$lang_edit.'</small></div>';
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