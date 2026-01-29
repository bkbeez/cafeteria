<?php if(!isset($index['page'])||$index['page']!='request'){ header("location:".((isset($_SERVER['SERVER_PORT'])&&$_SERVER['SERVER_PORT']==443)?'https://':'http://').$_SERVER["HTTP_HOST"]); exit(); } ?>
<?php
    if( Auth::admin()||User::get('shop_id') ){
        // Allowed
    }else{
        $_SESSION['deny'] = array();
        $_SESSION['deny']['title'] = ( (App::lang()=='en') ? 'Oops! For shop only' : 'ขออภัย! สำหรับร้านค้าเท่านั้น' );
        header('Location: '.APP_HOME.'/deny');
        exit;
    }
    // Filter
    $filter_as = strtolower($index['page'].'_list_as');
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
    .table-filter .filter-result .date {
        width: 125px;
    }
    .table-filter .filter-result .shop {
        width: auto;
    }
    .table-filter .filter-result .name {
        width: 20%;
    }
    .table-filter .filter-result .amount {
        width: 120px;
        text-align: right;
        padding-right: 10px;
    }
    .table-filter .filter-result .remark {
        width: 120px;
    }
    .table-filter .filter-result .shop>.date-o,
    .table-filter .filter-result .shop>.name-o,
    .table-filter .filter-result .shop>.amount-o,
    .table-filter .filter-result .shop>.remark-o {
        display: none;
    }
    .table-filter .filter-result .shop>span {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .table-filter .filter-result .not-available {
        color: #CCC!important;
        font-style: italic !important;
    }
    .table-filter .filter-result .cancelled {
        color: #e2626b !important;
        text-decoration: line-through;
        font-style: italic !important;
    }
    @media only all and (max-width: 991px) {
        .table-filter .filter-result .name {
            display: none;
        }
        .table-filter .filter-result .shop>.name-o {
            display: block;
        }
    }
    @media only all and (max-width: 768px) {
        .table-filter .filter-result .date {
            display: none;
        }
        .table-filter .filter-result .shop>.date-o {
            display: block;
        }
    }
    @media only all and (max-width: 667px) {
        .table-filter .filter-result .amount {
            display: none;
        }
        .table-filter .filter-result .shop>.amount-o {
            display: block;
        }
    }
    @media only all and (max-width: 414px) {
        .table-filter .filter-result .remark {
            display: none;
        }
        .table-filter .filter-result .shop>.remark-o {
            display: block;
        }
    }
</style>
<section class="table-filter">
    <form name="filter" action="<?=$form?>/filter/search.php" method="POST" enctype="multipart/form-data" target="_blank">
        <input type="hidden" name="state" value="loading" />
        <input type="hidden" name="filter_as" value="<?=$filter_as?>" />
        <section class="wrapper image-wrapper bg-overlay bg-overlay-400 bg-image pt-3" data-image-src="<?=THEME_IMG?>/bg-blue.jpg">
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
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                            <div class="form-floating mb-1">
                                <?php if( App::lang()=='en' ){ ?>
                                <input name="condition[start_date]" type="date" value="<?=((isset($filter['condition']['start_date'])&&$filter['condition']['start_date'])?Helper::date($filter['condition']['start_date']):null)?>" class="form-control" placeholder="...">
                                <?php }else{ ?>
                                <input name="condition[start_date]" type="text" value="<?=((isset($filter['condition']['start_date'])&&$filter['condition']['start_date'])?Helper::date($filter['condition']['start_date']):null)?>" class="form-control" data-provide="datepicker" data-date-language="th-th" pattern="\d{1,2}/\d{1,2}/\d{4}" autocomplete="off" placeholder="..." minlength="10" maxlength="10" onkeyup="this.value=this.value.replace(/[^0-9/:]/g,'');"/>
                                <?php } ?>
                                <label><?=Lang::get('DateStart')?></label>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                            <div class="form-floating mb-1">
                                <?php if( App::lang()=='en' ){ ?>
                                <input name="condition[end_date]" type="date" value="<?=((isset($filter['condition']['end_date'])&&$filter['condition']['end_date'])?Helper::date($filter['condition']['end_date']):null)?>" class="form-control" placeholder="...">
                                <?php }else{ ?>
                                <input name="condition[end_date]" type="text" value="<?=((isset($filter['condition']['end_date'])&&$filter['condition']['end_date'])?Helper::date($filter['condition']['end_date']):null)?>" class="form-control" data-provide="datepicker" data-date-language="th-th" pattern="\d{1,2}/\d{1,2}/\d{4}" autocomplete="off" placeholder="..." minlength="10" maxlength="10" onkeyup="this.value=this.value.replace(/[^0-9/:]/g,'');"/>
                                <?php } ?>
                                <label><?=Lang::get('DateEnd')?></label>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-floating form-select-wrapper mb-1">
                                <select name="condition[status]" class="form-select">
                                    <option value="ALL"<?=((!isset($filter['condition']['status'])||$filter['condition']['status']=='ALL')?' selected':null)?>><?=Lang::get('All')?></option>
                                    <option value="ST1"<?=((isset($filter['condition']['status'])&&$filter['condition']['status']=='ST1')?' selected':null)?>><?=Lang::get('OnWaiting')?></option>
                                    <option value="ST2"<?=((isset($filter['condition']['status'])&&$filter['condition']['status']=='ST2')?' selected':null)?>><?=Lang::get('OnAccepted')?></option>
                                    <option value="ST3"<?=((isset($filter['condition']['status'])&&$filter['condition']['status']=='ST3')?' selected':null)?>><?=Lang::get('OnReceived')?></option>
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
                                <th scope="col" class="date"><?=Lang::get('RequestDate')?></th>
                                <th scope="col" class="shop"><?=Lang::get('Shop')?></th>
                                <th scope="col" class="name"><?=Lang::get('Requester')?></th>
                                <th scope="col" class="amount"><?=Lang::get('Amount')?></th>
                                <th scope="col" class="remark">&nbsp;</th>
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
        if(action=='detail'){
            params['form_as'] = '<?=$form?>';
            $("#ManageDialog").load("<?=$form?>/filter/detail.php", params, function(response, status, xhr){
                if(status=="error"){
                    $(this).html('<div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content text-center">'+xhr.status + "<br>" + xhr.statusText+'<div class="modal-body"></div></div></div>');
                }else{
                    $("#ManageDialog").modal('show');
                }
            });
        }
    }
    $(document).ready(function(){
        $("form[name='filter'] .filter-search input[name='condition[start_date]']").change(function(){
            $("form[name='filter'] button[type='submit']").click();
        });
        $("form[name='filter'] .filter-search input[name='condition[end_date]']").change(function(){
            $("form[name='filter'] button[type='submit']").click();
        });
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