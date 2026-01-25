<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php
    $index['customheader'] = true;
    $index['page'] = 'shops';
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
    // Permission
    $admin_as = Auth::admin();
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
    .table-filter .filter-result .type {
        width: 12%;
    }
    .table-filter .filter-result .name {
        width: 25%;
    }
    .table-filter .filter-result .owner {
        width: 25%;
    }
    .table-filter .filter-result .remark {
        width: auto;
    }
    .table-filter .filter-result .name>span,
    .table-filter .filter-result .owner>span,
    .table-filter .filter-result .remark>span {
        display: block;
        font-weight: normal;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .table-filter .filter-result .name>font>i,
    .table-filter .filter-result .name>.tax-o,
    .table-filter .filter-result .name>.type-o,
    .table-filter .filter-result .name>.owner-o,
    .table-filter .filter-result .name>.remark-o{
        display: none;
    }
    .table-filter .filter-result .tax-number {
        margin: 0;
        padding-top: 0;
        padding-left: 0;
        padding-bottom: 0;
        overflow: hidden;
        border:1px solid #4079e0;
    }
    .table-filter .filter-result .tax-number>span {
        margin-right: 3px;
        padding-left: 3px;
        padding-right: 3px;
    }
    .table-filter .filter-result .not-available {
        color: #CCC!important;
        font-style: italic !important;
    }
    @media only all and (max-width: 991px) {
        .table-filter .filter-result .name {
            width: auto;
        }
        .table-filter .filter-result .owner {
            display: none;
        }
        .table-filter .filter-result .remark,
        .table-filter .filter-result .remark>span {
            overflow: auto;
            white-space: unset;
            text-overflow: unset;
        }
        .table-filter .filter-result .name>.owner-o {
            display: block;
        }
    }
    @media only all and (max-width: 768px) {
        .table-filter .filter-result .type {
            display: none;
        }
        .table-filter .filter-result .name>.type-o {
            display: block;
        }
        .table-filter .filter-result .name>font>i {
            display: inline;
            margin-right: 3px;
        }
    }
    @media only all and (max-width: 667px) {
        .table-filter .filter-result .remark {
            display: none;
        }
        .table-filter .filter-result .name>.tax-o {
            display: block;
        }
        .table-filter .filter-result .name,
        .table-filter .filter-result .name>span {
            overflow: auto;
            white-space: unset;
            text-overflow: unset;
        }
        .table-filter .filter-result .name>.remark-o {
            display: inline;
        }
        .table-filter .filter-result .actions.act-<?=($admin_as?'3':'2')?> {
            width: 45px;
            text-align: left;
        }
        .table-filter .filter-result .actions .btn-box.delete {
            margin-top: -4px;
        }
    }
</style>
<section class="table-filter">
    <form name="filter" action="<?=$form?>/filter/search.php" method="POST" enctype="multipart/form-data" target="_blank">
        <input type="hidden" name="state" value="loading" />
        <input type="hidden" name="filter_as" value="<?=$filter_as?>" />
        <input type="hidden" name="admin_as" value="<?=$admin_as?>" />
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
                                <button type="button" class="btn btn-primary btn-adding" onclick="manage_events('new', { 'link':'<?=$link?>' });"><i class="uil uil-plus"></i><span> <?=( (App::lang()=='en') ? 'New Shop' : 'ร้านใหม่' )?></span></button>
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
                                    <option value="ST1"<?=((isset($filter['condition']['status'])&&$filter['condition']['status']=='ST1')?' selected':null)?>><?=( (App::lang()=='en') ? 'Available' : 'พร้อมใช้งาน' )?></option>
                                    <option value="ST2"<?=((isset($filter['condition']['status'])&&$filter['condition']['status']=='ST2')?' selected':null)?>><?=( (App::lang()=='en') ? 'Not available' : 'ระงับใช้งาน' )?></option>
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
                                <th scope="col" class="type"><?=Lang::get('Type')?></th>
                                <th scope="col" class="name"><?=Lang::get('ShopName')?></th>
                                <th scope="col" class="owner"><?=Lang::get('ShopOwner')?></th>
                                <th scope="col" class="remark">&nbsp;</th>
                                <th scope="col" class="actions col-last act-<?=($admin_as?'3':'2')?>">&nbsp;</th>
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
        if(action=='new'){
            params['form_as'] = '<?=$form?>';
            $("#ManageDialog").load("<?=$form?>/filter/new.php", params, function(response, status, xhr){
                if(status=="error"){
                    $(this).html('<div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content text-center">'+xhr.status + "<br>" + xhr.statusText+'<div class="modal-body"></div></div></div>');
                }else{
                    $("#ManageDialog").modal('show');
                }
            });
        }else if(action=='edit'){
            params['form_as'] = '<?=$form?>';
            $("#ManageDialog").load("<?=$form?>/filter/edit.php", params, function(response, status, xhr){
                if(status=="error"){
                    $(this).html('<div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content text-center">'+xhr.status + "<br>" + xhr.statusText+'<div class="modal-body"></div></div></div>');
                }else{
                    $("#ManageDialog").modal('show');
                }
            });
        }else if(action=='address'){
            params['form_as'] = '<?=$form?>';
            $("#ManageDialog").load("<?=$form?>/filter/address.php", params, function(response, status, xhr){
                if(status=="error"){
                    $(this).html('<div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content text-center">'+xhr.status + "<br>" + xhr.statusText+'<div class="modal-body"></div></div></div>');
                }else{
                    $("#ManageDialog").modal('show');
                }
            });
        }else if(action=='delete'){
            swal({
                'title':'<b class="text-red" style="font-size:100px;"><i class="uil uil-trash-alt"></i></b>',
                'html' : '<div class="fs-24 text-red on-font-primary mb-2">'+params.display+'</div><div><?=Lang::get('ConfirmToDelete')?></div>',
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
                        url : "<?=$form?>/scripts/delete.php",
                        type: 'POST',
                        data: params,
                        dataType: "json",
                        beforeSend: function( xhr ) {
                            runStart();
                        }
                    }).done(function(data) {
                        runStop();
                        if(data.status=='success'){
                            swal({
                                'type': data.status,
                                'title': '<span class="on-font-primary">'+data.title+'</span>',
                                'html': data.text,
                                'showConfirmButton': false,
                                'timer': 1500
                            }).then(
                                function () {},
                                function (dismiss) {
                                    if (dismiss === 'timer') {
                                        $("form[name='filter'] button[type='submit']").click();
                                    }
                                }
                            );
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