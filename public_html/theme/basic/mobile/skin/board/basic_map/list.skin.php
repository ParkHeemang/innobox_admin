<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 선택옵션으로 인해 셀합치기가 가변적으로 변함
$colspan = 2;

if ($is_checkbox) $colspan++;

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>

<?php
    if ($bo_table == 'notice') {
        echo '<img src="'.G5_THEME_IMG_URL.'/m_cs_notice.jpg" width="100%" height="auto">';
    }
    if ($bo_table == 'reservation') {
        echo '<img src="'.G5_THEME_IMG_URL.'/m_cs_news.jpg" width="100%" height="auto">';
    }
    if ($bo_table == 'cscenter') {
        echo '<img src="'.G5_THEME_IMG_URL.'/m_cs_cscenter.jpg" width="100%" height="auto">';
    }
    if ($bo_table == 'notice' || $bo_table == 'reservation' || $bo_table == 'cscenter' ) {
        echo '<div id="m_cs_tab_wrap">';
        echo '<img src="'.G5_THEME_IMG_URL.'/m_customer_service_title.jpg" width="100%" height="auto">';
        echo '<ul>';
        echo '<li><a href="'.G5_BBS_URL.'/board.php?bo_table=notice">공지사항</a></li>';
        echo '<li><a href="'.G5_BBS_URL.'/board.php?bo_table=reservation">방문요청</a></li>';
        echo '<li><a href="'.G5_BBS_URL.'/write.php?bo_table=cscenter">견적상담</a></li>';
        echo '</ul>';
        echo '</div>';
    }
    if ($bo_table == 'maintenance') {
        echo '<img src="'.G5_THEME_IMG_URL.'/m_maintenance_img1.jpg" width="100%" height="auto">';
        echo '<div id="maintenance_wrap">';
        echo '<img src="'.G5_THEME_IMG_URL.'/m_maintenance_img4.jpg" width="100%" height="auto">';
        echo '<div id="maintenance_btn">';
        echo '<a href="'.G5_BBS_URL.'/content.php?co_id=maintenance"><img src="'.G5_THEME_IMG_URL.'/m_maintenance_img2.jpg"></a>';
        echo '<a href="'.G5_BBS_URL.'/board.php?bo_table=maintenance"><img src="'.G5_THEME_IMG_URL.'/m_maintenance_img3.jpg"></a>';
        echo'</div>';
        echo'</div>';
    }

?>


