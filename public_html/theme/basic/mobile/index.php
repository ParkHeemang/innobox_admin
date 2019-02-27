<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_THEME_MOBILE_PATH.'/head.php');
?>
<div id="m_index">
    <div id="slide_wrap">
        <div class="flexslider">
            <ul class="slides">
                <li>
                    <a href="#"><img src="<?php echo G5_THEME_IMG_URL?>/m_slide1.png"></a>
                </li>
                <li>
                    <a href="#"><img src="<?php echo G5_THEME_IMG_URL?>/m_slide2.png"></a>
                </li>
                <li>
                    <a href="#"><img src="<?php echo G5_THEME_IMG_URL?>/m_slide3.png"></a>
                </li>
                <li>
                    <a href="#"><img src="<?php echo G5_THEME_IMG_URL?>/m_slide4.png"></a>
                </li>
                <li>
                    <a href="#"><img src="<?php echo G5_THEME_IMG_URL?>/m_slide5.png"></a>
                </li>
            </ul>
        </div>
        <div class="custom-navigation">
            <div class="custom-controls-container"></div>
        </div>
    </div>
    <div id="main_lt">
        <div class="h">
            <h3>CALL CENTER <span><a href="tel:010-2738-3653">010-2738-3653</a></span></h3>
            <a href="<?php echo G5_BBS_URL?>/board.php?bo_table=maintenance" class="m_btn">유지보수 신청하기</a>
            <div class="s_tit">
            한번의 프로젝트가서로에게 믿음 감동, 소통이 되는곳 이노박스와 함께하세요.
            </div>
        </div>
        <table class="q">
            <tr>
                <td><a href="<?php echo G5_BBS_URL?>/board.php?bo_table=portfolio"><img src="<?php echo G5_THEME_IMG_URL?>/m_lt_ico1.png">포트폴리오</a></td>
                <td><a href="<?php echo G5_BBS_URL?>/content.php?co_id=shoppingmall"><img src="<?php echo G5_THEME_IMG_URL?>/m_lt_ico2.png">쇼핑몰</a></td>
                <td><a href="<?php echo G5_BBS_URL?>/content.php?co_id=homepage"><img src="<?php echo G5_THEME_IMG_URL?>/m_lt_ico3.png">홈페이지</a></td>
            </tr>
            <tr>
                <td><a href="<?php echo G5_BBS_URL?>/content.php?co_id=mobile"><img src="<?php echo G5_THEME_IMG_URL?>/m_lt_ico4.png">모바일</a></td>
                <td><a href="<?php echo G5_BBS_URL?>/content.php?co_id=marketing"><img src="<?php echo G5_THEME_IMG_URL?>/m_lt_ico5.png">마케팅서비스</a></td>
                <td><a href="<?php echo G5_BBS_URL?>/content.php?co_id=maintenance"><img src="<?php echo G5_THEME_IMG_URL?>/m_lt_ico6.png">유지보수</a></td>
            </tr>
        </table>
        <div class="menu_nt">
            <div class="hd">
                <h3>견적문의</h3>
            </div>
            <form name="fcs" id="fcs" method="post" action="./quick_update.php">
            <table>
                <tr>
                    <th>회사명</th>
                    <td>
                        <input type="text" name="q_name" required class="f">
                    </td>
                </tr>
                <tr>
                    <th>연락처</th>
                    <td>
                        <input type="text" name="q_hp" required class="f">
                    </td>
                </tr>
                <tr>
                    <th>문의내역</th>
                    <td>
                        <textarea name="q_con" class="f" placeholder="기존 홈페이지가 있으시면 홈페이지 주소를 적어주세요.
