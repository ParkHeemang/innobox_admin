<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
    </div>
</div>

<hr>

<?php echo poll('theme/basic'); // 설문조사 ?>

<hr>

<div id="ft_wrap">
    <div id="ft_hub">
        <h3>INNOBOX PAGES</h3>
        <table>
            <tr>
                <td><a href="<?php echo G5_BBS_URL?>/content.php?co_id=marketing">MARKETING</a></td>
                <td><a href="<?php echo G5_BBS_URL?>/login.php">SIGN IN</a></td>
                <td><a href="<?php echo G5_BBS_URL?>/content.php?co_id=shoppingmall">SHOPPING MALL</a></td>
            </tr>
            <tr>
                <td><a href="<?php echo G5_BBS_URL?>/content.php?co_id=maintenance">MAINTENANCE</a></td>
                <td><a href="<?php echo G5_BBS_URL?>/register.php">SIGN UP</a></td>
                <td><a href="<?php echo G5_BBS_URL?>/content.php?co_id=homepage">HOMEPAGE</a></td>
            </tr>
            <tr>
                <td><a href="<?php echo G5_BBS_URL?>/board.php?bo_table=notice">CUSTUMER CENTER</a></td>
                <td><a href="<?php echo G5_BBS_URL?>/board.php?bo_table=maintenance">MAINTENANCE  BOARD</a></td>
                <td><a href="<?php echo G5_BBS_URL?>/content.php?co_id=mobile">MOBILE</a></td>
            </tr>
            <tr>
                <td><a href="#none">TERMS AND CONDITIONS</a></td>
                <td><a href="#none">개인정보 처리방침</a></td>
                <td></td>
            </tr>
        </table>
    </div>
    <div id="ft">
        <ul>
            <li>상호 이노박스 INNOBOX</li>
            <li>대표 정병주</li>
            <li>사업자 등록번호 408-18-43756</li>
            <li>광주광역시 서구 치평동 1288-1 지아빌딩 5층 이노박스</li>
            <li>innobox_work@naver.com</li>
        </ul>
        <span class="copy">COPYRIGHT INNOBOX ALL RIGHTS RESEVED</span>
        <div class="b_logo">
            <img src="<?php echo G5_THEME_IMG_URL?>/m_bottom_logo.png">
        </div>
        <select class="familysite" onChange="on_page(this)">
            <option>FAMILY SITE</option>
            <option value="http://pumpkincorp.co.kr/" target="_blank">펌킨인터랙티브</option>
            <option value="http://keywordup.co.kr/" target="_blank">키워드업</option>
            <option value="http://ad.keywordup.co.kr/bbs/login.php?url=%2F1" target="_blank">키워드업(광고주)</option>
            <option value="http://sitesale.co.kr/" target="_blank">사이트세일</option>
        </select>
    </div>
</div>



<script>
$(function() {
    // 폰트 리사이즈 쿠키있으면 실행
    font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));
});

function on_page(sel) {
    if(sel.value!="")
    window.open(sel.value,'','');
}
</script>

<?php
include_once(G5_THEME_PATH."/tail.sub.php");
?>