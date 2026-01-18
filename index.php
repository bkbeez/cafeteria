<?php include($_SERVER["DOCUMENT_ROOT"].'/app/autoload.php'); ?>
<?php include(APP_HEADER); ?>
<style type="text/css">
    .home-logo {
        left: 0;
        float: left;
        width: 180px;
        margin:0 0 0 15px;
        position: absolute;
    }
    .home-logo>img {
        width: 100%;
        -webkit-animation: logo-fadein 6s;
           -moz-animation: logo-fadein 6s;
            -ms-animation: logo-fadein 6s;
             -o-animation: logo-fadein 6s;
                animation: logo-fadein 6s;
    }
    .navbar-brand img.on-light {
        visibility: hidden;
    }
    .wrapper-menu-icon .lift {
        cursor: pointer;
    }
    @media only all and (max-width: 575px) {
        .row-shop>div {
            width: 50%;
        }
    }
    @media only all and (max-width: 250px) {
        .row-shop>div {
            width: 100%;
            float: none;
        }
    }
</style>
<section class="wrapper image-wrapper bg-overlay bg-overlay-400 bg-image bg-content text-white" data-image-src="<?=THEME_IMG?>/bg-blue.jpg">
    <div class="container pt-5 pb-17 pb-lg-20 mt-n14">
        <div class="home-logo">
            <img src="<?=THEME_IMG?>/logo/logo-light.png" srcset="<?=THEME_IMG?>/logo/logo-light@2x.png 2x" />
        </div>
        <div class="row gx-0 gy-10 align-items-center mt-10 mt-lg-3">
            <div class="col-lg-6 site-intro" data-cues="slideInDown" data-group="page-title" data-delay="600">
                <h1 class="display-2 on-bold-primary text-yellow pt-14 mb-4">
                    <?=( (App::lang()=='en') ? APP_NAME_EN : APP_NAME_TH )?><br/>
                    <span class="fs-36 on-font-primary typer text-sky text-nowrap" data-delay="100" data-words="<?=( (App::lang()=='en') ? 'Faculty of Education,Chiang Mai University' : 'คณะศึกษาศาสตร์,มหาวิทยาลัยเชียงใหม่' )?>"></span><span class="cursor fs-36 text-sky" data-owner="typer"></span>
                </h1>
                <p class="lead fs-24 lh-sm text-white mb-7 pe-lg-0 pe-xxl-15"><?=( (App::lang()=='en') ? 'Management System for restaurants and cafes selling located within the Faculty of Education, Chiang Mai University.' : 'ระบบบริหารจัดการร้านค้า สำหรับให้บริการร้านอาหารและร้านกาแฟ ที่ค้าขายภายในพื้นที่คณะศึกษาศาสตร์ มหาวิทยาลัยเชียงใหม่' )?></p>
                <div><a href="<?=APP_HOME?>/profile" class="btn btn-lg btn-soft-primary rounded-pill"><?=( (App::lang()=='en') ? 'For Restaurant' : 'สำหรับร้านค้า' )?> &rarr;</a></div>
            </div>
            <div class="col-lg-5 offset-lg-1" data-cues="slideInDown">
                <div class="swiper-container dots-over shadow-lg" data-margin="0" data-autoplay="true" data-autoplaytime="3000" data-nav="true" data-dots="false" data-items="1">
                    <div class="swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide"><img src="<?=THEME_IMG?>/slide/photo-1.png" srcset="<?=THEME_IMG?>/slide/photo-1.png 2x" class="rounded" alt="slide-photo" /></div>
                            <div class="swiper-slide"><img src="<?=THEME_IMG?>/slide/photo-2.png" srcset="<?=THEME_IMG?>/slide/photo-2.png 2x" class="rounded" alt="slide-photo" /></div>
                            <div class="swiper-slide"><img src="<?=THEME_IMG?>/slide/photo-3.png" srcset="<?=THEME_IMG?>/slide/photo-3.png 2x" class="rounded" alt="slide-photo" /></div>
                            <div class="swiper-slide"><img src="<?=THEME_IMG?>/slide/photo-4.png" srcset="<?=THEME_IMG?>/slide/photo-4.png 2x" class="rounded" alt="slide-photo" /></div>
                            <div class="swiper-slide"><img src="<?=THEME_IMG?>/slide/photo-5.png" srcset="<?=THEME_IMG?>/slide/photo-5.png 2x" class="rounded" alt="slide-photo" /></div>
                            <div class="swiper-slide"><img src="<?=THEME_IMG?>/slide/photo-6.png" srcset="<?=THEME_IMG?>/slide/photo-6.png 2x" class="rounded" alt="slide-photo" /></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="wrapper">
    <div class="container pb-4 pb-sm-8 pb-md-10 pb-lg-0 mt-n16 mt-sm-n14 mt-md-n14 mt-lg-n16">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-1"></div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
                <figure class="rounded"><img src="<?=THEME_IMG?>/slide/photo.png" srcset="<?=THEME_IMG?>/slide/photo.png 2x" alt="" /></figure>
                <div class="row" data-cue="slideInUp">
                    <div class="col-xl-10 mx-auto">
                        <div class="card image-wrapper bg-full bg-overlay bg-overlay-400 bg-image text-white mt-n5 mt-lg-0 mt-lg-n50p mb-lg-n50p border-radius-lg-top" data-image-src="<?=THEME_IMG?>/bg-blue.jpg">
                            <div class="card-body p-5 p-xl-10" style="background:none;">
                                <div class="row row-shop align-items-center counter-wrapper gx-1 gy-1 text-center text-white">
                                    <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                        <h3 class="counter counter-lg text-white">2</h3>
                                        <p class="on-text-oneline on-font-primary"><?=Lang::get('ShopFood')?></p>
                                    </div>
                                    <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                        <h3 class="counter counter-lg text-white">2</h3>
                                        <p class="on-text-oneline on-font-primary"><?=Lang::get('ShopNoodle')?></p>
                                    </div>
                                    <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                        <h3 class="counter counter-lg text-white">2</h3>
                                        <p class="on-text-oneline on-font-primary"><?=Lang::get('ShopDrink')?></p>
                                    </div>
                                    <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                        <h3 class="counter counter-lg text-white">2</h3>
                                        <p class="on-text-oneline on-font-primary"><?=Lang::get('ShopOther')?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-1"></div>
        </div>
    </div>
