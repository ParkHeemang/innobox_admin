<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_THEME_PATH.'/head.php');

/* 1:plugin, 2:theme, 3:skin, 4:closer, 0:external */
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 3);
?>

<div id="find_info" class="new_win mbskin">
    <div class="find_box">
        <form name="fpasswordlost" action="<?php echo $action_url ?>" onsubmit="return fpasswordlost_submit(this);" method="post" autocomplete="off">
        <h1 id="win_title">아이디/비밀번호 찾기</h1>
        <fieldset id="info_fs">
            <p>
                회원가입 시 등록하신 이메일 주소를 입력해 주세요.<br>
                해당 이메일로 아이디와 비밀번호 정보를 보내드립니다.
            </p>
            <input type="email" id="mb_email" name="mb_email" placeholder="이메일주소(필수)" required class="frm_input email">
        </fieldset>
        <?php echo captcha_html(); ?>
        <div class="find_btn">
            <input type="submit" class="btn_submit" value="확인">
        </div>
        </form>
    </div>
</div>

<script>
function fpasswordlost_submit(f)
{
    <?php echo chk_captcha_js(); ?>

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