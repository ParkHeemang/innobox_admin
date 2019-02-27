<?php
$sub_menu = "100311";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$sl_id = preg_replace('/[^0-9]/', '', $sl_id);

if ($w == '') {
    $html_title = '추가';
    $sound_only = '<strong class="sound_only">필수</strong>';
    $ba['sl_url']        = "http://";
} else if ($w == 'u') {
    $html_title = '수정';
    $sql = " select * from {$g5['slider_table']} where sl_id = '$sl_id' ";
    $sl = sql_fetch($sql);
    if (empty($sl['sl_id'])) {
        alert('존재하지 않는 자료입니다.');
    } else {
        $sl_layer = empty($sl['sl_layer']) ? array() : unserialize($sl['sl_layer']);
    }

    /* theme 파일 루프용
    for ($i=0; $i<count($sl_layer['type']); $i++) {
        echo $sl_layer['type'][$i].'<br>';
        foreach ($sl_layer['data'] as $k=>$v) {
            echo $v[$i].'<br>';
        }
    } */

} else {
    alert('제대로 된 값이 넘어오지 않았습니다.');
}

$g5['title'] .= '메인슬라이더 '.$html_title;
include_once('./admin.head.php');
/* 1:plugin, 2:theme, 3:skin, 4:closer, 0:external */
add_javascript('<script src="'.G5_PLUGIN_URL.'/jquery-ui-1.12.1/external/globalize/globalize.js"></script>', 1);
add_javascript('<script src="'.G5_PLUGIN_URL.'/jquery-ui-1.12.1/external/jquery-mousewheel/jquery.mousewheel.js"></script>', 1);
//add_stylesheet('<link rel="stylesheet" href="">', 4);
//add_javascript('<script src=""></script>', 4);

