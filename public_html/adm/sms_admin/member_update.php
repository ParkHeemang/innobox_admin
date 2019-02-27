<?php
$sub_menu = "900200";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$g5['title'] = "회원정보 업데이트";

include_once('../admin.head.php');


//CSS
add_stylesheet('<link rel="stylesheet" href="'.G5_ADMIN_URL. '/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 4);
//JS DataTables
add_javascript('<script src="'.G5_ADMIN_URL.'/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_ADMIN_URL.'/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);
?>

<form name="mb_update_form" id="mb_update_form" action="./member_update_run.php" >
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
		<h1 class="member_list_title">
		회원정보 업데이트
		<small>member_update</small>
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
								<input type="submit" value="실행" class="btn_submit btn btn-danger">					
							</div>
						</nav>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div id="sms5_mbup">							
							<div class="local_desc02 local_desc">
								<p>
									새로운 회원정보로 업데이트 합니다.<br>
									실행 후 '완료' 메세지가 나오기 전에 프로그램의 실행을 중지하지 마십시오.
								</p>
							</div>
							<div class="local_desc01 local_desc member_update">
								<p>
									마지막 업데이트 일시 : <span id="datetime"><?php echo $sms5['cf_datetime']?></span> <br>
								</p>
							</div>

							<div id="res_msg" class="local_desc01 local_desc">
							</div>

							
						</div>

						<script>
						(function($){
							$( "#mb_update_form" ).submit(function( e ) {
								e.preventDefault();
								$("#res_msg").html('업데이트 중입니다. 잠시만 기다려 주십시오...');
								var params = { mtype : 'json' };
								$.ajax({
									url: $(this).attr("action"),
									cache:false,
									timeout : 30000,
									dataType:"json",
									data:params,
									success: function(data) {
										if(data.error){
											alert( data.error );
											$("#res_msg").html("");
										} else {
											$("#datetime").html( data.datetime );
											$("#res_msg").html( data.res_msg );
										}
									},
									error: function (xhr, ajaxOptions, thrownError) {
										alert(xhr.status);
										alert(thrownError);
									}
								});
								return false;
							});
						})(jQuery);
						</script>
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
</form>

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

</script>


<?php
include_once('../admin.tail.php');
?>