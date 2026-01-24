<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/shops'); ?>
<?php
    $lang = App::lang();
    $form = ( (isset($_POST['form_as'])&&$_POST['form_as']) ? $_POST['form_as'] : null );
    $rolehtmls = '';
    if( isset($_POST['id'])&&$_POST['id'] ){
        $data = Shop::one("SELECT shop.*
                        FROM shop
                        WHERE shop.id=:id
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
<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content modal-manage">
        <form name="RecordForm" action="<?=$form?>/scripts/update.php" method="POST" enctype="multipart/form-data" class="form-manage" target="_blank">
            <input type="hidden" name="id" value="<?=((isset($data['id'])&&$data['id'])?$data['id']:null)?>"/>
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h2 class="mb-0 text-primary text-start on-text-oneline"><i class="uil uil-edit" style="float:left;font-size:36px;line-height:36px;margin-right:3px;"></i><?=( (App::lang()=='en') ? 'Edit Shop' : 'แก้ไขร้าน' )?></h2>
            </div>
            <div class="modal-body">
                <div class="alert alert-primary alert-icon mb-2">
                    <div class="form-floating form-select-wrapper mb-1">
                        <select name="type_id" class="form-select"><?=Shop::typeOption(((isset($data['type_id'])&&$data['type_id'])?$data['type_id']:null));?></select>
                        <label><?=Lang::get('Type')?></label>
                    </div>
                    <div class="form-floating mb-1">
                        <input id="shop_name" name="shop_name" value="<?=((isset($data['shop_name'])&&$data['shop_name'])?$data['shop_name']:null)?>" type="text" class="form-control" placeholder="...">
                        <label for="shop_name"><?=Lang::get('ShopName')?> <span class="text-red">*</span></label>
                    </div>
                </div>
                <div class="alert alert-primary alert-icon mb-2">
                    <p class="lead text-dark mb-1 text-start on-text-oneline"><?=Lang::get('ShopOwner')?></p>
                    <div class="row gx-1">
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-2">
                            <div class="form-floating mb-1">
                                <input id="title" name="title" value="<?=((isset($data['title'])&&$data['title'])?$data['title']:null)?>" type="text" class="form-control" placeholder="...">
                                <label for="title"><?=Lang::get('NameTitle')?></label>
                            </div>
                        </div>
                        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-4">
                            <div class="form-floating mb-1">
                                <input id="name" name="name" value="<?=((isset($data['name'])&&$data['name'])?$data['name']:null)?>" type="text" class="form-control" placeholder="...">
                                <label for="name"><?=Lang::get('NameFirst')?> <span class="text-red">*</span></label>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                            <div class="form-floating mb-1">
                                <input id="surname" name="surname" value="<?=((isset($data['surname'])&&$data['surname'])?$data['surname']:null)?>" type="text" class="form-control" placeholder="...">
                                <label for="surname"><?=Lang::get('NameLast')?></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-1">
                        <input id="phone" name="phone" value="<?=((isset($data['phone'])&&$data['phone'])?$data['phone']:null)?>" type="text" class="form-control" placeholder="...">
                        <label for="phone"><?=Lang::get('Phone')?></label>
                    </div>
                </div>
                <div class="alert alert-primary alert-icon mb-2">
                    <p class="lead text-dark mb-1 text-start on-text-oneline"><?=Lang::get('Status')?></p>
                    <div class="row gx-1">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 mx-auto">
                            <div class="form-check mb-2">
                                <input id="status_1" class="form-check-input" type="radio" name="status" value="Y"<?=((isset($data['status_id'])&&$data['status_id']>0)?' checked':null)?>>
                                <label for="status_1" class="form-check-label form-payslip-select text-dark"><?=( (App::lang()=='en') ? 'Available' : 'พร้อมใช้งาน' )?></label>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 mx-auto">
                            <div class="form-check mb-2">
                                <input id="status_2" class="form-check-input" type="radio" name="status" value="N"<?=((isset($data['status_id'])&&$data['status_id']<=0)?' checked':null)?>>
                                <label for="status_2" class="form-check-label form-payslip-select text-dark"><?=( (App::lang()=='en') ? '<u class="text-red">Not</u> available' : '<u class="text-red">ระงับ</u>ใช้งาน' )?></label>
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
        if(action=='cmu'){
            if( params.self.value=='Y' ){
                $("form[name='RecordForm'] input[name='email_cmu']").removeAttr('disabled');
            }else{
                $("form[name='RecordForm'] input[name='email_cmu']").val(null).attr('disabled', true);
            }
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