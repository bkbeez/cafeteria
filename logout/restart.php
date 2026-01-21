<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php
    if( User::get('email') ){
        $email = User::get('email');
        unset($_SESSION['deny']);
        unset($_SESSION['login']);
        unset($_SESSION['login_redirect']);
        unset($_SESSION['print_as']);
        if( Auth::login($email) ){
            if( isset($_REQUEST['ajax']) ){
                Status::success( ( (App::lang()=='en') ? 'Restart successfully.' : 'รีสตาร์ทระบบเรียบร้อยแล้ว' ), array('title'=>( (App::lang()=='en') ? 'Restarted' : 'รีสตาร์ทแล้ว' ) ) );
            }else{
                header('Location: '.APP_HOME);
                exit;
            }
        }
    }
    if( isset($_REQUEST['ajax']) ){
        Status::error( ( (App::lang()=='en') ? 'Please check your account, and Try again !!!' : 'โปรดตรวจสอบบัญชีของท่าน และลองใหม่อีกครั้ง !!!' ), array('title'=>( (App::lang()=='en') ? 'Can not restart.' : 'ไม่สามารถรีสตาร์ทระบบได้' )) );
    }else{
        $_SESSION['deny'] = array();
        $_SESSION['deny']['title'] = ( (App::lang()=='en') ? 'Oops! Can not restart' : 'ขออภัย! ไม่สามารถรีสตาร์ทระบบได้' );
        $_SESSION['deny']['message'] = ( (App::lang()=='en') ? 'Please check your account, and Try again !!!' : 'โปรดตรวจสอบบัญชีของท่าน และลองใหม่อีกครั้ง !!!' );
        header('Location: '.APP_HOME.'/deny');
        exit;
    }
?>