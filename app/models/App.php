<?php
/**
 * App Class
 */
class App {

    /**
     *  Get
     *  @param  key
     *  @return value
     */
    static function lang($is_uppercase=false)
    {
        if( $is_uppercase ){
            return ( isset($_SESSION['SITE_LANGUAGE']) ? strtoupper($_SESSION['SITE_LANGUAGE']) : 'TH' );
        }else{
            return ( isset($_SESSION['SITE_LANGUAGE']) ? $_SESSION['SITE_LANGUAGE'] : 'th' );
        }
    }

    /**
     *  Profile
     *  @param  void
     *  @return htmls
     */
    static function profile()
    {
        if( Auth::check() ){
            $htmls = '<div id="offcanvas-profile" class="offcanvas offcanvas-end bg-light on-font-primary" data-bs-scroll="true" style="background:url(\''.THEME_IMG.'/map.png\') repeat-y top center;">';
                $htmls .= '<div class="offcanvas-header">';
                    $htmls .= '<h3 class="fs-24 mb-0 text-primary on-text-oneline">'.Lang::get('Profile').'</h3>';
                    $htmls .= '<button type="button" class="btn-close btn-close-dark" data-bs-dismiss="offcanvas" aria-label="Close"></button>';
                $htmls .= '</div>';
                $htmls .= '<div class="offcanvas-body d-flex flex-column">';
                    $htmls .= '<div class="offcanvas-box profile">';
                        $htmls .= '<a href="/?profile">';
                            $htmls .= '<div class="edit-box"><i class="uil uil-pen"></i></div>';
                            $htmls .= '<div class="img-box"><img src="'.User::get('image').'" onerror="this.onerror=null;this.src=\''.THEME_IMG.'/avatar.png\';" alt="Avatar"/></div>';
                            $htmls .= '<div class="info-box">';
                                $htmls .= '<font class="text-primary">'.User::get('name').'</font>';
                                $htmls .= '<div><i class="uil uil-envelopes"></i> '.User::get('email').'</div>';
                            $htmls .= '</div>';
                        $htmls .= '</a>';
                    $htmls .= '</div>';
                    if( Auth::admin() ){
                        $htmls .= '<div class="position-relative lift mt-2" onclick="document.location=\''.APP_HOME.'/admin/?users\'" style="cursor:pointer;">';
                            $htmls .= '<div class="shape rounded bg-pale-blue rellax d-md-block" data-rellax-speed="0" style="bottom:-0.3rem;right:-0.3rem;width:98%;height:98%;z-index:0;transform:translate3d(0px, 0px, 0px);"></div>';
                            $htmls .= '<div class="card">';
                                $htmls .= '<div class="card-body text-blue" style="height:72px;padding:12px 0 0 12px;">';
                                    $htmls .= '<div style="color:white;float:left;width:48px;height:48px;text-align:center;margin:0 10px 0 0;background:#3f78e0;border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;">';
                                        $htmls .= '<i class="uil uil-users-alt" style="font-size:36px;line-height:48px;"></i>';
                                    $htmls .= '</div>';
                                    $htmls .= '<font style="line-height:48px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">'.( (App::lang()=='en') ? 'User Accounts' : 'บัญชีผู้ใช้ระบบ' ).'</font>';
                                $htmls .= '</div>';
                            $htmls .= '</div>';
                        $htmls .= '</div>';
                        $htmls .= '<div class="position-relative lift mt-2" onclick="document.location=\''.APP_HOME.'/admin/?logs\'" style="cursor:pointer;">';
                            $htmls .= '<div class="shape rounded bg-pale-red rellax d-md-block" data-rellax-speed="0" style="bottom:-0.3rem;right:-0.3rem;width:98%;height:98%;z-index:0;transform:translate3d(0px, 0px, 0px);"></div>';
                            $htmls .= '<div class="card">';
                                $htmls .= '<div class="card-body text-red" style="height:72px;padding:12px 0 0 12px;">';
                                    $htmls .= '<div style="color:white;float:left;width:48px;height:48px;text-align:center;margin:0 10px 0 0;background:#e2626b;border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;">';
                                        $htmls .= '<i class="uil uil-credit-card-search" style="font-size:36px;line-height:48px;"></i>';
                                    $htmls .= '</div>';
                                    $htmls .= '<font style="line-height:48px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">'.( (App::lang()=='en') ? 'Monitor Logs' : 'ตรวจสอบล็อก' ).'</font>';
                                $htmls .= '</div>';
                            $htmls .= '</div>';
                        $htmls .= '</div>';
                        $htmls .= '<div class="position-relative lift mt-2" onclick="document.location=\''.APP_HOME.'/admin\'" style="cursor:pointer;">';
                            $htmls .= '<div class="shape rounded bg-pale-navy rellax d-md-block" data-rellax-speed="0" style="bottom:-0.3rem;right:-0.3rem;width:98%;height:98%;z-index:0;transform:translate3d(0px, 0px, 0px);"></div>';
                            $htmls .= '<div class="card">';
                                $htmls .= '<div class="card-body text-navy" style="height:72px;padding:12px 0 0 12px;">';
                                    $htmls .= '<div style="color:white;float:left;width:48px;height:48px;text-align:center;margin:0 10px 0 0;background:#343f52;border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;">';
                                        $htmls .= '<i class="uil uil-airplay" style="font-size:36px;line-height:48px;"></i>';
                                    $htmls .= '</div>';
                                    $htmls .= '<font style="line-height:48px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">'.( (App::lang()=='en') ? 'Sessions' : 'เซสซั่น' ).'</font>';
                                $htmls .= '</div>';
                            $htmls .= '</div>';
                        $htmls .= '</div>';
                    }
                    $htmls .= '<div class="offcanvas-footer flex-column" style="padding:9px 0 2px 0;">';
                        $htmls .= '<div class="restart-box">';
                            $htmls .= '<div class="offcanvas-box restart">';
                                $htmls .= '<a href="javascript:void(0);" onclick="runRestart();">';
                                    $htmls .= '<div class="icon-box"><i class="uil uil-refresh"></i></div>';
                                $htmls .= '</a>';
                            $htmls .= '</div>';
                        $htmls .= '</div>';
                        $htmls .= '<div class="logout-box">';
                            $htmls .= '<div class="offcanvas-box logout">';
                                $htmls .= '<a href="javascript:void(0);" onclick="runLogout();">';
                                    $htmls .= '<div class="icon-box"><i class="uil uil-power"></i></div>';
                                    $htmls .= '<div class="info-box">';
                                        $htmls .= '<font class="text-primary">Logout</font>';
                                        $htmls .= '<div>ออกจากระบบ<span class="underline-3 style-3 red">โรงอาหาร</span></div>';
                                    $htmls .= '</div>';
                                $htmls .= '</a>';
                            $htmls .= '</div>';
                        $htmls .= '</div>';
                    $htmls .= '</div>';
                $htmls .= '</div>';
            $htmls .= '</div>';

            return $htmls;
        }

        return null;
    }

