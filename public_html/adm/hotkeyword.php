<?php

ini_set('display_errors', 1); 
ini_set('error_reporting', E_ALL);

$sub_menu = '300250';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '핫키워드관리';
include_once ('./admin.head.php');



/* 1:plugin, 2:theme, 3:skin, 4:closer, 0:external */
add_javascript('<script src="'.G5_PLUGIN_URL.'/jquery-ui-1.12.1/jquery-ui.min.js"></script>', 1);
add_javascript('<script src="'.G5_PLUGIN_URL.'/jquery-ui-1.12.1/jquery-ui-datepicker.set.defaults.js"></script>', 1);
add_javascript('<script src="'.G5_PLUGIN_URL.'/jquery-ui-1.12.1/external/globalize/globalize.js"></script>', 1);
add_javascript('<script src="'.G5_PLUGIN_URL.'/jquery-ui-1.12.1/external/jquery-mousewheel/jquery.mousewheel.js"></script>', 1);
add_stylesheet('<link rel="stylesheet" href="'.G5_PLUGIN_URL.'/jquery-ui-1.12.1/jquery-ui.min.css" />', 1);

add_javascript('<script src="'.G5_PLUGIN_URL.'/jquery-ui-timepicker/dist/jquery-ui-timepicker-addon.js"></script>', 1);
add_javascript('<script src="'.G5_PLUGIN_URL.'/jquery-ui-timepicker/dist/i18n/jquery-ui-timepicker-ko.js"></script>', 1);
add_javascript('<script src="'.G5_PLUGIN_URL.'/jquery-ui-timepicker/dist/jquery-ui-sliderAccess.js"></script>', 1);
add_stylesheet('<link rel="stylesheet" href="'.G5_PLUGIN_URL.'/jquery-ui-timepicker/dist/jquery-ui-timepicker-addon.css">', 1);

//add_javascript('<script src="'.G5_PLUGIN_URL.'/mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js"></script>', 1);
//add_stylesheet('<link rel="stylesheet" href="'.G5_PLUGIN_URL.'/mCustomScrollbar/jquery.mCustomScrollbar.css">', 1);


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
		핫키워드관리
		<small>hotkeyword</small>
		</h1>
	<ol class="breadcrumb">
	<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	<li><a href="#">회원관리</a></li>
	<li class="active">회원정보리스트</li>
	</ol>
	</section>
	
	<form name="member_delete" method="post" action="./member_list_update.php" onsubmit="return fmemberlist_submit(this);">

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<nav class="navbar navbar-default navbtn">
							<div class="text-right">							
								<input type="button" value="저장" class="btn btn-primary btn_01" onclick="this.form.submit();" accesskey="s">						
							</div>
						</nav>
					</div>
					<!-- /.box-header -->

					<div class="box-body hotkeyword">
					
						<form name="fhotkeyword" id="fhotkeyword" action="./hotkeyword_update.php" onsubmit="return fhotkeyword_submit(this);" method="post" enctype="MULTIPART/FORM-DATA">
							<input type="hidden" name="w" value="u" />
							<input type="hidden" name="token" value="<?php echo get_admin_token(); ?>" />

							<div class="stack stack-table">
								<div class="group">
									<h3 style="position:relative"><i class="fa fa-list fa-lg" aria-hidden="true"></i> 핫키워드</h3>
									<div class="entry">
										<?php
											$hot = get_hotkeyword(true,false,0);
											for ($i=0; $i<count($hot); $i++) {
												echo '<div class="piece">';
												echo '<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>';
												echo '<input type="hidden" name="stack[]" value="" />';
												echo '<input type="hidden" name="erase[]" value="" />';
												echo '<input type="text" name="keyword[]" value="'.get_text($hot[$i][1]).'" placeholder="키워드" class="frm_input word form-control" /> ';
												echo '<select name="switch[]" class="form-control"><option value="1" '.get_selected($hot[$i][0], '1').'>보이기</option><option value="0" '.get_selected($hot[$i][0], '0').'>감추기</option></select> ';
												echo '<button type="button" class="btn btn-info ssWhite ssBgBlue insertStack">추가</button> ';
												echo '<button type="button" class="btn btn-danger ssWhite ssBgRed deleteStack">제거</button> ';
												echo '</div>';
											}

											// clone
											{
												echo '<div class="piece clone">';
												echo '<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>';
												echo '<input type="hidden" name="stack[]" value="" />';
												echo '<input type="hidden" name="erase[]" value="" />';
												echo '<input type="text" name="keyword[]" value="" placeholder="키워드" class="frm_input word form-control" /> ';
												echo '<select name="switch[]" class="form-control"><option value="1">보이기</option><option value="0">감추기</option></select> ';
												echo '<button type="button" class="btn btn-info ssWhite ssBgBlue insertStack">추가</button> ';
												echo '<button type="button" class="btn btn-danger ssWhite ssBgRed deleteStack">제거</button> ';
												echo '</div>';
											}
										?>
									</div>
								</div>
								<div class="group">
									<h3 style="position:relative; white-space : nowrap;"><i class="fa fa-list fa-lg" aria-hidden="true"></i> 인기검색어 TOP10 (최근 한달)</h3>
									<div class="refer">
										<?php
											$sql = "select pp_word, count(*) as cnt 
													from {$g5['popular_table']} a 
													where trim(pp_word) <> '' 
														and pp_date > date_add(curdate(), interval -1 month)
														and (pp_word not in ('".implode("','", array_column($hot, 1))."') or 1) 
													group by pp_word 
													order by cnt desc 
													limit 10 ";
											$res = sql_query($sql);
											for ($i=0; $row=sql_fetch_array($res); $i++) {
												echo '<div class="piece">';
												echo '<input type="text" class="frm_input ssAlignR" style="width:30px" value="'.($i+1).'" readonly /> ';
												echo '<input type="text" class="frm_input" value="'.get_text($row['pp_word']).'" readonly /> ';
												echo '<input type="text" class="frm_input ssAlignR" style="width:40px" value="'.intval($row['cnt']).'" readonly /> 회 검색 ';
												echo '</div>';
											}
										?>
									</div>
								</div>
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
	  </form>
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
</script>


<?php
    include_once('./admin.tail.php');
?>