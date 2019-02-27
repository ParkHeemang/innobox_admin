<?php
include_once('./_common.php');
header("Content-Type: text/plain; charset=utf-8");

$arr = get_spam_ip(); // 휴지통 게시판에서 ip 주소 차단
print_r($arr);


$arr = get_spam_mb(); // 휴지통 게시판에서 회원아이디 차단
print_r($arr);

