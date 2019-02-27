<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

define('G5_TAGEDITOR_DIR',     'tagEditor');

define('G5_TAGEDITOR_URL',     G5_PLUGIN_URL.'/'.G5_TAGEDITOR_DIR);
define('G5_TAGEDITOR_PATH',     G5_PLUGIN_PATH.'/'.G5_TAGEDITOR_DIR);

$g5['tag_table'] = G5_TABLE_PREFIX.'tag'; // 태그 테이블

$seo = array();
$seo['type'] = 'website';
$seo['title'] = '광주홈페이지제작 이노박스';
$seo['description'] = '광주홈페이지, 광주어플제작, 무료견적상담, 모바일웹, 반응형웹, 웹접근성, 유지관리, 상무지구위치';
$seo['image'] = 'http://innobox.co.kr/img/homepage_title.jpg';
$seo['url'] = 'http://innobox.co.kr';

include_once(G5_LIB_PATH.'/shop.lib.php');
$config['cf_lg_mert_key'] = '95160cce09854ef44d2edb2bfb05f9f3';
?>