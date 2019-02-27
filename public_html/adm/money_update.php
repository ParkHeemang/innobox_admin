<?php

ini_set('display_errors', 1); 
ini_set('error_reporting', E_ALL);

$sub_menu = "200250";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

check_admin_token();

$mb_id = $_POST['mb_id'];
$mo_money = $_POST['mo_money'];
$mo_content = $_POST['mo_content'];
$expire = preg_replace('/[^0-9]/', '', $_POST['mo_expire_term']);

$mb = get_member($mb_id);

if (!$mb['mb_id'])
    alert('존재하는 회원아이디가 아닙니다.', './money_list.php?'.$qstr);

if (($mo_money < 0) && ($mo_money * (-1) > $mb['mb_money']))
    alert('예치금을 깎는 경우 현재 예치금보다 작으면 안됩니다.', './money_list.php?'.$qstr);

insert_money($mb_id, $mo_money, $mo_content, '@passive', $mb_id, $member['mb_id'].'-'.uniqid(''), $expire);

goto_url('./money_list.php?'.$qstr);
?>
