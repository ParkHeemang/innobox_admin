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

include_once(G5_ADMIN_PATH.'/head.sub.php');

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

if( ! empty($_COOKIE['g5_admin_btn_gnb']) ){
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

    <div class="wrapper">
		<header class="main-header">
			<!-- Logo -->
			<a href="./" class="logo">
			<!-- mini logo for sidebar mini 50x50 pixels -->
			<span class="logo-mini"><b>A</b>LT</span>
			<!-- logo for regular state and mobile devices -->
			<span class="logo-lg"><b>Admin</b>모드</span>
			</a>
			<!-- Header Navbar: style can be found in header.less -->
			<nav class="navbar navbar-static-top">
				<!-- Sidebar toggle button-->
				<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
					<span class="sr-only">Toggle navigation</span>
				</a>
				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<!-- Messages: style can be found in dropdown.less-->
						<li class="dropdown messages-menu">
							<a href="http://demo.sir.kr/gnuboard5/shop/" target="_blank" title="쇼핑몰 바로가기">
							  <i class="glyphicon glyphicon-shopping-cart"></i>
							</a>

						</li>
						<!-- Notifications: style can be found in dropdown.less -->
						<li class="dropdown notifications-menu">
							<a href="http://www.innobox.co.kr/" target="_blank" title="커뮤니티 바로가기">
							 <i class="glyphicon glyphicon-home"></i>  
							</a>
						</li>
						<!-- Tasks: style can be found in dropdown.less -->
						<li class="dropdown notifications-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-bell-o"></i>
								<span class="label label-warning">10</span>
							</a>
							<ul class="dropdown-menu">
								<li class="header">You have 10 notifications</li>
								<li>
									<!-- inner menu: contains the actual data -->
									<ul class="menu">
										<li><a href="#"><i class="fa fa-users text-aqua"></i> 5 new members joined today</a></li>
										<li><a href="#"><i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the page and may cause design problems</a></li>
										<li><a href="#"><i class="fa fa-users text-red"></i> 5 new members joined</a></li>
										<li><a href="#"><i class="fa fa-shopping-cart text-green"></i> 25 sales made</a></li>
										<li><a href="#"><i class="fa fa-user text-red"></i> You changed your username</a></li>
									</ul>
								</li><li class="footer"><a href="#">View all</a></li>
							</ul>
						</li>
						<!-- User Account: style can be found in dropdown.less -->

						<!--부가서비스 -->
						<li class="dropdown messages-menu">
							<a href="http://demo.sir.kr/gnuboard5/adm/service.php" target="_blank">
							  부가서비스
							</a>
						</li>
						<!--부가서비스 -->
						<li class="dropdown user user-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<img src="<?php echo G5_ADMIN_URL?>/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
								<span class="hidden-xs">관리자</span>
							</a>
							<ul class="dropdown-menu" >
								<!-- User image -->
								<li class="user-header">
									<img src="<?php echo G5_ADMIN_URL?>/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
									<p>
										관리자 정보입니다
										<small>2018년 8월 16일</small>
									</p>
								</li>
								<!-- Menu Body -->
								<li class="user-body">
									<div class="row">
										<div class="col-xs-4 text-center">
											<a href="#">Followers</a>
										</div>
										<div class="col-xs-4 text-center">
											<a href="#">Sales</a>
										</div>
										<div class="col-xs-4 text-center">
											<a href="#">Friends</a>
										</div>
									</div>
									<!-- /.row -->
								</li>
								<!-- Menu Footer-->
								<li class="user-footer">
									<div class="pull-left">
										<a href="<?php echo G5_ADMIN_URL ?>/member_form.php?w=u&amp;mb_id=<?php echo $member['mb_id'] ?>" class="btn btn-default btn-flat">관리자 정보</a>
									</div>
									<div class="pull-right">
										<a href="<?php echo G5_BBS_URL ?>/logout.php" class="btn btn-default btn-flat">로그 아웃</a>
									</div>
								</li>
							</ul>
						</li>
						<!-- Control Sidebar Toggle Button -->
						<li>
							<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
						</li>
					</ul>
				</div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <!-- search form -->
                <form action="#" method="get" class="sidebar-form">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Search...">
                        <span class="input-group-btn">
							<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
						</span>
                    </div>
                </form>
                <!-- /.search form -->
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">관리자 메뉴</li>
                    <li class="treeview">
                        <a href="#">
							<i class="glyphicon glyphicon-cog"></i> <span>환경설정</span>
							<span class="pull-right-container">
							  <i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
                        <ul class="treeview-menu">
                            <li class="active"><a href="<?php echo G5_ADMIN_URL?>/config_form.php"><i class="fa fa-circle-o"></i> 기본환경설정</a></li>
                            <li><a href="<?php echo G5_ADMIN_URL?>/config_more.php"><i class="fa fa-circle-o"></i> 기타환경설정</a></li>
							<li><a href="<?php echo G5_ADMIN_URL?>/auth_list.php"><i class="fa fa-circle-o"></i> 관리권한설정</a></li>
							<li><a href="<?php echo G5_ADMIN_URL?>/theme.php"><i class="fa fa-circle-o"></i> 테마설정</a></li>
                            <li><a href="<?php echo G5_ADMIN_URL?>/menu_list.php"><i class="fa fa-circle-o"></i> 메뉴설정</a></li>
							 <li><a href="<?php echo G5_ADMIN_URL?>/sendmail_test.php"><i class="fa fa-circle-o"></i> 메일 테스트</a></li>
                            <li><a href="<?php echo G5_ADMIN_URL?>/newwinlist.php"><i class="fa fa-circle-o"></i> 팝업레이어관리</a></li>
							<li><a href="<?php echo G5_ADMIN_URL?>/slider_list.php"><i class="fa fa-circle-o"></i> 메인슬라이더관리</a></li>
                            <li><a href="<?php echo G5_ADMIN_URL?>/session_file_delete.php"><i class="fa fa-circle-o"></i> 세션파일 일괄삭제</a></li>
                            <li><a href="<?php echo G5_ADMIN_URL?>/cache_file_delete.php"><i class="fa fa-circle-o"></i> 캐시파일 일괄삭제</a></li>
							<li><a href="<?php echo G5_ADMIN_URL?>/captcha_file_delete.php"><i class="fa fa-circle-o"></i> 캡챠파일 일괄삭제</a></li>
							<li><a href="<?php echo G5_ADMIN_URL?>/thumbnail_file_delete.php"><i class="fa fa-circle-o"></i> 썸네일파일 일괄삭제</a></li>
                        </ul>
                    </li>

                    <li class="treeview">
                        <a href="#">
							<i class="glyphicon glyphicon-user"></i> <span>회원관리</span>
							<span class="pull-right-container">
							  <i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
                        <ul class="treeview-menu">
                            <li class="active"><a href="<?php echo G5_ADMIN_URL?>/member_list.php"><i class="fa fa-circle-o"></i> 회원관리</a></li>
                            <li><a href="<?php echo G5_ADMIN_URL?>/mail_list.php"><i class="fa fa-circle-o"></i> 회원메일발송</a></li>
							<li><a href="<?php echo G5_ADMIN_URL?>/visit_list.php"><i class="fa fa-circle-o"></i> 접속자집계</a></li>
							<li><a href="<?php echo G5_ADMIN_URL?>/visit_search.php"><i class="fa fa-circle-o"></i> 접속자검색</a></li>
							<li><a href="<?php echo G5_ADMIN_URL?>/visit_delete.php"><i class="fa fa-circle-o"></i> 접속자로그삭제</a></li>
							 <li><a href="<?php echo G5_ADMIN_URL?>/money_list.php"><i class="fa fa-circle-o"></i> 예치금관리</a></li> 
                            <li><a href="<?php echo G5_ADMIN_URL?>/point_list.php"><i class="fa fa-circle-o"></i> 포인트관리</a></li> 
                            <li><a href="<?php echo G5_ADMIN_URL?>/poll_list.php"><i class="fa fa-circle-o"></i> 투표관리</a></li>	
							
							
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
							<i class="glyphicon glyphicon-list"></i> <span>게시판관리</span>
							<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
							<small class="label pull-right bg-green">new</small>
							</span>
						</a>
                        <ul class="treeview-menu">                            
                            <li><a href="<?php echo G5_ADMIN_URL?>/board_list.php"><i class="fa fa-circle-o"></i> 게시판관리</a></li>
                            <li><a href="<?php echo G5_ADMIN_URL?>/boardgroup_list.php"><i class="fa fa-circle-o"></i> 게시판그룹관리</a></li>
							<li><a href="<?php echo G5_ADMIN_URL?>/popular_list.php"><i class="fa fa-circle-o"></i> 인기검색어관리</a></li>
							<li><a href="<?php echo G5_ADMIN_URL?>/popular_rank.php"><i class="fa fa-circle-o"></i> 인기검색어순위</a></li>
							 <li><a href="<?php echo G5_ADMIN_URL?>/qa_config.php"><i class="fa fa-circle-o"></i> 1:1문의설정</a></li>                            
                            <li><a href="<?php echo G5_ADMIN_URL?>/faqmasterlist.php"><i class="fa fa-circle-o"></i> FAQ관리</a></li>
							<li class="active"><a href="<?php echo G5_ADMIN_URL?>/contentlist.php"><i class="fa fa-circle-o"></i> 내용관리</a></li>
							<li><a href="<?php echo G5_ADMIN_URL?>/write_count.php"><i class="fa fa-circle-o"></i> 글,댓글 현황</a></li>                           
							<!-- <li><a href="<?php echo G5_ADMIN_URL?>/hotkeyword.php"><i class="fa fa-circle-o"></i> 핫키워드관리</a></li> -->							
                        </ul>
                    </li>
					<li class="treeview">
                        <a href="#">
                    							<i class="glyphicon glyphicon-shopping-cart"></i> <span>쇼핑몰관리</span>
                    							<span class="pull-right-container">
                    							  <i class="fa fa-angle-left pull-right"></i>
                    							</span>
                    						</a>
                        <ul class="treeview-menu">
                            <li class="active"><a href="<?php echo G5_SHOP_ADMIN_URL?>/configform.php"><i class="fa fa-circle-o"></i> 쇼핑몰설정</a></li>
                            <li><a href="<?php echo G5_SHOP_ADMIN_URL?>/orderlist.php"><i class="fa fa-circle-o"></i> 주문내역</a></li>
                            <li><a href="<?php echo G5_SHOP_ADMIN_URL?>/personalpaylist.php"><i class="fa fa-circle-o"></i> 개인결제관리</a></li>
                            <li><a href="<?php echo G5_SHOP_ADMIN_URL?>/categorylist.php"><i class="fa fa-circle-o"></i> 분류관리</a></li>
                            <li><a href="<?php echo G5_SHOP_ADMIN_URL?>/itemlist.php"><i class="fa fa-circle-o"></i> 상품관리</a></li>
                            <li><a href="<?php echo G5_SHOP_ADMIN_URL?>/itemqalist.php"><i class="fa fa-circle-o"></i> 상품문의</a></li>
                            <li><a href="<?php echo G5_SHOP_ADMIN_URL?>/itemuselist.php"><i class="fa fa-circle-o"></i> 사용후기</a></li>
                            <li><a href="<?php echo G5_SHOP_ADMIN_URL?>/itemstocklist.php"><i class="fa fa-circle-o"></i> 상품재고관리</a></li>
                            <li><a href="<?php echo G5_SHOP_ADMIN_URL?>/itemtypelist.php"><i class="fa fa-circle-o"></i> 상품유형관리</a></li>
                            <li><a href="<?php echo G5_SHOP_ADMIN_URL?>/optionstocklist.php"><i class="fa fa-circle-o"></i> 상품옵션재고관리</a></li>
                            <li><a href="<?php echo G5_SHOP_ADMIN_URL?>/couponlist.php"><i class="fa fa-circle-o"></i> 쿠폰관리</a></li>
                            <li><a href="<?php echo G5_SHOP_ADMIN_URL?>/couponzonelist.php"><i class="fa fa-circle-o"></i> 구폰존관리</a></li>
                            <li><a href="<?php echo G5_SHOP_ADMIN_URL?>/sendcostlist.php"><i class="fa fa-circle-o"></i> 추가배송비관리</a></li>
                            <li><a href="<?php echo G5_SHOP_ADMIN_URL?>/inorderlist.php"><i class="fa fa-circle-o"></i> 미완료주문</a></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
                    							<i class="glyphicon glyphicon-sort-by-attributes"></i> <span>쇼핑몰현황/기타</span>
                    							<span class="pull-right-container">
                    							  <i class="fa fa-angle-left pull-right"></i>
                    							</span>
                    						</a>
                        <ul class="treeview-menu">
                            <li class="active"><a href="<?php echo G5_SHOP_ADMIN_URL?>/sale1.php"><i class="fa fa-circle-o"></i> 매출현황</a></li>
                            <li><a href="<?php echo G5_SHOP_ADMIN_URL?>/itemsellrank.php"><i class="fa fa-circle-o"></i> 상품판매순위</a></li>
                            <li><a href="<?php echo G5_SHOP_ADMIN_URL?>/orderprint.php"><i class="fa fa-circle-o"></i> 주문내역출력</a></li>
                            <li><a href="<?php echo G5_SHOP_ADMIN_URL?>/itemstocksms.php"><i class="fa fa-circle-o"></i> 재입고SMS알림</a></li>
                            <li><a href="<?php echo G5_SHOP_ADMIN_URL?>/itemevent.php"><i class="fa fa-circle-o"></i> 이벤트관리</a></li>
                            <li><a href="<?php echo G5_SHOP_ADMIN_URL?>/itemeventlist.php"><i class="fa fa-circle-o"></i> 이벤트일괄처리</a></li>
                            <li><a href="<?php echo G5_SHOP_ADMIN_URL?>/bannerlist.php"><i class="fa fa-circle-o"></i> 배너관리</a></li>
                            <li><a href="<?php echo G5_SHOP_ADMIN_URL?>/wishlist.php"><i class="fa fa-circle-o"></i> 보관함현황</a></li>
                            <li><a href="<?php echo G5_SHOP_ADMIN_URL?>/price.php"><i class="fa fa-circle-o"></i> 가격비교사이트</a></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
							<i class="glyphicon glyphicon-send"></i> <span>SMS 관리</span>
							<span class="pull-right-container">
							  <i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
                        <ul class="treeview-menu">
                            <li class="active"><a href="<?php echo G5_SMS5_ADMIN_URL?>/config.php"><i class="fa fa-circle-o"></i> SMS 기본설정</a></li>
                            <li><a href="<?php echo G5_SMS5_ADMIN_URL?>/member_update.php"><i class="fa fa-circle-o"></i> 회원정보업데이트</a></li>
                            <li><a href="<?php echo G5_SMS5_ADMIN_URL?>/sms_write.php"><i class="fa fa-circle-o"></i> 문자 보내기</a></li>
                            <li><a href="<?php echo G5_SMS5_ADMIN_URL?>/history_list.php"><i class="fa fa-circle-o"></i> 전송내역-건별</a></li>
                            <li><a href="<?php echo G5_SMS5_ADMIN_URL?>/history_num.php"><i class="fa fa-circle-o"></i> 전송내역-번호별</a></li>
                            <li><a href="<?php echo G5_SMS5_ADMIN_URL?>/form_group.php"><i class="fa fa-circle-o"></i> 이모티콘 그룹</a></li>
                            <li><a href="<?php echo G5_SMS5_ADMIN_URL?>/form_list.php"><i class="fa fa-circle-o"></i> 이모티콘 관리</a></li>
                            <li><a href="<?php echo G5_SMS5_ADMIN_URL?>/num_group.php"><i class="fa fa-circle-o"></i> 휴대폰번호 그룹</a></li>
                            <li><a href="<?php echo G5_SMS5_ADMIN_URL?>/num_book.php"><i class="fa fa-circle-o"></i> 휴대폰번호 관리</a></li>
							<li><a href="<?php echo G5_SMS5_ADMIN_URL?>/num_book_file.php"><i class="fa fa-circle-o"></i> 휴대폰번호 파일</a></li>
                        </ul>
                    </li>                    
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

		<script>
		//현재 열려있는 메뉴바 활성화
		var url = window.location;
		// for sidebar menu but not for treeview submenu
		$('ul.sidebar-menu a').filter(function() {
			return this.href == url;
		}).parent().siblings().removeClass('active').end().addClass('active');
		// for treeview which is like a submenu
		$('ul.treeview-menu a').filter(function() {
			return this.href == url;
		}).parentsUntil(".sidebar-menu > .treeview-menu").siblings().removeClass('active').end().addClass('active');
		</script>