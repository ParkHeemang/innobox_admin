<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>
<h2 id="container_title"><?php echo $board['bo_subject'] ?><span class="sound_only"> 목록</span></h2>
<h2 class="sub_title">CUSTOMER SERVICE</h2>
<div class="pk_title_img"><img src="<?php echo G5_IMG_URL ?>/cscenter_title.jpg" alt=""/></div>
<div class="snb">
	<ul class="snb_ul">
		<li class="snb_li first"><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=notice" <?php if ($bo_table==='notice') echo'class="snb_on"'; ?>>공지사항</a></li>
		<li class="snb_li"><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=reservation" <?php if ($bo_table==='reservation') echo'class="snb_on"'; ?>>방문요청</a></li>
		<li class="snb_li"><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=cscenter" <?php if ($bo_table==='cscenter') echo'class="snb_on"'; ?>>견적상담</a></li>
	</ul>
</div>
<section id="bo_w">
    <h2 id="container_subtitle">글등록</h2>

    <!-- 게시물 작성/수정 시작 { -->
    <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" style="width:<?php echo $width; ?>">
    <input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <?php if ($w=='') {  ?>
    <input type="hidden" name="wr_5" value="대기">
    <?php } ?>
    <?php
    $option = '';
    $option_hidden = '';
    if ($is_notice || $is_html || $is_secret || $is_mail) {
        $option = '';
        if ($is_notice) {
            $option .= "\n".'<input type="checkbox" id="notice" name="notice" value="1" '.$notice_checked.'>'."\n".'<label for="notice">공지</label>';
        }

        if ($is_html) {
            if ($is_dhtml_editor) {
                $option_hidden .= '<input type="hidden" value="html1" name="html">';
            } else {
                $option .= "\n".'<input type="checkbox" id="html" name="html" onclick="html_auto_br(this);" value="'.$html_value.'" '.$html_checked.'>'."\n".'<label for="html">html</label>';
            }
        }

        if ($is_secret) {
            if ($is_admin || $is_secret==1) {
                $option .= "\n".'<input type="checkbox" id="secret" name="secret" value="secret" '.$secret_checked.'>'."\n".'<label for="secret">비밀글</label>';
            } else {
                $option_hidden .= '<input type="hidden" name="secret" value="secret">';
            }
        }

        if ($is_mail) {
            $option .= "\n".'<input type="checkbox" id="mail" name="mail" value="mail" '.$recv_email_checked.'>'."\n".'<label for="mail">답변메일받기</label>';
        }
    }

    echo $option_hidden;
    ?>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <tbody>
        <?php if ($is_name) { ?>
        <tr>
            <th scope="row"><label for="wr_name">작성자<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="wr_name" value="<?php echo $name ?>" id="wr_name" required class="frm_input required" size="10" maxlength="20"></td>
			<?php if($board['bo_1_subj']):?>
			<!-- 회사명 -->
			<th scope="row"><label for="wr_1"><?=$board['bo_1_subj']?><strong class="sound_only">필수</strong></label></th>
			<td>
				<div id="autosave_wrapper">
					<input type="text" name="wr_1" value="<?=$write['wr_1']?>" id="wr_1" class="frm_input required frm_inputWidth price" size="50">
				</div>
			</td>
		<?php endif?>
        </tr>
        <?php } else { ?>
        <tr>
            <th scope="row"><label for="wr_name">작성자<strong class="sound_only">필수</strong></label></th>
            <td><?php echo $member['mb_name']; ?></td>
			<?php if($board['bo_1_subj']):?>
			<!-- 회사명 -->
			<th scope="row"><label for="wr_1"><?=$board['bo_1_subj']?><strong class="sound_only">필수</strong></label></th>
			<td>
				<div id="autosave_wrapper">
					<input type="text" name="wr_1" value="<?=$write['wr_1']?>" id="wr_1" class="frm_input required frm_inputWidth price" size="50">
				</div>
			</td>
		<?php endif?>
        </tr>
        <?php } ?>

        <?php if ($is_email) { ?>
        <tr>
            <th scope="row"><label for="wr_email">이메일</label></th>
            <td><input type="text" name="wr_email" value="<?php echo $email ?>" id="wr_email" class="frm_input required email" size="50" maxlength="100"></td>
			<?php if($board['bo_2_subj']):?>
			<!-- 연락처 -->
			<th scope="row"><label for="wr_2"><?=$board['bo_2_subj']?><strong class="sound_only">필수</strong></label></th>
			<td>
				<div id="autosave_wrapper">
					<input type="text" name="wr_2" value="<?=$write['wr_2']?>" id="wr_2" class="frm_input required frm_inputWidth price" size="50">
				</div>
			</td>
		<?php endif?>
        </tr>
        <?php } else { ?>
		<tr>
            <th scope="row"><label for="wr_email">이메일</label></th>
            <td><input type="text" name="wr_email" value="<?php echo $email ?>" id="wr_email" class="frm_input required email" size="50" maxlength="100"></td>
			<?php if($board['bo_2_subj']):?>
			<!-- 연락처 -->
			<th scope="row"><label for="wr_2"><?=$board['bo_2_subj']?><strong class="sound_only">필수</strong></label></th>
			<td>
				<div id="autosave_wrapper">
					<input type="text" name="wr_2" value="<?=$write['wr_2']?>" id="wr_2" class="frm_input required frm_inputWidth price" size="50">
				</div>
			</td>
		<?php endif?>
        </tr>
		<?php } ?>


		<?php if($board['bo_3_subj']):?>
		<!-- 쇼케이스 -->
		<tr>
			<th scope="row"><?=$board['bo_3_subj']?></th>
			<td colspan="3">
				<?php
					$z = json_decode($board['bo_3']);
					$wr_3_chk = explode(",", $write['wr_3']);
					echo '<input type="hidden" name="wr_3" value="'.$write['wr_3'].'" />';
					foreach($z->options as $wr_3_key => $wr_3_value) {
						echo '<label><input type="checkbox" value="'.$wr_3_key.'"'.((in_array($wr_3_key, $wr_3_chk))?' checked="checked"':'').' data-name="wr_3" /> '.$wr_3_value.'</label> &nbsp; ';
					}
				?>
			</td>
		</tr>
		<?php endif ?>

        <?php if ($option) { ?>
        <tr>
            <th scope="row">옵션</th>
            <td colspan="3"><?php echo $option ?></td>
        </tr>
        <?php } ?>

        <?php if ($is_category) { ?>
        <tr>
            <th scope="row"><label for="ca_name">분류<strong class="sound_only">필수</strong></label></th>
            <td>
                <select name="ca_name" id="ca_name" required class="required" >
                    <option value="">선택하세요</option>
                    <?php echo $category_option ?>
                </select>
            </td>
        </tr>
        <?php } ?>

        <tr>
            <th scope="row"><label for="wr_subject">제목<strong class="sound_only">필수</strong></label></th>
            <td colspan="3">
                 <input type="text" name="wr_subject" value="<?php echo $subject ?>" id="wr_subject" required class="frm_input required" size="50" maxlength="255">
            </td>
        </tr>

        <tr>
					<th scope="row"><label for="wr_content">내용<strong class="sound_only">필수</strong></label></th>
					<td colspan="3" class="wr_content">
						<?php if($write_min || $write_max) { ?>
						<!-- 최소/최대 글자 수 사용 시 -->
						<p id="char_count_desc">이 게시판은 최소 <strong><?php echo $write_min; ?></strong>글자 이상, 최대 <strong><?php echo $write_max; ?></strong>글자 이하까지 글을 쓰실 수 있습니다.</p>
						<?php } ?>
						<?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
						<?php if($write_min || $write_max) { ?>
						<!-- 최소/최대 글자 수 사용 시 -->
						<div id="char_count_wrap"><span id="char_count"></span>글자</div>
						<?php } ?>
					</td>
        </tr>
				<?php
				if ($is_admin)
				{
				?>
        <tr>
					<th scope="row"><label for="wr_4">답변<strong class="sound_only">필수</strong></label></th>
					<td colspan="3" class="wr_4">
						<textarea id="wr_4" name="wr_4" class="" maxlength="65536" style="width:100%;height:300px"><?php echo $write['wr_4']?></textarea>
					</td>
        </tr>
        <tr>
					<th scope="row"><label for="wr_5">진행상태<strong class="sound_only">필수</strong></label></th>
					<td colspan="3" class="wr_5">
						<select name="wr_5">
							<option value="대기" <?php if($write['wr_5'] == "대기") echo " selected "; ?>>대기</option>
							<option value="완료" <?php if($write['wr_5'] == "완료") echo " selected "; ?>>완료</option>
						</select>
					</td>
        </tr>
				<?php
				}
				?>


        <?php for ($i=0; $is_file && $i<$file_count; $i++) { ?>
        <tr>
            <th scope="row">파일 #<?php echo $i+1 ?></th>
            <td colspan="3">
                <input type="file" name="bf_file[]" title="파일첨부 <?php echo $i+1 ?> : 용량 <?php echo $upload_max_filesize ?> 이하만 업로드 가능" class="frm_file frm_input">
                <?php if ($is_file_content) { ?>
                <input type="text" name="bf_content[]" value="<?php echo ($w == 'u') ? $file[$i]['bf_content'] : ''; ?>" title="파일 설명을 입력해주세요." class="frm_file frm_input" size="50">
                <?php } ?>
                <?php if($w == 'u' && $file[$i]['file']) { ?>
                <input type="checkbox" id="bf_file_del<?php echo $i ?>" name="bf_file_del[<?php echo $i;  ?>]" value="1"> <label for="bf_file_del<?php echo $i ?>"><?php echo $file[$i]['source'].'('.$file[$i]['size'].')';  ?> 파일 삭제</label>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>

				<?php if ($is_password) { ?>
        <?php } else { ?>
				<tr>
            <th scope="row"><label for="wr_password">비밀번호<strong class="sound_only">필수</strong></label></th>
            <td colspan="3"><input type="password" name="wr_password" id="wr_password" <?php echo $password_required ?> class="frm_input <?php echo $password_required ?>" maxlength="20"></td>
        </tr>
        <?php } ?>

        <?php if ($is_guest) { //자동등록방지  ?>
        <tr>
            <th scope="row">자동등록방지</th>
            <td colspan="3">
                <?php echo $captcha_html ?>
            </td>
        </tr>
        <?php } ?>

        </tbody>
        </table>
    </div>

    <div class="btn_confirm">
        <input type="submit" value="작성완료" id="btn_submit" accesskey="s" class="btn_submit">
        <a href="./board.php?bo_table=<?php echo $bo_table ?>" class="btn_cancel">취소</a>
    </div>
    </form>

    <script>
		// 아이콘
		$('input[data-name="wr_3"]').on('click', function() {
			var chk = [];
			var name = $(this).data('name');
			$('input[data-name="'+name+'"]:checked').each(function() {
				chk.push($(this).val());
			});
			$('input[name="'+name+'"]').val(chk.join(','));
		});

    <?php if($write_min || $write_max) { ?>
    // 글자수 제한
    var char_min = parseInt(<?php echo $write_min; ?>); // 최소
    var char_max = parseInt(<?php echo $write_max; ?>); // 최대
    check_byte("wr_content", "char_count");

    $(function() {
        $("#wr_content").on("keyup", function() {
            check_byte("wr_content", "char_count");
        });
    });

    <?php } ?>
    function html_auto_br(obj)
    {
        if (obj.checked) {
            result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
            if (result)
                obj.value = "html2";
            else
                obj.value = "html1";
        }
        else
            obj.value = "";
    }

    function fwrite_submit(f)
    {
        <?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

        var subject = "";
        var content = "";
        $.ajax({
            url: g5_bbs_url+"/ajax.filter.php",
            type: "POST",
            data: {
                "subject": f.wr_subject.value,
                "content": f.wr_content.value
            },
            dataType: "json",
            async: false,
            cache: false,
            success: function(data, textStatus) {
                subject = data.subject;
                content = data.content;
            }
        });

        if (subject) {
            alert("제목에 금지단어('"+subject+"')가 포함되어있습니다");
            f.wr_subject.focus();
            return false;
        }

        if (content) {
            alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
            if (typeof(ed_wr_content) != "undefined")
                ed_wr_content.returnFalse();
            else
                f.wr_content.focus();
            return false;
        }

        if (document.getElementById("char_count")) {
            if (char_min > 0 || char_max > 0) {
                var cnt = parseInt(check_byte("wr_content", "char_count"));
                if (char_min > 0 && char_min > cnt) {
                    alert("내용은 "+char_min+"글자 이상 쓰셔야 합니다.");
                    return false;
                }
                else if (char_max > 0 && char_max < cnt) {
                    alert("내용은 "+char_max+"글자 이하로 쓰셔야 합니다.");
                    return false;
                }
            }
        }

        <?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함  ?>

        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }
    </script>
</section>
<!-- } 게시물 작성/수정 끝 -->