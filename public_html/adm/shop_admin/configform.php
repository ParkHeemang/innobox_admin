<?php
$sub_menu = '400100';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[$sub_menu], "r");

if (!$config['cf_icode_server_ip'])   $config['cf_icode_server_ip'] = '211.172.232.124';
if (!$config['cf_icode_server_port']) $config['cf_icode_server_port'] = '7295';

if ($config['cf_sms_use'] && $config['cf_icode_id'] && $config['cf_icode_pw']) {
    $userinfo = get_icode_userinfo($config['cf_icode_id'], $config['cf_icode_pw']);
}

$g5['title'] = '쇼핑몰설정';
include_once ('../admin.head.php');

$pg_anchor = '<ul class="anchor">
<li><a href="#anc_scf_info">사업자정보</a></li>
<li><a href="#anc_scf_skin">스킨설정</a></li>
<li><a href="#anc_scf_index">쇼핑몰 초기화면</a></li>
<li><a href="#anc_mscf_index">모바일 초기화면</a></li>
<li><a href="#anc_scf_payment">결제설정</a></li>
<li><a href="#anc_scf_delivery">배송설정</a></li>
<li><a href="#anc_scf_etc">기타설정</a></li>
<li><a href="#anc_scf_sms">SMS설정</a></li>
</ul>';

// 무이자 할부 사용설정 필드 추가
if(!isset($default['de_card_noint_use'])) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_default_table']}`
                    ADD `de_card_noint_use` tinyint(4) NOT NULL DEFAULT '0' AFTER `de_card_use` ", true);
}

// 모바일 관련상품 설정 필드추가
if(!isset($default['de_mobile_rel_list_use'])) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_default_table']}`
                    ADD `de_mobile_rel_list_use` tinyint(4) NOT NULL DEFAULT '0' AFTER `de_rel_img_height`,
                    ADD `de_mobile_rel_list_skin` varchar(255) NOT NULL DEFAULT '' AFTER `de_mobile_rel_list_use`,
                    ADD `de_mobile_rel_img_width` int(11) NOT NULL DEFAULT '0' AFTER `de_mobile_rel_list_skin`,
                    ADD `de_mobile_rel_img_height` int(11) NOT NULL DEFAULT ' 0' AFTER `de_mobile_rel_img_width`", true);
}

// 신규회원 쿠폰 설정 필드 추가
if(!isset($default['de_member_reg_coupon_use'])) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_default_table']}`
                    ADD `de_member_reg_coupon_use` tinyint(4) NOT NULL DEFAULT '0' AFTER `de_tax_flag_use`,
                    ADD `de_member_reg_coupon_term` int(11) NOT NULL DEFAULT '0' AFTER `de_member_reg_coupon_use`,
                    ADD `de_member_reg_coupon_price` int(11) NOT NULL DEFAULT '0' AFTER `de_member_reg_coupon_term` ", true);
}

// 신규회원 쿠폰 주문 최소금액 필드추가
if(!isset($default['de_member_reg_coupon_minimum'])) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_default_table']}`
                    ADD `de_member_reg_coupon_minimum` int(11) NOT NULL DEFAULT '0' AFTER `de_member_reg_coupon_price` ", true);
}

// lg 결제관련 필드 추가
if(!isset($default['de_pg_service'])) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_default_table']}`
                    ADD `de_pg_service` varchar(255) NOT NULL DEFAULT '' AFTER `de_sms_hp` ", true);
}


// inicis 필드 추가
if(!isset($default['de_inicis_mid'])) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_default_table']}`
                    ADD `de_inicis_mid` varchar(255) NOT NULL DEFAULT '' AFTER `de_kcp_site_key`,
                    ADD `de_inicis_admin_key` varchar(255) NOT NULL DEFAULT '' AFTER `de_inicis_mid` ", true);
}

// 모바일 초기화면 이미지 줄 수 필드 추가
if(!isset($default['de_mobile_type1_list_row'])) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_default_table']}`
                    ADD `de_mobile_type1_list_row` int(11) NOT NULL DEFAULT '0' AFTER `de_mobile_type1_list_mod`,
                    ADD `de_mobile_type2_list_row` int(11) NOT NULL DEFAULT '0' AFTER `de_mobile_type2_list_mod`,
                    ADD `de_mobile_type3_list_row` int(11) NOT NULL DEFAULT '0' AFTER `de_mobile_type3_list_mod`,
                    ADD `de_mobile_type4_list_row` int(11) NOT NULL DEFAULT '0' AFTER `de_mobile_type4_list_mod`,
                    ADD `de_mobile_type5_list_row` int(11) NOT NULL DEFAULT '0' AFTER `de_mobile_type5_list_mod` ", true);
}

// 모바일 관련상품 이미지 줄 수 필드 추가
if(!isset($default['de_mobile_rel_list_mod'])) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_default_table']}`
                    ADD `de_mobile_rel_list_mod` int(11) NOT NULL DEFAULT '0' AFTER `de_mobile_rel_list_skin` ", true);
}

// 모바일 검색상품 이미지 줄 수 필드 추가
if(!isset($default['de_mobile_search_list_row'])) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_default_table']}`
                    ADD `de_mobile_search_list_row` int(11) NOT NULL DEFAULT '0' AFTER `de_mobile_search_list_mod` ", true);
}

// PG 간펼결제 사용여부 필드 추가
if(!isset($default['de_easy_pay_use'])) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_default_table']}`
                    ADD `de_easy_pay_use` tinyint(4) NOT NULL DEFAULT '0' AFTER `de_iche_use` ", true);
}

// 이니시스 삼성페이 사용여부 필드 추가
if(!isset($default['de_samsung_pay_use'])) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_default_table']}`
                    ADD `de_samsung_pay_use` tinyint(4) NOT NULL DEFAULT '0' AFTER `de_easy_pay_use` ", true);
}

// 이니시스
if(!isset($default['de_inicis_cartpoint_use'])) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_default_table']}`
                    ADD `de_inicis_cartpoint_use` tinyint(4) NOT NULL DEFAULT '0' AFTER `de_samsung_pay_use` ", true);
}

// 이니시스 lpay 사용여부 필드 추가
if(!isset($default['de_inicis_lpay_use'])) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_default_table']}`
                    ADD `de_inicis_lpay_use` tinyint(4) NOT NULL DEFAULT '0' AFTER `de_samsung_pay_use` ", true);
}

// 카카오페이 필드 추가
if(!isset($default['de_kakaopay_mid'])) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_default_table']}`
                    ADD `de_kakaopay_mid` varchar(255) NOT NULL DEFAULT '' AFTER `de_tax_flag_use`,
                    ADD `de_kakaopay_key` varchar(255) NOT NULL DEFAULT '' AFTER `de_kakaopay_mid`,
                    ADD `de_kakaopay_enckey` varchar(255) NOT NULL DEFAULT '' AFTER `de_kakaopay_key`,
                    ADD `de_kakaopay_hashkey` varchar(255) NOT NULL DEFAULT '' AFTER `de_kakaopay_enckey`,
                    ADD `de_kakaopay_cancelpwd` varchar(255) NOT NULL DEFAULT '' AFTER `de_kakaopay_hashkey` ", true);
}

// 이니시스 웹결제 사인키 필드 추가
if(!isset($default['de_inicis_sign_key'])) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_default_table']}`
                    ADD `de_inicis_sign_key` varchar(255) NOT NULL DEFAULT '' AFTER `de_inicis_admin_key` ", true);
}

// 네이버페이 필드추가
if(!isset($default['de_naverpay_mid'])) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_default_table']}`
                    ADD `de_naverpay_mid` varchar(255) NOT NULL DEFAULT '' AFTER `de_kakaopay_cancelpwd`,
                    ADD `de_naverpay_cert_key` varchar(255) NOT NULL DEFAULT '' AFTER `de_naverpay_mid`,
                    ADD `de_naverpay_button_key` varchar(255) NOT NULL DEFAULT '' AFTER `de_naverpay_cert_key`,
                    ADD `de_naverpay_test` tinyint(4) NOT NULL DEFAULT '0' AFTER `de_naverpay_button_key`,
                    ADD `de_naverpay_mb_id` varchar(255) NOT NULL DEFAULT '' AFTER `de_naverpay_test`,
                    ADD `de_naverpay_sendcost` varchar(255) NOT NULL DEFAULT '' AFTER `de_naverpay_mb_id`", true);
}

// 유형별상품리스트 설정필드 추가
if(!isset($default['de_listtype_list_skin'])) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_default_table']}`
                    ADD `de_listtype_list_skin` varchar(255) NOT NULL DEFAULT '' AFTER `de_mobile_search_img_height`,
                    ADD `de_listtype_list_mod` int(11) NOT NULL DEFAULT '0' AFTER `de_listtype_list_skin`,
                    ADD `de_listtype_list_row` int(11) NOT NULL DEFAULT '0' AFTER `de_listtype_list_mod`,
                    ADD `de_listtype_img_width` int(11) NOT NULL DEFAULT '0' AFTER `de_listtype_list_row`,
                    ADD `de_listtype_img_height` int(11) NOT NULL DEFAULT '0' AFTER `de_listtype_img_width`,
                    ADD `de_mobile_listtype_list_skin` varchar(255) NOT NULL DEFAULT '' AFTER `de_listtype_img_height`,
                    ADD `de_mobile_listtype_list_mod` int(11) NOT NULL DEFAULT '0' AFTER `de_mobile_listtype_list_skin`,
                    ADD `de_mobile_listtype_list_row` int(11) NOT NULL DEFAULT '0' AFTER `de_mobile_listtype_list_mod`,
                    ADD `de_mobile_listtype_img_width` int(11) NOT NULL DEFAULT '0' AFTER `de_mobile_listtype_list_row`,
                    ADD `de_mobile_listtype_img_height` int(11) NOT NULL DEFAULT '0' AFTER `de_mobile_listtype_img_width` ", true);
}



//CSS
add_stylesheet('<link rel="stylesheet" href="'.G5_PHP_URL. 'bower_components/bootstrap/dist/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 5);
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL2.'/bootstrap2.custom.css?ver='.G5_CSS_VER.'"></script>',8);

//JS DataTables
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);

?>

