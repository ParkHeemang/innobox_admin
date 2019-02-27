<?php
$sub_menu = '300820';
include_once('./_common.php');

check_demo();

auth_check($auth[$sub_menu], 'r');


$g5['title'] = '글,댓글 현황';
include_once ('./admin.head.php');

$period_array = array(
    '오늘'=>array('시간', 0),									//array(그래프단위($day),일수)
    '어제'=>array('시간', 0),
    '7일전'=>array('일', 7),
    '14일전'=>array('일', 14),
    '30일전'=>array('일', 30),
    '3개월전'=>array('주', 90),
    '6개월전'=>array('주', 180),
    '1년전'=>array('월', 365),
    '2년전'=>array('월', 365*2),
    '3년전'=>array('월', 365*3),
    '5년전'=>array('년', 365*5),
    '10년전'=>array('년', 365*10),
);

$is_period = false;
foreach($period_array as $key=>$value) {				
    if ($key == $period) {																			//select한 $period의 값이 $period_array의 배열에 있으면
        $is_period = true;																				//$is_period는 true
        break;
    }
}
if (!$is_period)											
    $period = '오늘';																							//$period의 default값 : 오늘

$day = $period_array[$period][0];															//$day : '시간','일','주','월','년'     그래프단위

$today = date('Y-m-d', G5_SERVER_TIME);											
$yesterday = date('Y-m-d', G5_SERVER_TIME - 86400);

if ($period == '오늘') {
    $from = $today;
    $to = $from;
} else if ($period == '어제') {
    $from = $yesterday;
    $to = $from;
} else if ($period == '내일') {
    $from = date('Y-m-d', G5_SERVER_TIME + (86400 * 2));
    $to = $from;
} else {
    $from = date('Y-m-d', G5_SERVER_TIME - (86400 * $period_array[$period][1]));
    $to = $yesterday;
}

$sql_bo_table = '';
if ($bo_table)
    $sql_bo_table = "and bo_table = '$bo_table'";

