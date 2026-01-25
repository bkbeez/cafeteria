<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php Auth::ajax(APP_PATH.'/stock'); ?>
<?php
    $lang = App::lang();
    $form = ( (isset($_POST['form_as'])&&$_POST['form_as']) ? $_POST['form_as'] : null );
    $htmls = '';
    $lists = Stock::sql("SELECT stock.*
                        FROM stock
                        WHERE stock.status_id>=0
                        ORDER BY stock.sequence, stock.id;"
    );
    if( isset($lists)&&count($lists)>0 ){
        foreach($lists as $seq => $item){
            $htmls .= '<form name="saving" class="form-manage AT-'.$item['id'].'" action="'.$form.'/scripts/inventory/update.php" method="POST" enctype="multipart/form-data" target="_blank">';
                $htmls .= '<input type="hidden" name="id" value="'.$item['id'].'"/>';
                $htmls .= '<div class="card mb-2 lift">';
                    $htmls .= '<div class="card-body p-1">';
                        $htmls .= '<div class="row gx-1">';
                            $htmls .= '<div class="col-3 col-type">';
                                $htmls .= '<div class="seq-box">';
                                    $htmls .= '<div>'.Lang::get('NO.').'</div><span class="num"></span>';
                                $htmls .= '</div>';
                                $htmls .= '<div class="form-floating form-select-wrapper mb-0">';
                                    $htmls .= '<select name="type_id" class="form-select" onchange="record_events(\'update\', { \'id\':\''.$item['id'].'\' });">';
                                        $htmls .= Stock::typeOption(( (isset($item['type_id'])&&$item['type_id']) ? $item['type_id'] : null ));
                                    $htmls .= '</select>';
                                    $htmls .= '<label for="type">'.Lang::get('Type').'</label>';
                                $htmls .= '</div>';
                            $htmls .= '</div>';
                            $htmls .= '<div class="col-4 col-name">';
                                $htmls .= '<div class="form-floating mb-0">';
                                    $htmls .= '<input name="name" value="'.$item['name'].'" type="text" class="form-control" placeholder="..." onchange="record_events(\'update\', { \'id\':\''.$item['id'].'\' });">';
                                    $htmls .= '<label for="name">'.Lang::get('Name').' <span class="text-red">*</span></label>';
                                $htmls .= '</div>';
                            $htmls .= '</div>';
                            $htmls .= '<div class="col-2 col-unit">';
                                $htmls .= '<div class="form-floating mb-0">';
                                    $htmls .= '<input name="unit" value="'.$item['unit'].'" type="text" class="form-control" placeholder="..." onchange="record_events(\'update\', { \'id\':\''.$item['id'].'\' });">';
                                    $htmls .= '<label for="unit">'.Lang::get('Unit').'</label>';
                                $htmls .= '</div>';
                            $htmls .= '</div>';
                            $htmls .= '<div class="col-1 col-btns text-center toggle">';
                                $htmls .= '<i class="uil uil-toggle-'.( ($item['status_id']>0) ? 'on' : 'off' ).'" onclick="record_events(\'status\', { \'id\':\''.$item['id'].'\', \'self\':this });"></i>';
                                $htmls .= '<div class="toggle-label">OFF/ON</div>';
                            $htmls .= '</div>';
                            $htmls .= '<div class="col-1 col-btns text-center save">';
                                $htmls .= '<button type="submit" class="btn btn-icon btn-outline-success w-100"><i class="uil uil-save"></i><span>'.Lang::get('Save').'</span></button>';
                            $htmls .= '</div>';
                            $htmls .= '<div class="col-1 col-btns text-center dels">';
                                $htmls .= '<button type="button" '.( ($item['status_id']>0) ? 'class="btn btn-icon btn-soft-dark text-muted w-100" disabled' : 'class="btn btn-icon btn-outline-danger w-100"' ).' onclick="record_events(\'delete\', { \'id\':\''.$item['id'].'\' });"><i class="uil uil-trash"></i><span>'.Lang::get('Del').'</span></button>';
                            $htmls .= '</div>';
                        $htmls .= '</div>';
                    $htmls .= '</div>';
                $htmls .= '</div>';
            $htmls .= '</form>';
        }
    }else{
        $htmls .= '<div class="alert alert-empty alert-light alert-icon text-center text-ash mb-0" style="min-height:50px;padding:8px 8px 1px 8px;">- '.Lang::get('NotFoundResult').' -</div>';
    }
