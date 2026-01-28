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
    if( isset($_GET['new']) ){
        include(APP_ROOT.'/'.$index['page'].'/manage/new.php');
    }else{
        include(APP_ROOT.'/'.$index['page'].'/filter/index.php');
    }
?>