<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');
add_javascript('<script src="'.G5_PLUGIN_URL.'/flexslider/jquery.flexslider.js"></script>', 1);
add_stylesheet('<link rel="stylesheet" href="'.G5_THEME_CSS_URL.'/jquery.flexslider.css">', 1);

?>

<header id="hd">
    <h1 id="hd_h1"><?php echo $g5['title'] ?></h1>

    <div class="to_content"><a href="#container">본문 바로가기</a></div>

    <?php
    if(defined('_INDEX_')) { // index에서만 실행
        include G5_MOBILE_PATH.'/newwin.inc.php'; // 팝업레이어
    } ?>

    <div id="hd_wrapper">
        <span id="opener"><img src="<?php echo G5_THEME_IMG_URL?>/m_menu_open.png"></span>
        <div id="logo">
            <a href="<?php echo G5_URL ?>"><img src="<?php echo G5_THEME_IMG_URL ?>/m_logo.png" alt="<?php echo $config['cf_title']; ?>"></a>
        </div>
    </div>
</header>

<div id="gnb_bg">
    <div id="gnb">
        <div id="gnb_hd">
            <div class="logo">
                <a href="<?php echo G5_URL?>"><img src="<?php echo G5_THEME_IMG_URL?>/m_menu_logo.png"></a>
            </div>
            <ul>
                <?php if ($is_member) { ?>
                <li><a href="<?php echo G5_BBS_URL?>/logout.php"><img src="<?php echo G5_THEME_IMG_URL?>/login.png">LOGOUT</a></li>
                <li><a href="<?php echo G5_BBS_URL ?>/member_confirm.php?url=<?php echo G5_BBS_URL ?>/register_form.php"><img src="<?php echo G5_THEME_IMG_URL?>/join_ico.png">MYINFO</a></li>
                <?php } else { ?>
                <li><a href="<?php echo G5_BBS_URL?>/login.php"><img src="<?php echo G5_THEME_IMG_URL?>/login.png">LOGIN</a></li>
                <li><a href="<?php echo G5_BBS_URL?>/register.php"><img src="<?php echo G5_THEME_IMG_URL?>/join_ico.png">JOIN US</a></li>
                <?php } ?>
                <!--
                <li><a href="#none" class="empty"><img src="<?php echo G5_THEME_IMG_URL?>/pay_ico.png">개인결제</a></li>
                -->
            </ul>
            <img src="<?php echo G5_THEME_IMG_URL?>/m_close.png" class="close">
        </div>
        <div id="gnb_menu">
            <div class="row"><a href="<?php echo G5_BBS_URL?>/board.php?bo_table=portfolio" class="a1">포트폴리오</a></div>
            <div class="row"><a href="<?php echo G5_BBS_URL?>/content.php?co_id=shoppingmall" class="a1">쇼핑몰</a></div>
            <div class="row"><a href="<?php echo G5_BBS_URL?>/content.php?co_id=homepage" class="a1">홈페이지</a></div>
            <div class="row"><a href="<?php echo G5_BBS_URL?>/content.php?co_id=mobile" class="a1">모바일</a></div>
            <div class="row">
                <a href="#none" class="a1 ch">마케팅 서비스</a>
                <div class="row2 ssHide">
                    <a href="<?php echo G5_BBS_URL?>/content.php?co_id=marketing">마케팅</a>
                    <a href="http://www.keywordup.co.kr/" target="_blank">키워드업</a>
                </div>
            </div>
            <div class="row">
                <a href="#none" class="a1 ch">유지보수</a>
                <div class="row2 ssHide">
                    <a href="<?php echo G5_BBS_URL?>/content.php?co_id=maintenance">유지보수</a>
                    <a href="<?php echo G5_BBS_URL?>/board.php?bo_table=maintenance">유지보수 전용게시판</a>
                </div>
            </div>
            <div class="row">
                <a href="#none" class="a1 ch">고객센터</a>
                <div class="row2 ssHide">
                    <a href="<?php echo G5_BBS_URL?>/board.php?bo_table=notice">공지사항</a>
                    <a href="<?php echo G5_BBS_URL?>/board.php?bo_table=reservation">방문요청</a>
                    <a href="<?php echo G5_BBS_URL?>/write.php?bo_table=cscenter">견적상담</a>
                </div>
            </div>
        </div>
        <div id="gnb_cc">
            <h3>CALL CENTER</h3>
            <h4><a href="tel:010-2738-3653" style="color:#fff;">010-2738-3653</a></h4>
            <p>평일 : 09:30 ~ 19:00 / 점심 : 12:00 ~ 13:00</br>토,일 및 공휴일 휴무</p>
        </div>
        <div id="gnb_copy">COPYRIGHT innobox ALL RIGHTS RESEVED</div>

    </div>
</div>
<script>
    $('.empty').on('click',function(){
        alert('준비중입니다.');
    });
    $('#opener').on('click',function(){
        $('#gnb_bg').addClass('active');
    });
    $('.close').on('click',function(){
        $('#gnb_bg').removeClass('active');
    });
    $('.ch').on('click',function(){

        if($(this).siblings('.row2').is(':visible') == false ){
            $('.ch').removeClass('on');
            $(this).addClass('on');
            $('.row2').addClass('ssHide');
            $(this).siblings('.row2').removeClass('ssHide');
        } else {
            $('.ch').removeClass('on');
            $('.row2').addClass('ssHide');
        }

    });
</script>
<hr>

<div id="wrapper">

    <div id="container">
        <?php if ((!$bo_table || $w == 's' ) && !defined("_INDEX_")) { ?><div id="container_title"><?php echo $g5['title'] ?></div><?php } ?>
