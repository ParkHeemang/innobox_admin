<?php
$sub_menu = '100312';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '배너관리';
include_once('./admin.head.php');
/* 1:plugin, 2:theme, 3:skin, 4:closer, 0:external */
//add_stylesheet('<link rel="stylesheet" href="">', 4);
//add_javascript('<script src=""></script>', 4);

// 자료 날짜 min,max
$tss = sql_fetch("SELECT LEFT(MIN(ba_datetime),10) AS min, LEFT(max(ba_datetime),10) AS max FROM {$g5['banner_table']}");
if (empty($tss['min'])) $tss['min'] = date('Y-m-d');
if (empty($tss['max'])) $tss['max'] = date('Y-m-d');
$tss['min'] = explode("-", $tss['min']);
$tss['max'] = explode("-", $tss['max']);

// 날짜 검색 Date Range JS Date
add_javascript('<script>var sDate = new Date('.intval($tss['min'][0]).','.intval($tss['min'][1]).'-1,'.intval($tss['min'][2]).'), eDate = new Date('.intval($tss['max'][0]).','.intval($tss['max'][1]).'-1,'.intval($tss['max'][2]).');</script>', 0);

// sql_common
$sql_common = " from {$g5['banner_table']} ";

// sql_search
$sql_search = " where (1) ";
if ($prt == '0' || $prt == '1') {
    $sql_search .= " and ba_print = '".$prt."'";
}
if ($dev) {
    $sql_search .= " and ba_device = '".$dev."'";
}
if ($pos) {
    $sql_search .= " and ba_position = '".$pos."'";
}
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'ba_id' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

// sql_order
if (!$sst) {
    $sst = "ba_id";
    $sod = "desc";
}

if (!$sst) {
    $sql_order = " order by ba_device, ba_order, {$sst} {$sod} ";
} else {
    $sql_order = " order by $sst $sod ";
}

// paging
$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = intval($row['cnt']); // 전체 수
$rows = $_GET['pagenum'] ? $_GET['pagenum'] : $config['cf_page_rows']; // 페이지당 목록 수
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

// query
$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

// etc
$colspan = 14; // 테이블 column 수
$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>'; // 전체목록

// query string 재정의
$qstr = 'prt='.$prt.'&amp;'.'dev='.$dev.'&amp;'.'pos='.$pos.'&amp;'.$qstr;


