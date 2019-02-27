<?php
$sub_menu = '400660';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[$sub_menu], "w");

$sql = " select *
           from {$g5['g5_shop_item_qa_table']} a
           left join {$g5['member_table']} b on (a.mb_id = b.mb_id)
          where iq_id = '$iq_id' ";
$iq = sql_fetch($sql);
if (!$iq['iq_id']) alert('등록된 자료가 없습니다.');

$name = get_sideview($iq['mb_id'], get_text($iq['iq_name']), $iq['mb_email'], $iq['mb_homepage']);

$g5['title'] = '상품문의';
include_once ('../admin.head.php');

$qstr .= ($qstr ? '&amp;' : '').'sca='.$sca;
?>



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
	  <h1 class="member_list_title">
		관리권환설정
		<small>auth list</small>
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
								<a href="./itemqalist.php?<?php echo $qstr; ?>" class="btn btn_02 btn-default">목록</a>
								<input type="submit" accesskey='s' value="확인" class="btn_submit btn btn-primary">					
							</div>
						</nav>
					</div>
					<!-- /.box-header -->
					<div class="box-body">						
						<div class="alert alert-info">
							상품에 대한 문의에 답변하실 수 있습니다. 상품 문의 내용의 수정도 가능합니다.
						</div>
						<div class="tbl_frm01 tbl_wrap">
							<table>
							<caption><?php echo $g5['title']; ?> 수정</caption>
							<colgroup>
								<col class="grid_4">
								<col>
							</colgroup>
							<tbody>
							<tr>
								<th scope="row">이름</th>
								<td><?php echo $name; ?></td>
							</tr>
							<?php if($iq['iq_email']) { ?>
							<tr>
								<th scope="row">이메일</th>
								<td><?php echo get_text($iq['iq_email']); ?></td>
							</tr>
							<?php } ?>
							<?php if($iq['iq_hp']) { ?>
							<tr>
								<th scope="row">휴대폰</th>
								<td><?php echo hyphen_hp_number($iq['iq_hp']); ?></td>
							</tr>
							<?php } ?>
							<tr>
								<th scope="row"><label for="iq_subject">제목</label></th>
								<td><input type="text" name="iq_subject" value="<?php echo conv_subject($iq['iq_subject'],120); ?>" id="iq_subject" required class="frm_input required" size="95"></td>
							</tr>
							<tr>
								<th scope="row"><label for="iq_question">질문</label></th>
								<td><?php echo editor_html('iq_question', get_text(html_purifier($iq['iq_question']), 0)); ?></td>
							</tr>
							<tr>
								<th scope="row"><label for="iq_answer">답변</label></th>
								<td><?php echo editor_html('iq_answer', get_text(html_purifier($iq['iq_answer']), 0)); ?></td>
								<!-- <td><textarea name="iq_answer" id="iq_answer" rows="7"><?php echo get_text($iq['iq_answer']); ?></textarea></td> -->
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
</div>
<!-- /.content-wrapper -->









<?php
include_once ('../footer.php');
?>
