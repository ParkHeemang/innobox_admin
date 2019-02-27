<?php
$sub_menu = "900600";
include_once("./_common.php");

auth_check($auth[$sub_menu], "w");

// 파일 필드추가
$tmp = sql_fetch(" SHOW COLUMNS FROM {$g5['sms5_form_table']} LIKE 'fo_file' ");
if (empty($tmp['Field'])) {
    sql_query(" ALTER TABLE {$g5['sms5_form_table']} ADD `fo_file` varchar(255) NOT NULL DEFAULT '' AFTER `fo_name` ", false);
}

$g5['title'] = "이모티콘 ";

if ($w == 'u' && is_numeric($fo_no)) {
    $write = sql_fetch("select * from {$g5['sms5_form_table']} where fo_no='$fo_no'");
    $g5['title'] .= '수정';
}
else  {
    $write['fg_no'] = $fg_no;
    $g5['title'] .= '추가';
}

include_once(G5_ADMIN_PATH.'/admin.head.php');

/* 1:plugin, 2:theme, 3:skin, 4:closer, 0:external */
//add_stylesheet('<link rel="stylesheet" href="'.G5_PLUGIN_URL.'/malihu-custom-scrollbar-plugin-master/jquery.mCustomScrollbar.css">', 1);
//add_javascript('<script src="'.G5_PLUGIN_URL.'/malihu-custom-scrollbar-plugin-master/jquery.mCustomScrollbar.concat.min.js"></script>', 1);

