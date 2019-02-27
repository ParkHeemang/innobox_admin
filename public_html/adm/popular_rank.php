<?php
$sub_menu = "300400";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

if (empty($fr_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = G5_TIME_YMD;
if (empty($to_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = G5_TIME_YMD;

$qstr = "fr_date={$fr_date}&amp;to_date={$to_date}";

$sql_common = " from {$g5['popular_table']} a ";
$sql_search = " where trim(pp_word) <> '' and pp_date between '{$fr_date}' and '{$to_date}' ";
$sql_group = " group by pp_word ";
$sql_order = " order by cnt desc ";

$sql = " select pp_word {$sql_common} {$sql_search} {$sql_group} ";
$result = sql_query($sql);
$total_count = sql_num_rows($result);

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select pp_word, count(*) as cnt {$sql_common} {$sql_search} {$sql_group} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '인기검색어순위';
include_once('./admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$colspan = 3;


//CSS
add_stylesheet('<link rel="stylesheet" href="'.G5_ADMIN_URL. '/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 4);
//JS DataTables
add_javascript('<script src="'.G5_ADMIN_URL.'/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_ADMIN_URL.'/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);


?>

<script>
$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
		<h1 class="member_list_title">
		인기검색어순위
		<small>popular rank</small>
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
					<div class="box-body">					
						<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
							<div class="sch_last">
								<strong>기간별검색</strong>
								<input type="text" name="fr_date" value="<?php echo $fr_date ?>" id="fr_date" class="frm_input form-control" size="11" maxlength="10">
								<!-- <label for="fr_date" class="sound_only">시작일</label> -->
								~
								<input type="text" name="to_date" value="<?php echo $to_date ?>" id="to_date" class="frm_input form-control" size="11" maxlength="10">
								<!-- <label for="to_date" class="sound_only">종료일</label> -->
								<input type="submit" class="btn btn-default btn_submit" value="검색">
							</div>
							</form>

							<form name="fpopularrank" id="fpopularrank" method="post">
							<input type="hidden" name="sst" value="<?php echo $sst ?>">
							<input type="hidden" name="sod" value="<?php echo $sod ?>">
							<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
							<input type="hidden" name="stx" value="<?php echo $stx ?>">
							<input type="hidden" name="page" value="<?php echo $page ?>">
							<input type="hidden" name="token" value="<?php echo $token ?>">	
							
							<table id="example1" class="table table-bordered table-striped dataTable popular_rank">
							<!-- <caption><?php echo $g5['title']; ?> 목록</caption> -->
							<thead>
							<tr>
								<th scope="col">순위</th>
								<th scope="col">검색어</th>
								<th scope="col">검색회수</th>
							</tr>
							</thead>
							<tbody>
							<?php
							for ($i=0; $row=sql_fetch_array($result); $i++) {

								$word = get_text($row['pp_word']);
								$rank = ($i + 1 + ($rows * ($page - 1)));

							?>

							<tr>
								<td class="td_num"><?php echo $rank ?></td>
								<td class="td_left"><?php echo $word ?></td>
								<td class="td_num"><?php echo $row['cnt'] ?></td>
							</tr>

							<?php
							}

							if ($i == 0)
								echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
							?>
							</tbody>
							</table>							
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
    
<?php
echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page=");
?>


<?php
    include_once('./admin.tail.php');
?>