</section>
<section class="wrapper">
    <div class="container py-1 pb-8 mt-lg-n10">
        <div class="mx-auto text-center">
            <i class="icn-flower text-leaf fs-30 opacity-25"></i>
            <h2 class="display-5 text-center mt-2 mb-10">8 <?=Lang::get('Restaurants')?></h2>
        </div>
        <div class="row gx-5 gy-5 align-items-center">
            <div class="col-12 col-lg-6">
                <div class="row g-5 text-center">
                    <div class="col-md-6">
                        <div class="card shadow-lg mb-6">
                            <figure class="card-img-top overlay overlay-1">
                                <a href="#"><img class="img-fluid" src="<?=THEME_IMG?>/slide/photo.png" alt=""><span class="bg"></span></a>
                                <figcaption>
                                    <h5 class="from-top mb-0">View Gallery</h5>
                                </figcaption>
                            </figure>
                            <div class="card-body p-4">
                                <h3 class="h4 mb-0">Shop 1</h3>
                            </div>
                        </div>
                        <div class="card shadow-lg">
                            <figure class="card-img-top overlay overlay-1">
                                <a href="#"><img class="img-fluid" src="<?=THEME_IMG?>/slide/photo.png" alt=""><span class="bg"></span></a>
                                <figcaption>
                                    <h5 class="from-top mb-0">View Gallery</h5>
                                </figcaption>
                            </figure>
                            <div class="card-body p-4">
                                <h3 class="h4 mb-0">Shop 2</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-lg mt-md-6 mb-6">
                            <figure class="card-img-top overlay overlay-1">
                                <a href="#"><img class="img-fluid" src="<?=THEME_IMG?>/slide/photo.png" alt=""><span class="bg"></span></a>
                                <figcaption>
                                    <h5 class="from-top mb-0">View Gallery</h5>
                                </figcaption>
                            </figure>
                            <div class="card-body p-4">
                                <h3 class="h4 mb-0">Shop 3</h3>
                            </div>
                        </div>
                        <div class="card shadow-lg">
                            <figure class="card-img-top overlay overlay-1">
                                <a href="#"><img class="img-fluid" src="<?=THEME_IMG?>/slide/photo.png" alt=""><span class="bg"></span></a>
                                <figcaption>
                                <h5 class="from-top mb-0">View Gallery</h5>
                                </figcaption>
                            </figure>
                            <div class="card-body p-4">
                                <h3 class="h4 mb-0">Shop 4</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="row g-5 text-center">
                    <div class="col-md-6">
                        <div class="card shadow-lg mb-6">
                            <figure class="card-img-top overlay overlay-1">
                                <a href="#"><img class="img-fluid" src="<?=THEME_IMG?>/slide/photo.png" alt=""><span class="bg"></span></a>
                                <figcaption>
                                    <h5 class="from-top mb-0">View Gallery</h5>
                                </figcaption>
                            </figure>
                            <div class="card-body p-4">
                                <h3 class="h4 mb-0">Shop 1</h3>
                            </div>
                        </div>
                        <div class="card shadow-lg">
                            <figure class="card-img-top overlay overlay-1">
                                <a href="#"><img class="img-fluid" src="<?=THEME_IMG?>/slide/photo.png" alt=""><span class="bg"></span></a>
                                <figcaption>
                                    <h5 class="from-top mb-0">View Gallery</h5>
                                </figcaption>
                            </figure>
                            <div class="card-body p-4">
                                <h3 class="h4 mb-0">Shop 2</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-lg mt-md-6 mb-6">
                            <figure class="card-img-top overlay overlay-1">
                                <a href="#"><img class="img-fluid" src="<?=THEME_IMG?>/slide/photo.png" alt=""><span class="bg"></span></a>
                                <figcaption>
                                    <h5 class="from-top mb-0">View Gallery</h5>
                                </figcaption>
                            </figure>
                            <div class="card-body p-4">
                                <h3 class="h4 mb-0">Shop 3</h3>
                            </div>
                        </div>
                        <div class="card shadow-lg">
                            <figure class="card-img-top overlay overlay-1">
                                <a href="#"><img class="img-fluid" src="<?=THEME_IMG?>/slide/photo.png" alt=""><span class="bg"></span></a>
                                <figcaption>
                                <h5 class="from-top mb-0">View Gallery</h5>
                                </figcaption>
                            </figure>
                            <div class="card-body p-4">
                                <h3 class="h4 mb-0">Shop 4</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include(APP_FOOTER); ?>