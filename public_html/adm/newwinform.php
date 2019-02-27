<?php
$sub_menu = '100310';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[$sub_menu], "w");

$nw_id = preg_replace('/[^0-9]/', '', $nw_id);

$html_title = "팝업레이어";
if ($w == "u")
{
    $html_title .= " 수정";
    $sql = " select * from {$g5['new_win_table']} where nw_id = '$nw_id' ";
    $nw = sql_fetch($sql);
    if (!$nw['nw_id']) alert("등록된 자료가 없습니다.");
}
else
{
    $html_title .= " 입력";
    $nw['nw_device'] = 'both';
    $nw['nw_disable_hours'] = 24;
    $nw['nw_left']   = 10;
    $nw['nw_top']    = 10;
    $nw['nw_width']  = 500;
    $nw['nw_height'] = 500;
    // 2017-07-26
    // $nw['nw_content_html'] = 5;
    // nw_content : 에디터로만 사용하므로 POST 값에서 제외되어 nw_content_html 무조건 0 으로 기록됨
    // 0 : 에디터, 5 : 슬라이더 
}

$g5['title'] = $html_title;
include_once ('./admin.head.php');
/* 1:plugin, 2:theme, 3:skin, 4:closer, 0:external */
/*add_javascript('<script src="'.G5_PLUGIN_URL.'/jquery-ui-timepicker/dist/jquery-ui-timepicker-addon.js"></script>', 1);
add_javascript('<script src="'.G5_PLUGIN_URL.'/jquery-ui-timepicker/dist/i18n/jquery-ui-timepicker-ko.js"></script>', 1);
add_javascript('<script src="'.G5_PLUGIN_URL.'/jquery-ui-timepicker/dist/jquery-ui-sliderAccess.js"></script>', 1);
add_stylesheet('<link rel="stylesheet" href="'.G5_PLUGIN_URL.'/jquery-ui-timepicker/dist/jquery-ui-timepicker-addon.css">', 1);*/


