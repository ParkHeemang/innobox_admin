<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');
global $is_admin;

$n_cols = 4; // Gallery 타입 한 행 갯수
$n_thumb_width = 286; //썸네일 가로 크기
$n_thumb_height = 298; //썸네일 세로 크기

$board = sql_fetch(" select * from {$g5['board_table']} where bo_table = '$bo_table' ");
?>

<link rel="stylesheet" href="<?php echo $latest_skin_url; ?>/style.css">


<?php
$delay = 200;
if ($board['bo_use_category'] && $board['bo_category_list']) {
	echo '<ul data-wow-delay="'.$delay.'ms" class="catetab wow fadeInUp">';
	$ca_list = explode('|', $board['bo_category_list']);
	if (count($ca_list) > 1) {
		$delay = $delay + 20;
		echo '<li data-wow-delay="'.$delay.'ms" class="all "><a href="javascript:;">ALL</a></li>';
	}
	for ($i=0; $i<count($ca_list); $i++) {
		$delay = $delay + 20;
		echo '<li data-wow-delay="'.$delay.'ms" class="" data-type="'.$ca_list[$i].'"><a href="javascript:;">'.$ca_list[$i].'</a></li>';
	}
	echo '</ul>';
}
?>


<section class="n_gallery_wrap">
	<?php if (count($list) == 0) { //게시물이 없을 경우 ?>
		<div class="n_no_list">게시물이 없습니다.</div>
	<?php } else { //게시물이 있을 경우 ?>

		<ul class="n_thumb">
			<?php
				for ($i=0; $i<count($list); $i++) {
					$delay = $delay + 50;
					echo '<li data-wow-delay="'.$delay.'ms" class="wow fadeInUp grid-'.(($i % $n_cols) + 1).' '.$list[$i]['ca_name'].'" data-uid="'.$list[$i]['wr_id'].'">';
					echo '<a href="'.$list[$i]['href'].'">';

					$n_thumb = get_list_thumbnail($bo_table, $list[$i]['wr_id'], $n_thumb_width, $n_thumb_height);
					$n_noimg = "$latest_skin_url/img/noimg.gif";
					if($n_thumb['src']) {
						$img_content = '<img src="'.$n_thumb['src'].'" title="" />';
					} else {
						$img_content = '<img src="'.$n_noimg.'" width="'.$n_thumb_width.'" height="'.$n_thumb_height.'" alt="이미지없음" title="" />';
					}
					echo $img_content;

					echo '</a>';
					echo '</li>';
				}
			?>
		</ul>

	<?php } ?>
</section>

<script>
/*  */
$('ul.catetab li').on('click', function() {
	// 일단 모든 element 초기화
	$('ul.n_thumb li').removeClass(function (index, css) {
		return (css.match (/(^|\s)grid-\S+/g) || []).join(' ');
	}).css({'visibility':'visable', 'animation-name':'fadeInUp'});

	// show hide
	var type = $(this).data('type');
	if (type) {
		//$('ul.n_thumb li').fadeTo(500, .2);
		//$('ul.n_thumb li.'+type).fadeTo(300, 1);
		$('ul.n_thumb li').hide();
		$('ul.n_thumb li.'+type).show();
	} else {
		//$('ul.n_thumb li').fadeTo(300, 1);
		$('ul.n_thumb li').show();
	}

	// grid 재구성
	$('ul.n_thumb li:visible').each(function(i) {
		$(this).addClass('grid-'+((i%4)+1));
	});

});
</script>


<script>
	$(".catetab li").click(function() {
		$(this).parent().children().children('a').removeClass('on');
		$(this).children('a').addClass('on');
	});
	$(".catetab li").eq(0).trigger('click');
</script>
