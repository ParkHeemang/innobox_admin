<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/tail.php');
    return;
}
?>

    
</div>

<!-- } 콘텐츠 끝 -->
<div id="foot_wrap">
	<div id="foot">
		<div id="foot_copy">
			<ul>
				<li>상호 이노박스 INNOBOX</li>
				<li>대표 정병주</li>
                <li>사업자 등록번호 408-18-43756</li>
				<li>광주광역시 서구 치평동 1288-1 지아빌딩 5층 이노박스</li>
				<li>INNOBOX_WORK@NAVER.COM</li>
				<li class="last">COPYRIGHT innobox ALL RIGHTS RESEVED</li>
			</ul>
			<h2><img src="<?php echo G5_IMG_URL ?>/foot_logo.jpg" alt="로고"/></h2>
		</div>
		<div id="foot_page">
			<p class="page_title">INNOBOX PAGES</p>
			<select class="familysite" onChange="on_page(this)">
				<option>FAMILY SITE</option>
				<option value="http://pumpkincorp.co.kr/" target="_blank">펌킨인터랙티브</option>
				<option value="http://keywordup.co.kr/" target="_blank">키워드업</option>
				<option value="http://ad.keywordup.co.kr/bbs/login.php?url=%2F1" target="_blank">키워드업(광고주)</option>
				<option value="http://sitesale.co.kr/" target="_blank">사이트세일</option>
			</select>
			<ul class="foot_menu01">
				<li><a href="<?php echo G5_BBS_URL ?>/content.php?co_id=shoppingmall">SHOPPING MALL</a></li>
				<li><a href="<?php echo G5_BBS_URL ?>/content.php?co_id=homepage">HOMEPAGE</a></li>
				<li><a href="<?php echo G5_BBS_URL ?>/content.php?co_id=mobile">MOBILE</a></li>
			</ul>
			<ul class="foot_menu02">
				<li><a href="<?php echo G5_BBS_URL ?>/content.php?co_id=marketing">MARKETING</a></li>
				<li><a href="<?php echo G5_BBS_URL ?>/content.php?co_id=maintenance">MAINTENANCE</a></li>
				<li><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=cscenter">CUSTUMER CENTER</a></li>
				<li><a href="#">TERMS AND CONDITIONS</a></li>
			</ul>
			<ul class="foot_menu03">
				<li><a href="<?php echo G5_BBS_URL ?>/login.php">SING IN</a></li>
				<li><a href="<?php echo G5_BBS_URL ?>/register.php">SING UP</a></li>
				<li><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=support">MAINTENANCE BOARD</a></li>
				<li><a href="#">개인정보 처리방침</a></li>
			</ul>
		</div>
		<p class="to_top"><a href="#"><img src="<?php echo G5_IMG_URL ?>/top.jpg" alt="top"></a></p>
	</div>
</div>

<!-- 하단 시작 { -->


<?php
if(G5_DEVICE_BUTTON_DISPLAY && !G5_IS_MOBILE) { ?>
<!--a href="<?php echo get_device_change_url(); ?>" id="device_change">모바일 버전으로 보기</a-->
<?php
}

if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>

<!-- } 하단 끝 -->

<script>
$(function() {
    // 폰트 리사이즈 쿠키있으면 실행
    font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));
});
	function on_page(sel)
	{
		if(sel.value!="")
		window.open(sel.value,'','');
	}

	$(function () {
		//$('select.familysite').customSelect();
	});
</script>

	

<?php
include_once(G5_THEME_PATH."/tail.sub.php");
?>