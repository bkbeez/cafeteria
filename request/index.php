<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php
    $index['customheader'] = true;
    $index['page'] = 'request';
    $link = APP_PATH.'/'.$index['page'];
    $form = APP_PATH.'/'.$index['page'];
    if( !Auth::check() ){
        $_SESSION['login_redirect'] = APP_HOME.'/'.$index['page']; 
        header('Location: '.APP_HOME.'/login');
        exit;
    }
    $shop_id = User::get('shop_id');
    if( $shop_id ){
        $shop = Shop::one("SELECT shop.*
                        , TRIM(CONCAT(COALESCE(shop.title,''),shop.name,' ',COALESCE(shop.surname,''))) AS owner_name
                        , IF(shop.address IS NOT NULL
                            ,TRIM(CONCAT(shop.address,' ',COALESCE(shop.province,''),' ',COALESCE(shop.zipcode,'')))
                            ,NULL
                        ) AS fulladdress
                        FROM shop
                        WHERE shop.id=:id
                        LIMIT 1;"
                        , array('id'=>$shop_id)
        );
        $lists = Stock::sql("SELECT stock.*
                        FROM stock
                        WHERE stock.status_id=1;
                        ORDER BY stock.sequence, stock.id;"
        );
    }
?>
<?php include(APP_HEADER);?>
<style type="text/css">
    form[name='RequestForm'] table {
        margin:0;
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }
    form[name='RequestForm'] table .no {
        width: 50px;
        text-align: center;
    }
    form[name='RequestForm'] table .name {
        width: auto;
    }
    form[name='RequestForm'] table .price {
        width: 100px;
        text-align: center;
    }
    form[name='RequestForm'] table .quantity {
        width: 125px;
    }
    form[name='RequestForm'] table .amount {
        width: 125px;
    }


    form[name='RequestForm'] table thead tr th {
        text-align: center;
        vertical-align: middle;
    }
    form[name='RequestForm'] table tbody tr td {
        padding: 0 0 0 0;
        vertical-align: middle;
    }
    form[name='RequestForm'] table tbody tr td.quantity,
    orm[name='RequestForm'] table tbody tr td.amount {
        padding-top: 2px;
        padding-bottom: 2px;
    }
</style>
<form name="RequestForm" action="<?=$form?>" method="POST" enctype="multipart/form-data" class="form-manage" target="_blank">
    <section class="wrapper image-wrapper bg-image bg-overlay bg-overlay-400 text-white" data-image-src="<?=THEME_IMG?>/bg-blue.jpg">
        <div class="container text-center pt-4 pb-16">
            <div class="row">
                <div class="col-md-10 col-xl-8 mx-auto">
                    <div class="post-header">
                        <h1 class="display-1 text-white mb-2"><?=((isset($shop['shop_name'])&&$shop['shop_name'])?$shop['shop_name']:Lang::get('Shop'))?></h1>
                        <ul class="post-meta fs-17 mb-5">
                            <li><i class="uil uil-calendar-alt"></i> <?=Helper::dateDisplay(new datetime())?></li>
                            <li><i class="uil uil-building"></i> <?=( (App::lang()=='en') ? APP_FACT_EN : APP_FACT_TH )?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="wrapper bg-light">
        <div class="container pb-14 pb-md-16">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="blog single mt-n16">
                        <div class="card shadow-lg">
                            <div class="card-body">
                            <?php if( isset($lists)&&count($lists)>0 ){ ?>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="no">#</th>
                                            <th scope="col" class="name"><?=Lang::get('List')?></th>
                                            <th scope="col" class="price"><?=Lang::get('Price')?></th>
                                            <th scope="col" class="quantity"><?=Lang::get('Quantity')?></th>
                                            <th scope="col" class="amount"><?=Lang::get('Amount')?></th>
                                            <th scope="col" class="actions">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($lists as $seq => $item){ ?>
                                        <tr>
                                            <td class="no"><?=($seq+1)?></td>
                                            <td class="name"><?=$item['name']?></td>
                                            <td class="price"><?=( (isset($item['charge'])&&$item['charge']>0) ? number_format($item['charge'],2) : '-' )?></td>
                                            <td class="quantity">
                                                <input name="quantity" value="0" type="text" class="form-control" placeholder="..." style="text-align:right!important;">
                                            </td>
                                            <td class="amount">
                                                <input name="amount" value="0" type="text" class="form-control" placeholder="..." readonly style="text-align:right!important;background:none;">
                                            </td>
                                            <td class="actions act-3">&nbsp;</td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
<div id="ManageDialog" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="false" aria-modal="true"></div>
<script type="text/javascript">
    function profile_events(action, params){
        if(action=='shop'){
            params['shop_id'] = '<?=$shop_id?>';
            params['form_as'] = '<?=$form.'/shop'?>';
            $("#ManageDialog").load("<?=$form?>/shop/"+params.on+".php", params, function(response, status, xhr){
                if(status=="error"){
                    $(this).html('<div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content text-center">'+xhr.status + "<br>" + xhr.statusText+'<div class="modal-body"></div></div></div>');
                }else{
                    $("#ManageDialog").modal('show');
                }
            });
        }else if(action=='user'){
            params['form_as'] = '<?=$form.'/user'?>';
            $("#ManageDialog").load("<?=$form?>/user/"+params.on+".php", params, function(response, status, xhr){
                if(status=="error"){
                    $(this).html('<div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content text-center">'+xhr.status + "<br>" + xhr.statusText+'<div class="modal-body"></div></div></div>');
                }else{
                    $("#ManageDialog").modal('show');
                }
            });
        }
    }
</script>
<?php include(APP_FOOTER);?>