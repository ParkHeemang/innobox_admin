<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

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

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<div id="bbs" class="bbs_<?php echo $bo_table; ?>">

    <article id="bo_v" style="width:<?php echo $width; ?>">
        <header>
            <h1 id="bo_v_title">
                <?php
                if ($category_name) echo ($category_name ? $view['ca_name'].' | ' : ''); // 분류 출력 끝
                echo cut_str(get_text($view['wr_subject']), 70); // 글제목 출력
                ?>
            </h1>
            <section id="bo_v_info">
                <h2>페이지 정보</h2>
                <span>등록일 : <strong><?php echo date("Y.m.d", strtotime($view['wr_datetime'])) ?></strong></span>
                <span style="margin:0px 4px;">작성자 : <strong><?php echo $view['name'] ?></strong></span>
                <span>조회수 : <strong><?php echo number_format($view['wr_hit']) ?>회</strong></span>
            </section>
        </header>

        <?php
        if ($view['link']) {
        ?>
        <section id="bo_v_link">
            <h2>관련링크</h2>
            <ul>
            <?php
            // 링크
            $cnt = 0;
            for ($i=1; $i<=count($view['link']); $i++) {
                if ($view['link'][$i]) {
                    $cnt++;
                    $link = cut_str($view['link'][$i], 70);
             ?>
                <li>
                    <a href="<?php echo $view['link_href'][$i] ?>" target="_blank">
                        <img src="<?php echo $board_skin_url ?>/img/icon_link.gif" alt="관련링크">
                        <strong><?php echo $link ?></strong>
                    </a>
                    <span class="bo_v_link_cnt"><?php echo $view['link_hit'][$i] ?>회 연결</span>
                </li>
            <?php
                }
            }
             ?>
            </ul>
        </section>
        <?php } ?>

        <div id="bo_v_top">
            <?php
            ob_start();
             ?>
            
            <div class="bo_v_list">
                <a href="<?php echo $list_href ?>" class="">목록</a>
            </div>
            <ul class="bo_v_com">
                <?php if ($update_href) { ?><li><a href="<?php echo $update_href ?>" class="btn_b01">수정</a></li><?php } ?>
                <?php if ($delete_href) { ?><li><a href="<?php echo $delete_href ?>" class="btn_b01" onclick="del(this.href); return false;">삭제</a></li><?php } ?>

                <?php if ($search_href) { ?><li><a href="<?php echo $search_href ?>" class="btn_b01">검색</a></li><?php } ?>

            </ul>
            <?php
            $link_buttons = ob_get_contents();
            ob_end_flush();
             ?>
        </div>

        <section id="bo_v_atc">
            <h2 id="bo_v_atc_title">본문</h2>

            <?php
            // 파일 출력
            $v_img_count = count($view['file']);
            if($v_img_count) {
                echo "<div id=\"bo_v_img\">\n";

                for ($i=0; $i<=count($view['file']); $i++) {
                    if ($view['file'][$i]['view']) {
                        //echo $view['file'][$i]['view'];
                        echo get_view_thumbnail($view['file'][$i]['view']);
                    }
                }

                echo "</div>\n";
            }
             ?>

            <div id="bo_v_con"><?php echo get_view_thumbnail($view['content']); ?></div>
            <?php//echo $view['rich_content']; // {이미지:0} 과 같은 코드를 사용할 경우 ?>

            <?php if ($is_signature) { ?><p><?php echo $signature ?></p><?php } ?>


            <?php
            include(G5_SNS_PATH."/view.sns.skin.php");
            ?>
        </section>

        <?php
        if ($view['file']['count']) {
            $cnt = 0;
            for ($i=0; $i<count($view['file']); $i++) {
                if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view'])
                    $cnt++;
            }
        }
         ?>

        <?php if($cnt) { ?>
        <section id="bo_v_file">
            <h2>첨부파일</h2>
            <ul>
            <?php
            // 가변 파일
            for ($i=0; $i<count($view['file']); $i++) {
                if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) {
             ?>
                <li>
                    <span class="file_title">첨부파일</span>
                    <a href="<?php echo $view['file'][$i]['href'];  ?>" class="view_file_download">
                        <span><?php echo $view['file'][$i]['source'] ?></span>
                    </a>
                </li>
            <?php
                }
            }
             ?>
            </ul>
        </section>
        <?php } ?>
        <?php if($bo_table == "maintenance") { ?>
            <?php
            // 코멘트 입출력
            include_once(G5_BBS_PATH.'/view_comment.php');
             ?>
        <?php } ?>
        <div id="bo_v_bot">
            <!-- 링크 버튼 -->
            <?php echo $link_buttons ?>
        </div>

    </article>
</div>

<script>
<?php if ($board['bo_download_point'] < 0) { ?>
$(function() {
    $("a.view_file_download").click(function() {
        if(!g5_is_member) {
            alert("다운로드 권한이 없습니다.\n회원이시라면 로그인 후 이용해 보십시오.");
            return false;
        }

        var msg = "파일을 다운로드 하시면 포인트가 차감(<?php echo number_format($board['bo_download_point']) ?>점)됩니다.\n\n포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다.\n\n그래도 다운로드 하시겠습니까?";

        if(confirm(msg)) {
            var href = $(this).attr("href")+"&js=on";
            $(this).attr("href", href);

            return true;
        } else {
            return false;
        }
    });
});
<?php } ?>

function board_move(href)
{
    window.open(href, "boardmove", "left=50, top=50, width=500, height=550, scrollbars=1");
}
</script>

<!-- 게시글 보기 끝 -->

<script>
$(function() {
    $("a.view_image").click(function() {
        window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
        return false;
    });

    // 추천, 비추천
    $("#good_button, #nogood_button").click(function() {
        var $tx;
        if(this.id == "good_button")
            $tx = $("#bo_v_act_good");
        else
            $tx = $("#bo_v_act_nogood");

        excute_good(this.href, $(this), $tx);
        return false;
    });

    // 이미지 리사이즈
    $("#bo_v_atc").viewimageresize();
});

function excute_good(href, $el, $tx)
{
    $.post(
        href,
        { js: "on" },
        function(data) {
            if(data.error) {
                alert(data.error);
                return false;
            }

            if(data.count) {
                $el.find("strong").text(number_format(String(data.count)));
                if($tx.attr("id").search("nogood") > -1) {
                    $tx.text("이 글을 비추천하셨습니다.");
                    $tx.fadeIn(200).delay(2500).fadeOut(200);
                } else {
                    $tx.text("이 글을 추천하셨습니다.");
                    $tx.fadeIn(200).delay(2500).fadeOut(200);
                }
            }
        }, "json"
    );
}
</script>