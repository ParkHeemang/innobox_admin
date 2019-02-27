<?php
$sub_menu = "200900";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['poll_table']} ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "po_id";
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

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '투표관리';
include_once('./admin.head.php');

$colspan = 7;

//CSS
add_stylesheet('<link rel="stylesheet" href="'.G5_ADMIN_URL. '/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 4);

//JS DataTables
add_javascript('<script src="'.G5_ADMIN_URL.'/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_ADMIN_URL.'/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);


?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1 class="member_list_title">
		투표관리
		<small>poll list</small>
	  </h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo G5_ADMIN_URL?>/"><i class="fa fa-dashboard"></i> Home</a></li>
		</ol>
	</section>

	<form name="fpolllist" id="fpolllist" action="./poll_delete.php" method="post">
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">						
						<nav class="navbar navbar-default navbtn">
						  <div class = "fixed-button" align="right">
								<input type="submit" value="선택 삭제" class="btn btn_02">
								<a href="./poll_form.php" id="poll_add" class="btn btn-danger">투표 추가</a>
						  </div>
						</nav>
					</div>
					<!-- /.box-header -->
					<div class="box-body">		
						<input type="hidden" name="sst" value="<?php echo $sst ?>">
						<input type="hidden" name="sod" value="<?php echo $sod ?>">
						<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
						<input type="hidden" name="stx" value="<?php echo $stx ?>">
						<input type="hidden" name="page" value="<?php echo $page ?>">
						<input type="hidden" name="token" value="">
													
						<table id="example1" class="table table-bordered table-striped dataTable poll_list" width = '100%'>
							<!-- <caption><?php echo $g5['title']; ?> 목록</caption> -->
							<thead>
							<tr>
								<th scope="col">
								<!-- 	<label for="chkall" class="sound_only">현재 페이지 투표 전체</label> -->
									<input type="checkbox" name="chkall" value="1" id="example-select-all" onclick="check_all(this.form)">
								</th>
								<th scope="col">번호</th>
								<th scope="col">제목</th>
								<th scope="col">투표권한</th>
								<th scope="col">투표수</th>
								<th scope="col">기타의견</th>
								<th scope="col">관리</th>
							</tr>
							</thead>
							<tbody>
							<?php
							for ($i=0; $row=sql_fetch_array($result); $i++) {
								$sql2 = " select sum(po_cnt1+po_cnt2+po_cnt3+po_cnt4+po_cnt5+po_cnt6+po_cnt7+po_cnt8+po_cnt9) as sum_po_cnt from {$g5['poll_table']} where po_id = '{$row['po_id']}' ";
								$row2 = sql_fetch($sql2);
								$po_etc = ($row['po_etc']) ? "사용" : "미사용";
							
								$s_mod = '<a href="./poll_form.php?'.$qstr.'&amp;w=u&amp;po_id='.$row['po_id'].'" class="btn btn_03 btn-success">수정</a>';
							
								$bg = 'bg'.($i%2);
							?>
							
							<tr class="<?php echo $bg; ?>">
								<td class="td_chk">
									<label for="chk_<?php echo $i; ?>" class="sound_only"><!-- <?php echo cut_str(get_text($row['po_subject']),70) ?> 투표 --></label>
									<input type="checkbox" name="chk[]" value="<?php echo $row['po_id'] ?>" id="chk_<?php echo $i ?>">
								</td>
								<td class="td_num"><?php echo $row['po_id'] ?></td>
								<td class="td_left"><?php echo cut_str(get_text($row['po_subject']),70) ?></td>
								<td class="td_num"><?php echo $row['po_level'] ?></td>
								<td class="td_num"><?php echo $row2['sum_po_cnt'] ?></td>
								<td class="td_etc"><?php echo $po_etc ?></td>
								<td class="td_mng td_mng_s"><?php echo $s_mod ?></td>
							</tr>
							
							<?php
							}
							
							if ($i==0)
								echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
							?>
							</tbody>
						</table>
						
						<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
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
	</form>
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
	  'autowidth' : false,
	  'scrollX': true,	 
      'paging'      : true,
      'lengthChange': false,
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

 $(document).scroll(function(e){
    var scrollTop = $(document).scrollTop();
    if(scrollTop > 0){
        console.log(scrollTop);
        $('.navbar').removeClass('navbar-static-top').addClass('navbar-fixed-top');
    } else {
        $('.navbar').removeClass('navbar-fixed-top').addClass('navbar-static-top');
    }
});	

$(function() {
    $('#fpolllist').submit(function() {
        if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
            if (!is_checked("chk[]")) {
                alert("선택삭제 하실 항목을 하나 이상 선택하세요.");
                return false;
            }

            return true;
        } else {
            return false;
        }
    });
});

</script>


<?php
    include_once('./admin.tail.php');
?>