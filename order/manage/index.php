<?php if(!isset($request_id)||!isset($index['page'])||$index['page']!='order'){ header("location:".((isset($_SERVER['SERVER_PORT'])&&$_SERVER['SERVER_PORT']==443)?'https://':'http://').$_SERVER["HTTP_HOST"]); exit(); } ?>
<?php
    $data = DB::one("SELECT request.*
                    FROM request
                    WHERE request.id=:id
                    LIMIT 1;"
                    , array('id'=>$request_id)
    );
    $lists = Stock::sql("SELECT request_list.*
                    FROM request_list
                    WHERE request_list.request_id=:request_id;
                    ORDER BY request_list.id;"
                    , array('request_id'=>$request_id)
    );
    $index['hidefooter'] = true;
?>
<?php include(APP_HEADER);?>
<style type="text/css">
    .display-1 {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    form[name='ManageForm'] table {
        width: 100%;
        margin: 0 0 15px 0;
        table-layout: fixed;
        border-collapse: collapse;
    }
    form[name='ManageForm'] table .no {
        width: 50px;
        text-align: center;
    }
    form[name='ManageForm'] table .name {
        width: auto;
        text-align: left;
    }
    form[name='ManageForm'] table .name mark {
        margin: 0;
        display: none;
        font-size: 12px;
    }
    form[name='ManageForm'] table .price {
        width: 50px;
        text-align: center;
    }
    form[name='ManageForm'] table .request {
        width: 85px;
        text-align: right;
    }
    form[name='ManageForm'] table .quantity {
        width: 135px;
        text-align: right;
    }
    form[name='ManageForm'] table .amount {
        width: 135px;
        text-align: right;
    }
    form[name='ManageForm'] table .baht {
        width: 45px;
    }
    form[name='ManageForm'] table .baht .baht-sm {
        display: none;
    }
    form[name='ManageForm'] table thead tr th {
        padding-left: 0;
        padding-right: 0;
        vertical-align: top;
    }
    form[name='ManageForm'] table thead tr th.quantity {
        padding-right: 36px;
    }
    form[name='ManageForm'] table thead tr th.request,
    form[name='ManageForm'] table thead tr th.amount {
        padding-right: 25px;
    }
    form[name='ManageForm'] table tbody tr td {
        padding: 14px 0 0 0;
        vertical-align: top;
    }
    form[name='ManageForm'] table tbody tr td.request {
        text-align: right;
        padding-right: 25px;
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
        padding-left: 0;
        text-align: right;
    }
    form[name='ManageForm'] table tfoot tr td {
        font-weight: bold;
        vertical-align: top;
        padding: 14px 0 0 0;
        border-bottom-color: white;
    }
    form[name='ManageForm'] table tfoot tr td.name {
        text-align: right;
        padding-right: 0;
    }
    form[name='ManageForm'] table tfoot tr td.quantity {
        text-align: right;
        padding-right: 36px;
    }
    form[name='ManageForm'] table tfoot tr td.amount {
        text-align: right;
        padding-right: 25px;
    }
    form[name='ManageForm'] table tfoot tr td.last {
        padding: 15px 0 0 0;
        text-align: left;
    }
    form[name='ManageForm'] table tfoot tr td.last>i {
        font-size: 28px;
        line-height: 24px;
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
        .display-shop>span {
            width: 100%;
            display: block;
            text-align: left;
            padding-left: 15%;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
        form[name='ManageForm'] table .no {
            display: none;
        }
        form[name='ManageForm'] table .name mark {
            color: white;
            display: inline-block;
            background-color: #0d2987;
        }
        form[name='ManageForm'] table .price {
            width: 40px;
        }
        form[name='ManageForm'] table .quantity {
            width: 20%;
            padding-right: 0!important;
        }
        form[name='ManageForm'] table .amount {
            width: 20%;
            padding-right: 5px!important;
        }
        form[name='ManageForm'] table .baht {
            width: 5%;
        }
        form[name='ManageForm'] table thead tr th {
            padding-left: 3px;
            padding-right: 3px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
        form[name='ManageForm'] table thead tr th.quantity {
            text-align: center;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        form[name='ManageForm'] table thead tr th.amount {
            text-align: right;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        form[name='ManageForm'] table tbody tr td.name {
            padding-top: 0;
            vertical-align: middle;
        }
        form[name='ManageForm'] table .baht .baht-lg {
            display: none;
        }
        form[name='ManageForm'] table .baht .baht-sm {
            display: block;
        }
        form[name='ManageForm'] table tfoot tr td {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
        form[name='ManageForm'] table tfoot tr td.name,
        form[name='ManageForm'] table tfoot tr td.quantity {
            text-align: left;
            padding-left: 5px;
        }
        form[name='ManageForm'] table tfoot tr td.last>i {
            font-size: 16px;
        }
        form[name='ManageForm'] .manage-footer .confirm-btn>.btn {
            width: 48%;
        }
        form[name='ManageForm'] .manage-footer .confirm-btn>.btn:first-chlid {
            float: left;
            position: absolute;
        }
    }
    @media only all and (max-width: 414px) {
        .display-shop>span {
            padding-left: 0;
        }
    }
</style>
<form name="ManageForm" action="<?=$form?>/scripts/checking.php" method="POST" enctype="multipart/form-data" class="form-manage" target="_blank">
    <input type="hidden" name="request_id" value="<?=((isset($data['id'])&&$data['id'])?$data['id']:null)?>"/>
    <section class="wrapper image-wrapper bg-image bg-overlay bg-overlay-400 text-white" data-image-src="<?=THEME_IMG?>/bg-blue.jpg">
        <div class="container text-center pt-4 pb-16">
            <div class="row">
                <div class="col-md-10 col-xl-8 mx-auto">
                    <div class="post-header">
                        <h1 class="display-1 text-white mb-2"><?=( (App::lang()=='en') ? 'Cafeteria Requisition Form' : 'รายการเบิกภาชนะโรงอาหาร' )?></h1>
                        <div class="display-shop text-white fs-20 mb-5">
                            <span><i class="uil uil-university"></i> <?=( (App::lang()=='en') ? APP_FACT_EN : APP_FACT_TH )?></span>
                        </div>
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
                                <div class="alert alert-warning alert-icon" style="padding: 5px;">
                                    <div class="row gx-1">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                            <div class="form-floating">
                                                <input value="<?=((isset($data['shop_name'])&&$data['shop_name'])?$data['shop_name']:null)?>" type="text" class="form-control" placeholder="..." disabled style="background:white;">
                                                <label><?=Lang::get('Shop')?></label>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                            <div class="form-floating">
                                                <input value="<?=((isset($data['request_date'])&&$data['request_date'])?Helper::dateDisplay($data['request_date'], App::lang()):null)?>" type="text" class="form-control" placeholder="..." disabled style="background:white;">
                                                <label><?=Lang::get('Date')?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if( isset($lists)&&count($lists)>0 ){ ?>
                                    <table border="0" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="no">#</th>
                                                <th scope="col" class="name"><?=Lang::get('List')?></th>
                                                <th scope="col" class="price"><?=Lang::get('Price')?></th>
                                                <th scope="col" class="request"><?=Lang::get('QuantityRequest')?></th>
                                                <th scope="col" class="quantity"><?=Lang::get('QuantityActual')?></th>
                                                <th scope="col" class="amount"><?=Lang::get('Amount')?></th>
                                                <th scope="col" class="baht">&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach($lists as $seq => $item){ ?>
                                            <tr class="AT-<?=$item['id']?>">
                                                <td class="no"><?=($seq+1)?></td>
                                                <td class="name"><mark class="doc"><?=($seq+1)?></mark><?=$item['name']?></td>
                                                <td class="price"><?=(($item['price']>0)?number_format($item['price'],2):'-')?></td>
                                                <td class="request"><?=(($item['quantity']>0)?number_format($item['quantity'],0):'0')?></td>
                                                <td class="quantity">
                                                    <input type="hidden" name="list[<?=$seq?>][request_list_id]" value="<?=$item['id']?>"/>
                                                    <input type="hidden" name="list[<?=$seq?>][stock_id]" value="<?=$item['stock_id']?>"/>
                                                    <input type="hidden" name="list[<?=$seq?>][name]" value="<?=$item['name']?>"/>
                                                    <input type="hidden" name="list[<?=$seq?>][request]" value="<?=$item['quantity']?>"/>
                                                    <input type="number" name="list[<?=$seq?>][quantity]" value="<?=$item['quantity']?>" min="0" max="999" step="1" class="form-control set-quantity" placeholder="..." onkeyup="manage_events('change', { 'at':'<?=$item['id']?>' });" onchange="manage_events('change', { 'at':'<?=$item['id']?>' });"/>
                                                    <input type="hidden" name="list[<?=$seq?>][unit]" value="<?=$item['unit']?>" class="set-unit"/>
                                                    <input type="hidden" name="list[<?=$seq?>][price]" value="<?=$item['price']?>" class="set-price"/>
                                                    <input type="hidden" name="list[<?=$seq?>][amount]" value="<?=$item['amount']?>" class="set-amount"/>
                                                </td>
                                                <td class="amount"><?=(($item['amount']>0)?number_format($item['amount'],2):'0.00')?></td>
                                                <td class="baht"><span class="baht-lg"><?=Lang::get('Baht')?></span><span class="baht-sm">฿</span></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="no"></td>
                                                <td class="name" colspan="2"><?=Lang::get('GrandTotal')?></td>
                                                <td class="amount total-amount" colspan="3"><?=((isset($data['amount'])&&$data['amount']>0)?number_format($data['amount'],2):'0.00')?></td>
                                                <td class="baht"><span class="baht-lg"><?=Lang::get('Baht')?></span><span class="baht-sm">฿</span></td>
                                            </tr>
                                            <tr>
                                                <td class="no"></td>
                                                <td class="name" colspan="2"><?=Lang::get('Requester')?></td>
                                                <td class="amount" colspan="3"><?=((isset($data['requester'])&&$data['requester'])?$data['requester']:'-')?></td>
                                                <td class="baht last"><i class="uil uil-file-edit-alt"></i></td>
                                            </tr>
                                            <tr>
                                                <td class="no"></td>
                                                <td class="name" colspan="2"><?=Lang::get('Officer')?></td>
                                                <td class="amount" colspan="3"><?=User::get('fullname')?></td>
                                                <td class="baht last"><i class="uil uil-file-check-alt"></i></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                <?php } ?>
                                <hr class="mt-1 mb-1">
                                <div class="alert alert-warning alert-icon mb-0" style="padding:5px;">
                                    <div class="row gx-1">
                                        <div class="col-6">
                                            <div class="form-control bg-soft-green">
                                                <input class="form-check-input" type="radio" name="status" value="Y" checked style="cursor:pointer;" onchange="manage_events('status', { 'self':this });">
                                                <span class="fs-16 on-font-primary text-green"><?=( (App::lang()=='en') ? '<b>Accept</b>, <em>allow to use.</em>' : '<b>ยืนยัน</b>, <em>อนุญาตให้จ่ายภาชนะได้</em>' )?></span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-control bg-soft-red">
                                                <input class="form-check-input" type="radio" name="status" value="N" style="cursor:pointer;" onchange="manage_events('status', { 'self':this });">
                                                <span class="fs-16 on-font-primary text-red"><?=( (App::lang()=='en') ? '<b>Reject</b>, <em>not allow to use.</em>' : '<b>ปฏิเสธ</b>, <em>ไม่อนุญาตให้จ่ายภาชนะได้</em>' )?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-floating mt-1 mb-0">
                                        <input id="remark" name="remark" value="" type="text" class="form-control" placeholder="..." disabled>
                                        <label for="remark"><?=Lang::get('Remark')?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="manage-footer">
                        <div class="confirm-box"></div>
                        <div class="confirm-btn">
                            <button type="button" class="btn btn-lg btn-primary rounded" onclick="manage_events('confirm');"><i class="uil uil-check-circle"></i><?=Lang::get('Confirm')?></button>
                            <button type="button" class="btn btn-lg btn-navy rounded" onclick="manage_events('cancel');"><i class="uil uil-times-circle"></i><?=Lang::get('Back')?></button>
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
                if( isNaN(quantity) ){ quantity=0; }
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
            $("form[name='ManageForm'] table tfoot .total-amount").html(grand_total.format(2));
        }else if(action=='status'){
            manage_events('confirm', { 'on':'N' });
            if( params.self.value=='N' ){
                $("form[name='ManageForm'] input[name='remark']").val(null).removeAttr('disabled').focus();
            }else{
                $("form[name='ManageForm'] input[name='remark']").val(null).attr('disabled', true);
            }
        }else if(action=="confirm"){
            if( params!=undefined ){
                $("form[name='ManageForm'] .confirm-box").html('');
                $("form[name='ManageForm'] .confirm-btn").show();
            }else{
                var htmls  = '<div class="fs-20 mb-2 text-center on-text-normal on-text-oneline">';
                    if( document.ManageForm.status[0].checked ){
                        htmls += '<?=( (App::lang()=='en') ? 'Confirm to accept this request ?' : 'ยืนยันรายการเบิกภาชนะนี้ ใช่ หรือ ไม่ ?' )?>';
                    }else if( document.ManageForm.status[1].checked ){
                        htmls += '<?=( (App::lang()=='en') ? 'Confirm to <u class="text-red">reject</u> this request ?' : 'ยืนยัน<u class="text-red">ปฏิเสธ</u>รายการเบิกนี้ ใช่ หรือ ไม่ ?' )?>';
                    }
                    htmls += '</div>';
                    htmls += '<button type="submit" class="btn btn-icon btn-icon-start btn-success rounded-pill"><i class="uil uil-check-circle"></i><?=Lang::get('Yes')?></button>';
                    htmls += '&nbsp;';
                    htmls += '<button type="button" class="btn btn-icon btn-icon-start btn-outline-danger rounded-pill" onclick="manage_events(\'confirm\', { \'on\':\'N\' });"><i class="uil uil-times-circle"></i><?=Lang::get('No')?></button>';
                $("form[name='ManageForm'] .confirm-box").html(htmls);
                $("form[name='ManageForm'] .confirm-btn").hide();
            }
        }else if(action=="cancel"){
            document.location = '<?=APP_HOST?>/profile';
        }
    }
    $(document).ready(function() {
        $("form[name='ManageForm']").ajaxForm({
            beforeSubmit: function (formData, jqForm, options) {
                runStart();
            },
            success: function(rs) {
                runStop();
                var data = JSON.parse(rs);
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
                                document.location = '<?=APP_HOME?>/order/?lists';
                            }
                        }
                    );
                }else{
                    manage_events('confirm', { 'on':'N' });
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
        });
    });
</script>
<?php include(APP_FOOTER);?>