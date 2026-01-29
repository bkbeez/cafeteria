<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/request'); ?>
<?php
    $form = ( (isset($_POST['form_as'])&&$_POST['form_as']) ? $_POST['form_as'] : null );
    if( isset($_POST['id'])&&$_POST['id'] ){
        $data = DB::one("SELECT request.*
                        FROM request
                        WHERE request.id=:id
                        LIMIT 1;"
                        , array('id'=>$_POST['id'])
        );
        $lists = Stock::sql("SELECT request_list.*
                        FROM request_list
                        WHERE request_list.request_id=:id;
                        ORDER BY request_list.id;"
                        , array('id'=>$_POST['id'])
        );
    }
?>
<style type="text/css">
    .modal-dialog .modal-header {
        min-height: 125px;
        background: #f5f5f7;
    }
    .modal-dialog .modal-header .subtitle {
        padding-left:40px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .modal-dialog .modal-body {
        margin-top: -30px;
        padding-left: 35px;
        padding-right: 35px;
        padding-bottom: 15px;
    }
    .modal-dialog .modal-body .bill-label {
        width: 50px;
        color:white;
        text-align: center;
        background:#343f53;
        display: inline-block;
    }
    .modal-dialog .modal-body>.alert {
        padding: 10px 15px 5px 15px;
    }
    .modal-dialog .modal-body table {
        width: 100%;
        font-size: 14px;
        margin: 0 0 15px 0;
        table-layout: fixed;
        border-collapse: collapse;
    }
    .modal-dialog .modal-body table .no {
        width: 40px;
        text-align: center;
    }
    .modal-dialog .modal-body table .name {
        width: auto;
        text-align: left;
    }
    .modal-dialog .modal-body table .price {
        width: 70px;
        text-align: right;
    }
    .modal-dialog .modal-body table .quantity {
        width: 15%;
        text-align: right;
    }
    .modal-dialog .modal-body table .amount {
        width: 15%;
        text-align: right;
    }
    .modal-dialog .modal-body table .baht {
        width: 45px;
        text-align: center;
    }
    .modal-dialog .modal-body table .baht .baht-sm {
        display: none;
    }
    .modal-dialog .modal-body table thead tr th {
        vertical-align: top;
        padding: 2px 5px 3px 0;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        border-bottom:1px solid #343f53;
    }
    .modal-dialog .modal-body table tbody tr td {
        vertical-align: top;
        padding: 2px 5px 0 0;
    }
    .modal-dialog .modal-body table tfoot tr td {
        font-weight: bold;
        vertical-align: top;
        padding: 1px 5px 0 0;
        border-bottom-color: white;
    }
    .modal-dialog .modal-body table tfoot tr:first-child td{
        border-top:1px solid #343f53;
    }
    .modal-dialog .modal-body table tfoot tr td.name {
        text-align: right;
    }
    .modal-dialog .modal-body table tfoot tr td.last {
        padding: 15px 0 0 0;
        text-align: left;
    }
    @media only all and (max-width: 991px) {
        .modal-dialog.modal-lg {
            --bs-modal-width: 90%;
        }
        .modal-dialog .modal-body table .quantity {
            width: 12%;
        }
    }
    @media only all and (max-width: 667px) {
        .modal-dialog.modal-lg {
            --bs-modal-width: 98%;
        }
        .modal-dialog .modal-body table .price {
            width: 40px;
        }
        .modal-dialog .modal-body table .amount {
            width: 20%;
        }
        .modal-dialog .modal-body table .baht {
            width: 15px;
        }
        .modal-dialog .modal-body table .baht .baht-lg {
            display: none;
        }
        .modal-dialog .modal-body table .baht .baht-sm {
            display: block;
        }
    }
    @media only all and (max-width: 500px) {
        .modal-dialog .modal-body table .no {
            width: 20px;
        }
        .modal-dialog .modal-body table tfoot tr td.name {
            text-align: left;
            padding-left: 3px;
        }
    }
</style>
<div class="modal-dialog modal-lg">
    <div class="modal-content modal-manage">
        <div class="form-manage">
            <input type="hidden" name="id" value="<?=((isset($data['id'])&&$data['id'])?$data['id']:null)?>"/>
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h2 class="mb-0 text-dark text-start on-text-oneline"><i class="uil uil-file-alt" style="float:left;font-size:36px;line-height:36px;margin-right:3px;"></i><?=( (App::lang()=='en') ? 'Cafeteria Requisition Form' : 'รายการเบิกภาชนะโรงอาหาร' )?></h2>
                <div class="subtitle"><?=( (App::lang()=='en') ? APP_FACT_EN : APP_FACT_TH )?></div>
            </div>
            <div class="modal-body">
                <div class="alert alert-secondary alert-icon mb-2">
                    <div class="row gx-1">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 pb-1">
                            <mark class="bill-label"><?=Lang::get('Shop')?></mark>
                            <?=((isset($data['shop_name'])&&$data['shop_name'])?$data['shop_name']:'-')?>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 pb-1">
                            <mark class="bill-label"><?=Lang::get('Date')?></mark>
                            <?=((isset($data['request_date'])&&$data['request_date'])?Helper::dateDisplay($data['request_date'], App::lang()):'-')?>
                        </div>
                    </div>
                </div>
                <?php if( isset($lists)&&count($lists)>0 ){ ?>
                    <table border="0" class="table table-borderless">
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
                                <td class="price"><?=(($item['price']>0)?number_format($item['price'],2):'-')?></td>
                                <td class="quantity"><?=(($item['quantity']>0)?number_format($item['quantity'],0):'0')?></td>
                                <td class="amount"><?=(($item['amount']>0)?number_format($item['amount'],2):'0.00')?></td>
                                <td class="baht"><span class="baht-lg"><?=Lang::get('Baht')?></span><span class="baht-sm">฿</span></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="name" colspan="2"><?=Lang::get('GrandTotal')?></td>
                                <td class="amount" colspan="3"><?=((isset($data['amount'])&&$data['amount']>0)?number_format($data['amount'],2):'0.00')?></td>
                                <td class="baht"><span class="baht-lg"><?=Lang::get('Baht')?></span><span class="baht-sm">฿</span></td>
                            </tr>
                            <tr>
                                <td class="name" colspan="2"><?=Lang::get('Requester')?></td>
                                <td class="amount" colspan="3"><?=User::get('fullname')?></td>
                                <td class="baht">&nbsp;</td>
                            </tr>
                        </tfoot>
                    </table>
                <?php } ?>
            </div>
        </div>
    </div>
</div>