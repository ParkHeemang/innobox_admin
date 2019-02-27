<?php
$sub_menu = "100200";
include_once('./_common.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

$sql_common = " from {$g5['auth_table']} a left join {$g5['member_table']} b on (a.mb_id=b.mb_id) ";

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
    $sst  = "a.mb_id, au_menu";
    $sod = "";
}
$sql_order = " order by $sst $sod ";

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

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall btn_ov02">전체목록</a>';

$g5['title'] = "관리권한설정";
include_once('./admin.head.php');

$colspan = 5;

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
		관리권한설정
		<small>auth list</small>
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
				<form name="fauthlist" id="fauthlist" method="post" action="./auth_list_delete.php" onsubmit="return fauthlist_submit(this);">
				<div class="box">
					<div class="box-header">
						<nav class="navbar navbar-default navbtn">
							<div class="text-right">							
								<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn-danger btn_02">					
							</div>
						</nav>
					</div>
					<!-- /.box-header -->
					<div class="box-body">						
						<div class="local_ov01 local_ov">
							<?php echo $listall ?>
							<span class="btn_ov01"><span class="ov_txt">설정된 관리권한</span><span class="ov_num"><?php echo number_format($total_count) ?>건</span></span>
						</div>						
							<input type="hidden" name="sst" value="<?php echo $sst ?>">
							<input type="hidden" name="sod" value="<?php echo $sod ?>">
							<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
							<input type="hidden" name="stx" value="<?php echo $stx ?>">
							<input type="hidden" name="page" value="<?php echo $page ?>">
							<input type="hidden" name="token" value="">							
							    <table id="example1" class="table table-bordered table-striped dataTable member_list" width="100%">
									<thead>
									<tr>
										<th scope="col">
											<!-- <label for="chkall" class="sound_only">현재 페이지 회원 전체</label> -->
											<input type="checkbox" name="chkall" value="1" id="example-select-all" onclick="check_all(this.form)">
										</th>
										<th scope="col"><?php echo subject_sort_link('a.mb_id') ?>회원아이디</a></th>
										<th scope="col"><?php echo subject_sort_link('mb_nick') ?>닉네임</a></th>
										<th scope="col">메뉴</th>
										<th scope="col">권한</th>
									</tr>
									</thead>
									<tbody>
									<?php
									$count = 0;
									for ($i=0; $row=sql_fetch_array($result); $i++)
									{
										$is_continue = false;
										// 회원아이디가 없는 메뉴는 삭제함
										if($row['mb_id'] == '' && $row['mb_nick'] == '') {
											sql_query(" delete from {$g5['auth_table']} where au_menu = '{$row['au_menu']}' ");
											$is_continue = true;
										}
								
										// 메뉴번호가 바뀌는 경우에 현재 없는 저장된 메뉴는 삭제함
										if (!isset($auth_menu[$row['au_menu']]))
										{
											sql_query(" delete from {$g5['auth_table']} where au_menu = '{$row['au_menu']}' ");
											$is_continue = true;
										}
								
										if($is_continue)
											continue;
								
										$mb_nick = get_sideview($row['mb_id'], $row['mb_nick'], $row['mb_email'], $row['mb_homepage']);
								
										$bg = 'bg'.($i%2);
									?>
									<tr class="<?php echo $bg; ?>">
										<td class="td_chk">
											<input type="hidden" name="au_menu[<?php echo $i ?>]" value="<?php echo $row['au_menu'] ?>">
											<input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>">
											<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['mb_nick'] ?>님 권한</label>
											<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
										</td>
										<td class="td_mbid"><a href="?sfl=a.mb_id&amp;stx=<?php echo $row['mb_id'] ?>"><?php echo $row['mb_id'] ?></a></td>
										<td class="td_auth_mbnick"><?php echo $mb_nick ?></td>
										<td class="td_menu">
											<?php echo $row['au_menu'] ?>
											<?php echo $auth_menu[$row['au_menu']] ?>
										</td>
										<td class="td_auth"><?php echo $row['au_auth'] ?></td>
									</tr>
									<?php
										$count++;
									}
								
									if ($count == 0)
										echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
									?>
									</tbody>
								</table>
								</form>

							<?php
							//if (isset($stx))
							//    echo '<script>document.fsearch.sfl.value = "'.$sfl.'";</script>'."\n";

							if (strstr($sfl, 'mb_id'))
								$mb_id = $stx;
							else
								$mb_id = '';
							?>						
						<?php
						$pagelist = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page=');
						echo $pagelist;
						?>
						<form name="fauthlist2" id="fauthlist2" action="./auth_update.php" method="post" autocomplete="off">
							<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
							<input type="hidden" name="stx" value="<?php echo $stx ?>">
							<input type="hidden" name="sst" value="<?php echo $sst ?>">
							<input type="hidden" name="sod" value="<?php echo $sod ?>">
							<input type="hidden" name="page" value="<?php echo $page ?>">
							<input type="hidden" name="token" value="">

							<section id="add_admin">
								<h3 class="h2_frm text-light-blue">관리권한 추가</h3>

								<div class="local_desc01 local_desc auth_list">
									<p>
										다음 양식에서 회원에게 관리권한을 부여하실 수 있습니다.<br>
										권한 <strong>r</strong>은 읽기권한, <strong>w</strong>는 쓰기권한, <strong>d</strong>는 삭제권한입니다.
									</p>
								</div>

								<div class="tbl_frm01 tbl_wrap">
									<table class="table table-bordered table-striped dataTable auth_list">
										<colgroup>
											<col class="grid_4">
											<col>
										</colgroup>
										<tbody>
										<tr>
											<th scope="row"><label for="mb_id">회원아이디<strong class="sound_only">필수</strong></label></th>
											<td>
												<strong id="msg_mb_id" class="msg_sound_only"></strong>
												<input type="text" name="mb_id" value="<?php echo $mb_id ?>" id="mb_id" required class="required frm_input form-control">
												<!-- <button type="button" id="sch_member" class="btn btn-default">회원검색</button> -->
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="au_menu">접근가능메뉴<strong class="sound_only">필수</strong></label></th>
											<td>
												<select id="au_menu" name="au_menu" required class="required form-control">
													<option value=''>선택하세요</option>
													<?php
													foreach($auth_menu as $key=>$value)
													{
														if (!(substr($key, -3) == '000' || $key == '-' || !$key))
															echo '<option value="'.$key.'">'.$key.' '.$value.'</option>';
													}
													?>
												</select>
											</td>
										</tr>
										<tr>
											<th scope="row">권한지정</th>
											<td>
												<input type="checkbox" name="r" value="r" id="r" checked>
												<label for="r">r (읽기)</label>
												<input type="checkbox" name="w" value="w" id="w">
												<label for="w">w (쓰기)</label>
												<input type="checkbox" name="d" value="d" id="d">
												<label for="d">d (삭제)</label>
											</td>
										</tr>
										</tbody>
									</table>
								</div>

								<div class="btn_confirm01 btn_confirm">
									<input type="submit" value="추가" class="btn_submit btn btn-danger">
								</div>
							</section>
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
$(function(){
    $("#sch_member").click(function() {
        var opt = "left=50,top=50,width=520,height=600,scrollbars=1";
        var url = "./select_member.php?form=fauthlist2&field=mb_id";
        window.open(url, "win_member", opt);
    });
});

$(function (){
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
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : false,
	  'order': [  [2, 'asc']],
      'info'        : false,
	  'language' : lang_kor,
      'autoWidth'   : true,
	  'columnDefs': [{
            'bSortable': false,
            'aTargets': [1]
        }]
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


function fauthlist_submit(f){
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
include_once ('./admin.tail.php');
?>
