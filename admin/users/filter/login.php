<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/admin'); ?>
<?php
    $form = ( (isset($_POST['form_as'])&&$_POST['form_as']) ? $_POST['form_as'] : null );
    $rolehtmls = '';
    if( (isset($_POST['id'])&&$_POST['id'])&&(isset($_POST['email'])&&$_POST['email']) ){
        $data = DB::one("SELECT member.*
                        FROM member
                        WHERE member.id=:id AND member.email=:email
                        LIMIT 1;"
                        , array('id'=>$_POST['id'], 'email'=>$_POST['email'])
        );
    }
    if( Auth::admin() ){
        $rolehtmls .= '<div class="form-floating form-select-wrapper mb-1">';
            $rolehtmls .= '<select id="role" name="role" class="form-select" aria-label="..." required>';
                $rolehtmls .= Util::memberRoleOption(true, ((isset($data['role'])&&$data['role'])?$data['role']:null));
            $rolehtmls .= '</select>';
            $rolehtmls .= '<label for="role">'.Lang::get('UserAccount').' <span class="text-red">*</span></label>';
        $rolehtmls .= '</div>';
    }else{
        if(isset($data['role'])&&$data['role']=='ADMIN'){
            $rolehtmls .= '<div class="form-floating mb-1">';
                $rolehtmls .= '<input type="hidden" name="role" value="'.((isset($data['role'])&&$data['role'])?$data['role']:null).'"/>';
                $rolehtmls .= '<input id="role" value="'.Util::memberRoleName($data['role']).'" type="text" class="form-control" placeholder="..." disabled>';
                $rolehtmls .= '<label for="role">'.Lang::get('UserAccount').' <span class="text-red">*</span></label>';
            $rolehtmls .= '</div>';
        }else{
            $rolehtmls .= '<div class="form-floating form-select-wrapper mb-1">';
                $rolehtmls .= '<select id="role" name="role" class="form-select" aria-label="..." required>';
                    $rolehtmls .= Util::memberRoleOption(false, ((isset($data['role'])&&$data['role'])?$data['role']:null));
                $rolehtmls .= '</select>';
                $rolehtmls .= '<label for="role">'.Lang::get('UserAccount').' <span class="text-red">*</span></label>';
            $rolehtmls .= '</div>';
        }
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
        <form name="RecordForm" action="<?=$form?>/scripts/setlogin.php" method="POST" enctype="multipart/form-data" class="form-manage" target="_blank">
            <input type="hidden" name="id" value="<?=((isset($data['id'])&&$data['id'])?$data['id']:null)?>"/>
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h2 class="mb-0 text-primary text-start on-text-oneline"><i class="uil uil-lock-alt" style="float:left;font-size:36px;line-height:36px;margin-right:3px;"></i><?=( (App::lang()=='en') ? 'User and Password' : 'ผู้ใช้และรหัสผ่าน' )?></h2>
            </div>
            <div class="modal-body">
                <div class="alert alert-primary alert-icon mb-2">
                    <div class="form-floating mb-1">
                        <input id="email" name="email" value="<?=((isset($data['email'])&&$data['email'])?$data['email']:null)?>" type="email" class="form-control" placeholder="..." readonly>
                        <label for="email"><?=Lang::get('Email')?> <span class="text-red">*</span></label>
                    </div>
                </div>
                <div class="alert alert-danger alert-icon mb-2 pt-3">
                    <div class="row gx-1">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 mx-auto">
                            <div class="form-check mb-2">
                                <input id="pass_1" class="form-check-input" type="radio" name="is_pass" value="A" checked onchange="record_events('password', { 'self':this });">
                                <label for="pass_1" class="form-check-label form-payslip-select text-dark"><?=( (App::lang()=='en') ? 'Login with normally' : 'เข้าสู่ระบบด้วยวิธีปกติ' )?></label>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 mx-auto">
                            <div class="form-check mb-2">
                                <input id="pass_2" class="form-check-input" type="radio" name="is_pass" value="N" onchange="record_events('password', { 'self':this });">
                                <label for="pass_2" class="form-check-label form-payslip-select text-dark"><?=( (App::lang()=='en') ? 'Reset the password.' : 'รีเซตรหัสที่กำหนดไว้' )?></label>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mx-auto">
                            <div class="form-check mb-2">
                                <input id="pass_3" class="form-check-input" type="radio" name="is_pass" value="Y" onchange="record_events('password', { 'self':this });">
                                <label for="pass_3" class="form-check-label form-payslip-select text-dark"><?=( (App::lang()=='en') ? 'Login with this password' : 'กำหนดให้บัญชีนี้เข้าสู่ระบบด้วยรหัสผ่าน &darr;' )?></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-1">
                        <input id="password_default" name="password_default" value="" type="text" class="form-control" placeholder="..." disabled>
                        <label for="password_default"><?=Lang::get('PasswordDefault')?></label>
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
        if(action=='password'){
            if( params.self.value=='Y' ){
                $("form[name='RecordForm'] input[name='password_default']").removeAttr('disabled').focus();
            }else{
                $("form[name='RecordForm'] input[name='password_default']").val(null).attr('disabled', true);
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