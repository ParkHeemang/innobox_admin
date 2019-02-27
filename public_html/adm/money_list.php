<?php
$sub_menu = "200250";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['money_table']} ";

$sql_search = " where (1) ";

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_id' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "mo_id";
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

$mb = array();
if ($sfl == 'mb_id' && $stx)
    $mb = get_member($stx);

$g5['title'] = '예치금관리';
include_once ('./admin.head.php');

$colspan = 9;

$mo_expire_term = '';
if($config['cf_money_term'] > 0) {
    $mo_expire_term = $config['cf_money_term'];
}

if (strstr($sfl, "mb_id"))
    $mb_id = $stx;
else
    $mb_id = "";


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
		예치금관리
		<small>money list</small>
	  </h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo G5_ADMIN_URL?>/"><i class="fa fa-dashboard"></i> Home</a></li>
		</ol>
	</section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<form name="fmaillist" id="fmaillist" action="./mail_delete.php" method="post">
						<nav class="navbar navbar-default navbtn">
							<div class = "fixed-button" align="right">
								<div align="right" style="width:45%">
									<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn-default" style="color : white; background-color: #3c8dbc !important; margin-bottom:10px;" />
								</div>
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

						
						<table id="example1" class="table table-bordered table-striped dataTable member_list">
							<!--     <caption><?php echo $g5['title']; ?> 목록</caption> -->
							<thead>
							<tr>
								<th scope="col">
									<!-- <label for="chkall" class="sound_only">예치금 내역 전체</label> -->
									<input type="checkbox" name="chkall" value="1" id="example-select-all" onclick="check_all(this.form)">
								</th>
								<th scope="col"><?php echo subject_sort_link('mb_id') ?>회원아이디</a></th>
								<th scope="col">이름</th>
								<th scope="col">닉네임</th>
								<th scope="col"><?php echo subject_sort_link('mo_content') ?>예치금 내용</a></th>
								<th scope="col"><?php echo subject_sort_link('mo_money') ?>예치금</a></th>
								<th scope="col"><?php echo subject_sort_link('mo_datetime') ?>일시</a></th>
								<th scope="col">만료일</th>
								<th scope="col">예치금합</th>
							</tr>
							</thead>
							<tbody>
							<?php
							for ($i=0; $row=sql_fetch_array($result); $i++) {
								if ($i==0 || ($row2['mb_id'] != $row['mb_id'])) {
									$sql2 = " select mb_id, mb_name, mb_nick, mb_email, mb_homepage, mb_money from {$g5['member_table']} where mb_id = '{$row['mb_id']}' ";
									$row2 = sql_fetch($sql2);
								}

								$mb_nick = get_sideview($row['mb_id'], $row2['mb_nick'], $row2['mb_email'], $row2['mb_homepage']);

								$link1 = $link2 = '';
								if (!preg_match("/^\@/", $row['mo_rel_table']) && $row['mo_rel_table']) {
									$link1 = '<a href="'.G5_BBS_URL.'/board.php?bo_table='.$row['mo_rel_table'].'&amp;wr_id='.$row['mo_rel_id'].'" target="_blank">';
									$link2 = '</a>';
								}

								$expr = '';
								if($row['mo_expired'] == 1)
									$expr = ' txt_expired';

								$bg = 'bg'.($i%2);
							?>

							<tr class="<?php echo $bg; ?>">
								<td class="td_chk">
									<input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>" id="mb_id_<?php echo $i ?>">
									<input type="hidden" name="mo_id[<?php echo $i ?>]" value="<?php echo $row['mo_id'] ?>" id="mo_id_<?php echo $i ?>">
									<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['mo_content'] ?> 내역</label>
									<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
								</td>
								<td class="td_mbid"><a href="?sfl=mb_id&amp;stx=<?php echo $row['mb_id'] ?>"><?php echo $row['mb_id'] ?></a></td>
								<td class="td_mbname"><?php echo get_text($row2['mb_name']); ?></td>
								<td class="td_name sv_use"><div><?php echo $mb_nick ?></div></td>
								<td class="td_pt_log"><?php echo $link1 ?><?php echo $row['mo_content'] ?><?php echo $link2 ?></td>
								<td class="td_numbig td_pt"><?php echo number_format($row['mo_money']) ?></td>
								<td class="td_datetime"><?php echo $row['mo_datetime'] ?></td>
								<td class="td_date<?php echo $expr; ?>">
									<?php if ($row['mo_expired'] == 1) { ?>
									만료<?php echo substr(str_replace('-', '', $row['mo_expire_date']), 2); ?>
									<?php } else echo $row['mo_expire_date'] == '9999-12-31' ? '&nbsp;' : $row['mo_expire_date']; ?>
								</td>
								<td class="td_numbig td_pt"><?php echo number_format($row['mo_mb_money']) ?></td>
							</tr>

							<?php
							}

							if ($i == 0)
								echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
							?>
							</tbody>
						</table>
						

						</form>

						<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

						<section id="money_mng">
							<h4 class="text-light-blue">개별회원 예치금 증감 설정</h4>

							<form name="fmoneylist2" method="post" id="fmoneylist2" action="./money_update.php" autocomplete="off">
							<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
							<input type="hidden" name="stx" value="<?php echo $stx ?>">
							<input type="hidden" name="sst" value="<?php echo $sst ?>">
							<input type="hidden" name="sod" value="<?php echo $sod ?>">
							<input type="hidden" name="page" value="<?php echo $page ?>">
							<input type="hidden" name="token" value="<?php echo $token ?>">

							<div class="tbl_frm01 tbl_wrap">
								<table  id="example2" class="table table-bordered table-hover dataTable member-form">
								<colgroup>
									<col class="grid_4">
									<col>
								</colgroup>
								<tbody>
								<tr>
									<th scope="row"><label for="mb_id">회원아이디<strong class="sound_only">필수</strong></label></th>
									<td>									   
										<input type="text" name="mb_id" value="<?php echo $mb_id ?>" id="mb_id" class="required frm_input form-control " required>									   
										<button type="button" id="sch_member" class="btn btn-warning">회원검색</button>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="mo_content">예치금 내용<strong class="sound_only">필수</strong></label></th>
									<td>
									<div class="col-xs-5">
									   <input type="text" name="mo_content" id="mo_content" required class="required frm_input form-control " size="80">
									   </div>               
									   </td>
								</tr>
								<tr>
									<th scope="row"><label for="mo_money">예치금<strong class="sound_only">필수</strong></label></th>
									<td><div class="col-xs-2">
									<input type="text" name="mo_money" id="mo_money" required class="required frm_input form-control ">
									</div>
									</td>
								</tr>
								<?php if($config['cf_money_term'] > 0) { ?>
								<tr>
									<th scope="row"><label for="mo_expire_term">예치금 유효기간</label></th>
									<td><input type="text" name="mo_expire_term" value="<?php echo $mo_expire_term; ?>" id="mo_expire_term" class="예치금관리" size="5"> 일</td>
								</tr>
								<?php } ?>
								</tbody>
								</table>
							</div>

							<div class="btn_confirm01 btn_confirm">
								<input type="submit" value="확인" class="btn btn-primary">
							</div>

							</form>
						</section>
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
$(function() {
    $("#sch_member").click(function() {
        var opt = "left=50,top=50,width=520,height=600,scrollbars=1";
        var url = "./select_member.php?form=fmoneylist2&field=mb_id";
        window.open(url, "win_member", opt);
    });
});
</script>

<script>
function fmoneylist_submit(f)
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
 $(document).scroll(function(e){
    var scrollTop = $(document).scrollTop();
    if(scrollTop > 0){
        console.log(scrollTop);
        $('.navbar').removeClass('navbar-static-top').addClass('navbar-fixed-top');
    } else {
        $('.navbar').removeClass('navbar-fixed-top').addClass('navbar-static-top');
    }
});	


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
	  'scrollX': false,	 
	  'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : false,
	  'order': [  [2, 'asc']],
      'info'        : false,
	  'language' : lang_kor,
      'autoWidth'   : false,
	  'columnDefs': [{
            'bSortable': false,
            'aTargets': [1]
        }]

    });

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
    include_once('./admin.tail.php');
?>