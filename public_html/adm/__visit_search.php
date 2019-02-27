<?php
$sub_menu = '200810';
include_once('./_common.php');
include_once(G5_PATH.'/lib/visit.lib.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = '접속자검색';
include_once('./admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$colspan = 6;
$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'">처음</a>'; //페이지 처음으로 (초기화용도)

//CSS
add_stylesheet('<link rel="stylesheet" href="'.G5_ADMIN_URL. '/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 4);

//JS DataTables
add_javascript('<script src="'.G5_ADMIN_URL.'/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_ADMIN_URL.'/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1 class="member_list_title">
		접속자검색<small>visit search</small>
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
				</div>
				<!-- /.box-header -->
				<div class="box-body visit_search">				
					<table id="example1" class="table table-bordered table-striped DataTable visit_search" width="100%">
						
					<thead>
					<tr>
						<th scope="col">IP</th>
						<th scope="col">접속 경로</th>
						<th scope="col">브라우저</th>
						<th scope="col">OS</th>
						<th scope="col">접속기기</th>
						<th scope="col">일시</th>
					</tr>
					</thead>
					<tbody>
					<?php
					$sql_common = " from {$g5['visit_table']} ";
					if ($sfl) {
						if($sfl=='vi_ip' || $sfl=='vi_date'){
							$sql_search = " where $sfl like '$stx%' ";
						}else{
							$sql_search = " where $sfl like '%$stx%' ";
						}
					}
					$sql = " select count(*) as cnt
								{$sql_common}
								{$sql_search} ";
					$row = sql_fetch($sql);
					$total_count = $row['cnt'];

					$rows = $config['cf_page_rows'];
					$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
					if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
					$from_record = ($page - 1) * $rows; // 시작 열을 구함

					$sql = " select *
								{$sql_common}
								{$sql_search}
								order by vi_id desc
								limit {$from_record}, {$rows} ";
					$result = sql_query($sql);

					for ($i=0; $row=sql_fetch_array($result); $i++) {
						$brow = $row['vi_browser'];
						if(!$brow)
							$brow = get_brow($row['vi_agent']);

						$os = $row['vi_os'];
						if(!$os)
							$os = get_os($row['vi_agent']);

						$device = $row['vi_device'];

						$link = "";
						$referer = "";
						$title = "";
						if ($row['vi_referer']) {

							$referer = get_text(cut_str($row['vi_referer'], 255, ""));
							$referer = urldecode($referer);

							if (!is_utf8($referer)) {
								$referer = iconv('euc-kr', 'utf-8', $referer);
							}

							$title = str_replace(array("<", ">"), array("&lt;", "&gt;"), $referer);
							$link = '<a href="'.$row['vi_referer'].'" target="_blank" title="'.$title.'">';
						}

						if ($is_admin == 'super')
							$ip = $row['vi_ip'];
						else
							$ip = preg_replace("/([0-9]+).([0-9]+).([0-9]+).([0-9]+)/", G5_IP_DISPLAY, $row['vi_ip']);

						$bg = 'bg'.($i%2);
					?>
					<tr class="<?php echo $bg; ?>">
						<td class="td_id"><a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?sfl=vi_ip&amp;stx=<?php echo $ip; ?>"><?php echo $ip; ?></a></td>
						<td class="td_left"><?php echo $link.$title; ?><?php echo $link ? '</a>' : ''; ?></td>
						<td class="td_idsmall td_category1"><?php echo $brow; ?></td>
						<td class="td_idsmall td_category3"><?php echo $os; ?></td>
						<td class="td_idsmall td_category2"><?php echo $device; ?></td>
						<td class="td_datetime"><a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?sfl=vi_date&amp;stx=<?php echo $row['vi_date']; ?>"><?php echo $row['vi_date']; ?></a> <?php echo $row['vi_time']; ?></td>
					</tr>
					<?php } ?>
					<?php if ($i == 0) echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>'; ?>
					</tbody>
					</table>
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


$(function(){
    $("#sch_sort").change(function(){ // select #sch_sort의 옵션이 바뀔때
        if($(this).val()=="vi_date"){ // 해당 value 값이 vi_date이면
            $("#sch_word").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" }); // datepicker 실행
        }else{ // 아니라면
            $("#sch_word").datepicker("destroy"); // datepicker 미실행
        }
    });

    if($("#sch_sort option:selected").val()=="vi_date"){ // select #sch_sort 의 옵션중 selected 된것의 값이 vi_date라면
        $("#sch_word").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" }); // datepicker 실행
    }
});

function fvisit_submit(f)
{
    return true;
}


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
      'lengthChange': false,
      'searching'   : true,
      'info'        : false,
	  'language' : lang_kor,
      'autoWidth'   : true,
	  "dom": '<"top"f>t<"col-sm-6"i><"col-sm-6"l><"col-sm-7"p><"clear">'

	 
	})







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

</script>	
	
    
    
<?php
    include_once('./admin.tail.php');
?>