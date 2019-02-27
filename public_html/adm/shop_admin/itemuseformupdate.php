<?php
$sub_menu = '400650';
include_once('./_common.php');

check_demo();

if ($w == 'd')
    auth_check($auth[$sub_menu], "d");
else
    auth_check($auth[$sub_menu], "w");

check_admin_token();


if ($w == "")
{
    if ($is_date && $is_time_i && $is_time_i && $is_time_s) {
        $is_time = $is_date.' '.sprintf('%02d',$is_time_h).':'.sprintf('%02d',$is_time_i).':'.sprintf('%02d',$is_time_s);
    } 

    $sql = "insert into {$g5['g5_shop_item_use_table']} set 
                it_id = '$it_id',
                is_name = '$is_name',
                is_score = '$is_score',
                is_subject = '$is_subject',
                is_content = '$is_content',
                is_confirm = '$is_confirm',
                is_reply_subject = '$is_reply_subject',
                is_reply_content = '$is_reply_content',
                is_reply_name = '".$member['mb_name']."', 
                is_time = '$is_time' ";
    sql_query($sql);

    update_use_cnt($_POST['it_id']);
    update_use_avg($_POST['it_id']);

    goto_url("./itemuselist.php");
}
elseif ($w == "u")
{
    $sql = "update {$g5['g5_shop_item_use_table']} set 
                it_id = '$it_id',
                is_name = '$is_name',
                is_score = '$is_score',
                is_subject = '$is_subject',
                is_content = '$is_content',
                is_confirm = '$is_confirm',
                is_reply_subject = '$is_reply_subject',
                is_reply_content = '$is_reply_content',
                is_reply_name = '".$member['mb_name']."', 
                is_time = '$is_time'
            where is_id = '$is_id' ";
    sql_query($sql);

    update_use_cnt($_POST['it_id']);
    update_use_avg($_POST['it_id']);

    goto_url("./itemuseform.php?w=$w&amp;is_id=$is_id&amp;sca=$sca&amp;$qstr");
}
else
{
    alert();
}
?>