//CSS
add_stylesheet('<link rel="stylesheet" href="'.G5_ADMIN_URL. '/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 4);
//JS DataTables
add_javascript('<script src="'.G5_ADMIN_URL.'/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_ADMIN_URL.'/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper banner_list">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
		<h1 class="member_list_title">
		배너관리
		<small>banner list</small>
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
				<div class="box">
					<div class="box-header">
						<div class="text-right">
							<nav class="navbar navbar-default navbtn">
								<input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn btn_02 btn-default">
								<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02 btn-default">
								<a href="./slider_form.php" id="slider_add" class="btn btn_01 btn-danger">추가</a>
							</nav>
						</div>
					</div>
					<!-- /.box-header -->
				<div class="box-body">	
					<div class="local_ov01 local_ov">
						<div class="row">
							<div class="col text-left">
								<?php echo $listall ?>
								<span class="btn_ov01">
									<span class="ov_txt">전체</span>
									<span class="ov_num"> <?php echo $total_count; ?>건</span>
								</span>
							</div>
							<div class="col text-right">
								<!-- <input type="button" class="btn btn-info" value="검색된 결과 엑셀 다운로드" onclick="fsliderlist_export(this.form);"> -->
							</div>
						</div>
					</div>

					<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
						<!-- <label for="prt" class="sound_only">노출여부</label> -->
						<select name="prt" id="prt" class="form-control">
							<option value="">노출여부</option>
							<option value="1" <?php echo get_selected($_GET['prt'], '1'); ?>>노출</option>
							<option value="0" <?php echo get_selected($_GET['prt'], '0'); ?>>숨김</option>
						</select>
						<!-- <label for="pos" class="sound_only">위치</label> -->
						<select name="dev" id="dev" class="form-control">
							<option value="">접속기기</option>
							<option value="pc" <?php echo get_selected($_GET['dev'], 'pc'); ?>>PC</option>
							<option value="mobile" <?php echo get_selected($_GET['dev'], 'mobile'); ?>>모바일</option>
							<option value="both" <?php echo get_selected($_GET['dev'], 'both'); ?>>PC와 모바일</option>
						</select>
						<select name="pos" id="pos" class="form-control">
							<option value="">위치</option>
							<?php
								$foo = "select ba_position from {$g5['banner_table']} group by ba_position order by ba_position";
								$bar = sql_query($foo);
								for ($i=0; $tmp=sql_fetch_array($bar); $i++) {
									echo '<option value="'.$tmp['ba_position'].'" '.get_selected($tmp['ba_position'], $_GET['pos']).'>'.$tmp['ba_position'].'</option>';
								}
							?>

							<!-- 
							<option value="메인" <?php echo get_selected($_GET['pos'], '메인'); ?>>메인</option>
							<option value="상단" <?php echo get_selected($_GET['pos'], '상단'); ?>>상단</option>
							<option value="하단" <?php echo get_selected($_GET['pos'], '하단'); ?>>하단</option>
							<option value="허브" <?php echo get_selected($_GET['pos'], '허브'); ?>>허브</option>
							<option value="메뉴" <?php echo get_selected($_GET['pos'], '메뉴'); ?>>메뉴</option>
							<option value="스크롤배너L" <?php echo get_selected($_GET['pos'], '스크롤배너L'); ?>>스크롤배너L</option>
							<option value="스크롤배너R" <?php echo get_selected($_GET['pos'], '스크롤배너R'); ?>>스크롤배너R</option>
							<option value="상품상세" <?php echo get_selected($_GET['pos'], '상품상세'); ?>>상품상세</option> -->
						</select>
						&nbsp;
						<!-- <label for="sfl" class="sound_only">검색대상</label> -->
						<select name="sfl" id="sfl" class="form-control">
							<option value="ba_alt"<?php echo get_selected($_GET['sfl'], "ba_alt"); ?>>설명</option>
						</select>
						<!-- <label for="stx" class="sound_only">검색어</label> -->
						<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input form-control">
						<input type="submit" class="btn_submit btn btn-default" value="검색">
						<!-- <input type="button" class="btn_button ssBgGreen ssWhite ssFloatR" value="검색된 결과 엑셀 다운로드" onclick="fbannerlist_export(this.form);"> -->
					</form>

					<form name="fbannerlist" id="fbannerlist" action="./banner_list_update.php" onsubmit="return fbannerlist_submit(this);" method="post">
						<input type="hidden" name="prt" value="<?php echo $prt ?>">
						<input type="hidden" name="dev" value="<?php echo $dev ?>">
						<input type="hidden" name="pos" value="<?php echo $pos ?>">
						<input type="hidden" name="sst" value="<?php echo $sst ?>">
						<input type="hidden" name="sod" value="<?php echo $sod ?>">
						<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
						<input type="hidden" name="stx" value="<?php echo $stx ?>">
						<input type="hidden" name="page" value="<?php echo $page ?>">
						<input type="hidden" name="pagenum" value="<?php echo $pagenum ?>">
						<input type="hidden" name="token" value="">

						<table id="example1" class="table table-bordered table-striped dataTable member_list">
							<!-- <caption><?php echo $g5['title']; ?> 목록</caption> -->
							<thead>
								<tr>
									<th scope="col" id="ba_list_chk">
										<!-- <label for="chkall" class="sound_only"><?php echo $g5['title']; ?> 전체</label> -->
										<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
									</th>

									<th scope="col" id="th_id"><?php echo subject_sort_link('ba_id'); ?>ID</a></th>
									<th scope="col" id="th_prt"><?php echo subject_sort_link('ba_print'); ?>노출</a></th>
									<th scope="col" id="th_dvc"><?php echo subject_sort_link('ba_device'); ?>접속기기</a></th>
									<th scope="col" id="th_loc"><?php echo subject_sort_link('ba_position'); ?>위치</a></th>
									<th scope="col" id="th_odr"><?php echo subject_sort_link('ba_order'); ?>출력순서</a></th>
									<th scope="col" id="th_ifn">파일명</th>
									<th scope="col" id="th_ifs">사이즈(px)</th>
									<th scope="col" id="th_alt">설명</th>
									<th scope="col" id="th_st"><?php echo subject_sort_link('ba_begin_time'); ?>시작</a></th>
									<th scope="col" id="th_end"><?php echo subject_sort_link('ba_end_time'); ?>종료</a></th>
									<th scope="col" id="th_hit">조회</th>
									<th scope="col" id="th_img">이미지확인</th>
									<th scope="col" id="th_mng">관리</th>
								</tr>
							</thead>
							<tbody>
								<?php
									for ($i=0; $row=sql_fetch_array($result); $i++) {
										// 테두리 있는지
										$ba_border  = $row['ba_border'];
										// 새창 띄우기인지
										$ba_new_win = ($row['ba_new_win']) ? 'target="_blank"' : '';

										$w = 0;
										$h = 0;
										$bimg = G5_DATA_PATH.'/banner/'.$row['ba_img'];
										if(file_exists($bimg)) {
											$size = @getimagesize($bimg);
											if($size[0] && $size[0] > 800)
												$width = 800;
											else
												$width = $size[0];

											$ba_img = "";
											if ($row['ba_url'] && $row['ba_url'] != "http://")
												$ba_img .= '<a href="'.$row['ba_url'].'" '.$ba_new_win.'>';
											$ba_img .= '<img src="'.G5_DATA_URL.'/banner/'.$row['ba_img'].'" width="'.$width.'" alt="'.$row['ba_alt'].'"></a>';

											$w = $size[0];
											$h = $size[1];
										}

										switch($row['ba_device']) {
											case 'pc':
												$ba_device = 'PC';
												break;
											case 'mobile':
												$ba_device = '모바일';
												break;
											default:
												$ba_device = 'PC와 모바일';
												break;
										}

										$ba_begin_time = substr($row['ba_begin_time'], 2, 14);
										$ba_end_time   = substr($row['ba_end_time'], 2, 14);

										$off = ( (substr($row['ba_begin_time'], 0, 4) != '0000' && $row['ba_begin_time'] <= date('Y-m-d H:i:s')) && (substr($row['ba_end_time'], 0, 4) != '0000' && $row['ba_end_time'] >= date('Y-m-d H:i:s'))) ? '' : '_off';

										$ba_begin_time = isset($row['ba_begin_time']) && substr($row['ba_begin_time'], 0, 4) != '0000' ? '<img src="./img/cal'.$off.'.png" title="'.$row['ba_begin_time'].'" />' : '';
										$ba_end_time   = isset($row['ba_end_time']) && substr($row['ba_end_time'], 0, 4) != '0000' ? '<img src="./img/cal'.$off.'.png" title="'.$row['ba_end_time'].'" />' : '';

										$ba_print = empty($row['ba_print']) ? '<span class="ssRed">숨김</span>' : '노출';

										$bg = 'bg'.($i%2);

										echo '<tr class="'.$bg.'" data-bunch="'.$i.'">';
										
										// checkbox
										echo '<td headers="ba_list_chk" class="td_chk">';
										echo '<input type="hidden" name="ba_id['.$i.']" value="'.$row['ba_id'].'" id="ba_id_'.$i.'">';
										//echo '<label for="chk_'.$i.'" class="sound_only">'.get_text($row['ba_alt']).'</label>';
										echo '<input type="checkbox" name="chk[]" value="'.$i.'" id="chk_'.$i.'">';
										echo '</td>';

										echo '<td headers="th_id" class="td_num">'.$row['ba_id'].'</td>';
										echo '<td headers="th_prt">'.$ba_print.'</td>';
										echo '<td headers="th_dvc">'.$ba_device.'</td>';
										echo '<td headers="th_loc">'.$row['ba_position'].'</td>';
										echo '<td headers="th_odr" class="td_num"><input type="text" name="ba_order['.$i.']" class="frm_input form-control" value="'.$row['ba_order'].'" data-proto="'.$row['ba_order'].'" size="3"></td>';
										echo '<td headers="th_ifn">'.$row['ba_img'].'</td>';
										echo '<td headers="th_ifs">'.(empty($row['ba_img']) ? '' : $w.' × '.$h).'</td>';
										echo '<td headers="th_loc">'.$row['ba_alt'].'</td>';
										echo '<td headers="th_st" class="td_chk">'.$ba_begin_time.'</td>';
										echo '<td headers="th_end" class="td_chk">'.$ba_end_time.'</td>';
										echo '<td headers="th_hit" class="td_num">'.$row['ba_hit'].'</td>';
										echo '<td headers="th_img"><button type="button" class="sba_img_view btn_frmline btn btn-default">이미지확인</button></td>';
										echo '<td headers="th_mng" class="td_mng td_mng_m">';
										echo '<a href="./banner_form.php?w=u&amp;ba_id='.$row['ba_id'].'&amp;'.$qstr.'" class="btn btn_03 btn-success">수정</a> ';
										echo '<a href="./banner_form_update.php?w=d&amp;ba_id='.$row['ba_id'].'" class="btn btn_02 btn-danger" onclick="return delete_confirm(this);">삭제</a> ';
										echo '</td>';
										echo '</tr>';

										echo '<tr class="'.$bg.'">';
										echo '<td headers="th_img" colspan="'.$colspan.'" class="td_img_view sba_img">';
										echo '<div class="sba_image">'.$ba_img.'</div>';
										echo '</td>';
										echo '</tr>';
									}

									if ($i == 0) {
										echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
									}
								?>
							</tbody>
						</table>						

					</form>
				</div>
				<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

<script>
$(function() {
    $(".sba_img_view").on("click", function() {
        $(this).closest("tr").next().toggle();
        //$(this).closest("tr").next().slideToggle(); // .sba_img .sba_image
    }).trigger('click');
});
</script>

<script>
function fbannerlist_submit(f) 
{
}
function fbannerlist_export(f) 
{
    location.href = './banner_list_export.php?alt=xls&' + $(f).serialize();
}
</script>

<script>
$(document).scroll(function(e){
    var scrollTop = $(document).scrollTop();
    if(scrollTop > 0){
        console.log(scrollTop);
        $('.navbar').removeClass('navbar-static-top').addClass('navbar-fixed-top');
    } else {
        $('.navbar').removeClass('navbar-fixed-top').addClass('navbar-static-top');
    }
});
 $(function () {
    // Korean
    var lang_kor = {
        "decimal" : "",
        "emptyTable" : "데이터가 없습니다.",
        "info" : "_START_ - _END_ (총 _TOTAL_ 개)",
        "infoEmpty" : "0명",
        "infoFiltered" : "(전체 _MAX_ 개 중 검색결과)",
        "infoPostFix" : "",
        "thousands" : ",",
        "lengthMenu" : "_MENU_ 개",
        "loadingRecords" : "로딩중...",
        "processing" : "처리중...",
        "search" : "검색:",
        "zeroRecords" : "검색된 데이터가 없습니다.",
        "paginate" : {
            "first" : "첫 페이지",
            "last" : "마지막 페이지",
            "next" : "다음",
            "previous" : "이전"
        },
        "aria" : {
            "sortAscending" : " :  오름차순 정렬",
            "sortDescending" : " :  내림차순 정렬"
        }
    };


    var table = $('#example1').DataTable({ 
   
	  'scrollX': true,	 
	  'paging'      : false,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : false,
	  'order': [  [2, 'asc']],
      'info'        : true,
	  'language' : lang_kor,
      'autoWidth'   : true,
	  'columnDefs': [{
            'bSortable': false,
            'aTargets': [1]
        }]

    });

  })
</script> 


<?php
include_once ('./admin.tail.php');
?>
