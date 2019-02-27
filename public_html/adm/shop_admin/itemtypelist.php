<?php
$sub_menu = '400610';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$doc = strip_tags($doc);

$g5['title'] = '상품유형관리';
include_once ('../admin.head.php');

/*
$sql_search = " where 1 ";
if ($search != "") {
	if ($sel_field != "") {
    	$sql_search .= " and $sel_field like '%$search%' ";
    }
}

if ($sel_ca_id != "") {
    $sql_search .= " and (ca_id like '$sel_ca_id%' or ca_id2 like '$sel_ca_id%' or ca_id3 like '$sel_ca_id%') ";
}

if ($sel_field == "")  $sel_field = "it_name";
*/

$where = " where ";
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
    $sql_search .= " $where (ca_id like '$sca%' or ca_id2 like '$sca%' or ca_id3 like '$sca%') ";
}

if ($sfl == "")  $sfl = "it_name";

if (!$sst)  {
    $sst  = "it_id";
    $sod = "desc";
}
$sql_order = "order by $sst $sod";

$sql_common = "  from {$g5['g5_shop_item_table']} ";
$sql_common .= $sql_search;

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql  = " select it_id,
                 it_name,
                 it_type1,
                 it_type2,
                 it_type3,
                 it_type4,
                 it_type5
          $sql_common
          $sql_order
          limit $from_record, $rows ";
$result = sql_query($sql);

$qstr  = $qstr.'&amp;sca='.$sca.'&amp;page='.$page.'&amp;save_stx='.$stx;

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

//CSS
add_stylesheet('<link rel="stylesheet" href="'.G5_PHP_URL. 'bower_components/bootstrap/dist/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 5);
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL2.'/bootstrap.custom.css?ver='.G5_CSS_VER.'"></script>',8);
//JS DataTables
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);


