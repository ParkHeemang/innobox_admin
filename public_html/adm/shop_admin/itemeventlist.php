<?php
$sub_menu = '500310';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$ev_id = preg_replace('/[^0-9]/', '', $ev_id);
$sort1 = strip_tags($sort1);
$sel_field = strip_tags($sel_field);
$sel_ca_id = get_search_string($sel_ca_id);
$search = get_search_string($search);

$g5['title'] = '이벤트일괄처리';
include_once ('../admin.head.php');

$where = " where ";
$sql_search = "";
if ($search != "") {
    if ($sel_field != "") {
        $sql_search .= " $where $sel_field like '%$search%' ";
        $where = " and ";
    }
}

if ($sel_ca_id != "") {
    $sql_search .= " $where ca_id like '$sel_ca_id%' ";
}

if ($sel_field == "")  {
    $sel_field = "it_name";
}

$sql_common = " from {$g5['g5_shop_item_table']} a
                left join {$g5['g5_shop_event_item_table']} b on (a.it_id=b.it_id and b.ev_id='$ev_id') ";
$sql_common .= $sql_search;

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

if (!$sort1) {
    $sort1 = "b.ev_id";
}

if (!$sort2 || $sort2 != "asc") {
    $sort2 = "desc";
}

$sql  = " select a.*, b.ev_id
          $sql_common
          order by $sort1 $sort2
          limit $from_record, $rows ";
$result = sql_query($sql);

//$qstr1 = 'sel_ca_id='.$sel_ca_id.'&amp;sel_field='.$sel_field.'&amp;search='.$search;
$qstr1 = 'ev_id='.$ev_id.'&amp;sel_ca_id='.$sel_ca_id.'&amp;sel_field='.$sel_field.'&amp;search='.$search;
$qstr  = $qstr1.'&amp;sort1='.$sort1.'&amp;sort2='.$sort2.'&amp;page='.$page;

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

