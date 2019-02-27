<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/listtype.php');
    return;
}

// 테마에 listtype.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
    $theme_listtype_file = G5_THEME_SHOP_PATH.'/listtype.php';
    if(is_file($theme_listtype_file)) {
        include_once($theme_listtype_file);
        return;
        unset($theme_listtype_file);
    }
}

$type = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\s]/", "", $_REQUEST['type']);
if ($type == 1)      $g5['title'] = '히트상품';
else if ($type == 2) $g5['title'] = '추천상품';
else if ($type == 3) $g5['title'] = '최신상품';
else if ($type == 4) $g5['title'] = '인기상품';
else if ($type == 5) $g5['title'] = '할인상품';
else
    alert('상품유형이 아닙니다.');

include_once('./_head.php');

// 한페이지에 출력하는 이미지수 = $list_mod * $list_row
$list_mod   = $default['de_listtype_list_mod'];   // 한줄에 이미지 몇개씩 출력?
$list_row   = $default['de_listtype_list_row'];   // 한 페이지에 몇라인씩 출력?

$img_width  = $default['de_listtype_img_width'];  // 출력이미지 폭
$img_height = $default['de_listtype_img_height']; // 출력이미지 높이

if (!$skin)
    $skin = $default['de_listtype_list_skin'];
else
    $skin = preg_replace('#\.+[\\\/]#', '', $skin);

define('G5_SHOP_CSS_URL', G5_SHOP_SKIN_URL);
?>

<!-- 상품 목록 시작 { -->
<div id="sct">
    <?php
    $nav_skin = $skin.'/navigation.skin.php';
    if (!is_file($nav_skin)) {
        $nav_skin = G5_SHOP_SKIN_PATH.'/navigation.skin.php';
    }
    include $nav_skin;

    // 상품 출력순서가 있다면
    if ($sort != "") {
        $order_by = $sort.' '.$sortodr.' , it_order, it_id desc';
    } else {
        $order_by = 'it_order, it_id desc';
    }

    $error = '<p class="sct_noitem">등록된 상품이 없습니다.</p>';

    // 리스트 유형별로 출력
    $list_file = G5_SHOP_SKIN_PATH.'/'.$skin;
    if (file_exists($list_file)) {
        // 상품유형에 포함된 상품 갯수
        $cait = sql_fetch(" select count(*) as cnt from {$g5['g5_shop_item_table']} where it_use='1' and it_type".$type."='1' ");
        echo '<div id="sct_totalcnt"><u>'.$g5['title'].'</u> 총 <b>'.number_format($cait['cnt']).'</b>개의 상품이 등록되어 있습니다.</div>';

        // 상품 정렬 타입 변경
        echo '<div id="sct_sortlst">';
        $sort_skin = $skin_dir.'/list.sort.skin.php';
        if ( ! is_file($sort_skin)) {
            $sort_skin = G5_SHOP_SKIN_PATH.'/list.sort.skin.php';
        }
        include $sort_skin;

        // 상품 보기 타입 변경 버튼
        $sub_skin = $skin_dir.'/list.sub.skin.php';
        if ( ! is_file($sub_skin))
            $sub_skin = G5_SHOP_SKIN_PATH.'/list.sub.skin.php';
        include $sub_skin;
        echo '</div>';

        // 총몇개 = 한줄에 몇개 * 몇줄
        $items = $list_mod * $list_row;
        // 페이지가 없으면 첫 페이지 (1 페이지)
        if ($page < 1) $page = 1;
        // 시작 레코드 구함
        $from_record = ($page - 1) * $items;

        $list = new item_list();
        $list->set_type($type);
        $list->set_list_skin($list_file);
        $list->set_list_mod($list_mod);
        $list->set_list_row($list_row);
        $list->set_img_size($img_width, $img_height);
        $list->set_is_page(true);
        $list->set_order_by($order_by);
        $list->set_from_record($from_record);
        $list->set_view('it_img', true);
        $list->set_view('it_id', false);
        $list->set_view('it_name', true);
        $list->set_view('it_cust_price', false);
        $list->set_view('it_price', true);
        $list->set_view('it_icon', true);
        $list->set_view('sns', true);
        echo $list->run();

        // where 된 전체 상품수
        $total_count = $list->total_count;
        // 전체 페이지 계산
        $total_page  = ceil($total_count / $items);
    } else {
        echo '<div align="center">'.$skin.' 파일을 찾을 수 없습니다.<br>관리자에게 알려주시면 감사하겠습니다.</div>';
    }

    $qstr .= '&amp;type='.$type.'&amp;sort='.$sort;
    echo get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page=");
    ?>
</div>
<!-- } 상품 목록 끝 -->

<?php
include_once('./_tail.php');
?>
