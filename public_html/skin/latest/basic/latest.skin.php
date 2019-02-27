<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');
global $is_admin;



$n_thumb_width = 286; //썸네일 가로 크기
$n_thumb_height = 298; //썸네일 세로 크기

$board = sql_fetch(" select * from {$g5['board_table']} where bo_table = '$bo_table' ");
?>

<link rel="stylesheet" href="<?php echo $latest_skin_url; ?>/style.css">

<section class="n_gallery_wrap">
  <?php if (count($list) == 0) { //게시물이 없을 경우 ?>
  <div class="n_no_list">게시물이 없습니다.</div>
  <?php } else { //게시물이 있을 경우 ?>
 
  <ul class="n_thumb">
    <?php for ($i = 0; $i < count($list); $i++) { 
		if($i % $board['bo_gallery_cols'] == 0)
                $style = 'clear:both;';
	  else{
		$style = '';
	  }
	?>

    <li data-wow-delay="<?php echo (400+(50*$i)); ?>ms" class=" wow fadeInUp grid-<?php echo ($i+1); ?>" style="<?php echo $style;?>">
      <a href="<?php echo $list[$i]['href']; ?>">
      <?php
      $n_thumb = get_list_thumbnail($bo_table, $list[$i]['wr_id'], $n_thumb_width, $n_thumb_height);
      $n_noimg = "$latest_skin_url/img/noimg.gif";
      if($n_thumb['src']) {
          $img_content = '<img src="'.$n_thumb['src'].'" width="'.$n_thumb_width.'" height="'.$n_thumb_height.'" title="" />';
      } else {
	      $img_content = '<img src="'.$n_noimg.'" width="'.$n_thumb_width.'" height="'.$n_thumb_height.'" alt="이미지없음" title="" />';
      }
      echo $img_content;
      ?>
      </a>
	 
	<p class="gall_text_href"><a href="<?php echo $list[$i]['href']; ?>"><?php echo $list[$i]['subject']; ?></a></p>
	<p class="sut_title"><?php echo $list[$i]['wr_1'] ?></p>
	<p class="price"><span><?php echo $list[$i]['wr_2'] ?></span></p>
    </li>
    <?php } ?>
  </ul>

  <?php } ?>
</section>
