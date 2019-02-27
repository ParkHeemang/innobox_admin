<?php
include_once('./_common.php');
?>
<style>
body { margin:0px; }
table { width:100%; border-collapse:collapse; font-size:13px;}
table th { background-color:#f8f8f8; padding:10px; border-bottom:1px solid #ddd;}
table td { padding:10px; border-bottom:1px solid #ddd;} 
input[type="text"] { border:1px solid #ddd; padding:5px 10px; width:100%;}
textarea { border:1px solid #ddd; width:100%; padding:10px; box-sizing:border-box; height:200px;}
input[type="submit"] { border:none; background-color:#f79422; color:#fff; font-size:15px; width:90%; margin:20px auto; display:block; padding:10px 0px; cursor:pointer;}
</style>
<form name="fcs" id="fcs" method="post" action="./quick_update.php">
    <table>
        <tr>
            <th>회사명</th>
            <td>
                <input type="text" name="q_name" required class="f">
            </td>
        </tr>
        <tr>
            <th>연락처</th>
            <td>
                <input type="text" name="q_hp" required class="f">
            </td>
        </tr>
        <tr>
            <th>문의내역</th>
            <td>
                <textarea name="q_con" class="f" placeholder="기존 홈페이지가 있으시면 홈페이지 주소를 적어주세요.
홈페이지나 쇼핑몰 제작을 의뢰하실경우 벤치마킹할 사이트를 적어주시면 더욱 빠른 견적상담을 받으실 수 있습니다."></textarea>
            </td>
        </tr>
    </table>
    <input type="submit" value="문의하기">
</form>