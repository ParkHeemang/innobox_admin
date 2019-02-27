<?php
$sub_menu = '400660';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '상품문의';
include_once ('../admin.head.php');

$where = " where ";
$sql_search = "";
if ($stx != "") {
    if ($sfl != "") {
        $sql_search .= " $where $sfl like '%$stx%' ";
        $where = " and ";
    }
    if ($save_stx != $stx)
        $page = 1;
}

if ($sca != "") {
    $sql_search .= " and ca_id like '$sca%' ";
}

if ($sfl == "")  $sfl = "it_name";
if (!$sst) {
    $sst = "iq_id";
    $sod = "desc";
}

$sql_common = "  from {$g5['g5_shop_item_qa_table']} a
                 left join {$g5['g5_shop_item_table']} b on (a.it_id = b.it_id)
                 left join {$g5['member_table']} c on (a.mb_id = c.mb_id) ";
$sql_common .= $sql_search;

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql  = " select *
          $sql_common
          order by $sst $sod, iq_id desc
          limit $from_record, $rows ";
$result = sql_query($sql);

//$qstr = 'page='.$page.'&amp;sst='.$sst.'&amp;sod='.$sod.'&amp;stx='.$stx;
$qstr .= ($qstr ? '&amp;' : '').'sca='.$sca.'&amp;save_stx='.$stx;

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';





//CSS

add_stylesheet('<link rel="stylesheet" href="'.G5_PHP_URL. 'bower_components/bootstrap/dist/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 5);
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL2.'/bootstrap.custom.css?ver='.G5_CSS_VER.'"></script>',8);

//JS DataTables
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
	  <h1 class="member_list_title">
		상품문의
		<small>itemqalist</small>
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
						<nav class="navbar navbar-default navbtn" style="z-index: 1000000000000000000000000000000000">
							<div class="text-right">							
								<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn-danger btn_02">						
							</div>
						</nav>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="local_ov01 local_ov">
							<?php echo $listall; ?>
							<span class="btn_ov01"><span class="ov_txt"> 전체 문의내역</span><span class="ov_num"> <?php echo $total_count; ?>건</span></span>
						</div>

						<form name="flist" class="local_sch01 local_sch">
						<input type="hidden" name="page" value="<?php echo $page; ?>">
						<input type="hidden" name="save_stx" value="<?php echo $stx; ?>">

						<!-- <label for="sca" class="sound_only">분류선택</label> -->
						<select name="sca" id="sca" class="form-control">
							<option value="">전체분류</option>
							<?php
							$sql1 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} order by ca_order, ca_id ";
							$result1 = sql_query($sql1);
							for ($i=0; $row1=sql_fetch_array($result1); $i++) {
								$len = strlen($row1['ca_id']) / 2 - 1;
								$nbsp = "";
								for ($i=0; $i<$len; $i++) $nbsp .= "&nbsp;&nbsp;&nbsp;";
								$selected = ($row1['ca_id'] == $sca) ? ' selected="selected"' : '';
								echo '<option value="'.$row1['ca_id'].'"'.$selected.'>'.$nbsp.$row1['ca_name'].'</option>'.PHP_EOL;
							}
							?>
						</select>

						<!-- <label for="sfl" class="sound_only">검색대상</label> -->
						<select name="sfl" id="sfl" class="form-control">
							<option value="it_name" <?php echo get_selected($sfl, 'it_name'); ?>>상품명</option>
							<option value="a.it_id" <?php echo get_selected($sfl, 'a.it_id'); ?>>상품코드</option>
						</select>

						<!-- <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label> -->
						<input type="text" name="stx" value="<?php echo $stx; ?>" id="stx" required class="frm_input required form-control">
						<input type="submit" value="검색" class="btn_submit btn btn-default">

						</form>

						<form name="fitemqalist" method="post" action="./itemqalistupdate.php" onsubmit="return fitemqalist_submit(this);" autocomplete="off">
						<input type="hidden" name="sca" value="<?php echo $sca; ?>">
						<input type="hidden" name="sst" value="<?php echo $sst; ?>">
						<input type="hidden" name="sod" value="<?php echo $sod; ?>">
						<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
						<input type="hidden" name="stx" value="<?php echo $stx; ?>">
						<input type="hidden" name="page" value="<?php echo $page; ?>">

						<div class="tbl_head01 tbl_wrap" id="itemqalist">
							<table id="example1" class="table table-bordered table-striped dataTable member_list">
							<caption><?php echo $g5['title']; ?> 목록</caption>
							<thead>
							<tr>
								<th scope="col">
									<!-- <label for="chkall" class="sound_only">상품문의 전체</label> -->
									<input type="checkbox" name="chkall" value="1" id="example-select-all" onclick="check_all(this.form)">
								</th>
								<th scope="col"><?php echo subject_sort_link('it_name'); ?>상품명</a></th>
								<th scope="col"><?php echo subject_sort_link('iq_subject'); ?>질문</a></th>
								<th scope="col"><?php echo subject_sort_link('mb_name'); ?>이름</a></th>
								<th scope="col"><?php echo subject_sort_link('iq_answer'); ?>답변</a></th>
								<th scope="col">관리</th>
							</tr>
							</thead>
							<tbody>
							<?php
							for ($i=0; $row=sql_fetch_array($result); $i++) {
								$row['iq_subject'] = cut_str($row['iq_subject'], 30, "...");
								$href = G5_SHOP_URL.'/item.php?it_id='.$row['it_id'];
								$name = get_sideview($row['mb_id'], get_text($row['iq_name']), $row['mb_email'], $row['mb_homepage']);
								$answer = $row['iq_answer'] ? 'Y' : '&nbsp;';
								$iq_question = get_view_thumbnail(conv_content($row['iq_question'], 1), 300);
								$iq_answer = $row['iq_answer'] ? get_view_thumbnail(conv_content($row['iq_answer'], 1), 300) : "답변이 등록되지 않았습니다.";

								$bg = 'bg'.($i%2);
							?>
							<tr class="<?php echo $bg; ?>">
								<td class="td_chk">
									<!-- <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['iq_subject']) ?> 상품문의</label> -->
									<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i; ?>">
									<input type="hidden" name="iq_id[<?php echo $i; ?>]" value="<?php echo $row['iq_id']; ?>">
								</td>
								<td class="td_left td_odrnum"><a href="<?php echo $href; ?>"><?php echo cut_str($row['it_name'],30); ?></a></td>
								<td class="td_left">
									<a href="#" class="qa_href" onclick="return false;" target="<?php echo $i; ?>"><?php echo get_text($row['iq_subject']); ?> <span class="tit_op">열기</span></a>
								</td>
								<td class="td_name"><?php echo $name; ?></td>
								<td class="td_boolean"><?php echo $answer; ?></td>
								<td class="td_mng td_mng_s">
									<a href="./itemqaform.php?w=u&amp;iq_id=<?php echo $row['iq_id']; ?>&amp;<?php echo $qstr; ?>" class="btn btn_03"><span class="sound_only"><?php echo get_text($row['iq_subject']); ?> </span>수정</a>
								</td>
							</tr>
							<tr id="qa_div<?php echo $i; ?>" class="qa_div" style="display:none;">
								<td></td>
								<td style="text-align:left;vertical-align:top;padding:1rem;"><?php echo get_it_image($row['it_id'], 100, 100, true, '', '', true); ?></td>
								<td style="text-align:left;vertical-align:top;padding:1rem;" colspan="5">
									<div class="qa_q">
										<strong>문의내용</strong>
										<p><?php echo $row['iq_time']; ?></p>
										<?php echo $iq_question; ?>
									</div>
									<div class="qa_a">
										<strong>답변</strong>
										<?php echo $iq_answer; ?>
									</div>
								</td>
							</tr>

							<?php
							}
							if ($i == 0) {
								echo '<tr><td colspan="6" class="empty_table"><span>자료가 없습니다.</span></td></tr>';
							}
							?>
							</tbody>
							</table>
						</div>
						</form>

						<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
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
function fitemqalist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed  == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}

