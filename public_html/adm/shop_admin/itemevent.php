<?php
$sub_menu = '500300';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '이벤트관리';
include_once ('../admin.head.php');

$sql_common = " from {$g5['g5_shop_event_table']} ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$sql = "select * $sql_common order by ev_id desc ";
$result = sql_query($sql);



//CSS
add_stylesheet('<link rel="stylesheet" href="'.G5_PHP_URL. 'bower_components/bootstrap/dist/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 5);
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL2.'/bootstrap.custom.css?ver='.G5_CSS_VER.'"></script>',8);

//JS DataTables
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);

?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
		<h1 class="member_list_title">
		이벤트관리
		<small>itemEvent</small>
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
						<nav class="navbar navbar-default navbtn" style="z-index: 1000000000000000000000000000000000">
							<div class="text-right">							
								<a href="./itemeventform.php" class="btn btn-danger btn_01">이벤트 추가</a>						
							</div>
						</nav>
					</div>		
					<!-- /.box-header -->
					<div class="box-body">
						<div class="tbl_head01 tbl_wrap">
							<table id="example1" class="table table-bordered table-striped dataTable member_list">
								<caption><?php echo $g5['title']; ?> 목록</caption>
								<thead>
								<tr>
									<th scope="col">이벤트번호</th>
									<th scope="col">제목</th>
									<th scope="col">연결상품</th>
									<th scope="col">사용</th>
									<th scope="col">관리</th>
								</tr>
								</thead>
								<tbody>
								<?php
								for ($i=0; $row=sql_fetch_array($result); $i++) {

									$href = "";
									$sql = " select count(ev_id) as cnt from {$g5['g5_shop_event_item_table']} where ev_id = '{$row['ev_id']}' ";
									$ev = sql_fetch($sql);
									if ($ev['cnt']) {
										$href = '<a href="javascript:;" onclick="itemeventwin('.$row['ev_id'].');">';
										$href_close = '</a>';
									}
									if ($row['ev_subject_strong']) $subject = '<strong>'.$row['ev_subject'].'</strong>';
									else $subject = $row['ev_subject'];
								?>

								<tr>
									<td class="td_num"><?php echo $row['ev_id']; ?></td>
									<td class="td_left"><?php echo $subject; ?></td>
									<td class="td_num"><?php echo $href; ?><?php echo $ev['cnt']; ?><?php echo $href_close; ?></td>
									<td class="td_boolean"><?php echo $row['ev_use'] ? '<span class="txt_true">예</span>' : '<span class="txt_false">아니오</span>'; ?></td>
									<td class="td_mng td_mng_l">
										<a href="./itemeventform.php?w=u&amp;ev_id=<?php echo $row['ev_id']; ?>" class="btn btn_03">수정</a>
										<a href="<?php echo G5_SHOP_URL; ?>/event.php?ev_id=<?php echo $row['ev_id']; ?>" class="btn btn_02">보기</a>
										<a href="./itemeventformupdate.php?w=d&amp;ev_id=<?php echo $row['ev_id']; ?>" onclick="return delete_confirm(this);" class="btn btn_02">삭제</a>
									</td>
								</tr>

								<?php
								}

								if ($i == 0) {
									echo '<tr><td colspan="5" class="empty_table">자료가 없습니다.</td></tr>';
								}
								?>
								</tbody>
							</table>
						</div>
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
function itemeventwin(ev_id)
{
    window.open("./itemeventwin.php?ev_id="+ev_id, "itemeventwin", "left=10,top=10,width=500,height=600,scrollbars=1");
}
</script>



<?php
include_once ('../footer.php');
?>