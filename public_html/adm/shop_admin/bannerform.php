<?php
$sub_menu = '500500';
include_once('./_common.php');
auth_check($auth[$sub_menu], "w");
$bn_id = preg_replace('/[^0-9]/', '', $bn_id);
$html_title = '배너';
$g5['title'] = $html_title.'관리';

if ($w=="u")
{
    $html_title .= ' 수정';
    $sql = " select * from {$g5['g5_shop_banner_table']} where bn_id = '$bn_id' ";
    $bn = sql_fetch($sql);
}
else
{
    $html_title .= ' 입력';
    $bn['bn_url']        = "http://";
    $bn['bn_begin_time'] = date("Y-m-d 00:00:00", time());
    $bn['bn_end_time']   = date("Y-m-d 00:00:00", time()+(60*60*24*31));
}

// 접속기기 필드 추가
if(!sql_query(" select bn_device from {$g5['g5_shop_banner_table']} limit 0, 1 ")) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_banner_table']}`
                    ADD `bn_device` varchar(10) not null default '' AFTER `bn_url` ", true);
    sql_query(" update {$g5['g5_shop_banner_table']} set bn_device = 'pc' ", true);
}

include_once ('../admin.head.php');


//CSS
add_stylesheet('<link rel="stylesheet" href="'.G5_PHP_URL. 'bower_components/bootstrap/dist/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 5);
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL2.'/bootstrap2.custom.css?ver='.G5_CSS_VER.'"></script>',8);
//JS DataTables
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);
?>

