<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);

include_once(G5_THEME_PATH.'/head.php');
?>

<!-- 로그인 시작 { -->
<div id="mb_login" class="mbskin">
    <h1><?php echo $g5['title'] ?></h1>
	<P class="login_sub_txt">펌킨인터렉티브 홈페이지 관리서비스입니다.</br>가입하신 아이디와 비밀번호로 로그인 해주세요.</P>

    <form name="flogin" action="<?php echo $login_action_url ?>" onsubmit="return flogin_submit(this);" method="post">
    <input type="hidden" name="url" value="<?php echo $login_url ?>">

    <fieldset id="login_fs">
        <legend>회원로그인</legend>
        <label for="login_id" class="login_id">아이디<strong class="sound_only"> 필수</strong></label>
        <input type="text" name="mb_id" id="login_id" required class="frm_input required" size="20" maxLength="20">
        <label for="login_pw" class="login_pw">비밀번호<strong class="sound_only"> 필수</strong></label>
        <input type="password" name="mb_password" id="login_pw" required class="frm_input required" size="20" maxLength="20">
		<!--<label for="login_btn" class="login_sumit">LOGIN<strong class="sound_only"> 필수</strong></label>-->
        <input type="submit" id="login_btn" value="로그인" class="btn_submit">
        <!--input type="checkbox" name="auto_login" id="login_auto_login">
        <label for="login_auto_login">자동로그인</label-->
    </fieldset>

    <aside id="login_info">

        <div id="info_left">
			<span>아이디/비밀번호를</br>잊으셨나요?</span>
            <a href="<?php echo G5_BBS_URL ?>/password_lost.php"  id="" class="btn02 search_pw">아이디/비밀번호 찾기</a>
        </div>
		<div id="info_right">
			<span>아직 유지보수</br>회원이 아니신가요?</span>
			<a href="./register.php" class="btn01 lg_join">회원가입 하기</a>
		</div>
    </aside>

    

    </form>

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
<!-- } 로그인 끝 -->