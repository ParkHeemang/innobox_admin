<?php
$sub_menu = '400650';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[$sub_menu], "w");

if ($w == "") {
    $is['is_time'] = date('Y-m-d H:i:s');
    $is['is_name'] = file_get_contents(G5_BBS_URL.'/ajax.random_name.php');
    $is['is_score'] = '5';
} elseif ($w == "u") {
    $sql = " select *
               from {$g5['g5_shop_item_use_table']} a
               left join {$g5['member_table']} b on (a.mb_id = b.mb_id)
               left join {$g5['g5_shop_item_table']} c on (a.it_id = c.it_id)
              where is_id = '$is_id' ";
    $is = sql_fetch($sql);


    if (!$is['is_id'])
        alert('등록된 자료가 없습니다.');
}

// 사용후기 의 답변 필드 추가
if (!isset($is['is_reply_subject'])) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_item_use_table']}`
                ADD COLUMN `is_reply_subject` VARCHAR(255) NOT NULL DEFAULT '' AFTER `is_confirm`,
                ADD COLUMN `is_reply_content` TEXT NOT NULL AFTER `is_reply_subject`,
                ADD COLUMN `is_reply_name` VARCHAR(25) NOT NULL DEFAULT '' AFTER `is_reply_content`
                ", false);
}

$name = get_sideview($is['mb_id'], get_text($is['is_name']), $is['mb_email'], $is['mb_homepage']);

// 확인
$is_confirm_yes  =  $w == "" || $is['is_confirm'] ? 'checked="checked"' : '';
$is_confirm_no   = $w == "u" && !$is['is_confirm'] ? 'checked="checked"' : '';

$g5['title'] = '사용후기';
include_once ('../admin.head.php');
add_stylesheet('<link rel="stylesheet" href="'.G5_PLUGIN_URL.'/jquery-ui-timepicker/dist/jquery-ui-timepicker-addon.min.css" >', 1);
add_javascript('<script src="'.G5_PLUGIN_URL.'/jquery-ui-timepicker/dist/jquery-ui-timepicker-addon.min.js"></script>', 1);

$qstr .= ($qstr ? '&amp;' : '').'sca='.$sca;


// 회원 정보
// 상품 정보
$it = array();
$foo = "select 
            i.it_id,i.it_name,i.it_use,i.it_soldout, i.ca_id, 
            c.ca_name 
        from {$g5['g5_shop_item_table']} as i
        left join {$g5['g5_shop_category_table']} as c on i.ca_id=c.ca_id
        where (1)
        order by c.ca_order, c.ca_id, i.it_order, i.it_name ";
$bar = sql_query($foo);
for ($i=0; $tmp=sql_fetch_array($bar); $i++) {
    $it[$tmp['it_id']] = $tmp;
}

// 상품 카테고리 정보
$ca = array();
$foo = "select * from {$g5['g5_shop_category_table']} 
        where (1)
        order by ca_order, ca_id ";
$bar = sql_query($foo);
for ($i=0; $tmp=sql_fetch_array($bar); $i++) {
    $ca[$tmp['ca_id']] = $tmp;
}

//CSS
add_stylesheet('<link rel="stylesheet" href="'.G5_PHP_URL. 'bower_components/bootstrap/dist/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 5);
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL2.'/bootstrap2.custom.css?ver='.G5_CSS_VER.'"></script>',8);


//JS DataTables
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);

?>


