<link rel="stylesheet" href="http://startup.innobox.co.kr/theme/basic/css/sub.css">


<h2 data-wow-delay="50ms" id="container_title" class="wow fadeInUp"><?php echo $g5['title'] ?><span class="sound_only"> 목록</span></h2>
<h2 data-wow-delay="100ms" class="sub_title wow fadeInUp">SRARTUPS PACKAGE</h2>
<div data-wow-delay="100ms" class="pk_title_img wow fadeInUp"><img src="<?php echo G5_IMG_URL ?>/package_title.jpg" alt=""/></div>
<div data-wow-delay="150ms" class="snb snb_package wow fadeInUp">
	<ul class="snb_ul">
		<li class="snb_li first"><a href="#">창업패키지</a></li>
	</ul>
</div>
<h3 data-wow-delay="150ms" class="sub_subject wow fadeInUp">쇼핑몰 구축 컨설팅 </h3>

<table data-wow-delay="200ms" summary="쇼핑몰 구축 컨설팅 표" class="tbl_pk01 wow fadeInUp">
	<caption></caption>
	<colgroup>
		<col width="202">
		<col width="808">
		<col width="190">
	</colgroup>
	<tbody>
		<tr>
			<th class="ssH84">창업상담</th>
			<td class="ssPdl40">- 쇼핑몰 구축 컨설팅</br>- 디자인 상담</td>
			<td><span class="pk_btn">무료</span></td>
		</tr>
		<tr>
			<th class="ssH159">스킨디자인</th>
			<td class="ssPdl40">- 쇼핑몰 스킨 선택 (전체패키지)</br><span class="pk01_txt">작업범위 : 메인,분류,상세</br>스킨패키지 적용 : 로그인 4종 세트,</br>장바구니, 마이페이지 왼쪽메뉴,<br/>이용안내, 회원약관, 회원가입/수정</span></td>
			<td class="ssTaCenter"><span class="price_lt">3,000,000 원</span><span class="pk_btn">500,000 원</span></td>
		</tr>
		<tr>
			<th class="ssH89">솔류션</th>
			<td class="ssPdl40">- 고고몰 솔류션</td>
			<td class="ssTaCenter"><span class="price_lt">660,000 원</span><span class="pk_btn">330,000 원</span></td>
		</tr>
		<tr>
			<th class="ssH89">도메인</th>
			<td class="ssPdl40">- 도메인 1년 이용료</td>
			<td class="ssTaCenter"><span class="price_lt">16,500 원</span><span class="pk_btn">무료</span></td>
		</tr>
		<tr>
			<th class="ssH89">웹호스팅</th>
			<td class="ssPdl40">- 웹호스팅 1년 이용료</td>
			<td class="ssTaCenter"><span class="price_lt">120,000 원</span><span class="pk_btn">무료</span></td>
		</tr>
		<tr>
			<th class="ssH89">부가서비스</th>
			<td class="ssPdl40">- PG결제,문자 연동→무료</td>
			<td class="ssTaCenter"><span class="price_lt">120,000 원</span><span class="pk_btn">무료</span></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3"><span class="total_lt">정가 3,916,500원</span><span class="total_price">총 830,000 원</span></td>
		</tr>
	</tfoot>
</table>

<div data-wow-delay="200ms" class="pk_btn_box wow fadeInUp">
	<p href="#" class="pk_btn_tell">1644-3879 전화상담</p>
	<a href="http://startup.innobox.co.kr/bbs/write.php?bo_table=cscenter" class="pk_summit">패키지 신청하기</a>
</div>

<h3 data-wow-delay="250ms" class="sub_subject wow fadeInUp">옵션선택</h3>

<table data-wow-delay="300ms" summary="옵션 선택표" class="tbl_pk02 wow fadeInUp">
	<caption></caption>
	<colgroup>
		<col width="1012">
		<col width="188">
	</colgroup>
	<tbody>
		<tr>
			<th class="ssH83">모바일 스킨 (10% 할인) / 작업범위 : 메인, 분류, 상세, 카테고리</th>
			<td><span class="price_lt">198,000 원</span><span class="pk_btn">178,000 원</span></td>
		</tr>
		<tr>
			<th class="ssH62">블로그디자인 (고급형)</th>
			<td><span class="pk_btn">550,000 원</span></td>
		</tr>
		<tr>
			<th class="ssH58">블로그 마케팅 대행 (1개월) ->상위노출 1달보장</th>
			<td><span class="pk_btn">550,000 원</span></td>
		</tr>
		<tr>
			<th class="ssH58">네이버 체크아웃 연동</th>
			<td><span class="pk_btn">220,000 원</span></td>
		</tr>
		<tr>
			<th class="ssH58">070인터넷전화, 대표전화</th>
			<td><span class="pk_btn">유플러스 신청</span></td>
		</tr>
		<tr>
			<th class="ssH58">전자결제 LG 유플러스 필수</th>
			<td><span class="pk_btn">220,000 원</span></td>
		</tr>
	</tbody>
</table>
	
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

	<script>
		$(".snb_li").click(function() {
			$(this).parent().children().children('a').removeClass('snb_on');
			$(this).children('a').addClass('snb_on');
		});
		$(".snb_li").eq(0).trigger('click');
	</script>