<form name="fbanner" action="./bannerformupdate.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="bn_id" value="<?php echo $bn_id; ?>">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
	  <h1 class="member_list_title">
		배너관리
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
						<nav class="navbar navbar-default navbtn" style="z-index: 1000000000000000000000000000000000">
							<div class="text-right">							
								<a href="./bannerlist.php" class="btn_02 btn btn-default">목록</a>
								<input type="submit" value="확인" class="btn_submit btn btn-primary" accesskey="s">					
							</div>
						</nav>
					</div>
					<!-- /.box-header -->
					<div class="box-body">						
						<div class="tbl_frm01 tbl_wrap">
							<table id="example2" class="table table-bordered table-hover dataTable member-form" role="grid" aria-describedby="example2_info">
							<caption><?php echo $g5['title']; ?></caption>
							<colgroup>
								<col class="grid_4">
								<col>
							</colgroup>
							<tbody>
							<tr>
								<th scope="row">이미지</th>
								<td>
									<input type="file" name="bn_bimg">
									<?php
									$bimg_str = "";
									$bimg = G5_DATA_PATH."/banner/{$bn['bn_id']}";
									if (file_exists($bimg) && $bn['bn_id']) {
										$size = @getimagesize($bimg);
										if($size[0] && $size[0] > 750)
											$width = 750;
										else
											$width = $size[0];

										echo '<input type="checkbox" name="bn_bimg_del" value="1" id="bn_bimg_del"> <label for="bn_bimg_del">삭제</label>';
										$bimg_str = '<img src="'.G5_DATA_URL.'/banner/'.$bn['bn_id'].'" width="'.$width.'">';
									}
									if ($bimg_str) {
										echo '<div class="banner_or_img">';
										echo $bimg_str;
										echo '</div>';
									}
									?>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="bn_alt">이미지 설명</label></th>
								<td>
									<?php echo help("img 태그의 alt, title 에 해당되는 내용입니다.\n배너에 마우스를 오버하면 이미지의 설명이 나옵니다."); ?>
									<input type="text" name="bn_alt" value="<?php echo $bn['bn_alt']; ?>" id="bn_alt" class="frm_input form-control" size="80">
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="bn_url">링크</label></th>
								<td>
									<?php echo help("배너클릭시 이동하는 주소입니다."); ?>
									<input type="text" name="bn_url" size="80" value="<?php echo $bn['bn_url']; ?>" id="bn_url" class="frm_input form-control">
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="bn_device">접속기기</label></th>
								<td>
									<?php echo help('배너를 표시할 접속기기를 선택합니다.'); ?>
									<select name="bn_device" id="bn_device" class="form-control">
										<option value="both"<?php echo get_selected($bn['bn_device'], 'both', true); ?>>PC와 모바일</option>
										<option value="pc"<?php echo get_selected($bn['bn_device'], 'pc'); ?>>PC</option>
										<option value="mobile"<?php echo get_selected($bn['bn_device'], 'mobile'); ?>>모바일</option>
								</select>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="bn_position">출력위치</label></th>
								<td>
									<?php echo help("왼쪽 : 쇼핑몰화면 왼쪽에 출력합니다.\n메인 : 쇼핑몰 메인화면(index.php)에만 출력합니다."); ?>
									<select name="bn_position" id="bn_position" class="form-control">
										<option value="왼쪽" <?php echo get_selected($bn['bn_position'], '왼쪽'); ?>>왼쪽</option>
										<option value="메인" <?php echo get_selected($bn['bn_position'], '메인'); ?>>메인</option>
								</select>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="bn_border">테두리</label></th>
								<td>
									 <?php echo help("배너이미지에 테두리를 넣을지를 설정합니다.", 50); ?>
									<select name="bn_border" id="bn_border" class="form-control">
										<option value="0" <?php echo get_selected($bn['bn_border'], 0); ?>>사용안함</option>
										<option value="1" <?php echo get_selected($bn['bn_border'], 1); ?>>사용</option>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="bn_new_win">새창</label></th>
								<td>
									<?php echo help("배너클릭시 새창을 띄울지를 설정합니다.", 50); ?>
									<select name="bn_new_win" id="bn_new_win" class="form-control">
										<option value="0" <?php echo get_selected($bn['bn_new_win'], 0); ?>>사용안함</option>
										<option value="1" <?php echo get_selected($bn['bn_new_win'], 1); ?>>사용</option>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="bn_begin_time">시작일시</label></th>
								<td>
									<?php echo help("배너 게시 시작일시를 설정합니다."); ?>
									<input type="text" name="bn_begin_time" value="<?php echo $bn['bn_begin_time']; ?>" id="bn_begin_time" class="frm_input form-control"  size="21" maxlength="19">
									<input type="checkbox" name="bn_begin_chk" value="<?php echo date("Y-m-d 00:00:00", time()); ?>" id="bn_begin_chk" onclick="if (this.checked == true) this.form.bn_begin_time.value=this.form.bn_begin_chk.value; else this.form.bn_begin_time.value = this.form.bn_begin_time.defaultValue;">
									<label for="bn_begin_chk">오늘</label>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="bn_end_time">종료일시</label></th>
								<td>
									<?php echo help("배너 게시 종료일시를 설정합니다."); ?>
									<input type="text" name="bn_end_time" value="<?php echo $bn['bn_end_time']; ?>" id="bn_end_time" class="frm_input form-control" size=21 maxlength=19>
									<input type="checkbox" name="bn_end_chk" value="<?php echo date("Y-m-d 23:59:59", time()+60*60*24*31); ?>" id="bn_end_chk" onclick="if (this.checked == true) this.form.bn_end_time.value=this.form.bn_end_chk.value; else this.form.bn_end_time.value = this.form.bn_end_time.defaultValue;">
									<label for="bn_end_chk">오늘+31일</label>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="bn_order">출력 순서</label></th>
								<td>
								   <?php echo help("배너를 출력할 때 순서를 정합니다. 숫자가 작을수록 먼저 출력됩니다."); ?>
								   <?php echo order_select("bn_order", $bn['bn_order']); ?>
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
 $(document).scroll(function(e){
    var scrollTop = $(document).scrollTop();
    if(scrollTop > 0){
        console.log(scrollTop);
        $('.navbar').removeClass('navbar-static-top').addClass('navbar-fixed-top');
    } else {
        $('.navbar').removeClass('navbar-fixed-top').addClass('navbar-static-top');
    }
});
//document.getElementById("bn_order").setAttribute("class", "form-control");
$("#bn_order")[0].setAttribute("class", "form-control");
</script>

<?php
include_once ('../footer.php');
?>
