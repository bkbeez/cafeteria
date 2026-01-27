<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php
    if( !isset($_POST['login_email'])||!$_POST['login_email'] ){
        Status::error( Lang::get('Require').' !!!', array('onfocus'=>"login_email") );
    }
    // Begin
    $parameters = array();
    $parameters['email'] = Helper::stringSave($_POST['login_email']);
    if( (isset($_POST['login_email'])&&$_POST['login_email'])&&(isset($_POST['login_password'])&&$_POST['login_password']) ){
        $check = DB::one("SELECT email,password FROM member WHERE email=:email LIMIT 1;", $parameters);
        if( isset($check['email'])&&$check['email']&&password_verify(Helper::stringSave($_POST['login_password']), $check['password']) ){
            if( Auth::login($check['email']) ){
                $redirect = APP_HOME;
                if( isset($_SESSION['login_redirect']) ){
                    $redirect = $_SESSION['login_redirect'];
                    unset($_SESSION['login_redirect']);
                }
                Status::success( Lang::get('LoginSuccess'), array('url'=>$redirect) );
            }
        }
        Status::error( ( (App::lang()=='en') ? 'Password is incorrect' : 'รหัสผ่านไม่ถูกต้อง' ).' !!!', array('onfocus'=>'login_password') );
    }else{
        if( Helper::isLocal() ){
            $check = DB::one("SELECT email,shop_id,role FROM member WHERE email=:email LIMIT 1;", $parameters);
            if( isset($check['email'])&&$check['email'] ){
                if( isset($check['shop_id'])&&$check['shop_id'] ){
                    Status::success( Lang::get('Success'), array('shop'=>'Y') );
                }else if( isset($check['role'])&&$check['role']=='ADMIN' ){
                    if( Auth::login($check['email']) ){
                        $redirect = APP_HOME;
                        if( isset($_SESSION['login_redirect']) ){
                            $redirect = $_SESSION['login_redirect'];
                            unset($_SESSION['login_redirect']);
                        }
                        Status::success( Lang::get('LoginSuccess'), array('url'=>$redirect) );
                    }
                }
            }else if( $parameters['email']=='admin@mail.com' ){
                $member = array();
                $member['id'] = (new datetime())->format("YmdHis").Helper::randomNumber(7);
                $member['role'] = 'ADMIN';
                $member['email'] = $parameters['email'];
                $member['name'] = 'Administrator';
                $member['user_by'] = $parameters['email'];
                if( DB::create("INSERT INTO `member` (`id`,`role`,`email`,`name`,`date_create`,`user_create`) VALUES (:id,:role,:email,:name,NOW(),:user_by);", $member) ){
                    if( Auth::login($member['email']) ){
                        $redirect = APP_HOME;
                        if( isset($_SESSION['login_redirect']) ){
                            $redirect = $_SESSION['login_redirect'];
                            unset($_SESSION['login_redirect']);
                        }
                        Status::success( Lang::get('LoginSuccess'), array('url'=>$redirect) );
                    }
                }
            }
        }else{
            $check = DB::one("SELECT email FROM member WHERE email=:email AND shop_id IS NOT NULL LIMIT 1;", $parameters);
            if( isset($check['email'])&&$check['email'] ){
                Status::success( Lang::get('Success'), array('shop'=>'Y') );
            }else{
                Status::error( ( (App::lang()=='en') ? 'Email is not shop' : 'ไม่ใช่บัญชีร้านค้า' ).' !!!', array('onfocus'=>'login_email') );
            }
        }
    }
    // Done
    Status::error( Lang::get('PleaseTryAgain').' !!!', array('title'=>Lang::get('LoginError')) );
?>