// query string 재정의
$qstr = 'prt='.$prt.'&amp;'.'dev='.$dev.'&amp;'.$qstr;

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
		메인슬라이더 수정
		<small>slider form</small>
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
				<form name="fsliderform" id="fsliderform" action="./slider_form_update.php" onsubmit="return fsliderform_submit(this);" method="post" enctype="multipart/form-data">
				<div class="box">
					<div class="box-header">
						<nav class="navbar navbar-default navbtn">
							<div class="text-right">							
								<a href="./slider_list.php?<?php echo $qstr ?>" class="btn btn_02 btn-default">목록</a>
								<input type="submit" value="확인" class="btn_submit btn btn-danger" accesskey='s'>	
							</div>
						</nav>
					</div>


	

					<!-- /.box-header -->
					<div class="box-body">					
						
							<input type="hidden" name="w" value="<?php echo $w ?>">
							<input type="hidden" name="prt" value="<?php echo $prt ?>">
							<input type="hidden" name="dev" value="<?php echo $dev ?>">
							<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
							<input type="hidden" name="stx" value="<?php echo $stx ?>">
							<input type="hidden" name="sst" value="<?php echo $sst ?>">
							<input type="hidden" name="sod" value="<?php echo $sod ?>">
							<input type="hidden" name="page" value="<?php echo $page ?>">
							<input type="hidden" name="pagenum" value="<?php echo $pagenum ?>">
							<input type="hidden" name="token" value="">
							<input type="hidden" name="sl_id" value="<?php echo $sl_id; ?>">

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
												<select name="sl_print" id="sl_print" class="form-control">
													<option value="1" <?php echo get_selected($sl['sl_print'] , '1'); ?>>노출</option>
													<option value="0" <?php echo get_selected($sl['sl_print'] , '0'); ?>>숨김</option>
												</select>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="sl_device">접속기기</label></th>
											<td>
												<?php echo help('슬라이더를 표시할 접속기기를 선택합니다.'); ?>
												<select name="sl_device" id="sl_device" class="form-control">
													<option value="pc"<?php echo get_selected($sl['sl_device'], 'pc'); ?>>PC</option>
													<option value="mobile"<?php echo get_selected($sl['sl_device'], 'mobile'); ?>>모바일</option>
											</select>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="sl_order">순번</label></th>
											<td>
												<?php echo help("낮은 순번이 먼저 출력됩니다. 같은 순번이면 나중에 등록된 게 우선 순위입니다."); ?>
												<input name="sl_order" id="sl_order" value="<?php echo $sl['sl_order']; ?>" class="frm_input form-control">
											</td>
										</tr>
										<tr>
											<th scope="row">이미지</th>
											<td>
												<div class="custom-file d-inline-block w-25">
													<label class="custom-file-label" for="">Choose file</label>
													<input type="file" name="sl_simg" class="custom-file-input">
												</div>

												<?php
												$simg_str = "";
												$simg = G5_DATA_PATH.'/slider/'.$sl['sl_img'];
												if (file_exists($simg) && $sl['sl_img']) {
													$size = @getimagesize($simg);
													if($size[0] && $size[0] > 920)
														$width = 920;
													else
														$width = $size[0];

													$img_size = '<span class="ssSilver">(가로:'.$size[0].'px × 세로:'.$size[1].'px)</span>';
													$img_link = '<a href="'.G5_DATA_URL.'/slider/'.$sl['sl_img'].'" target="_blank">'.$sl['sl_img'].'</a>';
													echo '<input type="checkbox" name="sl_simg_del" value="1" id="sl_simg_del"> <label for="sl_simg_del"><b>삭제</b> '.$sl['sl_img'].' '.$img_size.'</label>';
													$simg_str = '<img src="'.G5_DATA_URL.'/slider/'.$sl['sl_img'].'" width="'.$width.'">';
												} else {
													echo '적정 사이즈는 가로:1920px × 세로:540px 입니다';
												}

												if ($simg_str) {
													echo '<div class="slider_or_img mt-3">';
													echo '<a href="'.G5_DATA_URL.'/slider/'.$sl['sl_img'].'" target="_blank">'.$simg_str.'</a>';
													echo '</div>';
												}
												?>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="sl_alt">이미지 설명</label></th>
											<td>
												<?php echo help("img 태그의 alt, title 에 해당되는 내용입니다.\n슬라이더에 마우스를 오버하면 이미지의 설명이 나옵니다."); ?>
												<input type="text" name="sl_alt" value="<?php echo $sl['sl_alt']; ?>" id="sl_alt" class="frm_input form-control" size="80">
											</td>
										</tr>

										<tr>
											<th scope="row"><label for="sl_begin_time">시작일시</label></th>
											<td>
												<?php echo help("메인슬라이더 게시 시작일시를 설정합니다."); ?>
												<input type="text" name="sl_begin_time" value="<?php echo $sl['sl_begin_time']; ?>" id="sl_begin_time" class="frm_input form-control"  size="21" maxlength="19">
												<input type="checkbox" name="sl_begin_chk" value="<?php echo date("Y-m-d 00:00:00", time()); ?>" id="sl_begin_chk" onclick="if (this.checked == true) this.form.sl_begin_time.value=this.form.sl_begin_chk.value; else this.form.sl_begin_time.value = this.form.sl_begin_time.defaultValue;">
												<label for="sl_begin_chk">오늘</label>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="sl_end_time">종료일시</label></th>
											<td>
												<?php echo help("메인슬라이더 게시 종료일시를 설정합니다."); ?>
												<input type="text" name="sl_end_time" value="<?php echo $sl['sl_end_time']; ?>" id="sl_end_time" class="frm_input form-control" size=21 maxlength=19>
												<input type="checkbox" name="sl_end_chk" value="<?php echo date("Y-m-d 23:59:59", time()+60*60*24*31); ?>" id="sl_end_chk" onclick="if (this.checked == true) this.form.sl_end_time.value=this.form.sl_end_chk.value; else this.form.sl_end_time.value = this.form.sl_end_time.defaultValue;">
												<label for="sl_end_chk">오늘+31일</label>
											</td>
										</tr>

										<tr>
											<th scope="row"><label for="sl_url">링크</label></th>
											<td>
												<?php echo help("슬라이더 클릭시 이동하는 주소입니다."); ?>
												<input type="text" name="sl_url" size="80" value="<?php echo $sl['sl_url']; ?>" id="sl_url" class="frm_input form-control">
											</td>
										</tr>
										<tr>
											<th><label for="sl_layer">레이어 추가</label></th>
											<td>
												<ul class="entry" id="sl_layer">

													<?php for ($i=0; $i<count($sl_layer['type']); $i++) { ?>

													<li class="piece">
														<i class="fa fa-arrows fa-1x" aria-hidden="true"></i>
														<div>
															<p>
																<label for="" class="dfn">구분</label> 
																<select name="sl_layer[type][]" class="form-control">
																	<option value="subject" <?php echo get_selected($sl_layer['type'][$i], 'subject'); ?>>제목</option>
																	<option value="content" <?php echo get_selected($sl_layer['type'][$i], 'content'); ?>>내용</option>
																</select>
															</p>
															<p>
																<label for="" class="dfn">width</label> 
																<input type="text" name="sl_layer[data][width][]" class="frm_input form-control spinner ssAlignR" value="<?php echo $sl_layer['data']['width'][$i]; ?>" size="5" />
															</p>
															<p>
																<label for="" class="dfn">position</label> 
																<select name="sl_layer[data][position][]" class="form-control">
																	<option value="topLeft" <?php echo get_selected($sl_layer['data']['position'][$i], 'topLeft'); ?>>topLeft</option>
																	<option value="topCenter" <?php echo get_selected($sl_layer['data']['position'][$i], 'topCenter'); ?>>topCenter</option>
																	<option value="topRight" <?php echo get_selected($sl_layer['data']['position'][$i], 'topRight'); ?>>topRight</option>
																	<option value="bottomLeft" <?php echo get_selected($sl_layer['data']['position'][$i], 'bottomLeft'); ?>>bottomLeft</option>
																	<option value="bottomCenter" <?php echo get_selected($sl_layer['data']['position'][$i], 'bottomCenter'); ?>>bottomCenter</option>
																	<option value="bottomRight" <?php echo get_selected($sl_layer['data']['position'][$i], 'bottomRight'); ?>>bottomRight</option>
																	<option value="centerLeft" <?php echo get_selected($sl_layer['data']['position'][$i], 'centerLeft'); ?>>centerLeft</option>
																	<option value="centerCenter" <?php echo get_selected($sl_layer['data']['position'][$i], 'centerCenter'); ?>>centerCenter</option>
																	<option value="centerRight" <?php echo get_selected($sl_layer['data']['position'][$i], 'centerRight'); ?>>centerRight</option>
																</select>
															</p>
															<p>
																<label for="" class="dfn">vertical</label>
																<input type="text" name="sl_layer[data][vertical][]" class="frm_input form-control spinner ssAlignR" value="<?php echo $sl_layer['data']['vertical'][$i]; ?>" size="5" />
															</p>
														</div>
														<div>
															<p>
																<label for="" class="dfn">show-transition</label> 
																<select name="sl_layer[data][show-transition][]" class="form-control">
																	<option value="up" <?php echo get_selected($sl_layer['data']['show-transition'][$i], 'up'); ?>>up</option>
																	<option value="down" <?php echo get_selected($sl_layer['data']['show-transition'][$i], 'down'); ?>>down</option>
																	<option value="left" <?php echo get_selected($sl_layer['data']['show-transition'][$i], 'left'); ?>>left</option>
																	<option value="right" <?php echo get_selected($sl_layer['data']['show-transition'][$i], 'right'); ?>>right</option>
																</select>
															</p>
															<p>
																<label for="" class="dfn">show-offset</label> 
																<input type="text" name="sl_layer[data][show-offset][]" class="frm_input form-control spinner ssAlignR" value="<?php echo $sl_layer['data']['show-offset'][$i]; ?>" size="5" />
															</p>
															<p>
																<label for="" class="dfn">show-duration</label> 
																<input type="text" name="sl_layer[data][show-duration][]" class="frm_input form-control spinner ssAlignR" value="<?php echo $sl_layer['data']['show-duration'][$i]; ?>" size="5" />
															</p>
															<p>
																<label for="" class="dfn">show-delay</label> 
																<input type="text" name="sl_layer[data][show-delay][]" class="frm_input form-control spinner ssAlignR" value="<?php echo $sl_layer['data']['show-delay'][$i]; ?>" size="5" />
															</p>
														</div>
														<div>
															<p>
																<label for="" class="dfn">hide-transition</label> 
																<select name="sl_layer[data][hide-transition][]" class="form-control">
																	<option value="up" <?php echo get_selected($sl_layer['data']['hide-transition'][$i], 'up'); ?>>up</option>
																	<option value="down" <?php echo get_selected($sl_layer['data']['hide-transition'][$i], 'down'); ?>>down</option>
																	<option value="left" <?php echo get_selected($sl_layer['data']['hide-transition'][$i], 'left'); ?>>left</option>
																	<option value="right" <?php echo get_selected($sl_layer['data']['hide-transition'][$i], 'right'); ?>>right</option>
																</select>
															</p>
															<p>
																<label for="" class="dfn">hide-offset</label> 
																<input type="text" name="sl_layer[data][hide-offset][]" class="frm_input form-control spinner ssAlignR" value="<?php echo $sl_layer['data']['hide-offset'][$i]; ?>" size="5" />
															</p>
															<p>
																<label for="" class="dfn">hide-duration</label> 
																<input type="text" name="sl_layer[data][hide-duration][]" class="frm_input form-control spinner ssAlignR" value="<?php echo $sl_layer['data']['hide-duration'][$i]; ?>" size="5" />
															</p>
															<p>
																<label for="" class="dfn">hide-delay</label> 
																<input type="text" name="sl_layer[data][hide-delay][]" class="frm_input form-control spinner ssAlignR" value="<?php echo $sl_layer['data']['hide-delay'][$i]; ?>" size="5" />
															</p>
														</div>
														<div>
															<label for="" class="dfn ssBgDark ssWhite">텍스트</label> 
															<input name="sl_layer[text][]" class="frm_input form-control title" value="<?php echo $sl_layer['text'][$i]; ?>" />
															<input type="hidden" name="sl_layer[erase][]" value="">
															<label><input type="checkbox" name="sl_layer[wreck][]" value="1"> 삭제</button>
														</div>
													</li>

													<?php } ?>
													<?php if ($i < 5) { ?>

													<li class="piece clone">
														<i class="fa fa-arrows fa-1x" aria-hidden="true"></i>
														<div>
															<p>
																<label for="" class="dfn">구분</label> 
																<select name="sl_layer[type][]" class="form-control">
																	<option value="subject">제목</option>
																	<option value="content">내용</option>
																</select>
															</p>
															<p>
																<label for="" class="dfn">width</label> 
																<input type="text" name="sl_layer[data][width][]" class="frm_input form-control spinner ssAlignR" value="940" size="5" maxlength="4" />
															</p>
															<p>
																<label for="" class="dfn">position</label> 
																<select name="sl_layer[data][position][]" class="form-control">
																	<option value="topLeft">topLeft</option>
																	<option value="topCenter" selected>topCenter</option>
																	<option value="topRight">topRight</option>
																	<option value="bottomLeft">bottomLeft</option>
																	<option value="bottomCenter">bottomCenter</option>
																	<option value="bottomRight">bottomRight</option>
																	<option value="centerLeft">centerLeft</option>
																	<option value="centerCenter">centerCenter</option>
																	<option value="centerRight">centerRight</option>
																</select>
															</p>
															<p>
																<label for="" class="dfn">vertical</label> 
																<input type="text" name="sl_layer[data][vertical][]" class="frm_input form-control spinner ssAlignR" value="100" size="5" maxlength="4" />
															</p>
														</div>
														<div>
															<p>
																<label for="" class="dfn">show-transition</label> 
																<select name="sl_layer[data][show-transition][]" class="form-control">
																	<option value="up">up</option>
																	<option value="down" selected>down</option>
																	<option value="left">left</option>
																	<option value="right">right</option>
																</select>
															</p>
															<p>
																<label for="" class="dfn">show-offset</label> 
																<input type="text" name="sl_layer[data][show-offset][]" class="frm_input form-control spinner ssAlignR" value="80" size="5" maxlength="4" />
															</p>
															<p>
																<label for="" class="dfn">show-duration</label> 
																<input type="text" name="sl_layer[data][show-duration][]" class="frm_input form-control spinner ssAlignR" value="800" size="5" maxlength="4" />
															</p>
															<p>
																<label for="" class="dfn">show-delay</label> 
																<input type="text" name="sl_layer[data][show-delay][]" class="frm_input form-control spinner ssAlignR" value="800" size="5" maxlength="4" />
															</p>
														</div>
														<div>
															<p>
																<label for="" class="dfn">hide-transition</label> 
																<select name="sl_layer[data][hide-transition][]" class="form-control">
																	<option value="up">up</option>
																	<option value="down" selected>down</option>
																	<option value="left">left</option>
																	<option value="right">right</option>
																</select>
															</p>
															<p>
																<label for="" class="dfn">hide-offset</label> 
																<input type="text" name="sl_layer[data][hide-offset][]" class="frm_input form-control spinner ssAlignR" value="80" size="5" maxlength="4" />
															</p>
															<p>
																<label for="" class="dfn">hide-duration</label> 
																<input type="text" name="sl_layer[data][hide-duration][]" class="frm_input form-control spinner ssAlignR" value="800" size="5" maxlength="4" />
															</p>
															<p>
																<label for="" class="dfn">hide-delay</label> 
																<input type="text" name="sl_layer[data][hide-delay][]" class="frm_input form-control spinner ssAlignR" value="800" size="5" maxlength="4" />
															</p>
														</div>
														<div>
															<label for="" class="dfn ssBgDark ssWhite">텍스트</label> 
															<input name="sl_layer[text][]" class="frm_input form-control title" value="" />
															<button type="button" class="btn btn-info insertStack ssBgDark ssWhite">추가</button>
															<button type="button" class="btn btn-danger deleteStack">제거</button>
														</div>
													</li>

													<?php } ?>

												</ul>
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

