<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php
    $index['customheader'] = true;
    $index['page'] = 'profile';
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
    }
?>
<?php include(APP_HEADER);?>
<style type="text/css">
    .card-profile .tax-number {
        margin: 0;
        padding-top: 0;
        padding-left: 0;
        padding-bottom: 0;
        overflow: hidden;
        border:1px solid #4079e0;
    }
    .card-profile .tax-number>span {
        margin-right: 3px;
        padding-left: 3px;
        padding-right: 3px;
    }
</style>
<section class="wrapper image-wrapper bg-image bg-overlay bg-overlay-400 text-white" data-image-src="<?=THEME_IMG?>/bg-blue.jpg">
    <div class="container pt-18 pb-8"></div>
</section>
<section class="wrapper">
    <div class="container pb-11">
        <div class="row">
            <div class="col-xl-12 mx-auto mt-n19">
                <div class="card card-profile">
                <?php if( $shop_id ){ ?>
                    <div class="row gx-0">
                        <div class="col-lg-6 image-wrapper bg-image bg-cover rounded-top rounded-lg-start d-md-block" data-image-src="<?=THEME_IMG?>/shop.png"></div>
                        <div class="col-lg-6">
                            <div class="p-5 p-md-6 p-lg-7">
                                <div class="d-flex flex-row mt-3">
                                    <div><div class="icon me-4 mt-n3" style="font-size:32px;"><i class="uil uil-shop"></i></div></div>
                                    <div>
                                        <h5 class="mb-0">
                                            <?=Lang::get('Shop')?>
                                            <button type="button" class="btn btn-sm btn-soft-primary rounded-pill" style="padding:3px 6px 3px 4px;line-height:14px;" onclick="profile_events('shop', { 'on':'name' });"><i class="uil uil-pen"></i>&nbsp;<?=Lang::get('Edit')?></button>
                                        </h5>
                                        <p class="set-shop-name"><?=((isset($shop['shop_name'])&&$shop['shop_name'])?$shop['shop_name']:'-')?></p>
                                    </div>
                                </div>
                                <div class="d-flex flex-row">
                                    <div><div class="icon me-4 mt-n3" style="font-size:32px;"><i class="uil uil-user"></i></div></div>
                                    <div>
                                        <h5 class="mb-0">
                                            <?=Lang::get('Owner')?>
                                            <button type="button" class="btn btn-sm btn-soft-primary rounded-pill" style="padding:3px 6px 3px 4px;line-height:14px;" onclick="profile_events('shop', { 'on':'owner' });"><i class="uil uil-pen"></i>&nbsp;<?=Lang::get('Edit')?></button>
                                        </h5>
                                        <p class="set-shop-owner"><?=((isset($shop['owner_name'])&&$shop['owner_name'])?$shop['owner_name']:'-')?></p>
                                    </div>
                                </div>
                                <div class="d-flex flex-row">
                                    <div><div class="icon me-4 mt-n3" style="font-size:32px;"><i class="uil uil-credit-card"></i></div></div>
                                    <div>
                                        <h5 class="mb-0">
                                            <?=Lang::get('TaxNumber')?>
                                            <button type="button" class="btn btn-sm btn-soft-primary rounded-pill" style="padding:3px 6px 3px 4px;line-height:14px;" onclick="profile_events('shop', { 'on':'tax' });"><i class="uil uil-pen"></i>&nbsp;<?=Lang::get('Edit')?></button>
                                        </h5>
                                        <p class="set-shop-tax"><?=((isset($shop['tax_number'])&&$shop['tax_number'])?'<mark class="doc tax-number"><span class="text-white bg-primary">TAX</span>'.$shop['tax_number'].'</mark>':'-')?></p>
                                    </div>
                                </div>
                                <div class="d-flex flex-row">
                                    <div><div class="icon me-4 mt-n3" style="font-size:32px;"><i class="uil uil-postcard"></i></div></div>
                                    <div>
                                        <h5 class="mb-0">
                                            <?=Lang::get('AddressBilling')?>
                                            <button type="button" class="btn btn-sm btn-soft-primary rounded-pill" style="padding:3px 6px 3px 4px;line-height:14px;" onclick="profile_events('shop', { 'on':'address' });"><i class="uil uil-pen"></i>&nbsp;<?=Lang::get('Edit')?></button>
                                        </h5>
                                        <p class="set-shop-address"><?=((isset($shop['fulladdress'])&&$shop['fulladdress'])?$shop['fulladdress']:'-')?></p>
                                    </div>
                                </div>
                                <div class="d-flex flex-row">
                                    <div><div class="icon me-4 mt-n3" style="font-size:32px;"><i class="uil uil-phone-volume"></i></div></div>
                                    <div>
                                        <h5 class="mb-0">
                                            <?=Lang::get('Phone')?>
                                            <button type="button" class="btn btn-sm btn-soft-primary rounded-pill" style="padding:3px 6px 3px 4px;line-height:14px;" onclick="profile_events('shop', { 'on':'phone' });"><i class="uil uil-pen"></i>&nbsp;<?=Lang::get('Edit')?></button>
                                        </h5>
                                        <p class="set-shop-phone"><?=((isset($shop['phone'])&&$shop['phone'])?$shop['phone']:'-')?></p>
                                    </div>
                                </div>
                                <div class="d-flex flex-row">
                                    <div><div class="icon me-4 mt-n3" style="font-size:32px;"><i class="uil uil-envelopes"></i></div></div>
                                    <div>
                                        <h5 class="mb-0">
                                            <?=Lang::get('Email')?>
                                        </h5>
                                        <p class="set-user-email"><?=User::get('email', '-').( (User::get('is_cmu')=='Y') ? '<br>'.User::get('email_cmu') : null )?></p>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary rounded w-100" onclick="document.location='<?=APP_HOME?>/request';">BOOKING &rarr;</button>
                            </div>
                        </div>
                    </div>
                <?php }else{ ?>
                    <div class="row gx-0">
                        <div class="col-lg-6 image-wrapper bg-image bg-cover rounded-top rounded-lg-start d-md-block" data-image-src="<?=THEME_IMG?>/cafeteria.jpg"></div>
                        <div class="col-lg-6">
                            <div class="p-5 p-md-6 p-lg-7">
                                <div class="d-flex flex-row mt-3">
                                    <div><div class="icon me-4 mt-n3" style="font-size:32px;"><i class="uil uil-shield-check"></i></div></div>
                                    <div>
                                        <h5 class="mb-0">
                                            <?=Lang::get('UserAccount')?>
                                        </h5>
                                        <p class="set-user-email"><?=Util::memberRoleName(User::get('role'))?></p>
                                    </div>
                                </div>
                                <div class="d-flex flex-row">
                                    <div><div class="icon me-4 mt-n3" style="font-size:32px;"><i class="uil uil-user"></i></div></div>
                                    <div>
                                        <h5 class="mb-0">
                                            <?=Lang::get('NameFull')?>
                                            <button type="button" class="btn btn-sm btn-soft-primary rounded-pill" style="padding:3px 6px 3px 4px;line-height:14px;" onclick="profile_events('user', { 'on':'name' });"><i class="uil uil-pen"></i>&nbsp;<?=Lang::get('Edit')?></button>
                                        </h5>
                                        <p class="set-user-name"><?=User::get('fullname', '-')?></p>
                                    </div>
                                </div>
                                <div class="d-flex flex-row">
                                    <div><div class="icon me-4 mt-n3" style="font-size:32px;"><i class="uil uil-phone-volume"></i></div></div>
                                    <div>
                                        <h5 class="mb-0">
                                            <?=Lang::get('Phone')?>
                                            <button type="button" class="btn btn-sm btn-soft-primary rounded-pill" style="padding:3px 6px 3px 4px;line-height:14px;" onclick="profile_events('user', { 'on':'phone' });"><i class="uil uil-pen"></i>&nbsp;<?=Lang::get('Edit')?></button>
                                        </h5>
                                        <p class="set-user-phone"><?=User::get('phone', '-')?></p>
                                    </div>
                                </div>
                                <div class="d-flex flex-row">
                                    <div><div class="icon me-4 mt-n3" style="font-size:32px;"><i class="uil uil-envelopes"></i></div></div>
                                    <div>
                                        <h5 class="mb-0">
                                            <?=Lang::get('Email')?>
                                        </h5>
                                        <p class="set-user-email"><?=User::get('email', '-').( (User::get('is_cmu')=='Y') ? '<br>'.User::get('email_cmu') : null )?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
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