//CSS
add_stylesheet('<link rel="stylesheet" href="'.G5_ADMIN_URL. '/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 4);
add_stylesheet('<link rel="stylesheet" href="'.G5_ADMIN_URL. '/css/jquery-ui-timepicker-addon.min.css?ver='.G5_CSS_VER.'">', 5);

//JS DataTables
add_javascript('<script src="'.G5_ADMIN_URL.'/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_ADMIN_URL.'/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);
add_javascript('<script src="'.G5_ADMIN_URL.'/js/jquery-ui-timepicker-addon.min.js?ver='.G5_JS_VER.'"></script>', 6);
add_javascript('<script src="'.G5_ADMIN_URL.'/js/jquery-ui-timepicker-ko.js?ver='.G5_JS_VER.'"></script>', 7);
?>
<link rel="stylesheet" href="http://test.innobox.co.kr/admlte2/css/bootstrap2.custom.css?ver=171222">

<!-- <script src="http://test.innobox.co.kr/plugin/jquery-ui-timepicker/dist/jquery-ui-timepicker-addon.js"></script>
<script src="http://test.innobox.co.kr/plugin/jquery-ui-timepicker/dist/i18n/jquery-ui-timepicker-ko.js"></script> -->


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
	  <h1 class="member_list_title">
		팝업레이어 입력
		<small>newwin form</small>
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
				<form name="frmnewwin" action="./newwinformupdate.php" onsubmit="return frmnewwin_check(this);" method="post" enctype="multipart/form-data">
				<div class="box">					
					<div class="box-header">
						<nav class="navbar navbar-default navbtn">
							<div class="text-right">							
								<a href="./newwinlist.php" class=" btn btn_02 btn-default">목록</a>
								<input type="submit" value="확인" class="btn_submit btn btn-primary" accesskey="s">					
							</div>
						</nav>
					</div>

					<!-- /.box-header -->
					<div class="box-body">					
							<input type="hidden" name="w" value="<?php echo $w; ?>">
							<input type="hidden" name="nw_id" value="<?php echo $nw_id; ?>">
							<input type="hidden" name="token" value="">

							<div class="local_desc01 local_desc">
								<p>초기화면 접속 시 자동으로 뜰 팝업레이어를 설정합니다.</p>
							</div>							
								<table id="example2" class="table table-bordered table-hover dataTable config_form">
								<!-- <caption><?php echo $g5['title']; ?></caption> -->
								<colgroup>
									<col class="grid_4">
									<col>
								</colgroup>
								<tbody>
								<tr>
									<th scope="row"><label for="nw_device">접속기기</label></th>
									<td>
										<?php echo help("팝업레이어가 표시될 접속기기를 설정합니다."); ?>
										<select name="nw_device" id="nw_device">
											<option value="both"<?php echo get_selected($nw['nw_device'], 'both', true); ?>>PC와 모바일</option>
											<option value="pc"<?php echo get_selected($nw['nw_device'], 'pc'); ?>>PC</option>
											<option value="mobile"<?php echo get_selected($nw['nw_device'], 'mobile'); ?>>모바일</option>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="nw_disable_hours">시간<strong class="sound_only"> 필수</strong></label></th>
									<td>
										<?php echo help("고객이 다시 보지 않음을 선택할 시 몇 시간동안 팝업레이어를 보여주지 않을지 설정합니다."); ?>
										<input type="text" name="nw_disable_hours" value="<?php echo $nw['nw_disable_hours']; ?>" id="nw_disable_hours" required class="frm_input form-control required" size="5"> 시간
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="nw_begin_time">시작일시<strong class="sound_only"> 필수</strong></label></th>
									<td>
										<input type="text" name="nw_begin_time" value="<?php echo $nw['nw_begin_time']; ?>" id="nw_begin_time" required class="frm_input form-control required" size="21" maxlength="19">
										<input type="checkbox" name="nw_begin_chk" value="<?php echo date("Y-m-d 00:00:00", G5_SERVER_TIME); ?>" id="nw_begin_chk" onclick="if (this.checked == true) this.form.nw_begin_time.value=this.form.nw_begin_chk.value; else this.form.nw_begin_time.value = this.form.nw_begin_time.defaultValue;">
										<label for="nw_begin_chk">시작일시를 오늘로</label>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="nw_end_time">종료일시<strong class="sound_only"> 필수</strong></label></th>
									<td>
										<input type="text" name="nw_end_time" value="<?php echo $nw['nw_end_time']; ?>" id="nw_end_time" required class="frm_input form-control required" size="21" maxlength="19">
										<input type="checkbox" name="nw_end_chk" value="<?php echo date("Y-m-d 23:59:59", G5_SERVER_TIME+(60*60*24*7)); ?>" id="nw_end_chk" onclick="if (this.checked == true) this.form.nw_end_time.value=this.form.nw_end_chk.value; else this.form.nw_end_time.value = this.form.nw_end_time.defaultValue;">
										<label for="nw_end_chk">종료일시를 오늘로부터 7일 후로</label>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="nw_left">팝업레이어 좌측 위치<strong class="sound_only"> 필수</strong></label></th>
									<td>
									   <input type="text" name="nw_left" value="<?php echo $nw['nw_left']; ?>" id="nw_left" required class="frm_input form-control required" size="5"> px
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="nw_top">팝업레이어 상단 위치<strong class="sound_only"> 필수</strong></label></th>
									<td>
										<input type="text" name="nw_top" value="<?php echo $nw['nw_top']; ?>" id="nw_top" required class="frm_input form-control required"  size="5"> px
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="nw_width">팝업레이어 넓이<strong class="sound_only"> 필수</strong></label></th>
									<td>
										<input type="text" name="nw_width" value="<?php echo $nw['nw_width'] ?>" id="nw_width" required class="frm_input form-control required" size="5"> px
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="nw_height">팝업레이어 높이<strong class="sound_only"> 필수</strong></label></th>
									<td>
										<input type="text" name="nw_height" value="<?php echo $nw['nw_height'] ?>" id="nw_height" required class="frm_input form-control required" size="5"> px
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="nw_subject">팝업 제목<strong class="sound_only"> 필수</strong></label></th>
									<td>
										<input type="text" name="nw_subject" value="<?php echo stripslashes($nw['nw_subject']) ?>" id="nw_subject" required class="frm_input form-control required" size="80">
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="nw_content_html">팝업 컨텐츠 종류<strong class="sound_only"> 필수</strong></label></th>
									<td>
										<?php if ($w == '' || ($w == 'u' && $nw['nw_content_html'] == 0)) { ?>
										<input type="radio" name="nw_content_html" class="nw_content_html" id="nw_content_html_0" value="0" <?php echo get_checked($nw['nw_content_html'], 0); ?>> 
										<label for="nw_content_html_0">에디터</label>
										<?php } ?>
										<?php if ($w == '' || ($w == 'u' && $nw['nw_content_html'] == 5)) { ?>
										<input type="radio" name="nw_content_html" class="nw_content_html" id="nw_content_html_5" value="5" <?php echo get_checked($nw['nw_content_html'], 5); ?>> 
										<label for="nw_content_html_5">슬라이드</label>
										<?php } ?>
										<?php if ($w == 'u') { ?>
										(수정일 경우, 팝업 컨텐츠 종류를 변경하실 수 없습니다.)
										<?php } ?>
									</td>
								</tr>
								<tr class="nw_content" id="nw_content_0" style="display:none">
									<th scope="row"><label for="nw_content">내용</label></th>
									<td><?php echo editor_html('nw_content', get_text($nw['nw_content'], 0)); ?></td>
								</tr>
								<tr class="nw_content" id="nw_content_5" style="display:none">
									<th scope="row"><label for="nw_content">내용</label></th>
									<td>
										<?php echo help('<b>주의!</b> 이미지 크기가 모두 같아야 합니다.'); ?>

										<ul class="entry" id="nw_layer">
											<?php
												$row = empty($nw['nw_content']) ? array() : unserialize($nw['nw_content']);
												for ($i=0; $i<count($row); $i++) {
													$img = G5_DATA_URL.'/popup/'.$row[$i]['file'];
													$size = @getimagesize(G5_DATA_PATH.'/popup/'.$row[$i]['file']);
													if ($i == 0) {
														$firstborn = $size;
													} else {
														if ($firstborn[0] == $size[0] && $firstborn[1] == $size[1]) {
															$diffsize = '';
														} else {
															$diffsize = 'ssRed';
														}
													}
													echo '
													<li class="piece mt-1 mb-1">
														<input type="hidden" name="stack[]" value="" />
														<input type="hidden" name="erase[]" value="" />
														<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
														<span class="">이미지</span>
														<div class="d-inline-block w-25">
															<input type="file" name="file[]" class="frm_input form-control d-none" value="" /> 
															<input type="text" name="name[]" class="frm_input form-control w-100" value="'.$row[$i]['file'].'" readonly /> 
														</div>
														<span class="ml-3">링크</span>
														<input type="text" name="link[]" class="frm_input form-control" value="'.$row[$i]['link'].'" placeholder="옵션" />
														<label><input type="checkbox" name="wreck[]" value="1"> 삭제 </label>
														<a href="'.$img.'" class="image '.$diffsize.'" data-width="'.$size[0].'" data-height="'.$size[1].'">[이미지 사이즈 : '.$size[0].'px * '.$size[1].'px]</a>
													</li>';
												}
											?>

											<li class="piece clone mt-1 mb-1">
												<input type="hidden" name="stack[]" value="" />
												<input type="hidden" name="erase[]" value="" />
												<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
												<span class="">이미지</span>
												<div class="custom-file d-inline-block w-25">
													<label class="custom-file-label" for="">Choose file</label>
													<input type="file" name="file[]" class="custom-file-input">
													<input type="text" name="name[]" class="frm_input form-control d-none"> 
												</div>
												<span class="ml-3">링크</span>
												<input type="text" name="link[]" class="frm_input form-control" placeholder="옵션" /> 
												<button type="button" class="insertStack btn btn-info">추가</button>
												<button type="button" class="removeStack btn btn-danger">삭제</button>
											</li>
										</ul>
									</td>
								</tr>
								</tbody>
								</table>												
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
    $('#nw_device').on('change', function() {
        var $this = $(this);
        if ($this.val() == 'mobile') {
            $('.nw_pos, .nw_size').hide();
        } else {
            $('.nw_pos, .nw_size').show();
        }
    }).trigger('change');

    $('#nw_begin_time').datetimepicker({
        dateFormat: 'yy-mm-dd',
        timeFormat: 'HH:mm:ss',
        controlType: 'select',
        oneLine: true,
        maxDateTime: new Date($('#nw_end_time').val()),
    });
    $('#nw_end_time').datetimepicker({
        dateFormat: 'yy-mm-dd',
        timeFormat: 'HH:mm:ss',
        controlType: 'select',
        oneLine: true,
        minDateTime: new Date($('#nw_begin_time').val()),
    });
    $('.entry').sortable({ 
        containment: "parent"
    });
    $('.nw_content_html').on('click change', function() {
        var $this = $(this),
            value = $('[name="nw_content_html"]:checked').val() || 0;
        $('.nw_content').hide();
        $('#nw_content_' + value).show();
    }).trigger('change');

    $(document).on('click', '.insertStack', function(e) {
        var $this = $(this),
            piece = $this.closest('.piece'),
            siblings = piece.siblings().length;
        piece.clone()
            .find('.custom-file-label').text('Choose file').end()
            .find('input[name^="file"]').val('').end()
            .find('input[name^="link"]').val('').end()
            .insertAfter(piece);
    });
    $(document).on('click', '.removeStack', function(e) {
        var $this = $(this),
            piece = $this.closest('.piece'),
            siblings = piece.siblings().filter('.clone').length;
        if (siblings > 0) {
            piece.remove();
        }
    });
    $(document).on('click', '.deleteStack', function(e) {
        var $this = $(this),
            piece = $this.closest('.piece'),
            $prev = piece.prev(),
            $next = piece.next(),
            siblings = piece.siblings().filter('.clone').length;
        if (siblings > 0) {
            var n = piece.find('input:file').filter(function() { return this.value; }).length;
            if (n > 0) {
                if ( ! confirm('정말로 제거하시겠습니까? ')) {
                    return false;
                }
            }
            piece.remove();
            $prev.find('input:text:eq(0)').focus();
        } else {
        }
    });
    $(document).on('click', '[name="wreck[]"]', function() {
        var $this = $(this),
            check = $this.is(':checked'),
            erase = $this.closest('.piece').find('[name="erase[]"]');

        erase.val(check == true ? '1' : '');
    });
    $(document).on('click', '.image', function() {
        var $this = $(this),
            _w = $this.data('width'),
            _h = $this.data('height'),
            _s = $this.attr('href');
        // window.open(_s, 'image', 'width='+_w+',height='+_h+'');
        return false;
    });
});
</script>

<script>
function frmnewwin_check(f)
{
    errmsg = "";
    errfld = "";

    <?php echo get_editor_js('nw_content'); ?>

    check_field(f.nw_subject, "제목을 입력하세요.");

    if (errmsg != "") {
        alert(errmsg);
        errfld.focus();
        return false;
    }
    return true;
}

//모든 select 스타일 지정
var selects = document.getElementsByTagName("select"); 
for (var i = 0; i < selects.length; i++) { 
selects[i].className = "form-control"
}

</script>

<?php
include_once ('./admin.tail.php');
?>
