<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- 회원가입결과 시작 { -->
<div id="reg_result" class="mbskin">

	<h2 id="reg_title">회원가입</h2>
	<p id="reg_nav">회원가입완료</p>
	<h3>회원가입이 완료되었습니다.</h3>
	<p class="rr_txt">이노박스회원이 되시면 창업패키지 신청 및 제작의뢰등 회원서비스를 이용하실수 있습니다.</br>필요하신 회원 서비스를 바로 이용해보세요.</p>

    <div class="btn_confirm">
        <a href="<?php echo G5_URL ?>/" class="btn02">메인으로</a>
		<a href="http://startup.innobox.co.kr/bbs/login.php" class="btn_login">로그인하기</a>
    </div>
</div>
<!-- } 회원가입결과 끝 -->