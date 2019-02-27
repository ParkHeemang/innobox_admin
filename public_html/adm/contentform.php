<?php
$sub_menu = '300600';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[$sub_menu], "w");

// 상단, 하단 파일경로 필드 추가
if(!sql_query(" select co_include_head from {$g5['content_table']} limit 1 ", false)) {
    $sql = " ALTER TABLE `{$g5['content_table']}`  ADD `co_include_head` VARCHAR( 255 ) NOT NULL ,
                                                    ADD `co_include_tail` VARCHAR( 255 ) NOT NULL ";
    sql_query($sql, false);
}

// html purifier 사용여부 필드
if(!sql_query(" select co_tag_filter_use from {$g5['content_table']} limit 1 ", false)) {
    sql_query(" ALTER TABLE `{$g5['content_table']}`
                    ADD `co_tag_filter_use` tinyint(4) NOT NULL DEFAULT '0' AFTER `co_content` ", true);
    sql_query(" update {$g5['content_table']} set co_tag_filter_use = '1' ");
}

// 모바일 내용 추가
if(!sql_query(" select co_mobile_content from {$g5['content_table']} limit 1", false)) {
    sql_query(" ALTER TABLE `{$g5['content_table']}`
                    ADD `co_mobile_content` longtext NOT NULL AFTER `co_content` ", true);
}

// 스킨 설정 추가
if(!sql_query(" select co_skin from {$g5['content_table']} limit 1 ", false)) {
    sql_query(" ALTER TABLE `{$g5['content_table']}`
                    ADD `co_skin` varchar(255) NOT NULL DEFAULT '' AFTER `co_mobile_content`,
                    ADD `co_mobile_skin` varchar(255) NOT NULL DEFAULT '' AFTER `co_skin` ", true);
    sql_query(" update {$g5['content_table']} set co_skin = 'basic', co_mobile_skin = 'basic' ");
}

$html_title = "내용";
$g5['title'] = $html_title.' 관리';

if ($w == "u")
{
    $html_title .= " 수정";
    $readonly = " readonly";

    $sql = " select * from {$g5['content_table']} where co_id = '$co_id' ";
    $co = sql_fetch($sql);
    if (!$co['co_id'])
        alert('등록된 자료가 없습니다.');
}
else
{
    $html_title .= ' 입력';
    $co['co_html'] = 2;
    $co['co_skin'] = 'basic';
    $co['co_mobile_skin'] = 'basic';
}

include_once ('./admin.head.php');

add_stylesheet('<link rel="stylesheet" href="'.G5_ADMIN_URL. '/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 4);
//JS DataTables
add_javascript('<script src="'.G5_ADMIN_URL.'/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_ADMIN_URL.'/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);
//add_javascript('<script src="http://test.innobox.co.kr/plugin/editor/smarteditor2/js/service/HuskyEZCreator.js?ver='.G5_JS_VER.'"></script>', 6);
//add_javascript('<script src="http://test.innobox.co.kr/plugin/editor/smarteditor2/config.js?ver='.G5_JS_VER.'"></script>', 6);
//add_javascript('<script src="http://test.innobox.co.kr/admlte2/HuskyEZCreator.js?ver='.G5_JS_VER.'"></script>', 7);

?>

<form name="frmcontentform" action="./contentformupdate.php" onsubmit="return frmcontentform_check(this);" method="post" enctype="MULTIPART/FORM-DATA" >
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
		내용관리
		<small>contentform</small>
		</h1>
		<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">회원관리</a></li>
		<li class="active">회원정보리스트</li>
		</ol>
	</section>
	<section class="content">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
				  <nav class="navbar navbar-default navbtn" style="z-index: 10000000000000000000000000000000000000000000">
						<div class="text-right">  				  					  
							<a href="./contentlist.php" class="btn btn-info btn-list">목록</a>				  
							<input type="submit" value="확인" class="btn btn-danger btn_submit btn" accesskey='s'>						 
						</div>
				  </nav>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<input type="hidden" name="w" value="<?php echo $w ?>">
					<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
					<input type="hidden" name="stx" value="<?php echo $stx ?>">
					<input type="hidden" name="sst" value="<?php echo $sst ?>">
					<input type="hidden" name="sod" value="<?php echo $sod ?>">
					<input type="hidden" name="page" value="<?php echo $page ?>">
					<input type="hidden" name="token" value="">

					<table id="example2" class="table table-bordered table-hover dataTable member-form">
						<!--  <caption><?php echo $g5['title']; ?> 목록</caption> -->
						<colgroup>
							<col class="grid_4">
							<col>
						</colgroup>
						<tbody>
						<tr>
							<th scope="row"><label for="co_id">ID</label></th>
							<td>
								<?php echo help('20자 이내의 영문자, 숫자, _ 만 가능합니다.'); ?><br>						

								<input type="text" value="<?php echo $co['co_id']; ?>" name="co_id" id ="co_id" required <?php echo $readonly; ?> class="required <?php echo $readonly; ?> frm_input form-control" size="20" maxlength="20">
								<?php if ($w == 'u') { ?><a href="http://test.innobox.co.kr/bbs/content.php?co_id=<?php echo $co_id; ?>" class="btn_frmline">내용확인</a><?php } ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="co_subject">제목</label></th>
							<td><input type="text" name="co_subject" value="<?php echo htmlspecialchars2($co['co_subject']); ?>" id="co_subject" required class="frm_input form-control required" size="90"></td>
						</tr>
						<tr>
							<th scope="row">내용</th>
							<td><?php echo editor_html('co_content', get_text($co['co_content'], 0)); ?></td>
						</tr>
						<tr>
							<th scope="row">모바일 내용 <?php $html ?></th>
							<td><?php echo editor_html('co_mobile_content', get_text($co['co_mobile_content'], 0)); ?></td>
						</tr>
						<tr>
							<th scope="row"><label for="co_skin">스킨 디렉토리<strong class="sound_only">필수</strong></label></th>
							<td>
								<?php echo get_skin_select('content', 'co_skin', 'co_skin', $co['co_skin'], 'required class="form-control"'); ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="co_mobile_skin">모바일스킨 디렉토리<strong class="sound_only">필수</strong></label></th>
							<td>
								<?php echo get_mobile_skin_select('content', 'co_mobile_skin', 'co_mobile_skin', $co['co_mobile_skin'], 'required class="form-control"'); ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="co_tag_filter_use">태그 필터링 사용</label></th>
							<td>
								<?php echo help("내용에서 iframe 등의 태그를 사용하려면 사용안함으로 선택해 주십시오."); ?>
								<select name="co_tag_filter_use" id="co_tag_filter_use" class="form-control">
									<option value="1"<?php echo get_selected(1, $co['co_tag_filter_use']); ?>>사용함</option>
									<option value="0"<?php echo get_selected(0, $co['co_tag_filter_use']); ?>>사용안함</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="co_include_head">상단 파일 경로</label></th>
							<td>
								<?php echo help("설정값이 없으면 기본 상단 파일을 사용합니다."); ?>
								<input type="text" name="co_include_head" value="<?php echo $co['co_include_head']; ?>" id="co_include_head" class="frm_input form-control" size="60">
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="co_include_tail">하단 파일 경로</label></th>
							<td>
								<?php echo help("설정값이 없으면 기본 하단 파일을 사용합니다."); ?>
								<input type="text" name="co_include_tail" value="<?php echo $co['co_include_tail']; ?>" id="co_include_tail" class="frm_input form-control" size="60">
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="co_himg">상단이미지</label></th>
							<td>
								<input type="file" name="co_himg" id="co_himg">
								<?php
								$himg = G5_DATA_PATH.'/content/'.$co['co_id'].'_h';
								if (file_exists($himg)) {
									$size = @getimagesize($himg);
									if($size[0] && $size[0] > 750)
										$width = 750;
									else
										$width = $size[0];

									echo '<input type="checkbox" name="co_himg_del" value="1" id="co_himg_del"> <label for="co_himg_del">삭제</label>';
									$himg_str = '<img src="'.G5_DATA_URL.'/content/'.$co['co_id'].'_h" width="'.$width.'" alt="">';
								}
								if ($himg_str) {
									echo '<div class="banner_or_img">';
									echo $himg_str;
									echo '</div>';
								}
								?>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="co_timg">하단이미지</label></th>
							<td>
								<input type="file" name="co_timg" id="co_timg">
								<?php
								$timg = G5_DATA_PATH.'/content/'.$co['co_id'].'_t';
								if (file_exists($timg)) {
									$size = @getimagesize($timg);
									if($size[0] && $size[0] > 750)
										$width = 750;
									else
										$width = $size[0];

									echo '<input type="checkbox" name="co_timg_del" value="1" id="co_timg_del"> <label for="co_timg_del">삭제</label>';
									$timg_str = '<img src="'.G5_DATA_URL.'/content/'.$co['co_id'].'_t" width="'.$width.'" alt="">';
								}
								if ($timg_str) {
									echo '<div class="banner_or_img">';
									echo $timg_str;
									echo '</div>';
								}
								?>
							</td>
						</tr>
						</tbody>
					</table>		
				</div>
			</div>
		</div>
	</section>
</div>
<!-- /.content-wrapper -->

</form>

<script>
function frmcontentform_check(f)
{
    errmsg = "";
    errfld = "";

    <?php echo get_editor_js('co_content'); ?>
    <?php echo chk_editor_js('co_content'); ?>
    <?php echo get_editor_js('co_mobile_content'); ?>

    check_field(f.co_id, "ID를 입력하세요.");
    check_field(f.co_subject, "제목을 입력하세요.");
    check_field(f.co_content, "내용을 입력하세요.");

    if (errmsg != "") {
        alert(errmsg);
        errfld.focus();
        return false;
    }
    return true;
}


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
    include_once('./footer.php');
?>
