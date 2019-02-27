<?php
$sub_menu = "900100";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$g5['title'] = "SMS 기본설정";

if (!$config['cf_icode_server_ip'])   $config['cf_icode_server_ip'] = '211.172.232.124';
if (!$config['cf_icode_server_port']) $config['cf_icode_server_port'] = '7295';

if ($config['cf_sms_use'] && $config['cf_icode_id'] && $config['cf_icode_pw'])
{
    $userinfo = get_icode_userinfo($config['cf_icode_id'], $config['cf_icode_pw']);
}

if (!$config['cf_icode_id'])
    $config['cf_icode_id'] = 'sir_';

if (!$sms5['cf_skin'])
    $sms5['cf_skin'] = 'basic';

include_once(G5_ADMIN_PATH.'/admin.head.php');


//CSS
add_stylesheet('<link rel="stylesheet" href="'.G5_ADMIN_URL. '/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 4);
//JS DataTables
add_javascript('<script src="'.G5_ADMIN_URL.'/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_ADMIN_URL.'/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);

?>
<?php if (!$config['cf_icode_pw']) { ?>
<div class="local_desc01 local_desc">
    <p>
        SMS 기능을 사용하시려면 먼저 아이코드에 서비스 신청을 하셔야 합니다.<br>
        <a href="http://icodekorea.com/res/join_company_fix_a.php?sellid=sir2" target="_blank">아이코드 서비스 신청하기</a>
    </p>
</div>
<?php } ?>

<?php
if ($config['cf_sms_use'] == 'icode') { // 아이코드 사용
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
		<h1 class="member_list_title">
		SMS 기본설정
		<small>SMSconfig1</small>
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
				<form name="fconfig" method="post" action="./config_update.php" enctype="multipart/form-data" >
				<div class="box">
					<div class="box-header">
						<nav class="navbar navbar-default navbtn" style="z-index: 1000000000000000000000000000000000">
							<div class="text-right">							
								<input type="submit" value="확인" class="btn_submit btn btn-primary" accesskey="s">					
							</div>
						</nav>
					</div>
					<!-- /.box-header -->
					<div class="box-body">						
						<input type="hidden" name="cf_icode_server_ip" value="<?php echo $config['cf_icode_server_ip']?>">
						<input type="hidden" name="cf_sms_use" value="<?php echo $config['cf_sms_use']?>">
						<div class="tbl_frm01 tbl_wrap">
							<table>
							<caption><?php echo $g5['title']; ?></caption>
							<colgroup>
								<col class="grid_4">
								<col>
							</colgroup>
							<tbody>
							<tr>
								<th scope="row"><label for="cf_icode_id">아이코드 회원아이디<strong class="sound_only"> 필수</strong></label></th>
								<td>
									<?php echo help("아이코드에서 사용하시는 회원아이디를 입력합니다."); ?>
									<input type="text" name="cf_icode_id" value="<?php echo $config['cf_icode_id']; ?>" id="cf_icode_id" required class="frm_input required">
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="cf_icode_pw">아이코드 비밀번호<strong class="sound_only"> 필수</strong></label></th>
								<td>
									<?php echo help("아이코드에서 사용하시는 비밀번호를 입력합니다."); ?>
									<input type="password" name="cf_icode_pw" value="<?php echo $config['cf_icode_pw']; ?>" id="cf_icode_pw" required class="frm_input required">
									<?php if (!$config['cf_icode_pw']) { ?>현재 비밀번호가 입력되어 있지 않습니다.<?php } ?>
								</td>
							</tr>
							<tr>
								<th scope="row">요금제</th>
								<td>
									<?php
										if ($userinfo['payment'] == 'A') {
										   echo '충전제';
											echo '<input type="hidden" name="cf_icode_server_port" value="7295">';
										} else if ($userinfo['payment'] == 'C') {
											echo '정액제';
											echo '<input type="hidden" name="cf_icode_server_port" value="7296">';
										} else {
											echo '가입해주세요.';
											echo '<input type="hidden" name="cf_icode_server_port" value="7295">';
										}
									?>
								</td>
							</tr>
							<?php if ($userinfo['payment'] == 'A') { ?>
							<tr>
								<th scope="row">충전 잔액</th>
								<td>
									<?php echo number_format($userinfo['coin'])?> 원
									<a href="http://www.icodekorea.com/smsbiz/credit_card_amt.php?icode_id=<?php echo $config['cf_icode_id']; ?>&amp;icode_passwd=<?php echo $config['cf_icode_pw']; ?>" target="_blank" class="btn_frmline">충전하기</a>
								</td>
							</tr>
							<?php } ?>
							<tr>
								<th scope="row"><label for="cf_phone">회신번호<strong class="sound_only"> 필수</strong></label></th>
								<td>
									<?php echo help("회신받을 휴대폰 번호를 입력하세요. 회신번호는 발신번호로 사전등록된 번호와 동일해야 합니다.<br>예) 010-123-4567"); ?>
									<input type="text" name="cf_phone" value="<?php echo $sms5['cf_phone']; ?>" id="cf_phone" required class="frm_input required" size="13">
								</td>
							</tr>
							</tbody>
							</table>
						</div>						

					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
				</form>
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->		
	</section>
	<!-- /.content -->	
</div>
<!-- /.content-wrapper -->

						<?php } else { ?>





<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
		<h1 class="member_list_title">
		SMS기본설정
		<small>SMSconfig2</small>
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

						<section>
							<h2 class="h2_frm">SMS 문자전송 서비스를 사용할 수 없습니다.</h2>
							<div class="local_desc01 local_desc">
								<p>
									SMS 를 사용하지 않고 있기 때문에, 문자 전송을 할 수 없습니다.<br>
									SMS 사용 설정은 <a href="../config_form.php#anc_cf_sms" class="btn_frmline">환경설정 &gt; 기본환경설정 &gt; SMS설정</a> 에서 SMS 사용을 아이코드로 변경해 주셔야 사용하실수 있습니다.
								</p>
							</div>
						</section>

						<?php } ?>
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
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>



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