홈페이지나 쇼핑몰 제작을 의뢰하실경우 벤치마킹할 사이트를 적어주시면 더욱 빠른 견적상담을 받으실 수 있습니다."></textarea>
                    </td>
                </tr>
            </table>
            <input type="submit" value="문의하기">
            </form>
        </div>
        <!--
        <div class="menu nt">
            <div class="hd">
                <h3>NOTICE</h3>
                <div class="r">
                    <a href="<?php echo G5_BBS_URL?>/board.php?bo_table=notice"><img src="<?php echo G5_THEME_IMG_URL?>/m_lt_write.png"></a>
                    <a href="<?php echo G5_BBS_URL?>/board.php?bo_table=notice"><img src="<?php echo G5_THEME_IMG_URL?>/m_lt_read.png"></a>
                </div>
            </div>
            <table>
                <?php
                    $sql = " select * from g5_write_notice
                             where (1)
                             order by wr_datetime desc
                             limit 2
                    ";
                    $res = sql_query($sql);
                    echo '<table>';
                    for ($i=0; $row=sql_fetch_array($res); $i++) {
                        echo '<tr>';
                        echo '<th><a href="'.G5_BBS_URL.'/board.php?bo_table=notice&wr_id='.$row['wr_id'].'">'.$row['wr_subject'].'</a></th>';
                        echo '<td>'.date("m.d", strtotime($row['wr_datetime'])).'</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                ?>
            </table>
            <div class="more">
                <a href="<?php echo G5_BBS_URL?>/board.php?bo_table=notice">more</a>
            </div>
        </div>

        <div class="menu hm">
            <div class="hd">
                <h3>HOMEPAGE/MOBILE</h3>
                <div class="r">
                    <a href="<?php echo G5_BBS_URL?>/write.php?bo_table=cscenter"><img src="<?php echo G5_THEME_IMG_URL?>/m_lt_write.png"></a>
                    <a href="<?php echo G5_BBS_URL?>/board.php?bo_table=cscenter"><img src="<?php echo G5_THEME_IMG_URL?>/m_lt_read.png"></a>
                </div>
            </div>
            <table>
                <?php
                    $sql = " select * from g5_write_cscenter
                             where (1)
                             order by wr_datetime desc
                             limit 2
                    ";
                    $res = sql_query($sql);
                    echo '<table>';
                    for ($i=0; $row=sql_fetch_array($res); $i++) {
                        echo '<tr>';
                        echo '<th><a href="'.G5_BBS_URL.'/board.php?bo_table=cscenter&wr_id='.$row['wr_id'].'">'.$row['wr_subject'].'</a></th>';
                        echo '<td>'.date("m.d", strtotime($row['wr_datetime'])).'</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                ?>
            </table>
            <div class="more">
                <a href="<?php echo G5_BBS_URL?>/board.php?bo_table=cscenter">more</a>
            </div>
        </div>
        -->


    </div>

    <div id="portfolio_wrap">
        <div id="portfolio">
        <h2 data-wow-delay="100ms" class="wow fadeInUp">PORTFOLIO</h2>
        <?php echo latest('theme/main', 'portfolio', 100, 16);?>
        <p data-wow-delay="750ms" class="btn_pofol wow fadeInUp"><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=portfolio">포트폴리오 더보기<img src="<?php echo G5_IMG_URL ?>/btn_img01.png" alt="포트폴리오 더보기"/></a></p>
        <p data-wow-delay="800ms" class="btn_pay wow fadeInUp"><a href="<?php echo G5_BBS_URL ?>/write.php?bo_table=cscenter">견적 상담신청<img src="<?php echo G5_IMG_URL ?>/btn_img02.png" alt="견적상담신청"/></a></p>
        </div>
    </div>

</div>
<script>
$(window).load(function() {
    $('.flexslider').flexslider({
        animation: "slide",
        directionNav: false,
        controlsContainer: $(".custom-controls-container"),
    });
});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
      //===================
      //  WOW
      //  do not touch
      //===================
      try {
          new WOW().init();
      } catch (exception) {
      }
    });
</script>

<?php
include_once(G5_THEME_MOBILE_PATH.'/tail.php');
?>