<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php
    $index['customheader'] = true;
    $index['page'] = 'request';
    $link = APP_PATH.'/'.$index['page'];
    $form = APP_PATH.'/'.$index['page'];
    if( !Auth::check() ){
        $_SESSION['login_redirect'] = APP_HOME.'/'.$index['page'];
        if( isset($_GET['new']) ){
            $_SESSION['login_redirect'] .= '/?new';
        }
        header('Location: '.APP_HOME.'/login');
        exit;
    }
    if( Auth::staff()||User::get('shop_id') ){
        // Allowed
    }else{
        $_SESSION['deny'] = array();
        $_SESSION['deny']['title'] = ( (App::lang()=='en') ? 'Oops! For shop only' : 'ขออภัย! สำหรับร้านค้าเท่านั้น' );
        header('Location: '.APP_HOME.'/deny');
        exit;
    }
    if( isset($_GET['new']) ){
        include(APP_ROOT.'/'.$index['page'].'/manage/new.php');
    }else{
        include(APP_ROOT.'/'.$index['page'].'/filter/index.php');
    }
?>