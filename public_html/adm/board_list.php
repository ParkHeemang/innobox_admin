<?php
$sub_menu = "300100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['board_table']} a ";
$sql_search = " where (1) ";

if ($is_admin != "super") {
    $sql_common .= " , {$g5['group_table']} b ";
    $sql_search .= " and (a.gr_id = b.gr_id and b.gr_admin = '{$member['mb_id']}') ";
}

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "bo_table" :
            $sql_search .= " ($sfl like '$stx%') ";
            break;
        case "a.gr_id" :
            $sql_search .= " ($sfl = '$stx') ";
            break;
        default :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "a.gr_id, a.bo_table";
    $sod = "asc";
}
$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '게시판관리';
include_once('./admin.head.php');

$colspan = 15;

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
			게시판관리
			<small>서브 타이틀</small>
		</h1>
	  <ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">회원관리</a></li>
		<li class="active">회원정보리스트</li>
	  </ol>
	</section>
	<form name="fboardlist" id="fboardlist" action="./board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
	<!-- Main content -->
	<section class="content">	
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<nav class="navbar navbar-default navbtn">
							<div class = "text-right">						  
								<input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn btn-success">
								<?php if ($is_admin == 'super') { ?>
								<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn-danger">
								<?php } ?>
								<?php if ($is_admin == 'super') { ?>
								<a href="./board_form.php" id="bo_add" class="btn btn-primary">게시판 추가</a>
								<?php } ?>						  
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
						<input type="hidden" name="token" value="<?php echo $token ?>">						
							<table id="example1" class="table table-bordered table-striped dataTable board_list" width="100%">
								<!-- <caption><?php echo $g5['title']; ?> 목록</caption>  -->
								<thead>
								<tr>
									<th scope="col">
										<!-- <label for="chkall" class="sound_only">게시판 전체</label>  -->
										<input type="checkbox" name="chkall" value="1" id="example-select-all" onclick="check_all(this.form)">
									</th>
									<th scope="col"><?php echo subject_sort_link('a.gr_id') ?>그룹</a></th>
									<th scope="col"><?php echo subject_sort_link('bo_table') ?>TABLE</a></th>
									<th scope="col"><?php echo subject_sort_link('bo_skin', '', 'desc') ?>스킨</a></th>
									<th scope="col"><?php echo subject_sort_link('bo_mobile_skin', '', 'desc') ?>모바일<br>스킨</a></th>
									<th scope="col"><?php echo subject_sort_link('bo_subject') ?>제목</a></th>
									<th scope="col">읽기P <!-- <span class="sound_only">포인트</span> --> </th>
									<th scope="col">쓰기P <!-- <span class="sound_only">포인트</span> --> </th>
									<th scope="col">댓글P <!-- <span class="sound_only">포인트</span> --> </th>
									<th scope="col">다운P <!-- <span class="sound_only">포인트</span> --> </th>
									<th scope="col"><?php echo subject_sort_link('bo_use_sns') ?>SNS<br>사용</a></th>
									<th scope="col"><?php echo subject_sort_link('bo_use_search') ?>검색<br>사용</a></th>
									<th scope="col"><?php echo subject_sort_link('bo_order') ?>출력<br>순서</a></th>
									<th scope="col">접속기기</th>
									<th scope="col">관리</th>
								</tr>
								</thead>
								<tbody>
								<?php
								for ($i=0; $row=sql_fetch_array($result); $i++) {
									$one_update = '<a href="./board_form.php?w=u&amp;bo_table='.$row['bo_table'].'&amp;'.$qstr.'" class="btn btn-info">수정</a>';
									$one_copy = '<a href="./board_copy.php?bo_table='.$row['bo_table'].'" class="board_copy btn btn-success target="win_board_copy">복사</a>';

									$bg = 'bg'.($i%2);
								?>

								<tr class="<?php echo $bg; ?>">
									<td class="td_chk">
										 <!-- <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['bo_subject']) ?></label>  -->
										<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>" class='chk'>
									</td>
									<td>
										<?php if ($is_admin == 'super'){ ?>
											<?php echo get_group_select("gr_id[$i]", $row['gr_id'], "class='form-control'") ?>
										<?php }else{ ?>
											<input type="hidden" name="gr_id[<?php echo $i ?>]" value="<?php echo $row['gr_id'] ?>"><?php echo $row['gr_subject'] ?>
										<?php } ?>
									</td>
									<td>
										<input type="hidden" name="board_table[<?php echo $i ?>]" value="<?php echo $row['bo_table'] ?>">
										<a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $row['bo_table'] ?>"><?php echo $row['bo_table'] ?></a>
									</td>
									<td>
										 <!-- <label for="bo_skin_<?php echo $i; ?>" class="sound_only">스킨</label>  -->
										<?php echo get_skin_select('board', 'bo_skin_'.$i, "bo_skin[$i]", $row['bo_skin'], 'class="form-control"'); ?>
									</td>
									<td>
										 <!-- <label for="bo_mobile_skin_<?php echo $i; ?>" class="sound_only">모바일 스킨</label> --> 
										<?php echo get_mobile_skin_select('board', 'bo_mobile_skin_'.$i, "bo_mobile_skin[$i]", $row['bo_mobile_skin'], 'class="form-control"'); ?>
									</td>
									<td>
										<!--  <label for="bo_subject_<?php echo $i; ?>" class="sound_only">게시판 제목<strong class="sound_only"> 필수</strong></label>  -->
										<input type="text" name="bo_subject[<?php echo $i ?>]" value="<?php echo get_text($row['bo_subject']) ?>" id="bo_subject_<?php echo $i ?>" required class="required tbl_input form-control bo_subject full_input" size="10">
									</td>
									<td class="td_numsmall">
										 <!-- <label for="bo_read_point_<?php echo $i; ?>" class="sound_only">읽기 포인트</label>  -->
										<input type="text" name="bo_read_point[<?php echo $i ?>]" value="<?php echo $row['bo_read_point'] ?>" id="bo_read_point_<?php echo $i; ?>" class="tbl_input form-control" size="2">
									</td>
									<td class="td_numsmall">
										 <!-- <label for="bo_write_point_<?php echo $i; ?>" class="sound_only">쓰기 포인트</label>  -->
										<input type="text" name="bo_write_point[<?php echo $i ?>]" value="<?php echo $row['bo_write_point'] ?>" id="bo_write_point_<?php echo $i; ?>" class="tbl_input form-control" size="2">
									</td>
									<td class="td_numsmall">
										 <!-- <label for="bo_comment_point_<?php echo $i; ?>" class="sound_only">댓글 포인트</label>  -->
										<input type="text" name="bo_comment_point[<?php echo $i ?>]" value="<?php echo $row['bo_comment_point'] ?>" id="bo_comment_point_<?php echo $i; ?>" class="tbl_input form-control" size="2">
									</td>
									<td class="td_numsmall">
										 <!-- <label for="bo_download_point_<?php echo $i; ?>" class="sound_only">다운<br>포인트</label>  -->
										<input type="text" name="bo_download_point[<?php echo $i ?>]" value="<?php echo $row['bo_download_point'] ?>" id="bo_download_point_<?php echo $i; ?>" class="tbl_input form-control" size="2">
									</td>
									<td class="td_numsmall">
										 <!-- <label for="bo_use_sns_<?php echo $i; ?>" class="sound_only">SNS<br>사용</label>  -->
										<input type="checkbox" name="bo_use_sns[<?php echo $i ?>]" value="1" id="bo_use_sns_<?php echo $i ?>" <?php echo $row['bo_use_sns']?"checked":"" ?>>
									</td>
									<td class="td_numsmall">
										 <!-- <label for="bo_use_search_<?php echo $i; ?>" class="sound_only">검색<br>사용</label> --> 
										<input type="checkbox" name="bo_use_search[<?php echo $i ?>]" value="1" id="bo_use_search_<?php echo $i ?>" <?php echo $row['bo_use_search']?"checked":"" ?>>
									</td>
									<td class="td_numsmall">
										 <!-- <label for="bo_order_<?php echo $i; ?>" class="sound_only">출력<br>순서</label>  -->
										<input type="text" name="bo_order[<?php echo $i ?>]" value="<?php echo $row['bo_order'] ?>" id="bo_order_<?php echo $i ?>" class="tbl_input form-control" size="2">
									</td>
									<td class="td_mngsmall">
									<!-- 	<label for="bo_device_<?php echo $i; ?>" class="sound_only">접속기기</label> -->
										<select name="bo_device[<?php echo $i ?>]" id="bo_device_<?php echo $i ?>" class="form-control">
											<option value="both"<?php echo get_selected($row['bo_device'], 'both', true); ?>>모두</option>
											<option value="pc"<?php echo get_selected($row['bo_device'], 'pc'); ?>>PC</option>
											<option value="mobile"<?php echo get_selected($row['bo_device'], 'mobile'); ?>>모바일</option>
										</select>
									</td>
									<td class="td_mng td_mng_m">
										<?php echo $one_update ?>
										<?php echo $one_copy ?>
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
    
    
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>