?>
<style type="text/css">
    .modal-dialog .modal-manage {
        background-image: url('<?=THEME_IMG?>/bg-blue.jpg');
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        position: relative;
        background-attachment: scroll;
    }
    .modal-dialog .modal-body {
        padding-left:35px;
        padding-right:35px;
    }
    .modal-dialog .modal-body.formlists {
        counter-reset: line;
    }
    .modal-dialog .modal-body.formlists form .num:before {
        counter-increment: line;
        content: counter(line);
    }
    .modal-dialog .modal-body.formlists .col-type {
        padding-left: 56px;
    }
    .modal-dialog .modal-body.formlists .col-type .seq-box {
        left: 5px;
        float: left;
        width: 50px;
        height: 50px;
        line-height: 22px;
        text-align: center;
        margin: 2px 0 0 0;
        position: absolute;
    }
    .modal-dialog .modal-body.formlists .col-type .seq-box>div {
        font-size: 13px;
    }
    .modal-dialog .modal-body.formlists .col-btns.toggle {
        padding-top: 5px;
    }
    .modal-dialog .modal-body.formlists .col-btns.toggle>i {
        color: #3f78e0;
        cursor: pointer;
        font-size: 45px;
        line-height: 42px;
    }
    .modal-dialog .modal-body.formlists .col-btns.toggle>i.uil-toggle-off {
        color: #aab0bc;
    }
    .modal-dialog .modal-body.formlists .col-btns.toggle .toggle-label {
        width: 100%;
        font-size: 12px;
        margin-top: -12px;
        line-height: 10px;
        text-align: center;
    }
    .modal-dialog .modal-body.formlists .col-btns.save>.btn:hover {
        color: white;
    }
    .modal-dialog .modal-body.formlists .col-btns>.btn>i {
        font-size: 28px;
        line-height: 28px;
    }
    .modal-dialog .modal-body.formlists .form-select,
    .modal-dialog .modal-body.formlists .form-control,
    .modal-dialog .modal-body.formlists .form-floating>label {
        padding-right: 3px!important;
    }
    .modal-dialog .modal-footer {
        min-height: 60px;
        padding-left: 30px;
        padding-right: 30px;
    }
    .modal-dialog .modal-footer .loading {
        float:left;
        position: absolute;
        line-height: 48px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .modal-dialog .modal-footer .loading img {
        height: 32px;
        margin-top: -5px;
    }
    .modal-dialog .modal-footer .loading i {
        float: left;
        font-size: 32px;
        line-height: 50px;
        margin-right: 3px;
    }
    .modal-dialog .modal-footer .loading b {
        font-size: 17px;
        line-height: 50px;
    }
    .modal-dialog .modal-footer button {
        padding-left: 8px;
        padding-right: 8px;
        text-align: center !important;
    }
    .modal-dialog .modal-footer button>i {
        float: left;
        font-size: 28px;
        line-height: 28px;
        margin-right: 3px;
    }
    @media only all and (max-width: 1200px) {
        .modal-dialog.modal-xl {
            --bs-modal-width: 94%;
        }
        .modal-dialog .modal-body.formlists .col-btns>.btn>span {
            font-size: 13px;
        }
    }
    @media only all and (max-width: 991px) {
        .modal-dialog .modal-body.formlists .col-type {
            width: 25%;
        }
        .modal-dialog .modal-body.formlists .col-name {
            width: 30%;
        }
        .modal-dialog .modal-body.formlists .col-unit {
            width: 15%;
        }
        .modal-dialog .modal-body.formlists .col-btns {
            width: 10%;
        }
        .modal-dialog .modal-body.formlists .col-btns>.btn>span {
            font-size: 11px;
        }
    }
    @media only all and (max-width: 768px) {
        .modal-dialog .modal-body.formlists .col-type {
            width: 40%;
        }
        .modal-dialog .modal-body.formlists .col-name {
            width: 60%;
        }
        .modal-dialog .modal-body.formlists .col-unit {
            width: 40%;
            padding-top: 5px;
        }
        .modal-dialog .modal-body.formlists .col-btns {
            width: 20%;
            padding-top: 5px;
        }
        .modal-dialog .modal-body.formlists .col-btns>.btn>span {
            font-size: 15px;
        }
    }
    @media only all and (max-width: 667px) {
        .modal-dialog.modal-xl {
            --bs-modal-width: 98%;
        }
        .modal-dialog .modal-body.formlists .col-type {
            width: 100%;
        }
        .modal-dialog .modal-body.formlists .col-name {
            width: 100%;
            padding-top: 5px;
        }
        .modal-dialog .modal-body.formlists .col-unit {
            width: 40%;
        }
        .modal-dialog .modal-body.formlists .col-btns {
            width: 20%;
        }
    }
    @media only all and (max-width: 414px) {
        .modal-dialog .modal-footer button>span {
            display: none;
        }
    }
</style>
<div class="modal-dialog modal-custom-bg modal-xl">
    <div class="modal-content modal-manage">
        <div class="modal-header" style="min-height:105px;">
            <button type="button" class="btn-close text-white" onclick="record_events('close');"></button>
            <h2 class="mb-0 text-white text-start on-text-oneline"><i class="uil uil-book-medical" style="float:left;font-size:45px;line-height:42px;margin-right:3px;"></i> <?=( (App::lang()=='en') ? 'Asset Lists' : 'รายการทรัพย์สิน' )?></h2>
        </div>
        <div class="modal-body" style="margin-top:-30px;"></div>
        <div class="modal-body formlists" style="padding-top:5px;"><?=$htmls?></div>
        <div class="modal-body finished" style="margin-bottom:-30px;"></div>
        <div class="modal-footer text-end">
            <form name="CreateForm" action="<?=$form?>/scripts/inventory/create.php" method="POST" enctype="multipart/form-data" class="form-manage mt-2" target="_blank">
                <input type="hidden" name="form_as" value="<?=$form?>" />
                <div class="loading"></div>
                <button type="submit" class="btn btn-primary rounded"><i class="uil uil-plus-circle"></i><span><?=( (App::lang()=='en') ? 'Create New' : 'เพิ่มรายการ' )?></span></button>
                <button type="button" class="btn btn-success rounded" onclick="record_events('close');"><i class="uil uil-check-circle"></i><span><?=Lang::get('Finish')?></span></button>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    function record_events(action, params) {
        if(action=='update'){
            $("form[name='saving'].AT-"+params.id+" button[type='submit']").click();
        }else if(action=="status"){
            if( $(params.self).hasClass('uil-toggle-on') ){
                $.ajax({
                    url : "<?=$form?>/scripts/inventory/status.php",
                    type: 'POST',
                    data: { 'id':params.id, 'status':'N' },
                    dataType: "json",
                    beforeSend: function( xhr ) {
                        $("form[name='saving'].AT-"+params.id+" button[type='submit']").html('<i class="uil uil-save"></i>');
                    }
                }).done(function(data) {
                    if(data.status=='success'){
                        $(params.self).attr('class','uil uil-toggle-off');
                        $("form[name='saving'].AT-"+data.id+" .dels>button").attr('class','btn btn-icon btn-outline-danger w-100').removeAttr('disabled');
                    }else{
                        $("form[name='saving'].AT-"+params.id+" .card-body").after('<div class="on-form-status text-end text-red">'+data.text+'</div>');
                    }
                });
            }else{
                $.ajax({
                    url : "<?=$form?>/scripts/inventory/status.php",
                    type: 'POST',
                    data: { 'id':params.id, 'status':'Y' },
                    dataType: "json",
                    beforeSend: function( xhr ) {
                        $("form[name='saving'].AT-"+params.id+" button[type='submit']").html('<i class="uil uil-save"></i>');
                    }
                }).done(function(data) {
                    if(data.status=='success'){
                        $(params.self).attr('class','uil uil-toggle-on');
                        $("form[name='saving'].AT-"+data.id+" .dels>button").attr({'class':'btn btn-icon btn-soft-dark text-muted w-100', 'disabled':true});
                    }else{
                        $("form[name='saving'].AT-"+params.id+" .card-body").after('<div class="on-form-status text-end text-red">'+data.text+'</div>');
                    }
                });
            }
        }else if(action=="delete"){
            if( params.no!=undefined ){
                $("form[name='saving'].AT-"+params.id+" .on-form-status").remove();
            }else{
                var htmls = '<div class="on-form-status text-end text-red">';
                        htmls += '<font class="fs-sm"><?=Lang::get('ConfirmToDelete')?></font>';                    
                        htmls += '<button type="submit" class="btn btn-sm btn-success rounded" style="padding:0 10px;margin-left:3px;"><?=Lang::get('Yes')?></button>';
                        htmls += '<button type="button" class="btn btn-sm btn-outline-danger rounded" onclick="record_events(\'delete\', { \'on\':\'N\' });" style="padding:0 10px;margin-left:3px;"><?=Lang::get('No')?></button>';
                        htmls += '<input type="hidden" name="delete" value="Y"/>';
                    htmls += '</div>';
                $("form[name='saving'].AT-"+params.id+" .card-body").append(htmls);
            }
        }else if(action=="close"){
            $("#ManageDialog").modal('hide');
            $("form[name='filter'] input[name='state']").val(null);
            $("form[name='filter'] button[type='submit']").click();
        }
    }
    $(document).ready(function() {
        $("form[name='CreateForm']").ajaxForm({
            beforeSubmit: function (formData, jqForm, options) {
                $("form[name='CreateForm'] .loading").html('<img src="<?=THEME_IMG?>/o-loading.gif"/> <b class="text-yellow"><?=Lang::get('Processing')?>.. .. .</b>');
            },
            success: function(rs) {
                var newdata = JSON.parse(rs);
                if(newdata.status=='success'){
                    $("form[name='CreateForm'] .loading").html('<span class="form-on-loading"><i class="uil uil-check-circle text-white"></i> <b class="text-white">'+newdata.text+'</b></span>');
                    $(".modal-dialog .modal-body.formlists").append(newdata.htmls);
                    $("form[name='saving'].AT-"+newdata.id).ajaxForm({
                        beforeSubmit: function (formData, jqForm, options) {
                            $("form[name='saving'].AT-"+formData[0].value+" label>em").remove();
                            $("form[name='saving'].AT-"+formData[0].value+" .on-form-status").remove();
                            $("form[name='saving'].AT-"+formData[0].value+" button[type='submit']").html('<img src="<?=THEME_IMG?>/o-loading.gif" style="height:28px;"/>');
                        },
                        success: function(rs) {
                            var data = JSON.parse(rs);
                            if(data.status=='success'){
                                if(data.delete!=undefined){
                                    $("form[name='saving'].AT-"+data.id).fadeOut(function(){
                                        $(this).remove();
                                    });
                                }else{
                                    $("form[name='saving'].AT-"+data.id+" button[type='submit']").html('<font><?=Lang::get('Save')?></font>');
                                    $("form[name='saving'].AT-"+data.id+" button[type='submit']>font").fadeOut(function(){
                                        $("form[name='saving'].AT-"+data.id+" button[type='submit']").html('<i class="uil uil-save"></i>');
                                    });
                                }
                            }else{
                                $("form[name='saving'].AT-"+data.id+" button[type='submit']").html('<font><?=Lang::get('Fail')?></font>');
                                $("form[name='saving'].AT-"+data.id+" button[type='submit']>font").fadeOut(function(){
                                    $("form[name='saving'].AT-"+data.id+" button[type='submit']").html('<i class="uil uil-save"></i>');
                                });
                                if(data.onfocus!=undefined&&data.onfocus){
                                    $("form[name='saving'].AT-"+data.id+" input[name='"+data.onfocus+"']").focus();
                                    $("form[name='saving'].AT-"+data.id+" label[for='"+data.onfocus+"']").append('<em class="fs-12 text-red">'+data.text+'</em>');
                                }else{
                                    $("form[name='saving'].AT-"+data.id+" .card-body").after('<div class="on-form-status text-end text-red">'+data.text+'</div>');
                                }
                            }
                        }
                    });
                    $("form[name='CreateForm'] .loading>.form-on-loading").fadeOut(function(){
                        $(this).remove();
                    });
                }else{
                    $("form[name='CreateForm'] .loading").html('<i class="uil uil-check-circle text-red"></i> <b class="text-red">'+newdata.text+'</b>');
                }
            }
        });
        $("form[name='saving']").ajaxForm({
            beforeSubmit: function (formData, jqForm, options) {
                $("form[name='saving'].AT-"+formData[0].value+" label>em").remove();
                $("form[name='saving'].AT-"+formData[0].value+" .on-form-status").remove();
                $("form[name='saving'].AT-"+formData[0].value+" button[type='submit']").html('<img src="<?=THEME_IMG?>/o-loading.gif" style="height:28px;"/>');
            },
            success: function(rs) {
                var data = JSON.parse(rs);
                if(data.status=='success'){
                    if(data.delete!=undefined){
                        $("form[name='saving'].AT-"+data.id).fadeOut(function(){
                            $(this).remove();
                        });
                    }else{
                        $("form[name='saving'].AT-"+data.id+" button[type='submit']").html('<font><?=Lang::get('Save')?></font>');
                        $("form[name='saving'].AT-"+data.id+" button[type='submit']>font").fadeOut(function(){
                            $("form[name='saving'].AT-"+data.id+" button[type='submit']").html('<i class="uil uil-save"></i>');
                        });
                    }
                }else{
                    $("form[name='saving'].AT-"+data.id+" button[type='submit']").html('<font><?=Lang::get('Fail')?></font>');
                    $("form[name='saving'].AT-"+data.id+" button[type='submit']>font").fadeOut(function(){
                        $("form[name='saving'].AT-"+data.id+" button[type='submit']").html('<i class="uil uil-save"></i>');
                    });
                    if(data.onfocus!=undefined&&data.onfocus){
                        $("form[name='saving'].AT-"+data.id+" input[name='"+data.onfocus+"']").focus();
                        $("form[name='saving'].AT-"+data.id+" label[for='"+data.onfocus+"']").append('<em class="fs-12 text-red">'+data.text+'</em>');
                    }else{
                        $("form[name='saving'].AT-"+data.id+" .card-body").after('<div class="on-form-status text-end text-red">'+data.text+'</div>');
                    }
                }
            }
        });
    });
</script>