<form name="fitemuseform" method="post" action="./itemuseformupdate.php" onsubmit="return fitemuseform_submit(this);">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="is_id" value="<?php echo $is_id; ?>">
<input type="hidden" name="sca" value="<?php echo $sca; ?>">
<input type="hidden" name="sst" value="<?php echo $sst; ?>">
<input type="hidden" name="sod" value="<?php echo $sod; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
	  <h1 class="member_list_title">
		사용후기
		<small>itemuse form</small>
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
								<a href="./itemuselist.php?<?php echo $qstr; ?>" class="btn_02 btn btn-default">목록</a>
								<input type="submit" value="확인" class="btn_submit btn btn-danger" accesskey="s">					
							</div>
						</nav>
					</div>

				

					<!-- /.box-header -->
					<div class="box-body">						
						<div class="tbl_frm01 tbl_wrap">
							<div class="alert alert-<?php echo empty($is['mb_id']) ? 'info' : 'danger'; ?>">
								<?php if ($w == "") { ?>이 페이지에서 <b>새로 등록</b>하는 사용후기는 <b>회원아이디 없이 작성</b>하실 수 있습니다.<br><?php } ?>
								사용후기는 회원만 작성할 수 있도록 되어 있습니다. 따라서 이름은 수정이 가능하나 회원아이디는 수정하실 수 없습니다.
							</div>
							<table id="example2" class="table table-bordered table-hover dataTable member-form" role="grid" aria-describedby="example2_info">
								<!-- <caption><?php echo $g5['title']; ?> 수정</caption> -->
								<colgroup>
									<col class="grid_4">
									<col>
								</colgroup>
								<tbody>
								<tr>
									<th scope="row">이름/회원아이디</th>
									<td>
										<?php // echo $name; ?>
										<input type="text" name="is_name" id="is_name" class="frm_input form-control" value="<?php echo $is['is_name']; ?>" placeholder="이름" required>
										<input type="text" name="mb_id" id="mb_id" class="frm_input form-control" value="<?php echo $is['mb_id']; ?>" placeholder="회원아이디" readonly style="border-color:#ddd;color:#aaa;">
									</td>
								</tr>
								<tr>
									<th scope="row">상품명</th>
									<td>
										<!-- <a href="<?php echo G5_SHOP_URL; ?>/item.php?it_id=<?php echo $is['it_id']; ?>"><?php echo $is['it_name']; ?></a> -->
										<select name="it_id" id="it_id" required class="form-control">
											<option value="">상품 선택</option>
											<?php
												foreach ($it as $k=>$v) {
													$ca_name = array();
													$len = strlen($v['ca_id']) / 2;
													for ($j=1; $j<=$len; $j++) {
														$ca_name[] = $ca[substr($v['ca_id'], 0, $j*2)]['ca_name'];
													}

													echo '<option value="'.$k.'" '.get_selected($k, $is['it_id']).'>'.implode(" &gt; ", $ca_name).' &gt; '.$v['it_name'].'</option>';
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row">평점/작성일</th>
									<td>
										<select name="is_score" id="is_score" required class="form-control">
											<option value="">평점 선택</option>
											<option value="5" <?php echo get_selected($is['is_score'], "5"); ?>>매우만족</option>
											<option value="4" <?php echo get_selected($is['is_score'], "4"); ?>>만족</option>
											<option value="3" <?php echo get_selected($is['is_score'], "3"); ?>>보통</option>
											<option value="2" <?php echo get_selected($is['is_score'], "2"); ?>>불만</option>
											<option value="1" <?php echo get_selected($is['is_score'], "1"); ?>>매우불만</option>
										</select>
										<!-- <img src="<?php echo G5_URL; ?>/shop/img/s_star<?php echo $is['is_score']; ?>.png"> (<?php echo $is['is_score']; ?>점) -->

										<input type="text" name="is_time" id="is_time" class="frm_input form-control" required placeholder="작성일" value="<?php echo $is['is_time']; ?>">
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="is_subject">제목</label></th>
									<td><input type="text" name="is_subject" required class="required frm_input form-control" id="is_subject" size="100"
									value="<?php echo get_text($is['is_subject']); ?>"></td>
								</tr>
								<tr>
									<th scope="row">내용</th>
									<td><?php echo editor_html('is_content', get_text($is['is_content'], 0)); ?></td>
								</tr>
								<tr>
									<th scope="row"><label for="is_reply_subject">답변 제목</label></th>
									<td><input type="text" name="is_reply_subject" class="frm_input form-control" id="is_reply_subject" size="100"
									value="<?php echo get_text($is['is_reply_subject']); ?>"></td>
								</tr>
								<tr>
									<th scope="row">답변 내용</th>
									<td><?php echo editor_html('is_reply_content', get_text($is['is_reply_content'], 0)); ?></td>
								</tr>
								<tr>
									<th scope="row">확인</th>
									<td>
										<input type="radio" name="is_confirm" value="1" id="is_confirm_yes" <?php echo $is_confirm_yes; ?>>
										<label for="is_confirm_yes">예</label>
										<input type="radio" name="is_confirm" value="0" id="is_confirm_no" <?php echo $is_confirm_no; ?>>
										<label for="is_confirm_no">아니오</label>
									</td>
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
</form>






<script>
$(function() {
    $('#is_time').datetimepicker({
        dateFormat: 'yy-mm-dd',
        timeFormat: 'HH:mm:ss',
        stepHour: 1,
        stepMinute: 1,
        stepSecond: 1,
    });
});
function fitemuseform_submit(f)
{
    <?php echo get_editor_js('is_content'); ?>
    <?php echo get_editor_js('is_reply_content'); ?>
    return true;
}
</script>

<?php
include_once ('../footer.php');
?>