<div id="bbs" class="bbs_<?php echo $bo_table; ?>">
    
    <!-- 게시판 목록 시작 -->
    <div id="bo_list">
        <fieldset id="bo_sch">
            <legend>게시물 검색</legend>

            <form name="fsearch" method="get">
            <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
            <input type="hidden" name="sca" value="<?php echo $sca ?>">
            <input type="hidden" name="sop" value="and">
            
            <input type="text" name="stx" value="<?php echo stripslashes($stx) ?>" required id="stx" class="frm_input required sch_bar" size="15" maxlength="20" placeholder="검색어를 입력해주세요">
            <input type="submit" value="" class="btn_submit">
            </form>
        </fieldset>
        <?php if ($is_category) { ?>
        <nav id="bo_cate">
            <h2><?php echo ($board['bo_mobile_subject'] ? $board['bo_mobile_subject'] : $board['bo_subject']) ?> 카테고리</h2>
            <ul id="bo_cate_ul">
                <?php echo $category_option ?>
            </ul>
        </nav>
        <?php } ?>
            <!--
        <div class="bo_fx">
            <?php if ($rss_href || $write_href) { ?>
            <ul class="btn_bo_user">
                <?php if ($rss_href) { ?><li><a href="<?php echo $rss_href ?>" class="btn_b01">RSS</a></li><?php } ?>
                <?php if ($admin_href) { ?><li><a href="<?php echo $admin_href ?>" class="btn_admin">관리자</a></li><?php } ?>
                <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b02">글쓰기</a></li><?php } ?>
            </ul>
            <?php } ?>
        </div>
            -->
        <form name="fboardlist" id="fboardlist" action="./board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
        <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
        <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
        <input type="hidden" name="stx" value="<?php echo $stx ?>">
        <input type="hidden" name="spt" value="<?php echo $spt ?>">
        <input type="hidden" name="sst" value="<?php echo $sst ?>">
        <input type="hidden" name="sod" value="<?php echo $sod ?>">
        <input type="hidden" name="page" value="<?php echo $page ?>">
        <input type="hidden" name="sw" value="">

        <div class="tbl_head01 tbl_wrap">
            <table>
            <thead>
            <tr>
                <th scope="col">제목</th>
            </tr>
            </thead>
            <tbody>
            <?php
            for ($i=0; $i<count($list); $i++) { ?>
            

            <tr class="<?php if ($list[$i]['is_notice']) echo "bo_notice"; ?>">
          
                <td class="td_subject">

                    <a class="bo_tit" href="<?php echo $list[$i]['href'] ?>">
                        <?php
                        echo $list[$i]['icon_reply'];
                        if ($list[$i]['is_notice']) // 공지
                        echo '<span class="notice">공지</span>';
                        ?>
                        <?php 
                        echo $list[$i]['subject'];
                        if ($is_category && $list[$i]['ca_name']) {
                        ?>
                        
                        <a href="<?php echo $list[$i]['ca_name_href'] ?>" class="bo_cate_link <?php echo $ca_status ?> status00"><?php echo $list[$i]['ca_name'] ?></a>
                        <?php } ?>
                        <?php if ($list[$i]['comment_cnt']) { ?><span class="sound_only">댓글</span><?php echo $list[$i]['comment_cnt']; ?><span class="sound_only">개</span><?php } ?>
                        <?php
                        // if ($list[$i]['link']['count']) { echo '['.$list[$i]['link']['count']}.']'; }
                        // if ($list[$i]['file']['count']) { echo '<'.$list[$i]['file']['count'].'>'; }

                        //if (isset($list[$i]['icon_new'])) echo $list[$i]['icon_new'];
                        //if (isset($list[$i]['icon_hot'])) echo $list[$i]['icon_hot'];
                        //if (isset($list[$i]['icon_file'])) echo $list[$i]['icon_file'];
                        //if (isset($list[$i]['icon_link'])) echo $list[$i]['icon_link'];
                        //if (isset($list[$i]['icon_secret'])) echo $list[$i]['icon_secret'];

                        ?>
                    </a>
                    <div>등록일 : <?php echo date("Y.m.d", strtotime($list[$i]['wr_datetime'])) ?></div>
                    <div>작성자 : <?php echo $list[$i]['name'] ?></div>
                    <div>조회수 : <?php echo $list[$i]['wr_hit'] ?></div>
                </td>
            </tr>
            <?php } ?>
            <?php if (count($list) == 0) { echo '<tr><td colspan="'.$colspan.'" class="empty_table"><span class="caution">!</span><span>게시물이 없습니다.</span></td></tr>'; } ?>
            </tbody>
            </table>
        </div>

        <?php if ($list_href || $is_checkbox || $write_href) { ?>
        <div class="bo_fx">
            <ul class="btn_bo_adm">
                <?php if ($list_href) { ?>
                <li><a href="<?php echo $list_href ?>" class="btn_b01"> 목록</a></li>
                <?php } ?>
                <!--<?php if ($is_checkbox) { ?>
                <li><input type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value"></li>
                <li><input type="submit" name="btn_submit" value="선택복사" onclick="document.pressed=this.value"></li>
                <li><input type="submit" name="btn_submit" value="선택이동" onclick="document.pressed=this.value"></li>
                <?php } ?>-->
            </ul>

            <ul class="btn_bo_user">
                <li><?php if ($write_href) { ?><a href="<?php echo $write_href ?>" class="btn_b02">글쓰기</a><?php } ?></li>
            </ul>
        </div>
        <?php } ?>
        </form>
    </div>
</div>

<?php if($is_checkbox) { ?>
<noscript>
<p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
</noscript>
<?php } ?>

<!-- 페이지 -->
<?php echo $write_pages; ?>



<?php if ($is_checkbox) { ?>
<script>
function all_checked(sw) {
    var f = document.fboardlist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]")
            f.elements[i].checked = sw;
    }
}

function fboardlist_submit(f) {
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택복사") {
        select_copy("copy");
        return;
    }

    if(document.pressed == "선택이동") {
        select_copy("move");
        return;
    }

    if(document.pressed == "선택삭제") {
        if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
            return false;

        f.removeAttribute("target");
        f.action = "./board_list_update.php";
    }

    return true;
}

// 선택한 게시물 복사 및 이동
function select_copy(sw) {
    var f = document.fboardlist;

    if (sw == 'copy')
        str = "복사";
    else
        str = "이동";

    var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

    f.sw.value = sw;
    f.target = "move";
    f.action = "./move.php";
    f.submit();
}
</script>
<?php } ?>
<!-- 게시판 목록 끝 -->