<form name="fconfig" action="./configformupdate.php" onsubmit="return fconfig_check(this)" method="post" enctype="MULTIPART/FORM-DATA">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">	
	<!-- Content Header (Page header) -->    
	<section class="content-header configform">
		<nav class="navbar navbar-default navbtn configform" style="z-index: 1000000000000000000000000000000000">
			<div class="nav-tabs-custom configform">
				<ul class="nav nav-tabs" id="tab_ul">                 
					<li class="active"><a href="#tab_1" data-toggle="tab">사업자정보 입력</a></li>
					<li><a href="#tab_2" data-toggle="tab">스킨설정</a></li>
					<li><a href="#tab_3" data-toggle="tab">사용자 초기화면 설정</a></li>
					<li><a href="#tab_4" data-toggle="tab">모바일초기화면 설정</a></li>
					<li><a href="#tab_5" data-toggle="tab">결제 설정</a></li>
					<li><a href="#tab_6" data-toggle="tab">배송 설정</a></li>
					<li><a href="#tab_7" data-toggle="tab">기타 설정</a></li>
					<li><a href="#tab_8" data-toggle="tab">SNS 설정</a></li>				  
					<li class="pull-right config_form">
						<div>
							<a href="<?php echo G5_SHOP_URL2;?>" class="btn btn_02 btn-default">쇼핑몰</a>
							<input type="submit" value="확인" class="btn_submit btn btn-primary" accesskey="s">
						</div>
					</li>
				</ul>
			</div>
		</nav>	  
	</section>
	<!-- Main content -->	
	<section class="content configform">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">					
					</div>
					<!-- /.box-header -->
					<div class="box-body">											
							<!-- Custom Tabs -->
							<div class="tab-content">
								<div class="tab-pane active" id="tab_1">
									<table id="example2" class="table table-bordered table-hover dataTable configform">
										<!-- <caption><h2>사업자정보 입력</h2></caption> -->
										<colgroup>
										<col class="grid_4">
										<col>
										<col class="grid_4">
										<col>
										</colgroup>
										<tbody>
										<tr>
										<th scope="row"><label for="de_admin_company_name">회사명</label></th>
										<td>
											<input type="text" name="de_admin_company_name" value="<?php echo $default['de_admin_company_name']; ?>" id="de_admin_company_name" class="frm_input form-control" size="30">
										</td>
										<th scope="row"><label for="de_admin_company_saupja_no">사업자등록번호</label></th>
										<td>
											<input type="text" name="de_admin_company_saupja_no"  value="<?php echo $default['de_admin_company_saupja_no']; ?>" id="de_admin_company_saupja_no" class="frm_input form-control" size="30">
										</td>
										</tr>
										<tr>
										<th scope="row"><label for="de_admin_company_owner">대표자명</label></th>
										<td colspan="3">
											<input type="text" name="de_admin_company_owner" value="<?php echo $default['de_admin_company_owner']; ?>" id="de_admin_company_owner" class="frm_input form-control" size="30">
										</td>
										</tr>
										<tr>
										<th scope="row"><label for="de_admin_company_tel">대표전화번호</label></th>
										<td>
											<input type="text" name="de_admin_company_tel" value="<?php echo $default['de_admin_company_tel']; ?>" id="de_admin_company_tel" class="frm_input form-control" size="30">
										</td>
										<th scope="row"><label for="de_admin_company_fax">팩스번호</label></th>
										<td>
											<input type="text" name="de_admin_company_fax" value="<?php echo $default['de_admin_company_fax']; ?>" id="de_admin_company_fax" class="frm_input form-control" size="30">
										</td>
										</tr>
										<tr>
										<th scope="row"><label for="de_admin_tongsin_no">통신판매업 신고번호</label></th>
										<td>
											<input type="text" name="de_admin_tongsin_no" value="<?php echo $default['de_admin_tongsin_no']; ?>" id="de_admin_tongsin_no" class="frm_input form-control" size="30">
										</td>
										<th scope="row"><label for="de_admin_buga_no">부가통신 사업자번호</label></th>
										<td>
											<input type="text" name="de_admin_buga_no" value="<?php echo $default['de_admin_buga_no']; ?>" id="de_admin_buga_no" class="frm_input form-control" size="30">
										</td>
										</tr>
										<tr>
										<th scope="row"><label for="de_admin_company_zip">사업장우편번호</label></th>
										<td>
											<input type="text" name="de_admin_company_zip" value="<?php echo $default['de_admin_company_zip']; ?>" id="de_admin_company_zip" class="frm_input form-control" size="10">
										</td>
										<th scope="row"><label for="de_admin_company_addr">사업장주소</label></th>
										<td>
											<input type="text" name="de_admin_company_addr" value="<?php echo $default['de_admin_company_addr']; ?>" id="de_admin_company_addr" class="frm_input form-control" size="30">
										</td>
										</tr>
										<tr>
										<th scope="row"><label for="de_admin_info_name">정보관리책임자명</label></th>
										<td>
											<input type="text" name="de_admin_info_name" value="<?php echo $default['de_admin_info_name']; ?>" id="de_admin_info_name" class="frm_input form-control" size="30">
										</td>
										<th scope="row"><label for="de_admin_info_email">정보책임자 e-mail</label></th>
										<td>
											<input type="text" name="de_admin_info_email" value="<?php echo $default['de_admin_info_email']; ?>" id="de_admin_info_email" class="frm_input form-control" size="30">
										</td>
										</tr>
										</tbody>
									</table>
								</div>
								<!-- /.tab-pane -->
								<div class="tab-pane" id="tab_2">
									<table id="example2" class="table table-bordered table-hover dataTable member-form">
										<!-- 	<caption>스킨설정</caption> -->
											<tbody>
											<tr>
												<th scope="row"><label for="de_shop_skin">PC용 스킨</label></th>
												<td>
													<?php echo get_skin_select('shop', 'de_shop_skin', 'de_shop_skin', $default['de_shop_skin'], 'required'); ?>
												</td>
											</tr>
											<tr>
												<th scope="row"><label for="de_shop_mobile_skin">모바일용 스킨</label></th>
												<td>
													<?php echo get_mobile_skin_select('shop', 'de_shop_mobile_skin', 'de_shop_mobile_skin', $default['de_shop_mobile_skin'], 'required'); ?>
												</td>
											</tr>
											</tbody>
									</table>
								</div>
								<!-- /.tab-pane -->
								<div class="tab-pane" id="tab_3">
									<table id="example2" class="table table-bordered table-hover dataTable member-form">
										<!-- <caption>쇼핑몰 초기화면 설정</caption> -->
										<tbody>
										<tr>
											<th scope="row">히트상품출력</th>
											<td>
												<label for="de_type1_list_use">출력</label>
												<input type="checkbox" name="de_type1_list_use" value="1" id="de_type1_list_use" <?php echo $default['de_type1_list_use']?"checked":""; ?>>
												<label for="de_type1_list_skin">스킨</label>
												<select name="de_type1_list_skin" id="de_type1_list_skin">
													<?php echo get_list_skin_options("^main.[0-9]+\.skin\.php", G5_SHOP_SKIN_PATH, $default['de_type1_list_skin']); ?>
												</select>
												<label for="de_type1_list_mod">1줄당 이미지 수</label>
												<input type="text" name="de_type1_list_mod" value="<?php echo $default['de_type1_list_mod']; ?>" id="de_type1_list_mod" class="frm_input form-control" size="3">
												<label for="de_type1_list_row">출력할 줄 수</label>
												<input type="text" name="de_type1_list_row" value="<?php echo $default['de_type1_list_row']; ?>" id="de_type1_list_row" class="frm_input form-control" size="3">
												<label for="de_type1_img_width">이미지 폭</label>
												<input type="text" name="de_type1_img_width" value="<?php echo $default['de_type1_img_width']; ?>" id="de_type1_img_width" class="frm_input form-control" size="3">
												<label for="de_type1_img_height">이미지 높이</label>
												<input type="text" name="de_type1_img_height" value="<?php echo $default['de_type1_img_height']; ?>" id="de_type1_img_height" class="frm_input form-control" size="3">
											</td>
										</tr>
										<tr>
											<th scope="row">추천상품출력</th>
											<td>
												<label for="de_type2_list_use">출력</label>
												<input type="checkbox" name="de_type2_list_use" value="1" id="de_type2_list_use" <?php echo $default['de_type2_list_use']?"checked":""; ?>>
												<label for="de_type2_list_skin">스킨</label>
												<select name="de_type2_list_skin" id="de_type2_list_skin">
													<?php echo get_list_skin_options("^main.[0-9]+\.skin\.php", G5_SHOP_SKIN_PATH, $default['de_type2_list_skin']); ?>
												</select>
												<label for="de_type2_list_mod">1줄당 이미지 수</label>
												<input type="text" name="de_type2_list_mod" value="<?php echo $default['de_type2_list_mod']; ?>" id="de_type2_list_mod" class="frm_input form-control" size="3">
												<label for="de_type2_list_row">출력할 줄 수</label>
												<input type="text" name="de_type2_list_row" value="<?php echo $default['de_type2_list_row']; ?>" id="de_type2_list_row" class="frm_input form-control" size="3">
												<label for="de_type2_img_width">이미지 폭</label>
												<input type="text" name="de_type2_img_width" value="<?php echo $default['de_type2_img_width']; ?>" id="de_type2_img_width" class="frm_input form-control" size="3">
												<label for="de_type2_img_height">이미지 높이</label>
												<input type="text" name="de_type2_img_height" value="<?php echo $default['de_type2_img_height']; ?>" id="de_type2_img_height" class="frm_input form-control" size="3">
											</td>
										</tr>
										<tr>
											<th scope="row">최신상품출력</th>
											<td>
												<label for="de_type3_list_use">출력</label>
												<input type="checkbox" name="de_type3_list_use" value="1" id="de_type3_list_use" <?php echo $default['de_type3_list_use']?"checked":""; ?>>
												<label for="de_type3_list_skin">스킨</label>
												<select name="de_type3_list_skin" id="de_type3_list_skin">
													<?php echo get_list_skin_options("^main.[0-9]+\.skin\.php", G5_SHOP_SKIN_PATH, $default['de_type3_list_skin']); ?>
												</select>
												<label for="de_type3_list_mod">1줄당 이미지 수</label>
												<input type="text" name="de_type3_list_mod" value="<?php echo $default['de_type3_list_mod']; ?>" id="de_type3_list_mod" class="frm_input form-control" size="3">
												<label for="de_type3_list_row">출력할 줄 수</label>
												<input type="text" name="de_type3_list_row" value="<?php echo $default['de_type3_list_row']; ?>" id="de_type3_list_row" class="frm_input form-control" size="3">
												<label for="de_type3_img_width">이미지 폭</label>
												<input type="text" name="de_type3_img_width" value="<?php echo $default['de_type3_img_width']; ?>" id="de_type3_img_width" class="frm_input form-control" size="3">
												<label for="de_type3_img_height">이미지 높이</label>
												<input type="text" name="de_type3_img_height" value="<?php echo $default['de_type3_img_height']; ?>" id="de_type3_img_height" class="frm_input form-control" size="3">
											</td>
										</tr>
										<tr>
											<th scope="row">인기상품출력</th>
											<td>
												<label for="de_type4_list_use">출력</label>
												<input type="checkbox" name="de_type4_list_use" value="1" id="de_type4_list_use" <?php echo $default['de_type4_list_use']?"checked":""; ?>>
												<label for="de_type4_list_skin">스킨</label>
												<select name="de_type4_list_skin" id="de_type4_list_skin">
													<?php echo get_list_skin_options("^main.[0-9]+\.skin\.php", G5_SHOP_SKIN_PATH, $default['de_type4_list_skin']); ?>
												</select>
												<label for="de_type4_list_mod">1줄당 이미지 수</label>
												<input type="text" name="de_type4_list_mod" value="<?php echo $default['de_type4_list_mod']; ?>" id="de_type4_list_mod" class="frm_input form-control" size="3">
												<label for="de_type4_list_row">출력할 줄 수</label>
												<input type="text" name="de_type4_list_row" value="<?php echo $default['de_type4_list_row']; ?>" id="de_type4_list_row" class="frm_input form-control" size="3">
												<label for="de_type4_img_width">이미지 폭</label>
												<input type="text" name="de_type4_img_width" value="<?php echo $default['de_type4_img_width']; ?>" id="de_type4_img_width" class="frm_input form-control" size="3">
												<label for="de_type4_img_height">이미지 높이</label>
												<input type="text" name="de_type4_img_height" value="<?php echo $default['de_type4_img_height']; ?>" id="de_type4_img_height" class="frm_input form-control" size="3">
											</td>
										</tr>
										<tr>
											<th scope="row">할인상품출력</th>
											<td>
												<label for="de_type5_list_use">출력</label>
												<input type="checkbox" name="de_type5_list_use" value="1" id="de_type5_list_use" <?php echo $default['de_type5_list_use']?"checked":""; ?>>
												<label for="de_type5_list_skin">스킨</label>
												<select name="de_type5_list_skin" id="de_type5_list_skin">
													<?php echo get_list_skin_options("^main.[0-9]+\.skin\.php", G5_SHOP_SKIN_PATH, $default['de_type5_list_skin']); ?>
												</select>
												<label for="de_type5_list_mod">1줄당 이미지 수</label>
												<input type="text" name="de_type5_list_mod" value="<?php echo $default['de_type5_list_mod']; ?>" id="de_type5_list_mod" class="frm_input form-control" size="3">
												<label for="de_type5_list_row">출력할 줄 수</label>
												<input type="text" name="de_type5_list_row" value="<?php echo $default['de_type5_list_row']; ?>" id="de_type5_list_row" class="frm_input form-control" size="3">
												<label for="de_type5_img_width">이미지 폭</label>
												<input type="text" name="de_type5_img_width" value="<?php echo $default['de_type5_img_width']; ?>" id="de_type5_img_width" class="frm_input form-control" size="3">
												<label for="de_type5_img_height">이미지 높이</label>
												<input type="text" name="de_type5_img_height" value="<?php echo $default['de_type5_img_height']; ?>" id="de_type5_img_height" class="frm_input form-control" size="3">
											</td>
										</tr>
										</tbody>
									</table>
								</div>
								<!-- /.tab-pane -->
								<div class="tab-pane" id="tab_4">
									<table id="example2" class="table table-bordered table-hover dataTable configform">
										<!-- <caption>모바일 쇼핑몰 초기화면 설정</caption> -->
										<colgroup>
											<col class="grid_4">
											<col>
										</colgroup>
										<tbody>
										<tr>
											<th scope="row">히트상품출력</th>
											<td>
												<label for="de_mobile_type1_list_use">출력</label>
												<input type="checkbox" name="de_mobile_type1_list_use" value="1" id="de_mobile_type1_list_use" <?php echo $default['de_mobile_type1_list_use']?"checked":""; ?>>
												<label for="de_mobile_type1_list_skin">스킨</label>
												<select name="de_mobile_type1_list_skin" id="de_mobile_type1_list_skin">
													<?php echo get_list_skin_options("^main.[0-9]+\.skin\.php", G5_MSHOP_SKIN_PATH, $default['de_mobile_type1_list_skin']); ?>
												</select>
												<label for="de_mobile_type1_list_mod">1줄당 이미지 수</label>
												<input type="text" name="de_mobile_type1_list_mod" value="<?php echo $default['de_mobile_type1_list_mod']; ?>" id="de_mobile_type1_list_mod" class="frm_input form-control" size="3">
												 <label for="de_mobile_type1_list_row">출력할 줄 수</label>
												<input type="text" name="de_mobile_type1_list_row" value="<?php echo $default['de_mobile_type1_list_row']; ?>" id="de_mobile_type1_list_row" class="frm_input form-control" size="3">
												<label for="de_mobile_type1_img_width">이미지 폭</label>
												<input type="text" name="de_mobile_type1_img_width" value="<?php echo $default['de_mobile_type1_img_width']; ?>" id="de_mobile_type1_img_width" class="frm_input form-control" size="3">
												<label for="de_mobile_type1_img_height">이미지 높이</label>
												<input type="text" name="de_mobile_type1_img_height" value="<?php echo $default['de_mobile_type1_img_height']; ?>" id="de_mobile_type1_img_height" class="frm_input form-control" size="3">
											</td>
										</tr>
										<tr>
											<th scope="row">추천상품출력</th>
											<td>
												<label for="de_mobile_type2_list_use">출력</label> <input type="checkbox" name="de_mobile_type2_list_use" value="1" id="de_mobile_type2_list_use" <?php echo $default['de_mobile_type2_list_use']?"checked":""; ?>>
												<label for="de_mobile_type2_list_skin">스킨 </label>
												<select name="de_mobile_type2_list_skin" id="de_mobile_type2_list_skin">
													<?php echo get_list_skin_options("^main.[0-9]+\.skin\.php", G5_MSHOP_SKIN_PATH, $default['de_mobile_type2_list_skin']); ?>
												</select>
												<label for="de_mobile_type2_list_mod">1줄당 이미지 수</label>
												<input type="text" name="de_mobile_type2_list_mod" value="<?php echo $default['de_mobile_type2_list_mod']; ?>" id="de_mobile_type2_list_mod" class="frm_input form-control" size="3">
												 <label for="de_mobile_type2_list_row">출력할 줄 수</label>
												<input type="text" name="de_mobile_type2_list_row" value="<?php echo $default['de_mobile_type2_list_row']; ?>" id="de_mobile_type2_list_row" class="frm_input form-control" size="3">
												<label for="de_mobile_type2_img_width">이미지 폭</label>
												<input type="text" name="de_mobile_type2_img_width" value="<?php echo $default['de_mobile_type2_img_width']; ?>" id="de_mobile_type2_img_width" class="frm_input form-control" size="3">
												<label for="de_mobile_type2_img_height">이미지 높이</label>
												<input type="text" name="de_mobile_type2_img_height" value="<?php echo $default['de_mobile_type2_img_height']; ?>" id="de_mobile_type2_img_height" class="frm_input form-control" size="3">
											</td>
										</tr>
										<tr>
											<th scope="row">최신상품출력</th>
											<td>
												<label for="de_mobile_type3_list_use">출력</label>
												<input type="checkbox" name="de_mobile_type3_list_use" value="1" id="de_mobile_type3_list_use" <?php echo $default['de_mobile_type3_list_use']?"checked":""; ?>>
												<label for="de_mobile_type3_list_skin">스킨</label>
												<select name="de_mobile_type3_list_skin" id="de_mobile_type3_list_skin">
													<?php echo get_list_skin_options("^main.[0-9]+\.skin\.php", G5_MSHOP_SKIN_PATH, $default['de_mobile_type3_list_skin']); ?>
												</select>
												<label for="de_mobile_type3_list_mod">1줄당 이미지 수</label>
												<input type="text" name="de_mobile_type3_list_mod" value="<?php echo $default['de_mobile_type3_list_mod']; ?>" id="de_mobile_type3_list_mod" class="frm_input form-control" size="3">
												 <label for="de_mobile_type3_list_row">출력할 줄 수</label>
												<input type="text" name="de_mobile_type3_list_row" value="<?php echo $default['de_mobile_type3_list_row']; ?>" id="de_mobile_type3_list_row" class="frm_input form-control" size="3">
												<label for="de_mobile_type3_img_width">이미지 폭</label>
												<input type="text" name="de_mobile_type3_img_width" value="<?php echo $default['de_mobile_type3_img_width']; ?>" id="de_mobile_type3_img_width" class="frm_input form-control" size="3">
												<label for="de_mobile_type3_img_height">이미지 높이</label>
												<input type="text" name="de_mobile_type3_img_height" value="<?php echo $default['de_mobile_type3_img_height']; ?>" id="de_mobile_type3_img_height" class="frm_input form-control" size="3">
											</td>
										</tr>
										<tr>
											<th scope="row">인기상품출력</th>
											<td>
												<label for="de_mobile_type4_list_use">출력</label>
												<input type="checkbox" name="de_mobile_type4_list_use" value="1" id="de_mobile_type4_list_use" <?php echo $default['de_mobile_type4_list_use']?"checked":""; ?>>
												<label for="de_mobile_type4_list_skin">스킨</label>
												<select name="de_mobile_type4_list_skin" id="de_mobile_type4_list_skin">
													<?php echo get_list_skin_options("^main.[0-9]+\.skin\.php", G5_MSHOP_SKIN_PATH, $default['de_mobile_type4_list_skin']); ?>
												</select>
												<label for="de_mobile_type4_list_mod">1줄당 이미지 수</label>
												<input type="text" name="de_mobile_type4_list_mod" value="<?php echo $default['de_mobile_type4_list_mod']; ?>" id="de_mobile_type4_list_mod" class="frm_input form-control" size="3">
												 <label for="de_mobile_type4_list_row">출력할 줄 수</label>
												<input type="text" name="de_mobile_type4_list_row" value="<?php echo $default['de_mobile_type4_list_row']; ?>" id="de_mobile_type4_list_row" class="frm_input form-control" size="3">
												<label for="de_mobile_type4_img_width">이미지 폭</label>
												<input type="text" name="de_mobile_type4_img_width" value="<?php echo $default['de_mobile_type4_img_width']; ?>" id="de_mobile_type4_img_width" class="frm_input form-control" size="3">
												<label for="de_mobile_type4_img_height">이미지 높이</label>
												<input type="text" name="de_mobile_type4_img_height" value="<?php echo $default['de_mobile_type4_img_height']; ?>" id="de_mobile_type4_img_height" class="frm_input form-control" size="3">
											</td>
										</tr>
										<tr>
											<th scope="row">할인상품출력</th>
											<td>
												<label for="de_mobile_type5_list_use">출력</label>
												<input type="checkbox" name="de_mobile_type5_list_use" value="1" id="de_mobile_type5_list_use" <?php echo $default['de_mobile_type5_list_use']?"checked":""; ?>>
												<label for="de_mobile_type5_list_skin">스킨</label>
												<select id="de_mobile_type5_list_skin" name="de_mobile_type5_list_skin">
													<?php echo get_list_skin_options("^main.[0-9]+\.skin\.php", G5_MSHOP_SKIN_PATH, $default['de_mobile_type5_list_skin']); ?>
												</select>
												<label for="de_mobile_type5_list_mod">1줄당 이미지 수</label>
												<input type="text" name="de_mobile_type5_list_mod" value="<?php echo $default['de_mobile_type5_list_mod']; ?>" id="de_mobile_type5_list_mod" class="frm_input form-control" size="3">
												 <label for="de_mobile_type5_list_row">출력할 줄 수</label>
												<input type="text" name="de_mobile_type5_list_row" value="<?php echo $default['de_mobile_type5_list_row']; ?>" id="de_mobile_type5_list_row" class="frm_input form-control" size="3">
												<label for="de_mobile_type5_img_width">이미지 폭</label>
												<input type="text" name="de_mobile_type5_img_width" value="<?php echo $default['de_mobile_type5_img_width']; ?>" id="de_mobile_type5_img_width" class="frm_input form-control" size="3">
												<label for="de_mobile_type5_img_height">이미지 높이</label>
												<input type="text" name="de_mobile_type5_img_height" value="<?php echo $default['de_mobile_type5_img_height']; ?>" id="de_mobile_type5_img_height" class="frm_input form-control" size="3">
											</td>
										</tr>
										</tbody>
									</table>
								</div>
								<!-- /.tab-pane -->
								<div class="tab-pane" id="tab_5">
									<div class="tbl_frm01 tbl_wrap">
										<table id="example2" class="table table-bordered table-hover dataTable configform">
										<!-- <caption>결제설정 입력</caption> -->
										<colgroup>
											<col class="grid_4">
											<col>
										</colgroup>
										<tbody>
										<tr>
											<th scope="row"><label for="de_bank_use">무통장입금사용</label></th>
											<td>
												<?php echo help("주문시 무통장으로 입금을 가능하게 할것인지를 설정합니다.\n사용할 경우 은행계좌번호를 반드시 입력하여 주십시오.", 50); ?>
												<select id="de_bank_use" name="de_bank_use">
													<option value="0" <?php echo get_selected($default['de_bank_use'], 0); ?>>사용안함</option>
													<option value="1" <?php echo get_selected($default['de_bank_use'], 1); ?>>사용</option>
												</select>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_bank_account">은행계좌번호</label></th>
											<td>
												<textarea name="de_bank_account" id="de_bank_account"><?php echo $default['de_bank_account']; ?></textarea>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_iche_use">계좌이체 결제사용</label></th>
											<td>
											<?php echo help("주문시 실시간 계좌이체를 가능하게 할것인지를 설정합니다.", 50); ?>
												<select id="de_iche_use" name="de_iche_use">
													<option value="0" <?php echo get_selected($default['de_iche_use'], 0); ?>>사용안함</option>
													<option value="1" <?php echo get_selected($default['de_iche_use'], 1); ?>>사용</option>
												</select>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_vbank_use">가상계좌 결제사용</label></th>
											<td>
												<?php echo help("주문별로 유일하게 생성되는 일회용 계좌번호입니다. 주문자가 가상계좌에 입금시 상점에 실시간으로 통보가 되므로 업무처리가 빨라집니다.", 50); ?>
												<select name="de_vbank_use" id="de_vbank_use">
													<option value="0" <?php echo get_selected($default['de_vbank_use'], 0); ?>>사용안함</option>
													<option value="1" <?php echo get_selected($default['de_vbank_use'], 1); ?>>사용</option>
												</select>
											</td>
										</tr>
										<tr id="kcp_vbank_url" class="pg_vbank_url">
											<th scope="row">NHN KCP 가상계좌<br>입금통보 URL</th>
											<td>
												<?php echo help("NHN KCP 가상계좌 사용시 다음 주소를 <strong><a href=\"http://admin.kcp.co.kr\" target=\"_blank\">NHN KCP 관리자</a> &gt; 상점정보관리 &gt; 정보변경 &gt; 공통URL 정보 &gt; 공통URL 변경후</strong>에 넣으셔야 상점에 자동으로 입금 통보됩니다."); ?>
												<?php echo G5_SHOP_URL; ?>/settle_kcp_common.php</td>
										</tr>
										<tr id="inicis_vbank_url" class="pg_vbank_url">
											<th scope="row">KG이니시스 가상계좌 입금통보 URL</th>
											<td>
												<?php echo help("KG이니시스 가상계좌 사용시 다음 주소를 <strong><a href=\"https://iniweb.inicis.com/\" target=\"_blank\">KG이니시스 관리자</a> &gt; 거래조회 &gt; 가상계좌 &gt; 입금통보방식선택 &gt; URL 수신 설정</strong>에 넣으셔야 상점에 자동으로 입금 통보됩니다."); ?>
												<?php echo G5_SHOP_URL; ?>/settle_inicis_common.php</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_hp_use">휴대폰결제사용</label></th>
											<td>
												<?php echo help("주문시 휴대폰 결제를 가능하게 할것인지를 설정합니다.", 50); ?>
												<select id="de_hp_use" name="de_hp_use">
													<option value="0" <?php echo get_selected($default['de_hp_use'], 0); ?>>사용안함</option>
													<option value="1" <?php echo get_selected($default['de_hp_use'], 1); ?>>사용</option>
												</select>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_card_use">신용카드결제사용</label></th>
											<td>
												<?php echo help("주문시 신용카드 결제를 가능하게 할것인지를 설정합니다.", 50); ?>
												<select id="de_card_use" name="de_card_use">
													<option value="0" <?php echo get_selected($default['de_card_use'], 0); ?>>사용안함</option>
													<option value="1" <?php echo get_selected($default['de_card_use'], 1); ?>>사용</option>
												</select>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_card_noint_use">신용카드 무이자할부사용</label></th>
											<td>
												<?php echo help("주문시 신용카드 무이자할부를 가능하게 할것인지를 설정합니다.<br>사용으로 설정하시면 PG사 가맹점 관리자 페이지에서 설정하신 무이자할부 설정이 적용됩니다.<br>사용안함으로 설정하시면 PG사 무이자 이벤트 카드를 제외한 모든 카드의 무이자 설정이 적용되지 않습니다.", 50); ?>
												<select id="de_card_noint_use" name="de_card_noint_use">
													<option value="0" <?php echo get_selected($default['de_card_noint_use'], 0); ?>>사용안함</option>
													<option value="1" <?php echo get_selected($default['de_card_noint_use'], 1); ?>>사용</option>
												</select>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_easy_pay_use">PG사 간편결제 버튼 사용</label></th>
											<td>
												<?php echo help("주문서 작성 페이지에 PG사 간편결제(PAYCO, PAYNOW, KPAY) 버튼의 별도 사용 여부를 설정합니다.", 50); ?>
												<select id="de_easy_pay_use" name="de_easy_pay_use">
													<option value="0" <?php echo get_selected($default['de_easy_pay_use'], 0); ?>>노출안함</option>
													<option value="1" <?php echo get_selected($default['de_easy_pay_use'], 1); ?>>노출함</option>
												</select>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_taxsave_use">현금영수증<br>발급사용</label></th>
											<td>
												<?php echo help("관리자는 설정에 관계없이 <a href=\"".G5_ADMIN_URL."/shop_admin/orderlist.php\">주문내역</a> &gt; 보기에서 발급이 가능합니다.\n현금영수증 발급 취소는 PG사에서 지원하는 현금영수증 취소 기능을 사용하시기 바랍니다.", 50); ?>
												<select id="de_taxsave_use" name="de_taxsave_use">
													<option value="0" <?php echo get_selected($default['de_taxsave_use'], 0); ?>>사용안함</option>
													<option value="1" <?php echo get_selected($default['de_taxsave_use'], 1); ?>>사용</option>
												</select>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="cf_use_point">포인트 사용</label></th>
											<td>
												<?php echo help("<a href=\"".G5_ADMIN_URL."/config_form.php#frm_board\" target=\"_blank\">환경설정 &gt; 기본환경설정</a>과 동일한 설정입니다."); ?>
												<input type="checkbox" name="cf_use_point" value="1" id="cf_use_point"<?php echo $config['cf_use_point']?' checked':''; ?>> 사용
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_settle_min_point">결제 최소포인트</label></th>
											<td>
												<?php echo help("회원의 포인트가 설정값 이상일 경우만 주문시 결제에 사용할 수 있습니다.\n포인트 사용을 하지 않는 경우에는 의미가 없습니다."); ?>
												<input type="text" name="de_settle_min_point" value="<?php echo $default['de_settle_min_point']; ?>" id="de_settle_min_point" class="frm_input form-control" size="10"> 점
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_settle_max_point">최대 결제포인트</label></th>
											<td>
												<?php echo help("주문 결제시 최대로 사용할 수 있는 포인트를 설정합니다.\n포인트 사용을 하지 않는 경우에는 의미가 없습니다."); ?>
												<input type="text" name="de_settle_max_point" value="<?php echo $default['de_settle_max_point']; ?>" id="de_settle_max_point" class="frm_input form-control" size="10"> 점
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_settle_point_unit">결제 포인트단위</label></th>
											<td>
												<?php echo help("주문 결제시 사용되는 포인트의 절사 단위를 설정합니다."); ?>
												<select id="de_settle_point_unit" name="de_settle_point_unit">
													<option value="100" <?php echo get_selected($default['de_settle_point_unit'], 100); ?>>100</option>
													<option value="10"  <?php echo get_selected($default['de_settle_point_unit'],  10); ?>>10</option>
													<option value="1"   <?php echo get_selected($default['de_settle_point_unit'],   1); ?>>1</option>
												</select> 점
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_card_point">포인트부여</label></th>
											<td>
												<?php echo help("신용카드, 계좌이체, 휴대폰 결제시 포인트를 부여할지를 설정합니다. (기본값은 '아니오')"); ?>
												<select id="de_card_point" name="de_card_point">
													<option value="0" <?php echo get_selected($default['de_card_point'], 0); ?>>아니오</option>
													<option value="1" <?php echo get_selected($default['de_card_point'], 1); ?>>예</option>
												</select>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_point_days">주문완료 포인트</label></th>
											<td>
												<?php echo help("주문자가 회원일 경우에만 주문완료시 포인트를 지급합니다. 주문취소, 반품 등을 고려하여 포인트를 지급할 적당한 기간을 입력하십시오. (기본값은 7일)\n0일로 설정하는 경우에는 주문완료와 동시에 포인트를 지급합니다."); ?>
												주문 완료 <input type="text" name="de_point_days" value="<?php echo $default['de_point_days']; ?>" id="de_point_days" class="frm_input form-control" size="2"> 일 이후에 포인트를 지급
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_pg_service">결제대행사</label></th>
											<td>
												<input type="hidden" name="de_pg_service" id="de_pg_service" value="<?php echo $default['de_pg_service']; ?>" >
												<?php echo help('쇼핑몰에서 사용할 결제대행사를 선택합니다.'); ?>
												<ul class="de_pg_tab">
													<li class="<?php if($default['de_pg_service'] == 'kcp') echo 'tab-current'; ?>"><a href="#kcp_info_anchor" data-value="kcp" title="NHN KCP 선택하기" >NHN KCP</a></li>
													<li class="<?php if($default['de_pg_service'] == 'lg') echo 'tab-current'; ?>"><a href="#lg_info_anchor" data-value="lg" title="LG유플러스 선택하기">LG유플러스</a></li>
													<li class="<?php if($default['de_pg_service'] == 'inicis') echo 'tab-current'; ?>"><a href="#inicis_info_anchor" data-value="inicis" title="KG이니시스 선택하기">KG이니시스</a></li>
												</ul>
											</td>
										</tr>
										<tr class="pg_info_fld kcp_info_fld" id="kcp_info_anchor">
											<th scope="row">
												<label for="de_kcp_mid">KCP SITE CODE</label><br>
												<!-- <a href="<?php echo SUPPORT; ?>/service/p_pg.php" target="_blank" id="scf_kcpreg" class="kcp_btn">NHN KCP서비스신청하기</a> -->
											</th>
											<td>
												<?php echo help("NHN KCP 에서 받은 SITE CODE 를 입력하세요. 예) SR9A3"); ?>
												<input type="text" name="de_kcp_mid" value="<?php echo $default['de_kcp_mid']; ?>" id="de_kcp_mid" class="frm_input form-control code_input" size="2" maxlength="3"> 영대문자, 숫자 혼용
											</td>
										</tr>
										<tr class="pg_info_fld kcp_info_fld">
											<th scope="row"><label for="de_kcp_site_key">NHN KCP SITE KEY</label></th>
											<td>
												<?php echo help("25자리 영대소문자와 숫자 - 그리고 _ 로 이루어 집니다. SITE KEY 발급 NHN KCP 전화: 1544-8660\n예) 1Q9YRV83gz6TukH8PjH0xFf__"); ?>
												<input type="text" name="de_kcp_site_key" value="<?php echo $default['de_kcp_site_key']; ?>" id="de_kcp_site_key" class="frm_input form-control" size="36" maxlength="25">
											</td>
										</tr>
										<tr class="pg_info_fld lg_info_fld" id="lg_info_anchor">
											<th scope="row">
												<label for="cf_lg_mid">LG유플러스 상점아이디</label><br>
												<!-- <a href="<?php echo SUPPORT; ?>/service/lg_pg.php" target="_blank" id="scf_lgreg" class="lg_btn">LG유플러스 서비스신청하기</a> -->
											</th>
											<td>
												<?php echo help("LG유플러스에서 받은 상점 ID를 입력하세요. 예) lgdacomxpay\n<a href=\"".G5_ADMIN_URL."/config_form.php#anc_cf_cert\">기본환경설정 &gt; 본인확인</a> 설정의 LG유플러스 상점아이디와 동일합니다."); ?>
												<input type="text" name="cf_lg_mid" value="<?php echo $config['cf_lg_mid']; ?>" id="cf_lg_mid" class="frm_input form-control code_input" size="10" maxlength="20"> 영문자, 숫자 혼용
											</td>
										</tr>
										<tr class="pg_info_fld lg_info_fld">
											<th scope="row"><label for="cf_lg_mert_key">LG유플러스 MERT KEY</label></th>
											<td>
												<?php echo help("LG유플러스 상점MertKey는 상점관리자 -> 계약정보 -> 상점정보관리에서 확인하실 수 있습니다.\n예) 95160cce09854ef44d2edb2bfb05f9f3\n<a href=\"".G5_ADMIN_URL."/config_form.php#anc_cf_cert\">기본환경설정 &gt; 본인확인</a> 설정의 LG유플러스 MERT KEY와 동일합니다."); ?>
												<input type="text" name="cf_lg_mert_key" value="<?php echo $config['cf_lg_mert_key']; ?>" id="cf_lg_mert_key" class="frm_input form-control " size="36" maxlength="50">
											</td>
										</tr>
										<tr class="pg_info_fld inicis_info_fld" id="inicis_info_anchor">
											<th scope="row">
												<label for="de_inicis_mid">KG이니시스 상점아이디</label><br>
												<!-- <a href="<?php echo SUPPORT; ?>/service/inicis_pg.php" target="_blank" id="scf_kgreg" class="kg_btn">KG이니시스 서비스신청하기</a> -->
											</th>
											<td>
												<?php echo help("KG이니시스로 부터 발급 받으신 상점아이디(MID) 입력 합니다. 예) SIRpaytest"); ?>
												<input type="text" name="de_inicis_mid" value="<?php echo $default['de_inicis_mid']; ?>" id="de_inicis_mid" class="frm_input form-control code_input" size="10" maxlength="10"> 영문소문자(숫자포함 가능)
											</td>
										</tr>
										<tr class="pg_info_fld inicis_info_fld">
											<th scope="row"><label for="de_inicis_admin_key">KG이니시스 키패스워드</label></th>
											<td>
												<?php echo help("KG이니시스에서 발급받은 4자리 상점 키패스워드를 입력합니다.\nKG이니시스 상점관리자 패스워드와 관련이 없습니다.\n키패스워드 값을 확인하시려면 상점측에 발급된 키파일 안의 readme.txt 파일을 참조해 주십시오"); ?>
												<input type="text" name="de_inicis_admin_key" value="<?php echo $default['de_inicis_admin_key']; ?>" id="de_inicis_admin_key" class="frm_input form-control" size="5" maxlength="4">
											</td>
										</tr>
										<tr class="pg_info_fld inicis_info_fld">
											<th scope="row"><label for="de_inicis_sign_key">KG이니시스 웹결제 사인키</label></th>
											<td>
												<?php echo help("KG이니시스에서 발급받은 웹결제 사인키를 입력합니다.\n관리자 페이지의 상점정보 > 계약정보 > 부가정보의 웹결제 signkey생성 조회 버튼 클릭, 팝업창에서 생성 버튼 클릭 후 해당 값을 입력합니다."); ?>
												<input type="text" name="de_inicis_sign_key" value="<?php echo $default['de_inicis_sign_key']; ?>" id="de_inicis_sign_key" class="frm_input form-control" size="40" maxlength="50">
											</td>
										</tr>
										<tr class="pg_info_fld inicis_info_fld">
											<th scope="row">
												<label for="de_samsung_pay_use">KG이니시스 삼성페이 사용</label>
												<!-- <a href="<?php echo SUPPORT; ?>/service/samsungpay.php" target="_blank" class="kg_btn">삼성페이 서비스신청하기</a> -->
											</th>
											<td>
												<?php echo help("체크시 KG이니시스 삼성페이를 사용합니다.( 모바일 결제시 주문화면에 삼성페이 버튼이 출력됩니다. ) <br >실결제시 반드시 결제대행사 KG이니시스 항목에 상점 아이디와 키패스워드를 입력해 주세요.", 50); ?>
												<input type="checkbox" name="de_samsung_pay_use" value="1" id="de_samsung_pay_use"<?php echo $default['de_samsung_pay_use']?' checked':''; ?>> <label for="de_samsung_pay_use">사용</label>
											</td>
										</tr>
										<tr class="pg_info_fld inicis_info_fld">
											<th scope="row">
												<label for="de_inicis_lpay_use">KG이니시스 L.pay 사용</label>
											</th>
											<td>
												<?php echo help("체크시 KG이니시스 L.pay를 사용합니다. <br >실결제시 반드시 결제대행사 KG이니시스 항목의 상점 정보( 아이디, 키패스워드, 웹결제 사인키 )를 입력해 주세요.", 50); ?>
												<input type="checkbox" name="de_inicis_lpay_use" value="1" id="de_inicis_lpay_use"<?php echo $default['de_inicis_lpay_use']?' checked':''; ?>> <label for="de_inicis_lpay_use">사용</label>
											</td>
										</tr>
										<tr class="pg_info_fld inicis_info_fld">
											<th scope="row">
												<label for="de_inicis_cartpoint_use">KG이니시스 신용카드 포인트 결제</label>
											</th>
											<td>
												<?php echo help("신용카드 포인트 결제에 대해 이니시스와 계약을 맺은 상점에서만 적용하는 옵션입니다.<br>체크시 pc 결제에서는 신용카드 포인트 사용 여부에 대한 팝업창에 사용 버튼과 사용안함 버튼이 표기되어 결제하는 고객의 선택여부에 따라 신용카드 포인트 결제가 가능합니다.<br >모바일에서는 신용카드 포인트 사용이 가능합니다.", 50); ?>
												<input type="checkbox" name="de_inicis_cartpoint_use" value="1" id="de_inicis_cartpoint_use"<?php echo $default['de_inicis_cartpoint_use']?' checked':''; ?>> <label for="de_inicis_cartpoint_use">사용</label>
											</td>
										</tr>
										<tr class="kakao_info_fld">
											<th scope="row">
												<label for="de_kakaopay_mid">카카오페이 상점MID</label>
												<!-- <a href="<?php echo SUPPORT; ?>/service/kakaopay.php" target="_blank" class="kakao_btn">카카오페이 서비스신청하기</a> -->
											</th>
											<td>
												<?php echo help("카카오페이로 부터 발급 받으신 상점아이디(MID) 입력 합니다. 예) KHSIRtestm"); ?>
												<input type="text" name="de_kakaopay_mid" value="<?php echo $default['de_kakaopay_mid']; ?>" id="de_kakaopay_mid" class="frm_input form-control code_input" size="5" maxlength="4">
											</td>
										</tr>
										<tr class="kakao_info_fld">
											<th scope="row"><label for="de_kakaopay_key">카카오페이 상점키</label></th>
											<td>
												<?php echo help("카카오페이로 부터 발급 받으신 상점 서명키를 입력합니다."); ?>
												<input type="text" name="de_kakaopay_key" value="<?php echo $default['de_kakaopay_key']; ?>" id="de_kakaopay_key" class="frm_input form-control" size="100">
											</td>
										</tr>
										<tr class="kakao_info_fld">
											<th scope="row"><label for="de_kakaopay_enckey">카카오페이 상점 EncKey</label></th>
											<td>
												<?php echo help("카카오페이로 부터 발급 받으신 상점 인증 전용 EncKey를 입력합니다."); ?>
												<input type="text" name="de_kakaopay_enckey" value="<?php echo $default['de_kakaopay_enckey']; ?>" id="de_kakaopay_enckey" class="frm_input form-control" size="20">
											</td>
										</tr>
										<tr class="kakao_info_fld">
											<th scope="row"><label for="de_kakaopay_hashkey">카카오페이 상점 HashKey</label></th>
											<td>
												<?php echo help("카카오페이로 부터 발급 받으신 상점 인증 전용 HashKey를 입력합니다."); ?>
												<input type="text" name="de_kakaopay_hashkey" value="<?php echo $default['de_kakaopay_hashkey']; ?>" id="de_kakaopay_hashkey" class="frm_input form-control" size="20">
											</td>
										</tr>
										<tr class="kakao_info_fld">
											<th scope="row"><label for="de_kakaopay_cancelpwd">카카오페이 결제취소 비밀번호</label></th>
											<td>
												<?php echo help("카카오페이 상점관리자에서 설정하신 취소 비밀번호를 입력합니다.<br>입력하신 비밀번호와 상점관리자에서 설정하신 비밀번호가 일치하지 않으면 취소가 되지 않습니다."); ?>
												<input type="text" name="de_kakaopay_cancelpwd" value="<?php echo $default['de_kakaopay_cancelpwd']; ?>" id="de_kakaopay_cancelpwd" class="frm_input form-control" size="20">
											</td>
										</tr>
										<tr class="naver_info_fld">
											<th scope="row">
												<label for="de_naverpay_mid">네이버페이 가맹점 아이디</label>
												<!-- <a href="<?php echo SUPPORT; ?>/service/naverpay.php" target="_blank" class="naver_btn">네이버페이 서비스신청하기</a> -->
											</th>
											<td>
												<?php echo help("네이버페이 가맹점 아이디를 입력합니다."); ?>
												<input type="text" name="de_naverpay_mid" value="<?php echo $default['de_naverpay_mid']; ?>" id="de_naverpay_mid" class="frm_input form-control" size="20" maxlength="50">
											 </td>
										</tr>
										<tr class="naver_info_fld">
											<th scope="row">
												<label for="de_naverpay_cert_key">네이버페이 가맹점 인증키</label>
											</th>
											<td>
												<?php echo help("네이버페이 가맹점 인증키를 입력합니다."); ?>
												<input type="text" name="de_naverpay_cert_key" value="<?php echo $default['de_naverpay_cert_key']; ?>" id="de_naverpay_cert_key" class="frm_input form-control" size="50" maxlength="100">
											 </td>
										</tr>
										<tr class="naver_info_fld">
											<th scope="row">
												<label for="de_naverpay_button_key">네이버페이 버튼 인증키</label>
											</th>
											<td>
												<?php echo help("네이버페이 버튼 인증키를 입력합니다."); ?>
												<input type="text" name="de_naverpay_button_key" value="<?php echo $default['de_naverpay_button_key']; ?>" id="de_naverpay_button_key" class="frm_input form-control" size="50" maxlength="100">
											 </td>
										</tr>
										<tr class="naver_info_fld">
											<th scope="row"><label for="de_naverpay_test">네이버페이 결제테스트</label></th>
											<td>
												<?php echo help("네이버페이 결제테스트 여부를 설정합니다. 검수 과정 중에는 <strong>예</strong>로 설정해야 하며 최종 승인 후 <strong>아니오</strong>로 설정합니다."); ?>
												<select id="de_naverpay_test" name="de_naverpay_test">
													<option value="1" <?php echo get_selected($default['de_naverpay_test'], 1); ?>>예</option>
													<option value="0" <?php echo get_selected($default['de_naverpay_test'], 0); ?>>아니오</option>
												</select>
											</td>
										</tr>
										<tr class="naver_info_fld">
											<th scope="row">
												<label for="de_naverpay_mb_id">네이버페이 결제테스트 아이디</label>
											</th>
											<td>
												<?php echo help("네이버페이 결제테스트를 위한 테스트 회원 아이디를 입력합니다. 네이버페이 검수 과정에서 필요합니다."); ?>
												<input type="text" name="de_naverpay_mb_id" value="<?php echo $default['de_naverpay_mb_id']; ?>" id="de_naverpay_mb_id" class="frm_input form-control" size="20" maxlength="20">
											 </td>
										</tr>
										<tr class="naver_info_fld">
											<th scope="row">네이버페이 상품정보 XML URL</th>
											<td>
												<?php echo help("네이버페이에 상품정보를 XML 데이터로 제공하는 페이지입니다. 검수과정에서 아래의 URL 정보를 제공해야 합니다."); ?>
												<?php echo G5_SHOP_URL; ?>/naverpay/naverpay_item.php
											 </td>
										</tr>
										<tr class="naver_info_fld">
											<th scope="row">
												<label for="de_naverpay_sendcost">네이버페이 추가배송비 안내</label>
											</th>
											<td>
												<?php echo help("네이버페이를 통한 결제 때 구매자에게 보여질 추가배송비 내용을 입력합니다.<br>예) 제주도 3,000원 추가, 제주도 외 도서·산간 지역 5,000원 추가"); ?>
												<input type="text" name="de_naverpay_sendcost" value="<?php echo $default['de_naverpay_sendcost']; ?>" id="de_naverpay_sendcost" class="frm_input form-control" size="70">
											 </td>
										</tr>
										<tr>
											<th scope="row">에스크로 사용</th>
											<td>
												<?php echo help("에스크로 결제를 사용하시려면, 반드시 결제대행사 상점 관리자 페이지에서 에스크로 서비스를 신청하신 후 사용하셔야 합니다.\n에스크로 사용시 배송과의 연동은 되지 않으며 에스크로 결제만 지원됩니다."); ?>
													<input type="radio" name="de_escrow_use" value="0" <?php echo $default['de_escrow_use']==0?"checked":""; ?> id="de_escrow_use1">
													<label for="de_escrow_use1">일반결제 사용</label>
													<input type="radio" name="de_escrow_use" value="1" <?php echo $default['de_escrow_use']==1?"checked":""; ?> id="de_escrow_use2">
													<label for="de_escrow_use2"> 에스크로결제 사용</label>
											</td>
										</tr>
										<tr>
											<th scope="row">결제 테스트</th>
											<td>
												<?php echo help("PG사의 결제 테스트를 하실 경우에 체크하세요. 결제단위 최소 1,000원"); ?>
												<input type="radio" name="de_card_test" value="0" <?php echo $default['de_card_test']==0?"checked":""; ?> id="de_card_test1">
												<label for="de_card_test1">실결제 </label>
												<input type="radio" name="de_card_test" value="1" <?php echo $default['de_card_test']==1?"checked":""; ?> id="de_card_test2">
												<label for="de_card_test2">테스트결제</label>
												<div class="scf_cardtest kcp_cardtest">
													<a href="http://admin.kcp.co.kr/" target="_blank" class="btn_frmline">실결제 관리자</a>
													<a href="http://testadmin8.kcp.co.kr/" target="_blank" class="btn_frmline">테스트 관리자</a>
												</div>
												<div class="scf_cardtest lg_cardtest">
													<a href="https://pgweb.uplus.co.kr/" target="_blank" class="btn_frmline">실결제 관리자</a>
													<a href="https://pgweb.uplus.co.kr/tmert" target="_blank" class="btn_frmline">테스트 관리자</a>
												</div>
												<div class="scf_cardtest inicis_cardtest">
													<a href="https://iniweb.inicis.com/" target="_blank" class="btn_frmline">상점 관리자</a>
												</div>
												<div id="scf_cardtest_tip">
													<strong>일반결제 사용시 테스트 결제</strong>
													<dl>
														<dt>신용카드</dt><dd>1000원 이상, 모든 카드가 테스트 되는 것은 아니므로 여러가지 카드로 결제해 보셔야 합니다.<br>(BC, 현대, 롯데, 삼성카드)</dd>
														<dt>계좌이체</dt><dd>150원 이상, 계좌번호, 비밀번호는 가짜로 입력해도 되며, 주민등록번호는 공인인증서의 것과 일치해야 합니다.</dd>
														<dt>가상계좌</dt><dd>1원 이상, 모든 은행이 테스트 되는 것은 아니며 "해당 은행 계좌 없음" 자주 발생함.<br>(광주은행, 하나은행)</dd>
														<dt>휴대폰</dt><dd>1004원, 실결제가 되며 다음날 새벽에 일괄 취소됨</dd>
													</dl>
													<strong>에스크로 사용시 테스트 결제</strong><br>
													<dl>
														<dt>신용카드</dt><dd>1000원 이상, 모든 카드가 테스트 되는 것은 아니므로 여러가지 카드로 결제해 보셔야 합니다.<br>(BC, 현대, 롯데, 삼성카드)</dd>
														<dt>계좌이체</dt><dd>150원 이상, 계좌번호, 비밀번호는 가짜로 입력해도 되며, 주민등록번호는 공인인증서의 것과 일치해야 합니다.</dd>
														<dt>가상계좌</dt><dd>1원 이상, 입금통보는 제대로 되지 않음.</dd>
														<dt>휴대폰</dt><dd>테스트 지원되지 않음.</dd>
													</dl>
													<ul id="kcp_cardtest_tip" class="scf_cardtest_tip_adm scf_cardtest_tip_adm_hide">
														<li>테스트결제의 <a href="http://testadmin8.kcp.co.kr/assist/login.LoginAction.do" target="_blank">상점관리자</a> 로그인 정보는 NHN KCP로 문의하시기 바랍니다. (기술지원 1544-8661)</li>
														<li><b>일반결제</b>의 테스트 사이트코드는 <b>T0000</b> 이며, <b>에스크로 결제</b>의 테스트 사이트코드는 <b>T0007</b> 입니다.</li>
													</ul>
													<ul id="lg_cardtest_tip" class="scf_cardtest_tip_adm scf_cardtest_tip_adm_hide">
														<li>테스트결제의 <a href="http://pgweb.dacom.net:7085/" target="_blank">상점관리자</a> 로그인 정보는 LG유플러스 상점아이디 첫 글자에 t를 추가해서 로그인하시기 바랍니다. 예) tsi_lguplus</li>
													</ul>
													<ul id="inicis_cardtest_tip" class="scf_cardtest_tip_adm scf_cardtest_tip_adm_hide">
														<li><b>일반결제</b>의 테스트 사이트 mid는 <b>INIpayTest</b> 이며, <b>에스크로 결제</b>의 테스트 사이트 mid는 <b>iniescrow0</b> 입니다.</li>
													</ul>
												</div>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_tax_flag_use">복합과세 결제</label></th>
											<td>
												 <?php echo help("복합과세(과세, 비과세) 결제를 사용하려면 체크하십시오.\n복합과세 결제를 사용하기 전 PG사에 별도로 결제 신청을 해주셔야 합니다. 사용시 PG사로 문의하여 주시기 바랍니다."); ?>
												<input type="checkbox" name="de_tax_flag_use" value="1" id="de_tax_flag_use"<?php echo $default['de_tax_flag_use']?' checked':''; ?>> 사용
											</td>
										</tr>
										</tbody>
										</table>
										<script>
										$('#scf_cardtest_tip').addClass('scf_cardtest_tip');
										$('<button type="button" class="scf_cardtest_btn btn_frmline">테스트결제 팁 더보기</button>').appendTo('.scf_cardtest');

										$(".scf_cardtest").addClass("scf_cardtest_hide");
										$(".<?php echo $default['de_pg_service']; ?>_cardtest").removeClass("scf_cardtest_hide");
										$("#<?php echo $default['de_pg_service']; ?>_cardtest_tip").removeClass("scf_cardtest_tip_adm_hide");
										</script>
									</div>
								</div>
								<!-- /.tab-pane -->
								<div class="tab-pane" id="tab_6">
									<div class="tbl_frm01 tbl_wrap">
										<table id="example2" class="table table-bordered table-hover dataTable configform">
										<!-- <caption>배송설정 입력</caption> -->
										<colgroup>
											<col class="grid_4">
											<col>
										</colgroup>
										<tbody>
										<tr>
											<th scope="row"><label for="de_delivery_company">배송업체</label></th>
											<td>
												<?php echo help("이용 중이거나 이용하실 배송업체를 선택하세요."); ?>
												<select name="de_delivery_company" id="de_delivery_company">
													<?php echo get_delivery_company($default['de_delivery_company']); ?>
												</select>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_send_cost_case">배송비유형</label></th>
											<td>
												<?php echo help("<strong>금액별차등</strong>으로 설정한 경우, 주문총액이 배송비상한가 미만일 경우 배송비를 받습니다.\n<strong>무료배송</strong>으로 설정한 경우, 배송비상한가 및 배송비를 무시하며 착불의 경우도 무료배송으로 설정합니다.\n<strong>상품별로 배송비 설정을 한 경우 상품별 배송비 설정이 우선</strong> 적용됩니다.\n예를 들어 무료배송으로 설정했을 때 특정 상품에 배송비가 설정되어 있으면 주문시 배송비가 부과됩니다."); ?>
												<select name="de_send_cost_case" id="de_send_cost_case">
													<option value="차등" <?php echo get_selected($default['de_send_cost_case'], "차등"); ?>>금액별차등</option>
													<option value="무료" <?php echo get_selected($default['de_send_cost_case'], "무료"); ?>>무료배송</option>
												</select>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_send_cost_limit">배송비상한가</label></th>
											<td>
												<?php echo help("배송비유형이 '금액별차등'일 경우에만 해당되며 배송비상한가를 여러개 두고자 하는 경우는 <b>;</b> 로 구분합니다.\n\n예를 들어 20000원 미만일 경우 4000원, 30000원 미만일 경우 3000원 으로 사용할 경우에는 배송비상한가를 20000;30000 으로 입력하고 배송비를 4000;3000 으로 입력합니다."); ?>
												<input type="text" name="de_send_cost_limit" value="<?php echo $default['de_send_cost_limit']; ?>" size="40" class="frm_input form-control" id="de_send_cost_limit"> 원
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_send_cost_list">배송비</label></th>
											<td>
												<input type="text" name="de_send_cost_list" value="<?php echo $default['de_send_cost_list']; ?>" size="40" class="frm_input form-control" id="de_send_cost_list"> 원
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_hope_date_use">희망배송일사용</label></th>
											<td>
												<?php echo help("'예'로 설정한 경우 주문서에서 희망배송일을 입력 받습니다."); ?>
												<select name="de_hope_date_use" id="de_hope_date_use">
													<option value="0" <?php echo get_selected($default['de_hope_date_use'], 0); ?>>사용안함</option>
													<option value="1" <?php echo get_selected($default['de_hope_date_use'], 1); ?>>사용</option>
												</select>
											</td>
										</tr>
										<tr>
											 <th scope="row"><label for="de_hope_date_after">희망배송일지정</label></th>
											<td>
												<?php echo help("오늘을 포함하여 설정한 날 이후부터 일주일 동안을 달력 형식으로 노출하여 선택할수 있도록 합니다."); ?>
												<input type="text" name="de_hope_date_after" value="<?php echo $default['de_hope_date_after']; ?>" id="de_hope_date_after" class="frm_input form-control" size="5"> 일
											</td>
										</tr>
										<tr>
											<th scope="row">배송정보</th>
											<td><?php echo editor_html('de_baesong_content', get_text($default['de_baesong_content'], 0)); ?></td>
										</tr>
										<tr>
											<th scope="row">교환/반품</th>
											<td><?php echo editor_html('de_change_content', get_text($default['de_change_content'], 0)); ?></td>
										</tr>
										</tbody>
										</table>
									</div>
								</div>
								<!-- /.tab-pane -->
								<div class="tab-pane" id="tab_7">
									<div class="tbl_frm01 tbl_wrap">
										<table id="example2" class="table table-bordered table-hover dataTable configform">
										<!-- <caption>기타 설정</caption> -->
										<colgroup>
											<col class="grid_4">
											<col>
										</colgroup>
										<tbody>
										<tr>
											<th scope="row">관련상품출력</th>
											<td>
												<?php echo help("관련상품의 경우 등록된 상품은 모두 출력하므로 '출력할 줄 수'는 설정하지 않습니다. 이미지높이를 0으로 설정하면 상품이미지를 이미지폭에 비례하여 생성합니다."); ?>
												<label for="de_rel_list_skin">스킨</label>
												<select name="de_rel_list_skin" id="de_rel_list_skin">
													<?php echo get_list_skin_options("^relation.[0-9]+\.skin\.php", G5_SHOP_SKIN_PATH, $default['de_rel_list_skin']); ?>
												</select>
												<label for="de_rel_img_width">이미지폭</label>
												<input type="text" name="de_rel_img_width" value="<?php echo $default['de_rel_img_width']; ?>" id="de_rel_img_width" class="frm_input form-control" size="3">
												<label for="de_rel_img_height">이미지높이</label>
												<input type="text" name="de_rel_img_height" value="<?php echo $default['de_rel_img_height']; ?>" id="de_rel_img_height" class="frm_input form-control" size="3">
												<label for="de_rel_list_mod">1줄당 이미지 수</label>
												<input type="text" name="de_rel_list_mod" value="<?php echo $default['de_rel_list_mod']; ?>" id="de_rel_list_mod" class="frm_input form-control" size="3">
												<label for="de_rel_list_use">출력</label>
												<input type="checkbox" name="de_rel_list_use" value="1" id="de_rel_list_use" <?php echo $default['de_rel_list_use']?"checked":""; ?>>
											</td>
										</tr>
										<tr>
											<th scope="row">모바일 관련상품출력</th>
											<td>
												<?php echo help("관련상품의 경우 등록된 상품은 모두 출력하므로 '출력할 줄 수'는 설정하지 않습니다. 이미지높이를 0으로 설정하면 상품이미지를 이미지폭에 비례하여 생성합니다."); ?>
												<label for="de_mobile_rel_list_skin">스킨</label>
												<select name="de_mobile_rel_list_skin" id="de_mobile_rel_list_skin">
													<?php echo get_list_skin_options("^relation.[0-9]+\.skin\.php", G5_MSHOP_SKIN_PATH, $default['de_mobile_rel_list_skin']); ?>
												</select>
												<label for="de_mobile_rel_img_width">이미지폭</label>
												<input type="text" name="de_mobile_rel_img_width" value="<?php echo $default['de_mobile_rel_img_width']; ?>" id="de_mobile_rel_img_width" class="frm_input form-control" size="3">
												<label for="de_mobile_rel_img_height">이미지높이</label>
												<input type="text" name="de_mobile_rel_img_height" value="<?php echo $default['de_mobile_rel_img_height']; ?>" id="de_mobile_rel_img_height" class="frm_input form-control" size="3">
												<label for="de_mobile_rel_list_mod">1줄당 이미지 수</label>
												<input type="text" name="de_mobile_rel_list_mod" value="<?php echo $default['de_mobile_rel_list_mod']; ?>" id="de_mobile_rel_list_mod" class="frm_input form-control" size="3">
												<label for="de_mobile_rel_list_use">출력</label>
												<input type="checkbox" name="de_mobile_rel_list_use" value="1" id="de_mobile_rel_list_use" <?php echo $default['de_mobile_rel_list_use']?"checked":""; ?>>
											</td>
										</tr>
										<tr>
											<th scope="row">검색상품출력</th>
											<td>
												<label for="de_search_list_skin">스킨</label>
												<select name="de_search_list_skin" id="de_search_list_skin">
													<?php echo get_list_skin_options("^list.[0-9]+\.skin\.php", G5_SHOP_SKIN_PATH, $default['de_search_list_skin']); ?>
												</select>
												<label for="de_search_img_width">이미지폭</label>
												<input type="text" name="de_search_img_width" value="<?php echo $default['de_search_img_width']; ?>" id="de_search_img_width" class="frm_input form-control" size="3">
												<label for="de_search_img_height">이미지높이</label>
												<input type="text" name="de_search_img_height" value="<?php echo $default['de_search_img_height']; ?>" id="de_search_img_height" class="frm_input form-control" size="3">
												<label for="de_search_list_mod">1줄당 이미지 수</label>
												<input type="text" name="de_search_list_mod" value="<?php echo $default['de_search_list_mod']; ?>" id="de_search_list_mod" class="frm_input form-control" size="3">
												<label for="de_search_list_row">출력할 줄 수</label>
												<input type="text" name="de_search_list_row" value="<?php echo $default['de_search_list_row']; ?>" id="de_search_list_row" class="frm_input form-control" size="3">
											</td>
										</tr>
										<tr>
											<th scope="row">모바일 검색상품출력</th>
											<td>
												<label for="de_mobile_search_list_skin">스킨</label>
												<select name="de_mobile_search_list_skin" id="de_mobile_search_list_skin">
													<?php echo get_list_skin_options("^list.[0-9]+\.skin\.php", G5_MSHOP_SKIN_PATH, $default['de_mobile_search_list_skin']); ?>
												</select>
												<label for="de_mobile_search_img_width">이미지폭</label>
												<input type="text" name="de_mobile_search_img_width" value="<?php echo $default['de_mobile_search_img_width']; ?>" id="de_mobile_search_img_width" class="frm_input form-control" size="3">
												<label for="de_mobile_search_img_height">이미지높이</label>
												<input type="text" name="de_mobile_search_img_height" value="<?php echo $default['de_mobile_search_img_height']; ?>" id="de_mobile_search_img_height" class="frm_input form-control" size="3">
												<label for="de_mobile_search_list_mod">1줄당 이미지 수</label>
												<input type="text" name="de_mobile_search_list_mod" value="<?php echo $default['de_mobile_search_list_mod']; ?>" id="de_mobile_search_list_mod" class="frm_input form-control" size="3">
												<label for="de_mobile_search_list_row">출력할 줄 수</label>
												<input type="text" name="de_mobile_search_list_row" value="<?php echo $default['de_mobile_search_list_row']; ?>" id="de_mobile_search_list_row" class="frm_input form-control" size="3">
											</td>
										</tr>
										<tr>
											<th scope="row">유형별 상품리스트</th>
											<td>
												<label for="de_listtype_list_skin">스킨</label>
												<select name="de_listtype_list_skin" id="de_listtype_list_skin">
													<?php echo get_list_skin_options("^list.[0-9]+\.skin\.php", G5_SHOP_SKIN_PATH, $default['de_listtype_list_skin']); ?>
												</select>
												<label for="de_listtype_img_width">이미지폭</label>
												<input type="text" name="de_listtype_img_width" value="<?php echo $default['de_listtype_img_width']; ?>" id="de_listtype_img_width" class="frm_input form-control" size="3">
												<label for="de_listtype_img_height">이미지높이</label>
												<input type="text" name="de_listtype_img_height" value="<?php echo $default['de_listtype_img_height']; ?>" id="de_listtype_img_height" class="frm_input form-control" size="3">
												<label for="de_listtype_list_mod">1줄당 이미지 수</label>
												<input type="text" name="de_listtype_list_mod" value="<?php echo $default['de_listtype_list_mod']; ?>" id="de_listtype_list_mod" class="frm_input form-control" size="3">
												<label for="de_listtype_list_row">출력할 줄 수</label>
												<input type="text" name="de_listtype_list_row" value="<?php echo $default['de_listtype_list_row']; ?>" id="de_listtype_list_row" class="frm_input form-control" size="3">
											</td>
										</tr>
										<tr>
											<th scope="row">모바일 유형별 상품리스트</th>
											<td>
												<label for="de_mobile_listtype_list_skin">스킨</label>
												<select name="de_mobile_listtype_list_skin" id="de_mobile_listtype_list_skin">
													<?php echo get_list_skin_options("^list.[0-9]+\.skin\.php", G5_MSHOP_SKIN_PATH, $default['de_mobile_listtype_list_skin']); ?>
												</select>
												<label for="de_mobile_listtype_img_width">이미지폭</label>
												<input type="text" name="de_mobile_listtype_img_width" value="<?php echo $default['de_mobile_listtype_img_width']; ?>" id="de_mobile_listtype_img_width" class="frm_input form-control" size="3">
												<label for="de_mobile_listtype_img_height">이미지높이</label>
												<input type="text" name="de_mobile_listtype_img_height" value="<?php echo $default['de_mobile_listtype_img_height']; ?>" id="de_mobile_listtype_img_height" class="frm_input form-control" size="3">
												<label for="de_mobile_listtype_list_mod">1줄당 이미지 수</label>
												<input type="text" name="de_mobile_listtype_list_mod" value="<?php echo $default['de_mobile_listtype_list_mod']; ?>" id="de_mobile_listtype_list_mod" class="frm_input form-control" size="3">
												<label for="de_mobile_listtype_list_row">출력할 줄 수</label>
												<input type="text" name="de_mobile_listtype_list_row" value="<?php echo $default['de_mobile_listtype_list_row']; ?>" id="de_mobile_listtype_list_row" class="frm_input form-control" size="3">
											</td>
										</tr>
										<tr>
											<th scope="row">이미지(소)</th>
											<td>
												<?php echo help("분류리스트에서 보여지는 사이즈를 설정하시면 됩니다. 분류관리의 출력 이미지폭, 높이의 기본값으로 사용됩니다. 높이를 0 으로 설정하시면 폭에 비례하여 높이를 썸네일로 생성합니다."); ?>
												<label for="de_simg_width"><span class="sound_only">이미지(소) </span>폭</label>
												<input type="text" name="de_simg_width" value="<?php echo $default['de_simg_width']; ?>" id="de_simg_width" class="frm_input form-control" size="5"> 픽셀
												/
												<label for="de_simg_height"><span class="sound_only">이미지(소) </span>높이</label>
												<input type="text" name="de_simg_height" value="<?php echo $default['de_simg_height']; ?>" id="de_simg_height" class="frm_input form-control" size="5"> 픽셀
											</td>
										</tr>
										<tr>
											<th scope="row">이미지(중)</th>
											<td>
												<?php echo help("상품상세보기에서 보여지는 상품이미지의 사이즈를 픽셀로 설정합니다. 높이를 0 으로 설정하시면 폭에 비례하여 높이를 썸네일로 생성합니다."); ?>
												<label for="de_mimg_width"><span class="sound_only">이미지(중) </span>폭</label>
												<input type="text" name="de_mimg_width" value="<?php echo $default['de_mimg_width']; ?>" id="de_mimg_width" class="frm_input form-control" size="5"> 픽셀
												/
												<label for="de_mimg_height"><span class="sound_only">이미지(중) </span>높이</label>
												<input type="text" name="de_mimg_height" value="<?php echo $default['de_mimg_height']; ?>" id="de_mimg_height" class="frm_input form-control" size="5"> 픽셀
											</td>
										</tr>
										<tr>
											<th scope="row">상단로고이미지</th>
											<td>
												<?php echo help("쇼핑몰 상단로고를 직접 올릴 수 있습니다. 이미지 파일만 가능합니다."); ?>
												<input type="file" name="logo_img" id="logo_img">
												<?php
												$logo_img = G5_DATA_PATH."/common/logo_img";
												if (file_exists($logo_img))
												{
													$size = getimagesize($logo_img);
												?>
												<input type="checkbox" name="logo_img_del" value="1" id="logo_img_del">
												<label for="logo_img_del"><span class="sound_only">상단로고이미지</span> 삭제</label>
												<span class="scf_img_logoimg"></span>
												<div id="logoimg" class="banner_or_img">
													<img src="<?php echo G5_DATA_URL; ?>/common/logo_img" alt="">
													<button type="button" class="sit_wimg_close">닫기</button>
												</div>
												<script>
												$('<button type="button" id="cf_logoimg_view" class="btn_frmline scf_img_view">상단로고이미지 확인</button>').appendTo('.scf_img_logoimg');
												</script>
												<?php } ?>
											</td>
										</tr>
										<tr>
											<th scope="row">하단로고이미지</th>
											<td>
												<?php echo help("쇼핑몰 하단로고를 직접 올릴 수 있습니다. 이미지 파일만 가능합니다."); ?>
												<input type="file" name="logo_img2" id="logo_img2">
												<?php
												$logo_img2 = G5_DATA_PATH."/common/logo_img2";
												if (file_exists($logo_img2))
												{
													$size = getimagesize($logo_img2);
												?>
												<input type="checkbox" name="logo_img_del2" value="1" id="logo_img_del2">
												<label for="logo_img_del2"><span class="sound_only">하단로고이미지</span> 삭제</label>
												<span class="scf_img_logoimg2"></span>
												<div id="logoimg2" class="banner_or_img">
													<img src="<?php echo G5_DATA_URL; ?>/common/logo_img2" alt="">
													<button type="button" class="sit_wimg_close">닫기</button>
												</div>
												<script>
												$('<button type="button" id="cf_logoimg2_view" class="btn_frmline scf_img_view">하단로고이미지 확인</button>').appendTo('.scf_img_logoimg2');
												</script>
												<?php } ?>
											</td>
										</tr>
										<tr>
											<th scope="row">모바일 상단로고이미지</th>
											<td>
												<?php echo help("모바일 쇼핑몰 상단로고를 직접 올릴 수 있습니다. 이미지 파일만 가능합니다."); ?>
												<input type="file" name="mobile_logo_img" id="mobile_logo_img">
												<?php
												$mobile_logo_img = G5_DATA_PATH."/common/mobile_logo_img";
												if (file_exists($mobile_logo_img))
												{
													$size = getimagesize($mobile_logo_img);
												?>
												<input type="checkbox" name="mobile_logo_img_del" value="1" id="mobile_logo_img_del">
												<label for="mobile_logo_img_del"><span class="sound_only">모바일 상단로고이미지</span> 삭제</label>
												<span class="scf_img_mobilelogoimg"></span>
												<div id="mobilelogoimg" class="banner_or_img">
													<img src="<?php echo G5_DATA_URL; ?>/common/mobile_logo_img" alt="">
													<button type="button" class="sit_wimg_close">닫기</button>
												</div>
												<script>
												$('<button type="button" id="cf_mobilelogoimg_view" class="btn_frmline scf_img_view">모바일 상단로고이미지 확인</button>').appendTo('.scf_img_mobilelogoimg');
												</script>
												<?php } ?>
											</td>
										</tr>
										<tr>
											<th scope="row">모바일 하단로고이미지</th>
											<td>
												<?php echo help("모바일 쇼핑몰 하단로고를 직접 올릴 수 있습니다. 이미지 파일만 가능합니다."); ?>
												<input type="file" name="mobile_logo_img2" id="mobile_logo_img2">
												<?php
												$mobile_logo_img2 = G5_DATA_PATH."/common/mobile_logo_img2";
												if (file_exists($mobile_logo_img2))
												{
													$size = getimagesize($mobile_logo_img2);
												?>
												<input type="checkbox" name="mobile_logo_img_del2" value="1" id="mobile_logo_img_del2">
												<label for="mobile_logo_img_del2"><span class="sound_only">모바일 하단로고이미지</span> 삭제</label>
												<span class="scf_img_mobilelogoimg2"></span>
												<div id="mobilelogoimg2" class="banner_or_img">
													<img src="<?php echo G5_DATA_URL; ?>/common/mobile_logo_img2" alt="">
													<button type="button" class="sit_wimg_close">닫기</button>
												</div>
												<script>
												$('<button type="button" id="cf_mobilelogoimg2_view" class="btn_frmline scf_img_view">모바일 하단로고이미지 확인</button>').appendTo('.scf_img_mobilelogoimg2');
												</script>
												<?php } ?>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_item_use_write">사용후기 작성</label></th>
											<td>
												 <?php echo help("주문상태에 따른 사용후기 작성여부를 설정합니다.", 50); ?>
												<select name="de_item_use_write" id="de_item_use_write">
													<option value="0" <?php echo get_selected($default['de_item_use_write'], 0); ?>>주문상태와 무관하게 작성가능</option>
													<option value="1" <?php echo get_selected($default['de_item_use_write'], 1); ?>>주문상태가 완료인 경우에만 작성가능</option>
												</select>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_item_use_use">사용후기</label></th>
											<td>
												 <?php echo help("사용후기가 올라오면, 즉시 출력 혹은 관리자 승인 후 출력 여부를 설정합니다.", 50); ?>
												<select name="de_item_use_use" id="de_item_use_use">
													<option value="0" <?php echo get_selected($default['de_item_use_use'], 0); ?>>즉시 출력</option>
													<option value="1" <?php echo get_selected($default['de_item_use_use'], 1); ?>>관리자 승인 후 출력</option>
												</select>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_level_sell">상품구입 권한</label></th>
											<td>
												<?php echo help("권한을 1로 설정하면 누구나 구입할 수 있습니다. 특정회원만 구입할 수 있도록 하려면 해당 권한으로 설정하십시오."); ?>
												<?php echo get_member_level_select('de_level_sell', 1, 10, $default['de_level_sell']); ?>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_code_dup_use">코드 중복검사</label></th>
											<td>
												 <?php echo help("분류, 상품 등을 추가할 때 자동으로 코드 중복검사를 하려면 체크하십시오."); ?>
												<input type="checkbox" name="de_code_dup_use" value="1" id="de_code_dup_use"<?php echo $default['de_code_dup_use']?' checked':''; ?>> 사용
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_cart_keep_term">장바구니 보관기간</label></th>
											<td>
												 <?php echo help("장바구니 상품의 보관 기간을 설정하십시오."); ?>
												<input type="text" name="de_cart_keep_term" value="<?php echo $default['de_cart_keep_term']; ?>" id="de_cart_keep_term" class="frm_input form-control" size="5"> 일
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="de_guest_cart_use">비회원 장바구니</label></th>
											<td>
												 <?php echo help("비회원 장바구니 기능을 사용하려면 체크하십시오."); ?>
												<input type="checkbox" name="de_guest_cart_use" value="1" id="de_guest_cart_use"<?php echo $default['de_guest_cart_use']?' checked':''; ?>> 사용
											</td>
										</tr>
										<tr>
											<th scope="row">신규회원 쿠폰발행</th>
											<td>
												 <?php echo help("신규회원에게 주문금액 할인 쿠폰을 발행하시려면 아래를 설정하십시오."); ?>
												<label for="de_member_reg_coupon_use">쿠폰발행</label>
												<input type="checkbox" name="de_member_reg_coupon_use" value="1" id="de_member_reg_coupon_use"<?php echo $default['de_member_reg_coupon_use']?' checked':''; ?>>
												<label for="de_member_reg_coupon_price">쿠폰할인금액</label>
												<input type="text" name="de_member_reg_coupon_price" value="<?php echo $default['de_member_reg_coupon_price']; ?>" id="de_member_reg_coupon_price" class="frm_input form-control" size="10"> 원
												<label for="de_member_reg_coupon_minimum">주문최소금액</label>
												<input type="text" name="de_member_reg_coupon_minimum" value="<?php echo $default['de_member_reg_coupon_minimum']; ?>" id="de_member_reg_coupon_minimum" class="frm_input form-control" size="10"> 원이상
												<label for="de_member_reg_coupon_term">쿠폰유효기간</label>
												<input type="text" name="de_member_reg_coupon_term" value="<?php echo $default['de_member_reg_coupon_term']; ?>" id="de_member_reg_coupon_term" class="frm_input form-control" size="5"> 일
											</td>
										</tr>
										<tr>
											<th scope="row">비회원에 대한<br/>개인정보수집 내용</th>
											<td><?php echo editor_html('de_guest_privacy', get_text($default['de_guest_privacy'], 0)); ?></td>
										</tr>
										<tr>
											<th scope="row">MYSQL USER</th>
											<td><?php echo G5_MYSQL_USER; ?></td>
										</tr>
										<tr>
											<th scope="row">MYSQL DB</th>
											<td><?php echo G5_MYSQL_DB; ?></td>
										</tr>
										<tr>
											<th scope="row">서버 IP</th>
											<td><?php echo ($_SERVER['SERVER_ADDR']?$_SERVER['SERVER_ADDR']:$_SERVER['LOCAL_ADDR']); ?></td>
										</tr>
										</tbody>
										</table>
									</div>
								</div>
								<!-- /.tab-pane -->
								<div class="tab-pane" id="tab_8">
									<div class="tbl_frm01 tbl_wrap">
										<table id="example2" class="table table-bordered table-hover dataTable configform">
										<!-- <caption>SMS 설정</caption> -->
										<colgroup>
											<col class="grid_4">
											<col>
										</colgroup>
										<tbody>
										<tr>
											<th scope="row"><label for="cf_sms_use">SMS 사용</label></th>
											<td>
												<?php echo help("SMS  서비스 회사를 선택하십시오. 서비스 회사를 선택하지 않으면, SMS 발송 기능이 동작하지 않습니다.<br>아이코드는 무료 문자메세지 발송 테스트 환경을 지원합니다.<br><a href=\"".G5_ADMIN_URL."/config_form.php#anc_cf_sms\">기본환경설정 &gt; SMS</a> 설정과 동일합니다."); ?>
												<select id="cf_sms_use" name="cf_sms_use">
													<option value="" <?php echo get_selected($config['cf_sms_use'], ''); ?>>사용안함</option>
													<!-- <option value="icode" <?php echo get_selected($config['cf_sms_use'], 'icode'); ?>>아이코드</option> -->
													<option value="aligo" <?php echo get_selected($config['cf_sms_use'], 'aligo'); ?>>이노박스</option>
												</select>
											</td>
										</tr>

										<tr class="sms_svc sms_icode">
											<th scope="row"><label for="cf_sms_type">SMS 전송유형</label></th>
											<td>
												<?php echo help("전송유형을 SMS로 선택하시면 최대 80바이트까지 전송하실 수 있으며<br>LMS로 선택하시면 90바이트 이하는 SMS로, 그 이상은 1500바이트까지 LMS로 전송됩니다.<br>요금은 건당 SMS는 16원, LMS는 48원입니다."); ?>
												<select id="cf_sms_type" name="cf_sms_type">
													<option value="" <?php echo get_selected($config['cf_sms_type'], ''); ?>>SMS</option>
													<option value="LMS" <?php echo get_selected($config['cf_sms_type'], 'LMS'); ?>>LMS</option>
												</select>
											</td>
										</tr>
										<tr class="sms_svc sms_icode">
											<th scope="row"><label for="cf_icode_id">아이코드 회원아이디</label></th>
											<td>
												<?php echo help("아이코드에서 사용하시는 회원아이디를 입력합니다."); ?>
												<input type="text" name="cf_icode_id" value="<?php echo $config['cf_icode_id']; ?>" id="cf_icode_id" class="frm_input form-control" size="20">
											</td>
										</tr>
										<tr class="sms_svc sms_icode">
											<th scope="row"><label for="cf_icode_pw">아이코드 비밀번호</label></th>
											<td>
												<?php echo help("아이코드에서 사용하시는 비밀번호를 입력합니다."); ?>
												<input type="password" name="cf_icode_pw" value="<?php echo $config['cf_icode_pw']; ?>" class="frm_input form-control" id="cf_icode_pw">
											</td>
										</tr>
										<tr class="sms_svc sms_icode">
											<th scope="row">요금제</th>
											<td>
												<input type="hidden" name="cf_icode_server_ip" value="<?php echo $config['cf_icode_server_ip']; ?>">
												<?php
													if ($userinfo['payment'] == 'A') {
													   echo '충전제';
														echo '<input type="hidden" name="cf_icode_server_port" value="7295">';
													} else if ($userinfo['payment'] == 'C') {
														echo '정액제';
														echo '<input type="hidden" name="cf_icode_server_port" value="7296">';
													} else {
														echo '가입해주세요.';
														echo '<input type="hidden" name="cf_icode_server_port" value="7295">';
													}
												?>
											</td>
										</tr>
										<tr class="sms_svc sms_icode">
											<th scope="row">아이코드 SMS 신청<br>회원가입</th>
											<td>
												<?php echo help("아래 링크에서 회원가입 하시면 문자 건당 16원에 제공 받을 수 있습니다."); ?>
												<a href="http://icodekorea.com/res/join_company_fix_a.php?sellid=sir2" target="_blank" class="btn_frmline">아이코드 회원가입</a>
											</td>
										</tr>
										<?php if ($userinfo['payment'] == 'A') { ?>
										<tr class="sms_svc sms_icode">
											<th scope="row">충전 잔액</th>
											<td>
												<?php echo number_format($userinfo['coin']); ?> 원.
												<a href="http://www.icodekorea.com/smsbiz/credit_card_amt.php?icode_id=<?php echo $config['cf_icode_id']; ?>&amp;icode_passwd=<?php echo $config['cf_icode_pw']; ?>" target="_blank" class="btn_frmline" onclick="window.open(this.href,'icode_payment', 'scrollbars=1,resizable=1'); return false;">충전하기</a>
											</td>
										</tr>
										<?php } ?>

										<tr class="sms_svc sms_aligo">
											<th scope="row"><label for="cf_aligo_id">이노박스 회원아이디</label></th>
											<td>
												<?php echo help("이노박스에서 사용하시는 회원아이디를 입력합니다."); ?>
												<input type="text" name="cf_aligo_id" value="<?php echo $config['cf_aligo_id']; ?>" id="cf_aligo_id" class="frm_input form-control" size="20">
											</td>
										</tr>
										<tr class="sms_svc sms_aligo">
											<th scope="row"><label for="cf_aligo_pw">이노박스 비밀번호</label></th>
											<td>
												<?php echo help("이노박스에서 사용하시는 비밀번호를 입력합니다."); ?>
												<input type="password" name="cf_aligo_pw" value="<?php echo $config['cf_aligo_pw']; ?>" id="cf_aligo_pw" class="frm_input form-control">
											</td>
										</tr>

										<tr>
											<th scope="row"><label for="de_sms_hp">관리자 휴대폰번호</label></th>
											<td>
												<?php echo help("주문서작성시 쇼핑몰관리자가 문자메세지를 받아볼 번호를 숫자만으로 입력하세요. 예) 0101234567"); ?>
												<input type="text" name="de_sms_hp" value="<?php echo $default['de_sms_hp']; ?>" id="de_sms_hp" class="frm_input form-control" size="20">
											</td>
										</tr>
										 </tbody>
										</table>
									</div>
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
 $(document).scroll(function(e){
    var scrollTop = $(document).scrollTop();
    if(scrollTop > 0){
        console.log(scrollTop);
        $('.navbar').removeClass('navbar-static-top').addClass('navbar-fixed-top');
    } else {
        $('.navbar').removeClass('navbar-fixed-top').addClass('navbar-static-top');
    }
});

//모든 select 스타일 지정
var selects = document.getElementsByTagName("select"); 
for (var i = 0; i < selects.length; i++) { 
selects[i].className = "form-control"
}

//모든 select 스타일 지정
var selects = document.getElementsByTagName("button"); 
for (var i = 0; i < selects.length; i++) { 
selects[i].className = "btn btn-default"
}

$("#tab_ul li").on("click", function(){
document.body.scrollTop = 0; // For Safari
document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
});

</script>


<?php
include_once ('../footer.php');
?>

