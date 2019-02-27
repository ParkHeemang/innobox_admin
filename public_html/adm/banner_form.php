<?php
$sub_menu = '100312';
include_once('./_common.php');

auth_check($auth[$sub_menu], "w");

$ba_id = preg_replace('/[^0-9]/', '', $ba_id);

if ($w == '') {
    $html_title .= ' 입력';
    $ba['ba_url'] = "";
    //$ba['ba_begin_time'] = date("Y-m-d 00:00:00", time());
    //$ba['ba_end_time']   = date("Y-m-d 00:00:00", time()+(60*60*24*31));
} else if ($w == 'u') {
    $html_title .= ' 수정';
    $sql = " select * from {$g5['banner_table']} where ba_id = '$ba_id' ";
    $ba = sql_fetch($sql);
    if (empty($ba['ba_id'])) {
        alert('존재하지 않는 자료입니다.');
    }
} else {
    alert('제대로 된 값이 넘어오지 않았습니다.');
}

$g5['title'] .= '배너 '.$html_title;
include_once ('./admin.head.php');

/* 1:plugin, 2:theme, 3:skin, 4:closer, 0:external */
add_stylesheet('<link rel="stylesheet" href="'.G5_PLUGIN_URL.'/jquery-ui-1.12.1/jquery-ui.min.css" />', 1);
add_javascript('<script src="'.G5_PLUGIN_URL.'/jquery-ui-1.12.1/jquery-ui.min.js"></script>', 1);
add_javascript('<script src="'.G5_PLUGIN_URL.'/jquery-ui-1.12.1/jquery-ui-datepicker.set.defaults.js"></script>', 1);

