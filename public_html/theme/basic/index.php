<?php
define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/index.php');
    return;
}

include_once(G5_THEME_PATH.'/head.php');
add_stylesheet('<link rel="stylesheet" href="'.G5_THEME_CSS_URL.'/style.css">', 0);
?>
	<link rel="stylesheet" type="text/css" href="http://themes.mokaine.com/beetle-html/css/font-awesome.min.css" />

	<!-- -->
	<div id="intro-wrap" style="background:url('/img/dell_bg.jpg') no-repeat;">
				<div id="intro" class="preload" data-autoplay="5000" data-navigation="true" data-pagination="true" data-transition="fade">

					<!-- 01 start -->
					<div class="intro-item" style="">
						<div class="intro-mockup-wrapper">
							<div class="caption-mockup caption-right column six last-special" style="position:absolute; top:75px; right:0px; text-align:left; width:321px;z-index:99999">
								<h2 style="font-size:35px; color:#fff; font-weight:normal">광주광역시도시공사</h2>
								<p style="font-size:20px; color:#999; line-height:22px;">
									RECENT PROJECT<br />
									<span style="font-size:15px; color:#999;">WEB SITE/WEBstandard</span>
								</p>
								<P style="font-size:12px; color:#999; letter-spacing:-0.8px; padding-top:20px; padding-bottom:20px;">
									한번의 프로젝트가 서로에게 믿음과 감동, 소통이 되는 곳<br />
									디지털 커뮤니케이션_이노박스와 광주광역시도시공사의 <br />
									홈페이지를 포트폴리오에서 확인해보세요.
								</P>
								<p style="font-size:12px; color:#999;">
									홈페이지 제작부터 유지보수까지 확실하게!<br />
									관공서부터 기업, 쇼핑몰, 소셜에 이르기까지<br />
									이노박스에서 만족스러운 결과물을 보여드립니다.
								</p>
								<a class="button white transparent" href="<?php echo G5_BBS_URL; ?>/board.php?bo_table=portfolio">SEE MORE PROJECT ITEM</a>
							</div><!-- caption -->
							<div class="intro-mockup intro-left column six" style="position:absolute; top:96px; left:0px;">
								<img src="<?php echo G5_IMG_URL; ?>/dell_i04.jpg" alt="" style="max-width:100% !important;">
							</div><!-- intro-mockup -->
						</div><!-- intro-mockup-wrapper -->
					</div>
					<!-- 01 end -->

					<!-- 02 start -->
					<div class="intro-item" style="">
						<div class="intro-mockup-wrapper">
							<div class="caption-mockup caption-right column six last-special" style="position:absolute; top:75px; right:0px; text-align:left; width:321px;z-index:99999">
								<h2 style="font-size:40px; color:#fff; font-weight:normal">심복 어플리케이션</h2>
								<p style="font-size:20px; color:#999; line-height:22px;">
									RECENT PROJECT<br />
									<span style="font-size:15px; color:#999;">WEB/MOBILE/APP</span>
								</p>
								<P style="font-size:12px; color:#999; letter-spacing:-0.8px; padding-top:20px; padding-bottom:20px;">
									심복어플리케이션은 버스회사를 위한 최적의<br />
									메뉴만을 구성하여 만들어진 어플입니다. <br />
									프로젝트 진행 과정을 포트폴리오에서 확인해보세요.
								</P>
								<p style="font-size:12px; color:#999;">
									심복은 버스회사와 버스운전원이 사용할 2가지 어플을<br />
									개발하여 회사와 직원모두가 편리하게 이용할수 있도록<br />
									제작되었습니다. 지금바로 확인해보세요
								</p>
								<a class="button white transparent" href="http://www.innobox.co.kr/bbs/board.php?bo_table=portfolio&wr_id=78">SEE MORE PROJECT ITEM</a>
							</div><!-- caption -->
							<div class="intro-mockup intro-left column six" style="position:absolute; top:96px; left:0px;">
								<img src="<?php echo G5_IMG_URL; ?>/simbok.png" alt="" style="max-width:100% !important;">
							</div><!-- intro-mockup -->
						</div><!-- intro-mockup-wrapper -->
					</div>
					<!-- 02 end -->

					<!-- 03 start -->
					<div class="intro-item" style="">
						<div class="intro-mockup-wrapper">
							<div class="caption-mockup caption-right column six last-special" style="position:absolute; top:75px; right:0px; text-align:left; width:321px;z-index:99999">
								<h2 style="font-size:40px; color:#fff; font-weight:normal">GEAR360 Rental</h2>
								<p style="font-size:20px; color:#999; line-height:22px;">
									RECENT PROJECT<br />
									<span style="font-size:15px; color:#999;">PROMOTION/WEB/MOBILE</span>
								</p>
								<P style="font-size:12px; color:#999; letter-spacing:-0.8px; padding-top:20px; padding-bottom:20px;">
									한번의 프로젝트가 서로에게 믿음과 감동, 소통이 되는 곳<br />
									디지털 커뮤니케이션_이노박스와  국내여행사 + 기어360 <br />
									프로젝트 진행 과정을 포트폴리오에서 확인해보세요.
								</P>
								<p style="font-size:12px; color:#999;">
									메이저 여행사들와 함께한 기어360 렌탈 프로모션 프로젝트<br />
									pc 프로모션 페이지부터 모바일, 인쇄물에 이르기까지<br />
									이노박스에서 만족스러운 결과물을 보여드립니다.
								</p>
								<a class="button white transparent" href="<?php echo G5_BBS_URL; ?>/board.php?bo_table=portfolio">SEE MORE PROJECT ITEM</a>
							</div><!-- caption -->
							<div class="intro-mockup intro-left column six" style="position:absolute; top:96px; left:0px;">
								<img src="<?php echo G5_IMG_URL; ?>/dell_i03.jpg" alt="" style="max-width:100% !important;">
							</div><!-- intro-mockup -->
						</div><!-- intro-mockup-wrapper -->
					</div>
					<!-- 03 end -->

					<div class="intro-item" style="">
						<div class="intro-mockup-wrapper">
							<div class="caption-mockup caption-right column six last-special" style="position:absolute; top:75px; right:0px; text-align:left; width:321px;z-index:99999">
								<h2 style="font-size:40px; color:#fff; font-weight:normal">FLUNCH KOREA</h2>
								<p style="font-size:20px; color:#999; line-height:22px;">
									RECENT PROJECT<br />
									<span style="font-size:15px; color:#999;">SHOPPINGMALL/MOBILE/NFC TAG</span>
								</p>
								<P style="font-size:12px; color:#999; letter-spacing:-0.8px; padding-top:20px; padding-bottom:20px;">
									한번의 프로젝트가 서로에게 믿음과 감동, 소통이 되는 곳<br />
									디지털 커뮤니케이션_이노박스의 프런치코리아프로젝트 진행 과정을<br />
									포트폴리오에서 확인해보세요.
								</P>
								<p style="font-size:12px; color:#999;">
									프런치코리아의 아이덴티티 컬러를 살려 사용자가<br />
									이용하기편하도록 제작된 홈페이지입니다. 회사 소개부터<br />
									쇼핑몰까지 있는 통합 사이트를 오픈하게 되었습니다.
								</p>
								<a class="button white transparent" href="<?php echo G5_BBS_URL; ?>/board.php?bo_table=portfolio">SEE MORE PROJECT ITEM</a>
							</div><!-- caption -->
							<div class="intro-mockup intro-left column six" style="position:absolute; top:96px; left:0px;">
								<img src="<?php echo G5_IMG_URL; ?>/dell_i01.jpg" alt="" style="max-width:100% !important;">
							</div><!-- intro-mockup -->
						</div><!-- intro-mockup-wrapper -->
					</div>
					<div class="intro-item" style="">
						<div class="intro-mockup-wrapper">
							<div class="caption-mockup caption-right column six last-special" style="position:absolute; top:75px; right:0px;  text-align:left; width:321px;">
								<h2 style="font-size:40px; color:#fff; font-weight:normal">ALLD</h2>
								<p style="font-size:20px; color:#999; line-height:22px;">
									RECENT PROJECT<br />
									<span style="font-size:15px; color:#999;">SHOPPINGMALL/MOBILE</span>
								</p>
								<P style="font-size:12px; color:#999; letter-spacing:-0.8px; padding-top:20px; padding-bottom:20px;">
									한번의 프로젝트가 서로에게 믿음과 감동, 소통이 되는 곳<br />
									디지털 커뮤니케이션_이노박스의 올디 프로젝트 진행 과정을<br />
									포트폴리오에서 확인해보세요.
								</P>
								<p style="font-size:12px; color:#999;">
									"SMART, EASY SHOPPING"삼성정품 인증 디지털/IT 쇼핑몰<br />
									ALLD의 블루 컬러를 포인트로 사용하기 편안한 쇼핑몰이<br />
									완성되었습니다.
								</p>
								<a class="button white transparent" href="<?php echo G5_BBS_URL; ?>/board.php?bo_table=portfolio">SEE MORE PROJECT ITEM</a>
							</div><!-- caption -->
							<div class="intro-mockup intro-left column six" style="position:absolute; top:96px; left:0px;">
								<img src="/img/dell_i02.jpg" alt="" style="max-width:100% !important;">
							</div><!-- intro-mockup -->
						</div><!-- intro-mockup-wrapper -->
					</div>
				</div><!-- intro -->
			</div><!-- intro-wrap -->
			<div class="owl-pause">
				<a href="#"><img src="/theme/basic/img/pause.png"></a>
			</div>
			<script src="/js/plugins.js"></script>
			<script src="/js/beetle.js"></script>
	</div>

	<div id="quick_wrap">
		<div id="quick_menu">
			<div data-wow-delay="100ms" class="latest_menu first wow fadeInUp">
				<h2>NOTICE</h2>
				<p class="write_ico">
					<a href="<?php echo G5_BBS_URL ?>/write.php?bo_table=notice"><img src="<?php echo G5_IMG_URL ?>/write_ico.png" alt="글쓰기"/></a>
					<a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=notice"><img src="<?php echo G5_IMG_URL ?>/view_ico.png" alt="보기"/></a>
				</p>
				<?php echo latest('theme/basic', 'notice', 2, 16);?>
				<p class="more"><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=notice">more</a></p>
			</div>
			<div data-wow-delay="200ms" class="latest_menu wow fadeInUp">
				<h2>HOMEPAGE/MOBILE</h2>
				<p class="write_ico">
					<a href="<?php echo G5_BBS_URL ?>/write.php?bo_table=cscenter"><img src="<?php echo G5_IMG_URL ?>/write_ico.png" alt="글쓰기"/></a>
					<a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=cscenter"><img src="<?php echo G5_IMG_URL ?>/view_ico.png" alt="보기"/></a>
				</p>
				<?php echo latest('theme/basic', 'cscenter', 2, 16);?>
				<p class="more"><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=cscenter">more</a></p>
			</div>
			<div data-wow-delay="300ms" class="q_menu wow fadeInUp">
				<h2>CALL CENTER<span><a href="tel:010-2738-3653">010-2738-3653</a></span></h2>
				<p class="fixed"><a href="<?php echo G5_BBS_URL?>/board.php?bo_table=maintenance">유지보수신청하기</a></p>
				<p class="txt ssBoth">한번의 프로젝트가서로에게 믿음 감동, 소통이 되는곳 이노박스와 함께하세요.</p>
				<ul>
					<li>
						<a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=portfolio">
						<img src="<?php echo G5_IMG_URL ?>/qico_01.jpg" alt="포트폴리오" class="quick_ico"/>
						</a>
					</li>
					<li>
						<a href="<?php echo G5_BBS_URL ?>/content.php?co_id=shoppingmall">
						<img src="<?php echo G5_IMG_URL ?>/qico_02.jpg" alt="쇼핑몰" class="quick_ico"/>
						</a>
					</li>
					<li>
						<a href="<?php echo G5_BBS_URL ?>/content.php?co_id=homepage">
						<img src="<?php echo G5_IMG_URL ?>/qico_03.jpg" alt="홈페이지" class="quick_ico"/>
					</a>
					</li>
					<li class="ssBoth">
						<a href="<?php echo G5_BBS_URL ?>/content.php?co_id=mobile">
						<img src="<?php echo G5_IMG_URL ?>/qico_04.jpg" alt="모바일" class="quick_ico"/>
					</a>
					</li>
					<li>
						<a href="<?php echo G5_BBS_URL ?>/content.php?co_id=marketing">
						<img src="<?php echo G5_IMG_URL ?>/qico_05.jpg" alt="마케팅서비스" class="quick_ico"/>
					</a>
					</li>
					<li>
						<a href="<?php echo G5_BBS_URL ?>/content.php?co_id=maintenance">
						<img src="<?php echo G5_IMG_URL ?>/qico_06.jpg" alt="유지보수" class="quick_ico"/>
					</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div id="portfolio_wrap">
		<div id="portfolio">
		<h2 data-wow-delay="100ms" class="wow fadeInUp">PORTFOLIO</h2>
		<?php echo latest('main', 'portfolio', 100, 16);?>
		<p data-wow-delay="750ms" class="btn_pofol wow fadeInUp"><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=portfolio">포트폴리오 더보기<img src="<?php echo G5_IMG_URL ?>/btn_img01.png" alt="포트폴리오 더보기"/></a></p>
			<p data-wow-delay="800ms" class="btn_pay wow fadeInUp"><a href="<?php echo G5_BBS_URL ?>/write.php?bo_table=cscenter">견적 상담신청<img src="<?php echo G5_IMG_URL ?>/btn_img02.png" alt="견적상담신청"/></a></p>
		</div>
	</div>
	<script>
		$('.quick_ico').hover(function() {
			var img=$(this).attr('src').replace(/\.jpg/,'_on.jpg');
			$(this).attr('src', $(this).attr('src').replace(/\.jpg/,'_on.jpg'));

		},function(){

			var img=$(this).attr('src').replace(/\_on.jpg/,'.jpg');
				$(this).attr('src', img);
		}
			);

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
include_once(G5_THEME_PATH.'/tail.php');
?>