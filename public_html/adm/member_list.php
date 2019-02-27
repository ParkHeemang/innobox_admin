<?php
$sub_menu = "200100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['member_table']} ";

$sql_search = " where (1) ";

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_point' :
            $sql_search .= " ({$sfl} >= '{$stx}') ";
            break;
        case 'mb_level' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        case 'mb_tel' :
        case 'mb_hp' :
            $sql_search .= " ({$sfl} like '%{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if ($is_admin != 'super')
    $sql_search .= " and mb_level <= '{$member['mb_level']}' ";

if (!$sst) {
    $sst = "mb_datetime";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

// 탈퇴회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$leave_count = $row['cnt'];

// 차단회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$intercept_count = $row['cnt'];

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '회원관리';
include_once('./admin.head.php');

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$colspan = 16;

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
		회원관리
			<small>member list</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">회원관리</a></li>
		<li class="active">회원정보리스트</li>			
	</section>
	<!-- Main content -->
	<form name="fmemberlist" id="fmemberlist" method="post" action="./member_list_update.php" onsubmit="return fmemberlist_submit(this);">
	<!-- <form name="fmemberlist" id="fmemberlist" action="./member_list_update.php" onsubmit="return fmemberlist_submit(this);" method="post"> -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<nav class="navbar navbar-default navbtn mb-nav">
							<div class="text-right">									
								<button type="button" class="btn btn-info btn-list"><i class="fa fa-fw fa-list"></i>목록</button>
								<input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn btn-success">
								<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn-danger">
								<?php if ($is_admin == 'super') { ?>
								<a href="./member_form.php" id="member_add" class="btn btn-primary">회원추가</a>
								<?php } ?>		
							</div>
						</nav>
						<div class="alert alert-dismissible alert-default">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="font-size: 25px;">×</button>
							회원자료 삭제 시 다른 회원이 기존 회원아이디를 사용하지 못하도록 회원아이디, 이름, 닉네임은 삭제하지 않고 영구 보관합니다.
						</div>	
					</div>
					<!-- /.box-header -->											
					<div class="box-body">
						<table id="example1" class="table table-bordered table-striped dataTable member_list" width="100%">
							<thead>
							<tr>
							<th id="division1"><input type="checkbox" id="example-select-all" name="check_all" value="1"></th>		
							<th>아이디</th>
							<th>이&nbsp&nbsp름</th>
							<th>닉네임</th>
							<th>휴대폰</th>
							<th style="width : 124px">E-mail</th>
							<th>&nbsp&nbsp권&nbsp&nbsp한&nbsp&nbsp</th>						 
							<th>최종접속</th>
							<th>가입일</th>						 
							<th>관&nbsp&nbsp리</th>
							</tr>
							</thead>
							<tbody>
							<?php
							for ($i=0; $row=sql_fetch_array($result); $i++) {
							?>	
							<tr>					  
							<td>
							<input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>" id="mb_id_<?php echo $i ?>">
							<input type="checkbox" name="chk[]" value="<?php echo $i?>" id="chk_<?php echo $i?>">
							</td>
							<td <?php if ($row['mb_intercept_date']!=''){ echo 'style="text-decoration-line : line-through; color: red"';}else if($row['mb_leave_date']!=''){echo 'style="text-decoration-line : line-through;"';}?>><?php echo $row['mb_id']?></td>
							<td><?php echo $row['mb_name']?></td>
							<td><?php echo $row['mb_nick']?></td>						  
							<td><?php echo $row['mb_hp']?></td>
							<td><?php echo $row['mb_email']?></td>
							<td>								  
								<select class="form-control">
									<option <?php if($row['mb_level'] == "1") echo " selected"?> >1</option>
									<option <?php if($row['mb_level'] == "2") echo " selected"?>>2</option>
									<option <?php if($row['mb_level'] == "3") echo " selected"?>>3</option>
									<option <?php if($row['mb_level'] == "4") echo " selected"?>>4</option>
									<option <?php if($row['mb_level'] == "5") echo " selected"?>>5</option>
									<option <?php if($row['mb_level'] == "6") echo " selected"?>>6</option>
									<option <?php if($row['mb_level'] == "7") echo " selected"?>>7</option>
									<option <?php if($row['mb_level'] == "8") echo " selected"?>>8</option>
									<option <?php if($row['mb_level'] == "9") echo " selected"?>>9</option>
									<option <?php if($row['mb_level'] == "10") echo " selected"?>>10</option>
								</select>									  
							</td>
							<td><?php echo $row['mb_today_login']?></td>
							<td><?php echo $row['mb_datetime']?></td>					
							<td>
								<button type="button" class="btn btn-danger" onclick="location.href='./member_form.php?<?php echo $qstr?>&amp;w=u&amp;mb_id=<?php echo $row['mb_id']?>'">수정</button>
								<button type="button" class="btn btn-info" onclick="location.href='boardgroupmember_form.php'">그룹</button>
							</td>
							</tr>
								<?php
								}
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
  'ordering'    : true,
  'order': [  [1, 'desc']],
  'info'        : true,
  'language' : lang_kor,
  'autoWidth'   : true,
  'columnDefs': [{
		'bSortable': false,
		'aTargets': [0,6,9]
	}]
//$('#division1').removeClass('sorting sorting_asc sorting_desc');  
 // [{ 'targets': [0,5,10,11], 'orderable': false }, 	  ]

});




// Handle click on "Select all" control                                                                                        example-select-all 체크박스를 체크하였을때
$('#example-select-all').on('click', function(){
   // Get all rows with search applied			                                                                                모든 열을 rows에 담고
   var rows = table.rows({ 'search': 'applied' }).nodes();
   // Check/uncheck checkboxes for all rows in the table			                                            prop : 속성값 추가하기
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

/* 삭제(체크박스된 것 전부) */
function fmemberlist_submit(f){

    if (!is_checked("chk[]")) {                                                                   //name 으로 구별
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }
    if(document.pressed == "선택삭제") {                                                
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {                    //선택에 따라 true false 반영         
			return false;																				//아니오 선택했을때
        }		
    }	
	
	/*var token = get_ajax_token();								//여기서 못넘어가
	
	if(!token) {
		alert("토큰 정보가 올바르지 않습니다.");
		return false;
	}*/
	

	/*var $f = $(f);

	if(typeof f.token === "undefined")
		$f.prepend('<input type="hidden" name="token" value="">');

	$f.find("input[name=token]").val(token);*/
	return true;
}
</script>



    
<?php
    include_once('./admin.tail.php');
?>

