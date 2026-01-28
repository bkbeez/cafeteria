<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php
    $index['customheader'] = true;
    $index['page'] = 'stock';
    $link = APP_PATH.'/'.$index['page'];
    $form = APP_PATH.'/'.$index['page'];
    if( !Auth::check() ){
        $_SESSION['login_redirect'] = APP_HOME.'/'.$index['page']; 
        header('Location: '.APP_HOME.'/login');
        exit;
    }
    if( !Auth::staff() ){
        $_SESSION['deny'] = array();
        $_SESSION['deny']['title'] = ( (App::lang()=='en') ? 'Oops! For officer only' : 'ขออภัย! สำหรับเจ้าหน้าที่เท่านั้น' );
        header('Location: '.APP_HOME.'/deny');
        exit;
    }
    // Filter
    $filter_as = strtolower($index['page'].'_as');
    $filter = ( isset($_SESSION['login']['filter'][$filter_as]) ? $_SESSION['login']['filter'][$filter_as] : null );
    // Footer
    $filterfooter_as = 'footer-'.$index['page'];
    $filterfooter = '<div class="container table-container pb-6">';
        $filterfooter .= '<div id="'.$filterfooter_as.'" class="table-footer">';
            $filterfooter .= '<div class="filter-display"><span class="badge bg-pale-ash text-dark rounded-pill">- '.Lang::get('NotFoundResult').' -</span></div>';
            $filterfooter .= '<div class="filter-pagination">';
                $filterfooter .= '<div class="row">';
                    $filterfooter .= '<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 filter-prev">';
                        $filterfooter .= '<button type="button" class="btn btn-sm'.((isset($filter['page'])&&$filter['page']==1)?' btn-soft-ash':' btn-primary').'"><i class="uil uil-angle-left-b"></i><span> '.Lang::get('Prev').'</span></button>';
                    $filterfooter .= '</div>';
                    $filterfooter .= '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 filter-page">';
                        $filterfooter .= '<center>';
                            $filterfooter .= '<select name="page" class="page-on form-select">';
                            if(isset($filter['pages'])&&$filter['pages']){
                                for($page=1;$page<=intval($filter['pages']);$page++){
                                    $filterfooter .= '<option value="'.$page.'"'.((isset($filter['page'])&&intval($filter['page'])==$page)?' selected':null).'>'.$page.'</option>';
                                }
                            }else{
                                $filterfooter .= '<option value="1">1</option>';
                            }
                            $filterfooter .= '</select>';
                            $filterfooter .= '<div class="page-total gb">/<span>1</span></div>';
                        $filterfooter .= '</center>';
                    $filterfooter .= '</div>';
                    $filterfooter .= '<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 filter-next">';
                        $filterfooter .= '<button type="button" class="btn btn-sm btn-icon btn-icon-end'.((isset($filter['page'])&&isset($filter['pages'])&&$filter['page']==$filter['pages'])?' btn-soft-ash':' btn-primary').'"><span>'.Lang::get('Next').' </span><i class="uil uil-angle-right-b"></i></button>';
                    $filterfooter .= '</div>';
                $filterfooter .= '</div>';
            $filterfooter .= '</div>';
        $filterfooter .= '</div>';
    $filterfooter .= '</div>';
    $index['filterfooter'] = $filterfooter;
