<?php
/* 스팸을 차단합시다 */

// 스팸필터를 위해 휴지통 trash 게시판에 있는 아이피
if ( ! function_exists('get_spam_ip')) {
    function get_spam_ip() 
    {
        global $g5;
        $return = array();

        $tbl = $g5['write_prefix'].'trash'; 

        $sql = "select distinct(wr_ip) from {$tbl}";
        $res = sql_query($sql);
        for ($i=0; $row=sql_fetch_array($res); $i++) {
            $return[] = $row['wr_ip'];
        }

        //$return[] = '121.147.177.246'; // 테스트

        return $return;
    }
}

// 스팸필터를 위해 휴지통 trash 게시판에 있는 회원아이디
if ( ! function_exists('get_spam_mb')) {
    function get_spam_mb() 
    {
        global $g5;
        $return = array();

        $tbl = $g5['write_prefix'].'trash'; 

        $sql = "select distinct(mb_id) from {$tbl} where mb_id!=''";
        $res = sql_query($sql);
        for ($i=0; $row=sql_fetch_array($res); $i++) {
            $return[] = $row['mb_id'];
        }

        //$return[] = 'admin'; // 테스트

        return $return;
    }
}


// 접근차단 IP
$spam = get_spam_ip();
if (in_array($_SERVER['REMOTE_ADDR'], $spam)) {
    die ("Access Denied.");
}

// 접근차단 아이디
$spam = get_spam_mb();
if (in_array($member['mb_id'], $spam)) {
    die ("Access Denied.");
}

unset($spam);