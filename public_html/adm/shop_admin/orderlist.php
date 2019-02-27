<?php
$sub_menu = '400400';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '주문내역';
include_once('../admin.head.php');
//include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$colspan = '14';

// 출력모드 2017-07-21
$mode = empty($_COOKIE['OrderPrintMode']) ? 'date' : $_COOKIE['OrderPrintMode'];

$where = array();

$doc = strip_tags($doc);
$sort1 = in_array($sort1, array('od_id', 'od_cart_price', 'od_receipt_price', 'od_cancel_price', 'od_misu', 'od_cash')) ? $sort1 : '';
$sort2 = in_array($sort2, array('desc', 'asc')) ? $sort2 : 'desc';
$sel_field = get_search_string($sel_field);
if( !in_array($sel_field, array('od_id', 'mb_id', 'od_name', 'od_tel', 'od_hp', 'od_b_name', 'od_b_tel', 'od_b_hp', 'od_deposit_name', 'od_invoice')) ){   //검색할 필드 대상이 아니면 값을 제거
    $sel_field = '';
}
$od_status = get_search_string($od_status);
$search = get_search_string($search);
if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = '';
if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = '';

$sql_search = "";
if ($search != "") {
    if ($sel_field != "") {
        $where[] = " $sel_field like '%$search%' ";
    }

    if ($save_search != $search) {
        $page = 1;
    }
}

if ($od_status) {
    switch($od_status) {
        case '전체취소':
            $where[] = " od_status = '취소' ";
            break;
        case '부분취소':
            $where[] = " od_status IN('주문', '입금', '준비', '배송', '완료') and od_cancel_price > 0 ";
            break;
        default:
            $where[] = " od_status = '$od_status' ";
            break;
    }

    switch ($od_status) {
        case '주문' :
            $sort1 = "od_id";
            $sort2 = "desc";
            break;
        case '입금' :   // 결제완료
            $sort1 = "od_receipt_time";
            $sort2 = "desc";
            break;
        case '배송' :   // 배송중
            $sort1 = "od_invoice_time";
            $sort2 = "desc";
            break;
    }
}

if ($od_settle_case) {
    $where[] = " od_settle_case = '$od_settle_case' ";
}

if ($od_misu) {
    $where[] = " od_misu != 0 ";
}

if ($od_cancel_price) {
    $where[] = " od_cancel_price != 0 ";
}

if ($od_refund_price) {
    $where[] = " od_refund_price != 0 ";
}

if ($od_receipt_point) {
    $where[] = " od_receipt_point != 0 ";
}

if ($od_coupon) {
    $where[] = " ( od_cart_coupon > 0 or od_coupon > 0 or od_send_coupon > 0 ) ";
}

if ($od_escrow) {
    $where[] = " od_escrow = 1 ";
}

if ($mode == 'proc') {
	//$where[] = " od_time > date_add(curdate(), interval -1 month) ";
	if (empty($fr_date) && empty($to_date)) {
		$fr_date = date('Y-m-d', strtotime('-1 month')-86400);
		$to_date = date('Y-m-d');
	}
}

if ($fr_date && $to_date) {
    $where[] = " od_time between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
}

if ($where) {
    $sql_search = " where " . implode(" and ", $where);
}

if ($sel_field == "")  $sel_field = "od_id";
if ($sort1 == "") $sort1 = "od_id";
if ($sort2 == "") $sort2 = "desc";

$sql_common = " from {$g5['g5_shop_order_table']} ";

$sql = " select count(od_id) as cnt $sql_common  $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 20; //$config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$qstr1 = "od_status=".urlencode($od_status)."&amp;od_settle_case=".urlencode($od_settle_case)."&amp;od_misu=$od_misu&amp;od_cancel_price=$od_cancel_price&amp;od_refund_price=$od_refund_price&amp;od_receipt_point=$od_receipt_point&amp;od_coupon=$od_coupon&amp;fr_date=$fr_date&amp;to_date=$to_date&amp;sel_field=$sel_field&amp;search=$search&amp;save_search=$search";
if($default['de_escrow_use'])
    $qstr1 .= "&amp;od_escrow=$od_escrow";
$qstr = "$qstr1&amp;sort1=$sort1&amp;sort2=$sort2&amp;page=$page";

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="btn btn-info">전체목록</a>';

