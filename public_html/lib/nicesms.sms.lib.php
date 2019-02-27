<?php
include_once(G5_PLUGIN_PATH . '/snoopy/Snoopy.class.php');
$snoopy = new Snoopy;

function send_sms($msg, $recevier, $sender = '1644-3897')
{
	global $snoopy;
	  // 이 경우 전달 값중에서 returnurl 값은 제외시켜 주세요.
  // 그리고 한글이나 특수문자가 들어 있는 값은 urlencode 를 해주세요.특히 msg1 값 또는 resdate 값 등등..
  //$en_msg1 = iconv("UTF-8", "CP949", urlencode($msg));
	$en_msg1 = $msg;
  $en_resdate = urlencode("2004-03-01 00:00:00");

	$submit_url = "http://sms.nicesms.co.kr/cpsms_utf8/cpsms.aspx";
	//$submit_url = "http://sms.nicesms.co.kr/cpsms/cpsms.aspx";
	$submit_vars["userid"]		= "seng82";
	$submit_vars["password"]	= "ffc54c7c9411081bb5c649da73032c6a";
	$submit_vars["msgcnt"]		= "1";
	$submit_vars["msg1"]			= $en_msg1;
	$submit_vars["receivers"]		= $recevier;
	$submit_vars["sender"]		= $sender;
	$submit_vars["resflag"]		= 'N';
	$snoopy->httpmethod = "POST";
	$snoopy->submit($submit_url,$submit_vars);

	return $snoopy->results;
}
?>