// query string 재정의
$qstr = 'prt='.$prt.'&amp;'.'dev='.$dev.'&amp;'.'pos='.$pos.'&amp;'.$qstr;


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
		배너 수정
		<small>banner form</small>
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
						<nav class="navbar navbar-default navbtn">
							<div class="text-right">							
								<a href="./banner_list.php?<?php echo $qstr ?>" class="btn btn_02 btn-default">목록</a>
								<input type="submit" value="확인" class="btn_submit btn btn-primary" accesskey='s'>					
							</div>
						</nav>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<form name="fbanner" action="./banner_form_update.php" method="post" enctype="multipart/form-data">
						<input type="hidden" name="w" value="<?php echo $w ?>">
						<input type="hidden" name="dev" value="<?php echo $dev ?>">
						<input type="hidden" name="pos" value="<?php echo $pos ?>">
						<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
						<input type="hidden" name="stx" value="<?php echo $stx ?>">
						<input type="hidden" name="sst" value="<?php echo $sst ?>">
						<input type="hidden" name="sod" value="<?php echo $sod ?>">
						<input type="hidden" name="page" value="<?php echo $page ?>">
						<input type="hidden" name="token" value="">
						<input type="hidden" name="ba_id" value="<?php echo $ba_id; ?>">
						<div class="tbl_frm01 tbl_wrap">
							<table id="example2" class="table table-bordered table-hover dataTable member-form" role="grid" aria-describedby="example2_info">
								<!-- <caption><?php echo $g5['title']; ?></caption> -->
								<colgroup>
									<col class="grid_4">
									<col>
								</colgroup>
								<tbody>
									<tr>
										<th scope="row">노출</th>
										<td>
											<select name="ba_print" id="ba_print" class="form-control">
												<option value="1" <?php echo get_selected($ba['ba_print'] , '1'); ?>>노출</option>
												<option value="0" <?php echo get_selected($ba['ba_print'] , '0'); ?>>숨김</option>
											</select>
										</td>
									</tr>
									<tr>
										<th scope="row"><label for="ba_device">접속기기</label></th>
										<td>
											<?php echo help('배너를 표시할 접속기기를 선택합니다.'); ?>
											<select name="ba_device" id="ba_device" class="form-control">
												<option value="both"<?php echo get_selected($ba['ba_device'], 'both', true); ?>>PC와 모바일</option>
												<option value="pc"<?php echo get_selected($ba['ba_device'], 'pc'); ?>>PC</option>
												<option value="mobile"<?php echo get_selected($ba['ba_device'], 'mobile'); ?>>모바일</option>
										</select>
										</td>
									</tr>
									<tr>
										<th scope="row"><label for="ba_position">출력 위치</label></th>
										<td>
											<?php echo help('배너를 표시할 영역입니다. 꼭 확인하세요!'); ?>
											<input type="text" name="ba_position" id="ba_position" value="<?php echo $ba['ba_position']; ?>" class="frm_input form-control">
											<select name="ba_possess" id="ba_possess" class="form-control">
												<option value="">입력된 위치 선택 입력</option>
												<?php
													$foo = "select ba_position from {$g5['banner_table']} group by ba_position order by ba_position";
													$bar = sql_query($foo);
													for ($i=0; $tmp=sql_fetch_array($bar); $i++) {
														echo '<option value="'.$tmp['ba_position'].'">'.$tmp['ba_position'].'</option>';
													}
												?>

												<!-- 
												<option value="메인" <?php echo get_selected($ba['ba_position'], '메인'); ?>>메인</option>
												<option value="상단" <?php echo get_selected($ba['ba_position'], '상단'); ?>>상단</option>
												<option value="하단" <?php echo get_selected($ba['ba_position'], '하단'); ?>>하단</option>
												<option value="허브" <?php echo get_selected($ba['ba_position'], '허브'); ?>>허브</option>
												<option value="메뉴" <?php echo get_selected($ba['ba_position'], '메뉴'); ?>>메뉴</option>
												<option value="스크롤배너L" <?php echo get_selected($ba['ba_position'], '스크롤배너L'); ?>>스크롤배너L</option>
												<option value="스크롤배너R" <?php echo get_selected($ba['ba_position'], '스크롤배너R'); ?>>스크롤배너R</option>
												<option value="상품상세" <?php echo get_selected($ba['ba_position'], '상품상세'); ?>>상품상세</option> -->
											</select>
										</td>
									</tr>
									<tr>
										<th scope="row"><label for="ba_order">출력 순서</label></th>
										<td>
											<?php echo help("낮은 순번이 먼저 출력됩니다. 같은 순번이면 나중에 등록된 게 우선 순위입니다."); ?>
										   <input type="text" name="ba_order" id="ba_order" class="frm_input form-control" value="<?php echo $ba['ba_order']; ?>" />
										   <?php // echo order_select("ba_order", $ba['ba_order']); ?>
										</td>
									</tr>

									<tr>
										<th scope="row">이미지</th>
										<td>
											<div class="custom-file d-inline-block w-25">
												<label class="custom-file-label" for="">Choose file</label>
												<input type="file" name="ba_bimg" class="custom-file-input">
											</div>

											<?php
											$bimg_str = "";
											$bimg = G5_DATA_PATH.'/banner/'.$ba['ba_img'];
											if (file_exists($bimg) && $ba['ba_img']) {
												$size = @getimagesize($bimg);
												if($size[0] && $size[0] > 920)
													$width = 920;
												else
													$width = $size[0];

												$img_size = '<span class="ssSilver">(가로:'.$size[0].'px × 세로:'.$size[1].'px)</span>';
												$img_link = '<a href="'.G5_DATA_URL.'/banner/'.$ba['ba_img'].'" target="_blank">'.$ba['ba_img'].'</a>';
												echo '<input type="checkbox" name="ba_bimg_del" value="1" id="ba_bimg_del"> <label for="ba_bimg_del"><b>삭제</b> '.$ba['ba_img'].' '.$img_size.'</label>';
												$bimg_str = '<img src="'.G5_DATA_URL.'/banner/'.$ba['ba_img'].'" width="'.$width.'">';
											}

											if ($bimg_str) {
												echo '<div class="banner_or_img mt-3">';
												echo '<a href="'.G5_DATA_URL.'/banner/'.$ba['ba_img'].'" target="_blank">'.$bimg_str.'</a>';
												echo '</div>';
											}
											?>
										</td>
									</tr>
									<tr>
										<th scope="row"><label for="ba_alt">이미지 설명</label></th>
										<td>
											<?php echo help("img 태그의 alt, title 에 해당되는 내용입니다.\n배너에 마우스를 오버하면 이미지의 설명이 나옵니다."); ?>
											<input type="text" name="ba_alt" value="<?php echo $ba['ba_alt']; ?>" id="ba_alt" class="frm_input form-control" size="80">
										</td>
									</tr>
									<tr>
										<th scope="row"><label for="ba_url">링크</label></th>
										<td>
											<?php echo help("배너 클릭시 이동하는 주소입니다."); ?>
											<input type="text" name="ba_url" size="80" value="<?php echo $ba['ba_url']; ?>" id="ba_url" class="frm_input form-control">
										</td>
									</tr>
									<tr>
										<th scope="row"><label for="ba_new_win">새창</label></th>
										<td>
											<?php echo help("배너 클릭시 새창을 띄울지를 설정합니다.", 50); ?>
											<select name="ba_new_win" id="ba_new_win" class='form-control'>
												<option value="0" <?php echo get_selected($ba['ba_new_win'], 0); ?>>사용안함</option>
												<option value="1" <?php echo get_selected($ba['ba_new_win'], 1); ?>>사용</option>
											</select>
										</td>
									</tr>
									<!-- 
									<tr>
										<th scope="row"><label for="ba_border">테두리</label></th>
										<td>
											 <?php echo help("배너 이미지에 테두리를 넣을지를 설정합니다.", 50); ?>
											<select name="ba_border" id="ba_border">
												<option value="0" <?php echo get_selected($ba['ba_border'], 0); ?>>사용안함</option>
												<option value="1" <?php echo get_selected($ba['ba_border'], 1); ?>>사용</option>
											</select>
										</td>
									</tr> -->
									<tr>
										<th scope="row"><label for="ba_begin_time">시작일시</label></th>
										<td>
											<?php echo help("배너 게시 시작일시를 설정합니다."); ?>
											<input type="text" name="ba_begin_time" value="<?php echo $ba['ba_begin_time']; ?>" id="ba_begin_time" class="frm_input form-control"  size="21" maxlength="19">
											<input type="checkbox" name="ba_begin_chk" value="<?php echo date("Y-m-d 00:00:00", time()); ?>" id="ba_begin_chk" onclick="if (this.checked == true) this.form.ba_begin_time.value=this.form.ba_begin_chk.value; else this.form.ba_begin_time.value = this.form.ba_begin_time.defaultValue;">
											<label for="ba_begin_chk">오늘</label>
										</td>
									</tr>
									<tr>
										<th scope="row"><label for="ba_end_time">종료일시</label></th>
										<td>
											<?php echo help("배너 게시 종료일시를 설정합니다."); ?>
											<input type="text" name="ba_end_time" value="<?php echo $ba['ba_end_time']; ?>" id="ba_end_time" class="frm_input form-control" size=21 maxlength=19>
											<input type="checkbox" name="ba_end_chk" value="<?php echo date("Y-m-d 23:59:59", time()+60*60*24*31); ?>" id="ba_end_chk" onclick="if (this.checked == true) this.form.ba_end_time.value=this.form.ba_end_chk.value; else this.form.ba_end_time.value = this.form.ba_end_time.defaultValue;">
											<label for="ba_end_chk">오늘+31일</label>
										</td>
									</tr>
								</tbody>
							</table>
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
 $(document).scroll(function(e){
    var scrollTop = $(document).scrollTop();
    if(scrollTop > 0){
        console.log(scrollTop);
        $('.navbar').removeClass('navbar-static-top').addClass('navbar-fixed-top');
    } else {
        $('.navbar').removeClass('navbar-fixed-top').addClass('navbar-static-top');
    }
});

$(function() {
    $('#ba_possess').on('change', function() {
        $('#ba_position').val($(this).val());
    });
});
</script>

<?php
include_once ('./admin.tail.php');
?>
