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
        -webkit-border-top-left-radius: 0.4rem;
        -webkit-border-top-right-radius: 0.4rem;
        -moz-border-radius-topleft: 0.4rem;
        -moz-border-radius-topright: 0.4rem;
        border-top-left-radius: 0.4rem;
        border-top-right-radius: 0.4rem;
    }
    .container-custome-body {
        overflow:hidden;
        -webkit-border-bottom-right-radius: 0.4rem;
        -webkit-border-bottom-left-radius: 0.4rem;
        -moz-border-radius-bottomright: 0.4rem;
        -moz-border-radius-bottomleft: 0.4rem;
        border-bottom-right-radius: 0.4rem;
        border-bottom-left-radius: 0.4rem;
    }
</style>
<section class="wrapper image-wrapper bg-overlay bg-overlay-400 bg-image" data-image-src="<?=THEME_IMG?>/bg-blue.jpg">
    <div class="container container-custome-header pt-2 pb-0 text-center bg-white">
        <h1 class="text-primary pt-2 mb-5"><?=( (App::lang()=='en') ? 'Sessions' : 'เซสซั่น' )?></h1>
    </div>
</section>
<section class="wrapper">
    <div class="container container-custome-body pt-1 pb-1 bg-white">
        <div class="card mb-2">
            <div class="card-body p-1">
                <a class="collapse-link stretched-link collapsed" data-bs-toggle="collapse" href="#collapse-1" aria-expanded="false">All Sessions</a>
            </div>
            <div id="collapse-1" class="card-footer bg-dark p-5 accordion-collapse collapse">
                <div class="text-white"><?=Helper::debug($_SESSION);?></div>
            </div>
        </div>
        <div class="card mb-2">
            <div class="card-body p-1">
                <a class="collapse-link stretched-link" data-bs-toggle="collapse" href="#collapse-2" aria-expanded="false">User Sessions</a>
            </div>
            <div id="collapse-2" class="card-footer bg-dark p-5 accordion-collapse collapse show">
                <div class="text-white"><?=Helper::debug($_SESSION['login']['user']);?></div>
            </div>
        </div>
    </div>
</section>
<?php } ?>
<?php include(APP_FOOTER);?>