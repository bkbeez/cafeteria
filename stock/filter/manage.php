<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/stock'); ?>
<?php
    $lang = App::lang();
    $form = ( (isset($_POST['form_as'])&&$_POST['form_as']) ? $_POST['form_as'] : null );
    if( isset($_POST['id'])&&$_POST['id'] ){
        $data = Stock::one("SELECT stock.*
                        , (stock.total-stock.expose) AS balance
                        FROM stock
                        WHERE stock.id=:id
                        LIMIT 1;"
                        , array('id'=>$_POST['id'])
        );
    }
?>
<style type="text/css">
    .modal-dialog .modal-header {
        min-height:100px;
        background: #eaf0fa;
    }
    .modal-dialog .modal-body {
        margin-top: -30px;
        padding-left: 35px;
        padding-right: 35px;
    }
    .modal-dialog .modal-body>.alert {
        padding: 5px 15px;
    }
    .modal-dialog .modal-footer button>i {
        float: left;
        font-size: 28px;
        line-height: 28px;
        margin-right: 3px;
    }
</style>
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-manage">
        <form name="RecordForm" action="<?=$form?>/scripts/change.php" method="POST" enctype="multipart/form-data" class="form-manage" target="_blank">
            <input type="hidden" name="id" value="<?=((isset($data['id'])&&$data['id'])?$data['id']:null)?>"/>
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h2 class="mb-0 text-primary text-start on-text-oneline"><i class="uil uil-edit" style="float:left;font-size:36px;line-height:36px;margin-right:3px;"></i><?=((isset($data['name'])&&$data['name'])?$data['name']:( (App::lang()=='en') ? 'Edit Item' : 'แก้ไขรายการ' ))?></h2>
            </div>
            <div class="modal-body">
                <div class="alert alert-primary alert-icon mb-2">
                    <div class="row gx-1">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-floating mb-1">
                                <input id="charge" name="charge" value="<?=((isset($data['charge'])&&$data['charge'])?$data['charge']:null)?>" type="text" class="form-control" placeholder="...">
                                <label for="charge"><?=Lang::get('Charge')?></label>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-floating mb-1">
                                <input id="unit" name="unit" value="<?=((isset($data['unit'])&&$data['unit'])?$data['unit']:null)?>" type="text" class="form-control" placeholder="...">
                                <label for="unit"><?=Lang::get('Unit')?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-primary alert-icon mb-2">
                    <p class="lead text-dark mb-1 text-start on-text-oneline"><?=( (App::lang()=='en') ? 'Stock Management' : 'ทรัพย์สินคงคลัง' )?></p>
                    <div class="form-floating mb-1">
                        <input id="total" name="total" value="<?=((isset($data['total'])&&$data['total'])?$data['total']:0)?>" type="number" class="form-control" placeholder="..." onchange="record_events('change');">
                        <label for="total"><?=Lang::get('Inventory')?></label>
                    </div>
                    <div class="row gx-1">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-floating mb-1">
                                <input id="expose" name="expose" value="<?=((isset($data['expose'])&&$data['expose'])?$data['expose']:0)?>" type="number" class="form-control" placeholder="..." onchange="record_events('change');">
                                <label for="expose"><?=Lang::get('Expose')?></label>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-floating mb-1">
                                <input id="balance" name="balance" value="<?=((isset($data['balance'])&&$data['balance'])?$data['balance']:0)?>" type="number" class="form-control" placeholder="..." readonly>
                                <label for="balance"><?=Lang::get('Balance')?></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-center">
                <div class="confirm-box"></div>
                <div class="row gx-1 row-button">
                    <div class="col-lg-6 col-md-6 pt-1">
                        <button type="button" class="btn btn-lg btn-green rounded-pill w-100" onclick="record_events('confirm');"><i class="uil uil-save"></i><?=Lang::get('Save')?></button>
                    </div>
                    <div class="col-lg-6 col-md-6 pt-1">
                        <button type="button" class="btn btn-lg btn-outline-danger rounded-pill w-100" data-bs-dismiss="modal"><i class="uil uil-cancel" style="font-size:35px;"></i><?=Lang::get('Discard')?></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    function record_events(action, params){
        $("form[name='RecordForm'] label>em").remove();
        if(action=='change'){
            var total = parseFloat($("form[name='RecordForm'] input[name='total']").val());
            if(isNaN(total)) total = 0;
            var expose = parseFloat($("form[name='RecordForm'] input[name='expose']").val());
            if(isNaN(expose)) expose = 0;
            var balance = total-expose;
            $("form[name='RecordForm'] input[name='balance']").val(balance);
        }else if(action=="confirm"){
            if( params!=undefined ){
                $("form[name='RecordForm'] .confirm-box").html('').css('margin-top','0');
                $("form[name='RecordForm'] .row-button").show();
            }else{
                var htmls  = '<div class="fs-19 mb-2 text-center on-text-normal"><?=Lang::get('ConfirmToUpdate')?></div>';                    
                    htmls += '<button type="submit" class="btn btn-lg btn-icon btn-icon-start btn-success rounded-pill"><i class="uil uil-check-circle"></i><?=Lang::get('Yes')?></button>';
                    htmls += '&nbsp;';
                    htmls += '<button type="button" class="btn btn-lg btn-icon btn-icon-start btn-outline-danger rounded-pill" onclick="record_events(\'confirm\', { \'on\':\'N\' });"><i class="uil uil-times-circle"></i><?=Lang::get('No')?></button>';
                $("form[name='RecordForm'] .confirm-box").html(htmls).css('margin-top','-15px');
                $("form[name='RecordForm'] .row-button").hide();
            }
        }
    }
    $(document).ready(function() {
        $("form[name='RecordForm']").ajaxForm({
            beforeSubmit: function (formData, jqForm, options) {
                $("form[name='RecordForm'] label>em").remove();
                runStart();
            },
            success: function(rs) {
                runStop();
                var data = JSON.parse(rs);
                if(data.status=='success'){
                    $("form[name='RecordForm'] .modal-header, form[name='RecordForm'] .modal-body, hr").hide();
                    var htmls ='<div class="d-flex flex-row text-start" style="margin-top:15px;">';
                        htmls +='<div style="margin-top:-5px;"><span class="icon btn btn-circle btn-lg btn-success pe-none me-4"><i class="uil uil-check"></i></span></div>';
                        htmls +='<div class="text-primary" style="font-weight:normal;">';
                            htmls +='<h4 class="mb-0 text-green on-text-oneline on-font-primary" style="color:#3a2e74;">'+data.title+'</h4>';
                            htmls +='<p class="fs-14 text-green">'+data.text+'</p>';
                        htmls +='</div>';
                    htmls +='</div>';
                    $("form[name='RecordForm'] .modal-footer").html(htmls);
                    $("form[name='RecordForm'] .modal-footer").fadeOut(1000, function(){
                        $("#ManageDialog").modal('hide');
                        $("form[name='filter'] input[name='state']").val(null);
                        $("form[name='filter'] button[type='submit']").click();
                    });
                }else{
                    if(data.onfocus!=undefined&&data.onfocus){
                        $("form[name='RecordForm'] input[name='"+data.onfocus+"']").focus();
                        $("form[name='RecordForm'] label[for='"+data.onfocus+"']").append('<em class="fs-12 text-red">'+data.text+'</em>');
                    }else{
                        $("form[name='RecordForm'] .confirm-box>div").html('<span class="text-red">'+data.text+'</span>');
                    }
                }
            }
        });
    });
</script>