<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_THEME_PATH.'/head.php');


// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<div id="mb_login" class="mbskin">

    <form name="flogin" action="<?php echo $login_action_url ?>" onsubmit="return flogin_submit(this);" method="post">
    <input type="hidden" name="url" value="<?php echo $login_url ?>">

    <div id="login_frm">
		<h1>회원<?php echo $g5['title'] ?></h1>
        <label for="login_id" class="sound_only">아이디<strong class="sound_only"> 필수</strong></label>
        <input type="text" name="mb_id" id="login_id" placeholder="아이디(필수)" required class="frm_input required" maxLength="20">
        <label for="login_pw" class="sound_only">비밀번호<strong class="sound_only"> 필수</strong></label>
        <input type="password" name="mb_password" id="login_pw" placeholder="비밀번호(필수)" required class="frm_input required" maxLength="20">
        <input type="submit" value="로그인" class="btn_submit themebgcolor">
        <div class="pw_lost">
            <span>아이디/비밀번호를 잊으셨나요?</span>
            <a href="<?php echo G5_BBS_URL ?>/password_lost.php" id="" class="">아이디 비밀번호 찾기</a>
        </div>
    </div>

    </form>

    <?php // 쇼핑몰 사용시 여기부터 ?>
    <?php if ($default['de_level_sell'] == 1) { // 상품구입 권한 ?>

        <!-- 주문하기, 신청하기 -->
        <?php if (preg_match("/orderform.php/", $url)) { ?>

    <section id="mb_login_notmb">
        <h2>비회원 구매</h2>

        <p>
            비회원으로 주문하시는 경우 포인트는 지급하지 않습니다.
        </p>

        <div id="guest_privacy">
           <textarea><?php echo get_text($config['cf_privacy']) ?></textarea>
        </div>

				<input type="checkbox" id="agree" value="1">
        <label for="agree">개인정보수집에 대한 내용을 읽었으며 이에 동의합니다.</label>
        

        <div class="btn_confirm">
            <a href="javascript:guest_submit(document.flogin);" class="btn02">비회원으로 구매하기</a>
        </div>

        <script>
        function guest_submit(f)
        {
            if (document.getElementById('agree')) {
                if (!document.getElementById('agree').checked) {
                    alert("개인정보수집에 대한 내용을 읽고 이에 동의하셔야 합니다.");
                    return;
                }
            }

            f.url.value = "<?php echo $url; ?>";
            f.action = "<?php echo $url; ?>";
            f.submit();
        }
        </script>
    </section>

        <?php } else if (preg_match("/orderinquiry.php$/", $url)) { ?>

    <section id="mb_login_odinfo">
        <h2>비회원 주문조회 안내</h2>
				<fieldset id="mb_login_od">
					<legend>비회원 주문조회</legend>

					<form name="forderinquiry" method="post" action="<?php echo urldecode($url); ?>" autocomplete="off">

					<label for="od_id" class="od_id sound_only">주문번호<strong class="sound_only"> 필수</strong></label>
					<input type="text" name="od_id" value="<?php echo $od_id ?>" id="od_id" placeholder="주문번호" required class="frm_input required" size="20">
					<label for="id_pwd" class="od_pwd sound_only">비밀번호<strong class="sound_only"> 필수</strong></label>
					<input type="password" name="od_pwd" size="20" id="od_pwd" placeholder="비밀번호" required class="frm_input required">
					<input type="submit" value="확인" class="btn_submit themebgcolor">

					</form>
				</fieldset>
        <p>메일로 발송해드린 주문서의 <strong>주문번호</strong> 및 주문 시 입력하신 <strong>핸드폰번호</strong>를 정확히 입력해주십시오.</p>
    </section>

        <?php } ?>

    <?php } ?>
    <?php // 쇼핑몰 사용시 여기까지 반드시 복사해 넣으세요 ?>

</div>






<script>
$(function(){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?");
        }
    });
});

function flogin_submit(f)
{
    return true;
}
</script>

<?php
include_once(G5_THEME_PATH.'/tail.php');

?>
