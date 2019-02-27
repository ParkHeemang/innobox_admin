<?php

$sub_menu = "100110";
include_once('./_common.php');


auth_check($auth[$sub_menu], 'r');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');


$g5['title'] = '기타환경설정';
include_once ('./admin.head.php');

/* 1:plugin, 2:theme, 3:skin, 4:closer, 0:external */
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js
add_javascript(G5_DAUMMAPS_JS, 0);    //다음 지도 js

$pg_anchor = '
<ul class="anchor">
    <li><a href="#anc_cf_meta">메타태그설정</a></li>
    <li><a href="#anc_cf_imgs">이미지업로드</a></li>
    <li><a href="#anc_cf_info">업체정보설정</a></li>
    <li><a href="#anc_cf_sns">SNS</a></li>
</ul>';



//JS DataTables
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js	


add_javascript('<script src="'.G5_JS_URL.'/common.js?ver='.G5_JS_VER.'"></script>', 6);



?>
<!-- <link rel="stylesheet" href="http://test.innobox.co.kr/admlte2/bower_components/bootstrap/dist/css/dataTables.bootstrap.min.css?ver=171222">
<link rel="stylesheet" href="http://test.innobox.co.kr/admlte2/css/bootstrap2.custom.css?ver=171222">
<script src="http://test.innobox.co.kr/admlte2//bower_components/datatables.net/js/jquery.dataTables.min.js?ver=171222"></script>
<script src="http://test.innobox.co.kr/admlte2//bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js?ver=171222"></script> -->


<form name="fconfigform" id="fconfigform"  method="post" onsubmit="return fconfigform_submit(this);" enctype="multipart/form-data">
<input type="hidden" name="token" value="" id="token">
<input type="hidden" name="current_tab" value="" id="current_tab">


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
		<!-- Content Header (Page header) -->    
		<section class="content-header configform">
			<nav class="navbar navbar-default navbtn configform">
				<div class="nav-tabs-custom configform">
					<ul class="nav nav-tabs" id="tab_ul">                 
					  <li class="active"><a href="#tab_1" data-toggle="tab">메타태그설정</a></li>
					  <li><a href="#tab_2" data-toggle="tab">이미지업로드</a></li>
					  <li><a href="#tab_3" data-toggle="tab">업체정보설정</a></li>
					  <li><a href="#tab_4" data-toggle="tab">SNS</a></li>
					
					  	<div class="text-right">
						<input type="submit" value="확인" class="btn btn_01 btn-primary" accesskey="s" onclick = "check_requireds();">
						<a href="'.G5_URL.'/"><button class="btn btn_02 btn-danger">메인으로 </button></a>
						</div>
					
					</ul>
				</div>
			</nav>
		</section>
		

		<div class="btn_fixed_top">
    
