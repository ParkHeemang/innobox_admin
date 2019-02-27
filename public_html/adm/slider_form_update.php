<?php


ini_set('display_errors', 1); 
ini_set('error_reporting', E_ALL);

$sub_menu = "100311";
include_once("./_common.php");

check_demo();

if ($W == 'd')
    auth_check($auth[$sub_menu], "d");
else
    auth_check($auth[$sub_menu], "w");

//check_admin_token();



// 업로드 디렉
$sl_img_dir = G5_DATA_PATH.'/slider';
@mkdir($sl_img_dir, G5_DIR_PERMISSION);
@chmod($sl_img_dir, G5_DIR_PERMISSION);

$sl_simg      = $_FILES['sl_simg']['tmp_name'];
$sl_simg_name = $_FILES['sl_simg']['name'];

// 파일정보
if($w == "u") {
    $file = sql_fetch(" select sl_img from {$g5['slider_table']} where sl_id = '$sl_id' ");
    $sl_img = $file['sl_img'];
}

if ($sl_simg_del) {
    $file_img = $sl_img_dir.'/'.$sl_img;
    @unlink($file_img);
    delete_thumb(dirname($file_img), basename($file_img));
    $sl_img = '';
}

if ($_FILES['sl_simg']['name']) {
    if($w == 'u' && $sl_img) {
        $file_img = $sl_img_dir.'/'.$sl_img;
        @unlink($file_img);
        delete_thumb(dirname($file_img), basename($file_img));
    }
    $sl_img = upload_image($_FILES['sl_simg']['tmp_name'], $_FILES['sl_simg']['name'], $sl_img_dir, $prefix='slider');
}



// 레이어
$cnt = count($_POST['sl_layer']['type']);
for ($i=0; $i<$cnt; $i++) {
    if (empty($_POST['sl_layer']['text'][$i]) || $_POST['sl_layer']['erase'][$i]) {
        unset($_POST['sl_layer']['type'][$i]);
        unset($_POST['sl_layer']['text'][$i]);
        foreach ($_POST['sl_layer']['data'] as $k=>$v) {
            unset($_POST['sl_layer']['data'][$k][$i]);
        }
    } else {
        if ($_POST['sl_layer']['type'][$i] == 'subject') {
            // html tag 사용금지
            $_POST['sl_layer']['text'][$i] = strip_tags($_POST['sl_layer']['text'][$i]);
        } else {
            $_POST['sl_layer']['text'][$i] = strip_tags($_POST['sl_layer']['text'][$i], '<br><a><button>');
        }
    }
}

$_POST['sl_layer']['type'] = array_values($_POST['sl_layer']['type']);
$_POST['sl_layer']['text'] = array_values($_POST['sl_layer']['text']);
foreach ($_POST['sl_layer']['data'] as $k=>$v) {
    $_POST['sl_layer']['data'][$k] = array_values($_POST['sl_layer']['data'][$k]);
}

$sl_layer = serialize($_POST['sl_layer']);


if ($w == '') {

    $now = G5_TIME_YMDHIS;
    $sql = " insert into {$g5['slider_table']} 
                set sl_img        = '$sl_img',
                    sl_alt        = '$sl_alt',
                    sl_url        = '$sl_url',
                    sl_device     = '$sl_device',
                    sl_layer      = '$sl_layer',
                    sl_begin_time = '$sl_begin_time',
                    sl_end_time   = '$sl_end_time',
                    sl_time       = '$now',
                    sl_hit        = '0',
                    sl_order      = '$sl_order',
                    sl_print      = '$sl_print' ";
    sql_query($sql);
    $sl_id = sql_insert_id();

} else if ($w == 'u') {

    $sl = sql_fetch("select sl_id from {$g5['slider_table']} where sl_id='{$sl_id}'");
    if (empty($sl['sl_id'])) {
        alert('존재하지 않는 자료입니다.');
    }

    $now = $sl['sl_time'];
    $sql = " update {$g5['slider_table']} 
                set sl_img        = '$sl_img',
                    sl_alt        = '$sl_alt',
                    sl_url        = '$sl_url',
                    sl_device     = '$sl_device',
                    sl_layer      = '$sl_layer',
                    sl_begin_time = '$sl_begin_time',
                    sl_end_time   = '$sl_end_time',
                    sl_time       = '$now',
                    sl_hit        = '0',
                    sl_order      = '$sl_order',
                    sl_print      = '$sl_print' 
                where sl_id = '{$sl_id}' ";
    sql_query($sql);

} else if ($w == 'd') {
    $sl = sql_fetch("select * from {$g5['slider_table']} where sl_id='{$sl_id}'");
    @unlink(G5_DATA_PATH."/slider/".$sl['sl_img']);

    $sql = " delete from {$g5['slider_table']} where sl_id = $sl_id ";
    $result = sql_query($sql);
} else {
    alert('제대로 된 값이 넘어오지 않았습니다.');
}

// query string 재정의
$qstr = 'prt='.$prt.'&amp;'.'dev='.$dev.'&amp;'.$qstr;

if ($w == "" || $w == "u") {
    goto_url('./slider_form.php?'.$qstr.'&amp;w=u&amp;sl_id='.$sl_id, false);
} else {
    goto_url("./slider_list.php");
}
?>