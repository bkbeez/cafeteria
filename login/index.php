<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php
    if( Auth::check() ){
        $redirect = APP_HOME;
        if( isset($_SESSION['login_redirect']) ){
            $redirect = $_SESSION['login_redirect'];
            unset($_SESSION['login_redirect']);
        }
        header('Location: '.$redirect);
        exit;
    }
?>
<!DOCTYPE html>
<html lang="<?=App::lang()?>">
    <head app-lang="<?=App::lang()?>" app-path="<?=APP_PATH?>">
        <meta charset="utf-8" />
        <meta name="keywords" content="<?=APP_CODE?>,EDU CMU CHECKIN">
        <meta name="description" content="<?=APP_FACT_TH.','.APP_FACT_EN?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />
        <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
        <title><?=APP_CODE?> | Login</title>
        <link rel="icon" type="image/png" href="<?=APP_PATH?>/favicon.png" />
        <link rel="icon" type="image/png" sizes="32x32" href="<?=APP_PATH?>/favicon.png" />
        <link rel="icon" type="image/png" sizes="16x16" href="<?=APP_PATH?>/favicon.png" />
        <link rel="icon shortcut" type="image/ico" href="<?=APP_PATH?>/favicon.ico" />
        <link rel="apple-touch-icon" sizes="76x76" href="<?=APP_PATH?>/favicon.png" />
        <link rel="apple-touch-icon" sizes="180x180" href="<?=APP_PATH?>/favicon.png">
        <link rel="apple-touch-icon" sizes="256x204" href="<?=APP_PATH?>/favicon.png">
        <link rel="apple-touch-icon-precomposed" href="<?=APP_PATH?>/favicon.png" />
        <link rel="stylesheet" href="<?=THEME_CSS?>/plugins.css">
        <link rel="stylesheet" href="<?=THEME_CSS?>/style.css">
        <link rel="stylesheet" href="<?=THEME_JS?>/sweetalert/sweetalert2.min.css" />
        <link rel="stylesheet" href="<?=THEME_CSS?>/index.css" />
        <script type="text/javascript" src="<?=THEME_JS?>/jquery-2.1.1.js"></script>
        <script type="text/javascript" src="<?=THEME_JS?>/jquery.form.min.js"></script>
        <script type="text/javascript" src="<?=THEME_JS?>/sweetalert/sweetalert2.min.js"></script>
        <script type="text/javascript" src="<?=THEME_JS?>/index.js"></script>
        <style type="text/css">
            .login .card {width:480px;overflow:hidden;}
            .login .card .card-body { padding-top:5px;padding-bottom:5px; }
            .login h1 { font-size:36px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis; }
            .login .login-logo { height:72px; }
            .login .login-cafeteria>img { width:100%;  }
            .login .btn-google>span { padding:0 5px;}
            @media only all and (max-width: 495px) {
                .login h1 { font-size:30px; }
                .login .login-logo { height:62px; }
            }
            @media only all and (max-width: 435px) {
                .login h1 { font-size:25px; }
                .login .login-logo { height:55px; }
            }
            @media only all and (max-width: 414px) {
                .login .btn-google>span { display:none; }
            }
            @media only all and (max-width: 400px) {
                .login .login-logo { height:72px;display:block; }
                .login .btn-google>span { display:none; }
            }
            @media screen and (max-height:435px) {
                .login .login-cafeteria { display:none; }
            }
        </style>
    </head>
    <body>
        <div class="page-loader"></div>
        <div class="container login on-font-primary">
            <div class="row">
                <div class="col d-flex justify-content-center align-items-center" style="height:100vh;">
                    <div class="card">
                        <center class="login-cafeteria"><img src="<?=THEME_IMG?>/cafeteria.jpg" style="width:100%;"/></center>
                        <div class="row gx-0">
                            <div class="col-lg-12 p-2">
                                <div class="card-body">
                                    <form name="LoginForm" action="<?=APP_PATH?>/login/loging.php" method="POST" enctype="multipart/form-data" class="form-manage">
                                        <center>
                                            <h1 class="text-sky on-bold-primary mb-0">
                                                <img class="login-logo" src="<?=THEME_IMG?>/logo/logo.png" alt="login-logo"/>
                                                <?=APP_CODE?>
                                            </h1>
                                        </center>
                                        <div class="form-floating mb-2">
                                            <input id="login_email" name="login_email" type="email" class="form-control" placeholder="..." onchange="login_events('check');">
                                            <label for="login_email">Email</label>
                                        </div>
                                        <div class="form-floating password-field mb-2">
                                            <input id="login_password" name="login_password" type="password" class="form-control" placeholder="..." disabled>
                                            <span class="password-toggle"><i class="uil uil-eye"></i></span>
                                            <label for="login_password">Password</label>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary rounded w-100">LOGIN &rarr;</button>
                                        </div>
                                    </form>
                                    <div class="form-manage text-center">
                                        <div class="divider-icon my-2"><?=Lang::get('Or')?></div>
                                        <button type="button" class="btn btn-google btn-red rounded w-100" style="line-height:28px;" onclick="login_events('google');"><img class="img-fluid for-light" src="<?=THEME_IMG?>/google.png" alt="google" style="height:28px;padding:2px;background:white;margin:0 5px 0 0;-webkit-border-radius:50%;-moz-border-radius:50%;border-radius:50%;"/> Sign in <span>with</span> Google <span>Account</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="progress-wrap"><svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102"><path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" /></svg></div>
        <script type="text/javascript" src="<?=THEME_JS?>/plugins.js"></script>
        <script type="text/javascript" src="<?=THEME_JS?>/theme.js"></script>
        <script type="text/javascript">
            function login_events(action, params){
                $("form[name='LoginForm'] label>span").remove();
                if(action=='check'){
                    $("form[name='LoginForm'] button[type='submit']").click();
                }else if(action=='google'){
                    $("body").fadeOut('slow', function(){
                        $(this).fadeIn(3000);
                        document.location = '<?=APP_HOME?>/login/signingoogle.php';
                    });
                }
            }
            $(document).ready(function() {
                $("form[name='LoginForm']").ajaxForm({
                    beforeSubmit: function (formData, jqForm, options) {
                        $("form[name='LoginForm'] label>span").remove();
                        runStart();
                    },
                    success: function(rs) {
                        runStop();
                        var data = JSON.parse(rs);
                        if(data.status=='success'){
                            if(data.shop!=undefined&&data.shop=='Y'){
                                $("form[name='LoginForm'] input[name='login_password']").removeAttr('disabled');
                            }else{
                                $("body").fadeOut('slow', function(){
                                    document.location = data.url;
                                });
                            }
                        }else{
                            if( data.onfocus!=undefined&&data.onfocus ){
                                $("form[name='LoginForm'] label[for='"+data.onfocus+"']").append("<span class=text-red><sup> * <em>"+data.text+"</em></sup></span>");
                                $("form[name='LoginForm'] input[name='"+data.onfocus+"']").focus();
                            }else{
                                swal({
                                    'type' : data.status,
                                    'title': data.title,
                                    'html' : data.text,
                                    'showCloseButton': false,
                                    'showCancelButton': false,
                                    'focusConfirm': false,
                                    'allowEscapeKey': false,
                                    'allowOutsideClick': false,
                                    'confirmButtonClass': 'btn btn-outline-danger',
                                    'confirmButtonText':'<span><?=Lang::get('Understand')?></span>',
                                    'buttonsStyling': false
                                }).then(
                                    function () {
                                        swal.close();
                                    },
                                    function (dismiss) {
                                        if (dismiss === 'cancel') {
                                            swal.close();
                                        }
                                    }
                                );
                            }
                        }
                    }
                });
            });
        </script>
    </body>
</html>