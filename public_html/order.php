<?php
include_once('./_common.php');

if ($is_guest) {
    alert('로그인 한 회원만 접근하실 수 있습니다.', G5_BBS_URL.'/login.php');
}

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/order.php');
    return;
}

// 테마에 order.php 있으면 include
if(defined('G5_THEME_PATH')) {
    $theme_cr_file = G5_THEME_PATH.'/order.php';
    if(is_file($theme_cr_file)) {
        include_once($theme_cr_file);
        return;
        unset($theme_cr_file);
    }
}

$g5['title'] = '결제';
include_once(G5_BBS_PATH.'/_head.php');
?>


<?php
include_once(G5_BBS_PATH.'/_tail.php');
?>