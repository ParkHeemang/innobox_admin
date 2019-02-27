<?php
$sub_menu = '200300';
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['mail_table']} ";

// 테이블의 전체 레코드수만 얻음
$sql = " select COUNT(*) as cnt {$sql_common} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$page = 1;

$sql = " select * {$sql_common} order by ma_id desc ";
$result = sql_query($sql);

$g5['title'] = '회원메일발송';
include_once('./admin.head.php');

$colspan = 7;

//CSS
add_stylesheet('<link rel="stylesheet" href="'.G5_ADMIN_URL. '/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 4);
//JS DataTables
add_javascript('<script src="'.G5_ADMIN_URL.'/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_ADMIN_URL.'/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);
?>



<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1 class="member_list_title">
			회원메일발송
			<small>mail list</small>
		  </h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo G5_ADMIN_URL?>/"><i class="fa fa-dashboard"></i> Home</a></li>
				</ol>
	</section>

	<form name="fmaillist" id="fmaillist" action="./mail_delete.php" method="post">
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<nav class="navbar navbar-default navbtn">
							<div class = "fixed-button" align="right">
							  
							  <input type="submit" value="선택삭제" class="btn btn-danger">
							  <a href="./mail_form.php" id="mail_add" class="btn btn-primary">메일내용추가</a>
							</div>
						</nav>
						<div class="alert alert-info alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="font-size: 25px;">×</button>
							<p>
							테스트는 등록된 최고관리자의 이메일로 테스트 메일을 발송합니다. 현재 등록된 메일은 총 <?php echo $total_count ?>건입니다. <strong>(	주의) 수신자가 동의하지 않은 대량 메일 발송에는 적합하지 않습니다. 수십건 단위로 발송해 주십시오.</strong>
							</p>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body">						
						<input type="hidden" name="sst" value="<?php echo $sst ?>">
						<input type="hidden" name="sod" value="<?php echo $sod ?>">
						<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
						<input type="hidden" name="stx" value="<?php echo $stx ?>">
						<input type="hidden" name="page" value="<?php echo $page ?>">
						<input type="hidden" name="token" value="">						
						<table id="example1" class="table table-bordered table-striped dataTable mail_list">
							<!-- <caption><?php echo $g5['title']; ?> 목록</caption> -->
							<thead>
							<tr>
								<th scope="col"><input type="checkbox" name="chkall" value="1" id="example-select-all" title="현재 페이지 목록 전체선택" onclick="check_all(this.form)"></th>
								<th scope="col">번호</th>
								<th scope="col">제목</th>
								<th scope="col">작성일시</th>
								<th scope="col">테스트</th>
								<th scope="col">보내기</th>
								<th scope="col">미리보기</th>
							</tr>
							</thead>
							<tbody>
							<?php
							for ($i=0; $row=sql_fetch_array($result); $i++) {
								$s_vie = '<a href="./mail_preview.php?ma_id='.$row['ma_id'].'" target="_blank" class="btn btn-success">미리보기</a>';

								$num = number_format($total_count - ($page - 1) * $config['cf_page_rows'] - $i);

								$bg = 'bg'.($i%2);
							?>

							<tr class="<?php echo $bg; ?>">
								<td class="td_chk">
									<!-- <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['ma_subject']; ?> 메일</label> -->
									<input type="checkbox" id="chk_<?php echo $i ?>" name="chk[]" value="<?php echo $row['ma_id'] ?>">
								</td>
								<td class="td_num_c"><?php echo $num ?></td>
								<td class="td_left"><a href="./mail_form.php?w=u&amp;ma_id=<?php echo $row['ma_id'] ?>"><?php echo $row['ma_subject'] ?></a></td>
								<td class="td_datetime"><?php echo $row['ma_time'] ?></td>
								<td class="td_test"><a href="./mail_test.php?ma_id=<?php echo $row['ma_id'] ?>">테스트</a></td>
								<td class="td_send"><a href="./mail_select_form.php?ma_id=<?php echo $row['ma_id'] ?>">보내기</a></td>
								<td class="td_mng"><?php echo $s_vie ?></td>
							</tr>

							<?php
							}
							if (!$i)
								echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
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
$(function() {
    $('#fmaillist').submit(function() {
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
include_once ('./admin.tail.php');
?>