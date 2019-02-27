<?php
include_once('./_common.php');

// 장바구니 갯수 구하기
echo get_cart_count(get_session('ss_cart_id'));
