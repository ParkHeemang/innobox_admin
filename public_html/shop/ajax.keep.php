<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');
header("Content-Type: application/json; charset=utf-8");

$type = $_POST['type']; // cart,wish
$item = $_POST['item']; // it_id

$return = array();
//$return['code'] = "failure";
//$return['message'] = "처리할 수 없는 요청입니다.";

if ( ! in_array($type, array('cart','wish'))) {
    $return['code'] = "failure";
    $return['message'] = "처리할 수 없는 요청입니다.";
    die(json_encode($return));
}
if (empty($item)) {
    $return['code'] = "failure";
    $return['message'] = "상품이 지정되지 않았습니다.";
    die(json_encode($return));
}

// 장바구니에 담기
if ($type == "cart") {
    cart_item_clean(); // 보관기간이 지난 상품 삭제
    set_cart_id($sw_direct); // cart id 설정
    $tmp_cart_id = ($sw_direct) ? get_session('ss_cart_direct') : get_session('ss_cart_id');

    // 브라우저에서 쿠키를 허용하지 않은 경우라고 볼 수 있음.
    if ( ! $tmp_cart_id) {
        $return['code'] = "failure";
        $return['message'] = "더 이상 작업을 진행할 수 없습니다.\n브라우저의 인터넷 옵션에서 쿠키 허용을 사용으로 설정해 주십시오.";
        die(json_encode($return));
    }

    // 레벨(권한)이 상품구입 권한보다 작다면 상품을 구입할 수 없음.
    if ($member['mb_level'] < $default['de_level_sell'])  {
        $return['code'] = "failure";
        $return['message'] = "상품을 구입할 수 있는 권한이 없습니다.";
        die(json_encode($return));
    }

    // ----------------------------------
    // 상품 재정의, 옵션 체크
    $tmp = sql_fetch(" select * from {$g5['g5_shop_item_table']} where it_id = '$item' ");
    if ($tmp['it_option_subject']) {
        $return['code'] = "failure";
        $return['message'] = "옵션이 있는 상품이므로, 장바구니에 바로 담으실 수 없습니다.";
        die(json_encode($return));
    }

    $_POST['it_id'][] = $item;
    $_POST['io_type'][$item][] = 0;
    $_POST['io_id'][$item][] = '';
    $_POST['io_value'][$item][] = $tmp['it_name'];
    $_POST['ct_qty'][$item][] = 1;
    // ----------------------------------


    $count = count($_POST['it_id']);
    if ($count < 1) {
        $return['code'] = "failure";
        $return['message'] = "장바구니에 담을 상품을 선택하여 주십시오.";
        die(json_encode($return));
    }

    $ct_count = $ol_count = 0;
    for ($i=0; $i<$count; $i++) {
        $it_id = $_POST['it_id'][$i];
        $opt_count = count($_POST['io_id'][$it_id]);

        if ($opt_count && $_POST['io_type'][$it_id][0] != 0) {
            $return['code'] = "failure";
            $return['message'] = "상품의 선택옵션을 선택해 주십시오.";
            die(json_encode($return));
        }

        for ($k=0; $k<$opt_count; $k++) {
            if ($_POST['ct_qty'][$it_id][$k] < 1) {
                $return['code'] = "failure";
                $return['message'] = "수량은 1 이상 입력해 주십시오.";
                die(json_encode($return));
            }
        }

        // 상품정보
        $sql = " select * from {$g5['g5_shop_item_table']} where it_id = '$it_id' ";
        $it = sql_fetch($sql);
        if ( ! $it['it_id']) {
            $return['code'] = "failure";
            $return['message'] = "상품정보가 존재하지 않습니다.";
            die(json_encode($return));
        }

        // 바로구매에 있던 장바구니 자료를 지운다.
        if($i == 0 && $sw_direct)
            sql_query(" delete from {$g5['g5_shop_cart_table']} where od_id = '$tmp_cart_id' and ct_direct = 1 ", false);

        // 최소, 최대 수량 체크
        if($it['it_buy_min_qty'] || $it['it_buy_max_qty']) {
            $sum_qty = 0;
            for ($k=0; $k<$opt_count; $k++) {
                if($_POST['io_type'][$it_id][$k] == 0)
                    $sum_qty += $_POST['ct_qty'][$it_id][$k];
            }

            if ($it['it_buy_min_qty'] > 0 && $sum_qty < $it['it_buy_min_qty']) {
                $return['code'] = "failure";
                $return['message'] = $it['it_name']."의 선택옵션 개수 총합 ".number_format($it['it_buy_min_qty'])."개 이상 주문해 주십시오.";
                die(json_encode($return));
            }

            if ($it['it_buy_max_qty'] > 0 && $sum_qty > $it['it_buy_max_qty']) {
                $return['code'] = "failure";
                $return['message'] = $it['it_name']."의 선택옵션 개수 총합 ".number_format($it['it_buy_max_qty'])."개 이하로 주문해 주십시오.";
                die(json_encode($return));
            }

            // 기존에 장바구니에 담긴 상품이 있는 경우에 최대 구매수량 체크
            if ($it['it_buy_max_qty'] > 0) {
                $sql4 = " select sum(ct_qty) as ct_sum
                            from {$g5['g5_shop_cart_table']}
                            where od_id = '$tmp_cart_id'
                              and it_id = '$it_id'
                              and io_type = '0'
                              and ct_status = '쇼핑' ";
                $row4 = sql_fetch($sql4);

                if(($sum_qty + $row4['ct_sum']) > $it['it_buy_max_qty']) {
                    $return['code'] = "failure";
                    $return['message'] = $it['it_name']."의 선택옵션 개수 총합 ".number_format($it['it_buy_max_qty'])."개 이하로 주문해 주십시오.";
                    die(json_encode($return));
                }
            }
        }


        // 옵션정보를 얻어서 배열에 저장
        $opt_list = array();
        $sql = " select * from {$g5['g5_shop_item_option_table']} where it_id = '$it_id' order by io_no asc ";
        $result = sql_query($sql);
        $lst_count = 0;
        for($k=0; $row=sql_fetch_array($result); $k++) {
            $opt_list[$row['io_type']][$row['io_id']]['id'] = $row['io_id'];
            $opt_list[$row['io_type']][$row['io_id']]['use'] = $row['io_use'];
            $opt_list[$row['io_type']][$row['io_id']]['price'] = $row['io_price'];
            $opt_list[$row['io_type']][$row['io_id']]['stock'] = $row['io_stock_qty'];

            // 선택옵션 개수
            if(!$row['io_type'])
                $lst_count++;
        }

        //--------------------------------------------------------
        //  재고 검사, 바로구매일 때만 체크
        //--------------------------------------------------------
        // 이미 주문폼에 있는 같은 상품의 수량합계를 구한다.
        if ($sw_direct) {
            for ($k=0; $k<$opt_count; $k++) {
                $io_id = preg_replace(G5_OPTION_ID_FILTER, '', $_POST['io_id'][$it_id][$k]);
                $io_type = preg_replace('#[^01]#', '', $_POST['io_type'][$it_id][$k]);
                $io_value = $_POST['io_value'][$it_id][$k];

                $sql = " select SUM(ct_qty) as cnt from {$g5['g5_shop_cart_table']}
                          where od_id <> '$tmp_cart_id'
                            and it_id = '$it_id'
                            and io_id = '$io_id'
                            and io_type = '$io_type'
                            and ct_stock_use = 0
                            and ct_status = '쇼핑'
                            and ct_select = '1' ";
                $row = sql_fetch($sql);
                $sum_qty = $row['cnt'];

                // 재고 구함
                $ct_qty = $_POST['ct_qty'][$it_id][$k];
                if(!$io_id)
                    $it_stock_qty = get_it_stock_qty($it_id);
                else
                    $it_stock_qty = get_option_stock_qty($it_id, $io_id, $io_type);

                if ($ct_qty + $sum_qty > $it_stock_qty) {
                    $return['code'] = "failure";
                    $return['message'] = $io_value." 의 재고수량이 부족합니다.\n현재 재고수량 : " . number_format($it_stock_qty - $sum_qty) . " 개";
                    die(json_encode($return));
                }
            }
        }
        //--------------------------------------------------------

        // 옵션수정일 때 기존 장바구니 자료를 먼저 삭제
        if($act == 'optionmod')
            sql_query(" delete from {$g5['g5_shop_cart_table']} where od_id = '$tmp_cart_id' and it_id = '$it_id' ");

        // 장바구니에 Insert
        // 바로구매일 경우 장바구니가 체크된것으로 강제 설정
        if($sw_direct) {
            $ct_select = 1;
            $ct_select_time = G5_TIME_YMDHIS;
        } else {
            $ct_select = 0;
            $ct_select_time = '0000-00-00 00:00:00';
        }

        // 장바구니에 Insert
        $comma = '';
        $sql = " INSERT INTO {$g5['g5_shop_cart_table']}
                        ( od_id, mb_id, it_id, it_name, it_sc_type, it_sc_method, it_sc_price, it_sc_minimum, it_sc_qty, ct_status, ct_price, ct_point, ct_point_use, ct_stock_use, ct_option, ct_qty, ct_notax, io_id, io_type, io_price, ct_time, ct_ip, ct_send_cost, ct_direct, ct_select, ct_select_time )
                    VALUES ";

        for ($k=0; $k<$opt_count; $k++) {
            $io_id = preg_replace(G5_OPTION_ID_FILTER, '', $_POST['io_id'][$it_id][$k]);
            $io_type = preg_replace('#[^01]#', '', $_POST['io_type'][$it_id][$k]);
            $io_value = $_POST['io_value'][$it_id][$k];

            // 선택옵션정보가 존재하는데 선택된 옵션이 없으면 건너뜀
            if ($lst_count && $io_id == '')
                continue;

            // 구매할 수 없는 옵션은 건너뜀
            if ($io_id && !$opt_list[$io_type][$io_id]['use'])
                continue;

            $io_price = $opt_list[$io_type][$io_id]['price'];
            $ct_qty = $_POST['ct_qty'][$it_id][$k];

            // 구매가격이 음수인지 체크
            if ($io_type) {
                if ((int)$io_price < 0) {
                    $return['code'] = "failure";
                    $return['message'] = "구매금액이 음수인 상품은 구매할 수 없습니다.";
                    die(json_encode($return));
                }
            } else {
                if((int)$it['it_price'] + (int)$io_price < 0) {
                    $return['code'] = "failure";
                    $return['message'] = "구매금액이 음수인 상품은 구매할 수 없습니다.";
                    die(json_encode($return));
                }
            }

            // 동일옵션의 상품이 있으면 수량 더함
            $sql2 = " select ct_id, io_type, ct_qty
                        from {$g5['g5_shop_cart_table']}
                        where od_id = '$tmp_cart_id'
                          and it_id = '$it_id'
                          and io_id = '$io_id'
                          and ct_status = '쇼핑' ";
            $row2 = sql_fetch($sql2);
            if ($row2['ct_id']) {
                // 재고체크
                $tmp_ct_qty = $row2['ct_qty'];
                if(!$io_id)
                    $tmp_it_stock_qty = get_it_stock_qty($it_id);
                else
                    $tmp_it_stock_qty = get_option_stock_qty($it_id, $io_id, $row2['io_type']);

                if ($tmp_ct_qty + $ct_qty > $tmp_it_stock_qty) {
                    $return['code'] = "failure";
                    $return['message'] = $io_value." 의 재고수량이 부족합니다.\n현재 재고수량 : " . number_format($tmp_it_stock_qty) . " 개";
                    die(json_encode($return));
                }

                $sql3 = " update {$g5['g5_shop_cart_table']}
                            set ct_qty = ct_qty + '$ct_qty'
                            where ct_id = '{$row2['ct_id']}' ";
                sql_query($sql3);
                $ol_count++; // 중복 상품 수량 업데이트 카운트
                continue;
            }

            // 포인트
            $point = 0;
            if($config['cf_use_point']) {
                if($io_type == 0) {
                    $point = get_item_point($it, $io_id);
                } else {
                    $point = $it['it_supply_point'];
                }

                if($point < 0)
                    $point = 0;
            }

            // 배송비결제
            if ($it['it_sc_type'] == 1)
                $ct_send_cost = 2; // 무료
            else if($it['it_sc_type'] > 1 && $it['it_sc_method'] == 1)
                $ct_send_cost = 1; // 착불

            $sql .= $comma."( '$tmp_cart_id', '{$member['mb_id']}', '{$it['it_id']}', '".addslashes($it['it_name'])."', '{$it['it_sc_type']}', '{$it['it_sc_method']}', '{$it['it_sc_price']}', '{$it['it_sc_minimum']}', '{$it['it_sc_qty']}', '쇼핑', '{$it['it_price']}', '$point', '0', '0', '$io_value', '$ct_qty', '{$it['it_notax']}', '$io_id', '$io_type', '$io_price', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '$ct_send_cost', '$sw_direct', '$ct_select', '$ct_select_time' )";
            $comma = ' , ';
            $ct_count++;
        }

        if ($ct_count > 0) {
            sql_query($sql);
            $return['code'] = "success";
            $return['message'] = "장바구니에 담았습니다.";
            die(json_encode($return));
        }
        if ($ol_count > 0) {
            $return['code'] = "success";
            $return['message'] = "이미 장바구니에 담긴 상품이므로\n장바구니의 상품 구매 수량을 업데이트 하였습니다.";
            die(json_encode($return));
        }
    }
}

