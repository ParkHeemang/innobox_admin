<?php
$sub_menu = '400300';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '상품관리';
include_once ('../admin.head.php');

// 분류
$ca_list  = '<option value="">선택</option>'.PHP_EOL;
$sql = " select * from {$g5['g5_shop_category_table']} ";
if ($is_admin != 'super')
    $sql .= " where ca_mb_id = '{$member['mb_id']}' ";
$sql .= " order by ca_order, ca_id ";
$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++)
{
    $len = strlen($row['ca_id']) / 2 - 1;
    $nbsp = '';
    for ($i=0; $i<$len; $i++) {
        $nbsp .= '&nbsp;&nbsp;&nbsp;';
    }
    $ca_list .= '<option value="'.$row['ca_id'].'">'.$nbsp.$row['ca_name'].'</option>'.PHP_EOL;
}

$where = " and ";
$sql_search = "";
if ($stx != "") {
    if ($sfl != "") {
        $sql_search .= " $where $sfl like '%$stx%' ";
        $where = " and ";
    }
    if ($save_stx != $stx)
        $page = 1;
}

if ($sca != "") {
    $sql_search .= " $where (a.ca_id like '$sca%' or a.ca_id2 like '$sca%' or a.ca_id3 like '$sca%') ";
}

if ($sfl == "")  $sfl = "it_name";