?>
<link rel="stylesheet" href="http://test.innobox.co.kr/admlte2/bower_components/bootstrap/dist/css/dataTables.bootstrap.min.css?ver=171222">
<link rel="stylesheet" href="http://test.innobox.co.kr/admlte2/css/bootstrap.custom.css?ver=171222">
<script src="http://test.innobox.co.kr/admlte2//bower_components/datatables.net/js/jquery.dataTables.min.js?ver=171222"></script>
<script src="http://test.innobox.co.kr/admlte2//bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js?ver=171222"></script>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
		<h1 class="member_list_title">
		상품유형관리
		<small>itemtypelist</small>
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
					<div class="box-body">					
						<div class="local_ov01 local_ov">
							<?php echo $listall; ?>
								<span class="btn_ov01"><span class="ov_txt">전체 상품</span><span class="ov_num">  <?php echo $total_count; ?>개</span></span>
						</div>

						<form name="flist" class="local_sch01 local_sch">
						<input type="hidden" name="doc" value="<?php echo $doc; ?>">
						<input type="hidden" name="page" value="<?php echo $page; ?>">

						<!-- <label for="sca" class="sound_only">분류선택</label> -->
						<select name="sca" id="sca" class="form-control">
							<option value="">전체분류</option>
							<?php
							$sql1 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} order by ca_order, ca_id ";
							$result1 = sql_query($sql1);
							for ($i=0; $row1=sql_fetch_array($result1); $i++) {
								$len = strlen($row1['ca_id']) / 2 - 1;
								$nbsp = "";
								for ($i=0; $i<$len; $i++) $nbsp .= "&nbsp;&nbsp;&nbsp;";
								echo '<option value="'.$row1['ca_id'].'" '.get_selected($sca, $row1['ca_id']).'>'.$nbsp.$row1['ca_name'].PHP_EOL;
							}
							?>
						</select>

						<label for="sfl" class="sound_only">검색대상</label>
						<select name="sfl" id="sfl" class="form-control">
							<option value="it_name" <?php echo get_selected($sfl, 'it_name'); ?>>상품명</option>
							<option value="it_id" <?php echo get_selected($sfl, 'it_id'); ?>>상품코드</option>
						</select>

						<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
						<input type="text" name="stx" value="<?php echo $stx; ?>" id="stx" required class="frm_input form-control required">
						<input type="submit" value="검색" class="btn_submit btn btn-default">

						</form>

						<form name="fitemtypelist" method="post" action="./itemtypelistupdate.php">
						<input type="hidden" name="sca" value="<?php echo $sca; ?>">
						<input type="hidden" name="sst" value="<?php echo $sst; ?>">
						<input type="hidden" name="sod" value="<?php echo $sod; ?>">
						<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
						<input type="hidden" name="stx" value="<?php echo $stx; ?>">
						<input type="hidden" name="page" value="<?php echo $page; ?>">

						<div class="tbl_head01 tbl_wrap">
							<table id="example1" class="table table-bordered table-striped dataTable member_list" width="100%">
							<caption><?php echo $g5['title']; ?> 목록</caption>
							<thead>
							<tr>
								<th scope="col"><?php echo subject_sort_link("it_id", $qstr, 1); ?>상품코드</a></th>
								<th scope="col"><?php echo subject_sort_link("it_name"); ?>상품명</a></th>
								<th scope="col"><?php echo subject_sort_link("it_type1", $qstr, 1); ?>히트<br>상품</a></th>
								<th scope="col"><?php echo subject_sort_link("it_type2", $qstr, 1); ?>추천<br>상품</a></th>
								<th scope="col"><?php echo subject_sort_link("it_type3", $qstr, 1); ?>신규<br>상품</a></th>
								<th scope="col"><?php echo subject_sort_link("it_type4", $qstr, 1); ?>인기<br>상품</a></th>
								<th scope="col"><?php echo subject_sort_link("it_type5", $qstr, 1); ?>할인<br>상품</a></th>
								<th scope="col">관리</th>
							</tr>
							</thead>
							<tbody>
							<?php for ($i=0; $row=sql_fetch_array($result); $i++) {
								$href = G5_SHOP_URL.'/item.php?it_id='.$row['it_id'];

								$bg = 'bg'.($i%2);
							?>
							<tr class="<?php echo $bg; ?>">
								<td class="td_code">
									<input type="hidden" name="it_id[<?php echo $i; ?>]" value="<?php echo $row['it_id']; ?>">
									<?php echo $row['it_id']; ?>
								</td>
								<td class="td_left"><a href="<?php echo $href; ?>"><?php echo get_it_image($row['it_id'], 50, 50); ?><?php echo cut_str(stripslashes($row['it_name']), 60, "&#133"); ?></a></td>
								<td class="td_chk2">
									<!-- <label for="type1_<?php echo $i; ?>" class="sound_only">히트상품</label> -->
									<input type="checkbox" name="it_type1[<?php echo $i; ?>]" value="1" id="type1_<?php echo $i; ?>" <?php echo ($row['it_type1'] ? 'checked' : ''); ?>>
								</td>
								<td class="td_chk2">
									<!-- <label for="type2_<?php echo $i; ?>" class="sound_only">추천상품</label> -->
									<input type="checkbox" name="it_type2[<?php echo $i; ?>]" value="1" id="type2_<?php echo $i; ?>" <?php echo ($row['it_type2'] ? 'checked' : ''); ?>>
								</td>
								<td class="td_chk2">
									<label for="type3_<?php echo $i; ?>" class="sound_only">신규상품</label>
									<input type="checkbox" name="it_type3[<?php echo $i; ?>]" value="1" id="type3_<?php echo $i; ?>" <?php echo ($row['it_type3'] ? 'checked' : ''); ?>>
								</td>
								<td class="td_chk2">
									<label for="type4_<?php echo $i; ?>" class="sound_only">인기상품</label>
									<input type="checkbox" name="it_type4[<?php echo $i; ?>]" value="1" id="type4_<?php echo $i; ?>" <?php echo ($row['it_type4'] ? 'checked' : ''); ?>>
								</td>
								<td class="td_chk2">
									<label for="type5_<?php echo $i; ?>" class="sound_only">할인상품</label>
									<input type="checkbox" name="it_type5[<?php echo $i; ?>]" value="1" id="type5_<?php echo $i; ?>" <?php echo ($row['it_type5'] ? 'checked' : ''); ?>>
								</td>
								<td class="td_mng td_mng_s">
									<a href="./itemform.php?w=u&amp;it_id=<?php echo $row['it_id']; ?>&amp;ca_id=<?php echo $row['ca_id']; ?>&amp;<?php echo $qstr; ?>" class="btn btn_03 btn-primary">수정</a>
								 </td>
							</tr>
							<?php
							}

							if (!$i)
								echo '<tr><td colspan="8" class="empty_table"><span>자료가 없습니다.</span></td></tr>';
							?>
							</tbody>
							</table>
						</div>

						<div class="btn_confirm03 btn_confirm">
							<input type="submit" value="일괄수정" class="btn_submit btn btn-danger">
						</div>
						</form>
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
      'lengthChange': false,
      'searching'   : false,
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

 
 </script>


<?php
include_once ('../footer.php');
?>