    /**
     *  Menus
     *  @param index
     *  @return htmls
     */ 
    static function menus($index=array())
    {
        $htmls = '<header class="wrapper">';
            if( isset($index['customheader']) ){
                $htmls = '<header class="wrapper custom-wrapper">';
            }
            $htmls .= '<nav class="navbar navbar-expand-lg classic transparent navbar-light">';
                $htmls .= '<div class="container flex-lg-row flex-nowrap align-items-center">';
                    $htmls .= '<div class="navbar-brand w-100">';
                        $htmls .= '<a href="'.APP_HOME.'">';
                            $htmls .= '<img class="on-light"src="'.THEME_IMG.'/logo/logo-light.png" srcset="'.THEME_IMG.'/logo/logo-light@2x.png 2x" alt=""/>';
                            $htmls .= '<img class="on-color" src="'.THEME_IMG.'/logo/logo.png" srcset="'.THEME_IMG.'/logo/logo@2x.png 2x" alt=""/>';
                        $htmls .= '</a>';
                    $htmls .= '</div>';
                    $htmls .= '<div class="navbar-collapse offcanvas offcanvas-nav offcanvas-start">';
                        $htmls .= '<div class="offcanvas-header d-lg-none">';
                            $htmls .= '<img src="'.THEME_IMG.'/logo/logo-light.png" srcset="'.THEME_IMG.'/logo/logo-light@2x.png 2x" alt=""/>';
                            $htmls .= '<button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>';
                        $htmls .= '</div>';
                        $htmls .= '<div id="mainsite-navbar" class="offcanvas-body ms-lg-auto d-flex flex-column h-100">';
                            $htmls .= '<ul class="navbar-nav">';
                            if( Auth::check() ){
                                $htmls .= '<li class="nav-item'.((isset($index['page'])&&$index['page']=='manage') ? ' active':null).' dropdown">';
                                    $htmls .= '<a class="nav-link dropdown-toggle" href="javascript:void(0);" data-bs-toggle="dropdown"><span class="nav-name"><div class="m-box-top"><i class="uil uil-apps"></i></div><div class="m-box"><i class="uil uil-apps"></i></div>'.( (App::lang()=='en') ? 'Management' : 'จัดการข้อมูล' ).'</span></a>';
                                    $htmls .= '<ul class="dropdown-menu mainsite-dropdown'.((isset($index['page'])&&$index['page']=='manage') ? ' show':null).'">';
                                        $htmls .= '<li class="nav-item'.((isset($index['view'])&&$index['view']=='shops') ? ' active':null).'">';
                                            $htmls .= '<a class="dropdown-item" href="'.APP_HOME.'/manage/?shops">';
                                                $htmls .= '<span class="nav-name"><div class="m-box"><i class="uil uil-shop"></i></div>'.( (App::lang()=='en') ? 'Shops' : 'ข้อมูลร้านค้า' ).'</span>';
                                            $htmls .= '</a>';
                                        $htmls .= '</li>';
                                        $htmls .= '<li class="nav-item'.((isset($index['view'])&&$index['view']=='bowls') ? ' active':null).'">';
                                            $htmls .= '<a class="dropdown-item" href="'.APP_HOME.'/manage/?bowls">';
                                                $htmls .= '<span class="nav-name"><div class="m-box"><i class="uil uil-shutter-alt"></i></div>'.( (App::lang()=='en') ? 'Bowls' : 'รายการถ้วย' ).'</span>';
                                            $htmls .= '</a>';
                                        $htmls .= '</li>';
                                        $htmls .= '<li class="nav-item'.((isset($index['view'])&&$index['view']=='plates') ? ' active':null).'">';
                                            $htmls .= '<a class="dropdown-item" href="'.APP_HOME.'/manage/?plates">';
                                                $htmls .= '<span class="nav-name"><div class="m-box"><i class="uil uil-record-audio"></i></div>'.( (App::lang()=='en') ? 'Plates' : 'รายการจาน' ).'</span>';
                                            $htmls .= '</a>';
                                        $htmls .= '</li>';
                                    $htmls .= '</ul>';
                                $htmls .= '</li>';
                            }
                            $htmls .= '</ul>';
                            $htmls .= '<div class="offcanvas-footer d-lg-none" style="padding:9px 0 2px 0;">';
                                $htmls .= '<div>';
                                    $htmls .= '<i class="uil uil-envelopes"></i> '.APP_EMAIL.'<br/>';
                                    $htmls .= '<i class="uil uil-phone-volume"></i> '.APP_PHONE.'<br/>';
                                    $htmls .= '<nav class="nav social social-white">';
                                        $htmls .= '<a href="https://www.edu.cmu.ac.th" target="_blank" style="margin:0;"><mark class="doc"><i class="uil uil-globe"></i></mark></a>';
                                        $htmls .= '<a href="https://www.facebook.com/edu.cmu.ac.th" target="_blank" style="margin:0;"><mark class="doc"><i class="uil uil-facebook"></i></mark></a>';
                                        $htmls .= '<a href="https://www.youtube.com/@predcmu4451" target="_blank" style="margin:0;"><mark class="doc"><i class="uil uil-youtube" target="_blank"></i></mark></a>';
                                    $htmls .= '</nav>';
                                $htmls .= '</div>';
                            $htmls .= '</div>';
                        $htmls .= '</div>';
                    $htmls .= '</div>';
                    $htmls .= '<div class="navbar-other ms-lg-4">';
                        $htmls .= '<ul class="navbar-nav flex-row align-items-center ms-auto">';
                        if( Auth::check() ){
                            /*$htmls .= '<li class="nav-item">';
                                $htmls .= '<a class="nav-link" href="'.APP_HOME.'/scan">';
                                    $htmls .= '<div class="nav-link-icon user-qrscan"><img src="'.THEME_IMG.'/qrscan.png" /></div>';
                                $htmls .= '</a>';
                            $htmls .= '</li>';*/
                            $htmls .= '<li class="nav-item">';
                                $htmls .= '<a class="nav-link" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-profile">';
                                    $htmls .= '<div class="nav-link-icon user-picture"><img src="'.User::get('picture').'" onerror="this.onerror=null;this.src=\''.THEME_IMG.'/avatar.png\';" /></div>';
                                $htmls .= '</a>';
                            $htmls .= '</li>';
                            $htmls .= '<li class="nav-item d-lg-none">';
                                $htmls .= '<button class="hamburger offcanvas-nav-btn"><span></span></button>';
                            $htmls .= '</li>';
                        }else{
                            $htmls .= '<li class="nav-item d-md-block on-font-primary">';
                                $htmls .= '<a href="'.APP_HOME.'/login" class="btn btn-login btn-sm btn-soft-primary rounded-pill">'.Lang::get('Login').'</a>';
                            $htmls .= '</li>';
                        }
                        $htmls .= '</ul>';
                    $htmls .= '</div>';
                $htmls .= '</div>';
            $htmls .= '</nav>';
            $htmls .= App::profile();
        $htmls .= '</header>';

        return $htmls;
    }

