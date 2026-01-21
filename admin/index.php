<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php
    $index['customheader'] = true;
    $index['page'] = 'admin';
    $link = APP_PATH.'/'.$index['page'];
    $form = APP_PATH.'/'.$index['page'];
    if( !Auth::check() ){
        $_SESSION['login_redirect'] = APP_HOME.'/'.$index['page']; 
        header('Location: '.APP_HOME.'/login');
        exit;
    }
    if( !Auth::admin() ){
        $_SESSION['deny'] = array();
        $_SESSION['deny']['title'] = ( (App::lang()=='en') ? 'Oops! For administrator only' : 'ขออภัย! สำหรับผู้ดูแลระบบเท่านั้น' );
        header('Location: '.APP_HOME.'/deny');
        exit;
    }
    $loadpage = null;
    if( isset($_GET['users']) ){
        $loadpage = 'users';
        $index['view'] = $loadpage;
    }else if( isset($_GET['logs']) ){
        $loadpage = 'logs';
        $index['view'] = $loadpage;
    }
?>
<?php include(APP_HEADER);?>
<?php if( isset($loadpage)&&$loadpage ){ include(APP_ROOT.'/'.$index['page'].'/'.$loadpage.'/index.php'); }else{ ?>
<style type="text/css">
    .container-custome-header {
        overflow:hidden;
        border-top:1px solid #173796;
        border-left:1px solid #173796;
        border-right:1px solid #173796;
        -webkit-border-top-left-radius: 0.4rem;
        -webkit-border-top-right-radius: 0.4rem;
        -moz-border-radius-topleft: 0.4rem;
        -moz-border-radius-topright: 0.4rem;
        border-top-left-radius: 0.4rem;
        border-top-right-radius: 0.4rem;
    }
    .container-custome-body {
        overflow:hidden;
        border-left:1px solid #173796;
        border-right:1px solid #173796;
        border-bottom:1px solid #173796;
        -webkit-border-bottom-right-radius: 0.4rem;
        -webkit-border-bottom-left-radius: 0.4rem;
        -moz-border-radius-bottomright: 0.4rem;
        -moz-border-radius-bottomleft: 0.4rem;
        border-bottom-right-radius: 0.4rem;
        border-bottom-left-radius: 0.4rem;
    }
</style>
<section class="wrapper image-wrapper bg-overlay bg-overlay-400 bg-image" data-image-src="<?=THEME_IMG?>/bg-blue.jpg">
    <div class="container container-custome-header pt-2 pb-0 text-center bg-soft-primary">
        <h1 class="text-primary pt-1 mb-2"><?=( (App::lang()=='en') ? 'Sessions' : 'เซสซั่น' )?></h1>
    </div>
</section>
<section class="wrapper">
    <div class="container container-custome-body pt-1 pb-3 bg-soft-primary mb-n4">
        <div class="card">
            <div class="card-body bg-dark p-2">
                <div class="text-white"><?=Helper::debug($_SESSION);?></div>
            </div>
        </div>
    </div>
</section>
<?php } ?>
<?php include(APP_FOOTER);?>