?>
<?php include(APP_HEADER);?>
<style type="text/css">
    .table-filter .filter-result {
        background: white;
    }
    .table-filter .filter-result .name {
        width: auto;
    }
    .table-filter .filter-result .unit {
        width: 125px;
    }
    .table-filter .filter-result .quantity {
        width: 100px;
        text-align: right;
    }
    .table-filter .filter-result .status {
        width: 62px;
        padding: 1px 0;
        text-align: center;
    }
    .table-filter .filter-result .status>i {
        color: #3f78e0;
        cursor: pointer;
        font-size: 48px;
        line-height: 42px;
    }
    .table-filter .filter-result .status>i.uil-toggle-off {
        color: #aab0bc;
    }
    .table-filter .filter-result .status .toggle-label {
        width: 100%;
        font-size: 10px;
        margin-top: -14px;
        line-height: 10px;
        text-align: center;
    }
    .table-filter .filter-result .name>.unit-o,
    .table-filter .filter-result .name>.quantity-o {
        display: none;
    }
    .table-filter .filter-result .not-available {
        color: #CCC!important;
        font-style: italic !important;
    }
    .table-filter .filter-result .actions {
        width: 45px;
        text-align: left !important;
    }
    @media only all and (max-width: 991px) {
        .table-filter .filter-result .name {
            width: auto;
        }
        .table-filter .filter-result .unit {
            display: none;
        }
        .table-filter .filter-result .quantity {
            width: 12%;
        }
        .table-filter .filter-result .name>.unit-o {
            display: block;
        }
    }
    @media only all and (max-width: 768px) {
        .table-filter .filter-result .quantity {
            width: 15%;
        }
    }
    @media only all and (max-width: 500px) {
        .table-filter .filter-result .quantity {
            display: none;
        }
        .table-filter .filter-result .name>.quantity-o {
            display: block;
        }
        .table-filter .filter-result .name>span {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
        .table-filter .filter-result .name>.quantity-o>span {
            display: block;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
    }
</style>
<section class="table-filter">
    <form name="filter" action="<?=$form?>/filter/search.php" method="POST" enctype="multipart/form-data" target="_blank">
        <input type="hidden" name="state" value="loading" />
        <input type="hidden" name="filter_as" value="<?=$filter_as?>" />
        <section class="wrapper image-wrapper bg-overlay bg-overlay-400 bg-image" data-image-src="<?=THEME_IMG?>/bg-blue.jpg">
            <div class="container">
                <div class="filter-search">
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-6 filter-pageby">
                            <select name="limit" class="form-select mb-1">
                                <option value="50"<?=((!isset($filter['limit'])||intval($filter['limit'])==50)?' selected':null)?>>50</option>
                                <option value="100"<?=((isset($filter['limit'])&&intval($filter['limit'])==100)?' selected':null)?>>100</option>
                                <option value="250"<?=((isset($filter['limit'])&&intval($filter['limit'])==250)?' selected':null)?>>250</option>
                                <option value="500"<?=((isset($filter['limit'])&&intval($filter['limit'])==500)?' selected':null)?>>500</option>
                                <option value="750"<?=((isset($filter['limit'])&&intval($filter['limit'])==750)?' selected':null)?>>750</option>
                                <option value="1000"<?=((isset($filter['limit'])&&intval($filter['limit'])==1000)?' selected':null)?>>1000</option>
                            </select>
                        </div>
                        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-6 filter-keyword">
                            <div class="mc-field-group input-group form-floating mb-1">
                                <input id="keyword" name="keyword" type="text" value="<?=((isset($filter['keyword'])&&$filter['keyword'])?$filter['keyword']:null)?>" class="form-control" placeholder="...">
                                <label for="keyword"><?=Lang::get('Keyword')?></label>
                                <button type="submit" class="btn btn-soft-sky btn-search" title="<?=Lang::get('Search')?>"><i class="uil uil-search"></i></button>
                                <button type="button" class="btn btn-soft-red text-red btn-clear"><i class="uil uil-filter-slash"></i><sup class="fs-10"><?=Lang::get('Clear')?></sup></button>
                                <button type="button" class="btn btn-primary btn-adding" onclick="manage_events('inventory', { 'link':'<?=$link?>' });"><i class="uil uil-book-medical"></i><span class="fs-sm">&nbsp;<?=( (App::lang()=='en') ? 'Assets' : 'ทรัพย์สิน' )?></span></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-floating form-select-wrapper mb-1">
                                <select name="condition[type]" class="form-select">
                                    <?=Shop::typeOption(((isset($filter['condition']['type'])&&$filter['condition']['type'])?$filter['condition']['type']:null), '<option value="ALL">'.Lang::get('All').'</option>')?>
                                </select>
                                <label><?=Lang::get('Type')?></label>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-floating form-select-wrapper mb-1">
                                <select name="condition[status]" class="form-select">
                                    <option value="ALL"<?=((!isset($filter['condition']['status'])||$filter['condition']['status']=='ALL')?' selected':null)?>><?=Lang::get('All')?></option>
                                    <option value="ST1"<?=((isset($filter['condition']['status'])&&$filter['condition']['status']=='ST1')?' selected':null)?>><?=( (App::lang()=='en') ? 'Allow to withdraw' : 'อนุญาตเบิก' )?></option>
                                    <option value="ST2"<?=((isset($filter['condition']['status'])&&$filter['condition']['status']=='ST2')?' selected':null)?>><?=( (App::lang()=='en') ? 'Not-Allow to withdraw' : 'ไม่อนุญาตเบิก' )?></option>
                                </select>
                                <label><?=Lang::get('Status')?></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="wrapper">
            <div class="container pb-4">
                <div class="filter-result">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col" class="no col-first">#</th>
                                <th scope="col" class="name"><?=Lang::get('List')?></th>
                                <th scope="col" class="unit"><?=Lang::get('Charge').'/'.Lang::get('Unit')?></th>
                                <th scope="col" class="quantity"><?=Lang::get('Inventory')?></th>
                                <th scope="col" class="quantity"><?=Lang::get('Expose')?></th>
                                <th scope="col" class="quantity"><?=Lang::get('Balance')?></th>
                                <th scope="col" class="status">&nbsp;</th>
                                <th scope="col" class="actions col-last">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </section>
        <input type="hidden" name="pages" value="<?=((isset($filter['pages'])&&$filter['pages'])?$filter['pages']:0)?>" />
        <input type="hidden" name="page" value="<?=((isset($filter['page'])&&$filter['page'])?$filter['page']:1)?>" />
    </form>
</section>
<div id="ManageDialog" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="false" aria-modal="true"></div>
<script type="text/javascript">
    function manage_events(action, params){
        if(action=='inventory'){
            params['form_as'] = '<?=$form?>';
            $("#ManageDialog").load("<?=$form?>/filter/inventory.php", params, function(response, status, xhr){
                if(status=="error"){
                    $(this).html('<div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content text-center">'+xhr.status + "<br>" + xhr.statusText+'<div class="modal-body"></div></div></div>');
                }else{
                    $("#ManageDialog").modal('show');
                }
            });
        }else if(action=='manage'){
            params['form_as'] = '<?=$form?>';
            $("#ManageDialog").load("<?=$form?>/filter/manage.php", params, function(response, status, xhr){
                if(status=="error"){
                    $(this).html('<div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content text-center">'+xhr.status + "<br>" + xhr.statusText+'<div class="modal-body"></div></div></div>');
                }else{
                    $("#ManageDialog").modal('show');
                }
            });
        }else if(action=='status'){
            if( $(params.self).hasClass('uil-toggle-on') ){
                swal({
                    'title':'<b class="text-yellow" style="font-size:100px;"><i class="uil uil-question-circle"></i></b>',
                    'html' : '<?=( (App::lang()=='en') ? 'Confirm to turn OFF this ?' : 'ยืนยันปิดการเบิกรายการนี้ ใช่ หรือ ไม่ ?' )?>',
                    'showCloseButton': false,
                    'showConfirmButton': true,
                    'showCancelButton': true,
                    'focusConfirm': false,
                    'allowEscapeKey': false,
                    'allowOutsideClick': false,
                    'confirmButtonClass': 'btn btn-icon btn-icon-start btn-success rounded-pill',
                    'confirmButtonText':'<font class="fs-16"><i class="uil uil-check-circle"></i><?=Lang::get('Yes')?></font>',
                    'cancelButtonClass': 'btn btn-icon btn-icon-start btn-outline-danger rounded-pill',
                    'cancelButtonText':'<font class="fs-16"><i class="uil uil-times-circle"></i><?=Lang::get('No')?></font>',
                    'buttonsStyling': false
                }).then(
                    function () {
                        $.ajax({
                            url : "<?=$form?>/scripts/status.php",
                            type: 'POST',
                            data: { 'id':params.id, 'status':'N' },
                            dataType: "json",
                            beforeSend: function( xhr ) {
                                runStart();
                            }
                        }).done(function(data) {
                            runStop();
                            if(data.status=='success'){
                                $(params.self).attr('class','uil uil-toggle-off');
                                $(params.self).parent().parent().attr('class','not-available');
                            }else{
                                swal({
                                    'type' : data.status,
                                    'title': '<span class="on-font-primary">'+data.title+'</span>',
                                    'html' : data.text,
                                    'showCloseButton': false,
                                    'showCancelButton': false,
                                    'focusConfirm': false,
                                    'allowEscapeKey': false,
                                    'allowOutsideClick': false,
                                    'confirmButtonClass': 'btn btn-outline-danger',
                                    'confirmButtonText':'<span><?=Lang::get('Okay')?></span>',
                                    'buttonsStyling': false
                                }).then(
                                    function () {
                                        swal.close();
                                    },
                                    function (dismiss) {
                                        if (dismiss === 'cancel') {
                                            swal.close();
                                        }
                                    }
                                );
                            }
                        });
                    },
                    function (dismiss) {
                        if (dismiss === 'cancel') {
                            swal.close();
                        }
                    }
                );
            }else{
                $.ajax({
                    url : "<?=$form?>/scripts/status.php",
                    type: 'POST',
                    data: { 'id':params.id, 'status':'Y' },
                    dataType: "json",
                    beforeSend: function( xhr ) {
                        runStart();
                    }
                }).done(function(data) {
                    runStop();
                    if(data.status=='success'){
                        $(params.self).attr('class','uil uil-toggle-on');
                        $(params.self).parent().parent().attr('class','available');
                    }else{
                        swal({
                            'type' : data.status,
                            'title': '<span class="on-font-primary">'+data.title+'</span>',
                            'html' : data.text,
                            'showCloseButton': false,
                            'showCancelButton': false,
                            'focusConfirm': false,
                            'allowEscapeKey': false,
                            'allowOutsideClick': false,
                            'confirmButtonClass': 'btn btn-outline-danger',
                            'confirmButtonText':'<span><?=Lang::get('Okay')?></span>',
                            'buttonsStyling': false
                        }).then(
                            function () {
                                swal.close();
                            },
                            function (dismiss) {
                                if (dismiss === 'cancel') {
                                    swal.close();
                                }
                            }
                        );
                    }
                });
            }
        }
    }
    $(document).ready(function(){
        $("form[name='filter'] .filter-search select").change(function(){
            $("form[name='filter'] button[type='submit']").click();
        });
        $("form[name='filter'] .filter-search .btn-clear").click(function(){
            $("#<?=$filterfooter_as?> .filter-pagination select").val(1);
            $("form[name='filter'] input[name='page']").val(1);
            $("form[name='filter'] input[name='pages']").val(0);
            $("form[name='filter'] input[name='keyword']").val(null);
            $("form[name='filter'] .filter-search select").val('ALL');
            $("form[name='filter'] .filter-search .form-control").val(null);
            $("form[name='filter'] button[type='submit']").click();
        });
        $(".table-filter").tablefilter({'keyword':'auto', 'footer':'#<?=$filterfooter_as?>'});
    });
</script>
<?php include(APP_FOOTER);?>