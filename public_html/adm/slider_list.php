<?php
$sub_menu = "100311";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');


$g5['title'] = '메인슬라이더관리';
include_once('./admin.head.php');
/* 1:plugin, 2:theme, 3:skin, 4:closer, 0:external */
//add_stylesheet('<link rel="stylesheet" href="">', 4);
//add_javascript('<script src=""></script>', 4);

// 자료 날짜 min,max
$tss = sql_fetch("SELECT LEFT(MIN(sl_datetime),10) AS min, LEFT(max(sl_datetime),10) AS max FROM {$g5['slider_table']}");
if (empty($tss['min'])) $tss['min'] = date('Y-m-d');
if (empty($tss['max'])) $tss['max'] = date('Y-m-d');
$tss['min'] = explode("-", $tss['min']);
$tss['max'] = explode("-", $tss['max']);

// 날짜 검색 Date Range JS Date
add_javascript('<script>var sDate = new Date('.intval($tss['min'][0]).','.intval($tss['min'][1]).'-1,'.intval($tss['min'][2]).'), eDate = new Date('.intval($tss['max'][0]).','.intval($tss['max'][1]).'-1,'.intval($tss['max'][2]).');</script>', 0);


// sql_common
$sql_common = " from {$g5['slider_table']} ";

// sql_search
$sql_search = " where (1) ";
if ($prt == '0' || $prt == '1') {
    $sql_search .= " and sl_print = '".$prt."'";
}
if ($dev) {
    $sql_search .= " and sl_device = '".$dev."'";
}
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'sl_id' :
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
    $sst = "sl_id";
    $sod = "desc";
} 