// 위시리스트에 담기
if ($type == "wish") {
    if (!$is_member) {
        //alert('회원 전용 서비스 입니다.', G5_BBS_URL.'/login.php?url='.urlencode($url));
        $return['code'] = "failure";
        $return['message'] = "회원 전용 서비스 입니다.";
        die(json_encode($return));
    }

    $it_id = $item;

    // 상품정보 체크
    $sql = " select it_id from {$g5['g5_shop_item_table']} where it_id = '$it_id' ";
    $row = sql_fetch($sql);

    if ( ! $row['it_id']) {
        $return['code'] = "failure";
        $return['message'] = "상품정보가 존재하지 않습니다.";
        die(json_encode($return));
    }

    $sql = " select wi_id from {$g5['g5_shop_wish_table']}
              where mb_id = '{$member['mb_id']}' and it_id = '$it_id' ";
    $row = sql_fetch($sql);

    if ( ! $row['wi_id']) { // 없다면 등록
        $sql = " insert {$g5['g5_shop_wish_table']}
                    set mb_id = '{$member['mb_id']}',
                        it_id = '$it_id',
                        wi_time = '".G5_TIME_YMDHIS."',
                        wi_ip = '$REMOTE_ADDR' ";
        sql_query($sql);
        $return['code'] = "success";
        $return['message'] = "위시리스트에 담았습니다.";
        die(json_encode($return));
    } else {
        $return['code'] = "failure";
        $return['message'] = "이미 위시리스트에 담긴 상품입니다.";
        die(json_encode($return));
    }
}

die(json_encode($return));
