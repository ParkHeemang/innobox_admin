<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<div id="reg_result" class="mbskin">
    <table id="step">
        <tr>
            <td>
                <span>STEP 01</span>
                <h2>약관동의</h2>
            </td>
            <td>
                <span>STEP 02</span>
                <h2>정보입력</h2>
            </td>
            <th>
                <span>STEP 03</span>
                <h2>가입완료</h2>
            </th>
        </tr>
    </table>

    <div id="result_box">
        <h2 class="mb_color">성공적으로 회원가입이 완료되었습니다</h2>
        <h3>
            이노박스 회원<span>이 되신것을 환영합니다.</span>
        </h3>
        <p>회원만이 누릴수 있는 다양한 혜택을 누려보세요~</p>
    </div>

    <div class="btn_confirm">
        <a href="<?php echo G5_URL ?>/" class="btn02">메인으로</a>
    </div>

</div>
