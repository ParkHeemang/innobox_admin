<?php
$sub_menu = '400410';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$sql_common = " from {$g5['g5_shop_order_data_table']} ";

$sql_search = " where cart_id <> '0' ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'od_id' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "od_id";
    $sod = "desc";
}
$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt
            {$sql_common}
            {$sql_search}
            {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *
            {$sql_common}
            {$sql_search}
            {$sql_order}
            limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$g5['title'] = '미완료주문';
include_once ('../admin.head.php');

$colspan = 10;




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
		미완료주문
		<small>inorderlist</small>
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
								<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02 btn-danger">					
							</div>
						</nav>
					</div>
					<!-- /.box-header -->

					<div class="box-body">
						<div class="local_ov01 local_ov">
						   <span class="btn_ov01"><span class="ov_txt">전체 </span><span class="ov_num">  <?php echo number_format($total_count) ?> 건 </span></span> 
						</div>

						<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
							<select name="sfl" title="검색대상" class="form-control">
								<option value="od_id"<?php echo get_selected($_GET['sfl'], "od_id"); ?>>주문번호</option>
							</select>
							<!-- <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label> -->
							<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input form-control">
							<input type="submit" class="btn_submit btn btn-default" value="검색">
						</form>

						<form name="finorderlist" id="finorderlist" method="post" action="./inorderlistdelete.php" onsubmit="return finorderlist_submit(this);">
						<input type="hidden" name="sst" value="<?php echo $sst; ?>">
						<input type="hidden" name="sod" value="<?php echo $sod; ?>">
						<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
						<input type="hidden" name="stx" value="<?php echo $stx; ?>">
						<input type="hidden" name="page" value="<?php echo $page; ?>">
						<input type="hidden" name="token" value="">

						<div class="tbl_head01 tbl_wrap" id="inorderlist">
							<table id="example1" class="table table-bordered table-striped dataTable member_list">
							<caption><?php echo $g5['title']; ?> 목록</caption>
							<thead>
							<tr>
								<th scope="col">
									<!-- <label for="chkall" class="sound_only">미완료주문 전체</label> -->
									<input type="checkbox" name="chkall" value="1" id="example-select-all" onclick="check_all(this.form)">
								</th>
								<th scope="col"><?php echo subject_sort_link('od_id') ?>주문번호</a></th>
								<th scope="col">PG</th>
								<th scope="col">주문자</th>
								<th scope="col">주문자전화</th>
								<th scope="col">받는분</th>
								<th scope="col">주문금액</th>
								<th scope="col">결제방법</th>
								<th scope="col">주문일시</th>
								<th scope="col">관리</th>
							</tr>
							</thead>
							<tbody>
							<?php
							for ($i=0; $row=sql_fetch_array($result); $i++) {
								$data = unserialize(base64_decode($row['dt_data']));

								switch($row['dt_pg']) {
									case 'inicis':
										$pg = 'KG이니시스';
										break;
									case 'lg':
										$pg = 'LGU+';
										break;
									default:
										$pg = 'KCP';
										break;
								}

								// 주문금액
								$sql = " select sum(if(io_type = '1', io_price, (ct_price + io_price)) * ct_qty) as price from {$g5['g5_shop_cart_table']} where od_id = '{$row['cart_id']}' and ct_status = '쇼핑' ";
								$ct = sql_fetch($sql);

								$bg = 'bg'.($i%2);
							?>

							<tr class="<?php echo $bg; ?>">
								<td class="td_chk">
									<input type="hidden" id="od_id_<?php echo $i; ?>" name="od_id[<?php echo $i; ?>]" value="<?php echo $row['od_id']; ?>">
									<input type="checkbox" id="chk_<?php echo $i; ?>" name="chk[]" value="<?php echo $i; ?>" title="내역선택">
								</td>
								<td class="td_odrnum2"><?php echo $row['od_id']; ?></td>
								<td class="td_center"><?php echo $pg; ?></td>
								<td class="td_name"><?php echo get_text($data['od_name']); ?></td>
								<td class="td_center"><?php echo get_text($data['od_tel']); ?></td>
								<td class="td_name"><?php echo get_text($data['od_b_name']); ?></td>
								<td class="td_price"><?php echo number_format($ct['price']); ?></td>
								<td class="td_center"><?php echo $data['od_settle_case']; ?></td>
								<td class="td_time"><?php echo $row['dt_time']; ?></td>
								<td class="td_mng td_mng_m">
									<a href="./inorderform.php?od_id=<?php echo $row['od_id']; ?>&amp;<?php echo $qstr; ?>" class="btn btn_03"><span class="sound_only"><?php echo $row['od_id']; ?> </span>보기</a>
									<a href="./inorderformupdate.php?w=d&amp;od_id=<?php echo $row['od_id']; ?>&amp;<?php echo $qstr; ?>" onclick="return delete_confirm(this);" class="btn btn_02"><span class="sound_only"><?php echo $row['od_id']; ?> </span>삭제</a>
								</td>
							</tr>

							<?php
							}

							if ($i == 0)
								echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
							?>
							</tbody>
							</table>
						</div>


						</form>

						<?php echo get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
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
$(function () {
	 
    // Korean
    var lang_kor = {
        "decimal" : "",
        "emptyTable" : "데이터가 없습니다.",
        "info" : "_START_ - _END_ (총 _TOTAL_ 명)",
        "infoEmpty" : "0명",
        "infoFiltered" : "(전체 _MAX_ 명 중 검색결과)",
        "infoPostFix" : "",
        "thousands" : ",",
        "lengthMenu" : "_MENU_ 개",
        "loadingRecords" : "로딩중...",
        "processing" : "처리중...",
        "search" : "검색 : ",
        "zeroRecords" : "검색된 데이터가 없습니다.",
        "paginate" : {
            "first" : "첫 페이지",
            "last" : "마지막 페이지",
            "next" : "다음",
            "previous" : "이전"
        },
        "aria" : {
            "sortAscending" : " :  오름차순 정렬",
            "sortDescending" : " :  내림차순 정렬"
        }
    };


    var table = $('#example1').DataTable({
	  'scrollX': true,	 
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : false,
	  'info'        : true,
	  'language' : lang_kor
	  

	
	//$('#division1').removeClass('sorting sorting_asc sorting_desc');
		  
	  
	  
	 // [{ 'targets': [0,5,10,11], 'orderable': false }, 	  ]



    });




	// Handle click on "Select all" control
	$('#example-select-all').on('click', function(){
	   // Get all rows with search applied
	   var rows = table.rows({ 'search': 'applied' }).nodes();
	   // Check/uncheck checkboxes for all rows in the table
	   $('input[type="checkbox"]', rows).prop('checked', this.checked);
	});

	// Handle click on checkbox to set state of "Select all" control
	$('#example tbody').on('change', 'input[type="checkbox"]', function(){
	   // If checkbox is not checked
	   if(!this.checked){
		  var el = $('#example-select-all').get(0);
		  // If "Select all" control is checked and has 'indeterminate' property
		  if(el && el.checked && ('indeterminate' in el)){
			 // Set visual state of "Select all" control
			 // as 'indeterminate'
			 el.indeterminate = true;
		  }
	   }
	});

 })

function finorderlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}
</script>


<?php
include_once ('../footer.php');
?>