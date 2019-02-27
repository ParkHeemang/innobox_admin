<?php
// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

// 주문상품 재고체크 js 파일
add_javascript('<script src="'.G5_JS_URL.'/shop.order.js"></script>', 0);

// 모바일 주문인지
$is_mobile_order = is_mobile();

set_session("ss_direct", $sw_direct);
// 장바구니가 비어있는가?
if ($sw_direct) {
    $tmp_cart_id = get_session('ss_cart_direct');
}
else {
    $tmp_cart_id = get_session('ss_cart_id');
}


// 새로운 주문번호 생성
$od_id = get_uniqid();
set_session('ss_order_id', $od_id);
$s_cart_id = $tmp_cart_id;
if($default['de_pg_service'] == 'inicis' || $default['de_inicis_lpay_use'])
    set_session('ss_order_inicis_id', $od_id);

$g5['title'] = '주문서 작성';

if(G5_IS_MOBILE)
    include_once('./head.php');
else
    include_once('./head.php');

// 기기별 주문폼 include
if($is_mobile_order) {
    $order_action_url = G5_HTTPS_MSHOP_URL.'/orderformupdate.php';
    require_once(G5_MSHOP_PATH.'/orderform.sub.php');
} else {
    $order_action_url = G5_HTTPS_SHOP_URL.'/orderformupdate.php';
    require_once(G5_SHOP_PATH.'/orderform.sub.php');
}


if(G5_IS_MOBILE)
    include_once('./tail.php');
else
    include_once('./tail.php');

?>
