<?php
$sub_menu = '400500';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$doc = strip_tags($doc);
$sort1 = strip_tags($sort1);
$sort2 = in_array($sort2, array('desc', 'asc')) ? $sort2 : 'desc';
$sel_ca_id = get_search_string($sel_ca_id);
$sel_field = get_search_string($sel_field);
$search = get_search_string($search);

$g5['title'] = '상품옵션재고관리';
include_once ('../admin.head.php');

$sql_search = " where b.it_id is not NULL ";
if ($search != "") {
	if ($sel_field != "") {
    	$sql_search .= " and $sel_field like '%$search%' ";
    }
}

if ($sel_ca_id != "") {
    $sql_search .= " and b.ca_id like '$sel_ca_id%' ";
}

if ($sel_field == "")  $sel_field = "b.it_name";
if ($sort1 == "") $sort1 = "a.io_stock_qty";
if ($sort2 == "") $sort2 = "asc";

$sql_common = "  from {$g5['g5_shop_item_option_table']} a left join {$g5['g5_shop_item_table']} b on ( a.it_id = b.it_id ) ";
$sql_common .= $sql_search;

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql  = " select a.it_id,
                 a.io_id,
                 a.io_type,
                 a.io_stock_qty,
                 a.io_noti_qty,
                 a.io_use,
                 b.it_name,
                 b.it_option_subject
           $sql_common
          order by $sort1 $sort2
          limit $from_record, $rows ";
$result = sql_query($sql);

