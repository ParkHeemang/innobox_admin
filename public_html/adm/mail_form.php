<?php
$sub_menu = "200300";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[$sub_menu], 'r');

$html_title = '회원메일';

if ($w == 'u') {
    $html_title .= '수정';
    $readonly = ' readonly';

    $sql = " select * from {$g5['mail_table']} where ma_id = '{$ma_id}' ";
    $ma = sql_fetch($sql);
    if (!$ma['ma_id'])
        alert('등록된 자료가 없습니다.');
} else {
    $html_title .= '입력';
}

$g5['title'] = $html_title;
include_once('./admin.head.php');

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
		회원메일입력
		<small>mail form</small>
	  </h1>
	  <ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">회원관리</a></li>
		<li class="active">회원정보리스트</li>
	  </ol>
	</section>
	<form name="fmailform" id="fmailform" action="./mail_update.php" onsubmit="return fmailform_check(this);" method="post">
	<!-- Main content -->	
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<nav class="navbar navbar-default navbtn">
							<div class="text-right">							
								<input type="submit" class="btn_submit btn btn-primary" accesskey="s" value="확인">					
							</div>
						</nav>
					</div>
					<!-- /.box-header -->
					<div class="box-body">						
						<div class="local_desc"><p>메일 내용에 {이름} , {닉네임} , {회원아이디} , {이메일} 처럼 내용에 삽입하면 해당 내용에 맞게 변환하여 메일을 발송합니다.</p></div>						
						<input type="hidden" name="w" value="<?php echo $w ?>" id="w">
						<input type="hidden" name="ma_id" value="<?php echo $ma['ma_id'] ?>" id="ma_id">
						<input type="hidden" name="token" value="" id="token">

						<div class="tbl_frm01 tbl_wrap">
							<table id="example2" class="table table-bordered table-hover dataTable member-form" role="grid" aria-describedby="example2_info">	
							<!-- <caption><?php echo $g5['title']; ?></caption> -->
							<colgroup>
								<col class="grid_4">
								<col>
							</colgroup>
							<tbody>
							<tr>
								<th scope="row"><label for="ma_subject">메일 제목<strong class="sound_only">필수</strong></label></th>
								<td><input type="text" name="ma_subject" value="<?php echo $ma['ma_subject'] ?>" id="ma_subject" required class="required frm_input form-control" size="100"></td>
							</tr>
							<tr>
								<th scope="row"><label for="ma_content">메일 내용<strong class="sound_only">필수</strong></label></th>
								<td><?php echo editor_html("ma_content", get_text($ma['ma_content'], 0)); ?></td>
							</tr>
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
	</form>
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

function fmailform_check(f)
{
    errmsg = "";
    errfld = "";

    check_field(f.ma_subject, "제목을 입력하세요.");
    //check_field(f.ma_content, "내용을 입력하세요.");

    if (errmsg != "") {
        alert(errmsg);
        errfld.focus();
        return false;
    }

    <?php echo get_editor_js("ma_content"); ?>
    <?php echo chk_editor_js("ma_content"); ?>

    return true;
}

document.fmailform.ma_subject.focus();
</script>
<?php
include_once('./admin.tail.php');
?>