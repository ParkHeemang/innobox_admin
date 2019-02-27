<?php
	$sub_menu = "200105";          // 메뉴코드   
include_once('./_common.php'); // 공통함수
include_once('./admin.head.php');   //헤더파일
	auth_check($auth[$sub_menu], 'r');


	if( !isset($g5['content_table']) ){
    die('<meta charset="utf-8">/data/dbconfig.php 파일에 <strong>$g5[\'content_table\'] = G5_TABLE_PREFIX.\'content\';</strong> 를 추가해 주세요.');
}



$g5['title'] = '내용관리';

$sql_common = " from {$g5['content_table']} ";
// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = "select * $sql_common order by co_id limit $from_record, {$config['cf_page_rows']} ";
$result = sql_query($sql);


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
		내용관리
		<small>서브 타이틀</small>
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
						<nav class="navbar navbar-default navbtn">
							<div class = "fixed-button" align="right">
							<div align="right" style="width:45%">
							<a href = "./contentform.php"><button type="button" class="btn btn-default" style="color : white; background-color: #3c8dbc !important; margin-bottom:10px;"><i class="fa fa-fw fa-plus-square"></i>내용추가</button></a><br>
							</div>
							</div>
						</nav>
					</div>
					<!-- /.box-header -->
					<div class="box-body">					
						<table  id="example1" class="table table-bordered table-striped DataTable contentlist" width="100%">
							<!-- <caption><?php echo $g5['title']; ?> 목록</caption> -->
							<thead>
							<tr>
								<th id="division1" class="th_checkbox"><input type="checkbox" id="example-select-all" name="check_all" value="1"></th>	
								<th scope="col">ID</th>
								<th scope="col">제목</th>
								<th scope="col">관리</th>
							</tr>
							</thead>
							<tbody>
							<?php for ($i=0; $row=sql_fetch_array($result); $i++) {
								$bg = 'bg'.($i%2);
							?>
							<tr class="<?php echo $bg; ?>">
								<td  class="td_checkbox"><input type="checkbox" name="chk[]" value="<?php echo $i?>" id="chk_<?php echo $i?>"></td>
								<td class="td_id"><?php echo $row['co_id']; ?></td>
								<td class="td_left"><?php echo htmlspecialchars2($row['co_subject']); ?></td>
								<td class="td_mng td_mng_l">
									<a href="./contentform.php?w=u&amp;co_id=<?php echo $row['co_id']; ?>" class="btn btn-success"><span class="sound_only"><?php echo htmlspecialchars2($row['co_subject']); ?> </span>수정</a>
									<a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=<?php echo $row['co_id']; ?>" class="btn btn-primary"><span class="sound_only"><?php echo htmlspecialchars2($row['co_subject']); ?> </span> 보기</a>
									<a href="./contentformupdate.php?w=d&amp;co_id=<?php echo $row['co_id']; ?>" onclick="return delete_confirm(this);" class="btn btn-danger"><span class="sound_only"><?php echo htmlspecialchars2($row['co_subject']); ?> </span>삭제</a>
								</td>
							</tr>
							<?php
							}
							if ($i == 0) {
								echo '<tr><td colspan="3" class="empty_table">자료가 한건도 없습니다.</td></tr>';
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
    


<!-- <script src="//code.jquery.com/jquery-1.12.4.min.js"></script>  -->

<?php
    include_once('./admin.tail.php');
?>