$qstr1 = 'sel_ca_id='.$sel_ca_id.'&amp;sel_field='.$sel_field.'&amp;search='.$search;
$qstr = $qstr1.'&amp;sort1='.$sort1.'&amp;sort2='.$sort2.'&amp;page='.$page;

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';


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
		상품옵션재고관리
		<small>optionstocklist</small>
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
						<nav class="navbar navbar-default navbtn" style="z-index: 1000000000000000000000000000000000">
							<div class="text-right">							
								<a href="./itemstocklist.php" class="btn btn-default btn_02">상품재고관리</a>
								<a href="./itemsellrank.php" class="btn btn-default btn_02">상품판매순위</a>
								<input type="submit" value="일괄수정" class="btn_submit btn btn-danger">						
							</div>
						</nav>
					</div>
					<!-- /.box-header -->
					<div class="box-body">	

						<form name="flist" class="local_sch01 local_sch">
						<input type="hidden" name="doc" value="<?php echo $doc; ?>">
						<input type="hidden" name="sort1" value="<?php echo $sort1; ?>">
						<input type="hidden" name="sort2" value="<?php echo $sort2; ?>">
						<input type="hidden" name="page" value="<?php echo $page; ?>">

						<label for="sel_ca_id" class="sound_only">분류선택</label>
						<select name="sel_ca_id" id="sel_ca_id" class="form-control">
							<option value=''>전체분류</option>
							<?php
							$sql1 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} order by ca_order, ca_id ";
							$result1 = sql_query($sql1);
							for ($i=0; $row1=sql_fetch_array($result1); $i++) {
								$len = strlen($row1['ca_id']) / 2 - 1;
								$nbsp = "";
								for ($i=0; $i<$len; $i++) $nbsp .= "&nbsp;&nbsp;&nbsp;";
								echo '<option value="'.$row1['ca_id'].'" '.get_selected($sel_ca_id, $row1['ca_id']).'>'.$nbsp.$row1['ca_name'].'</option>'.PHP_EOL;
							}
							?>
						</select>

						<!-- <label for="sel_field" class="sound_only">검색대상</label> -->
						<select name="sel_field" id="sel_field" class="form-control">
							<option value="b.it_name" <?php echo get_selected($sel_field, 'b.it_name'); ?>>상품명</option>
							<option value="a.it_id" <?php echo get_selected($sel_field, 'a.it_id'); ?>>상품코드</option>
						</select>

						<!-- <label for="search" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label> -->
						<input type="text" name="search" id="search" value="<?php echo $search; ?>" required class="frm_input required form-control">
						<input type="submit" value="검색" class="btn_submit btn btn-default">

						</form>

						<form name="fitemstocklist" action="./optionstocklistupdate.php" method="post">
						<input type="hidden" name="sort1" value="<?php echo $sort1; ?>">
						<input type="hidden" name="sort2" value="<?php echo $sort2; ?>">
						<input type="hidden" name="sel_ca_id" value="<?php echo $sel_ca_id; ?>">
						<input type="hidden" name="sel_field" value="<?php echo $sel_field; ?>">
						<input type="hidden" name="search" value="<?php echo $search; ?>">
						<input type="hidden" name="page" value="<?php echo $page; ?>">

						<div class="tbl_head01 tbl_wrap">
							<table id="example1" class="table table-bordered table-striped dataTable member_list">
							<!-- <caption><?php echo $g5['title']; ?> 목록</caption> -->
							<thead>
							<tr>
								<th scope="col"><a href="<?php echo title_sort("b.it_name") . "&amp;$qstr1"; ?>">상품명</a></th>
								<th scope="col">옵션항목</th>
								<th scope="col">옵션타입</th>
								<th scope="col"><a href="<?php echo title_sort("a.io_stock_qty") . "&amp;$qstr1"; ?>">창고재고</a></th>
								<th scope="col">주문대기</th>
								<th scope="col">가재고</th>
								<th scope="col">재고수정</th>
								<th scope="col">통보수량</th>
								<th scope="col"><a href="<?php echo title_sort("a.io_use") . "&amp;$qstr1"; ?>">판매</a></th>
								<th scope="col">관리</th>
							</tr>
							</thead>
							<tbody>
							<?php
							for ($i=0; $row=sql_fetch_array($result); $i++)
							{
								$href = G5_SHOP_URL."/item.php?it_id={$row['it_id']}";

								$sql1 = " select SUM(ct_qty) as sum_qty
											from {$g5['g5_shop_cart_table']}
										   where it_id = '{$row['it_id']}'
											 and io_id = '{$row['io_id']}'
											 and ct_stock_use = '0'
											 and ct_status in ('쇼핑', '주문', '입금', '준비') ";
								$row1 = sql_fetch($sql1);
								$wait_qty = $row1['sum_qty'];

								// 가재고 (미래재고)
								$temporary_qty = $row['io_stock_qty'] - $wait_qty;

								$option = '';
								$option_br = '';
								if($row['io_type']) {
									$opt = explode(chr(30), $row['io_id']);
									if($opt[0] && $opt[1])
										$option .= $opt[0].' : '.$opt[1];
								} else {
									$subj = explode(',', $row['it_option_subject']);
									$opt = explode(chr(30), $row['io_id']);
									for($k=0; $k<count($subj); $k++) {
										if($subj[$k] && $opt[$k]) {
											$option .= $option_br.$subj[$k].' : '.$opt[$k];
											$option_br = '<br>';
										}
									}
								}

								$type = '선택옵션';
								if($row['io_type'])
									$type = '추가옵션';

								// 통보수량보다 재고수량이 작을 때
								$io_stock_qty = number_format($row['io_stock_qty']);
								$io_stock_qty_st = ''; // 스타일 정의
								if($row['io_stock_qty'] <= $row['io_noti_qty']) {
									$io_stock_qty_st = ' sit_stock_qty_alert';
									$io_stock_qty = ''.$io_stock_qty.' !<span class="sound_only"> 재고부족 </span>';
								}

								$bg = 'bg'.($i%2);
							?>
							<tr class="<?php echo $bg; ?>">
								<td class="td_left">
									<input type="hidden" name="it_id[<?php echo $i; ?>]" value="<?php echo $row['it_id']; ?>">
									<input type="hidden" name="io_id[<?php echo $i; ?>]" value="<?php echo $row['io_id']; ?>">
									<input type="hidden" name="io_type[<?php echo $i; ?>]" value="<?php echo $row['io_type']; ?>">
									<a href="<?php echo $href; ?>"><?php echo get_it_image($row['it_id'], 50, 50); ?> <?php echo cut_str(stripslashes($row['it_name']), 60, "&#133"); ?></a>
								</td>
								<td class="td_left"><?php echo $option; ?></td>
								<td class="td_mng"><?php echo $type; ?></td>
								<td class="td_num<?php echo $io_stock_qty_st; ?>"><?php echo $io_stock_qty; ?></td>
								<td class="td_num"><?php echo number_format($wait_qty); ?></td>
								<td class="td_num"><?php echo number_format($temporary_qty); ?></td>
								<td class="td_num">
									<label for="stock_qty_<?php echo $i; ?>" class="sound_only">재고수정</label>
									<input type="text" name="io_stock_qty[<?php echo $i; ?>]" value="<?php echo $row['io_stock_qty']; ?>" id="stock_qty_<?php echo $i; ?>" class="frm_input" size="8" autocomplete="off">
								</td>
								<td class="td_num">
									<label for="noti_qty_<?php echo $i; ?>" class="sound_only">통보수량</label>
									<input type="text" name="io_noti_qty[<?php echo $i; ?>]" value="<?php echo $row['io_noti_qty']; ?>" id="noti_qty_<?php echo $i; ?>" class="frm_input" size="8" autocomplete="off">
								</td>
								<td class="td_chk2">
									<label for="use_<?php echo $i; ?>" class="sound_only">판매</label>
									<input type="checkbox" name="io_use[<?php echo $i; ?>]" value="1" id="use_<?php echo $i; ?>" <?php echo ($row['io_use'] ? "checked" : ""); ?>>
								</td>
								<td class="td_mng td_mng_s"><a href="./itemform.php?w=u&amp;it_id=<?php echo $row['it_id']; ?>&amp;ca_id=<?php echo $row['ca_id']; ?>&amp;<?php echo $qstr; ?>" class="btn btn_03">수정</a></td>
							</tr>
							<?php
							}
							if (!$i)
								echo '<tr><td colspan="10" class="empty_table"><span>자료가 없습니다.</span></td></tr>';
							?>
							</tbody>
							</table>
						</div>
						</form>

						<div class="local_desc01 local_desc">
							<p>
								재고수정의 수치를 수정하시면 창고재고의 수치가 변경됩니다.<br>
								창고재고가 부족한 경우 재고수량 뒤에 <span class="sit_stock_qty_alert">!</span><span class="sound_only"> 혹은 재고부족</span>으로 표시됩니다.
							</p>
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

$(document).scroll(function(e){
    var scrollTop = $(document).scrollTop();
    if(scrollTop > 0){
        console.log(scrollTop);
        $('.navbar').removeClass('navbar-static-top').addClass('navbar-fixed-top');
    } else {
        $('.navbar').removeClass('navbar-fixed-top').addClass('navbar-static-top');
    }
});

</script>


<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>



<?php
include_once ('../footer.php');
?>
