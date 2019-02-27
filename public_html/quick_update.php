<?php
include_once('./_common.php');

$sql = " insert into g5_quick 
         set q_name = '$q_name',
             q_hp = '$q_hp',
             q_pr = '$q_pr',
             q_con = '$q_con'
";
sql_query($sql);

if ($is_mobile) {
    alert('신청완료',G5_URL);
} else {
    alert('신청완료');
}

?>