</div>

	<!-- Main content -->	
	<section class="content configform">
	  <div class="row">
		<div class="col-xs-12">
			<div class="box noBorder">
				<div class="box-body">							
					<!-- tab-content -->
					<div class="tab-content">	
						<div class="tab-pane active" id="tab_1">
							<table id="example2" class="table table-bordered table-hover dataTable config_form">
								<!-- <caption>메타태그설정</caption> -->
								<colgroup>
									<col class="grid_4">
									<col>
									<col class="grid_4">
									<col>
								</colgroup>
								<tbody>
								<tr>
									<th scope="row"><label for="cf_title">홈페이지 제목<strong class="sound_only">필수</strong></label></th>
									<td colspan="3"><input type="text" name="cf_title" class="required frm_input form-control" value="<?php echo $config['cf_title'] ?>" id="cf_title" required size="35"></td>
								</tr>
								<tr>
									<th scope="row"><label for="cf_seo_author">메타태그1 : Author</label></th>
									<td colspan="3"><input type="text" name="cf_seo_author" id="cf_seo_author" class="frm_input form-control w-100" value="<?php echo $config['cf_seo_author'] ?>" /></td>
								</tr>
								<tr>
									<th scope="row"><label for="cf_seo_description">메타태그2 : Description</label></th>
									<td colspan="3"><input type="text" name="cf_seo_description" id="cf_seo_description" class="frm_input form-control w-100" value="<?php echo $config['cf_seo_description'] ?>" /></td>
								</tr>
								<tr>
									<th scope="row"><label for="cf_seo_keywords">메타태그3 : Keywords</label></th>
									<td colspan="3"><input type="text" name="cf_seo_keywords" id="cf_seo_keywords" class="frm_input form-control w-100" value="<?php echo $config['cf_seo_keywords'] ?>" /></td>
								</tr>
								<tr>
									<th scope="row"><label for="cf_add_meta">추가 메타태그</label></th>
									<td colspan="3">
										<?php echo help('추가로 사용하실 meta 태그를 입력합니다. (<a href="http://ogp.me/" target="_blank"><u>오픈 그래프</u></a>, <a href="http://webmastertool.naver.com/guide/basic_markup.naver" target="_blank"><u>네이버 가이드</u></a>)<br>&lt;meta name="naver-site-verification" content="" /&gt;'); ?>
										<textarea name="cf_add_meta" id="cf_add_meta"><?php echo $config['cf_add_meta']; ?></textarea>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="cf_syndi_token">네이버 신디케이션<br />연동키</label></th>
									<td colspan="3">
										<?php if (!function_exists('curl_init')) echo help('<b>경고) curl이 지원되지 않아 네이버 신디케이션을 사용할수 없습니다.</b>'); ?>
										<?php echo help('네이버 신디케이션 연동키(token)을 입력하면 네이버 신디케이션을 사용할 수 있습니다.<br>연동키는 <a href="http://webmastertool.naver.com/" target="_blank"><u>네이버 웹마스터도구</u></a> -> 네이버 신디케이션에서 발급할 수 있습니다.') ?>
										<input type="text" name="cf_syndi_token" value="<?php echo $config['cf_syndi_token'] ?>" id="cf_syndi_token" class="frm_input form-control" size="70">
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="cf_syndi_except">네이버 신디케이션<br />제외게시판</label></th>
									<td colspan="3">
										<?php echo help('네이버 신디케이션 수집에서 제외할 게시판 아이디를 | 로 구분하여 입력하십시오. 예) notice|adult<br>참고로 그룹접근사용 게시판, 글읽기 권한 2 이상 게시판, 비밀글은 신디케이션 수집에서 제외됩니다.') ?>
										<input type="text" name="cf_syndi_except" value="<?php echo $config['cf_syndi_except'] ?>" id="cf_syndi_except" class="frm_input form-control" size="70">
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="cf_add_script">추가 script, css</label></th>
									<td>
										<?php echo help('HTML의 &lt;/HEAD&gt; 태그위로 추가될 JavaScript와 css 코드를 설정합니다.<br>관리자 페이지에서는 이 코드를 사용하지 않습니다.') ?>
										<textarea name="cf_add_script" id="cf_add_script"><?php echo get_text($config['cf_add_script']); ?></textarea>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="cf_analytics">방문자분석 스크립트</label></th>
									<td colspan="3">
										<?php echo help('방문자분석 스크립트 코드를 입력합니다. 예) 구글 애널리틱스'); ?>
										<textarea name="cf_analytics" id="cf_analytics"><?php echo $config['cf_analytics']; ?></textarea>
									</td>
								</tr>
								</tbody>
							</table>
						</div>
						<div class="tab-pane" id="tab_2">
							<table id="example2" class="table table-bordered table-hover dataTable config_form">
								<!-- <caption>이미지업로드</caption> -->
								<colgroup>
									<col class="grid_4">
									<col>
									<col class="grid_4">
									<col>
								</colgroup>
								<tbody>
								<tr>
									<th scope="row">파비콘</th>
									<td>
										<?php echo help(" 파비콘를 직접 올릴 수 있습니다. 이미지 파일만 가능합니다."); ?>
										<input type="file" name="ico_img" id="ico_img">
										<?php
										$ico_img = G5_PATH."/favicon.ico";
										if (file_exists($ico_img)) {
											$size = getimagesize($ico_img);
											$base64 = 'data:image/'.pathinfo($ico_img, PATHINFO_EXTENSION).';base64,'.base64_encode(file_get_contents($ico_img));
										?>
										<!-- 
										<input type="checkbox" name="ico_img_del" value="1" id="ico_img_del">
										<label for="ico_img_del"><span class="sound_only">파비콘</span> 삭제</label> -->
										<?php } ?>
									</td>
									<td colspan="2" class="text-right">
										<?php if (file_exists($ico_img)) { ?>
											<img src="<?php echo $base64; ?>" alt="favicon.ico" style="max-height:50px;">
										<?php } ?>
									</td>
								</tr>
								<tr>
									<th scope="row">상단로고이미지</th>
									<td>
										<?php echo help(" 상단로고를 직접 올릴 수 있습니다. 이미지 파일만 가능합니다."); ?>
										<input type="file" name="logo_img" id="logo_img">
										<?php
										$logo_img = G5_DATA_PATH."/common/logo_img";
										if (file_exists($logo_img)) {
											$size = getimagesize($logo_img);
											$base64 = 'data:image/'.pathinfo($logo_img, PATHINFO_EXTENSION).';base64,'.base64_encode(file_get_contents($logo_img));
										?>
										<input type="checkbox" name="logo_img_del" value="1" id="logo_img_del">
										<label for="logo_img_del"><span class="sound_only">상단로고이미지</span> 삭제</label>
										<?php } ?>
									</td>
									<td colspan="2" class="text-right">
										<?php if (file_exists($logo_img)) { ?>
											<img src="<?php echo $base64; ?>" alt="logo_img" style="max-height:50px;">
										<?php } ?>
									</td>
								</tr>
								<tr>
									<th scope="row">하단로고이미지</th>
									<td>
										<?php echo help(" 하단로고를 직접 올릴 수 있습니다. 이미지 파일만 가능합니다."); ?>
										<input type="file" name="logo_img2" id="logo_img2">
										<?php
										$logo_img2 = G5_DATA_PATH."/common/logo_img2";
										if (file_exists($logo_img2)) {
											$size = getimagesize($logo_img2);
											$base64 = 'data:image/'.pathinfo($logo_img2, PATHINFO_EXTENSION).';base64,'.base64_encode(file_get_contents($logo_img2));
										?>
										<input type="checkbox" name="logo_img_del2" value="1" id="logo_img_del2">
										<label for="logo_img_del2"><span class="sound_only">하단로고이미지</span> 삭제</label>
										<?php } ?>
									</td>
									<td colspan="2" class="text-right">
										<?php if (file_exists($logo_img2)) { ?>
											<img src="<?php echo $base64; ?>" alt="logo_img2" style="max-height:50px;">
										<?php } ?>
									</td>
								</tr>
								<tr>
									<th scope="row">모바일 상단로고이미지</th>
									<td>
										<?php echo help("모바일  상단로고를 직접 올릴 수 있습니다. 이미지 파일만 가능합니다."); ?>
										<input type="file" name="mobile_logo_img" id="mobile_logo_img">
										<?php
										$mobile_logo_img = G5_DATA_PATH."/common/mobile_logo_img";
										if (file_exists($mobile_logo_img)) {
											$size = getimagesize($mobile_logo_img);
											$base64 = 'data:image/'.pathinfo($mobile_logo_img, PATHINFO_EXTENSION).';base64,'.base64_encode(file_get_contents($mobile_logo_img));
										?>
										<input type="checkbox" name="mobile_logo_img_del" value="1" id="mobile_logo_img_del">
										<label for="mobile_logo_img_del"><span class="sound_only">모바일 상단로고이미지</span> 삭제</label>
										<?php } ?>
									</td>
									<td colspan="2" class="text-right">
										<?php if (file_exists($mobile_logo_img)) { ?>
											<img src="<?php echo $base64; ?>" alt="mobile_logo_img" style="max-height:50px;">
										<?php } ?>
									</td>
								</tr>


								</tbody>
							</table>
						</div>
						<div class="tab-pane" id="tab_3">
							<table id="example2" class="table table-bordered table-hover dataTable config_form">
								<caption>업체정보설정</caption>
								<colgroup>
									<col class="grid_4">
									<col>
									<col class="grid_4">
									<col>
								</colgroup>
								<tbody>
								<tr>
									<th scope="row"><label for="cf_company_name">회사명</label></th>
									<td><input type="text" name="cf_company_name" id="cf_company_name" class="frm_input form-control" value="<?php echo $config['cf_company_name']; ?>" size="35"></td>
									<th scope="row"><label for="cf_company_saupja_no">사업자등록번호</label></th>
									<td><input type="text" name="cf_company_saupja_no" id="cf_company_saupja_no" class="frm_input form-control" value="<?php echo $config['cf_company_saupja_no']; ?>" size="35"></td>
								</tr>
								<tr>
									<th scope="row"><label for="cf_company_owner">대표자명</label></th>
									<td><input type="text" name="cf_company_owner" id="cf_company_owner" class="frm_input form-control" value="<?php echo $config['cf_company_owner']; ?>" size="35"></td>
									<th scope="row"><label for="cf_company_open1">영업시간</label></th>
									<td>
										<?php
										$open_time = explode(":", $config['cf_company_open']);
										$close_time = explode(":", $config['cf_company_close']);
										?>
										<!-- 영업시작 시간 -->
										<select name="cf_company_open1" id="cf_company_open1">
											<?php
												for ($i=0; $i<24; $i++) {
													$j = sprintf('%02d', $i);
													echo '<option value="'.$j.'" '.get_selected($j, $open_time[0]).'>'.$j.'</option>';
												}
											?>
										</select> :
										<select name="cf_company_open2" id="cf_company_open2">
											<?php
												for ($i=0; $i<60; $i+=5) {
													$j = sprintf('%02d', $i);
													echo '<option value="'.$j.'" '.get_selected($j, $open_time[1]).'>'.$j.'</option>';
												}
											?>
										</select> ~
										<!-- 영업종료 시간 -->
										<select name="cf_company_close1" id="cf_company_close1">
											<?php
												for ($i=0; $i<24; $i++) {
													$j = sprintf('%02d', $i);
													echo '<option value="'.$j.'" '.get_selected($j, $close_time[0]).'>'.$j.'</option>';
												}
											?>
										</select> :
										<select name="cf_company_close2" id="cf_company_close2">
											<?php
												for ($i=0; $i<60; $i+=5) {
													$j = sprintf('%02d', $i);
													echo '<option value="'.$j.'" '.get_selected($j, $close_time[1]).'>'.$j.'</option>';
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="cf_company_tel">대표전화번호</label></th>
									<td><input type="text" name="cf_company_tel" id="cf_company_tel" class="frm_input form-control" value="<?php echo $config['cf_company_tel']; ?>" size="35"></td>
									<th scope="row"><label for="cf_company_fax">팩스번호</label></th>
									<td><input type="text" name="cf_company_fax" id="cf_company_fax" class="frm_input form-control" value="<?php echo $config['cf_company_fax']; ?>" size="35"></td>
								</tr>
								<tr>
									<th scope="row"><label for="cf_company_tongsin_no">통신판매업 신고번호</label></th>
									<td><input type="text" name="cf_company_tongsin_no" id="cf_company_tongsin_no" class="frm_input form-control" value="<?php echo $config['cf_company_tongsin_no']; ?>" size="35"></td>
									<th scope="row"><label for="cf_company_buga_no">부가통신 사업자번호</label></th>
									<td><input type="text" name="cf_company_buga_no" id="cf_buga_no" class="frm_input form-control" value="<?php echo $config['cf_company_buga_no']; ?>" size="35"></td>
								</tr>
								<tr>
									<th scope="row"><label for="cf_faoinfo_name">정보관리책임자명</label></th>
									<td><input type="text" name="cf_faoinfo_name" id="cf_faoinfo_name" class="frm_input form-control" value="<?php echo $config['cf_faoinfo_name']; ?>" size="35"></td>
									<th scope="row"><label for="cf_faoinfo_email">정보책임자 e-mail</label></th>
									<td><input type="text" name="cf_faoinfo_email" id="cf_faoinfo_email" class="frm_input form-control" value="<?php echo $config['cf_faoinfo_email']; ?>" size="35"></td>
								</tr>
								<tr>
									<th scope="row"><label for="cf_company_zip">주소</label></th>
									<td class="td_addr_line">
										<label for="cf_company_zip" class="sound_only">우편번호</label>
										<input type="text" name="cf_company_zip" value="<?php echo $config['cf_company_zip'] ?>" id="cf_company_zip" class="frm_input form-control readonly" size="5" maxlength="5" readonly>
										<button type="button" class="btn btn-default" onclick="dmap('cf_company_zip', 'cf_company_addr1', 'cf_company_addr2', 'cf_company_addr3', 'cf_company_addr_jibeon');">주소 검색</button><br>
										<input type="text" name="cf_company_addr1" value="<?php echo $config['cf_company_addr1'] ?>" id="cf_company_addr1" class="frm_input form-control readonly" size="35" readonly>
										<label for="cf_company_addr1">기본주소</label><br>
										<input type="text" name="cf_company_addr2" value="<?php echo $config['cf_company_addr2'] ?>" id="cf_company_addr2" class="frm_input form-control" size="35">
										<label for="cf_company_addr2">상세주소</label><br>
										<input type="text" name="cf_company_addr3" value="<?php echo $config['cf_company_addr3'] ?>" id="cf_company_addr3" class="frm_input form-control" size="35" readonly>
										<label for="cf_company_addr3">참고항목</label><br>
										<input type="hidden" name="cf_company_addr_jibeon" value="<?php echo $config['cf_company_addr_jibeon']; ?>">
									</td>
									<th scope="row"><label for="cf_sns">SNS</label></th>
									<td class="td_addr_line">
										<input type="text" name="cf_sns_blog" id="cf_sns_blog" class="frm_input form-control" value="<?php echo $config['cf_sns_blog']; ?>" size="35" placeholder="http://" /> 
										<label for="cf_sns_blog">블로그</label><br>
										<input type="text" name="cf_sns_twitter" id="cf_sns_twitter" class="frm_input form-control" value="<?php echo $config['cf_sns_twitter']; ?>" size="35" placeholder="http://" /> 
										<label for="cf_sns_twitter">트위터</label><br>
										<input type="text" name="cf_sns_facebook" id="cf_sns_facebook" class="frm_input form-control" value="<?php echo $config['cf_sns_facebook']; ?>" size="35" placeholder="http://" /> 
										<label for="cf_sns_facebook">페이스북</label><br>
										<input type="text" name="cf_sns_instagram" id="cf_sns_instagram" class="frm_input form-control" value="<?php echo $config['cf_sns_instagram']; ?>" size="35" placeholder="http://" /> 
										<label for="cf_sns_instagram">인스타그램</label><br>
									</td>
								</tr>
								<tr>
									<th><label for="cf_company_lat">위도</label></th>
									<td><input type="text" name="cf_company_lat" id="cf_company_lat" class="frm_input form-control" value="<?php echo $config['cf_company_lat']; ?>" size="35" <?php echo empty($config['cf_kakao_js_apikey']) ? '' : ''; ?> /></td>
									<th><label for="cf_company_lng">경도</label></th>
									<td><input type="text" name="cf_company_lng" id="cf_company_lng" class="frm_input form-control" value="<?php echo $config['cf_company_lng']; ?>" size="35" <?php echo empty($config['cf_kakao_js_apikey']) ? '' : ''; ?> /></td>
								</tr>
								<tr<?php echo empty($config['cf_kakao_js_apikey']) ? ' class="d-none"' : ''; ?>>
									<th scope="row"><label for="map">약도</label></th>
									<td colspan="3"><div id="map" style="width:100%;height:300px;"></div></td>
								</tr>
								<tr>
									<th scope="row"><label for="cf_company_loc">오시는 길</label></th>
									<td colspan="3">
										<textarea name="cf_company_loc" id="cf_company_loc" cols="60" rows="5"><?php echo $config['cf_company_loc']; ?></textarea>
									</td>
								</tr>
								</tbody>
							</table>
						</div>
						<div class="tab-pane" id="tab_4">
							<table id="example2" class="table table-bordered table-hover dataTable config_more">
								<caption>소셜네트워크서비스 설정</caption>
								<colgroup>
									<col class="grid_4">
									<col>
									<col class="grid_4">
									<col>
								</colgroup>
								<tbody>
								<tr>
									<th scope="row"><label for="cf_social_login_use">소셜로그인설정</label></th>
									<td colspan="3">
										<?php echo help('소셜로그인을 사용합니다.') ?>
										<input type="checkbox" name="cf_social_login_use" value="1" id="cf_social_login_use" <?php echo (!empty($config['cf_social_login_use']))?'checked':''; ?>> 
										<label for="cf_social_login_use">사용</label>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="cf_social_servicelist">소셜로그인설정</label></th>
									<td colspan="3" class="social_config_explain">
										<div class="explain_box alert alert-warning">
											<input type="checkbox" name="cf_social_servicelist[]" id="check_social_naver" value="naver" <?php echo option_array_checked('naver', $config['cf_social_servicelist']); ?> >
											<label for="check_social_naver">네이버 로그인을 사용합니다</label>
											<hr>
											<div>
												<h3>네이버 CallbackURL</h3>
												<p><?php echo get_social_callbackurl('naver'); ?></p>
											</div>
										</div>
										<div class="explain_box alert alert-warning">
											<input type="checkbox" name="cf_social_servicelist[]" id="check_social_kakao" value="kakao" <?php echo option_array_checked('kakao', $config['cf_social_servicelist']); ?> >
											<label for="check_social_kakao">카카오 로그인을 사용합니다</label>
											<hr>
											<div>
												<h3>카카오 웹 Redirect Path</h3>
												<p><?php echo get_social_callbackurl('kakao', true); ?></p>
											</div>
										</div>
										<div class="explain_box alert alert-warning">
											<input type="checkbox" name="cf_social_servicelist[]" id="check_social_facebook" value="facebook" <?php echo option_array_checked('facebook', $config['cf_social_servicelist']); ?> >
											<label for="check_social_facebook">페이스북 로그인을 사용합니다</label>
											<hr>
											<div>
												<h3>페이스북 유효한 OAuth 리디렉션 URI</h3>
												<p><?php echo get_social_callbackurl('facebook'); ?></p>
											</div>
										</div>
										<div class="explain_box alert alert-warning">
											<input type="checkbox" name="cf_social_servicelist[]" id="check_social_google" value="google" <?php echo option_array_checked('google', $config['cf_social_servicelist']); ?> >
											<label for="check_social_google">구글 로그인을 사용합니다</label>
											<hr>
											<div>
												<h3>구글 승인된 리디렉션 URI</h3>
												<p><?php echo get_social_callbackurl('google'); ?></p>
											</div>
										</div>
										<div class="explain_box alert alert-warning">
											<input type="checkbox" name="cf_social_servicelist[]" id="check_social_twitter" value="twitter" <?php echo option_array_checked('twitter', $config['cf_social_servicelist']); ?> >
											<label for="check_social_twitter">트위터 로그인을 사용합니다</label>
											<hr>
											<div>
												<h3>트위터 CallbackURL</h3>
												<p><?php echo get_social_callbackurl('twitter'); ?></p>
											</div>
										</div>
										<div class="explain_box alert alert-warning">
											<input type="checkbox" name="cf_social_servicelist[]" id="check_social_payco" value="payco" <?php echo option_array_checked('payco', $config['cf_social_servicelist']); ?> >
											<label for="check_social_payco">페이코 로그인을 사용합니다</label>
											<hr>
											<div>
												<h3>페이코 CallbackURL</h3>
												<p><?php echo get_social_callbackurl('payco'); ?></p>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="cf_naver_clientid">네이버 Client ID</label></th>
									<td>
										<input type="text" name="cf_naver_clientid" value="<?php echo $config['cf_naver_clientid'] ?>" id="cf_naver_clientid" class="frm_input form-control" size="40"> <a href="https://developers.naver.com/apps/#/register" target="_blank" class="btn_frmline btn btn-default">앱 등록</a>
									</td>
									<th scope="row"><label for="cf_naver_secret">네이버 Client Secret</label></th>
									<td>
										<input type="text" name="cf_naver_secret" value="<?php echo $config['cf_naver_secret'] ?>" id="cf_naver_secret" class="frm_input form-control" size="45">
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="cf_facebook_appid">페이스북 앱 ID</label></th>
									<td>
										<input type="text" name="cf_facebook_appid" value="<?php echo $config['cf_facebook_appid'] ?>" id="cf_facebook_appid" class="frm_input form-control" size="40"> <a href="https://developers.facebook.com/apps" target="_blank" class="btn_frmline btn btn-default">앱 등록</a>
									</td>
									<th scope="row"><label for="cf_facebook_secret">페이스북 앱 Secret</label></th>
									<td>
										<input type="text" name="cf_facebook_secret" value="<?php echo $config['cf_facebook_secret'] ?>" id="cf_facebook_secret" class="frm_input form-control" size="45">
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="cf_twitter_key">트위터 컨슈머 Key</label></th>
									<td>
										<input type="text" name="cf_twitter_key" value="<?php echo $config['cf_twitter_key'] ?>" id="cf_twitter_key" class="frm_input form-control" size="40"> <a href="https://dev.twitter.com/apps" target="_blank" class="btn_frmline btn btn-default">앱 등록</a>
									</td>
									<th scope="row"><label for="cf_twitter_secret">트위터 컨슈머 Secret</label></th>
									<td>
										<input type="text" name="cf_twitter_secret" value="<?php echo $config['cf_twitter_secret'] ?>" id="cf_twitter_secret" class="frm_input form-control" size="45">
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="cf_google_clientid">구글 Client ID</label></th>
									<td>
										<input type="text" name="cf_google_clientid" value="<?php echo $config['cf_google_clientid'] ?>" id="cf_google_clientid" class="frm_input form-control" size="40"> <a href="https://console.developers.google.com" target="_blank" class="btn_frmline btn btn-default">앱 등록</a>
									</td>
									<th scope="row"><label for="cf_google_secret">구글 Client Secret</label></th>
									<td>
										<input type="text" name="cf_google_secret" value="<?php echo $config['cf_google_secret'] ?>" id="cf_google_secret" class="frm_input form-control" size="45">
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="cf_googl_shorturl_apikey">구글 짧은주소 API Key</label></th>
									<td colspan="3">
										<input type="text" name="cf_googl_shorturl_apikey" value="<?php echo $config['cf_googl_shorturl_apikey'] ?>" id="cf_googl_shorturl_apikey" class="frm_input form-control" size="40"> <a href="http://code.google.com/apis/console/" target="_blank" class="btn_frmline btn btn-default">API Key 등록</a>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="cf_kakao_rest_key">카카오 REST API 키</label></th>
									<td>
										<input type="text" name="cf_kakao_rest_key" value="<?php echo $config['cf_kakao_rest_key'] ?>" id="cf_kakao_rest_key" class="frm_input form-control" size="40"> <a href="https://developers.kakao.com/apps/new" target="_blank" class="btn_frmline btn btn-default">앱 등록</a>
									</td>
									<th scope="row"><label for="cf_kakao_client_secret">카카오 Client Secret</label></th>
									<td>
										<input type="text" name="cf_kakao_client_secret" value="<?php echo $config['cf_kakao_client_secret'] ?>" id="cf_kakao_client_secret" class="frm_input form-control" size="45">
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="cf_kakao_js_apikey">카카오 JavaScript 키</label></th>
									<td colspan="3">
										<input type="text" name="cf_kakao_js_apikey" value="<?php echo $config['cf_kakao_js_apikey'] ?>" id="cf_kakao_js_apikey" class="frm_input form-control" size="45">
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="cf_payco_clientid">페이코 Client ID</label></th>
									<td>
										<input type="text" name="cf_payco_clientid" value="<?php echo $config['cf_payco_clientid']; ?>" id="cf_payco_clientid" class="frm_input form-control" size="40"> <a href="https://developers.payco.com/guide" target="_blank" class="btn_frmline btn btn-default">앱 등록</a>
									</td>
									<th scope="row"><label for="cf_payco_secret">페이코 Secret</label></th>
									<td>
										<input type="text" name="cf_payco_secret" value="<?php echo $config['cf_payco_secret']; ?>" id="cf_payco_secret" class="frm_input form-control" size="45">
									</td>
								</tr>
								</tbody>
							</table>
						</div>
					</div>
					<!-- /.tab-content -->
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



/*입력 안된 탭으로 가기 위한 스크립트*/

//InternetExlplorer Closest함수 지원
if (!Element.prototype.matches)
    Element.prototype.matches = Element.prototype.msMatchesSelector || 
                                Element.prototype.webkitMatchesSelector;

if (!Element.prototype.closest) {
    Element.prototype.closest = function(s) {
        var el = this;
        if (!document.documentElement.contains(el)) return null;
        do {
            if (el.matches(s)) return el;
            el = el.parentElement || el.parentNode;
        } while (el !== null && el.nodeType === 1); 
        return null;
    };
}

//확인버튼에 onsubmit에 추가요망

function check_requireds(){

lis = $("#tab_ul li");
tab_panes = $(".tab-pane");
Requireds = $(".required");



Requireds.each(function(i){
	if(Requireds[i].value===""){
		closest_div = Requireds[i].closest("div.tab-pane");
		required_index = tab_panes.index(closest_div);


		lis.each(function(li_index){
			if(required_index===li_index){		
				lis[li_index].className="active"
			}else{
				lis[li_index].className="";
			}
		});

	tab_panes.map(function(tab_index){
		if(required_index===tab_index){		
			tab_panes[tab_index].className="tab-pane active"
			}else{
			tab_panes[tab_index].className="tab-pane";
			}
		});

	return false;
	}

});

}

/*--입력 안된 탭으로 가기 위한 스크립트--*/

function fconfigform_submit(f)
{
	for (var i=0; i < lis.length; i++){															//현재 탭 보내기 추가
			if(lis[i].className==="active"){												//li의 class가 active이면
				document.getElementById("current_tab").value = i;			//input의 value값을 지정
			};
		}

    f.action = "./config_more_update.php";
    return true;
}


//config_more_update.php로부터 보낸 post값을 다시 post로 받아와서 해당 탭 열기
//input(hidden) 추가 요망

var tab_ul = document.getElementById("tab_ul");
var lis = tab_ul.getElementsByTagName("li");
var tab_panes = document.getElementsByClassName("tab-pane");
var current_tab = "<?php echo $_POST['current_tab']?>";



for (var i=0; i < lis.length; i++){												//확인 버튼도 li의 모임 lis배열에 포함되기 때문에 lis-length-1을 해준다. -> 버튼 빼면서 복구
		if(i==current_tab){
			lis[i].className="active";
			tab_panes[i].className="tab-pane active";					
		}   
		else{
			lis[i].className="";
			tab_panes[i].className="tab-pane";				
		}
}


var ul_width=0;
for(var i=0; i< lis.length; i++){
    ul_width+=lis[i].offsetWidth;}

ul_width+= 90;




$(document).scroll(function(e){
    var scrollTop = $(document).scrollTop();
    if(scrollTop > 0){
        console.log(scrollTop);
        $('.navbar').removeClass('navbar-static-top').addClass('navbar-fixed-top');
    } else {
        $('.navbar').removeClass('navbar-fixed-top').addClass('navbar-static-top');
    }
});

	$("#tab_ul li").on("click", function(){
	document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
	});


function dmap(cf_company_zencode, cf_company_fulladdr,cf_company_detailAddr,cf_company_extraAddr){
		
		new daum.Postcode({
        oncomplete: function(data) {
            // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분입니다.
            // 예제를 참고하여 다양한 활용법을 확인해 보세요.

			 // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var fullAddr = ''; // 최종 주소 변수
                var extraAddr = ''; // 조합형 주소 변수

                // 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                    fullAddr = data.roadAddress;

                } else { // 사용자가 지번 주소를 선택했을 경우(J)
                    fullAddr = data.jibunAddress;
                }

                // 사용자가 선택한 주소가 도로명 타입일때 조합한다.
                if(data.userSelectedType === 'R'){
                    //법정동명이 있을 경우 추가한다.
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있을 경우 추가한다.
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                document.getElementById(cf_company_zencode).value = data.zonecode; //5자리 새우편번호 사용
                document.getElementById(cf_company_fulladdr).value = fullAddr;
				document.getElementById(cf_company_extraAddr).value = extraAddr;

                // 커서를 상세주소 필드로 이동한다.
				document.getElementById(cf_company_detailAddr).value = "";
                document.getElementById(cf_company_detailAddr).focus();
        }
    }).open();

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