// 주문삭제 히스토리 테이블 필드 추가
if(!sql_query(" select mb_id from {$g5['g5_shop_order_delete_table']} limit 1 ", false)) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_order_delete_table']}`
                    ADD `mb_id` varchar(20) NOT NULL DEFAULT '' AFTER `de_data`,
                    ADD `de_ip` varchar(255) NOT NULL DEFAULT '' AFTER `mb_id`,
                    ADD `de_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `de_ip` ", true);
}


//CSS

add_stylesheet('<link rel="stylesheet" href="'.G5_PHP_URL. 'bower_components/bootstrap/dist/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 5);
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL2.'/bootstrap.custom.css?ver='.G5_CSS_VER.'"></script>',8);


//JS DataTables
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);

?>



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
		<h1 class="member_list_title">
		주문내역
			<small>order list</small>
		</h1>
		<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">회원관리</a></li>
		<li class="active">회원정보리스트</li>
		</ol>
	</section>
	<!-- Main content -->
	
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">					
					</div>
					<!-- /.box-header -->
					<div class="box-body">		
						<div class="pk_order">
							<form name="frmorderlist" class="local_sch01 local_sch" id="frmorderlist">
								<input type="hidden" name="doc" value="<?php echo $doc; ?>">
								<input type="hidden" name="sort1" value="<?php echo $sort1; ?>">
								<input type="hidden" name="sort2" value="<?php echo $sort2; ?>">
								<input type="hidden" name="page" value="<?php echo $page; ?>">
								<input type="hidden" name="save_search" value="<?php echo $search; ?>">

								<label for="sel_field" class="sound_only">검색대상</label>
								<select name="sel_field" id="sel_field" class="form-control">
									<option value="od_id" <?php echo get_selected($sel_field, 'od_id'); ?>>주문번호</option>
									<option value="mb_id" <?php echo get_selected($sel_field, 'mb_id'); ?>>회원 ID</option>
									<option value="od_name" <?php echo get_selected($sel_field, 'od_name'); ?>>주문자</option>
									<option value="od_tel" <?php echo get_selected($sel_field, 'od_tel'); ?>>주문자전화</option>
									<option value="od_hp" <?php echo get_selected($sel_field, 'od_hp'); ?>>주문자핸드폰</option>
									<option value="od_b_name" <?php echo get_selected($sel_field, 'od_b_name'); ?>>받는분</option>
									<option value="od_b_tel" <?php echo get_selected($sel_field, 'od_b_tel'); ?>>받는분전화</option>
									<option value="od_b_hp" <?php echo get_selected($sel_field, 'od_b_hp'); ?>>받는분핸드폰</option>
									<option value="od_deposit_name" <?php echo get_selected($sel_field, 'od_deposit_name'); ?>>입금자</option>
									<option value="od_invoice" <?php echo get_selected($sel_field, 'od_invoice'); ?>>운송장번호</option>
								</select>

								<label for="search" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
								<input type="text" name="search" value="<?php echo $search; ?>" id="search" required class="required frm_input form-control" autocomplete="off">
								<input type="submit" value="검색" class="btn_submit btn btn-primary">
								<?php echo $listall; ?>
								<div class="text-right">
								<a href="javascript:forderlist_export();" class="btnbtn confirm float-right btn btn-success">주문내역 다운로드</a>
								</div>
							</form>

							<form name="schorderlist" class="local_sch02 local_sch" id="schorderlist">
								<div>
									<strong>주문상태</strong>
									<input type="radio" name="od_status" value="" id="od_status_all"    <?php echo get_checked($od_status, '');     ?>>
									<label for="od_status_all">전체</label>
									<input type="radio" name="od_status" value="주문" id="od_status_odr" <?php echo get_checked($od_status, '주문'); ?>>
									<label for="od_status_odr">주문</label>
									<input type="radio" name="od_status" value="입금" id="od_status_income" <?php echo get_checked($od_status, '입금'); ?>>
									<label for="od_status_income">입금</label>
									<input type="radio" name="od_status" value="준비" id="od_status_rdy" <?php echo get_checked($od_status, '준비'); ?>>
									<label for="od_status_rdy">준비</label>
									<input type="radio" name="od_status" value="배송" id="od_status_dvr" <?php echo get_checked($od_status, '배송'); ?>>
									<label for="od_status_dvr">배송</label>
									<input type="radio" name="od_status" value="완료" id="od_status_done" <?php echo get_checked($od_status, '완료'); ?>>
									<label for="od_status_done">완료</label>
									<input type="radio" name="od_status" value="전체취소" id="od_status_cancel" <?php echo get_checked($od_status, '전체취소'); ?>>
									<label for="od_status_cancel">전체취소</label>
									<input type="radio" name="od_status" value="부분취소" id="od_status_pcancel" <?php echo get_checked($od_status, '부분취소'); ?>>
									<label for="od_status_pcancel">부분취소</label>
								</div>

								<div>
									<strong>결제수단</strong>
									<input type="radio" name="od_settle_case" value="" id="od_settle_case01"        <?php echo get_checked($od_settle_case, '');          ?>>
									<label for="od_settle_case01">전체</label>
									<input type="radio" name="od_settle_case" value="무통장" id="od_settle_case02"   <?php echo get_checked($od_settle_case, '무통장');    ?>>
									<label for="od_settle_case02">무통장</label>
									<input type="radio" name="od_settle_case" value="가상계좌" id="od_settle_case03" <?php echo get_checked($od_settle_case, '가상계좌');  ?>>
									<label for="od_settle_case03">가상계좌</label>
									<input type="radio" name="od_settle_case" value="계좌이체" id="od_settle_case04" <?php echo get_checked($od_settle_case, '계좌이체');  ?>>
									<label for="od_settle_case04">계좌이체</label>
									<input type="radio" name="od_settle_case" value="휴대폰" id="od_settle_case05"   <?php echo get_checked($od_settle_case, '휴대폰');    ?>>
									<label for="od_settle_case05">휴대폰</label>
									<input type="radio" name="od_settle_case" value="신용카드" id="od_settle_case06" <?php echo get_checked($od_settle_case, '신용카드');  ?>>
									<label for="od_settle_case06">신용카드</label>
									<input type="radio" name="od_settle_case" value="간편결제" id="od_settle_case07" <?php echo get_checked($od_settle_case, '간편결제');  ?>>
									<label for="od_settle_case07">PG간편결제</label>
									<input type="radio" name="od_settle_case" value="KAKAOPAY" id="od_settle_case08" <?php echo get_checked($od_settle_case, 'KAKAOPAY');  ?>>
									<label for="od_settle_case08">KAKAOPAY</label>
								</div>

								<div>
									<strong>기타선택</strong>
									<input type="checkbox" name="od_misu" value="Y" id="od_misu01" <?php echo get_checked($od_misu, 'Y'); ?>>
									<label for="od_misu01">미수금</label>
									<input type="checkbox" name="od_cancel_price" value="Y" id="od_misu02" <?php echo get_checked($od_cancel_price, 'Y'); ?>>
									<label for="od_misu02">반품,품절</label>
									<input type="checkbox" name="od_refund_price" value="Y" id="od_misu03" <?php echo get_checked($od_refund_price, 'Y'); ?>>
									<label for="od_misu03">환불</label>
									<input type="checkbox" name="od_receipt_point" value="Y" id="od_misu04" <?php echo get_checked($od_receipt_point, 'Y'); ?>>
									<label for="od_misu04">포인트주문</label>
									<input type="checkbox" name="od_coupon" value="Y" id="od_misu05" <?php echo get_checked($od_coupon, 'Y'); ?>>
									<label for="od_misu05">쿠폰</label>
									<?php if($default['de_escrow_use']) { ?>
									<input type="checkbox" name="od_escrow" value="Y" id="od_misu06" <?php echo get_checked($od_escrow, 'Y'); ?>>
									<label for="od_misu06">에스크로</label>
									<?php } ?>
								</div>

								<div class="sch_last">
									<strong>주문일자</strong>
									<input type="text" id="fr_date"  name="fr_date" value="<?php echo $fr_date; ?>" class="frm_input form-control" size="10" maxlength="10"> ~
									<input type="text" id="to_date"  name="to_date" value="<?php echo $to_date; ?>" class="frm_input form-control" size="10" maxlength="10">
									<button type="button" class="btn btn-default" onclick="javascript:set_date('오늘');">오늘</button>
									<button type="button" class="btn btn-default" onclick="javascript:set_date('어제');">어제</button>
									<button type="button" class="btn btn-default" onclick="javascript:set_date('이번주');">이번주</button>
									<button type="button" class="btn btn-default" onclick="javascript:set_date('이번달');">이번달</button>
									<button type="button" class="btn btn-default" onclick="javascript:set_date('지난주');">지난주</button>
									<button type="button" class="btn btn-default"  onclick="javascript:set_date('지난달');">지난달</button>
									<button type="button" class="btn btn-default"  onclick="javascript:set_date('전체');">전체</button>
									<input type="submit" value="검색" class="btn_submit btn btn-primary">
									<?php echo $listall; ?>
								</div>
							</form>

							<form name="forderlist" id="forderlist" onsubmit="return forderlist_submit(this);" method="post" autocomplete="off">
							<input type="hidden" name="search_od_status" value="<?php echo $od_status; ?>">

							<div class="dashboard">
								<div class="total_count">
									주문내역 <b><?php echo number_format($total_count); ?></b> 건
									<?php if($od_status == '준비' && $total_count > 0) { ?>
									| <a href="./orderdelivery.php" id="order_delivery" class="ov_a">엑셀배송처리</a>
									<?php } ?>
								</div>
								<div class="mode_button">
									<a href="javascript:;" data-mode="date"><img src="<?php echo G5_ADMIN_URL; ?>/img/btn_orderdate_<?php echo ($mode === 'date') ? 'on' : 'off'; ?>.gif" align="absmiddle"></a>
									<a href="javascript:;" data-mode="proc"><img src="<?php echo G5_ADMIN_URL; ?>/img/btn_orderproc_<?php echo ($mode === 'proc') ? 'on' : 'off'; ?>.gif" align="absmiddle"></a>
								</div>
							</div>


							<!-- ###  주문일로 보기  ############################################# -->
							<?php if ($mode == 'date') { ?>
							<div class="tbl_head02 tbl_wrap">
								<table id="example1" class="table table-bordered table-striped dataTable member_list">
								<caption>주문 내역 목록</caption>
								<thead>
								<tr>
									<th scope="col">
										<label for="chkall" class="sound_only">주문 전체</label>
										<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
									</th>
									<th scope="col">번호</th>
									<th scope="col">주문일시</th>
									<th scope="col" id="th_odrnum"><a href="<?php echo title_sort("od_id", 1)."&amp;$qstr1"; ?>">주문번호</a></th>
									<th scope="col" class="td_odrtime">주문상품</th>
									<th scope="col">장바구니</th>
									<th scope="col" id="th_odrid">회원ID</th>
									<th scope="col" id="th_odrer">주문자</th>
									<th scope="col" id="th_odrertel">주문자전화</th>
									<th scope="col" id="th_recvr">받는분</th>
									<th scope="col">주문금액</th>
									<th scope="col" id="odrpay">결제수단</th>
									<th scope="col">결제금액</th>
									<th scope="col" id="odrstat">주문상태</th>
								</tr>
								</thead>
								<tbody>
								<?php
								$sql  = "select *,
											  (od_cart_coupon + od_coupon + od_send_coupon) as couponprice
										$sql_common $sql_search
										order by $sort1 $sort2
										limit $from_record, $rows ";
								$result = sql_query($sql);

								$num = $total_count - ($page - 1) * $rows; // 가상번호
								for ($i=0; $row=sql_fetch_array($result); $i++)
								{
									// 주문일시
									$od_time = substr($row['od_time'], 2, 14);
									// 주문상품
									$oct = sql_fetch("select it_name from {$g5['g5_shop_cart_table']} where od_id='{$row['od_id']}'  order by (case when ct_status in ('취소','품절','반품') then 1 else 0 end), ct_time desc limit 1");

									// 결제 수단
									$s_receipt_way = $s_br = "";
									if ($row['od_settle_case'])
									{
										$s_receipt_way = $row['od_settle_case'];
										$s_br = '<br />';

										// 간편결제
										if($row['od_settle_case'] == '간편결제') {
											switch($row['od_pg']) {
												case 'lg':
													$s_receipt_way = 'PAYNOW';
													break;
												case 'inicis':
													$s_receipt_way = 'KPAY';
													break;
												case 'kcp':
													$s_receipt_way = 'PAYCO';
													break;
												default:
													$s_receipt_way = $row['od_settle_case'];
													break;
											}
										}
									}
									else
									{
										$s_receipt_way = '결제수단없음';
										$s_br = '<br />';
									}

									if ($row['od_receipt_point'] > 0)
										$s_receipt_way .= "+포인트";

									$mb_nick = $row['od_name']; // get_sideview($row['mb_id'], get_text($row['od_name']), $row['od_email'], '');

									$od_cnt = 0;
									if ($row['mb_id'])
									{
										$sql2 = " select count(*) as cnt from {$g5['g5_shop_order_table']} where mb_id = '{$row['mb_id']}' ";
										$row2 = sql_fetch($sql2);
										$od_cnt = $row2['cnt'];
									}

									// 주문 번호에 device 표시
									$od_mobile = '';
									if($row['od_mobile'])
										$od_mobile = '모바일주문';
									if($row['od_mobile']) {
										$od_device = '<img src="'.G5_ADMIN_URL.'/img/btn_mobile.gif" alt="Mobile주문">';
									} else {
										$od_device = '<img src="'.G5_ADMIN_URL.'/img/btn_pc.gif" alt="PC주문">';
									}

									// 주문번호에 - 추가
									switch(strlen($row['od_id'])) {
										case 16:
											$disp_od_id = substr($row['od_id'],0,8).'-'.substr($row['od_id'],8);
											break;
										default:
											$disp_od_id = substr($row['od_id'],0,6).'-'.substr($row['od_id'],6);
											break;
									}

									// 주문 번호에 에스크로 표시
									$od_paytype = '';
									if($row['od_test'])
										//$od_paytype .= '<span class="list_test">테스트</span>';
										$od_paytype .= '<img src="'.G5_ADMIN_URL.'/img/btn_paytest.gif" alt="테스트"> ';

									if($default['de_escrow_use'] && $row['od_escrow'])
										//$od_paytype .= '<span class="list_escrow">에스크로</span>';
										$od_paytype .= '<img src="'.G5_ADMIN_URL.'/img/btn_escrow.gif" alt="에스크로"> ';

									$uid = md5($row['od_id'].$row['od_time'].$row['od_ip']);

									$invoice_time = is_null_time($row['od_invoice_time']) ? G5_TIME_YMDHIS : $row['od_invoice_time'];
									$delivery_company = $row['od_delivery_company'] ? $row['od_delivery_company'] : $default['de_delivery_company'];

									$bg = 'bg'.($i%2);
									$td_color = 0;
									if($row['od_cancel_price'] > 0) {
										$bg .= 'cancel';
										$td_color = 1;
									}

									if ($row['od_status'] == '취소') {
										// 전체취소
									} else if (in_array($row['od_status'], array('주문','입금','준비','배송','완료')) && $row['od_cancel_price'] > 0) {
										// 부분취소
									} else {
										// 취소없음
									}

									// od_status class num
									switch ($row['od_status']) {
										case '주문' : $status_num = '0'; break;
										case '입금' : $status_num = '1'; break;
										case '준비' : $status_num = '2'; break;
										case '배송' : $status_num = '3'; break;
										case '완료' : $status_num = '4'; break;
										case '취소' : $status_num = '5'; break;
										default : $status_num = '';
									}
									$od_phone = get_text($row['od_hp']);
									$od_phone = empty($od_phone) ? get_text($row['od_tel']) : $od_phone;
									$od_phone = preg_replace('%([0-9]{2,3})[\-\s]?([0-9]{3,4})[\-\s]?([0-9]{4})%', '$1-$2-$3', $od_phone);
								?>
								<tr>
									<td class="td_chk">
										<input type="hidden" name="od_id[<?php echo $i ?>]" value="<?php echo $row['od_id'] ?>" id="od_id_<?php echo $i ?>">
										<label for="chk_<?php echo $i; ?>" class="sound_only">주문번호 <?php echo $row['od_id']; ?></label>
										<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>" class="chk">
									</td>
									<td><?php echo $num--; ?></td>
									<td class="td_odrtime"><?php echo $od_time; ?></td>
									<td headers="th_ordnum" class="td_odrnum2">
										<a href="./orderform.php?od_id=<?php echo $row['od_id']; ?>&amp;<?php echo $qstr; ?>"><?php echo $disp_od_id; ?></a>
										<a href="./orderform.php?od_id=<?php echo $row['od_id']; ?>&amp;<?php echo $qstr; ?>" target="_blank"><img src="<?php echo G5_ADMIN_URL; ?>/img/btn_newwindow.gif" alt="새창으로 주문내역 보기" /></a>
										<a href="<?php echo G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $row['od_id']; ?>&amp;uid=<?php echo $uid; ?>" target="_blank"><img src="<?php echo G5_ADMIN_URL; ?>/img/btn_shoppingmall.gif" alt="쇼핑몰에서 주문내역 보기" /></a>
										<?php echo $od_device; ?>
										<?php echo $od_paytype; ?>
									</td>
									<td class="td_odrtitle">
										<a href="./orderform.php?od_id=<?php echo $row['od_id']; ?>&amp;<?php echo $qstr; ?>" class="orderitem" data-order="<?php echo $row['od_id']; ?>">
											<?php echo cut_str($oct['it_name'], 20); ?>
											<?php echo ($row['od_cart_count'] > 1) ? ' 외 '.($row['od_cart_count']-1).'건' : ''; ?>
										</a>
									</td>
									<td headers="th_odrcnt"><?php echo $row['od_cart_count']; ?>건</td>
									<td headers="th_odrid">
										<?php if ($row['mb_id']) { ?>
										<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?sort1=<?php echo $sort1; ?>&amp;sort2=<?php echo $sort2; ?>&amp;sel_field=mb_id&amp;search=<?php echo $row['mb_id']; ?>"><?php echo $row['mb_id']; ?></a>
										<?php } ?>
									</td>
									<td headers="th_odrer" class="td_name"><?php echo $mb_nick; ?></td>
									<td headers="th_odrertel" class="td_tel"><?php echo $od_phone; ?></td>
									<td headers="th_recvr" class="td_name"><a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?sort1=<?php echo $sort1; ?>&amp;sort2=<?php echo $sort2; ?>&amp;sel_field=od_b_name&amp;search=<?php echo get_text($row['od_b_name']); ?>"><?php echo get_text($row['od_b_name']); ?></a></td>
									<td class="td_numsum"><?php echo number_format($row['od_cart_price'] + $row['od_send_cost'] + $row['od_send_cost2']); ?></td>
									<td headers="th_odrpay" class="td_payby">
										<input type="hidden" name="current_settle_case[<?php echo $i ?>]" value="<?php echo $row['od_settle_case'] ?>">
										<?php echo $s_receipt_way; ?>
									</td>
									<td class="td_numsum"><?php echo number_format($row['od_receipt_price'] - $row['od_refund_price']); ?></td>
									<td headers="th_odrstat" class="td_odrstatus">
										<input type="hidden" name="current_status[<?php echo $i ?>]" value="<?php echo $row['od_status'] ?>">
										<a href="./orderform.php?od_id=<?php echo $row['od_id']; ?>&amp;<?php echo $qstr; ?>" class="status<?php echo $status_num; ?>"><?php echo $row['od_status']; ?></a>
									</td>
								</tr>
								<?php
									$tot_itemcount     += $row['od_cart_count'];
									$tot_orderprice    += ($row['od_cart_price'] + $row['od_send_cost'] + $row['od_send_cost2']);
									$tot_ordercancel   += $row['od_cancel_price'];
									$tot_receiptprice  += $row['od_receipt_price'];
									$tot_refundprice   += $row['od_refund_price'];
									$tot_couponprice   += $row['couponprice'];
									$tot_misu          += $row['od_misu'];
								}
								sql_free_result($result);
								if ($i == 0)
									echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
								?>
								</tbody>
								<tfoot>
								<tr class="orderlist">
									<th scope="row" colspan="4">&nbsp;</th>
									<th scope="row">&nbsp;</th>
									<th scope="row"><?php echo number_format($tot_itemcount); ?>건</th>
									<th scope="row" colspan="3">&nbsp;</th>
									<th scope="row">합 계</th>
									<th scope="row"><?php echo number_format($tot_orderprice); ?></th>
									<th scope="row">&nbsp;</th>
									<th scope="row"><?php echo number_format($tot_receiptprice - $tot_refundprice); ?></th>
									<th scope="row">&nbsp;</th>
								</tr>
								</tfoot>
								</table>
							</div>

							<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
							<?php } // End if ($mode == 'date') ?>





							<!-- ###  주문처리흐름으로 보기  ##################################### -->
							<?php if ($mode == 'proc') { ?>
							<div class="tbl_head02 tbl_wrap">
								<table id="sodr_list example1" class="table table-bordered table-striped dataTable member_list">
								<caption>주문 내역 목록</caption>
								<thead>
								<tr>
									<th scope="col">
										<label for="chkall" class="sound_only">주문 전체</label>
										<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
									</th>
									<th scope="col">번호</th>
									<th scope="col">주문일시</th>
									<th scope="col" id="th_odrnum"><a href="<?php echo title_sort("od_id", 1)."&amp;$qstr1"; ?>">주문번호</a></th>
									<th scope="col" class="td_odrtitle">주문상품</th>
									<th scope="col">장바구니</th>
									<th scope="col" id="th_odrid">회원ID</th>
									<th scope="col" id="th_odrer">주문자</th>
									<th scope="col" id="th_odrertel">주문자전화</th>
									<th scope="col" id="th_recvr">받는분</th>
									<th scope="col">주문금액</th>
									<th scope="col" id="odrpay">결제수단</th>
									<th scope="col">결제금액</th>
									<th scope="col" id="odrstat">주문상태</th>
								</tr>
								</thead>

								<?php
								// 처리흐름별
								$dfnst = array('주문','입금','준비','배송','완료','취소');
								if ($od_step) {
									$ordst = $od_step;
								} else {
									switch ($od_status) {
										case '주문' :
										case '입금' :
										case '준비' :
										case '배송' :
										case '완료' : $ordst = array($od_status); break;
										case '전체취소' : $ordst = array_slice($dfnst, -1); break;
										case '부분취소' : $ordst = array_slice($dfnst, 0, 5); break;
										case '취소' : 
										default : $ordst = $dfnst;
									}
								}

								$s = 0; // 처리상태 순번
								$c = 0; // 체크박스 순번
								foreach ($ordst as $ordno=>$orderstatus) { 
									$s++;
									$lst_itemcount = 0;
									$lst_orderprice = 0;
									$lst_ordercancel = 0;
									$lst_receiptprice = 0;
									$lst_couponprice = 0;
									$lst_misu = 0;
								?>
								<tbody class="odgroup odgroup<?php echo $ordno; ?>">
								<tr><td colspan="<?php echo $colspan; ?>" class="caption"><?php echo $orderstatus; ?></td></td>
								<?php
								$sql = "select *,
											(od_cart_coupon + od_coupon + od_send_coupon) as couponprice
										$sql_common $sql_search ";
								$sql .= empty($sql_search) ? " where " : " and ";
								$sql .= " od_status = '$orderstatus' ";
								$sql .= " order by $sort1 $sort2 ";
								$result = sql_query($sql);
								$num = sql_num_rows($result); // 가상번호
								for ($i=0; $row=sql_fetch_array($result); $i++)
								{
									// 주문일시
									$od_time = substr($row['od_time'], 2, 14);
									// 주문상품
									$oct = sql_fetch("select it_name from {$g5['g5_shop_cart_table']} where od_id='{$row['od_id']}'  order by (case when ct_status in ('취소','품절','반품') then 1 else 0 end), ct_time desc limit 1");

									// 결제 수단
									$s_receipt_way = $s_br = "";
									if ($row['od_settle_case'])
									{
										$s_receipt_way = $row['od_settle_case'];
										$s_br = '<br />';

										// 간편결제
										if($row['od_settle_case'] == '간편결제') {
											switch($row['od_pg']) {
												case 'lg':
													$s_receipt_way = 'PAYNOW';
													break;
												case 'inicis':
													$s_receipt_way = 'KPAY';
													break;
												case 'kcp':
													$s_receipt_way = 'PAYCO';
													break;
												default:
													$s_receipt_way = $row['od_settle_case'];
													break;
											}
										}
									}
									else
									{
										$s_receipt_way = '결제수단없음';
										$s_br = '<br />';
									}

									if ($row['od_receipt_point'] > 0)
										$s_receipt_way .= "+포인트";

									$mb_nick = $row['od_name']; // get_sideview($row['mb_id'], get_text($row['od_name']), $row['od_email'], '');

									$od_cnt = 0;
									if ($row['mb_id'])
									{
										$sql2 = " select count(*) as cnt from {$g5['g5_shop_order_table']} where mb_id = '{$row['mb_id']}' ";
										$row2 = sql_fetch($sql2);
										$od_cnt = $row2['cnt'];
									}

									// 주문 번호에 device 표시
									$od_mobile = '';
									if($row['od_mobile'])
										$od_mobile = '모바일주문';
									if($row['od_mobile']) {
										$od_device = '<img src="'.G5_ADMIN_URL.'/img/btn_mobile.gif" alt="Mobile주문">';
									} else {
										$od_device = '<img src="'.G5_ADMIN_URL.'/img/btn_pc.gif" alt="PC주문">';
									}

									// 주문번호에 - 추가
									switch(strlen($row['od_id'])) {
										case 16:
											$disp_od_id = substr($row['od_id'],0,8).'-'.substr($row['od_id'],8);
											break;
										default:
											$disp_od_id = substr($row['od_id'],0,6).'-'.substr($row['od_id'],6);
											break;
									}

									// 주문 번호에 에스크로 표시
									$od_paytype = '';
									if($row['od_test'])
										//$od_paytype .= '<span class="list_test">테스트</span>';
										$od_paytype .= '<img src="'.G5_ADMIN_URL.'/img/btn_paytest.gif" alt="테스트"> ';

									if($default['de_escrow_use'] && $row['od_escrow'])
										//$od_paytype .= '<span class="list_escrow">에스크로</span>';
										$od_paytype .= '<img src="'.G5_ADMIN_URL.'/img/btn_escrow.gif" alt="에스크로"> ';

									$uid = md5($row['od_id'].$row['od_time'].$row['od_ip']);

									$invoice_time = is_null_time($row['od_invoice_time']) ? G5_TIME_YMDHIS : $row['od_invoice_time'];
									$delivery_company = $row['od_delivery_company'] ? $row['od_delivery_company'] : $default['de_delivery_company'];

									$bg = 'bg'.($i%2);
									$td_color = 0;
									if($row['od_cancel_price'] > 0) {
										$bg .= 'cancel';
										$td_color = 1;
									}

									if ($row['od_status'] == '취소') {
										// 전체취소
									} else if (in_array($row['od_status'], array('주문','입금','준비','배송','완료')) && $row['od_cancel_price'] > 0) {
										// 부분취소
									} else {
										// 취소없음
									}

									// od_status class num
									switch ($row['od_status']) {
										case '주문' : $status_num = '0'; break;
										case '입금' : $status_num = '1'; break;
										case '준비' : $status_num = '2'; break;
										case '배송' : $status_num = '3'; break;
										case '완료' : $status_num = '4'; break;
										case '취소' : $status_num = '5'; break;
										default : $status_num = '';
									}
									$od_phone = get_text($row['od_hp']);
									$od_phone = empty($od_phone) ? get_text($row['od_tel']) : $od_phone;
									$od_phone = preg_replace('%([0-9]{2,3})[\-\s]?([0-9]{3,4})[\-\s]?([0-9]{4})%', '$1-$2-$3', $od_phone);
								?>

								<tr>
									<td class="td_chk">
										<input type="hidden" name="od_id[<?php echo $i ?>]" value="<?php echo $row['od_id'] ?>" id="od_id_<?php echo $i ?>">
										<label for="chk_<?php echo $i; ?>" class="sound_only">주문번호 <?php echo $row['od_id']; ?></label>
										<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>" class="chk">
									</td>
									<td><?php echo $num--; ?></td>
									<td class="td_odrtime"><?php echo $od_time; ?></td>
									<td headers="th_ordnum" class="td_odrnum2">
										<a href="./orderform.php?od_id=<?php echo $row['od_id']; ?>&amp;<?php echo $qstr; ?>"><?php echo $disp_od_id; ?></a>
										<a href="./orderform.php?od_id=<?php echo $row['od_id']; ?>&amp;<?php echo $qstr; ?>" target="_blank"><img src="<?php echo G5_ADMIN_URL; ?>/img/btn_newwindow.gif" alt="새창으로 주문내역 보기" /></a>
										<a href="<?php echo G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $row['od_id']; ?>&amp;uid=<?php echo $uid; ?>" target="_blank"><img src="<?php echo G5_ADMIN_URL; ?>/img/btn_shoppingmall.gif" alt="쇼핑몰에서 주문내역 보기" /></a>
										<?php echo $od_device; ?>
										<?php echo $od_paytype; ?>
									</td>
									<td class="td_odrtitle">
										<a href="./orderform.php?od_id=<?php echo $row['od_id']; ?>&amp;<?php echo $qstr; ?>" class="orderitem" data-order="<?php echo $row['od_id']; ?>">
											<?php echo cut_str($oct['it_name'], 20); ?>
											<?php echo ($row['od_cart_count'] > 1) ? ' 외 '.($row['od_cart_count']-1).'건' : ''; ?>
										</a>
									</td>
									<td headers="th_odrcnt"><?php echo $row['od_cart_count']; ?>건</td>
									<td headers="th_odrid">
										<?php if ($row['mb_id']) { ?>
										<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?sort1=<?php echo $sort1; ?>&amp;sort2=<?php echo $sort2; ?>&amp;sel_field=mb_id&amp;search=<?php echo $row['mb_id']; ?>"><?php echo $row['mb_id']; ?></a>
										<?php } ?>
									</td>
									<td headers="th_odrer" class="td_name"><?php echo $mb_nick; ?></td>
									<td headers="th_odrertel" class="td_tel"><?php echo $od_phone; ?></td>
									<td headers="th_recvr" class="td_name"><a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?sort1=<?php echo $sort1; ?>&amp;sort2=<?php echo $sort2; ?>&amp;sel_field=od_b_name&amp;search=<?php echo get_text($row['od_b_name']); ?>"><?php echo get_text($row['od_b_name']); ?></a></td>
									<td class="td_numsum"><?php echo number_format($row['od_cart_price'] + $row['od_send_cost'] + $row['od_send_cost2']); ?></td>
									<td headers="th_odrpay" class="td_payby">
										<input type="hidden" name="current_settle_case[<?php echo $i ?>]" value="<?php echo $row['od_settle_case'] ?>">
										<?php echo $s_receipt_way; ?>
									</td>
									<td class="td_numsum"><?php echo number_format($row['od_receipt_price'] - $row['od_refund_price']); ?></td>
									<td headers="th_odrstat" class="td_odrstatus">
										<input type="hidden" name="current_status[<?php echo $i ?>]" value="<?php echo $row['od_status'] ?>">
										<a href="./orderform.php?od_id=<?php echo $row['od_id']; ?>&amp;<?php echo $qstr; ?>" class="status<?php echo $status_num; ?>"><?php echo $row['od_status']; ?></a>
									</td>
								</tr>

								<?php
									$c++; // 체크박스 순번
									$tot_itemcount     += $row['od_cart_count'];
									$tot_orderprice    += ($row['od_cart_price'] + $row['od_send_cost'] + $row['od_send_cost2']);
									$tot_ordercancel   += $row['od_cancel_price'];
									$tot_receiptprice  += $row['od_receipt_price'];
									$tot_refundprice   += $row['od_refund_price'];
									$tot_couponprice   += $row['couponprice'];
									$tot_misu          += $row['od_misu'];

									$lst_itemcount     += $row['od_cart_count'];
									$lst_orderprice    += ($row['od_cart_price'] + $row['od_send_cost'] + $row['od_send_cost2']);
									$lst_ordercancel   += $row['od_cancel_price'];
									$lst_receiptprice  += $row['od_receipt_price'];
									$lst_refundprice   += $row['od_refund_price'];
									$lst_couponprice   += $row['couponprice'];
									$lst_misu          += $row['od_misu'];
								} // End for
								sql_free_result($result);

								if ($i == 0) {
									echo '<tr><td colspan="'.$colspan.'" class="emptylist">자료가 없습니다.</td></tr>';
								}
								?>
								<tr>
									<td colspan="<?php echo $colspan; ?>">
										<div class="ordinfo">
											<div class="allchoice"><a href="javascript:;" class="group_all"><img src="<?php echo G5_ADMIN_URL; ?>/img/btn_allchoice.gif" alt="전체선택" /></a></div>
											<div class="totallist">
												주문금액 : <b><?php echo number_format($lst_orderprice); ?></b> 원 &nbsp;
												결제금액 : <b><?php echo number_format($lst_receiptprice - $lst_refundprice); ?></b> 원
											</div>
										</div>
									</td>
								</tr>
								</tbody>
								<?php } // End foreach ?>
								<tfoot>
								<tr class="orderlist">
									<th scope="row" colspan="4">&nbsp;</th>
									<th scope="row">&nbsp;</th>
									<th scope="row"><?php echo number_format($tot_itemcount); ?>건</th>
									<th scope="row" colspan="3">&nbsp;</th>
									<th scope="row">합 계</th>
									<th scope="row"><?php echo number_format($tot_orderprice); ?></th>
									<th scope="row">&nbsp;</th>
									<th scope="row"><?php echo number_format($tot_receiptprice - $tot_refundprice); ?></th>
									<th scope="row">&nbsp;</th>
								</tr>
								</tfoot>
								</table>
							</div>
							<?php } // End if ($mode == 'proc') ?>



							<?php if (in_array($od_status, array('주문','입금','준비','배송'))) { ?>
							<div class="local_cmd01 local_cmd">
							<?php if (($od_status == '' || $od_status == '완료' || $od_status == '전체취소' || $od_status == '부분취소') == false) {
								// 검색된 주문상태가 '전체', '완료', '전체취소', '부분취소' 가 아니라면
							?>
								<label for="od_status" class="cmd_tit">주문상태 변경</label>
								<?php
								$change_status = "";
								if ($od_status == '주문') $change_status = "입금";
								if ($od_status == '입금') $change_status = "준비";
								if ($od_status == '준비') $change_status = "배송";
								if ($od_status == '배송') $change_status = "완료";
								?>
								<label><input type="checkbox" name="od_status" value="<?php echo $change_status; ?>"> '<?php echo $od_status ?>'상태에서 '<strong><?php echo $change_status ?></strong>'상태로 변경합니다.</label>
								<?php if($od_status == '주문' || $od_status == '준비') { ?>
								<input type="checkbox" name="od_send_mail" value="1" id="od_send_mail" checked="checked">
								<label for="od_send_mail"><?php echo $change_status; ?>안내 메일</label>
								<input type="checkbox" name="send_sms" value="1" id="od_send_sms" checked="checked">
								<label for="od_send_sms"><?php echo $change_status; ?>안내 SMS</label>
								<?php } ?>
								<?php if($od_status == '준비') { ?>
								<input type="checkbox" name="send_escrow" value="1" id="od_send_escrow">
								<label for="od_send_escrow">에스크로배송등록</label>
								<?php } ?>
								<input type="submit" value="선택수정" class="btn_submit" onclick="document.pressed=this.value">
							<?php } ?>
								<?php if ($od_status == '주문') { ?> <span>주문상태에서만 삭제가 가능합니다.</span> <input type="submit" value="선택삭제" class="btn_submit" onclick="document.pressed=this.value"><?php } ?>
							</div>
							<?php } ?>

							<div class="local_desc02 local_desc">
							<p>
								&lt;무통장&gt;인 경우에만 &lt;주문&gt;에서 &lt;입금&gt;으로 변경됩니다. 가상계좌는 입금시 자동으로 &lt;입금&gt;처리됩니다.<br>
								&lt;준비&gt;에서 &lt;배송&gt;으로 변경시 &lt;에스크로배송등록&gt;을 체크하시면 에스크로 주문에 한해 PG사에 배송정보가 자동 등록됩니다.<br>
								<strong>주의!</strong> <img src="<?php echo G5_ADMIN_URL; ?>/img/btn_shoppingmall.gif" /> 아이콘을 클릭하여 나오는 주문상세내역의 주소를 외부에서 조회가 가능한곳에 올리지 마십시오.
							</p>
							</div>

							</form>
						</div>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col -->
		</div>
	  <!-- /.row -->
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script>
$(function(){
    // 출력모드
    $('a[data-mode]').on('click', function() {
        var $this = $(this),
            _mode = $this.data('mode'),
            _cookie = $.cookie('OrderPrintMode'),
            _reform = '<?php echo $mode; ?>';
        if (_mode == _reform) return;
        $.cookie('OrderPrintMode', _mode, { expires: 365, path: '/' });
        document.location.reload(true);
    });

	// 라인 활성
	$('.chk').on('change', function() {
		/* 선택개별 */
		var chk = $(this).is(':checked');
		if (chk) {
			$(this).closest('tr').addClass('selected');
		} else {
			$(this).closest('tr').removeClass('selected');
		}
		/* 전체선택 */
		var chk1 = $('[name="chk[]"]').length;
		var chk2 = $('[name="chk[]"]:checked').length;
		if (chk2 == 0) {
			$('#chkall').prop('checked', false);
		} else {
			if (chk1 == chk2) {
				$('#chkall').prop('checked', true);
			} else {
				$('#chkall').prop('checked', false);
			}
		}
		/* 그룹선택 */
		$('.odgroup').each(function() {
			var chk1 = $(this).find('[name="chk[]"]').length;
			var chk2 = $(this).find('[name="chk[]"]:checked').length;
			var group_all = $(this).find('.group_all');
			if (chk2 == 0) {
				group_all.data('flag', false);
			} else {
				if (chk1 == chk2) {
					group_all.data('flag', true);
				} else {
					group_all.data('flag', false);
				}
			}
		});
	});

	// 전체 선택
	$('#chkall').on('click', function() {
		$('.chk').trigger('change');
		$('.group_all').data('flag', $(this).is(':checked'));
	});

	// 그룹 체크
	$('.group_all').on('click', function() {
		/* 반전
		$(this).closest('.odgroup').find('.chk').each(function() {
			$(this).prop('checked', $(this).is(':checked') ? false : true);
		}); */
		var flag = $(this).data('flag') ? false : true;
		$(this).data('flag', flag).closest('.odgroup').find('.chk').prop('checked', flag).trigger('change');
	});

    // 검색
    $('form.local_sch :radio').on('click', function() {
        $(this).closest('form').submit();
    });

    // 주문일자검색
    $("#fr_date, #to_date").datepicker({ 
        changeMonth: true, 
        changeYear: true, 
        dateFormat: "yy-mm-dd", 
        showButtonPanel: true, 
        yearRange: "c-99:c+99",
        maxDate: "+0d" 
    });

    // 주문상품보기
    $(".orderitem").on("click", function() {
        var $this = $(this);
        var od_id = $this.data('order'); // $this.text().replace(/[^0-9]/g, "");

        if($this.next("#orderitemlist").size())
            return false;

        $("#orderitemlist").remove();

        $.post(
            "./ajax.orderitem.php",
            { od_id: od_id },
            function(data) {
                $this.after("<div id=\"orderitemlist\"><div class=\"itemlist\"></div></div>");
                $("#orderitemlist .itemlist")
                    .html(data)
                    .append("<div id=\"orderitemlist_close\"><button type=\"button\" id=\"orderitemlist-x\" class=\"btn_button delete\">닫기</button></div>");
            }
        );

        return false;
    });

    // 상품리스트 닫기
    $(".orderitemlist-x").on("click", function() {
        $("#orderitemlist").remove();
    });

    $("body").on("click", function() {
        $("#orderitemlist").remove();
    });

    // 엑셀배송처리창
    $("#order_delivery").on("click", function() {
        var opt = "width=600,height=450,left=10,top=10";
        window.open(this.href, "win_excel", opt);
        return false;
    });
});

function set_date(today)
{
    <?php
    $date_term = date('w', G5_SERVER_TIME);
    $week_term = $date_term + 7;
    $last_term = strtotime(date('Y-m-01', G5_SERVER_TIME));
    ?>
    if (today == "오늘") {
        document.getElementById("fr_date").value = "<?php echo G5_TIME_YMD; ?>";
        document.getElementById("to_date").value = "<?php echo G5_TIME_YMD; ?>";
    } else if (today == "어제") {
        document.getElementById("fr_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME - 86400); ?>";
        document.getElementById("to_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME - 86400); ?>";
    } else if (today == "이번주") {
        document.getElementById("fr_date").value = "<?php echo date('Y-m-d', strtotime('-'.$date_term.' days', G5_SERVER_TIME)); ?>";
        document.getElementById("to_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
    } else if (today == "이번달") {
        document.getElementById("fr_date").value = "<?php echo date('Y-m-01', G5_SERVER_TIME); ?>";
        document.getElementById("to_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
    } else if (today == "지난주") {
        document.getElementById("fr_date").value = "<?php echo date('Y-m-d', strtotime('-'.$week_term.' days', G5_SERVER_TIME)); ?>";
        document.getElementById("to_date").value = "<?php echo date('Y-m-d', strtotime('-'.($week_term - 6).' days', G5_SERVER_TIME)); ?>";
    } else if (today == "지난달") {
        document.getElementById("fr_date").value = "<?php echo date('Y-m-01', strtotime('-1 Month', $last_term)); ?>";
        document.getElementById("to_date").value = "<?php echo date('Y-m-t', strtotime('-1 Month', $last_term)); ?>";
    } else if (today == "전체") {
        document.getElementById("fr_date").value = "";
        document.getElementById("to_date").value = "";
    }
}
</script>

<script>
function forderlist_export() {
    var chk = [];
    $('#forderlist input[name="chk[]"]:checked').each(function() {
        chk.push($('[name="od_id['+$(this).val()+']"]').val());
    });
    location.href = './orderlist.xlsx.php?alt=xls&' + $('#frmorderlist').serialize() + '&' + $('#schorderlist').serialize() + '&chk=' + chk.join(";");
}

function forderlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    /*
    switch (f.od_status.value) {
        case "" :
            alert("변경하실 주문상태를 선택하세요.");
            return false;
        case '주문' :

        default :

    }
    */

    if(document.pressed == "선택삭제") {
        if(confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            f.action = "./orderlistdelete.php";
            return true;
        }
        return false;
    }

    var change_status = f.od_status.value;

    if (f.od_status.checked == false) {
        alert("주문상태 변경에 체크하세요.");
        return false;
    }

    var chk = document.getElementsByName("chk[]");

    for (var i=0; i<chk.length; i++)
    {
        if (chk[i].checked)
        {
            var k = chk[i].value;
            var current_settle_case = f.elements['current_settle_case['+k+']'].value;
            var current_status = f.elements['current_status['+k+']'].value;

            switch (change_status)
            {
                case "입금" :
                    if (!(current_status == "주문" && current_settle_case == "무통장")) {
                        alert("'주문' 상태의 '무통장'(결제수단)인 경우에만 '입금' 처리 가능합니다.");
                        return false;
                    }
                    break;

                case "준비" :
                    if (current_status != "입금") {
                        alert("'입금' 상태의 주문만 '준비'로 변경이 가능합니다.");
                        return false;
                    }
                    break;

                case "배송" :
                    if (current_status != "준비") {
                        alert("'준비' 상태의 주문만 '배송'으로 변경이 가능합니다.");
                        return false;
                    }

                    var invoice      = f.elements['od_invoice['+k+']'];
                    var invoice_time = f.elements['od_invoice_time['+k+']'];
                    var delivery_company = f.elements['od_delivery_company['+k+']'];

                    if ($.trim(invoice_time.value) == '') {
                        alert("배송일시를 입력하시기 바랍니다.");
                        invoice_time.focus();
                        return false;
                    }

                    if ($.trim(delivery_company.value) == '') {
                        alert("배송업체를 입력하시기 바랍니다.");
                        delivery_company.focus();
                        return false;
                    }

                    if ($.trim(invoice.value) == '') {
                        alert("운송장번호를 입력하시기 바랍니다.");
                        invoice.focus();
                        return false;
                    }

                    break;
            }
        }
    }

    if (!confirm("선택하신 주문서의 주문상태를 '"+change_status+"'상태로 변경하시겠습니까?"))
        return false;

    f.action = "./orderlistupdate.php";
    return true;
}
</script>

<?php
include_once ('../footer.php');
?>