<script>
$(function() {
    $('.entry').sortable({ axis: 'y' });
    $('input.spinner:enabled').spinner({
        min: 0,
        //max: 500000,
        step: 10,
        spin: function(event, ui) {
        },
        start: function(event, ui) {
            var $this = $(this),
                readonly =  $this.prop('readonly');
            if (readonly) {
                return false;
            }
        },
        stop: function(event, ui) {
            $(this).trigger('change');
        }
    });

    $(document).on('keyup', '[name^="sl_layer[data]"]', function() {
        var $this = $(this);
        $this.val($this.val().replace(/[^0-9]/g, ''));
    }).trigger('keyup');

    $(document).on('click', '.insertStack', function(e) {
        var $this = $(this),
            piece = $this.closest('.piece'),
            siblings = piece.siblings().length;
        if (siblings+1 < 5) {
            piece.clone()
                .find('select[name^="sl_layer[type]"]').val('content').end()
                .insertAfter(piece)
                .find('input:text[name^="sl_layer[text]"]').val('').focus();
        } else {
            alert('최대 3개까지 입력 가능합니다.');
        }
    });
    $(document).on('click', '.removeStack', function(e) {
        var $this = $(this),
            piece = $this.closest('.piece'),
            siblings = piece.siblings().filter('.clone').length;
        if (siblings > 0) {
            $this.remove();
        }
    });
    $(document).on('click', '.deleteStack', function(e) {
        var $this = $(this),
            piece = $this.closest('.piece'),
            $prev = piece.prev(),
            $next = piece.next(),
            siblings = piece.siblings().filter('.clone').length;
        if (siblings > 0) {
            var n = piece.find('input:text[name="sl_layer[text][]"]').filter(function() { return this.value; }).length;
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

    $(document).on('click', '[name="sl_layer[wreck][]"]', function() {
        var $this = $(this),
            check = $this.is(':checked'),
            erase = $this.closest('.piece').find('[name="sl_layer[erase][]"]');

        erase.val(check == true ? '1' : '');
    });
});
</script>

<script>
function fsliderform_submit(f)
{
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