<script>
  $(function () {
    // Korean
    var lang_kor = {
        "decimal" : "",
        "emptyTable" : "데이터가 없습니다.",
        "info" : "_START_ - _END_ (총 _TOTAL_ 개)",
        "infoEmpty" : "0명",
        "infoFiltered" : "(전체 _MAX_ 개 중 검색결과)",
        "infoPostFix" : "",
        "thousands" : ",",
        "lengthMenu" : "_MENU_ 개",
        "loadingRecords" : "로딩중...",
        "processing" : "처리중...",
        "search" : "검색:",
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
      'ordering'    : true,
	  'order': [  [2, 'asc']],
      'info'        : true,
	  'language' : lang_kor,
      'autoWidth'   : false,
	  'columnDefs': [{
            'bSortable': false,
            'aTargets': [0,3]
        }]
    });


	$('#example-select-all').on('click', function(){
	   // Get all rows with search applied
	   var rows = table.rows({ 'search': 'applied' }).nodes();
	   // Check/uncheck checkboxes for all rows in the table
	   $('input[class="chk"]', rows).prop('checked', this.checked);
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


$(function(){
    $(".board_copy").click(function(){
        window.open(this.href, "win_board_copy", "left=100,top=100,width=550,height=450");
        return false;
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

function check_all(f)
{
    var chk = document.getElementsByName("chk[]");

    for (i=0; i<chk.length; i++)
        chk[i].checked = f.chkall.checked;
}

</script>	
<?php
    include_once('./admin.tail.php');
?>