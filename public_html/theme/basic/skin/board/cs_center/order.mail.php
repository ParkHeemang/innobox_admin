<?php //고객님께 ?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>사이트 주문 문의</title>
</head>

<?php
$cont_st = 'margin:0 auto 20px;width:94%;border:0;border-collapse:collapse';
$caption_st = 'padding:0 0 5px;font-weight:bold';
$th_st = 'padding:5px;border-top:1px solid #e9e9e9;border-bottom:1px solid #e9e9e9;background:#f5f6fa;text-align:left';
$td_st = 'padding:5px;border-top:1px solid #e9e9e9;border-bottom:1px solid #e9e9e9';
$empty_st = 'padding:30px;border-top:1px solid #e9e9e9;border-bottom:1px solid #e9e9e9;text-align:center';
$ft_a_st = 'display:block;padding:30px 0;background:#484848;color:#fff;text-align:center;text-decoration:none';
?>

<body>

<div style="margin:30px auto;width:600px;border:10px solid #f7f7f7">
    <div style="border:1px solid #dedede">
        <h1 style="margin:0 0 20px;padding:30px 30px 20px;background:#f7f7f7;color:#555;font-size:1.4em">
            사이트 주문 문의
        </h1>

        <p style="<?php echo $cont_st; ?>">
            <strong>문의자 <?php echo $_POST['wr_name']; ?></strong><br>
            본 메일은 <?php echo G5_TIME_YMDHIS; ?> (<?php echo get_yoil(G5_TIME_YMDHIS); ?>)을 기준으로 작성되었습니다.
        </p>

        <table style="<?php echo $cont_st; ?>">
        <caption style="<?php echo $caption_st; ?>"> 주문 내역</caption>
        <colgroup>
            <col style="width:130px">
            <col>
        </colgroup>
        <tbody>
       <tr>
            <th scope="row">내용</th>
            <td style="<?php echo $wr_content; ?>"></td>
        </tr>
        </tbody>
        </table>
    </div>
</div>

</body>
</html>
