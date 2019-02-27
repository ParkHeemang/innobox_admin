<?php
$sub_menu = "200820";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = '접속자로그삭제';
include_once('./admin.head.php');

// 최소년도 구함
$sql = " select min(vi_date) as min_date from {$g5['visit_table']} ";
$row = sql_fetch($sql);

$min_year = (int)substr($row['min_date'], 0, 4);
$now_year = (int)substr(G5_TIME_YMD, 0, 4);

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
		접속자로그삭제
		<small>visit delete</small>
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
						<div class="local_ov01 local_ov">
							접속자 로그를 삭제할 년도와 방법을 선택해주십시오.
						</div>
						<form name="fvisitdelete" class="visit_del" method="post" action="./visit_delete_update.php" onsubmit="return form_submit(this);">
							<div>
								<label for="year" class="sound_only">년도선택</label>
								<select name="year" id="year" class="form-control">
									<option value="">년도선택</option>
									<?php
									for($year=$min_year; $year<=$now_year; $year++) {
									?>
									<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
									<?php
									}
									?>
								</select> 년
								<label for="month" class="sound_only">월선택</label>
								<select name="month" id="month" class="form-control">
									<option value="">월선택</option>
									<?php
									for($i=1; $i<=12; $i++) {
									?>
									<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
									<?php
									}
									?>
								</select> 월
								<label for="method" class="sound_only">삭제방법선택</label>
								<select name="method" id="method" class="form-control">
									<option value="before">선택년월 이전 자료삭제</option>
									<option value="specific">선택년월의 자료삭제</option>
								</select>
							</div>
							<div class="visit_del_bt">
								<label for="pass">관리자 비밀번호<strong class="sound_only"> 필수</strong></label>
								<input type="password" name="pass" id="pass" class="frm_input required form-control">
								<input type="submit" value="확인" class="btn btn-danger">
							</div>
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
function form_submit(f)
{
    var year = $("#year").val();
    var month = $("#month").val();
    var method = $("#method").val();
    var pass = $("#pass").val();

    if(!year) {
        alert("년도를 선택해 주십시오.");
        return false;
    }

    if(!month) {
        alert("월을 선택해 주십시오.");
        return false;
    }

    if(!pass) {
        alert("관리자 비밀번호를 입력해 주십시오.");
        return false;
    }

    var msg = year+"년 "+month+"월";
    if(method == "before")
        msg += " 이전";
    else
        msg += "의";
    msg += " 자료를 삭제하시겠습니까?";

    return confirm(msg);
}
</script>

<?php
include_once('./admin.tail.php');
?>
