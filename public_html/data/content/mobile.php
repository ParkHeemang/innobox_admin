<link rel="stylesheet" href="http://startup.innobox.co.kr/theme/basic/css/sub.css">


<h2  data-wow-delay="50ms" id="container_title" class="wow fadeInUp"><?php echo $g5['title'] ?><span class="sound_only"> 목록</span></h2>
<h2 data-wow-delay="100ms" class="sub_title wow fadeInUp">MOBILE</h2>
<div data-wow-delay="150ms" class="pk_title_img wow fadeInUp"><img src="<?php echo G5_IMG_URL ?>/mobile_title.jpg" alt=""/></div>

<h3 data-wow-delay="200ms" class="sub_subject wow fadeInUp">모바일 웹, 왜 이노박스인가요?</h3>
	<div data-wow-delay="250ms" class="mb_con01 wow fadeInUp">
		<ul class="con01_ul">
			<li class="ul_li01">
				<span class="mb_title">CREATIVE DESIGN</span>
				<span class="mb_subject">창의적인 디자인</span>
			</li>
			<li class="ul_li02">
				<span class="mb_title">WEB STANDARDS</span>
				<span class="mb_subject">모바일 웹 표준 개발</span>
			</li>
			<li class="ul_li03">
				<span class="mb_title">EASY/CONVENIENT UI</span>
				<span class="mb_subject">편안하게 최적화된 UI</span>
			</li>
			<li class="ul_li04">
				<span class="mb_title">BRAND IDENTITY</span>
				<span class="mb_subject">브랜드 이미지 구축, 개선, 홍보</span>
			</li>
		</ul>
	</div>


<h3 data-wow-delay="250ms" class="sub_subject wow fadeInUp">모바일 웹/앱 개발 과정 안내 </h3>
	<div data-wow-delay="250ms" class="sp_con02 wow fadeInUp">
		<ul data-wow-delay="250ms" class="con02_ul wow fadeInUp">
			<li class="ul_li01"><span class="li_img"><img src="<?php echo G5_IMG_URL ?>/shop_ico01.png" alt="icon"/></span><span>무료상담 및 견적문의</span></li>
			<li class="ul_li02"><span class="li_img"><img src="<?php echo G5_IMG_URL ?>/shop_ico02.png" alt="icon"/></span><span>견적서 발송/계약 체결</span></li>
			<li class="ul_li03"><span class="li_img"><img src="<?php echo G5_IMG_URL ?>/shop_ico03.png" alt="icon"/></span><span>기획/기능 요구서 작성</span></li>
			<li class="ul_li04"><span class="li_img"><img src="<?php echo G5_IMG_URL ?>/shop_ico04.png" alt="icon"/></span><span>시안완료 후 퍼블리싱</br>/프로그램·기술작업</span></li>
			<li class="ul_li05"><span class="li_img"><img src="<?php echo G5_IMG_URL ?>/shop_ico05.png" alt="icon"/></span><span>웹사이트개발 완료</span></li>
			<li class="ul_li06"><span class="li_img"><img src="<?php echo G5_IMG_URL ?>/shop_ico06.png" alt="icon"/></span><span>유지보수</span></li>
		</ul>
	</div>
<h3 data-wow-delay="250ms" class="sub_subject wow fadeInUp">유지보수</h3>
	<div class="mb_con03">
		<p data-wow-delay="250ms" class="wow fadeInUp">
			홈페이지 완료 후 지속적인 관리로 경쟁력을 높여드립니다. 홈페이지 완성후 관리 또한 중요한 부분입니다.</br>
			변화가 잡은 웹트렌드에 적응하기 위해 유집수는 필연적인 부분입니다. 정기적인 관리로 홈페이지를 경쟁력 있게 유지 시켜 줄 수 있습니다.</br>
			디자인, 기능적인 부분에 변화가 필요한 곡개을 위해 이노박스의 유지보수 서비스를 이용해보세요.</br>
		</p>
		<div data-wow-delay="300ms" class="wow fadeInUp"><a href="<?php echo G5_BBS_URL ?>/content.php?co_id=maintenance">유지보수 서비스 안내 보기</a></div>
	</div>
<h3 data-wow-delay="300ms" class="sub_subject wow fadeInUp">최근 프로젝트포트폴리오</h3>
<?php echo latest('basic', 'portfolio|어플개발', 8, 16);?>

<div class="mb_btn_box">
	<p data-wow-delay="550ms" class="btn_more wow fadeInUp"><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=portfolio&sca=어플개발">포트폴리오 더보기<img src="../img/sp_more.png" alt=""/></a></p>
	<p data-wow-delay="600ms" class="btn_pay wow fadeInUp"><a href="<?php echo G5_BBS_URL ?>/write.php?bo_table=cscenter">견적 상담신청<img src="../img/pay_img.png" alt=""/></p>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function() {
		  //===================
		  //  WOW
		  //  do not touch
		  //===================
		  new WOW().init();
		});
	</script>