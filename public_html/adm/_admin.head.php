<?php
if (!defined('_GNUBOARD_')) exit;

$begin_time = get_microtime();

$files = glob(G5_ADMIN_PATH.'/css/admin_extend_*');
if (is_array($files)) {
    foreach ((array) $files as $k=>$css_file) {
        
        $fileinfo = pathinfo($css_file);
        $ext = $fileinfo['extension'];
        
        if( $ext !== 'css' ) continue;
        
        $css_file = str_replace(G5_ADMIN_PATH, G5_ADMIN_URL, $css_file);
        add_stylesheet('<link rel="stylesheet" href="'.$css_file.'">', $k);
    }
}

include_once(G5_PATH.'/_head.sub.php');
/* 1:plugin, 2:theme, 3:skin, 4:closer, 0:external */
add_javascript('<script src="'.G5_JS_URL.'/jquery.form.min.js?ver='.G5_JS_VER.'"></script>', 0);
add_stylesheet('<link rel="stylesheet" href="'.G5_ADMIN_URL.'/css/patch.css?ver='.G5_CSS_VER.'">', 4); // 수정 불가
add_stylesheet('<link rel="stylesheet" href="'.G5_ADMIN_URL.'/css/style.css?ver='.G5_CSS_VER.'">', 4); // 커스텀은 이 파일에서
add_javascript('<script src="'.G5_ADMIN_URL.'/js/script.js?ver='.G5_JS_VER.'"></script>', 4);

function print_menu1($key, $no='')
{
    global $menu;

    $str = print_menu2($key, $no);

    return $str;
}

function print_menu2($key, $no='')
{
    global $menu, $auth_menu, $is_admin, $auth, $g5, $sub_menu;

    $str .= "<ul>";
    for($i=1; $i<count($menu[$key]); $i++)
    {
        if ($is_admin != 'super' && (!array_key_exists($menu[$key][$i][0],$auth) || !strstr($auth[$menu[$key][$i][0]], 'r')))
            continue;

        if (($menu[$key][$i][4] == 1 && $gnb_grp_style == false) || ($menu[$key][$i][4] != 1 && $gnb_grp_style == true)) $gnb_grp_div = 'gnb_grp_div';
        else $gnb_grp_div = '';

        if ($menu[$key][$i][4] == 1) $gnb_grp_style = 'gnb_grp_style';
        else $gnb_grp_style = '';

        $current_class = '';

        if ($menu[$key][$i][0] == $sub_menu){
            $current_class = ' on';
        }

        $str .= '<li data-menu="'.$menu[$key][$i][0].'"><a href="'.$menu[$key][$i][2].'" class="gnb_2da '.$gnb_grp_style.' '.$gnb_grp_div.$current_class.'">'.$menu[$key][$i][1].'</a></li>';

        $auth_menu[$menu[$key][$i][0]] = $menu[$key][$i][1];
    }
    $str .= "</ul>";

    return $str;
}

$adm_menu_cookie = array(
'container' => '',
'gnb'       => '',
'btn_gnb'   => '',
);

if( ! empty($_COOKIE['g5_admin_btn_gnb']) || count($menu['menu'.substr($sub_menu,0,3)]) < 2 ){
    $adm_menu_cookie['container'] = 'container-small';
    $adm_menu_cookie['gnb'] = 'gnb_small';
    $adm_menu_cookie['btn_gnb'] = 'btn_gnb_open';
}
?>

<script>
var tempX = 0;
var tempY = 0;

function imageview(id, w, h)
{

    menu(id);

    var el_id = document.getElementById(id);

    //submenu = eval(name+".style");
    submenu = el_id.style;
    submenu.left = tempX - ( w + 11 );
    submenu.top  = tempY - ( h / 2 );

    selectBoxVisible();

    if (el_id.style.display != 'none')
        selectBoxHidden(id);
}
</script>

<div id="to_content"><a href="#container">본문 바로가기</a></div>

