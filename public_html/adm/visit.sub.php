<?php
if (!defined('_GNUBOARD_')) exit;

include_once(G5_LIB_PATH.'/visit.lib.php');
include_once('./admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

if (empty($fr_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = G5_TIME_YMD;
if (empty($to_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = G5_TIME_YMD;

$qstr = "fr_date=".$fr_date."&amp;to_date=".$to_date;
$query_string = $qstr ? '?'.$qstr : '';

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
		접속자집계
		<small>visit list</small>
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
					<!-- /.box-header -->
					<div class="box-body">
						<nav class="navbar navbar-default navbtn">					
						<form name="fvisit" id="fvisit" class="local_sch" method="get">
							<div class="sch_last">
								<strong>기간별검색</strong>
								<input type="text" name="fr_date" value="<?php echo $fr_date ?>" id="fr_date" class="frm_input form-control" size="11" maxlength="10">
								<!-- <label for="fr_date" class="sound_only">시작일</label> -->
								~
								<input type="text" name="to_date" value="<?php echo $to_date ?>" id="to_date" class="frm_input form-control" size="11" maxlength="10">
								<!-- <label for="to_date" class="sound_only">종료일</label> -->
								<input type="submit" value="검색" class="btn_submit btn btn-primary">
							</div>
						</form>
						</nav>

							<!--<ul class="anchor">
							<li><a href="./visit_list.php<?php echo $query_string ?>">접속자</a></li>
							<li><a href="./visit_domain.php<?php echo $query_string ?>">도메인</a></li>
							<li><a href="./visit_browser.php<?php echo $query_string ?>">브라우저</a></li>
							<li><a href="./visit_os.php<?php echo $query_string ?>">운영체제</a></li>
							<?php if(version_compare(phpversion(), '5.3.0', '>=') && defined('G5_BROWSCAP_USE') && G5_BROWSCAP_USE) { ?>
							<li><a href="./visit_device.php<?php echo $query_string ?>">접속기기</a></li>
							<?php } ?>
							<li><a href="./visit_hour.php<?php echo $query_string ?>">시간</a></li>
							<li><a href="./visit_week.php<?php echo $query_string ?>">요일</a></li>
							<li><a href="./visit_date.php<?php echo $query_string ?>">일</a></li>
							<li><a href="./visit_month.php<?php echo $query_string ?>">월</a></li>
							<li><a href="./visit_year.php<?php echo $query_string ?>">년</a></li>
						</ul> -->

						<script>

						$(function(){
							$("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
						});

						function fvisit_submit(act)
						{
							var f = document.fvisit;
							f.action = act;
							f.submit();
						}

						
						jQuery.browser = {};
						(function () {
							jQuery.browser.msie = false;
							jQuery.browser.version = 0;
							if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
								jQuery.browser.msie = true;
								jQuery.browser.version = RegExp.$1;
							}
						})();
						</script>