//CSS
add_stylesheet('<link rel="stylesheet" href="'.G5_ADMIN_URL. '/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 4);
//JS DataTables
add_javascript('<script src="'.G5_ADMIN_URL.'/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_ADMIN_URL.'/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
		<h1 class="member_list_title">
		이모티콘추가
		<small>form_write</small>
		</h1>
		<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">회원관리</a></li>
		<li class="active">회원정보리스트</li>
		</ol>
	</section>
	<!-- Main content -->	
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<form name="book_form" method="post" action="form_update.php" enctype="multipart/form-data">
				<div class="box">
					<div class="box-header">
						<nav class="navbar navbar-default navbtn" style="z-index: 1000000000000000000000000000000000">
							<div class="text-right">							
								<input type="submit" value="확인" class="btn_submit btn btn-primary" accesskey="s">
								<a href="./form_list.php?<?php echo clean_query_string($_SERVER['QUERY_STRING']); ?>" class="btn btn_02 btn-default">목록</a>					
							</div>
						</nav>
					</div>
											
					<!-- /.box-header -->
					<div class="box-body">						
						
						<input type="hidden" name="w" value="<?php echo $w?>">
						<input type="hidden" name="page" value="<?php echo $page?>">
						<input type="hidden" name="fo_no" value="<?php echo $write['fo_no']?>">
						<input type="hidden" name="get_fg_no" value="<?php echo $fg_no?>">
						<div class="tbl_frm01 tbl_wrap">
						<table>
						<caption><?php echo $g5['title'];?> 목록</caption>
						<colgroup>
							<col class="grid_4">
							<col>
						</colgroup>
						<tbody>
						<tr>
							<th scope="row"><label for="fg_no">그룹<strong class="sound_only"> 필수</strong></label></th>
							<td>
								<select name="fg_no" id="fg_no" required class="required form-control">
									<option value="0">미분류</option>
									<?php
									$qry = sql_query("select * from {$g5['sms5_form_group_table']} order by fg_name");
									while($res = sql_fetch_array($qry)) {
									?>
									<option value="<?php echo $res['fg_no']?>"<?php echo get_selected($res['fg_no'], $write['fg_no']); ?>><?php echo $res['fg_name']?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="fo_name">제목<strong class="sound_only"> 필수</strong></label></th>
							<td><input type="text" name="fo_name" id="fo_name" required value="<?php echo $write['fo_name']?>" class="frm_input form-control required" size="70"></td>
						</tr>

						<tr>
							<th scope="row"><label for="fo_file">이미지</th>
							<td>
								<?php echo help('이미지가 첨부되면 MMS로 전송됩니다. 파일크기는 900KB 이하로 첨부 가능합니다.'); ?>
								<input type="file" name="fo_file" id="fo_file" value="" class="frm_input">
								<?php
									$fo_file = '/sms/0/'.$write['fo_file'];
									$fo_size = @getimagesize(G5_DATA_PATH.$fo_file);
									if ($write['fo_file'] && file_exists(G5_DATA_PATH.$fo_file) && preg_match('%^image/(jpeg|gif|png)$%', $fo_size['mime'])) {
										$fo_image = G5_DATA_URL.$fo_file;
									} else {
										$fo_image = '';
									}
									if ($write['fo_file']) {
										echo '<input type="checkbox" name="fo_file_del" id="fo_file_del" value="1"> <label for="fo_file_del"><b>삭제</b> '.$write['fo_file'].'</label>';
									}
								?>
							</td>
						</tr>

						<tr>
							<th scope="row">메시지</th>
							<td id="sms5_emo_add">
								<?php echo empty($fo_image) ? '' : '<div class="sms5_img"><img src="'.$fo_image.'" /></div>'; ?>

								<div class="sms5_box write_wrap">
									<span class="box_ico"></span>
									<label for="sms_contents" id="wr_message_lbl">내용</label>

									<div class="box_wrap">
										<textarea name="fo_content" id="sms_contents" class="box_txt box_square" onkeyup="byte_check('sms_contents', 'sms_bytes');" accesskey="m"><?php echo $write['fo_content']?></textarea>
									</div>

									<div id="sms_byte"><span id="sms_bytes">0</span> / <span id="sms_max_bytes"><?php echo ($config['cf_sms_type'] == 'LMS' ? 90 : 80); ?></span> byte</div>

									<button type="button" id="write_sc_btn" class="write_scemo_btn">특수<br>기호</button>
									<div id="write_sc" class="write_scemo">
										<span class="scemo_ico"></span>
										<div class="scemo_list">
											<button type="button" class="scemo_add" onclick="javascript:add('■')">■</button>
											<button type="button" class="scemo_add" onclick="javascript:add('□')">□</button>
											<button type="button" class="scemo_add" onclick="javascript:add('▣')">▣</button>
											<button type="button" class="scemo_add" onclick="javascript:add('◈')">◈</button>
											<button type="button" class="scemo_add" onclick="javascript:add('◆')">◆</button>
											<button type="button" class="scemo_add" onclick="javascript:add('◇')">◇</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♥')">♥</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♡')">♡</button>
											<button type="button" class="scemo_add" onclick="javascript:add('●')">●</button>
											<button type="button" class="scemo_add" onclick="javascript:add('○')">○</button>
											<button type="button" class="scemo_add" onclick="javascript:add('▲')">▲</button>
											<button type="button" class="scemo_add" onclick="javascript:add('▼')">▼</button>
											<button type="button" class="scemo_add" onclick="javascript:add('▶')">▶</button>
											<button type="button" class="scemo_add" onclick="javascript:add('▷')">▷</button>
											<button type="button" class="scemo_add" onclick="javascript:add('◀')">◀</button>
											<button type="button" class="scemo_add" onclick="javascript:add('◁')">◁</button>
											<button type="button" class="scemo_add" onclick="javascript:add('☎')">☎</button>
											<button type="button" class="scemo_add" onclick="javascript:add('☏')">☏</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♠')">♠</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♤')">♤</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♣')">♣</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♧')">♧</button>
											<button type="button" class="scemo_add" onclick="javascript:add('★')">★</button>
											<button type="button" class="scemo_add" onclick="javascript:add('☆')">☆</button>
											<button type="button" class="scemo_add" onclick="javascript:add('☞')">☞</button>
											<button type="button" class="scemo_add" onclick="javascript:add('☜')">☜</button>
											<button type="button" class="scemo_add" onclick="javascript:add('▒')">▒</button>
											<button type="button" class="scemo_add" onclick="javascript:add('⊙')">⊙</button>
											<button type="button" class="scemo_add" onclick="javascript:add('㈜')">㈜</button>
											<button type="button" class="scemo_add" onclick="javascript:add('№')">№</button>
											<button type="button" class="scemo_add" onclick="javascript:add('㉿')">㉿</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♨')">♨</button>
											<button type="button" class="scemo_add" onclick="javascript:add('™')">™</button>
											<button type="button" class="scemo_add" onclick="javascript:add('℡')">℡</button>
											<button type="button" class="scemo_add" onclick="javascript:add('∑')">∑</button>
											<button type="button" class="scemo_add" onclick="javascript:add('∏')">∏</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♬')">♬</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♪')">♪</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♩')">♩</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♭')">♭</button>
										</div>
										<div class="scemo_cls"><button type="button" class="scemo_cls_btn">닫기</button></div>
									</div>
									<button type="button" id="write_emo_btn" class="write_scemo_btn">이모<br>티콘</button>
									<div id="write_emo" class="write_scemo">
										<span class="scemo_ico"></span>
										<div class="scemo_list">
											<button type="button" class="scemo_add" onclick="javascript:add('*^^*')">*^^*</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♡.♡')">♡.♡</button>
											<button type="button" class="scemo_add" onclick="javascript:add('@_@')">@_@</button>
											<button type="button" class="scemo_add" onclick="javascript:add('☞_☜')">☞_☜</button>
											<button type="button" class="scemo_add" onclick="javascript:add('ㅠ ㅠ')">ㅠ ㅠ</button>
											<button type="button" class="scemo_add" onclick="javascript:add('Θ.Θ')">Θ.Θ</button>
											<button type="button" class="scemo_add" onclick="javascript:add('^_~♥')">^_~♥</button>
											<button type="button" class="scemo_add" onclick="javascript:add('~o~')">~o~</button>
											<button type="button" class="scemo_add" onclick="javascript:add('★.★')">★.★</button>
											<button type="button" class="scemo_add" onclick="javascript:add('(!.!)')">(!.!)</button>
											<button type="button" class="scemo_add" onclick="javascript:add('⊙.⊙')">⊙.⊙</button>
											<button type="button" class="scemo_add" onclick="javascript:add('q.p')">q.p</button>
											<button type="button" class="scemo_add emo_long" onclick="javascript:add('┏( \'\')┛')">┏( \'\')┛</button>
											<button type="button" class="scemo_add emo_long" onclick="javascript:add('@)-)--')">@)-)--')</button>
											<button type="button" class="scemo_add emo_long" onclick="javascript:add('↖(^-^)↗')">↖(^-^)↗</button>
											<button type="button" class="scemo_add emo_long" onclick="javascript:add('(*^-^*)')">(*^-^*)</button>
										</div>
										<div class="scemo_cls"><button type="button" class="scemo_cls_btn">닫기</button></div>
									</div>
								</div>

							</td>
						</tr>
						<?php if ($w == 'u') {?>
						<tr>
							<th scope="row">업데이트</th>
							<td> <?php echo $write['fo_datetime']?> </td>
						</tr>
						<?php } ?>
						</tbody>
						</table>
						</div>
						
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
				</form>
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->		
	</section>
	<!-- /.content -->	