if (!$sst) {
    $sql_order = " order by sl_device, sl_order, {$sst} {$sod} ";
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
$colspan = 12; // 테이블 column 수
$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>'; // 전체목록

// query string 재정의
$qstr = 'prt='.$prt.'&amp;'.'dev='.$dev.'&amp;'.$qstr;


//CSS
add_stylesheet('<link rel="stylesheet" href="'.G5_ADMIN_URL. '/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 4);
//JS DataTables
add_javascript('<script src="'.G5_ADMIN_URL.'/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_ADMIN_URL.'/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);

?>



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper slider_list">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
		<h1 class="member_list_title">
		메인슬라이더관리
		<small>slider_list</small>
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
						<nav class="navbar navbar-default navbtn">
							<div class="text-right">							
								<input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn btn_02 btn btn-default">
								<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02 btn btn-default">
								<a href="./slider_form.php" id="slider_add" class="btn btn-danger">추가</a>	
							</div>
						</nav>
					</div>
					<!-- /.box-header -->
					<div class="box-body">			
						
							<div class="row non-margin">
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
						

						<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">
							<!-- <label for="prt" class="sound_only">노출여부</label> -->
							<select name="prt" id="prt" class="form-control">
							<option value="">노출여부</option>
							<option value="1" <?php echo get_selected($_GET['prt'], '1'); ?>>노출</option>
							<option value="0" <?php echo get_selected($_GET['prt'], '0'); ?>>숨김</option>
							</select>
							<!-- <label for="dev" class="sound_only">접속기기</label> -->
							<select name="dev" id="dev" class="form-control">
							<option value="">접속기기</option>
							<option value="pc" <?php echo get_selected($_GET['dev'], 'pc'); ?>>PC</option>
							<option value="mobile" <?php echo get_selected($_GET['dev'], 'mobile'); ?>>모바일</option>
							<!-- <option value="both" <?php echo get_selected($_GET['dev'], 'both'); ?>>PC와 모바일</option> -->
							</select>
							&nbsp;
							<!-- <label for="sfl" class="sound_only">검색대상</label> -->
							<select name="sfl" id="sfl" class="form-control">
							<option value="sl_alt"<?php echo get_selected($_GET['sfl'], "sl_alt"); ?>>설명</option>
							</select>
							<!-- <label for="stx" class="sound_only">검색어</label> -->
							<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input form-control">
							<input type="submit" class="btn_submit btn btn-default" value="검색">
						</form>

						<form name="fsliderlist" id="fsliderlist" action="./slider_list_update.php" onsubmit="return fsliderlist_submit(this);" method="post">
							<input type="hidden" name="prt" value="<?php echo $prt ?>">
							<input type="hidden" name="dev" value="<?php echo $dev ?>">
							<input type="hidden" name="sst" value="<?php echo $sst ?>">
							<input type="hidden" name="sod" value="<?php echo $sod ?>">
							<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
							<input type="hidden" name="stx" value="<?php echo $stx ?>">
							<input type="hidden" name="page" value="<?php echo $page ?>">
							<input type="hidden" name="pagenum" value="<?php echo $pagenum ?>">
							<input type="hidden" name="token" value="">

						
							<table id="example1" class="table table-bordered table-striped dataTable member_list" width="100%">
								<thead>
									<tr>
										<th scope="col" id="sl_list_chk">
											<!-- <label for="chkall" class="sound_only"><?php echo $g5['title']; ?> 전체</label> -->
											<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
										</th>
										<th scope="col"><?php echo subject_sort_link('sl_id'); ?>ID</a></th>
										<th scope="col"><?php echo subject_sort_link('sl_print'); ?>노출</a></th>
										<th scope="col"><?php echo subject_sort_link('sl_device'); ?>접속기기</a></th>
										<th scope="col"><?php echo subject_sort_link('sl_order'); ?>순번</a></th>
										<th scope="col">파일명</th>
										<th scope="col">사이즈</th>
										<th scope="col">설명</th>
										<th scope="col" id="th_st"><?php echo subject_sort_link('sl_begin_time'); ?>시작</a></th>
										<th scope="col" id="th_end"><?php echo subject_sort_link('sl_end_time'); ?>종료</a></th>
										<th scope="col">이미지</th>
										<th scope="col">관리</th>
									</tr>
								</thead>
								<tbody>
									<?php
										for ($i=0; $row=sql_fetch_array($result); $i++) {
											$bg = 'bg'.($i%2);

											// 테두리 있는지
											$sl_border  = $row['sl_border'];
											// 새창 띄우기인지
											$sl_new_win = ($row['sl_new_win']) ? 'target="_blank"' : '';

											$w = 0;
											$h = 0;
											$bimg = G5_DATA_PATH.'/slider/'.$row['sl_img'];
											if(file_exists($bimg)) {
												$size = @getimagesize($bimg);
												if($size[0] && $size[0] > 800)
													$width = 800;
												else
													$width = $size[0];

												$sl_img = "";
												if ($row['sl_url'] && $row['sl_url'] != "http://")
													$sl_img .= '<a href="'.$row['sl_url'].'" '.$sl_new_win.'>';
												$sl_img .= '<img src="'.G5_DATA_URL.'/slider/'.$row['sl_img'].'" width="'.$width.'" alt="'.$row['sl_alt'].'"></a>';
												$w = $size[0];
												$h = $size[1];
											}

											switch($row['sl_device']) {
												case 'pc':
													$sl_device = 'PC';
													break;
												case 'mobile':
													$sl_device = '모바일';
													break;
												default:
													$sl_device = 'PC와 모바일';
													break;
											}

											$sl_begin_time = substr($row['sl_begin_time'], 2, 14);
											$sl_end_time   = substr($row['sl_end_time'], 2, 14);

											$off = ( (substr($row['sl_begin_time'], 0, 4) != '0000' && $row['sl_begin_time'] <= date('Y-m-d H:i:s')) && (substr($row['sl_end_time'], 0, 4) != '0000' && $row['sl_end_time'] >= date('Y-m-d H:i:s'))) ? '' : '_off';

											$sl_begin_time = isset($row['sl_begin_time']) && substr($row['sl_begin_time'], 0, 4) != '0000' ? '<img src="./img/cal'.$off.'.png" title="'.$row['sl_begin_time'].'" />' : '';
											$sl_end_time   = isset($row['sl_end_time']) && substr($row['sl_end_time'], 0, 4) != '0000' ? '<img src="./img/cal'.$off.'.png" title="'.$row['sl_end_time'].'" />' : '';

											$sl_print = empty($row['sl_print']) ? '<span class="ssRed">숨김</span>' : '노출';

											echo '<tr class="'.$bg.'" data-bunch="'.$i.'">';

											// checkbox
											echo '<td headers="sl_list_chk" class="td_chk">';
											echo '<input type="hidden" name="sl_id['.$i.']" value="'.$row['sl_id'].'" id="sl_id_'.$i.'">';
										 //   echo '<label for="chk_'.$i.'" class="sound_only">'.get_text($row['sl_alt']).'</label>';
											echo '<input type="checkbox" name="chk[]" value="'.$i.'" id="chk_'.$i.'">';
											echo '</td>';

											echo '<td class="td_num">'.$row['sl_id'].'</td>';
											echo '<td>'.$sl_print.'</td>';
											echo '<td>'.$sl_device.'</td>';
											echo '<td class="td_num"><input type="text" name="sl_order['.$i.']" class="frm_input form-control" value="'.$row['sl_order'].'" data-proto="'.$row['sl_order'].'" size="3"></td>';
											echo '<td>'.$row['sl_img'].'</td>';
											echo '<td>'.$w.' × '.$h.'</td>';
											echo '<td>'.get_text($row['sl_alt']).'</td>';
											echo '<td headers="th_st" class="td_chk">'.$sl_begin_time.'</td>';
											echo '<td headers="th_end" class="td_chk">'.$sl_end_time.'</td>';
											echo '<td><button type="button" class="ssl_img_view btn_frmline btn btn-default">이미지확인</button></td>';
											echo '<td class="td_mng td_mng_m">';
											echo '<a href="./slider_form.php?w=u&amp;sl_id='.$row['sl_id'].'&amp;'.$qstr.'" class="btn btn_03 btn-success">수정</a> ';
											echo '<a href="./slider_form_update.php?w=d&amp;sl_id='.$row['sl_id'].'&amp;'.$qstr.'" class="btn btn_02 btn-danger" onclick="return delete_confirm(this);">삭제</a> ';
											echo '</td>';
											echo '</tr>';

											echo '<tr class="'.$bg.'">';
											echo '<td headers="th_img" colspan="'.$colspan.'" class="td_img_view ssl_img">';
											echo '<div class="ssl_image">'.$sl_img.'</div>';
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

						<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

						<script>
						$(function() {
						$(".ssl_img_view").on("click", function() {
							$(this).closest("tr").next().toggle();
							//$(this).closest("tr").next().slideToggle(); // .ssl_img .ssl_image
						}).trigger('click');
						});
						</script>

						<script>
						function fsliderlist_submit(f)
						{
						if (!is_checked("chk[]")) {
							alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
							return false;
						}

						if(document.pressed == "선택삭제") {
							if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
								return false;
							}
						}

						return true;
						}
						function fsliderlist_export(f)
						{
						location.href = './slider_list_export.php?alt=xls&' + $('#fsearch').serialize();
						}
						</script>
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