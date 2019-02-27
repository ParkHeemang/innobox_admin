<?php
include_once(G5_LIB_PATH.'/mailer.lib.php');
include_once(G5_LIB_PATH.'/nicesms.sms.lib.php');

if ( ! function_exists('is_hp')) {
    function is_hp($hp)
    {
        $hp = str_replace('-', '', trim($hp));
        if (preg_match("/^(01[016789])([0-9]{3,4})([0-9]{4})$/", $hp))
            return true;
        else
            return false;
    }
}

if ( ! function_exists('get_hp')) {
    function get_hp($hp, $hyphen=1)
    {
        global $g5;

        if (!is_hp($hp)) return '';

        if ($hyphen) $preg = "$1-$2-$3"; else $preg = "$1$2$3";

        $hp = str_replace('-', '', trim($hp));
        $hp = preg_replace("/^(01[016789])([0-9]{3,4})([0-9]{4})$/", $preg, $hp);

        if ($g5['sms5_demo'])
            $hp = '0100000000';

        return $hp;
    }
}

if (empty($w))
{
	//------------------------------------------------------------------------------
	// 운영자에게 메일보내기
	//------------------------------------------------------------------------------
	$subject = '사이트 제작 문의 ('.$wr_name.')';
	ob_start();
	include $board_skin_path.'/admin.mail.php';
	$content = ob_get_contents();
	ob_end_clean();

	mailer($wr_name, $wr_email, $config['cf_admin_email'], $subject, $content, 1);
	//------------------------------------------------------------------------------

	//------------------------------------------------------------------------------
	// 주문자에게 메일보내기
	//------------------------------------------------------------------------------
	/*
	$subject = '사이트 제작 문의';
	ob_start();
	include $board_skin_path.'/order.mail.php';
	$content = ob_get_contents();
	ob_end_clean();

	mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $wr_email, $subject, $content, 1);
	*/

	$wr_message = "사이트 제작 문의 " . $wr_name . "님" ;

	$mb = get_member($config['cf_admin']);
	//$hp = get_hp($mb['mb_hp'], 0);

	$_res = send_sms(stripslashes($wr_message), '01027383653');

	if(substr(trim($_res),0, 9) == "result=OK")
	{

	}

	$hp = get_hp($wr_5, 0);

	$_res = send_sms(stripslashes($wr_message), $hp);

	if(substr(trim($_res),0, 9) == "result=OK")
	{

	}

	//------------------------------------------------------------------------------
}
?>