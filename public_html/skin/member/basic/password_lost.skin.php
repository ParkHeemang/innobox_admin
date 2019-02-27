<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);

include_once(G5_THEME_PATH.'/head.php');
?>

<!-- 회원정보 찾기 시작 { -->
<div id="find_info" class="new_win mbskin">
    <h1 id="win_title">아이디/비밀번호찾기</h1>

    <form name="fpasswordlost" action="<?php echo $action_url ?>" onsubmit="return fpasswordlost_submit(this);" method="post" autocomplete="off">
    <fieldset id="info_fs">
        <p>
            회원가입 시 입력하신 이메일과 자동등록방지 숫자를 입력하시면<br>
            아이디와 비밀번호를 이메일로 보내드립니다.
        </p>
        <label for="mb_email" id="info_email">이메일<strong class="sound_only">필수</strong></label>
        <input type="text" name="mb_email" id="mb_email" required class="required frm_input email" size="30">
    </fieldset>
    <?php echo captcha_html();  ?>
	<span id="captcha_title">자동입력방지</span>
        <input type="submit" value="확인" class="btn_submit win_btn">
    </form>
</div>

<script>
function fpasswordlost_submit(f)
{
    <?php echo chk_captcha_js();  ?>

    return true;
}

$(function() {
    var sw = screen.width;
    var sh = screen.height;
    var cw = document.body.clientWidth;
    var ch = document.body.clientHeight;
    var top  = sh / 2 - ch / 2 - 100;
    var left = sw / 2 - cw / 2;
    moveTo(left, top);
});
</script>
<?php
include_once(G5_THEME_PATH.'/tail.php');
?>
<!-- } 회원정보 찾기 끝 -->