<header id="hd">
    <h1><?php echo $config['cf_title'] ?></h1>
    <div id="hd_top">
        <button type="button" id="btn_gnb" class="btn_gnb_close <?php echo $adm_menu_cookie['btn_gnb'];?>">메뉴</button>
       <div id="logo"><a href="<?php echo G5_ADMIN_URL ?>"><img src="<?php echo G5_ADMIN_URL ?>/img/logo.png" alt="<?php echo $config['cf_title'] ?> 관리자"></a></div>

        <div id="tnb">
            <ul>
                <li class="tnb_li"><a href="<?php echo G5_SHOP_URL ?>/" class="tnb_shop" target="_blank" title="쇼핑몰 바로가기">쇼핑몰 바로가기</a></li>
                <li class="tnb_li"><a href="<?php echo G5_URL ?>/" class="tnb_community" target="_blank" title="커뮤니티 바로가기">커뮤니티 바로가기</a></li>
                <!-- <li class="tnb_li"><a href="<?php echo G5_ADMIN_URL ?>/service.php" class="tnb_service">부가서비스</a></li> -->
                <li class="tnb_li"><button type="button" class="tnb_mb_btn">관리자<span class="./img/btn_gnb.png">메뉴열기</span></button>
                    <ul class="tnb_mb_area">
                        <li><a href="<?php echo G5_ADMIN_URL ?>/member_form.php?w=u&amp;mb_id=<?php echo $member['mb_id'] ?>">관리자정보</a></li>
                        <li id="tnb_logout"><a href="<?php echo G5_BBS_URL ?>/logout.php">로그아웃</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <nav id="gnb" class="gnb_large <?php echo $adm_menu_cookie['gnb']; ?>">
        <h2>관리자 주메뉴</h2>
        <ul class="gnb_ul">
            <?php
            $jj = 1;
            foreach($amenu as $key=>$value) {
                $href1 = $href2 = '';

                if ($menu['menu'.$key][0][2]) {
                    $href1 = '<a href="'.$menu['menu'.$key][0][2].'" class="gnb_1da">';
                    $href2 = '</a>';
                } else {
                    continue;
                }

                $current_class = "";
                if (isset($sub_menu) && (substr($sub_menu, 0, 3) == substr($menu['menu'.$key][0][0], 0, 3)))
                    $current_class = " on";

                $button_title = $menu['menu'.$key][0][1];
            ?>
            <li class="gnb_li<?php echo $current_class;?>">
                <button type="button" class="btn_op menu-<?php echo $key; ?> menu-order-<?php echo $jj; ?>" data-menu="<?php echo $key; ?>" data-title="<?php echo $button_title; ?>" data-href="<?php echo $menu['menu'.$key][0][2]; ?>"><?php echo $button_title;?></button>
                <div class="gnb_oparea_wr">
                    <?php if (count($menu['menu'.$key]) > 1) { ?>
                    <div class="gnb_oparea">
                        <h3><?php echo $menu['menu'.$key][0][1];?></h3>
                        <?php echo print_menu1('menu'.$key, 1); ?>
                    </div>
                    <?php } ?>
                </div>
            </li>
            <?php
            $jj++;
            }     //end foreach
            ?>
        </ul>
    </nav>

</header>
<script>
jQuery(function($){

    var menu_cookie_key = 'g5_admin_btn_gnb';

    $(".tnb_mb_btn").click(function(){
        $(".tnb_mb_area").toggle();
    });

    $("#btn_gnb").click(function(){
        var $this = $(this);

        // 하위메뉴가 없는 경우
        if ($('#gnb .gnb_li.on .gnb_oparea ul li').length == 0) {
            return false;
        }

        try {
            if( ! $this.hasClass("btn_gnb_open") ){
                set_cookie(menu_cookie_key, 1, 60*60*24*365);
            } else {
                delete_cookie(menu_cookie_key);
            }
        }
        catch(err) {
        }

        $("#container").toggleClass("container-small");
        $("#gnb").toggleClass("gnb_small");
        $this.toggleClass("btn_gnb_open");

    });

    $(".gnb_ul li .btn_op" ).click(function() {
        var $this = $(this),
            mnkey = $this.data('menu').toString(),
            mnlnk = ['999'],
            cmenu = $this.next().find('.gnb_oparea ul').children('li').length;

        // 하위메뉴가 없는 경우
        if (cmenu == 0 || $.inArray(mnkey, mnlnk) !== -1) {
            location.href = $this.data('href');
            return false;
        }

        $(this).parent().addClass("on").siblings().removeClass("on");
    });

});
</script>


<div id="wrapper">

    <div id="container" class="<?php echo $adm_menu_cookie['container']; ?>">

        <h1 id="container_title"><?php echo $g5['title'] ?></h1>
        <div class="container_wr">