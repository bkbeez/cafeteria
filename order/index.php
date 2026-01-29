<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php
    $index['customheader'] = true;
    $index['page'] = 'order';
    $link = APP_PATH.'/'.$index['page'];
    $form = APP_PATH.'/'.$index['page'];
    if( !Auth::check() ){
        $_SESSION['login_redirect'] = APP_HOME.'/'.$index['page'];
        if( isset($_GET['lists']) ){
            $_SESSION['login_redirect'] .= '/?lists';
        }else if( isset($_GET['daily']) ){
            $_SESSION['login_redirect'] .= '/?daily';
        }else{
            if( isset($_GET)&&count($_GET)>0 ){
                $geti = 0;
                foreach($_GET as $key => $value) {
                    if( $geti==0 ){
                        $_SESSION['login_redirect'] .= '/?'.$key.( $value ? '='.$value : null );
                    }else{
                        $_SESSION['login_redirect'] .= '&'.$key.( $value ? '='.$value : null );
                    }
                    $geti++;
                }
            }
        }
        header('Location: '.APP_HOME.'/login');
        exit;
    }
    if( !Auth::staff() ){
        $_SESSION['deny'] = array();
        $_SESSION['deny']['title'] = ( (App::lang()=='en') ? 'Oops! For officer only' : 'ขออภัย! สำหรับเจ้าหน้าที่เท่านั้น' );
        header('Location: '.APP_HOME.'/deny');
        exit;
    }
    if( isset($_GET['lists']) ){
        include(APP_ROOT.'/'.$index['page'].'/filter/index.php');
    }else if( isset($_GET['daily']) ){
        include(APP_ROOT.'/'.$index['page'].'/daily/index.php');
    }else{
        if( isset($_GET)&&count($_GET)>0 ){
            foreach($_GET as $key => $value) {
                $request_id = $key;
                break;
            }
        }
        if( isset($request_id) ){
            include(APP_ROOT.'/'.$index['page'].'/manage/index.php');
        }else{
            include(APP_ROOT.'/'.$index['page'].'/daily/index.php');
        }
    }
?>