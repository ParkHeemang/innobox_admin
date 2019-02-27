<?php
$sub_menu = '500500';
include_once('./_common.php');
include_once ('../admin.head.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '배너관리';


$sql_common = " from {$g5['g5_shop_banner_table']} ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

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
		배너관리
		<small>banner list</small>
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
							<a href="./bannerform.php" class="btn_01 btn btn-danger">배너추가</a>		
						</div>
					</nav>
				</div>
				<!-- /.box-header -->
				<div class="box-body">					
					<div class="tbl_head01 tbl_wrap">
						<table id="example1" class="table table-bordered table-striped dataTable member_list">
							<!-- <caption><?php echo $g5['title']; ?> 목록</caption> -->
							<thead>
							<tr>
								<th scope="col" rowspan="2" id="th_id">ID</th>
								<th scope="col" id="th_dvc">접속기기</th>
								<th scope="col" id="th_loc">위치</th>
								<th scope="col" id="th_st">시작일시</th>
								<th scope="col" id="th_end">종료일시</th>
								<th scope="col" id="th_odr">출력순서</th>
								<th scope="col" id="th_hit">조회</th>
								<th scope="col" id="th_mng">관리</th>
							</tr>
							<tr>
								<th scope="col" colspan="7" id="th_img">이미지</th>
							</tr>
							</thead>
							<tbody>
							<?php
							$sql = " select * from {$g5['g5_shop_banner_table']}
								  order by bn_order, bn_id desc
								  limit $from_record, $rows  ";
							$result = sql_query($sql);
							for ($i=0; $row=sql_fetch_array($result); $i++) {
								// 테두리 있는지
								$bn_border  = $row['bn_border'];
								// 새창 띄우기인지
								$bn_new_win = ($row['bn_new_win']) ? 'target="_blank"' : '';

								$bimg = G5_DATA_PATH.'/banner/'.$row['bn_id'];
								if(file_exists($bimg)) {
									$size = @getimagesize($bimg);
									if($size[0] && $size[0] > 800)
										$width = 800;
									else
										$width = $size[0];

									$bn_img = "";
								   
									$bn_img .= '<img src="'.G5_DATA_URL.'/banner/'.$row['bn_id'].'" width="'.$width.'" alt="'.$row['bn_alt'].'">';
								}

								switch($row['bn_device']) {
									case 'pc':
										$bn_device = 'PC';
										break;
									case 'mobile':
										$bn_device = '모바일';
										break;
									default:
										$bn_device = 'PC와 모바일';
										break;
								}

								$bn_begin_time = substr($row['bn_begin_time'], 2, 14);
								$bn_end_time   = substr($row['bn_end_time'], 2, 14);

								$bg = 'bg'.($i%2);
							?>

							<tr class="<?php echo $bg; ?>">
								<td headers="th_id" rowspan="2" class="td_num"><?php echo $row['bn_id']; ?></td>
								<td headers="th_dvc"><?php echo $bn_device; ?></td>
								<td headers="th_loc"><?php echo $row['bn_position']; ?></td>
								<td headers="th_st" class="td_datetime"><?php echo $bn_begin_time; ?></td>
								<td headers="th_end" class="td_datetime"><?php echo $bn_end_time; ?></td>
								<td headers="th_odr" class="td_num"><?php echo $row['bn_order']; ?></td>
								<td headers="th_hit" class="td_num"><?php echo $row['bn_hit']; ?></td>
								<td headers="th_mng" class="td_mng td_mns_m">
									<a href="./bannerform.php?w=u&amp;bn_id=<?php echo $row['bn_id']; ?>" class="btn btn_03">수정</a>
									<a href="./bannerformupdate.php?w=d&amp;bn_id=<?php echo $row['bn_id']; ?>" onclick="return delete_confirm(this);" class="btn btn_02">삭제</a>
								</td>
							</tr>
							<tr class="<?php echo $bg; ?>">
								<td headers="th_img" colspan="7" class="td_img_view sbn_img">
									<div class="sbn_image"><?php echo $bn_img; ?></div>
									<button type="button" class="sbn_img_view btn_frmline">이미지확인</button>
								</td>
							</tr>

							<?php
							}
							if ($i == 0) {
							echo '<tr><td colspan="8" class="empty_table">자료가 없습니다.</td></tr>';
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


<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

<script>
$(function() {
    $(".sbn_img_view").on("click", function() {
        $(this).closest(".td_img_view").find(".sbn_image").slideToggle();
    });
});
</script>

<?php
include_once ('../footer.php');
?>
