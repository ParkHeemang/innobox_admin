<?php
$sub_menu = '500400';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '재입고SMS 알림';
include_once ('../admin.head.php');

// 테이블 생성
if(!isset($g5['g5_shop_item_stocksms_table']))
    die('<meta charset="utf-8">dbconfig.php 파일에 <strong>$g5[\'g5_shop_item_stocksms_table\'] = G5_SHOP_TABLE_PREFIX.\'item_stocksms\';</strong> 를 추가해 주세요.');

if(!sql_query(" select ss_id from {$g5['g5_shop_item_stocksms_table']} limit 1", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['g5_shop_item_stocksms_table']}` (
                  `ss_id` int(11) NOT NULL AUTO_INCREMENT,
                  `it_id` varchar(20) NOT NULL DEFAULT '',
                  `ss_hp` varchar(255) NOT NULL DEFAULT '',
                  `ss_send` tinyint(4) NOT NULL DEFAULT '0',
                  `ss_send_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                  `ss_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                  `ss_ip` varchar(25) NOT NULL DEFAULT '',
                  PRIMARY KEY (`ss_id`)
                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ", true);
}

$sql_search = " where 1 ";
if ($search != "") {
	if ($sel_field != "") {
    	$sql_search .= " and $sel_field like '%$search%' ";
    }
}

if ($sel_field == "")  $sel_field = "it_it";
if ($sort1 == "") $sort1 = "ss_send";
if ($sort2 == "" || $sort2 != "desc") $sort2 = "asc";

$doc = strip_tags($doc);
$sort1 = strip_tags($sort1);
$sel_field = strip_tags($sel_field);
$search = get_search_string($search);

$sql_common = "  from {$g5['g5_shop_item_stocksms_table']} ";

// 미전송 건수
$sql = " select count(*) as cnt " . $sql_common . " where ss_send = '0' ";
$row = sql_fetch($sql);
$unsend_count = $row['cnt'];

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
           $sql_search
          order by $sort1 $sort2
          limit $from_record, $rows ";
$result = sql_query($sql);

$qstr1 = 'sel_field='.$sel_field.'&amp;search='.$search;
$qstr = $qstr1.'&amp;sort1='.$sort1.'&amp;sort2='.$sort2.'&amp;page='.$page;

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';


//CSS
add_stylesheet('<link rel="stylesheet" href="'.G5_PHP_URL. 'bower_components/bootstrap/dist/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 1);
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL2.'/bootstrap.custom.css?ver='.G5_CSS_VER.'"></script>',2);

//JS DataTables
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 3);
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 4);

?>



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
		<h1 class="member_list_title">
		재입고SMS 알림

		<small>itemstocksms</small>
		</h1>
		<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">회원관리</a></li>
		<li class="active">회원정보리스트</li>
		</ol>
	</section>

	<form name="fitemstocksms" action="./itemstocksmsupdate.php" method="post" onsubmit="return fitemstocksms_submit(this);">

	<!-- Main content -->	
	<section class="content">		
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<nav class="navbar navbar-default navbtn" style="z-index: 1000000000000000000000000000000000">
							<div class="text-right">							
								<?php if ($is_admin == 'super') { ?>
								<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn-default btn_02">
								<?php } ?>
								<input type="submit" name="act_button" value="선택SMS전송" class="btn_submit btn btn-danger" onclick="document.pressed=this.value">						
							</div>
						</nav>
					</div>
					<!-- /.box-header -->
					<div class="box-body">			
						<input type="hidden" name="sort1" value="<?php echo $sort1; ?>">
						<input type="hidden" name="sort2" value="<?php echo $sort2; ?>">
						<input type="hidden" name="sel_field" value="<?php echo $sel_field; ?>">
						<input type="hidden" name="search" value="<?php echo $search; ?>">
						<input type="hidden" name="page" value="<?php echo $page; ?>">
						<div class="tbl_head01 tbl_wrap">
							<table id="example1" class="table table-bordered table-striped dataTable member_list">
								<!-- <caption><?php echo $g5['title']; ?> 목록</caption> -->
								<thead>
								<tr>
									<th scope="col">
										<!-- <label for="chkall" class="sound_only">알림요청 전체</label> -->
										<input type="checkbox" name="chkall" value="1" id="example-select-all" onclick="check_all(this.form)">
									</th>
									<th scope="col">상품명</th>
									<th scope="col">휴대폰번호</th>
									<th scope="col">SMS전송</th>
									<th scope="col">SMS전송일시</th>
									<th scope="col">등록일시</th>
								</tr>
								</thead>
								<tbody>
								<?php
								for ($i=0; $row=sql_fetch_array($result); $i++)
								{
									// 상품정보
									$sql = " select it_name from {$g5['g5_shop_item_table']} where it_id = '{$row['it_id']}' ";
									$it = sql_fetch($sql);

									if($it['it_name'])
										$it_name = get_text($it['it_name']);
									else
										$it_name = '상품정보 없음';

									$bg = 'bg'.($i%2);

								?>
								<tr class="<?php echo $bg; ?>">
									<td class="td_chk">
										<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $it_name; ?> 알림요청</label>
										<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i; ?>">
										<input type="hidden" name="ss_id[<?php echo $i; ?>]" value="<?php echo $row['ss_id']; ?>">
									</td>
									<td class="td_left"><?php echo $it_name; ?></td>
									<td class="td_telbig"><?php echo $row['ss_hp']; ?></td>
									<td class="td_stat"><?php echo ($row['ss_send'] ? '전송완료' : '전송전'); ?></td>
									<td class="td_datetime"><?php echo (is_null_time($row['ss_send_time']) ? '' : $row['ss_send_time']); ?></td>
									<td class="td_datetime"><?php echo (is_null_time($row['ss_datetime']) ? '' : $row['ss_datetime']); ?></td>
								</tr>
								<?php
								}
								if (!$i)
									echo '<tr><td colspan="6" class="empty_table"><span>자료가 없습니다.</span></td></tr>';
								?>
								</tbody>
							</table>
						</div>
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
	
	</form>
</div>
<!-- /.content-wrapper -->

<script>

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
      'searching'   : true,
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

function fitemstocksms_submit(f)
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
</script>


<?php
include_once ('../footer.php');
?>