switch ($day) {
    case '시간' :
        $sql = " select substr(bn_datetime,6,8) as hours, sum(if(wr_id=wr_parent,1,0)) as wcount, sum(if(wr_id=wr_parent,0,1)) as ccount from {$g5['board_new_table']} where substr(bn_datetime,1,10) between '$from' and '$to' {$sql_bo_table} group by hours order by bn_datetime ";
        $result = sql_query($sql);
        for ($i=0; $row=sql_fetch_array($result); $i++) {
            // 월-일 시간
            $line1[] = "['".substr($row['hours'],0,8)."',".$row['wcount'].']';
            $line2[] = "['".substr($row['hours'],0,8)."',".$row['ccount'].']';
        }
        break;
    case '일' :
        $sql  = " select substr(bn_datetime,1,10) as days, sum(if(wr_id=wr_parent,1,0)) as wcount, sum(if(wr_id=wr_parent,0,1)) as ccount from {$g5['board_new_table']} where substr(bn_datetime,1,10) between '$from' and '$to' {$sql_bo_table} group by days order by bn_datetime ";
        $result = sql_query($sql);
        for ($i=0; $row=sql_fetch_array($result); $i++) {
            // 월-일
            $line1[] = "['".substr($row['days'],5,5)."',".$row['wcount'].']';
            $line2[] = "['".substr($row['days'],5,5)."',".$row['ccount'].']';
        }
        break;
    case '주' :
        $sql  = " select concat(substr(bn_datetime,1,4), '-', weekofyear(bn_datetime)) as weeks, sum(if(wr_id=wr_parent,1,0)) as wcount, sum(if(wr_id=wr_parent,0,1)) as ccount from {$g5['board_new_table']} where substr(bn_datetime,1,10) between '$from' and '$to' {$sql_bo_table} group by weeks order by bn_datetime ";
        $result = sql_query($sql);
        for ($i=0; $row=sql_fetch_array($result); $i++) {
            // 올해의 몇주로 보여주면 바로 확인이 안되므로 주를 날짜로 바꾼다.
            // 년-월-일
            list($lyear, $lweek) = explode('-', $row['weeks']);
            $date = date('y-m-d', strtotime($lyear.'W'.str_pad($lweek, 2, '0', STR_PAD_LEFT)));
            $line1[] = "['".$date."',".$row['wcount'].']';
            $line2[] = "['".$date."',".$row['ccount'].']';
        }
        break;
    case '월' :
        $sql  = " select substr(bn_datetime,1,7) as months, sum(if(wr_id=wr_parent,1,0)) as wcount, sum(if(wr_id=wr_parent,0,1)) as ccount from {$g5['board_new_table']} where substr(bn_datetime,1,10) between '$from' and '$to' {$sql_bo_table} group by months order by bn_datetime ";
        $result = sql_query($sql);
        for ($i=0; $row=sql_fetch_array($result); $i++) {
            // 년-월
            $line1[] = "['".substr($row['months'],2,5)."',".$row['wcount'].']';
            $line2[] = "['".substr($row['months'],2,5)."',".$row['ccount'].']';
        }
        break;
    case '년' :
        $sql  = " select substr(bn_datetime,1,4) as years, sum(if(wr_id=wr_parent,1,0)) as wcount, sum(if(wr_id=wr_parent,0,1)) as ccount from {$g5['board_new_table']} where substr(bn_datetime,1,10) between '$from' and '$to' {$sql_bo_table} group by years order by bn_datetime ";
        $result = sql_query($sql);
        for ($i=0; $row=sql_fetch_array($result); $i++) {
            // 년(4자리)
            $line1[] = "['".substr($row['years'],0,4)."',".$row['wcount'].']';
            $line2[] = "['".substr($row['years'],0,4)."',".$row['ccount'].']';
        }
        break;
}
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
		<h1 class="member_list_title">
		글,댓글 현황
		<small>write count</small>
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
					<div class="box-body">
						<div id="wr_cont">
							<form>
							<select name="bo_table" class="form-control">
							<option value="">전체게시판</option>
							<?php
							$sql = " select bo_table, bo_subject from {$g5['board_table']} order by bo_count_write desc ";
							$result = sql_query($sql);
							for($i=0; $row=sql_fetch_array($result); $i++) {
								echo "<option value=\"{$row['bo_table']}\"";
								if ($bo_table == $row['bo_table'])
									echo ' selected="selected"';
								echo ">{$row['bo_subject']}</option>\n";
							}
							?>
							</select>

							<select name="period" class="form-control">
							<?php
							foreach($period_array as $key=>$value) {
								echo "<option value=\"{$key}\"";
								if ($key == $period)
									echo " selected=\"selected\"";
								echo ">{$key}</option>\n";
							}
							?>
							</select>

							<select name="graph" class="form-control">
							<option value="line" <?php echo ($graph == 'line' ? 'selected="selected"' : ''); ?>>선그래프</option>
							<option value="bar" <?php echo ($graph == 'bar' ? 'selected="selected"' : ''); ?>>막대그래프</option>
							</select>

							<input type="submit" class="btn_submit btn btn-primary" value="확인">
							</form>
							<ul id="grp_color">
								<li><span style="background:rgba(60,141,188,0.8)"></span>글 수</li>
								<li class="color2"><span style="background:rgba(210, 214, 222, 1)"></span>댓글 수</li>
							</ul>
						</div>


						<!-- <br>
						line1
						<?php foreach($line1 as $key => $value){  echo "$key : $value".' / ';}?>
						<br>
						line2
						<?php foreach($line2 as $key => $value){  echo "$key : $value".' / ';}?>
						<br> -->
						<!-- 테스트 출력 -->
						<!-- <?php echo $line1[0]."//"?> -->
				<!-- 		<?php echo strpos($line1[0],",");?> -->
			<!-- 			<?php echo strpos($line1[0],",");?> -->


						
						<?php 

							
							$label_array = array();
							$data_array1 = array();
							$data_array2 = array();

							
							foreach($line1 as $key => $value){ 

							$token_idx = strpos($line1[$key],",");
							$end_idx = strpos($line1[$key],"]");
							
							$label_array[$key] = substr($line1[$key],2,$token_idx-3);
							$data_array1[$key] = substr($line1[$key],$token_idx+1,$end_idx-$token_idx-1);
							$data_array2[$key] = substr($line2[$key],$token_idx+1,$end_idx-$token_idx-1);
						
							
						//	echo "<br>";
						//	echo "token_idx : ".$token_idx." ";
						//	echo "end_idx : ".$end_idx." ";
						//	echo "label_array[key]: ".$label_array[$key]."\t".$data_array1[$key] ;
													}
						?>


					<!-- 	<?php $phm = substr($line1[0],2,$token_idx-3);?>
						<?php echo $phm?> -->
					<!-- 	<?php echo gettype($phm);?> -->
						<br>
				<!-- 		<?php echo $line1[0][$token_idx+1]?>
						<?php echo $line1[1][$token_idx+1]?> -->
						<div id="chart_wr">
							<?php
							if (empty($line1) || empty($line2)) {
								echo "<h5>그래프를 만들 데이터가 없습니다.</h5>\n";
							} else {
							?>
							<!-- <div id="chart1" style="height:500px; width:100%;"></div>
							<script>
							$(document).ready(function(){
								var line1 = [<?php echo implode($line1, ','); ?>];
								var line2 = [<?php echo implode($line2, ','); ?>];
								/*document.write(line1);
								document.write("<br>");
								document.write(line2);*/
								var plot1 = $.jqplot ('chart1', [line1, line2], {
										seriesDefaults: {
											<?php if ($graph == 'bar') { ?>
											renderer:$.jqplot.BarRenderer,
											<?php } ?>
											pointLabels: { show: true }
										},
										axes:{
											xaxis: {
												renderer: $.jqplot.CategoryAxisRenderer,
												label: '<?php echo $day; ?>',
												pad:0,
												max:23
											},
											yaxis: {
												label: '글수',
												min: 0
							
											}
										}
									});
							});
							</script> -->
															<!-- LINE CHART -->
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">Area Chart</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">

						<div class="chart">
							<canvas id="line-chart" width="800" height="450"></canvas>
							<script>
								new Chart(document.getElementById("line-chart"), {
								  type: 'line',
								  data: {
									labels: <?php echo json_encode($label_array)?>,
									datasets: [{ 
										data: <?php echo json_encode($data_array1)?>,
										label: "글 수",
										borderColor: "#3e95cd",
										fill: true
									  }, { 
										data: <?php echo json_encode($data_array2)?>,
										label: "댓글 수",
										borderColor: "#8e5ea2",
										fill: false
									  }
										/*, { 
										data: [168,170,178,190,203,276,408,547,675,734],
										label: "Europe",
										borderColor: "#3cba9f",
										fill: false
									  }, { 
										data: [40,20,10,16,24,38,74,167,508,784],
										label: "Latin America",
										borderColor: "#e8c3b9",
										fill: false
									  }, { 
										data: [6,3,2,2,7,26,82,172,312,433],
										label: "North America",
										borderColor: "#c45850",
										fill: false
									  }*/
									]
								  },
								  options: {
									title: {
									  display: true,
									  text: 'World population per region (in millions)'
									}
								  }
								});					
							</script>
							
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
							<?php
							}
							?>
						</div>


						<div>
						
						</div>


					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->



				<!-- BAR CHART -->
				<!-- <div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title">Bar Chart</h3>
				
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="chart">
							<canvas id="barChart" style="height: 295px; width: 1010px;" width="2020" height="590"></canvas>
						</div>
					</div>
					/.box-body
				</div> -->
				<!-- /.box -->


			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->		
	</section>
	<!-- /.content -->	
</div>
<!-- /.content-wrapper -->






<!-- Content Wrapper. Contains page content -->
<!-- <div class="content-wrapper">
	Content Header (Page header)    
	<section class="content-header">
		<h1 class="member_list_title">
		관리권환설정
		<small>auth list</small>
		</h1>
		<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">회원관리</a></li>
		<li class="active">회원정보리스트</li>
		</ol>
	</section>
	Main content	
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<nav class="navbar navbar-default navbtn">
							<div class="text-right">							
								<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn-default btn_02">					
							</div>
						</nav>
					</div>
					/.box-header
					<div class="box-body">
					


				




					
					</div>
					/.box-body
				</div>
				/.box
			</div>
			/.col
		</div>
		/.row		
	</section>
	/.content	
</div> -->
<!-- /.content-wrapper -->





<?php
include_once ('./admin.tail.php');
?>