$sql_common = " from {$g5['g5_shop_item_table']} a ,
                     {$g5['g5_shop_category_table']} b
               where (a.ca_id = b.ca_id";


//CSS			   
add_stylesheet('<link rel="stylesheet" href="'.G5_PHP_URL. 'bower_components/bootstrap/dist/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 5);
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL2.'/bootstrap.custom.css?ver='.G5_CSS_VER.'">',8);


//JS DataTables
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
	  <h1 class="member_list_title">
		상품관리
		<small>item list</small>
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
								<a href="./itemform.php" class="btn btn_01 btn btn-danger">상품등록</a>
								<a href="./itemexcel.php" onclick="return excelform(this.href);" target="_blank" class="btn btn_02 btn-default">상품일괄등록</a>
								<input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn btn_02 btn-default">
								<?php if ($is_admin == 'super') { ?>
								<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02 btn-default">
								<?php } ?>						
							</div>
						</nav>
					</div>
					<!-- /.box-header -->
					<div class="box-body">		
						<div class="local_ov01 local_ov">
							<?php echo $listall; ?>
							<span class="btn_ov01"><span class="ov_txt">등록된 상품</span><span class="ov_num"> <?php echo $total_count; ?>건</span></span>
						</div>

						<form name="flist" class="local_sch01 local_sch">
						<input type="hidden" name="page" value="<?php echo $page; ?>">
						<input type="hidden" name="save_stx" value="<?php echo $stx; ?>">

						<label for="sca" class="sound_only">분류선택</label>
						<select name="sca" id="sca" class="form-control">
							<option value="">전체분류</option>
							<?php
							$sql1 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} order by ca_order, ca_id ";
							$result1 = sql_query($sql1);
							for ($i=0; $row1=sql_fetch_array($result1); $i++) {
								$len = strlen($row1['ca_id']) / 2 - 1;
								$nbsp = '';
								for ($i=0; $i<$len; $i++) $nbsp .= '&nbsp;&nbsp;&nbsp;';
								echo '<option value="'.$row1['ca_id'].'" '.get_selected($sca, $row1['ca_id']).'>'.$nbsp.$row1['ca_name'].'</option>'.PHP_EOL;
							}
							?>
						</select>

						<label for="sfl" class="sound_only">검색대상</label>
						<select name="sfl" id="sfl" class="form-control">
							<option value="it_name" <?php echo get_selected($sfl, 'it_name'); ?>>상품명</option>
							<option value="it_id" <?php echo get_selected($sfl, 'it_id'); ?>>상품코드</option>
							<option value="it_maker" <?php echo get_selected($sfl, 'it_maker'); ?>>제조사</option>
							<option value="it_origin" <?php echo get_selected($sfl, 'it_origin'); ?>>원산지</option>
							<option value="it_sell_email" <?php echo get_selected($sfl, 'it_sell_email'); ?>>판매자 e-mail</option>
						</select>

						<label for="stx" class="sound_only">검색어</label>
						<input type="text" name="stx" value="<?php echo $stx; ?>" id="stx" class="frm_input form-control">
						<input type="submit" value="검색" class="btn_submit btn btn-default">

						</form>



						<form name="fitemlistupdate" method="post" action="./itemlistupdate.php" onsubmit="return fitemlist_submit(this);" autocomplete="off" id="fitemlistupdate">
						<input type="hidden" name="sca" value="<?php echo $sca; ?>">
						<input type="hidden" name="sst" value="<?php echo $sst; ?>">
						<input type="hidden" name="sod" value="<?php echo $sod; ?>">
						<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
						<input type="hidden" name="stx" value="<?php echo $stx; ?>">
						<input type="hidden" name="page" value="<?php echo $page; ?>">

						<div class="tbl_head01 tbl_wrap">

						   <table id="example1" class="table table-bordered table-striped dataTable member_list">
							<caption><?php echo $g5['title']; ?> 목록</caption>
							<thead>
							<tr>
								<th scope="col" rowspan="3">
									<label for="chkall" class="sound_only">상품 전체</label>
									<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
								</th>
								<th scope="col" rowspan="3"><?php echo subject_sort_link('it_id', 'sca='.$sca); ?>상품코드</a></th>
								<th scope="col" colspan="5">분류</th>
								<th scope="col" rowspan="3"><?php echo subject_sort_link('it_order', 'sca='.$sca); ?>순서</a></th>
								<th scope="col" rowspan="3"><?php echo subject_sort_link('it_use', 'sca='.$sca, 1); ?>판매</a></th>
								<th scope="col" rowspan="3"><?php echo subject_sort_link('it_soldout', 'sca='.$sca, 1); ?>품절</a></th>
								<th scope="col" rowspan="3"><?php echo subject_sort_link('it_hit', 'sca='.$sca, 1); ?>조회</a></th>
								<th scope="col" rowspan="3">관리</th>
							</tr>
							<tr>
								<th scope="col" rowspan="2" id="th_img">이미지</th>
								<th scope="col" rowspan="2" id="th_pc_title"><?php echo subject_sort_link('it_name', 'sca='.$sca); ?>상품명</a></th>
								<th scope="col" id="th_amt"><?php echo subject_sort_link('it_price', 'sca='.$sca); ?>판매가격</a></th>
								<th scope="col" id="th_camt"><?php echo subject_sort_link('it_cust_price', 'sca='.$sca); ?>시중가격</a></th>
								<th scope="col" id="th_skin">PC스킨</th>
							</tr>
							<tr>
								<th scope="col" id="th_pt"><?php echo subject_sort_link('it_point', 'sca='.$sca); ?>포인트</a></th>
								<th scope="col" id="th_qty"><?php echo subject_sort_link('it_stock_qty', 'sca='.$sca); ?>재고</a></th>
								<th scope="col" id="th_mskin">모바일스킨</th>
							</tr>
							</thead>
							<tbody>
							<?php
							for ($i=0; $row=sql_fetch_array($result); $i++)
							{
								$href = G5_SHOP_URL.'/item.php?it_id='.$row['it_id'];
								$bg = 'bg'.($i%2);

								$it_point = $row['it_point'];
								if($row['it_point_type'])
									$it_point .= '%';
							?>
							<tr class="<?php echo $bg; ?>">
								<td rowspan="3" class="td_chk">
									<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['it_name']); ?></label>
									<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i; ?>">
								</td>
								<td rowspan="3" class="td_num">
									<input type="hidden" name="it_id[<?php echo $i; ?>]" value="<?php echo $row['it_id']; ?>">
									<?php echo $row['it_id']; ?>
								</td>
								<td colspan="5" class="td_sort">
									<label for="ca_id_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['it_name']); ?> 기본분류</label>
									<select name="ca_id[<?php echo $i; ?>]" id="ca_id_<?php echo $i; ?>">
										<?php echo conv_selected_option($ca_list, $row['ca_id']); ?>
									</select>
									<label for="ca_id2_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['it_name']); ?> 2차분류</label>
									<select name="ca_id2[<?php echo $i; ?>]" id="ca_id2_<?php echo $i; ?>">
										<?php echo conv_selected_option($ca_list, $row['ca_id2']); ?>
									</select>
									<label for="ca_id3_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['it_name']); ?> 3차분류</label>
									<select name="ca_id3[<?php echo $i; ?>]" id="ca_id3_<?php echo $i; ?>">
										<?php echo conv_selected_option($ca_list, $row['ca_id3']); ?>
									</select>
								</td>
								<td rowspan="3" class="td_num">
									<label for="order_<?php echo $i; ?>" class="sound_only">순서</label>
									<input type="text" name="it_order[<?php echo $i; ?>]" value="<?php echo $row['it_order']; ?>" id="order_<?php echo $i; ?>" class="tbl_input" size="3">
								</td>
								<td rowspan="3">
									<label for="use_<?php echo $i; ?>" class="sound_only">판매여부</label>
									<input type="checkbox" name="it_use[<?php echo $i; ?>]" <?php echo ($row['it_use'] ? 'checked' : ''); ?> value="1" id="use_<?php echo $i; ?>">
								</td>
								<td rowspan="3">
									<label for="soldout_<?php echo $i; ?>" class="sound_only">품절</label>
									<input type="checkbox" name="it_soldout[<?php echo $i; ?>]" <?php echo ($row['it_soldout'] ? 'checked' : ''); ?> value="1" id="soldout_<?php echo $i; ?>">
								</td>
								<td rowspan="3" class="td_num"><?php echo $row['it_hit']; ?></td>
								<td rowspan="3" class="td_mng td_mng_s">
									<a href="./itemform.php?w=u&amp;it_id=<?php echo $row['it_id']; ?>&amp;ca_id=<?php echo $row['ca_id']; ?>&amp;<?php echo $qstr; ?>" class="btn btn_03"><span class="sound_only"><?php echo htmlspecialchars2(cut_str($row['it_name'],250, "")); ?> </span>수정</a>
									<a href="./itemcopy.php?it_id=<?php echo $row['it_id']; ?>&amp;ca_id=<?php echo $row['ca_id']; ?>" class="itemcopy btn btn_02" target="_blank"><span class="sound_only"><?php echo htmlspecialchars2(cut_str($row['it_name'],250, "")); ?> </span>복사</a>
									<a href="<?php echo $href; ?>" class="btn btn_02"><span class="sound_only"><?php echo htmlspecialchars2(cut_str($row['it_name'],250, "")); ?> </span>보기</a>
								</td>
							</tr>
							<tr class="<?php echo $bg; ?>">
								<td rowspan="2" class="td_img"><a href="<?php echo $href; ?>"><?php echo get_it_image($row['it_id'], 50, 50); ?></a></td>
								<td headers="th_pc_title" rowspan="2" class="td_input">
									<label for="name_<?php echo $i; ?>" class="sound_only">상품명</label>
									<input type="text" name="it_name[<?php echo $i; ?>]" value="<?php echo htmlspecialchars2(cut_str($row['it_name'],250, "")); ?>" id="name_<?php echo $i; ?>" required class="tbl_input required" size="30">
								</td>
								<td headers="th_amt" class="td_numbig td_input">
									<label for="price_<?php echo $i; ?>" class="sound_only">판매가격</label>
									<input type="text" name="it_price[<?php echo $i; ?>]" value="<?php echo $row['it_price']; ?>" id="price_<?php echo $i; ?>" class="tbl_input sit_amt" size="7">
								</td>
								<td headers="th_camt" class="td_numbig td_input">
									<label for="cust_price_<?php echo $i; ?>" class="sound_only">시중가격</label>
									<input type="text" name="it_cust_price[<?php echo $i; ?>]" value="<?php echo $row['it_cust_price']; ?>" id="cust_price_<?php echo $i; ?>" class="tbl_input sit_camt" size="7">
								</td>
								<td headers="th_skin" class="td_numbig td_input">
									<label for="it_skin_<?php echo $i; ?>" class="sound_only">PC 스킨</label>
									<?php echo get_skin_select('shop', 'it_skin_'.$i, 'it_skin['.$i.']', $row['it_skin']); ?>
								</td>
							</tr>
							<tr class="<?php echo $bg; ?>">
								<td headers="th_pt" class="td_numbig td_input"><?php echo $it_point; ?></td>
								<td headers="th_qty" class="td_numbig td_input">
									<label for="stock_qty_<?php echo $i; ?>" class="sound_only">재고</label>
									<input type="text" name="it_stock_qty[<?php echo $i; ?>]" value="<?php echo $row['it_stock_qty']; ?>" id="stock_qty_<?php echo $i; ?>" class="tbl_input sit_qty" size="7">
								</td>
								<td headers="th_mskin" class="td_numbig td_input">
									<label for="it_mobile_skin_<?php echo $i; ?>" class="sound_only">모바일 스킨</label>
									<?php echo get_mobile_skin_select('shop', 'it_mobile_skin_'.$i, 'it_mobile_skin['.$i.']', $row['it_mobile_skin']); ?>
								</td>
							</tr>
							<?php
							}
							if ($i == 0)
								echo '<tr><td colspan="12" class="empty_table">자료가 한건도 없습니다.</td></tr>';
							?>
							</tbody>
							</table>
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
</script>

<?php
include_once ('../footer.php');
?>