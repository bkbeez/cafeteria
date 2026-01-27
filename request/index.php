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
    form[name='ManageForm'] table {
        margin:0;
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }
    form[name='ManageForm'] table .no {
        width: 50px;
        text-align: center;
    }
    form[name='ManageForm'] table .name {
        width: auto;
    }
    form[name='ManageForm'] table .price {
        width: 70px;
        text-align: center;
    }
    form[name='ManageForm'] table .quantity {
        width: 125px;
    }
    form[name='ManageForm'] table .amount {
        width: 125px;
    }
    form[name='ManageForm'] table .baht {
        width: 45px;
    }
    form[name='ManageForm'] table thead tr th {
        text-align: center;
        vertical-align: top;
    }
    form[name='ManageForm'] table tbody tr td {
        padding: 14px 0 0 0;
        vertical-align: top;
    }
    form[name='ManageForm'] table tbody tr td.quantity {
        padding-top: 2px;
        padding-bottom: 2px;
    }
    form[name='ManageForm'] table tbody tr td.amount {
        text-align: right;
        padding-right: 25px;
    }
    form[name='ManageForm'] table tbody tr td.quantity>input {
        text-align: right;
    }
    form[name='ManageForm'] table tfoot tr td {
        font-weight: bold;
        vertical-align: top;
        padding: 14px 0 25px 0;
        border-bottom-color: white;
    }
    form[name='ManageForm'] table tfoot tr td.name {
        text-align: right;
    }
    form[name='ManageForm'] table tfoot tr td.quantity {
        text-align: right;
        padding-right: 36px;
    }
    form[name='ManageForm'] table tfoot tr td.amount {
        text-align: right;
        padding-right: 25px;
    }
    form[name='ManageForm'] .manage-footer {
        width: 100%;
        height: 120px;
        padding-top: 12px;
        text-align: center;
    }
    form[name='ManageForm'] .manage-footer .confirm-btn {
        padding-top: 25px;
    }
    form[name='ManageForm'] .manage-footer button>i {
        float: left;
        font-size: 28px;
        line-height: 28px;
        margin-right: 3px;
    }
    @media only all and (max-width: 667px) {
        form[name='ManageForm'] table .no {
            width: 5%;
        }
        form[name='ManageForm'] table .price {
            width: 70px;
        }
        form[name='ManageForm'] table .quantity {
            width: 20%;
        }
        form[name='ManageForm'] table .amount {
            width: 20%;
        }
        form[name='ManageForm'] table thead tr th {
            padding-left: 3px;
            padding-right: 3px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
        form[name='ManageForm'] table tbody tr td.name {
            padding-top: 0;
            vertical-align: middle;
        }
        form[name='ManageForm'] .manage-footer .confirm-btn>.btn {
            width: 48%;
        }
        form[name='ManageForm'] .manage-footer .confirm-btn>.btn:first-chlid {
            float: left;
            position: absolute;
        }
    }
</style>
<form name="ManageForm" action="<?=$form?>" method="POST" enctype="multipart/form-data" class="form-manage" target="_blank">
    <section class="wrapper image-wrapper bg-image bg-overlay bg-overlay-400 text-white" data-image-src="<?=THEME_IMG?>/bg-blue.jpg">
        <div class="container text-center pt-4 pb-16">
            <div class="row">
                <div class="col-md-10 col-xl-8 mx-auto">
                    <div class="post-header">
                        <h1 class="display-1 text-white mb-2"><?=((isset($shop['shop_name'])&&$shop['shop_name'])?$shop['shop_name']:Lang::get('Shop'))?></h1>
                        <ul class="post-meta text-white fs-17 mb-5">
                            <li><i class="uil uil-building"></i> <?=( (App::lang()=='en') ? APP_FACT_EN : APP_FACT_TH )?></li>
                            <li><i class="uil uil-calendar-alt"></i> <?=Helper::dateDisplay(new datetime())?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="wrapper">
        <div class="container pb-2">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="blog single mt-n16">
                        <div class="card shadow-lg">
                            <div class="card-body p-1">
                            <?php if( isset($lists)&&count($lists)>0 ){ ?>
                                <table border="0" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="no">#</th>
                                            <th scope="col" class="name"><?=Lang::get('List')?></th>
                                            <th scope="col" class="price"><?=Lang::get('Price')?></th>
                                            <th scope="col" class="quantity"><?=Lang::get('Quantity')?></th>
                                            <th scope="col" class="amount"><?=Lang::get('Amount')?></th>
                                            <th scope="col" class="baht">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($lists as $seq => $item){ ?>
                                        <tr class="AT-<?=$item['id']?>">
                                            <td class="no"><?=($seq+1)?></td>
                                            <td class="name"><?=$item['name']?></td>
                                            <td class="price"><?=(($item['charge']>0)?number_format($item['charge'],2):'-')?></td>
                                            <td class="quantity">
                                                <input type="hidden" name="list[<?=$seq?>][stock_id]" value="<?=$item['id']?>"/>
                                                <input type="hidden" name="list[<?=$seq?>][name]" value="<?=$item['name']?>"/>
                                                <input type="number" name="list[<?=$seq?>][quantity]" value="0" min="0" class="form-control set-quantity" placeholder="..." onchange="manage_events('change', { 'at':'<?=$item['id']?>' });"/>
                                                <input type="hidden" name="list[<?=$seq?>][unit]" value="<?=$item['unit']?>" class="set-unit"/>
                                                <input type="hidden" name="list[<?=$seq?>][price]" value="<?=$item['charge']?>" class="set-price"/>
                                                <input type="hidden" name="list[<?=$seq?>][amount]" value="0" class="set-amount"/>
                                            </td>
                                            <td class="amount">0.00</td>
                                            <td class="baht"><?=Lang::get('Baht')?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="name" colspan="4"><?=Lang::get('GrandTotal')?></td>
                                            <td class="amount">0.00</td>
                                            <td class="baht"><?=Lang::get('Baht')?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="manage-footer">
                        <div class="confirm-box"></div>
                        <div class="confirm-btn">
                            <button type="button" class="btn btn-lg btn-blue rounded" onclick="manage_events('confirm');"><i class="uil uil-check-circle"></i><?=Lang::get('Save')?></button>
                            <button type="button" class="btn btn-lg btn-outline-danger rounded"><i class="uil uil-times-circle"></i><?=Lang::get('Cancel')?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
<script type="text/javascript">
    function manage_events(action, params){
        if(action=='change'){
            var price = parseFloat($("form[name='ManageForm'] .AT-"+params.at+" .set-price").val());
            if( !isNaN(price) ){
                var quantity = parseFloat($("form[name='ManageForm'] .AT-"+params.at+" .set-quantity").val());
                var amount = quantity*price;
                $("form[name='ManageForm'] .AT-"+params.at+" .set-amount").val(amount);
                $("form[name='ManageForm'] .AT-"+params.at+" .amount").html(amount.format(2));
                manage_events('summary');
            }
        }else if(action=='summary'){
            var amounts = $("form[name='ManageForm'] table tbody .set-amount");
            var grand_total = 0;
            if(amounts.length>0){
                for(var i=0;i<amounts.length;i++){
                    var amount = parseFloat($(amounts[i]).val());
                    if(!isNaN(amount)){ grand_total += amount; }
                }
            }
            $("form[name='ManageForm'] table tfoot .amount").html(grand_total.format(2));
        }else if(action=="confirm"){
            if( params!=undefined ){
                $("form[name='ManageForm'] .confirm-box").html('');
                $("form[name='ManageForm'] .confirm-btn").show();
            }else{
                var htmls  = '<div class="fs-20 mb-2 text-center on-text-normal on-text-oneline"><?=( (App::lang()=='en') ? 'Confirm to create new order ?' : 'ยืนยันขอเบิกภาชนะเหล่านี้ ใช่ หรือ ไม่ ?' )?></div>';                    
                    htmls += '<button type="submit" class="btn btn-icon btn-icon-start btn-success rounded-pill"><i class="uil uil-check-circle"></i><?=Lang::get('Yes')?></button>';
                    htmls += '&nbsp;';
                    htmls += '<button type="button" class="btn btn-icon btn-icon-start btn-outline-danger rounded-pill" onclick="manage_events(\'confirm\', { \'on\':\'N\' });"><i class="uil uil-times-circle"></i><?=Lang::get('No')?></button>';
                $("form[name='ManageForm'] .confirm-box").html(htmls);
                $("form[name='ManageForm'] .confirm-btn").hide();
            }
        }
    }
    $(document).ready(function() {
        $("form[name='ManageForm'] input[name='login_email']").change(function(){
            if(this.value){
                $("form[name='ManageForm'] button[type='submit']").click();
            }else{
                login_events('email', {'on':'clear'});
            }
        });
        /*$("form[name='ManageForm']").ajaxForm({
            beforeSubmit: function (formData, jqForm, options) {
                $("form[name='ManageForm'] label>span").remove();
            },
            success: function(rs) {
                var data = JSON.parse(rs);
                if(data.status=='success'){
                    if(data.shop!=undefined&&data.shop=='Y'){
                        login_events('email', {'on':'show'});
                    }else{
                        $("body").fadeOut('slow', function(){
                            document.location = data.url;
                        });
                    }
                }else{
                    if( data.onfocus!=undefined&&data.onfocus ){
                        $("form[name='ManageForm'] label[for='"+data.onfocus+"']").append("<span class=text-red><sup> * <em>"+data.text+"</em></sup></span>");
                        $("form[name='ManageForm'] input[name='"+data.onfocus+"']").focus();
                    }else{
                        swal({
                            'type' : data.status,
                            'title': data.title,
                            'html' : data.text,
                            'showCloseButton': false,
                            'showCancelButton': false,
                            'focusConfirm': false,
                            'allowEscapeKey': false,
                            'allowOutsideClick': false,
                            'confirmButtonClass': 'btn btn-outline-danger',
                            'confirmButtonText':'<span><?=Lang::get('Understand')?></span>',
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
                }
            }
        });*/
    });
</script>
<?php include(APP_FOOTER);?>