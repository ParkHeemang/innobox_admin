<?php
$sub_menu = "300200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

if (!isset($group['gr_device'])) {
    // 게시판 그룹 사용 필드 추가
    // both : pc, mobile 둘다 사용
    // pc : pc 전용 사용
    // mobile : mobile 전용 사용
    // none : 사용 안함
    sql_query(" ALTER TABLE  `{$g5['board_group_table']}` ADD  `gr_device` ENUM(  'both',  'pc',  'mobile' ) NOT NULL DEFAULT  'both' AFTER  `gr_subject` ", false);
}

$sql_common = " from {$g5['group_table']} ";

$sql_search = " where (1) ";
if ($is_admin != 'super')
    $sql_search .= " and (gr_admin = '{$member['mb_id']}') ";

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "gr_id" :
        case "gr_admin" :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if ($sst)
    $sql_order = " order by {$sst} {$sod} ";
else
    $sql_order = " order by gr_id asc ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">처음</a>';

$g5['title'] = '게시판그룹설정';
include_once('./admin.head.php');

$colspan = 10;

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
		게시판그룹관리
		<small>서브 타이틀</small>
		</h1>
		<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">회원관리</a></li>
		<li class="active">회원정보리스트</li>
		</ol>
	</section>
	
	<form name="fboardgrouplist" id="fboardgrouplist" action="./boardgroup_list_update.php" onsubmit="return fboardgrouplist_submit(this);" method="post">
	<!-- Main content -->
	<section class="content">	
		<div class="row">
			<div class="col-xs-12">
				<div class="box" style="padding-bottom: 7px">
					<div class="box-header">
						<nav class="navbar navbar-default navbtn">
							<div class = "fixed-button" align="right">
							  
								<input type="submit" name="act_button" onclick="document.pressed=this.value" value="선택수정" class="btn btn-success">
								<input type="submit" name="act_button" onclick="document.pressed=this.value" value="선택삭제" class="btn btn-danger">
								<a href="./boardgroup_form.php" class="btn btn-primary">게시판그룹 추가</a>
							</div>
						</nav>

						<input type="hidden" name="sst" value="<?php echo $sst ?>">
						<input type="hidden" name="sod" value="<?php echo $sod ?>">
						<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
						<input type="hidden" name="stx" value="<?php echo $stx ?>">
						<input type="hidden" name="page" value="<?php echo $page ?>">
						<input type="hidden" name="token" value="">
					</div>
					<!-- /.box-header -->
					<div class="box-body">						
						<table id="example1" class="table table-bordered table-striped dataTable member_list" width="100%">
						<!-- <caption><?php echo $g5['title']; ?> 목록</caption> -->
						<thead>
						<tr>
							<th scope="col">
								<!-- <label for="chkall" class="sound_only">그룹 전체</label> -->
								<input type="checkbox" name="chkall" value="1" id="example-select-all" onclick="check_all(this.form)">
							</th>
							<th scope="col"><?php echo subject_sort_link('gr_id') ?>그룹아이디</a></th>
							<th scope="col"><?php echo subject_sort_link('gr_subject') ?>제목</a></th>
							<th scope="col"><?php echo subject_sort_link('gr_admin') ?>그룹관리자</a></th>
							<th scope="col">게시판</th>
							<th scope="col">접근<br>사용</th>
							<th scope="col">접근<br>회원수</th>
							<th scope="col"><?php echo subject_sort_link('gr_order') ?>출력<br>순서</a></th>
							<th scope="col">접속기기</th>
							<th scope="col">관리</th>
						</tr>
						</thead>
						<tbody>
						<?php
						for ($i=0; $row=sql_fetch_array($result); $i++)
						{
							// 접근회원수
							$sql1 = " select count(*) as cnt from {$g5['group_member_table']} where gr_id = '{$row['gr_id']}' ";
							$row1 = sql_fetch($sql1);

							// 게시판수
							$sql2 = " select count(*) as cnt from {$g5['board_table']} where gr_id = '{$row['gr_id']}' ";
							$row2 = sql_fetch($sql2);

							$s_upd = '<a href="./boardgroup_form.php?'.$qstr.'&amp;w=u&amp;gr_id='.$row['gr_id'].'" class="btn btn-success">수정</a>';

							$bg = 'bg'.($i%2);
						?>

						<tr class="<?php echo $bg; ?>">
							<td class="td_chk">
								<input type="hidden" name="group_id[<?php echo $i ?>]" value="<?php echo $row['gr_id'] ?>">
								<!-- <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['gr_subject'] ?> 그룹</label> -->
								<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>" class="chk">
							</td>
							<td class="td_left"><a href="<?php echo G5_BBS_URL ?>/group.php?gr_id=<?php echo $row['gr_id'] ?>"><?php echo $row['gr_id'] ?></a></td>
							<td class="td_input">
								<!-- <label for="gr_subject_<?php echo $i; ?>" class="sound_only">그룹제목</label> -->
								<input type="text" name="gr_subject[<?php echo $i ?>]" value="<?php echo get_text($row['gr_subject']) ?>" id="gr_subject_<?php echo $i ?>" class="tbl_input form-control board_group_title full_input">
							</td>
							<td class="td_mng td_input">
							<?php if ($is_admin == 'super'){ ?>
								<!-- <label for="gr_admin_<?php echo $i; ?>" class="sound_only">그룹관리자</label> -->
								<input type="text" name="gr_admin[<?php echo $i ?>]" value="<?php echo $row['gr_admin'] ?>" id="gr_admin_<?php echo $i ?>" class="tbl_input form-control" size="10" maxlength="20">
							<?php }else{ ?>
								<input type="hidden" name="gr_admin[<?php echo $i ?>]" value="<?php echo $row['gr_admin'] ?>"><?php echo $row['gr_admin'] ?>
							<?php } ?>
							</td>
							<td class="td_num"><a href="./board_list.php?sfl=a.gr_id&amp;stx=<?php echo $row['gr_id'] ?>"><?php echo $row2['cnt'] ?></a></td>
							<td class="td_numsmall">
								<!--  <label for="gr_use_access_<?php echo $i; ?>" class="sound_only">접근회원 사용</label> -->
								<input type="checkbox" name="gr_use_access[<?php echo $i ?>]" <?php echo $row['gr_use_access']?'checked':'' ?> value="1" id="gr_use_access_<?php echo $i ?>">
							</td>
							<td class="td_num"><a href="./boardgroupmember_list.php?gr_id=<?php echo $row['gr_id'] ?>"><?php echo $row1['cnt'] ?></a></td>
							<td class="td_numsmall">
								<!-- <label for="gr_order_<?php echo $i; ?>" class="sound_only">메인메뉴 출력순서</label> -->
								<input type="text" name="gr_order[<?php echo $i ?>]" value="<?php echo $row['gr_order'] ?>" id="gr_order_<?php echo $i ?>" class="tbl_input form-control" size="2">
							</td>
							<td class="td_mng">
								<!-- <label for="gr_device_<?php echo $i; ?>" class="sound_only">접속기기</label> -->
								<select name="gr_device[<?php echo $i ?>]" id="gr_device_<?php echo $i ?>" class="form-control">
									<option value="both"<?php echo get_selected($row['gr_device'], 'both'); ?>>모두</option>
									<option value="pc"<?php echo get_selected($row['gr_device'], 'pc'); ?>>PC</option>
									<option value="mobile"<?php echo get_selected($row['gr_device'], 'mobile'); ?>>모바일</option>
								</select>
							</td>
							<td class="td_mng td_mng_s"><?php echo $s_upd ?></td>
						</tr>

						<?php
							}
						if ($i == 0)
							echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
						?>
						</table>
					</div>
					<!-- /.box-body -->
				<div class="alert alert-default alert-dismissible" style="margin-bottom : 10px;">
				<p>
					접근사용 옵션을 설정하시면 관리자가 지정한 회원만 해당 그룹에 접근할 수 있습니다.<br>
					접근사용 옵션은 해당 그룹에 속한 모든 게시판에 적용됩니다.
				</p>
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
    
<?php
$pagelist = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page=');
echo $pagelist;
?>


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
      'ordering'    : false,
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