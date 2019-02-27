<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>
<h2 id="reg_title">회원가입</h2>
<p id="reg_nav">상세정보입력</p>
<!-- 회원정보 입력/수정 시작 { -->
<div class="mbskin" style="margin-bottom:100px;">

    <script src="<?php echo G5_JS_URL ?>/jquery.register_form.js"></script>
    <?php if($config['cf_cert_use'] && ($config['cf_cert_ipin'] || $config['cf_cert_hp'])) { ?>
    <script src="<?php echo G5_JS_URL ?>/certify.js"></script>
    <?php } ?>

    <form id="fregisterform" name="fregisterform" action="<?php echo $register_action_url ?>" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="url" value="<?php echo $urlencode ?>">
    <input type="hidden" name="agree" value="<?php echo $agree ?>">
    <input type="hidden" name="agree2" value="<?php echo $agree2 ?>">
    <input type="hidden" name="cert_type" value="<?php echo $member['mb_certify']; ?>">
    <input type="hidden" name="cert_no" value="">
    <?php if (isset($member['mb_sex'])) {  ?><input type="hidden" name="mb_sex" value="<?php echo $member['mb_sex'] ?>"><?php }  ?>
    <?php if (isset($member['mb_nick_date']) && $member['mb_nick_date'] > date("Y-m-d", G5_SERVER_TIME - ($config['cf_nick_modify'] * 86400))) { // 닉네임수정일이 지나지 않았다면  ?>
    <input type="hidden" name="mb_nick_default" value="<?php echo get_text($member['mb_nick']) ?>">
    <input type="hidden" name="mb_nick" value="<?php echo get_text($member['mb_nick']) ?>">
    <?php }  ?>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>상세정보입력</caption>
        <tbody>
        <tr>
            <th scope="row" style="border-top:none;"><label for="reg_mb_id">아이디<strong class="sound_only">필수</strong></label></th>
            <td style="border-top:none;">
                <input type="text" name="mb_id" value="<?php echo $member['mb_id'] ?>" id="reg_mb_id" <?php echo $required ?> <?php echo $readonly ?> class="frm_input frm_input01 <?php echo $required ?> <?php echo $readonly ?>" minlength="3" maxlength="20">
                <span id="msg_mb_id"></span>
            </td>
			<th scope="row" style="border-top:none;"><label for="reg_mb_name">이름<strong class="sound_only">필수</strong></label></th>
            <td style="border-top:none;">
                <?php if ($config['cf_cert_use']) { ?>
                <span class="frm_info">아이핀 본인확인 후에는 이름이 자동 입력되고 휴대폰 본인확인 후에는 이름과 휴대폰번호가 자동 입력되어 수동으로 입력할수 없게 됩니다.</span>
                <?php } ?>
                <input type="text" id="reg_mb_name" name="mb_name" value="<?php echo get_text($member['mb_name']) ?>" <?php echo $required ?> <?php echo $readonly; ?> class="frm_input frm_input01 <?php echo $required ?> <?php echo $readonly ?>" size="10">
                <?php
                if($config['cf_cert_use']) {
                    if($config['cf_cert_ipin'])
                        echo '<button type="button" id="win_ipin_cert" class="btn_frmline">아이핀 본인확인</button>'.PHP_EOL;
                    if($config['cf_cert_hp'])
                        echo '<button type="button" id="win_hp_cert" class="btn_frmline">휴대폰 본인확인</button>'.PHP_EOL;

                    echo '<noscript>본인확인을 위해서는 자바스크립트 사용이 가능해야합니다.</noscript>'.PHP_EOL;
                }
                ?>
                <?php
                if ($config['cf_cert_use'] && $member['mb_certify']) {
                    if($member['mb_certify'] == 'ipin')
                        $mb_cert = '아이핀';
                    else
                        $mb_cert = '휴대폰';
                ?>
                <div id="msg_certify">
                    <strong><?php echo $mb_cert; ?> 본인확인</strong><?php if ($member['mb_adult']) { ?> 및 <strong>성인인증</strong><?php } ?> 완료
                </div>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="reg_mb_password">비밀번호<strong class="sound_only">필수</strong></label></th>
            <td><input type="password" name="mb_password" id="reg_mb_password" <?php echo $required ?> class="frm_input frm_input01 <?php echo $required ?>" minlength="3" maxlength="20"></td>
			<?php if ($req_nick) {  ?>
            <th scope="row"><label for="reg_mb_nick">회사명<strong class="sound_only">필수</strong></label></th>
            <td>
                <input type="hidden" name="mb_nick_default" value="<?php echo isset($member['mb_nick'])?get_text($member['mb_nick']):''; ?>">
                <input type="text" name="mb_nick" value="<?php echo isset($member['mb_nick'])?get_text($member['mb_nick']):''; ?>" id="reg_mb_nick" required class="frm_input frm_input01 required nospace" size="10" maxlength="20">
                <span id="msg_mb_nick"></span>
            </td>
			<?php }  ?>
        </tr>
        <tr>
            <th scope="row"><label for="reg_mb_password_re">비밀번호 확인<strong class="sound_only">필수</strong></label></th>
            <td><input type="password" name="mb_password_re" id="reg_mb_password_re" <?php echo $required ?> class="frm_input frm_input01 <?php echo $required ?>" minlength="3" maxlength="20"></td>
			<?php if ($config['cf_use_tel']) {  ?>
            <th scope="row"><label for="reg_mb_tel">연락처<?php if ($config['cf_req_tel']) { ?><strong class="sound_only">필수</strong><?php } ?></label></th>
            <td>
                <input type="hidden" name="mb_tel" value="<?php echo get_text($member['mb_tel']) ?>" id="reg_mb_tel" <?php echo $config['cf_req_tel']?"required":""; ?> />

                <?php $mb_tel = explode("-", get_text($member['mb_tel'])); ?>

                <input type="text" value="<?php echo $mb_tel[0]; ?>" <?php echo ($config['cf_req_tel'])?"required":""; ?> class="mb_tel frm_input frm_input02  <?php echo ($config['cf_req_tel'])?"required":""; ?>" maxlength="4"> - 
                <input type="text" value="<?php echo $mb_tel[1]; ?>" <?php echo ($config['cf_req_tel'])?"required":""; ?> class="mb_tel frm_input frm_input02  <?php echo ($config['cf_req_tel'])?"required":""; ?>" maxlength="4"> - 
                <input type="text" value="<?php echo $mb_tel[2]; ?>" <?php echo ($config['cf_req_tel'])?"required":""; ?> class="mb_tel frm_input frm_input02  <?php echo ($config['cf_req_tel'])?"required":""; ?>" maxlength="4">
            </td>
			<?php }  ?>
        </tr>
		<tr>
            <th style="border-bottom:none;" scope="row"><label for="reg_mb_email">이메일<strong class="sound_only">필수</strong></label></th>
            <td style="border-bottom:none;">
                <?php if ($config['cf_use_email_certify']) {  ?>
                <span class="frm_info">
                    <?php if ($w=='') { echo "E-mail 로 발송된 내용을 확인한 후 인증하셔야 회원가입이 완료됩니다."; }  ?>
                    <?php if ($w=='u') { echo "E-mail 주소를 변경하시면 다시 인증하셔야 합니다."; }  ?>
                </span>
                <?php }  ?>
                <input type="hidden" name="old_email" value="<?php echo $member['mb_email'] ?>">
                <input type="text" name="mb_email" value="<?php echo isset($member['mb_email'])?$member['mb_email']:''; ?>" id="reg_mb_email" required class="frm_input frm_input01 email required" size="70" maxlength="100">
            </td>
			<?php if ($config['cf_use_hp'] || $config['cf_cert_hp']) {  ?>
            <th style="border-bottom:none;" scope="row"><label for="reg_mb_hp">휴대폰<?php if ($config['cf_req_hp']) { ?><strong class="sound_only">필수</strong><?php } ?></label></th>
            <td style="border-bottom:none;">
                <input type="hidden" name="mb_hp" value="<?php echo get_text($member['mb_hp']) ?>" id="reg_mb_hp" <?php echo ($config['cf_req_hp'])?"required":""; ?> />

                <?php $mb_hp = explode("-", get_text($member['mb_hp'])); ?>

                <input type="text" value="<?php echo $mb_hp[0]; ?>" <?php echo ($config['cf_req_hp'])?"required":""; ?> class="mb_hp frm_input frm_input02  <?php echo ($config['cf_req_hp'])?"required":""; ?>" maxlength="3"> - 
                <input type="text" value="<?php echo $mb_hp[1]; ?>" <?php echo ($config['cf_req_hp'])?"required":""; ?> class="mb_hp frm_input frm_input02  <?php echo ($config['cf_req_hp'])?"required":""; ?>" maxlength="4"> - 
                <input type="text" value="<?php echo $mb_hp[2]; ?>" <?php echo ($config['cf_req_hp'])?"required":""; ?> class="mb_hp frm_input frm_input02  <?php echo ($config['cf_req_hp'])?"required":""; ?>" maxlength="4">

                <?php if ($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
                <input type="hidden" name="old_mb_hp" value="<?php echo get_text($member['mb_hp']) ?>">
                <?php } ?>
            </td>
			 <?php }  ?>
        </tr>

        </tbody>
        </table>
    </div>



    <div class="btn_confirm">
        <input type="submit" value="<?php echo $w==''?'가입완료':'정보수정'; ?>" id="btn_submit" class="btn_submit join_submit" accesskey="s">
        <a href="<?php echo G5_URL ?>" class="btn_cancel">취소</a>
    </div>
    </form>

    <script>
    $(function() {

		$(".mb_tel").on('keyup', function() {
			$("#reg_mb_tel").val( $(".mb_tel").map(function() { return this.value;}).get().join('-') );
		});
		$(".mb_hp").on('keyup', function() {
			$("#reg_mb_hp").val( $(".mb_hp").map(function() { return this.value;}).get().join('-') );
		});

        $("#reg_zip_find").css("display", "inline-block");

        <?php if($config['cf_cert_use'] && $config['cf_cert_ipin']) { ?>
        // 아이핀인증
        $("#win_ipin_cert").click(function() {
            if(!cert_confirm())
                return false;

            var url = "<?php echo G5_OKNAME_URL; ?>/ipin1.php";
            certify_win_open('kcb-ipin', url);
            return;
        });

        <?php } ?>
        <?php if($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
        // 휴대폰인증
        $("#win_hp_cert").click(function() {
            if(!cert_confirm())
                return false;

            <?php
            switch($config['cf_cert_hp']) {
                case 'kcb':
                    $cert_url = G5_OKNAME_URL.'/hpcert1.php';
                    $cert_type = 'kcb-hp';
                    break;
                case 'kcp':
                    $cert_url = G5_KCPCERT_URL.'/kcpcert_form.php';
                    $cert_type = 'kcp-hp';
                    break;
                case 'lg':
                    $cert_url = G5_LGXPAY_URL.'/AuthOnlyReq.php';
                    $cert_type = 'lg-hp';
                    break;
                default:
                    echo 'alert("기본환경설정에서 휴대폰 본인확인 설정을 해주십시오");';
                    echo 'return false;';
                    break;
            }
            ?>

            certify_win_open("<?php echo $cert_type; ?>", "<?php echo $cert_url; ?>");
            return;
        });
        <?php } ?>
    });

    // submit 최종 폼체크
    function fregisterform_submit(f)
    {
        // 회원아이디 검사
        if (f.w.value == "") {
            var msg = reg_mb_id_check();
            if (msg) {
                alert(msg);
                f.mb_id.select();
                return false;
            }
        }

        if (f.w.value == "") {
            if (f.mb_password.value.length < 3) {
                alert("비밀번호를 3글자 이상 입력하십시오.");
                f.mb_password.focus();
                return false;
            }
        }

        if (f.mb_password.value != f.mb_password_re.value) {
            alert("비밀번호가 같지 않습니다.");
            f.mb_password_re.focus();
            return false;
        }

        if (f.mb_password.value.length > 0) {
            if (f.mb_password_re.value.length < 3) {
                alert("비밀번호를 3글자 이상 입력하십시오.");
                f.mb_password_re.focus();
                return false;
            }
        }

        // 이름 검사
        if (f.w.value=="") {
            if (f.mb_name.value.length < 1) {
                alert("이름을 입력하십시오.");
                f.mb_name.focus();
                return false;
            }

            /*
            var pattern = /([^가-힣\x20])/i;
            if (pattern.test(f.mb_name.value)) {
                alert("이름은 한글로 입력하십시오.");
                f.mb_name.select();
                return false;
            }
            */
        }

        <?php if($w == '' && $config['cf_cert_use'] && $config['cf_cert_req']) { ?>
        // 본인확인 체크
        if(f.cert_no.value=="") {
            alert("회원가입을 위해서는 본인확인을 해주셔야 합니다.");
            return false;
        }
        <?php } ?>

        // 닉네임 검사
        if ((f.w.value == "") || (f.w.value == "u" && f.mb_nick.defaultValue != f.mb_nick.value)) {
            var msg = reg_mb_nick_check();
            if (msg) {
                alert(msg);
                f.reg_mb_nick.select();
                return false;
            }
        }

        // E-mail 검사
        if ((f.w.value == "") || (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {
            var msg = reg_mb_email_check();
            if (msg) {
                alert(msg);
                f.reg_mb_email.select();
                return false;
            }
        }

        <?php if (($config['cf_use_hp'] || $config['cf_cert_hp']) && $config['cf_req_hp']) {  ?>
        // 휴대폰번호 체크
        var msg = reg_mb_hp_check();
        if (msg) {
            alert(msg);
            f.reg_mb_hp.select();
            return false;
        }
        <?php } ?>

        if (typeof f.mb_icon != "undefined") {
            if (f.mb_icon.value) {
                if (!f.mb_icon.value.toLowerCase().match(/.(gif)$/i)) {
                    alert("회원아이콘이 gif 파일이 아닙니다.");
                    f.mb_icon.focus();
                    return false;
                }
            }
        }

        if (typeof(f.mb_recommend) != "undefined" && f.mb_recommend.value) {
            if (f.mb_id.value == f.mb_recommend.value) {
                alert("본인을 추천할 수 없습니다.");
                f.mb_recommend.focus();
                return false;
            }

            var msg = reg_mb_recommend_check();
            if (msg) {
                alert(msg);
                f.mb_recommend.select();
                return false;
            }
        }

        <?php echo chk_captcha_js();  ?>

        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }
    </script>

</div>
<!-- } 회원정보 입력/수정 끝 -->