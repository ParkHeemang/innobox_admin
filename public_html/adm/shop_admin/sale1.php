<?php
$sub_menu = '500110';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '매출현황';
include_once ('../admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');


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
		매출현황
		<small>sale1</small>
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
					</div>			
					<!-- /.box-header -->
					<div class="box-body">
						<div class="local_sch03 local_sch">
							<div>
								<form name="frm_sale_today" action="./sale1today.php" method="get">
								<strong>일일 매출</strong>
								<input type="text" name="date" value="<?php echo date("Ymd", G5_SERVER_TIME); ?>" id="date" required class="required frm_input form-control" size="8" maxlength="8">
								<label for="date">일 하루</label>
								<input type="submit" value="확인" class="btn_submit btn btn-danger">
								</form>
							</div>

							<div>
								<form name="frm_sale_date" action="./sale1date.php" method="get">
								<strong>일간 매출</strong>
								<input type="text" name="fr_date" value="<?php echo date("Ym01", G5_SERVER_TIME); ?>" id="fr_date" required class="required frm_input form-control" size="8" maxlength="8">
								<label for="fr_date">일 ~</label>
								<input type="text" name="to_date" value="<?php echo date("Ymd", G5_SERVER_TIME); ?>" id="to_date" required class="required frm_input form-control" size="8" maxlength="8">
								<label for="to_date">일</label>
								<input type="submit" value="확인" class="btn_submit btn btn-danger">
								</form>
							</div>

							<div>
								<form name="frm_sale_month" action="./sale1month.php" method="get">
								<strong>월간 매출</strong>
								<input type="text" name="fr_month" value="<?php echo date("Y01", G5_SERVER_TIME); ?>" id="fr_month" required class="required frm_input form-control" size="6" maxlength="6">
								<label for="fr_month">월 ~</label>
								<input type="text" name="to_month" value="<?php echo date("Ym", G5_SERVER_TIME); ?>" id="to_month" required class="required frm_input form-control" size="6" maxlength="6">
								<label for="to_month">월</label>
								<input type="submit" value="확인" class="btn_submit btn btn-danger">
								</form>
							</div>

							<div class="sch_last">
								<form name="frm_sale_year" action="./sale1year.php" method="get">
								<strong>연간 매출</strong>
								<input type="text" name="fr_year" value="<?php echo date("Y", G5_SERVER_TIME)-1; ?>" id="fr_year" required class="required frm_input form-control" size="4" maxlength="4">
								<label for="fr_year">년 ~</label>
								<input type="text" name="to_year" value="<?php echo date("Y", G5_SERVER_TIME); ?>" id="to_year" required class="required frm_input form-control" size="4" maxlength="4">
								<label for="to_year">년</label>
								<input type="submit" value="확인" class="btn_submit btn btn-danger">
								</form>
							</div>
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
$(function() {
    $("#date, #fr_date, #to_date").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yymmdd",
        showButtonPanel: true,
        yearRange: "c-99:c+99",
        maxDate: "+0d"
    });
});
</script>

<?php
include_once ('../footer.php');
?>
