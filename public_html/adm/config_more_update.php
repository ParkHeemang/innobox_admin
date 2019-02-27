<?php
$sub_menu = "100110";
include_once('./_common.php');

check_demo();

auth_check($auth[$sub_menu], 'w');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

check_admin_token();

$cf_social_servicelist = !empty($_POST['cf_social_servicelist']) ? implode(',', $_POST['cf_social_servicelist']) : '';

$sql = " update {$g5['config_table']}
            set cf_title = '{$_POST['cf_title']}', 
                cf_add_meta = '{$_POST['cf_add_meta']}',
                cf_syndi_token = '{$_POST['cf_syndi_token']}',
                cf_syndi_except = '{$_POST['cf_syndi_except']}',
                cf_add_script = '{$_POST['cf_add_script']}',
                cf_analytics = '{$_POST['cf_analytics']}', 
                cf_googl_shorturl_apikey      = '{$_POST['cf_googl_shorturl_apikey']}',
                cf_kakao_js_apikey            = '{$_POST['cf_kakao_js_apikey']}',
                cf_facebook_appid             = '{$_POST['cf_facebook_appid']}',
                cf_facebook_secret            = '{$_POST['cf_facebook_secret']}',
                cf_twitter_key                = '{$_POST['cf_twitter_key']}',
                cf_twitter_secret             = '{$_POST['cf_twitter_secret']}',
                cf_naver_clientid             = '{$_POST['cf_naver_clientid']}',
                cf_naver_secret               = '{$_POST['cf_naver_secret']}',
                cf_google_clientid            = '{$_POST['cf_google_clientid']}',
                cf_google_secret              = '{$_POST['cf_google_secret']}',
                cf_kakao_rest_key             = '{$_POST['cf_kakao_rest_key']}',
                cf_kakao_client_secret        = '{$_POST['cf_kakao_client_secret']}',
                cf_payco_clientid             = '{$_POST['cf_payco_clientid']}',
                cf_payco_secret               = '{$_POST['cf_payco_secret']}',
                cf_social_login_use           = '{$_POST['cf_social_login_use']}',
                cf_social_servicelist         = '{$cf_social_servicelist}'
                ";
sql_query($sql);

// 쇼핑몰 환경설정도 동일하게 입력
$sql = " update {$g5['g5_shop_default_table']}
            set de_admin_company_owner        = '{$_POST['cf_company_owner']}',
                de_admin_company_name         = '{$_POST['cf_company_name']}',
                de_admin_company_saupja_no    = '{$_POST['cf_company_saupja_no']}',
                de_admin_company_tel          = '{$_POST['cf_company_tel']}',
                de_admin_company_fax          = '{$_POST['cf_company_fax']}',
                de_admin_tongsin_no           = '{$_POST['cf_company_tongsin_no']}',
                de_admin_company_zip          = '{$_POST['cf_company_zip']}',
                de_admin_company_addr         = '{$_POST['cf_company_addr1']} {$_POST['cf_company_addr2']}',
                de_admin_info_name            = '{$_POST['cf_faoinfo_name']}',
                de_admin_info_email           = '{$_POST['cf_faoinfo_email']}', 
                ";
sql_query($sql);


$sql = " update {$g5['configure_table']}
            set cf_seo_author = '{$_POST['cf_seo_author']}',
                cf_seo_description = '{$_POST['cf_seo_description']}',
                cf_seo_keywords = '{$_POST['cf_seo_keywords']}',
                cf_company_name = '{$_POST['cf_company_name']}',
                cf_company_saupja_no = '{$_POST['cf_company_saupja_no']}',
                cf_company_owner = '{$_POST['cf_company_owner']}',
                cf_company_open = '{$_POST['cf_company_open1']}:{$_POST['cf_company_open2']}',
                cf_company_close = '{$_POST['cf_company_close1']}:{$_POST['cf_company_close2']}',
                cf_company_tel = '{$_POST['cf_company_tel']}',
                cf_company_fax = '{$_POST['cf_company_fax']}',
                cf_company_tongsin_no = '{$_POST['cf_company_tongsin_no']}',
                cf_company_buga_no = '{$_POST['cf_company_buga_no']}',
                cf_faoinfo_name = '{$_POST['cf_faoinfo_name']}',
                cf_faoinfo_email = '{$_POST['cf_faoinfo_email']}',
                cf_company_zip = '{$_POST['cf_company_zip']}',
                cf_company_addr1 = '{$_POST['cf_company_addr1']}',
                cf_company_addr2 = '{$_POST['cf_company_addr2']}',
                cf_company_addr3 = '{$_POST['cf_company_addr3']}',
                cf_company_addr_jibeon = '{$_POST['cf_company_addr_jibeon']}',
                cf_company_loc = '{$_POST['cf_company_loc']}', 
                cf_company_lat = '{$_POST['cf_company_lat']}',
                cf_company_lng = '{$_POST['cf_company_lng']}',
                cf_company_more = '{$_POST['cf_company_more']}', 
                cf_sns_blog = '{$_POST['cf_sns_blog']}',
                cf_sns_twitter = '{$_POST['cf_sns_twitter']}',
                cf_sns_facebook = '{$_POST['cf_sns_facebook']}',
                cf_sns_instagram = '{$_POST['cf_sns_instagram']}',
                cf_sns_kakao = '{$_POST['cf_sns_kakao']}',
                cf_sns_line = '{$_POST['cf_sns_line']}',
                cf_sns_nateon = '{$_POST['cf_sns_nateon']}' 
                ";
sql_query($sql);

//sql_query(" OPTIMIZE TABLE `$g5[config_table]` ");

// 이미지 업로드
@mkdir(G5_DATA_PATH.'/common' , G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH.'/common' , G5_DIR_PERMISSION);

if ($_POST['ico_img_del']) @unlink(G5_PATH."/favicon.ico");
if ($_POST['logo_img_del']) @unlink(G5_DATA_PATH."/common/logo_img");
if ($_POST['logo_img_del2']) @unlink(G5_DATA_PATH."/common/logo_img2");
if ($_POST['mobile_logo_img_del']) @unlink(G5_DATA_PATH."/common/mobile_logo_img");
if ($_POST['mobile_logo_img_del2']) @unlink(G5_DATA_PATH."/common/mobile_logo_img2");

if ($_FILES['ico_img']['name']) upload_files($_FILES['ico_img']['tmp_name'], "favicon.ico", G5_PATH."");
if ($_FILES['logo_img']['name']) upload_files($_FILES['logo_img']['tmp_name'], "logo_img", G5_DATA_PATH."/common");
if ($_FILES['logo_img2']['name']) upload_files($_FILES['logo_img2']['tmp_name'], "logo_img2", G5_DATA_PATH."/common");
if ($_FILES['mobile_logo_img']['name']) upload_files($_FILES['mobile_logo_img']['tmp_name'], "mobile_logo_img", G5_DATA_PATH."/common");
if ($_FILES['mobile_logo_img2']['name']) upload_files($_FILES['mobile_logo_img2']['tmp_name'], "mobile_logo_img2", G5_DATA_PATH."/common");




$currentTab = $_POST['current_tab'];
?>



<form name="current_tab" method="post" action="./config_more.php">
<input type="hidden" name="current_tab" value="<?php echo $currentTab?>">
</form>



<script>
document.current_tab.submit();
</script>