// 이벤트제목
if($ev_id) {
    $tmp = sql_fetch(" select ev_subject from {$g5['g5_shop_event_table']} where ev_id = '$ev_id' ");
    $ev_title = $tmp['ev_subject'];
}

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
		이벤트일괄처리
		<small>itemevent list</small>
		</h1>
		<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">회원관리</a></li>
		<li class="active">회원정보리스트</li>
	  </ol>
	</section>
	<!-- Main content -->
	<form name="flist" class="local_sch01 local_sch" autocomplete="off">
	<section class="content">
	  <div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<nav class="navbar navbar-default navbtn" style="z-index: 1000000000000000000000000000000000">
						<div class="text-right">							
							<input type="submit" value="일괄수정" class="btn_submit btn btn-success" accesskey="s">						
						</div>
					</nav>
				</div>				
				<!-- /.box-header -->
				<div class="box-body">					
					<div class="local_ov01 local_ov">
						<?php echo $listall; ?>
						<span class="btn_ov01"><span class="ov_txt">전체 이벤트</span><span class="ov_num"> <?php echo $total_count; ?>건</span></span>  
					</div>

					<form name="flist" class="local_sch01 local_sch" autocomplete="off">
					<input type="hidden" name="page" value="<?php echo $page; ?>">
					<label for="ev_id" class="sound_only">이벤트</label>
					<select name="ev_id" id="ev_id" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" class="form-control">
						<?php
						// 이벤트 옵션처리
						$event_option = "<option value=''>이벤트를 선택하세요</option>";
						$sql1 = " select ev_id, ev_subject from {$g5['g5_shop_event_table']} order by ev_id desc ";
						$result1 = sql_query($sql1);
						while ($row1=sql_fetch_array($result1))
							$event_option .= '<option value="'.$row1['ev_id'].'" '.get_selected($ev_id, $row1['ev_id']).' >'.conv_subject($row1['ev_subject'], 20,"…").'</option>';
						echo $event_option;
						?>
					</select>
					<input type="submit" value="이동" class="btn_submit btn btn-default">

					</form>


					<input type="hidden" name="page" value="<?php echo $page; ?>">
					<input type="hidden" name="ev_id" value="<?php echo $ev_id; ?>">

					<label for="sel_ca_id" class="sound_only">분류선택</label>
					<select name="sel_ca_id" id="sel_ca_id" class="form-control">
						<option value=''>전체분류</option>
						<?php
						$sql1 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} order by ca_order, ca_id ";
						$result1 = sql_query($sql1);
						for ($i=0; $row1=sql_fetch_array($result1); $i++)
						{
							$len = strlen($row1['ca_id']) / 2 - 1;
							$nbsp = "";
							for ($i=0; $i<$len; $i++) $nbsp .= "&nbsp;&nbsp;&nbsp;";
							echo '<option value="'.$row1['ca_id'].'" '.get_selected($sel_ca_id, $row1['ca_id']).'>'.$nbsp.$row1['ca_name'].'</option>'.PHP_EOL;
						}
						?>
					</select>

					<label for="sel_field" class="sound_only">검색대상</label>
					<select name="sel_field" id="sel_field" class="form-control">
						<option value="it_name" <?php echo get_selected($sel_field, 'it_name'); ?>>상품명</option>
						<option value="a.it_id" <?php echo get_selected($sel_field, 'a.it_id'); ?>>상품코드</option>
					</select>

					<label for="search" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
					<input type="text" name="search" value="<?php echo $search; ?>" id="search" required class="form-control frm_input required">
					<input type="submit" value="검색" class="btn_submit btn btn-default">

					</form>

					<div class="local_desc01 local_desc">
						<p>상품을 이벤트별로 일괄 처리합니다. <?php echo ($ev_title ? '현재 선택된 이벤트는 '.$ev_title.'입니다.' : '이벤트를 선택해 주세요.'); ?></p>
					</div>

					<form name="fitemeventlistupdate" method="post" action="./itemeventlistupdate.php" onsubmit="return fitemeventlistupdatecheck(this)">
					<input type="hidden" name="ev_id" value="<?php echo $ev_id; ?>">
					<input type="hidden" name="sel_ca_id" value="<?php echo $sel_ca_id; ?>">
					<input type="hidden" name="sel_field" value="<?php echo $sel_field; ?>">
					<input type="hidden" name="search" value="<?php echo $search; ?>">
					<input type="hidden" name="page" value="<?php echo $page; ?>">
					<input type="hidden" name="sort1" value="<?php echo $sort1; ?>">
					<input type="hidden" name="sort2" value="<?php echo $sort2; ?>">

					<div class="tbl_head01 tbl_wrap">
						<table id="example1" class="table table-bordered table-striped dataTable member_list">
						<caption><?php echo $g5['title']; ?> 목록</caption>
						<thead>
						<tr>
							<th scope="col">이벤트</th>
							<th scope="col"><a href="<?php echo title_sort("a.it_id") . '&amp;'.$qstr1.'&amp;ev_id='.$ev_id; ?>">상품코드</a></th>
							<th scope="col"><a href="<?php echo title_sort("it_name") . '&&amp;'.$qstr1.'&amp;ev_id='.$ev_id; ?>">상품명</a></th>
						</tr>
						</thead>
						<tbody>
						<?php for ($i=0; $row=sql_fetch_array($result); $i++) {
							$href = G5_SHOP_URL.'/item.php?it_id='.$row['it_id'];

							$sql = " select ev_id from {$g5['g5_shop_event_item_table']}
									  where it_id = '{$row['it_id']}'
										and ev_id = '$ev_id' ";
							$ev = sql_fetch($sql);

							$bg = 'bg'.($i%2);
						?>

						<tr class="<?php echo $bg; ?>">
							<td class="td_chk2">
								<input type="hidden" name="it_id[<?php echo $i; ?>]" value="<?php echo $row['it_id']; ?>">
							<!--     <label for="ev_chk_<?php echo $i; ?>" class="sound_only">이벤트 사용</label> -->
								<input type="checkbox" name="ev_chk[<?php echo $i; ?>]" value="1" id="ev_chk_<?php echo $i; ?>" <?php echo ($row['ev_id'] ? "checked" : ""); ?>>
							</td>
							<td class="td_num"><a href="<?php echo $href; ?>"><?php echo $row['it_id']; ?></a></td>
							<td class="td_left"><a href="<?php echo $href; ?>"><?php echo get_it_image($row['it_id'], 50, 50); ?> <?php echo cut_str(stripslashes($row['it_name']), 60, "&#133"); ?></a></td>
						</tr>

						<?php
						}

						if ($i == 0)
							echo '<tr><td colspan="4" class="empty_table">자료가 없습니다.</td></tr>';
						?>
						</tbody>
						</table>
					</div>

					<div class="local_desc01 local_desc">
						<p>
							<?php if ($ev_title) { ?>
							 현재 선택된 이벤트는 <strong><?php echo $ev_title; ?></strong>입니다.<br>
							 선택된 이벤트의 상품 수정 내용을 반영하시려면 일괄수정 버튼을 누르십시오.
							<?php } else { ?>
							이벤트를 선택하지 않으셨습니다. <strong>수정 내용을 반영하기 전에 이벤트를 선택해주십시오.</strong><br>
							<a href="#ev_id" class="sound_only">이벤트 선택</a>
							<?php } ?>
						</p>
					</div>
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->
		</div>
		<!-- /.col -->
	  </div>
	  <!-- /.row -->
	</form>
	</section>
	<!-- /.content -->

</div>
<!-- /.content-wrapper -->

<script>
function fitemeventlistupdatecheck(f)
{
    if (!f.ev_id.value)
    {
        alert('이벤트를 선택하세요');
        return false;
    }

    return true;
}

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
	  'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : false,

      'info'        : true,
	  'language' : lang_kor,
      'autoWidth'   : false
	 



    });




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





</script>





<?php
include_once ('../footer.php');
?>