    /**
     *  Footer
     *  @param  index
     *  @return htmls
     */
    static function footer($index=array())
    {
        if( isset($index['hidefooter']) ){
            return null;
        }
        //$htmls .= '<footer class="bg-primary text-white">';
        $htmls = '<footer class="image-wrapper bg-overlay bg-overlay-400 bg-full bg-image pt-4 text-white" data-image-src="'.THEME_IMG.'/bg-blue.jpg">';
            $htmls .= '<div class="container pt-10 pb-6">';
                $htmls .= '<div class="row gy-2 gy-lg-0">';
                    $htmls .= '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-3 on-copyright">';
                        $htmls .= '<div class="widget on-logo">';
                            $htmls .= '<img src="'.THEME_IMG.'/logo/logo-o.png" srcset="'.THEME_IMG.'/logo/logo-o.png 2x" alt="" />';
                        $htmls .= '</div>';
                        $htmls .= '<p class="fs-14 mb-0">© '.date("Y").' '.APP_CODE.'. <br class="d-none d-lg-block">All rights reserved.</p>';
                    $htmls .= '</div>';
                    $htmls .= '<div class="col-xs-12 col-sm-12 col-md-9 col-lg-6">';
                        $htmls .= '<div class="widget">';
                            $htmls .= '<h4 class="widget-title text-white mb-1">'.Lang::get('Office').'</h4>';
                            $htmls .= '<address class="fs-14 pe-xl-15 pe-xxl-17">';
                                $htmls .= '<div class="on-text-oneline">'.( (App::lang()=='en') ? APP_FACT_EN : APP_FACT_TH ).'</div>';
                                $htmls .= '<div>'.( (App::lang()=='en') ? APP_ADDR_EN : APP_ADDR_TH ).'</div>';
                            $htmls .= '</address>';
                        $htmls .= '</div>';
                    $htmls .= '</div>';
                    $htmls .= '<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">';
                        $htmls .= '<div class="widget widget-phone">';
                            $htmls .= '<h4 class="widget-title text-white mb-1">'.Lang::get('Contact').'</h4>';
                            $htmls .= '<address class="fs-14 pe-xl-15 pe-xxl-17">';
                                $htmls .= '<div class="on-text-oneline"><i class="uil uil-envelopes"></i> '.APP_EMAIL.'</div>';
                                $htmls .= '<div><i class="uil uil-forwaded-call"></i> '.APP_PHONE.'</div>';
                            $htmls .= '</address>';
                        $htmls .= '</div>';
                    $htmls .= '</div>';
                $htmls .= '</div>';
                $htmls .= '<div class="row row-social gy-2 gy-lg-0">';
                    $htmls .= '<div class="col-xs-6 col-sm-6 col-md-9 col-lg-9 on-social">';
                        $htmls .= '<nav class="nav social social-white">';
                            $htmls .= '<a href="https://www.edu.cmu.ac.th" target="_blank"><mark class="doc"><i class="uil uil-globe"></i></mark></a>';
                            $htmls .= '<a href="https://www.facebook.com/edu.cmu.ac.th" target="_blank"><mark class="doc"><i class="uil uil-facebook"></i></mark></a>';
                            $htmls .= '<a href="https://www.youtube.com/@predcmu4451" target="_blank"><mark class="doc"><i class="uil uil-youtube" target="_blank"></i></mark></a>';
                        $htmls .= '</nav>';
                    $htmls .= '</div>';
                    $htmls .= '<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 on-language">';
                        $htmls .= '<div>';
                            $htmls .= '<nav class="nav social social-white">';
                                $htmls .= '<a href="javascript:void(0);" onclick="runLanguage(\'th\');"><mark class="doc '.((App::lang()=='th')?'bg-white text-primary':'text-white').'" style="font-size:0.8rem;line-height:1.70rem;">TH</mark></a>';
                                $htmls .= '<a href="javascript:void(0);" onclick="runLanguage(\'en\');"><mark class="doc '.((App::lang()=='en')?'bg-white text-primary':'text-white').'" style="font-size:0.8rem;line-height:1.70rem;">EN</mark></a>';
                            $htmls .= '</nav>';
                        $htmls .= '</div>';
                    $htmls .= '</div>';
                $htmls .= '</div>';
            $htmls .= '</div>';
        $htmls .= '</footer>';

        return $htmls;
        
    }

}
?>