$(function(){
    $(".qa_href").click(function(){
        var $content = $("#qa_div"+$(this).attr("target"));
        $(".qa_div").each(function(index, value){
            if ($(this).get(0) == $content.get(0)) { // 객체의 비교시 .get(0) 를 사용한다.
                $(this).is(":hidden") ? $(this).show() : $(this).hide();
            } else {
                $(this).hide();
            }
        });
    });
});


$(function () {

	 

    // Korean
    var lang_kor = {
        "decimal" : "",
        "emptyTable" : "데이터가 없습니다.",
        "info" : "_START_ - _END_ (총 _TOTAL_ 명)",
        "infoEmpty" : "0명",
        "infoFiltered" : "(전체 _MAX_ 명 중 검색결과)",
        "infoPostFix" : "",
        "thousands" : ",",
        "lengthMenu" : "_MENU_ 개",
        "loadingRecords" : "로딩중...",
        "processing" : "처리중...",
        "search" : "검색 : ",
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
      'paging'      : true,
      'lengthChange': true,
      'searching'   : false,
      'ordering'    : false,
	  'info'        : true,
	  'language' : lang_kor
	  

	
	//$('#division1').removeClass('sorting sorting_asc sorting_desc');
		  
	  
	  
	 // [{ 'targets': [0,5,10,11], 'orderable': false }, 	  ]



    });




	// Handle click on "Select all" control
	$('#example-select-all').on('click', function(){
	   // Get all rows with search applied
	   var rows = table.rows({ 'search': 'applied' }).nodes();
	   // Check/uncheck checkboxes for all rows in the table
	   $('input[type="checkbox"]', rows).prop('checked', this.checked);
	});

	// Handle click on checkbox to set state of "Select all" control
	$('#example tbody').on('change', 'input[type="checkbox"]', function(){
	   // If checkbox is not checked
	   if(!this.checked){
		  var el = $('#example-select-all').get(0);
		  // If "Select all" control is checked and has 'indeterminate' property
		  if(el && el.checked && ('indeterminate' in el)){
			 // Set visual state of "Select all" control
			 // as 'indeterminate'
			 el.indeterminate = true;
		  }
	   }
	});

 })


 $(document).scroll(function(e){
    var scrollTop = $(document).scrollTop();
    if(scrollTop > 0){
        console.log(scrollTop);
        $('.navbar').removeClass('navbar-static-top').addClass('navbar-fixed-top');
    } else {
        $('.navbar').removeClass('navbar-fixed-top').addClass('navbar-static-top');
    }
});	


</script>
	
<?php
include_once ('../footer.php');
?>
