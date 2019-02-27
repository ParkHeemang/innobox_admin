<link rel="stylesheet" href="http://startup.innobox.co.kr/theme/basic/css/sub.css">


<h2 data-wow-delay="50ms" id="container_title" class="wow fadeInUp"><?php echo $g5['title'] ?><span class="sound_only"> 목록</span></h2>
<h2 data-wow-delay="100ms" class="sub_title wow fadeInUp">HOMEPAGE</h2>
<div data-wow-delay="150ms" class="pk_title_img wow fadeInUp"><img src="<?php echo G5_IMG_URL ?>/homepage_title.jpg" alt=""/></div>

<h3 data-wow-delay="200ms" class="sub_subject wow fadeInUp">홈페이지 개발, 왜 이노박스인가요?</h3>
	<div data-wow-delay="250ms" class="hp_con01 wow fadeInUp">
		<ul class="con01_ul">
			<li class="ul_li01">
				<span class="hp_title">CREATIVE DESIGN</span>
				<span class="hp_subject">창의적인 디자인</span>
				<span class="hp_txt">소통을 통한 아름다운</br>디지털 디자인을 만들어</br>갑니다.</span>
			</li>
			<li class="ul_li02">
				<span class="hp_title">WEB STANDARDS</span>
				<span class="hp_subject">웹표준</span>
				<span class="hp_txt">누군든, 어떤 환경이든</br>편안하게 사용할 수 있는,</br>웹표준을 지키기위해 노력합니다.</span>
			</li>
			<li class="ul_li03">
				<span class="hp_title">CROSS BROWSING</span>
				<span class="hp_subject">크로스브라우징</span>
				<span class="hp_txt">브라우저 환경에 구애 받지 않고</br>사용 가능한 홈페이지,</br>경쟁력의 시작입니다.</span>
			</li>
			<li class="ul_li04">
				<span class="hp_title">BRAND IDENTITY</span>
				<span class="hp_subject">브랜드 아이덴티티</span>
				<span class="hp_txt">고개과의 소통을 통한</br>브랜드 아이덴티티를 홈페이지에</br>표현합니다.</span>
			</li>
			<li class="ul_li05">
				<span class="hp_title">MARKETING</span>
				<span class="hp_subject">마케팅</span>
				<span class="hp_txt">홈페이지완성으로 시작되는</br>다양한 마케팅 서비스로</br>경쟁력을 높여줍니다.</span>
			</li>
		</ul>
	</div>
<h3 data-wow-delay="200ms" class="sub_subject wow fadeInUp">홈페이지 개발 과정 안내 </h3>
	<div data-wow-delay="250ms" class="sp_con02 wow fadeInUp">
		<ul class="con02_ul">
			<li class="ul_li01"><span class="li_img"><img src="<?php echo G5_IMG_URL ?>/shop_ico01.png" alt="icon"/></span><span>무료상담 및 견적문의</span></li>
			<li class="ul_li02"><span class="li_img"><img src="<?php echo G5_IMG_URL ?>/shop_ico02.png" alt="icon"/></span><span>견적서 발송/계약 체결</span></li>
			<li class="ul_li03"><span class="li_img"><img src="<?php echo G5_IMG_URL ?>/shop_ico03.png" alt="icon"/></span><span>기획/기능 요구서 작성</span></li>
			<li class="ul_li04"><span class="li_img"><img src="<?php echo G5_IMG_URL ?>/shop_ico04.png" alt="icon"/></span><span>시안완료 후 퍼블리싱</br>/프로그램·기술작업</span></li>
			<li class="ul_li05"><span class="li_img"><img src="<?php echo G5_IMG_URL ?>/shop_ico05.png" alt="icon"/></span><span>웹사이트개발 완료</span></li>
			<li class="ul_li06"><span class="li_img"><img src="<?php echo G5_IMG_URL ?>/shop_ico06.png" alt="icon"/></span><span>유지보수</span></li>
		</ul>
	</div>
<h3 data-wow-delay="250ms" class="sub_subject wow fadeInUp">유지보수</h3>
	<div data-wow-delay="300ms" class="hp_con03 wow fadeInUp">
		<p>
			홈페이지 완료 후 지속적인 관리로 경쟁력을 높여드립니다. 홈페이지 완성후 관리 또한 중요한 부분입니다.</br>
			변화가 잡은 웹트렌드에 적응하기 위해 유집수는 필연적인 부분입니다. 정기적인 관리로 홈페이지를 경쟁력 있게 유지 시켜 줄 수 있습니다.</br>
			디자인, 기능적인 부분에 변화가 필요한 곡개을 위해 이노박스의 유지보수 서비스를 이용해보세요.</br>
		</p>
		<div data-wow-delay="350ms" class="wow fadeInUp"><a href="<?php echo G5_BBS_URL ?>/content.php?co_id=maintenance">유지보수 서비스 안내 보기</a></div>
	</div>
<h3 class="sub_subject">최근 프로젝트포트폴리오</h3>
<?php echo latest('basic', 'portfolio|홈페이지', 16, 16);?>

<div class="hp_btn_box">
	<p data-wow-delay="550ms" class="btn_more wow fadeInUp"><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=portfolio&sca=홈페이지">포트폴리오 더보기<img src="../img/sp_more.png" alt=""/></a></p>
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