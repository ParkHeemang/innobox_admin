<?php
$sub_menu = "200900";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$po_id = isset($po_id) ? (int) $po_id : 0;

$html_title = '투표';
if ($w == '')
    $html_title .= ' 생성';
else if ($w == 'u')  {
    $html_title .= ' 수정';
    $sql = " select * from {$g5['poll_table']} where po_id = '{$po_id}' ";
    $po = sql_fetch($sql);
} else
    alert('w 값이 제대로 넘어오지 않았습니다.');

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
		투표생성
		<small>poll form</small>
		</h1>
		<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">회원관리</a></li>
		<li class="active">회원정보리스트</li>
		</ol>
	</section>
	<!-- Main content -->
	<form name="fpoll" id="fpoll" action="./poll_form_update.php" method="post" enctype="multipart/form-data">
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<nav class="navbar navbar-default navbtn">
							<div class="text-right">							
								<a href="./poll_list.php?<?php echo $qstr ?>" class="btn_02 btn btn btn-default">목록</a>
							<input type="submit" value="확인" class="btn_submit btn btn btn-danger" accesskey="s">			
							</div>
						</nav>
					</div>
					<!-- /.box-header -->
					<div class="box-body">										
						<input type="hidden" name="po_id" value="<?php echo $po_id ?>">
						<input type="hidden" name="w" value="<?php echo $w ?>">
						<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
						<input type="hidden" name="stx" value="<?php echo $stx ?>">
						<input type="hidden" name="sst" value="<?php echo $sst ?>">
						<input type="hidden" name="sod" value="<?php echo $sod ?>">
						<input type="hidden" name="page" value="<?php echo $page ?>">
						<input type="hidden" name="token" value="">

						<div class="tbl_frm01 tbl_wrap">
							<table  id="example2" class="table table-bordered table-hover dataTable poll-form" role="grid" aria-describedby="example2_info">
								<!-- 	<caption><?php echo $g5['title']; ?></caption> -->
								<tbody>
								<tr>
									<th scope="row"><label for="po_subject">투표 제목<strong class="sound_only">필수</strong></label></th>
									<td><input type="text" name="po_subject" value="<?php echo $po['po_subject'] ?>" id="po_subject" required class="required frm_input form-control" size="80" maxlength="125"></td>
								</tr>

								<?php
								for ($i=1; $i<=9; $i++) {
									$required = '';
									if ($i==1 || $i==2) {
										$required = 'required';
										$sound_only = '<strong class="sound_only">필수</strong>';
									}

									$po_poll = get_text($po['po_poll'.$i]);
								?>

								<tr>
									<th scope="row"><label for="po_poll<?php echo $i ?>">항목 <?php echo $i ?><?php echo $sound_only ?></label></th>
									<td>
										<input type="text" name="po_poll<?php echo $i ?>" value="<?php echo $po_poll ?>" id="po_poll<?php echo $i ?>" <?php echo $required ?> class="frm_input form-control <?php echo $required ?>" maxlength="125">
										<label for="po_cnt<?php echo $i ?>">항목 <?php echo $i ?> 투표수</label>
										<input type="text" name="po_cnt<?php echo $i ?>" value="<?php echo $po['po_cnt'.$i] ?>" id="po_cnt<?php echo $i ?>" class="frm_input form-control" size="3">
								   </td>
								</tr>

								<?php } ?>

								<tr>
									<th scope="row"><label for="po_etc">기타의견</label></th>
									<td>
										<?php echo help('기타 의견을 남길 수 있도록 하려면, 간단한 질문을 입력하세요.') ?>
										<input type="text" name="po_etc" value="<?php echo get_text($po['po_etc']) ?>" id="po_etc" class="frm_input form-control" size="80" maxlength="125">
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="po_level">투표가능 회원레벨</label></th>
									<td>
										<?php echo help("레벨을 1로 설정하면 손님도 투표할 수 있습니다.") ?>
										<?php echo get_member_level_select('po_level', 1, 10, $po['po_level'],'class=form-control') ?> 이상 투표할 수 있음
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="po_point">포인트</label></th>
									<td>
										<?php echo help('투표에 참여한 회원에게 포인트를 부여합니다.') ?>
										<input type="text" name="po_point" value="<?php echo $po['po_point'] ?>" id="po_point" class="frm_input form-control"> 점
									</td>
								</tr>

								<?php if ($w == 'u') { ?>
								<tr>
									<th scope="row">투표등록일</th>
									<td><?php echo $po['po_date']; ?></td>
								</tr>
								<tr>
									<th scope="row"><label for="po_ips">투표참가 IP</label></th>
									<td><textarea name="po_ips" id="po_ips" readonly rows="10"><?php echo preg_replace("/\n/", " / ", $po['po_ips']) ?></textarea></td>
								</tr>
								<tr>
									<th scope="row"><label for="mb_ids">투표참가 회원</label></th>
									<td><textarea name="mb_ids" id="mb_ids" readonly rows="10"><?php echo preg_replace("/\n/", " / ", $po['mb_ids']) ?></textarea></td>
								</tr>
								<?php } ?>
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


</script>
 
	
<?php
include_once ('./admin.tail.php');
?>





