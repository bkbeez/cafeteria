<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php
    if( !isset($_POST['login_as'])||!$_POST['login_as'] ){
        Status::error( Lang::get('NotFound').Lang::get('Data').' !!!' );
    }else if( !isset($_POST['admin_email'])||!$_POST['admin_email'] ){
        Status::error( Lang::get('NotFound').Lang::get('Email').' !!!', array('onfocus'=>'admin_email') );
    }else if( $_POST['login_as']=='member'&&(!isset($_POST['member_email'])||!$_POST['member_email']) ){
        Status::error( Lang::get('NotFound').Lang::get('Email').' !!!', array('onfocus'=>'member_email') );
    }
    // Begin
    $admin_email = Helper::stringSave($_POST['admin_email']);
    $check = DB::one("SELECT id,email FROM member WHERE role='ADMIN' AND email=:email LIMIT 1;", array('email'=>$admin_email));
    if( $_POST['login_as']=='admin' ){
        if( isset($check['id'])&&$check['id'] ){
            if( Auth::login($check['email']) ){
                $redirect = APP_HOME;
                if( isset($_SESSION['login_redirect']) ){
                    $redirect = $_SESSION['login_redirect'];
                    unset($_SESSION['login_redirect']);
                }
                Status::success( Lang::get('LoginSuccess') , array('url'=>$redirect) );
            }
        }else if( Helper::isLocal()&&$admin_email=='admin@mail.com' ){
            $member = array();
            $member['id'] = (new datetime())->format("YmdHis").Helper::randomNumber(7);
            $member['role'] = 'ADMIN';
            $member['email'] = $admin_email;
            $member['name'] = 'Administrator';
            $member['user_by'] = $admin_email;
            if( DB::create("INSERT INTO `member` (`id`,`role`,`email`,`name`,`date_active`,`date_create`,`user_create`) VALUES (:id,:role,:email,:name,NOW(),NOW(),:user_by);", $member) ){
                if( Auth::login($member['email']) ){
                    $redirect = APP_HOME;
                    if( isset($_SESSION['login_redirect']) ){
                        $redirect = $_SESSION['login_redirect'];
                        unset($_SESSION['login_redirect']);
                    }
                    Status::success( Lang::get('LoginSuccess') , array('url'=>$redirect) );
                }
            }
        }
    }else if( $_POST['login_as']=='member' ){
        if( isset($check['id'])&&$check['id'] ){
            if( Auth::login(Helper::stringSave($_POST['member_email'])) ){
                $redirect = APP_HOME;
                if( isset($_SESSION['login_redirect']) ){
                    $redirect = $_SESSION['login_redirect'];
                    unset($_SESSION['login_redirect']);
                }
                Status::success( Lang::get('LoginSuccess') , array('url'=>$redirect) );
            }
        }
    }
    // Done
    Status::error( Lang::get('PleaseTryAgain').' !!!', array('title'=>Lang::get('LoginError')) );
?>