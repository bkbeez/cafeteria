<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/admin/?users'); ?>
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
    $condition = "";
    $_SESSION['login']['filter'][$filter_as]['keyword'] = null;
    if( $keyword ){
        $_SESSION['login']['filter'][$filter_as]['keyword'] = $keyword;
        $parameters['keyword'] = "%".$keyword."%";
        $condition .= " AND ( shop.display LIKE :keyword";
            $condition .= " OR TRIM(CONCAT(COALESCE(shop.title,''),shop.name,' ',COALESCE(shop.surname,''))) LIKE :keyword";
            $condition .= " OR shop.phone LIKE :keyword";
        $condition .= " )";
    }
    $_SESSION['login']['filter'][$filter_as]['condition'] = array();
    if( isset($_POST['condition']) ){
        /*foreach($_POST['condition'] as $key => $value ){
            if($value){
                if($key=="role"){
                    $_SESSION['login']['filter'][$filter_as]['condition'][$key] = $value;
                    if($value=='USER'){
                        $condition .= " AND shop.role='USER'";
                    }else if($value=='STAFF'){
                        $condition .= " AND shop.role='STAFF'";
                    }else if($value=='ADMIN'){
                        $condition .= " AND shop.role='ADMIN'";
                    }
                }else if($key=="cmu"){
                    $_SESSION['login']['filter'][$filter_as]['condition'][$key] = $value;
                    if($value=='CMU'){
                        $condition .= " AND shop.is_cmu='Y'";
                    }else if($value=='NOT'){
                        $condition .= " AND shop.is_cmu='N'";
                    }
                }else if($key=="status"){
                    $_SESSION['login']['filter'][$filter_as]['condition'][$key] = $value;
                    if($value=='ST1'){
                        $condition .= " AND shop.status=1";
                    }else if($value=='ST2'){
                        $condition .= " AND shop.status=2";
                    }
                }else{
                    $_SESSION['login']['filter'][$filter_as]['condition'][$key] = $value;
                    $parameters[$key] = $value;
                    $condition .= " AND shop.".$key."=:".$key;
                }
            }
        }*/
    }
    // Total and Pages
    $sql = "SELECT COUNT(shop.id) AS total
            FROM shop
            WHERE shop.id IS NOT NULL";
    $count = DB::one($sql.$condition, $parameters);
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
    $sql = "SELECT shop.*
            , tbl_shop_type.name_th AS type_th
            , tbl_shop_type.name_en AS type_en
            , TRIM(CONCAT(COALESCE(shop.title,''),shop.name,' ',COALESCE(shop.surname,''))) AS owner_name
            , 'NORM' AS status
            FROM shop
            INNER JOIN tbl_shop_type ON shop.type=tbl_shop_type.id
            WHERE shop.id IS NOT NULL";
    $sql .= $condition;
    $sql .= " ORDER BY shop.sequence";
    $sql .= " LIMIT $start, $limit;";
    $htmls = '';
    $lists = DB::sql($sql, $parameters);
    if( isset($lists)&&count($lists)>0 ){
        $lang_edit = Lang::get('Edit');
        $lang_delete = Lang::get('Del');
        foreach($lists as $no => $row){
            $row_no = (($start+1)+$no);
            $htmls .= '<tr class="'.$row['status'].'">';
                $htmls .= '<td class="no" scope="row">'.$row_no.'</td>';
                $htmls .= '<td class="type">'.$row['type_'.$lang].'</td>';
                $htmls .= '<td class="name">';
                    $htmls .= '<font class="type-o">'.$row['type'].'</font>';
                    $htmls .= '<font>'.$row['display'].'</font>';
                    $htmls .= '<span class="name-o"><i class="uil uil-user"></i> '.$row['owner_name'].'</span>';
                $htmls .= '</td>';
                $htmls .= '<td class="owner">';
                    $htmls .= '<font>'.$row['owner_name'].'</font>';
                $htmls .= '</td>';
                $htmls .= '<td class="remark">';
                    $htmls .= $row['phone'];
                    //$htmls .= '<font>'.( $row['email_cmu'] ? $row['email_cmu'] : null ).'</font>';
                $htmls .= '</td>';
                $htmls .= '<td class="actions act-2">';
                    $htmls .= '<div class="btn-box"><button onclick="manage_events(\'edit\', { \'id\':\''.$row['id'].'\' });" type="button" class="btn btn-sm btn-circle btn-outline-primary"><i class="uil uil-edit-alt"></i></button><small class=b-tip>'.$lang_edit.'</small></div>';
                    if( $admin_as||$row['role']!='ADMIN' ){
                        $htmls .= '<div class="btn-box delete"><button type="button" onclick="manage_events(\'delete\', { \'id\':\''.$row['id'].'\', \'display\':\''.$row['display'].'\' });" class="btn btn-sm btn-circle btn-outline-danger"><i class="uil uil-trash-alt"></i></button><small class=b-tip>'.$lang_delete.'</small></div>';
                    }else{
                        $htmls .= '<div class="btn-box disabled"><button type="button" class="btn btn-sm btn-circle btn-soft-ash text-ash" style="cursor:default;"><i class="uil uil-trash-alt"></i></button><small class=b-tip>'.$lang_delete.'</small></div>';    
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