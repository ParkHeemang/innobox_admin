<?php
$sub_menu = "300300";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

// 체크된 자료 삭제
if (isset($_POST['chk']) && is_array($_POST['chk'])) {
    for ($i=0; $i<count($_POST['chk']); $i++) {
        $pp_id = $_POST['chk'][$i];

        sql_query(" delete from {$g5['popular_table']} where pp_id = '$pp_id' ", true);
    }
}

$sql_common = " from {$g5['popular_table']} a ";
$sql_search = " where (1) ";

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "pp_word" :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
        case "pp_date" :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "pp_id";
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
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *
            {$sql_common}
            {$sql_search}
            {$sql_order}";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '인기검색어관리';
include_once('./admin.head.php');

$colspan = 4;

//CSS
add_stylesheet('<link rel="stylesheet" href="'.G5_ADMIN_URL. '/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 4);
//JS DataTables
add_javascript('<script src="'.G5_ADMIN_URL.'/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_ADMIN_URL.'/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);

?>


<script>
var list_update_php = '';
var list_delete_php = 'popular_list.php';
</script>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
		<h1 class="member_list_title">
		인기검색어관리
		<small>popular list</small>
		</h1>
		<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">회원관리</a></li>
		<li class="active">회원정보리스트</li>
		</ol>
	</section>
	
	<form name="member_delete" method="post" action="./member_list_update.php" onsubmit="return fmemberlist_submit(this);">
	<!-- Main content -->	
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<?php if ($is_admin == 'super'){ ?>
						<nav class="navbar navbar-default navbtn">
							<div class="text-right">							
								<button type="submit" class="btn btn_02 btn-danger">선택삭제</button>						
							</div>
						</nav>
						<?php } ?>
						<!-- <div class="local_ov01 local_ov">
							<?php echo $listall ?>
							<span class="btn_ov01"><span class="ov_txt">건수</span><span class="ov_num">  <?php echo number_format($total_count) ?>개</span></span>
						</div> -->
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<form name="fpopularlist" id="fpopularlist" method="post">
							<input type="hidden" name="sst" value="<?php echo $sst ?>">
							<input type="hidden" name="sod" value="<?php echo $sod ?>">
							<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
							<input type="hidden" name="stx" value="<?php echo $stx ?>">
							<input type="hidden" name="page" value="<?php echo $page ?>">
							<input type="hidden" name="token" value="<?php echo $token ?>">							
								<table id="example1" class="table table-bordered table-striped dataTable popular_list" width="100%">
									<!-- <caption><?php echo $g5['title']; ?> 목록</caption> -->
									<thead>
									<tr>
										<th scope="col">
											<!-- <label for="chkall" class="sound_only">현재 페이지 인기검색어 전체</label> -->
											<input type="checkbox" name="chkall" value="1" id="example-select-all" onclick="check_all(this.form)">
										</th>
										<th scope="col"><?php echo subject_sort_link('pp_word') ?>검색어</a></th>
										<th scope="col">등록일</th>
										<th scope="col">등록IP</th>
									</tr>
									</thead>
									<tbody>
									<?php
									for ($i=0; $row=sql_fetch_array($result); $i++) {

										$word = get_text($row['pp_word']);
										$bg = 'bg'.($i%2);
									?>

									<tr class="<?php echo $bg; ?>">
										<td class="td_chk">
											<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $word ?></label>
											<input type="checkbox" name="chk[]" value="<?php echo $row['pp_id'] ?>" id="chk_<?php echo $i ?>">
										</td>
										<td class="td_left"><a href="<?php echo $_SERVER['SCRIPT_NAME'] ?>?sfl=pp_word&amp;stx=<?php echo $word ?>"><?php echo $word ?></a></td>
										<td><?php echo $row['pp_date'] ?></td>
										<td><?php echo $row['pp_ip'] ?></td>
									</tr>

									<?php
									}

									if ($i == 0)
										echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
									?>
									</tbody>
								</table>							
						</form>
						<!-- <?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?> -->
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	  </form>
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
	

<script>
$(function() {

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

    $('#fpopularlist').submit(function() {
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

   
<?php
    include_once('./admin.tail.php');
?>