</div>
<!-- /.content-wrapper -->

<script>
    $(function() {
    });

    function add(str) {
        var conts = document.getElementById('sms_contents');
        var bytes = document.getElementById('sms_bytes');
        conts.focus();
        conts.value+=str;
        byte_check('sms_contents', 'sms_bytes');
        return;
    }
    function byte_check(sms_contents, sms_bytes)
    {
        var conts = document.getElementById(sms_contents);
        var bytes = document.getElementById(sms_bytes);
        var max_bytes = document.getElementById("sms_max_bytes");

        var i = 0;
        var cnt = 0;
        var exceed = 0;
        var ch = '';

        for (i=0; i<conts.value.length; i++)
        {
            ch = conts.value.charAt(i);
            if (escape(ch).length > 4) {
                cnt += 2;
            } else {
                cnt += 1;
            }
        }

        bytes.innerHTML = cnt;

        <?php if($config['cf_sms_type'] == 'LMS') { ?>
        if(cnt > 90)
            max_bytes.innerHTML = 1500;
        else
            max_bytes.innerHTML = 90;

        if (cnt > 1500)
        {
            exceed = cnt - 1500;
            alert('메시지 내용은 1500바이트를 넘을수 없습니다.\n\n작성하신 메세지 내용은 '+ exceed +'byte가 초과되었습니다.\n\n초과된 부분은 자동으로 삭제됩니다.');
            var tcnt = 0;
            var xcnt = 0;
            var tmp = conts.value;
            for (i=0; i<tmp.length; i++)
            {
                ch = tmp.charAt(i);
                if (escape(ch).length > 4) {
                    tcnt += 2;
                } else {
                    tcnt += 1;
                }

                if (tcnt > 1500) {
                    tmp = tmp.substring(0,i);
                    break;
                } else {
                    xcnt = tcnt;
                }
            }
            conts.value = tmp;
            bytes.innerHTML = xcnt;
            return;
        }
        <?php } else { ?>
        if (cnt > 80)
        {
            exceed = cnt - 80;
            alert('메시지 내용은 80바이트를 넘을수 없습니다.\n\n작성하신 메세지 내용은 '+ exceed +'byte가 초과되었습니다.\n\n초과된 부분은 자동으로 삭제됩니다.');
            var tcnt = 0;
            var xcnt = 0;
            var tmp = conts.value;
            for (i=0; i<tmp.length; i++)
            {
                ch = tmp.charAt(i);
                if (escape(ch).length > 4) {
                    tcnt += 2;
                } else {
                    tcnt += 1;
                }

                if (tcnt > 80) {
                    tmp = tmp.substring(0,i);
                    break;
                } else {
                    xcnt = tcnt;
                }
            }
            conts.value = tmp;
            bytes.innerHTML = xcnt;
            return;
        }
        <?php } ?>
    }

    byte_check('sms_contents', 'sms_bytes');
    document.getElementById('sms_contents').focus();
</script>

<script>
$(function(){
    $(".box_txt").bind("focus keydown", function(){
        $("#wr_message_lbl").hide();
    });
    $(".write_scemo_btn").click(function(){
        $(".write_scemo").hide();
        $(this).next(".write_scemo").show();
    });
    $(".scemo_cls_btn").click(function(){
        $(".write_scemo").hide();
    });
});
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>