<?php
$sub_menu = '500210';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '가격비교사이트';
include_once ('../admin.head.php');
$pg_anchor = '<ul class="anchor">
<li><a href="#anc_pricecompare_info">가격비교사이트 연동 안내</a></li>
<li><a href="#anc_pricecompare_engine">사이트별 엔진페이지 URL</a></li>
</ul>';


//CSS
add_stylesheet('<link rel="stylesheet" href="'.G5_PHP_URL. 'bower_components/bootstrap/dist/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 5);
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL2.'/bootstrap.custom.css?ver='.G5_CSS_VER.'"></script>',8);
//JS DataTables
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);

?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
		<h1 class="member_list_title">
		가격비교사이트
		<small>price compare</small>
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
					</div>
					<!-- /.box-header -->
					<div class="box-body">	
						<div class="col-md-12">
							<!-- Custom Tabs -->
							<div class="nav-tabs-custom">
								<ul class="nav nav-tabs">
								<li class="active class="bg-light-blue""><a href="#tab_1" data-toggle="tab" aria-expanded="false">가격비교사이트 연동 안내</a></li>
								<li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="true">사이트별 엔진페이지 URL</a></li>								  
								<li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
								</ul>
								<div class="tab-content price">
									<div class="tab-pane active" id="tab_1">
										<div class="local_desc01 local_desc price">
											<ol>
											<li>가격비교사이트는 네이버 지식쇼핑, 다음 쇼핑하우 등이 있습니다.</li>
											<li>앞서 나열한 가격비교사이트 중 희망하시는 사이트에 입점합니다.</li>
											<li><strong>사이트별 엔진페이지 URL</strong>을 참고하여 해당 엔진페이지 URL 을 입점하신 사이트에 알려주시면 됩니다.</li>
											</ol>
										</div>
									</div>
									<!-- /.tab-pane -->
									<div class="tab-pane" id="tab_2">
										<div class="local_desc01 local_desc price">
											<p>사이트 명을 클릭하시면 해당 사이트로 이동합니다.</p>

											<dl class="price_engine">
												<dt><a href="http://shopping.naver.com/" target="_blank">네이버 지식쇼핑</a></dt>
												<dd>
													<ul>
													<li>입점 안내 : <a href="http://join.shopping.naver.com/join/intro.nhn" target="_blank">http://join.shopping.naver.com/join/intro.nhn</a></li>
													<li>전체상품 URL : <a href="<?php echo G5_SHOP_URL; ?>/price/naver.php" target="_blank"><?php echo G5_SHOP_URL; ?>/price/naver.php</a></li>
													<li>요약상품 URL : <a href="<?php echo G5_SHOP_URL; ?>/price/naver_summary.php" target="_blank"><?php echo G5_SHOP_URL; ?>/price/naver_summary.php</a></li>
													</ul>
												</dd>
												<dt><a href="http://shopping.daum.net/" target="_blank">다음 쇼핑하우</a></dt>
												<dd>
													<ul>
													<li>입점 안내 : <a href="http://commerceone.biz.daum.net/join/intro.daum" target="_blank">http://commerceone.biz.daum.net/join/intro.daum</a></li>
													<li>전체상품 URL : <a href="<?php echo G5_SHOP_URL; ?>/price/daum.php" target="_blank"><?php echo G5_SHOP_URL; ?>/price/daum.php</a></li>
													<li>요약상품 URL : <a href="<?php echo G5_SHOP_URL; ?>/price/daum_summary.php" target="_blank"><?php echo G5_SHOP_URL; ?>/price/daum_summary.php</a></li>
													</ul>
												</dd>
											</dl>
										 </div>
									</div>
									<!-- /.tab-pane -->
								</div>
								<!-- /.tab-content -->
							</div>
							<!-- nav-tabs-custom -->
						</div>
						<!-- /.col-md-12 -->
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
