<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- 회원가입약관 동의 시작 { -->
<h2 id="reg_title">회원가입</h2>
<p id="reg_nav">약관동의</p>
<div class="mbskin">
    <form  name="fregister" id="fregister" action="<?php echo $register_action_url ?>" onsubmit="return fregister_submit(this);" method="POST" autocomplete="off">

    <section id="fregister_term">
        <h2>회원가입약관</h2>
        <textarea readonly><?php echo get_text($config['cf_stipulation']) ?></textarea>
    </section>

    <section id="fregister_private">
        <h2>개인정보취급방침</h2>
        <textarea readonly><?php echo get_text($config['cf_privacy']) ?></textarea>
        <fieldset class="fregister_agree">
			<input type="checkbox" name="agree" value="1" id="agree11">
			<label for="agree11">회원가입약관의 내용에 동의합니다.</label></br>
            <input type="checkbox" name="agree2" value="1" id="agree21">
			<label for="agree21">개인정보처리방침안내의 내용에 동의합니다.</label>
        </fieldset>
        </div>
    </section>

    <div class="reg_btn_confirm">
        <input type="submit" class="btn_submit join_submit" value="회원가입">
		<a href="<?php echo G5_URL ?>" class="btn_cancel">취소</a>
    </div>

    </form>

    <script>
    function fregister_submit(f)
    {
        if (!f.agree.checked) {
            alert("회원가입약관의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
            f.agree.focus();
            return false;
        }

        if (!f.agree2.checked) {
            alert("개인정보처리방침안내의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
            f.agree2.focus();
            return false;
        }

        return true;
    }
    </script>
</div>
<!-- } 회원가입 약관 동의 끝 -->