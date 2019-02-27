<?php
$sub_menu = "100800";
include_once("./_common.php");

if ($is_admin != "super")
    alert("최고관리자만 접근 가능합니다.", G5_URL);

$g5['title'] = "세션파일 일괄삭제";
include_once("./admin.head.php");
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
		<h1 class="member_list_title">
		세션파일 일괄삭제
		<small>session file delete</small>
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
						<div class="local_desc02 local_desc">
							<p>
								완료 메세지가 나오기 전에 프로그램의 실행을 중지하지 마십시오.
							</p>
						</div>

							<?php
							flush();

							$list_tag_st = "";
							$list_tag_end = "";
							if (!$dir=@opendir(G5_DATA_PATH.'/session')) {
							  echo "<p>세션 디렉토리를 열지못했습니다.</p>";
							} else {
								$list_tag_st = "<ul class=\"session_del\">\n<li>완료됨</li>\n";
								$list_tag_end = "</ul>\n";
							}

							$cnt=0;
							echo $list_tag_st;
							while($file=readdir($dir)) {

								if (!strstr($file,'sess_')) continue;
								if (strpos($file,'sess_')!=0) continue;

								$session_file = G5_DATA_PATH.'/session/'.$file;

								if (!$atime=@fileatime($session_file)) {
									continue;
								}
								if (time() > $atime + (3600 * 6)) {  // 지난시간을 초로 계산해서 적어주시면 됩니다. default : 6시간전
									$cnt++;
									$return = unlink($session_file);
									//echo "<script>document.getElementById('ct').innerHTML += '{$session_file}<br/>';</script>\n";
									echo "<li>{$session_file}</li>\n";

									flush();

									if ($cnt%10==0)
										//echo "<script>document.getElementById('ct').innerHTML = '';</script>\n";
										echo "\n";
								}
							}
							echo $list_tag_end;
							echo '<div class="local_desc01 local_desc"><p><strong>세션데이터 '.$cnt.'건 삭제 완료됐습니다.</strong><br>프로그램의 실행을 끝마치셔도 좋습니다.</p></div>'.PHP_EOL;
						?>
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


<?php
include_once("./admin.tail.php");
?>
