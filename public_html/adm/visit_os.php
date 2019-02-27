<?php
$sub_menu = "200800";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = 'OS별 접속자집계';
include_once('./visit.sub.php');

$colspan = 5;

$max = 0;
$sum_count = 0;
$sql = " select * from {$g5['visit_table']}
          where vi_date between '$fr_date' and '$to_date' ";
$result = sql_query($sql);
while ($row=sql_fetch_array($result)) {
    $s = $row['vi_os'];
    if(!$s)
        $s = get_os($row['vi_agent']);

    $arr[$s]++;

    if ($arr[$s] > $max) $max = $arr[$s];

    $sum_count++;
}
?>


						<div class="col-md-12 visit_list">
							<!-- Custom Tabs -->
							<div class="nav-tabs-custom">
								<ul class="nav nav-tabs">
								<li class=""><a href="./visit_list.php<?php echo $query_string ?>">접속자</a></li>
								<li class=""><a href="./visit_domain.php<?php echo $query_string ?>">도메인</a></li>	
								<li class=""><a href="./visit_browser.php<?php echo $query_string ?>">브라우저</a></li>	
								<li class="active"><a href="./visit_os.php<?php echo $query_string ?>">운영체제</a></li>	
								<li class=""><a href="./visit_device.php<?php echo $query_string ?>">접속기기</a></li>	
								<li class=""><a href="./visit_hour.php<?php echo $query_string ?>">시간</a></li>	
								<li class=""><a href="./visit_week.php<?php echo $query_string ?>">요일</a></li>	
								<li class=""><a href="./visit_date.php<?php echo $query_string ?>">일</a></li>
								<li class=""><a href="./visit_month.php<?php echo $query_string ?>">월</a></li>	
								<li class=""><a href="./visit_year.php<?php echo $query_string ?>">년</a></li>	
								<li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
								</ul>									
								<table id="example1" class="table table-bordered table-hover dataTable configform">
									<!-- <caption><?php echo $g5['title']; ?> 목록</caption> -->
									<thead>
									<tr>
										<th scope="col">순위</th>
										<th scope="col">OS</th>
										<th scope="col">그래프</th>
										<th scope="col">접속자수</th>
										<th scope="col">비율(%)</th>
									</tr>
									</thead>
									<tfoot>
									<tr>
										<td colspan="3">합계</td>
										<td><strong><?php echo $sum_count ?></strong></td>
										<td>100%</td>
									</tr>
									</tfoot>
									<tbody>
									<?php
									$i = 0;
									$k = 0;
									$save_count = -1;
									$tot_count = 0;
									if (count($arr)) {
										arsort($arr);
										foreach ($arr as $key=>$value) {
											$count = $arr[$key];
											if ($save_count != $count) {
												$i++;
												$no = $i;
												$save_count = $count;
											} else {
												$no = '';
											}

											if (!$key) {
												$key = 'Unknown';
											}

											$rate = ($count / $sum_count * 100);
											$s_rate = number_format($rate, 1);

											$bg = 'bg'.($i%2);
									?>

									<tr class="<?php echo $bg; ?>">
										<td class="td_num"><?php echo $no ?></td>
										<td class="td_category"><?php echo $key ?></td>
										<td>
											<div class="visit_bar">
												<span style="width:<?php echo $s_rate ?>%"></span>
											</div>
										</td>
										<td class="td_num_c3"><?php echo $count ?></td>
										<td class="td_num"><?php echo $s_rate ?></td>
									</tr>

									<?php
										}
									} else {
										echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
									}
									?>
									</tbody>
								</table>										
							</div>
							<!-- nav-tabs-custom -->
						</div>
						<!-- /.col-md-12 -->
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
$('table').attr( "width","100%" );
$(function () {

	// Korean
	var lang_kor = {
		"decimal" : "",
		"emptyTable" : "데이터가 없습니다.",
		"info" : "_START_ - _END_ (총 _TOTAL_ 개)",
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
	  'paging'      : false,
	  'lengthChange': false,
	  'searching'   : false,
	  'ordering'    : false,
	  'info'        : true,
	  'language' : lang_kor,
	  'autoWidth'   : true
	//$('#division1').removeClass('sorting sorting_asc sorting_desc');	  
	 // [{ 'targets': [0,5,10,11], 'orderable': false }, 	  ]
	});

})
</script>
<?php
include_once('./admin.tail.php');
?>